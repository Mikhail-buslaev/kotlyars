<?php
class ModelExtensionModuleKotlyarsCatalogFoundation extends Model {
	const URL_ATTRIBUTE_PREFIX = 'kvs_';
	const URL_RANGE_PREFIX = 'kvr_';

	protected function getRegistry() {
		$this->load->config('kotlyars_catalog');

		return array(
			'leaves'          => (array)$this->config->get('kotlyars_catalog_leaf_registry'),
			'attributes'      => (array)$this->config->get('kotlyars_catalog_attribute_definitions'),
			'ranges'          => (array)$this->config->get('kotlyars_catalog_range_definitions'),
			'leaf_attributes' => (array)$this->config->get('kotlyars_catalog_leaf_attribute_map'),
			'leaf_ranges'     => (array)$this->config->get('kotlyars_catalog_leaf_range_map')
		);
	}

	public function getCategoryIdFromPath($path = null) {
		if ($path === null) {
			$path = isset($this->request->get['path']) ? $this->request->get['path'] : '';
		}

		if ($path === '') {
			return 0;
		}

		$parts = explode('_', (string)$path);

		return (int)array_pop($parts);
	}

	public function getLeafCodeByCategoryId($category_id) {
		$query = $this->db->query("SELECT `leaf_code` FROM `" . DB_PREFIX . "kotlyars_catalog_category_map` WHERE `category_id` = '" . (int)$category_id . "' ORDER BY `is_primary` DESC, `leaf_code` ASC LIMIT 1");

		return $query->num_rows ? $query->row['leaf_code'] : '';
	}

	public function getCategoryFilterContext($category_id = 0) {
		$registry = $this->getRegistry();
		$category_id = (int)$category_id;

		if (!$category_id) {
			$category_id = $this->getCategoryIdFromPath();
		}

		$leaf_code = $this->getLeafCodeByCategoryId($category_id);
		$leaf_attribute_rules = $this->getLeafAttributeRules($leaf_code, $registry['leaf_attributes']);
		$leaf_range_rules = $this->getLeafRangeRules($leaf_code, $registry['leaf_ranges']);

		$context = array(
			'category_id'      => $category_id,
			'leaf_code'        => $leaf_code,
			'is_leaf'          => isset($registry['leaves'][$leaf_code]),
			'leaf'             => isset($registry['leaves'][$leaf_code]) ? $registry['leaves'][$leaf_code] : array(),
			'attribute_rules'  => $leaf_attribute_rules,
			'range_rules'      => $leaf_range_rules,
			'active_filters'   => array(
				'attributes'  => array(),
				'ranges'      => array(),
				'has_filters' => false
			)
		);

		if ($context['is_leaf']) {
			$context['active_filters'] = $this->parseRequestFilterState($context);
		}

		return $context;
	}

	public function buildActiveFilterQueryString($context = array(), array $state = null) {
		$context = $this->normalizeFilterContext($context);

		if (!$context['is_leaf']) {
			return '';
		}

		if ($state === null) {
			$state = $context['active_filters'];
		}

		$params = $this->buildFilterQueryParameters($context, $state);

		if (!$params) {
			return '';
		}

		return '&' . http_build_query($params, '', '&');
	}

	public function getLeafCategoryProductTotal($category_id, $context = array()) {
		$context = $this->normalizeFilterContext($context, $category_id);

		if (!$context['is_leaf']) {
			return 0;
		}

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
		$sql .= $this->buildLeafCategoryMatchSql($context, $context['active_filters'], true);

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function getLeafCategoryProducts($category_id, $context = array(), $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 20) {
		$context = $this->normalizeFilterContext($context, $category_id);

		if (!$context['is_leaf']) {
			return array();
		}

		$sql = "SELECT p.product_id,
			p.image,
			p.tax_class_id,
			p.minimum,
			p.model,
			p.quantity,
			p.sort_order,
			p.date_added,
			p.price AS base_price,
			pd.name AS name,
			pd.description,
			(SELECT AVG(rating) AS total FROM `" . DB_PREFIX . "review` r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,
			" . $this->getDiscountSelectSql() . " AS discount,
			" . $this->getSpecialSelectSql() . " AS special";
		$sql .= $this->buildLeafCategoryMatchSql($context, $context['active_filters']);
		$sql .= " GROUP BY p.product_id";
		$sql .= $this->buildSortSql($sort, $order);
		$sql .= $this->buildLimitSql($start, $limit);

		$product_data = array();
		$query = $this->db->query($sql);

		foreach ($query->rows as $row) {
			$product_data[$row['product_id']] = array(
				'product_id'      => (int)$row['product_id'],
				'name'            => $row['name'],
				'description'     => $row['description'],
				'model'           => $row['model'],
				'quantity'        => $row['quantity'],
				'image'           => $row['image'],
				'price'           => ($row['discount'] !== null ? $row['discount'] : $row['base_price']),
				'special'         => $row['special'],
				'tax_class_id'    => (int)$row['tax_class_id'],
				'minimum'         => (int)$row['minimum'],
				'rating'          => round(($row['rating'] === null) ? 0 : $row['rating']),
				'sort_order'      => (int)$row['sort_order'],
				'date_added'      => $row['date_added']
			);
		}

		return $product_data;
	}

	public function getLeafFilterBlockData($category_id) {
		$context = $this->getCategoryFilterContext($category_id);

		if (!$context['is_leaf']) {
			return array();
		}

		$attribute_groups = array();

		foreach ($context['attribute_rules'] as $attribute_code => $rule) {
			$options = array();
			$selected_values = isset($context['active_filters']['attributes'][$attribute_code]) ? $context['active_filters']['attributes'][$attribute_code] : array();

			foreach ($rule['options'] as $value_key => $label) {
				$count = $this->getLeafAttributeOptionCount($context, $attribute_code, $value_key);
				$selected = in_array($value_key, $selected_values);

				$options[] = array(
					'key'      => $value_key,
					'label'    => $label,
					'count'    => $count,
					'selected' => $selected,
					'disabled' => !$selected && $count < 1
				);
			}

			$attribute_groups[] = array(
				'code'    => $attribute_code,
				'label'   => $this->getAttributeLabel($attribute_code),
				'name'    => self::URL_ATTRIBUTE_PREFIX . $attribute_code,
				'options' => $options
			);
		}

		$range_groups = array();

		foreach ($context['range_rules'] as $range_code => $rule) {
			$selected_range = isset($context['active_filters']['ranges'][$range_code]) ? $context['active_filters']['ranges'][$range_code] : array();
			$available_range = $this->getLeafRangeAvailability($context, $range_code);
			$range_groups[] = array(
				'code'          => $range_code,
				'label'         => $this->getRangeLabel($range_code),
				'input_min'     => self::URL_RANGE_PREFIX . $range_code . '_min',
				'input_max'     => self::URL_RANGE_PREFIX . $range_code . '_max',
				'selected_min'  => isset($selected_range['min']) ? $this->formatFilterNumber($selected_range['min']) : '',
				'selected_max'  => isset($selected_range['max']) ? $this->formatFilterNumber($selected_range['max']) : '',
				'min'           => $this->formatFilterNumber($rule['min']),
				'max'           => $this->formatFilterNumber($rule['max']),
				'available_min' => $available_range['min'],
				'available_max' => $available_range['max'],
				'unit'          => isset($rule['unit']) ? $rule['unit'] : '',
				'currency'      => isset($rule['currency']) ? $rule['currency'] : ''
			);
		}

		return array(
			'leaf_code'           => $context['leaf_code'],
			'leaf_label'          => implode(' > ', $context['leaf']['path']),
			'has_active_filters'  => $context['active_filters']['has_filters'],
			'attribute_groups'    => $attribute_groups,
			'range_groups'        => $range_groups,
			'current_sort'        => isset($this->request->get['sort']) ? $this->request->get['sort'] : '',
			'current_order'       => isset($this->request->get['order']) ? $this->request->get['order'] : '',
			'current_limit'       => isset($this->request->get['limit']) ? $this->request->get['limit'] : '',
			'action'              => $this->url->link('product/category', 'path=' . (string)$this->request->get['path']),
			'reset'               => $this->buildFilterResetUrl(),
			'filter_query_string' => $this->buildActiveFilterQueryString($context)
		);
	}

	public function getProductFoundation($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . (int)$product_id . "'");

		$foundation = array(
			'leaf_code'  => '',
			'attributes' => array(),
			'ranges'     => array()
		);

		if ($query->num_rows) {
			$foundation['leaf_code'] = $query->row['leaf_code'];
		}

		$enum_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_enum` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($enum_query->rows as $row) {
			$foundation['attributes'][$row['attribute_code']] = $row['value_key'];
		}

		$range_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($range_query->rows as $row) {
			$foundation['ranges'][$row['range_code']] = (float)$row['value_decimal'];
		}

		return $foundation;
	}

	public function augmentProductInfo(array $product_info) {
		if (empty($product_info['product_id'])) {
			return $product_info;
		}

		$registry = $this->getRegistry();
		$foundation = $this->getProductFoundation($product_info['product_id']);
		$leaf = isset($registry['leaves'][$foundation['leaf_code']]) ? $registry['leaves'][$foundation['leaf_code']] : array();

		$product_info['governance_leaf_code'] = $foundation['leaf_code'];
		$product_info['governance_leaf_label'] = isset($leaf['label']) ? $leaf['label'] : '';
		$product_info['catalog_contract'] = array(
			'leaf_code'         => $foundation['leaf_code'],
			'leaf_path'         => isset($leaf['path']) ? $leaf['path'] : array(),
			'filterable_values' => $this->buildFilterableValues($product_info, $foundation, $registry),
			'range_values'      => $this->buildRangeValues($product_info, $foundation, $registry)
		);

		return $product_info;
	}

	protected function buildFilterableValues(array $product_info, array $foundation, array $registry) {
		$values = array();

		if (empty($registry['leaf_attributes'][$foundation['leaf_code']])) {
			return $values;
		}

		foreach ($registry['leaf_attributes'][$foundation['leaf_code']] as $rule) {
			$attribute_code = $rule['code'];
			$value_key = isset($foundation['attributes'][$attribute_code]) ? $foundation['attributes'][$attribute_code] : null;

			if ($value_key === null || !isset($rule['options'][$value_key])) {
				$values[$attribute_code] = null;
				continue;
			}

			$values[$attribute_code] = array(
				'key' => $value_key,
				'label' => $rule['options'][$value_key]
			);
		}

		return $values;
	}

	protected function buildRangeValues(array $product_info, array $foundation, array $registry) {
		$values = array();

		if (empty($registry['leaf_ranges'][$foundation['leaf_code']])) {
			return $values;
		}

		foreach ($registry['leaf_ranges'][$foundation['leaf_code']] as $rule) {
			$range_code = $rule['code'];
			$definition = $registry['ranges'][$range_code];

			if ($definition['storage'] === 'core') {
				$current_value = isset($product_info[$definition['core_field']]) ? $product_info[$definition['core_field']] : null;
			} else {
				$current_value = isset($foundation['ranges'][$range_code]) ? $foundation['ranges'][$range_code] : null;
			}

			$values[$range_code] = array(
				'value' => $current_value,
				'min'   => $rule['min'],
				'max'   => $rule['max']
			);

			if (isset($rule['unit'])) {
				$values[$range_code]['unit'] = $rule['unit'];
			}

			if (isset($rule['currency'])) {
				$values[$range_code]['currency'] = $rule['currency'];
			}
		}

		return $values;
	}

	protected function normalizeFilterContext($context = array(), $category_id = 0) {
		if (!is_array($context) || !isset($context['is_leaf'])) {
			return $this->getCategoryFilterContext((int)$category_id ?: (int)$context);
		}

		return $context;
	}

	protected function buildFilterResetUrl() {
		$query = array();

		if (!empty($this->request->get['sort'])) {
			$query['sort'] = $this->request->get['sort'];
		}

		if (!empty($this->request->get['order'])) {
			$query['order'] = $this->request->get['order'];
		}

		if (!empty($this->request->get['limit'])) {
			$query['limit'] = $this->request->get['limit'];
		}

		return $this->url->link('product/category', 'path=' . (string)$this->request->get['path'] . ($query ? '&' . http_build_query($query, '', '&') : ''));
	}

	protected function getLeafAttributeRules($leaf_code, array $leaf_attribute_map) {
		$rules = array();

		if (empty($leaf_attribute_map[$leaf_code])) {
			return $rules;
		}

		foreach ($leaf_attribute_map[$leaf_code] as $rule) {
			$rules[$rule['code']] = $rule;
		}

		return $rules;
	}

	protected function getLeafRangeRules($leaf_code, array $leaf_range_map) {
		$rules = array();

		if (empty($leaf_range_map[$leaf_code])) {
			return $rules;
		}

		foreach ($leaf_range_map[$leaf_code] as $rule) {
			$rules[$rule['code']] = $rule;
		}

		return $rules;
	}

	protected function parseRequestFilterState(array $context) {
		$state = array(
			'attributes'  => array(),
			'ranges'      => array(),
			'has_filters' => false
		);

		foreach ($context['attribute_rules'] as $attribute_code => $rule) {
			$request_key = self::URL_ATTRIBUTE_PREFIX . $attribute_code;

			if (!isset($this->request->get[$request_key]) || $this->request->get[$request_key] === '') {
				continue;
			}

			$requested_values = array_map('trim', explode(',', (string)$this->request->get[$request_key]));
			$requested_values = array_unique(array_filter($requested_values, 'strlen'));
			$selected_values = array();

			foreach ($rule['options'] as $value_key => $label) {
				if (in_array($value_key, $requested_values)) {
					$selected_values[] = $value_key;
				}
			}

			if ($selected_values) {
				$state['attributes'][$attribute_code] = $selected_values;
			}
		}

		foreach ($context['range_rules'] as $range_code => $rule) {
			$request_min = self::URL_RANGE_PREFIX . $range_code . '_min';
			$request_max = self::URL_RANGE_PREFIX . $range_code . '_max';
			$range_state = array();

			if (isset($this->request->get[$request_min]) && $this->request->get[$request_min] !== '' && is_numeric($this->request->get[$request_min])) {
				$range_state['min'] = max((float)$rule['min'], (float)$this->request->get[$request_min]);
			}

			if (isset($this->request->get[$request_max]) && $this->request->get[$request_max] !== '' && is_numeric($this->request->get[$request_max])) {
				$range_state['max'] = min((float)$rule['max'], (float)$this->request->get[$request_max]);
			}

			if (isset($range_state['min']) && isset($range_state['max']) && $range_state['min'] > $range_state['max']) {
				$range_state = array();
			}

			if ($range_state) {
				$state['ranges'][$range_code] = $range_state;
			}
		}

		$state['has_filters'] = (bool)($state['attributes'] || $state['ranges']);

		return $state;
	}

	protected function buildFilterQueryParameters(array $context, array $state) {
		$params = array();

		foreach ($context['attribute_rules'] as $attribute_code => $rule) {
			if (!empty($state['attributes'][$attribute_code])) {
				$params[self::URL_ATTRIBUTE_PREFIX . $attribute_code] = implode(',', $state['attributes'][$attribute_code]);
			}
		}

		foreach ($context['range_rules'] as $range_code => $rule) {
			if (isset($state['ranges'][$range_code]['min'])) {
				$params[self::URL_RANGE_PREFIX . $range_code . '_min'] = $this->formatFilterNumber($state['ranges'][$range_code]['min']);
			}

			if (isset($state['ranges'][$range_code]['max'])) {
				$params[self::URL_RANGE_PREFIX . $range_code . '_max'] = $this->formatFilterNumber($state['ranges'][$range_code]['max']);
			}
		}

		return $params;
	}

	protected function buildLeafCategoryBaseSql(array $context, $for_total = false) {
		$sql = " FROM `" . DB_PREFIX . "product` p";
		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p.product_id = p2c.product_id)";
		$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)";
		$sql .= " LEFT JOIN `" . DB_PREFIX . "kotlyars_catalog_product` kcp ON (p.product_id = kcp.product_id)";

		if (!$for_total) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id)";
		}

		$sql .= " WHERE p.status = '1' AND p.date_available <= NOW()";
		$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		$sql .= " AND p2c.category_id = '" . (int)$context['category_id'] . "'";
		$sql .= " AND kcp.leaf_code = '" . $this->db->escape($context['leaf_code']) . "'";

		if (!$for_total) {
			$sql .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}

		return $sql;
	}

	protected function buildLeafCategoryMatchSql(array $context, array $state, $for_total = false, array $options = array()) {
		$sql = $this->buildLeafCategoryBaseSql($context, $for_total);
		$sql .= $this->buildVendorFilterConditions($context, $state, $options);

		return $sql;
	}

	protected function buildVendorFilterConditions(array $context, array $state, array $options = array()) {
		$conditions = '';
		$exclude_attribute_code = isset($options['exclude_attribute_code']) ? $options['exclude_attribute_code'] : '';
		$candidate_attribute_code = isset($options['candidate_attribute_code']) ? $options['candidate_attribute_code'] : '';
		$candidate_value_key = isset($options['candidate_value_key']) ? $options['candidate_value_key'] : '';
		$exclude_range_code = isset($options['exclude_range_code']) ? $options['exclude_range_code'] : '';

		foreach ($context['attribute_rules'] as $attribute_code => $rule) {
			if ($candidate_attribute_code === $attribute_code && $candidate_value_key !== '') {
				$selected_values = array($candidate_value_key);
			} elseif ($exclude_attribute_code === $attribute_code) {
				continue;
			} else {
				$selected_values = !empty($state['attributes'][$attribute_code]) ? $state['attributes'][$attribute_code] : array();
			}

			if (!$selected_values) {
				continue;
			}

			$escaped_values = array();

			foreach ($selected_values as $selected_value) {
				$escaped_values[] = "'" . $this->db->escape($selected_value) . "'";
			}

			$conditions .= " AND EXISTS (SELECT 1 FROM `" . DB_PREFIX . "kotlyars_catalog_product_enum` kpe WHERE kpe.product_id = p.product_id AND kpe.attribute_code = '" . $this->db->escape($attribute_code) . "' AND kpe.value_key IN (" . implode(',', $escaped_values) . "))";
		}

		foreach ($context['range_rules'] as $range_code => $rule) {
			if ($exclude_range_code === $range_code || empty($state['ranges'][$range_code])) {
				continue;
			}

			$range_state = $state['ranges'][$range_code];
			$range_definition = $this->getRegistry()['ranges'][$range_code];

			if ($range_definition['storage'] === 'core') {
				$expression = $range_code === 'price' ? $this->getEffectivePriceExpression() : 'p.`' . $range_definition['core_field'] . '`';

				if (isset($range_state['min'])) {
					$conditions .= " AND " . $expression . " >= '" . (float)$range_state['min'] . "'";
				}

				if (isset($range_state['max'])) {
					$conditions .= " AND " . $expression . " <= '" . (float)$range_state['max'] . "'";
				}
			} else {
				$conditions .= " AND EXISTS (SELECT 1 FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` kpr WHERE kpr.product_id = p.product_id AND kpr.range_code = '" . $this->db->escape($range_code) . "'";

				if (isset($range_state['min'])) {
					$conditions .= " AND kpr.value_decimal >= '" . (float)$range_state['min'] . "'";
				}

				if (isset($range_state['max'])) {
					$conditions .= " AND kpr.value_decimal <= '" . (float)$range_state['max'] . "'";
				}

				$conditions .= ")";
			}
		}

		return $conditions;
	}

	protected function getLeafAttributeOptionCount(array $context, $attribute_code, $value_key) {
		if (empty($context['attribute_rules'][$attribute_code]['options'][$value_key])) {
			return 0;
		}

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
		$sql .= $this->buildLeafCategoryMatchSql($context, $context['active_filters'], true, array(
			'exclude_attribute_code'   => $attribute_code,
			'candidate_attribute_code' => $attribute_code,
			'candidate_value_key'      => $value_key
		));

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	protected function getLeafRangeAvailability(array $context, $range_code) {
		$rule = isset($context['range_rules'][$range_code]) ? $context['range_rules'][$range_code] : array();
		$available = array(
			'min' => isset($rule['min']) ? $this->formatFilterNumber($rule['min']) : '',
			'max' => isset($rule['max']) ? $this->formatFilterNumber($rule['max']) : ''
		);

		if (!$rule) {
			return $available;
		}

		$range_definition = $this->getRegistry()['ranges'][$range_code];

		if ($range_definition['storage'] === 'core') {
			$expression = $range_code === 'price' ? $this->getEffectivePriceExpression() : 'p.`' . $range_definition['core_field'] . '`';
			$sql = "SELECT MIN(" . $expression . ") AS min_value, MAX(" . $expression . ") AS max_value";
			$sql .= $this->buildLeafCategoryMatchSql($context, $context['active_filters'], true, array(
				'exclude_range_code' => $range_code
			));
		} else {
			$expression = "(SELECT kpr.value_decimal FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` kpr WHERE kpr.product_id = p.product_id AND kpr.range_code = '" . $this->db->escape($range_code) . "' LIMIT 1)";
			$sql = "SELECT MIN(" . $expression . ") AS min_value, MAX(" . $expression . ") AS max_value";
			$sql .= $this->buildLeafCategoryMatchSql($context, $context['active_filters'], true, array(
				'exclude_range_code' => $range_code
			));
			$sql .= " AND EXISTS (SELECT 1 FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` kpr WHERE kpr.product_id = p.product_id AND kpr.range_code = '" . $this->db->escape($range_code) . "')";
		}

		$query = $this->db->query($sql);

		if ($query->row['min_value'] !== null) {
			$available['min'] = $this->formatFilterNumber(max((float)$rule['min'], (float)$query->row['min_value']));
		}

		if ($query->row['max_value'] !== null) {
			$available['max'] = $this->formatFilterNumber(min((float)$rule['max'], (float)$query->row['max_value']));
		}

		return $available;
	}

	protected function getDiscountSelectSql() {
		return "(SELECT price FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)";
	}

	protected function getSpecialSelectSql() {
		return "(SELECT price FROM `" . DB_PREFIX . "product_special` ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)";
	}

	protected function getEffectivePriceExpression() {
		return "COALESCE(" . $this->getSpecialSelectSql() . ", " . $this->getDiscountSelectSql() . ", p.price)";
	}

	protected function buildSortSql($sort, $order) {
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		$sql = '';

		if (in_array($sort, $sort_data)) {
			if ($sort === 'pd.name' || $sort === 'p.model') {
				$sql .= " ORDER BY LCASE(" . $sort . ")";
			} elseif ($sort === 'p.price') {
				$sql .= " ORDER BY " . $this->getEffectivePriceExpression();
			} else {
				$sql .= " ORDER BY " . $sort;
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if ($order === 'DESC') {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		return $sql;
	}

	protected function buildLimitSql($start, $limit) {
		$start = (int)$start;
		$limit = (int)$limit;

		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		return " LIMIT " . $start . "," . $limit;
	}

	protected function getAttributeLabel($attribute_code) {
		$registry = $this->getRegistry();

		return isset($registry['attributes'][$attribute_code]['label']) ? $registry['attributes'][$attribute_code]['label'] : $attribute_code;
	}

	protected function getRangeLabel($range_code) {
		$registry = $this->getRegistry();

		return isset($registry['ranges'][$range_code]['label']) ? $registry['ranges'][$range_code]['label'] : $range_code;
	}

	protected function formatFilterNumber($value) {
		if ($value === '' || $value === null) {
			return '';
		}

		$formatted = number_format((float)$value, 4, '.', '');

		return rtrim(rtrim($formatted, '0'), '.');
	}
}
