<?php
class ModelExtensionModuleKotlyarsCatalogFoundation extends Model {
	const MODIFICATION_CODE = 'kotlyars_catalog_foundation';

	protected function getRegistry() {
		$this->load->config('kotlyars_catalog');

		return array(
			'leaves'                    => (array)$this->config->get('kotlyars_catalog_leaf_registry'),
			'native_category_nodes'     => (array)$this->config->get('kotlyars_catalog_native_category_nodes'),
			'attributes'                => (array)$this->config->get('kotlyars_catalog_attribute_definitions'),
			'native_attribute_groups'   => (array)$this->config->get('kotlyars_catalog_native_attribute_group_registry'),
			'native_attributes'         => (array)$this->config->get('kotlyars_catalog_native_attribute_registry'),
			'ranges'                    => (array)$this->config->get('kotlyars_catalog_range_definitions'),
			'leaf_attributes'           => (array)$this->config->get('kotlyars_catalog_leaf_attribute_map'),
			'leaf_ranges'               => (array)$this->config->get('kotlyars_catalog_leaf_range_map')
		);
	}

	public function ensureSchemaUpgrades() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_product` (
			`product_id` INT(11) NOT NULL,
			`leaf_code` VARCHAR(128) NOT NULL,
			`date_added` DATETIME NOT NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`product_id`),
			KEY `leaf_code` (`leaf_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_enum` (
			`product_id` INT(11) NOT NULL,
			`attribute_code` VARCHAR(64) NOT NULL,
			`value_key` VARCHAR(128) NOT NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`product_id`, `attribute_code`),
			KEY `attribute_code` (`attribute_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_range` (
			`product_id` INT(11) NOT NULL,
			`range_code` VARCHAR(64) NOT NULL,
			`value_decimal` DECIMAL(15,4) NOT NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`product_id`, `range_code`),
			KEY `range_code` (`range_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_map` (
			`leaf_code` VARCHAR(128) NOT NULL,
			`category_id` INT(11) NOT NULL,
			`is_primary` TINYINT(1) NOT NULL DEFAULT '0',
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`leaf_code`, `category_id`),
			KEY `category_id` (`category_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_node_map` (
			`node_code` VARCHAR(128) NOT NULL,
			`category_id` INT(11) NOT NULL,
			`parent_node_code` VARCHAR(128) NOT NULL DEFAULT '',
			`is_leaf` TINYINT(1) NOT NULL DEFAULT '0',
			`is_managed` TINYINT(1) NOT NULL DEFAULT '1',
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`node_code`),
			KEY `category_id` (`category_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_attribute_group_map` (
			`group_code` VARCHAR(64) NOT NULL,
			`attribute_group_id` INT(11) NOT NULL,
			`is_managed` TINYINT(1) NOT NULL DEFAULT '1',
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`group_code`),
			KEY `attribute_group_id` (`attribute_group_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_attribute_map` (
			`attribute_code` VARCHAR(64) NOT NULL,
			`attribute_id` INT(11) NOT NULL,
			`group_code` VARCHAR(64) NOT NULL,
			`input_type` VARCHAR(32) NOT NULL,
			`is_managed` TINYINT(1) NOT NULL DEFAULT '1',
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`attribute_code`),
			KEY `attribute_id` (`attribute_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->ensureProductSchemaCompatibility();
	}

	public function install() {
		$this->ensureSchemaUpgrades();

		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_add_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_edit_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_delete_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_products');

		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_admin_add_product', 'admin/model/catalog/product/addProduct/after', 'extension/module/kotlyars_catalog_foundation_event/addProductAfter');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_admin_edit_product', 'admin/model/catalog/product/editProduct/after', 'extension/module/kotlyars_catalog_foundation_event/editProductAfter');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_admin_delete_product', 'admin/model/catalog/product/deleteProduct/before', 'extension/module/kotlyars_catalog_foundation_event/deleteProductBefore');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_catalog_get_product', 'catalog/model/catalog/product/getProduct/after', 'extension/module/kotlyars_catalog_foundation_event/getProductAfter');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_catalog_get_products', 'catalog/model/catalog/product/getProducts/after', 'extension/module/kotlyars_catalog_foundation_event/getProductsAfter');

		$this->installModification();
		$this->bootstrapNativeCatalog();
	}

	public function uninstall() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_add_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_edit_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_delete_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_products');

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_attribute_map`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_attribute_group_map`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_node_map`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_enum`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_range`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_map`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_product`");

		$this->uninstallModification();
	}

	public function installModification() {
		$xml = $this->getModificationXml();

		if ($xml === '') {
			throw new Exception('Kotlyars catalog OCMOD XML could not be loaded.');
		}

		$this->load->model('setting/modification');

		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXml($xml);

		$name = $dom->getElementsByTagName('name')->item(0);
		$code = $dom->getElementsByTagName('code')->item(0);
		$author = $dom->getElementsByTagName('author')->item(0);
		$version = $dom->getElementsByTagName('version')->item(0);
		$link = $dom->getElementsByTagName('link')->item(0);

		if (!$code || !$code->nodeValue) {
			throw new Exception('Kotlyars catalog OCMOD code is missing.');
		}

		$modification_info = $this->model_setting_modification->getModificationByCode($code->nodeValue);

		if ($modification_info) {
			$this->model_setting_modification->deleteModification($modification_info['modification_id']);
		}

		$this->model_setting_modification->addModification(array(
			'extension_install_id' => 0,
			'name'                 => $name ? $name->nodeValue : 'Kotlyars Catalog Foundation',
			'code'                 => $code->nodeValue,
			'author'               => $author ? $author->nodeValue : '',
			'version'              => $version ? $version->nodeValue : '',
			'link'                 => $link ? $link->nodeValue : '',
			'xml'                  => $xml,
			'status'               => 1
		));
	}

	public function uninstallModification() {
		$this->load->model('setting/modification');

		$modification_info = $this->model_setting_modification->getModificationByCode(self::MODIFICATION_CODE);

		if ($modification_info) {
			$this->model_setting_modification->deleteModification($modification_info['modification_id']);
		}
	}

	public function getModificationStatus() {
		$this->ensureSchemaUpgrades();
		$this->load->model('setting/modification');

		return $this->model_setting_modification->getModificationByCode(self::MODIFICATION_CODE);
	}

	public function bootstrapNativeCatalog() {
		$this->ensureSchemaUpgrades();

		$languages = $this->getActiveAdminLanguages();
		$primary_language_id = $this->getPrimaryLanguageId($languages);
		$registry = $this->getRegistry();

		$this->load->model('catalog/category');
		$this->load->model('catalog/attribute_group');
		$this->load->model('catalog/attribute');

		$this->ensureNativeCategoryNodes($registry['native_category_nodes'], $languages, $primary_language_id);
		$group_map = $this->ensureNativeAttributeGroups($registry['native_attribute_groups'], $languages, $primary_language_id);
		$this->ensureNativeAttributes($registry['native_attributes'], $group_map, $languages, $primary_language_id);
		$this->backfillManagedProductsNativeBindings();
	}

	public function getOverview() {
		$this->ensureSchemaUpgrades();
		$registry = $this->getRegistry();

		return array(
			'leaf_count'                  => count($registry['leaves']),
			'attribute_count'             => count($this->getUsedAttributeCodes($registry['leaf_attributes'])),
			'range_count'                 => count($this->getUsedRangeCodes($registry['leaf_ranges'])),
			'native_category_node_count'  => count($registry['native_category_nodes']),
			'native_category_map_count'   => $this->getTableCount('kotlyars_catalog_category_node_map'),
			'native_leaf_map_count'       => $this->getTableCount('kotlyars_catalog_category_map'),
			'native_attribute_group_count'=> $this->getTableCount('kotlyars_catalog_attribute_group_map'),
			'native_attribute_map_count'  => $this->getTableCount('kotlyars_catalog_attribute_map')
		);
	}

	public function getProductFoundation($product_id) {
		$product_id = (int)$product_id;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . $product_id . "'");

		$foundation = array(
			'product_id' => $product_id,
			'leaf_code'  => '',
			'attributes' => array(),
			'ranges'     => array()
		);

		if ($query->num_rows) {
			$foundation['leaf_code'] = $query->row['leaf_code'];
		}

		$enum_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_enum` WHERE `product_id` = '" . $product_id . "'");

		foreach ($enum_query->rows as $row) {
			$foundation['attributes'][$row['attribute_code']] = $row['value_key'];
		}

		$range_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` WHERE `product_id` = '" . $product_id . "'");

		foreach ($range_query->rows as $row) {
			$foundation['ranges'][$row['range_code']] = (float)$row['value_decimal'];
		}

		return $foundation;
	}

	public function getProductFoundationState($product_id, array $posted = array(), array $selected_categories = array(), array $errors = array()) {
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($posted);
		$core_ranges = $this->getCurrentCoreRangeValues($product_id, $posted);

		if (!$foundation['leaf_code'] && $product_id && empty($posted)) {
			$foundation = $this->getProductFoundation($product_id);
		}

		$leaf_code = $foundation['leaf_code'];
		$selected_leaf = isset($registry['leaves'][$leaf_code]) ? $registry['leaves'][$leaf_code] : array();
		$leaf_attribute_rules = $this->getLeafAttributeRules($leaf_code, $registry['leaf_attributes']);
		$leaf_range_rules = $this->getLeafRangeRules($leaf_code, $registry['leaf_ranges']);

		$leaf_options = array();

		foreach ($registry['leaves'] as $leaf) {
			$leaf_options[$leaf['domain_label']][] = array(
				'code'       => $leaf['code'],
				'label'      => $leaf['label'],
				'path_label' => implode(' > ', $leaf['path'])
			);
		}

		$attribute_rows = array();

		foreach ($registry['attributes'] as $attribute_code => $definition) {
			$applicable_leaves = $this->getApplicableLeavesForAttribute($attribute_code, $registry['leaf_attributes']);
			$options_by_leaf = array();

			foreach ($applicable_leaves as $applicable_leaf_code) {
				$rule = $this->findRuleByCode($registry['leaf_attributes'][$applicable_leaf_code], $attribute_code);

				if ($rule) {
					$options_by_leaf[$applicable_leaf_code] = $this->formatOptions($rule['options']);
				}
			}

			$attribute_rows[] = array(
				'code'         => $attribute_code,
				'label'        => $definition['label'],
				'value'        => isset($foundation['attributes'][$attribute_code]) ? $foundation['attributes'][$attribute_code] : '',
				'visible'      => in_array($leaf_code, $applicable_leaves),
				'applicable'   => implode(',', $applicable_leaves),
				'options_json' => json_encode($options_by_leaf),
				'error'        => isset($errors['attributes'][$attribute_code]) ? $errors['attributes'][$attribute_code] : ''
			);
		}

		$range_rows = array();

		foreach ($registry['ranges'] as $range_code => $definition) {
			$applicable_leaves = $this->getApplicableLeavesForRange($range_code, $registry['leaf_ranges']);
			$rules_by_leaf = array();

			foreach ($applicable_leaves as $applicable_leaf_code) {
				$rule = $this->findRuleByCode($registry['leaf_ranges'][$applicable_leaf_code], $range_code);

				if ($rule) {
					$rules_by_leaf[$applicable_leaf_code] = $rule;
				}
			}

			$value = $definition['storage'] === 'core'
				? (isset($core_ranges[$range_code]) ? $core_ranges[$range_code] : '')
				: (isset($foundation['ranges'][$range_code]) ? $foundation['ranges'][$range_code] : '');

			$range_rows[] = array(
				'code'          => $range_code,
				'label'         => $definition['label'],
				'value'         => $value,
				'storage'       => $definition['storage'],
				'core_label'    => isset($definition['core_label']) ? $definition['core_label'] : '',
				'visible'       => in_array($leaf_code, $applicable_leaves),
				'applicable'    => implode(',', $applicable_leaves),
				'rules_json'    => json_encode($rules_by_leaf),
				'selected_rule' => isset($leaf_range_rules[$range_code]) ? $leaf_range_rules[$range_code] : array(),
				'error'         => isset($errors['ranges'][$range_code]) ? $errors['ranges'][$range_code] : ''
			);
		}

		$filter_preview = array(
			'attributes' => array_values(array_map(function($rule) use ($registry) {
				return $registry['attributes'][$rule['code']]['label'];
			}, $leaf_attribute_rules)),
			'ranges' => array_values(array_map(function($rule) use ($registry) {
				return $registry['ranges'][$rule['code']]['label'];
			}, $leaf_range_rules))
		);

		return array(
			'foundation'        => $foundation,
			'leaf_options'      => $leaf_options,
			'selected_leaf'     => $selected_leaf,
			'attribute_rows'    => $attribute_rows,
			'range_rows'        => $range_rows,
			'filter_preview'    => $filter_preview,
			'alignment_notice'  => $this->buildAlignmentNotice($leaf_code, $selected_categories),
			'errors'            => $errors,
			'leaf_label_lookup' => array_map(function($leaf) {
				return implode(' > ', $leaf['path']);
			}, $registry['leaves'])
		);
	}

	public function validateProductFoundation(array $data) {
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($data);
		$errors = array();

		if (!$foundation['leaf_code'] || !isset($registry['leaves'][$foundation['leaf_code']])) {
			$errors['leaf_code'] = 'Select a governed catalog leaf.';

			return $errors;
		}

		if (empty($data['product_category'])) {
			$errors['category_alignment'] = 'Assign at least one OpenCart category for storefront taxonomy.';
		}

		$allowed_attribute_rules = $this->getLeafAttributeRules($foundation['leaf_code'], $registry['leaf_attributes']);
		$allowed_range_rules = $this->getLeafRangeRules($foundation['leaf_code'], $registry['leaf_ranges']);
		$allowed_attribute_codes = array_keys($allowed_attribute_rules);
		$allowed_range_codes = array_keys($allowed_range_rules);

		foreach ($foundation['attributes'] as $attribute_code => $value) {
			if ($value === '') {
				continue;
			}

			if (!in_array($attribute_code, $allowed_attribute_codes)) {
				$errors['attributes'][$attribute_code] = 'This attribute is not allowed for the selected leaf.';
				continue;
			}

			if (!isset($allowed_attribute_rules[$attribute_code]['options'][$value])) {
				$errors['attributes'][$attribute_code] = 'Select a valid option for ' . $registry['attributes'][$attribute_code]['label'] . '.';
			}
		}

		foreach ($allowed_range_rules as $range_code => $range_rule) {
			$value = $this->extractRangeValue($range_code, $registry['ranges'][$range_code], $data, $foundation);

			if ($value === '') {
				$errors['ranges'][$range_code] = $registry['ranges'][$range_code]['label'] . ' is required for the selected leaf.';
				continue;
			}

			if (!is_numeric($value)) {
				$errors['ranges'][$range_code] = $registry['ranges'][$range_code]['label'] . ' must be numeric.';
				continue;
			}

			$numeric_value = (float)$value;

			if ($numeric_value < (float)$range_rule['min'] || $numeric_value > (float)$range_rule['max']) {
				$errors['ranges'][$range_code] = sprintf('%s must be between %s and %s.', $registry['ranges'][$range_code]['label'], $range_rule['min'], $range_rule['max']);
			}
		}

		foreach ($foundation['ranges'] as $range_code => $value) {
			if ($value === '') {
				continue;
			}

			if (!in_array($range_code, $allowed_range_codes)) {
				$errors['ranges'][$range_code] = 'This range is not allowed for the selected leaf.';
			}
		}

		return $errors;
	}

	public function saveProductFoundation($product_id, array $data) {
		$product_id = (int)$product_id;
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($data);

		if (!$foundation['leaf_code'] || !isset($registry['leaves'][$foundation['leaf_code']])) {
			return;
		}

		$this->ensureSchemaUpgrades();

		$allowed_attribute_rules = $this->getLeafAttributeRules($foundation['leaf_code'], $registry['leaf_attributes']);
		$allowed_range_rules = $this->getLeafRangeRules($foundation['leaf_code'], $registry['leaf_ranges']);

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_product` SET `product_id` = '" . $product_id . "', `leaf_code` = '" . $this->db->escape($foundation['leaf_code']) . "', `date_added` = NOW(), `date_modified` = NOW()");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_enum` WHERE `product_id` = '" . $product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` WHERE `product_id` = '" . $product_id . "'");

		foreach ($allowed_attribute_rules as $attribute_code => $rule) {
			$value = isset($foundation['attributes'][$attribute_code]) ? trim((string)$foundation['attributes'][$attribute_code]) : '';

			if ($value === '' || !isset($rule['options'][$value])) {
				continue;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "kotlyars_catalog_product_enum` SET `product_id` = '" . $product_id . "', `attribute_code` = '" . $this->db->escape($attribute_code) . "', `value_key` = '" . $this->db->escape($value) . "', `date_modified` = NOW()");
		}

		foreach ($allowed_range_rules as $range_code => $rule) {
			if ($registry['ranges'][$range_code]['storage'] !== 'extension') {
				continue;
			}

			$value = isset($foundation['ranges'][$range_code]) ? $foundation['ranges'][$range_code] : '';

			if ($value === '' || !is_numeric($value)) {
				continue;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "kotlyars_catalog_product_range` SET `product_id` = '" . $product_id . "', `range_code` = '" . $this->db->escape($range_code) . "', `value_decimal` = '" . (float)$value . "', `date_modified` = NOW()");
		}

		$this->syncManagedNativeProductState($product_id, $this->getProductFoundation($product_id));
	}

	public function deleteProductFoundation($product_id) {
		$product_id = (int)$product_id;

		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_enum` WHERE `product_id` = '" . $product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_range` WHERE `product_id` = '" . $product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . $product_id . "'");
	}

	protected function normalizeProductFoundation(array $data) {
		$foundation = isset($data['kotlyars_catalog_foundation']) ? (array)$data['kotlyars_catalog_foundation'] : array();
		$attributes = isset($foundation['attributes']) ? (array)$foundation['attributes'] : array();
		$ranges = isset($foundation['ranges']) ? (array)$foundation['ranges'] : array();

		foreach ($attributes as $attribute_code => $value) {
			$attributes[$attribute_code] = trim((string)$value);
		}

		foreach ($ranges as $range_code => $value) {
			$ranges[$range_code] = trim((string)$value);
		}

		return array(
			'leaf_code'  => isset($foundation['leaf_code']) ? $foundation['leaf_code'] : '',
			'attributes' => $attributes,
			'ranges'     => $ranges
		);
	}

	protected function getLeafAttributeRules($leaf_code, array $leaf_attribute_map) {
		$rules = array();

		if (!isset($leaf_attribute_map[$leaf_code])) {
			return $rules;
		}

		foreach ($leaf_attribute_map[$leaf_code] as $rule) {
			$rules[$rule['code']] = $rule;
		}

		return $rules;
	}

	protected function getLeafRangeRules($leaf_code, array $leaf_range_map) {
		$rules = array();

		if (!isset($leaf_range_map[$leaf_code])) {
			return $rules;
		}

		foreach ($leaf_range_map[$leaf_code] as $rule) {
			$rules[$rule['code']] = $rule;
		}

		return $rules;
	}

	protected function getApplicableLeavesForAttribute($attribute_code, array $leaf_attribute_map) {
		$leaves = array();

		foreach ($leaf_attribute_map as $leaf_code => $rules) {
			foreach ($rules as $rule) {
				if ($rule['code'] === $attribute_code) {
					$leaves[] = $leaf_code;
					break;
				}
			}
		}

		return $leaves;
	}

	protected function getApplicableLeavesForRange($range_code, array $leaf_range_map) {
		$leaves = array();

		foreach ($leaf_range_map as $leaf_code => $rules) {
			foreach ($rules as $rule) {
				if ($rule['code'] === $range_code) {
					$leaves[] = $leaf_code;
					break;
				}
			}
		}

		return $leaves;
	}

	protected function getUsedAttributeCodes(array $leaf_attribute_map) {
		$codes = array();

		foreach ($leaf_attribute_map as $rules) {
			foreach ($rules as $rule) {
				$codes[$rule['code']] = true;
			}
		}

		return array_keys($codes);
	}

	protected function getUsedRangeCodes(array $leaf_range_map) {
		$codes = array();

		foreach ($leaf_range_map as $rules) {
			foreach ($rules as $rule) {
				$codes[$rule['code']] = true;
			}
		}

		return array_keys($codes);
	}

	protected function findRuleByCode(array $rules, $code) {
		foreach ($rules as $rule) {
			if ($rule['code'] === $code) {
				return $rule;
			}
		}

		return array();
	}

	protected function formatOptions(array $options) {
		$formatted = array();

		foreach ($options as $value => $label) {
			$formatted[] = array(
				'value' => $value,
				'label' => $label
			);
		}

		return $formatted;
	}

	protected function extractRangeValue($range_code, array $range_definition, array $data, array $foundation) {
		if ($range_definition['storage'] === 'core') {
			$core_field = $range_definition['core_field'];

			return isset($data[$core_field]) ? trim((string)$data[$core_field]) : '';
		}

		return isset($foundation['ranges'][$range_code]) ? trim((string)$foundation['ranges'][$range_code]) : '';
	}

	protected function getCurrentCoreRangeValues($product_id, array $posted) {
		$values = array(
			'price' => isset($posted['price']) ? $posted['price'] : '',
			'weight' => isset($posted['weight']) ? $posted['weight'] : ''
		);

		if (($values['price'] !== '' || $values['weight'] !== '') || !$product_id) {
			return $values;
		}

		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct((int)$product_id);

		if ($product_info) {
			$values['price'] = $product_info['price'];
			$values['weight'] = $product_info['weight'];
		}

		return $values;
	}

	protected function buildAlignmentNotice($leaf_code, array $selected_categories) {
		if (!$leaf_code) {
			return '';
		}

		$linked_category_ids = $this->getLinkedCategoryIds($leaf_code);
		$selected_category_ids = array_map('intval', $selected_categories);

		if (!$linked_category_ids) {
			return 'No governed OpenCart category mapping is configured yet for this leaf. Save remains allowed.';
		}

		if ($selected_category_ids && array_intersect($selected_category_ids, $linked_category_ids)) {
			return 'Selected OpenCart categories match the configured governance mapping.';
		}

		return 'Configured governance category mappings do not match the current OpenCart category selection. Save remains allowed in this rollout.';
	}

	protected function getLinkedCategoryIds($leaf_code) {
		$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "kotlyars_catalog_category_map` WHERE `leaf_code` = '" . $this->db->escape($leaf_code) . "'");

		return array_map('intval', array_column($query->rows, 'category_id'));
	}

	protected function ensureProductSchemaCompatibility() {
		$columns = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "kotlyars_catalog_product`");
		$column_names = array_column($columns->rows, 'Field');

		if (!in_array('leaf_code', $column_names)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "kotlyars_catalog_product` ADD `leaf_code` VARCHAR(128) NOT NULL DEFAULT '' AFTER `product_id`");
			$column_names[] = 'leaf_code';
		}

		if (in_array('category_code', $column_names)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "kotlyars_catalog_product` SET `leaf_code` = CASE `category_code`
				WHEN 'diamonds.coloured' THEN 'diamonds.colour'
				ELSE `category_code`
			END WHERE `leaf_code` = '' AND `category_code` != ''");
		}
	}

	protected function getActiveAdminLanguages() {
		$query = $this->db->query("SELECT `language_id`, `name`, `code` FROM `" . DB_PREFIX . "language` WHERE `status` = '1' ORDER BY `language_id` ASC");

		if (!$query->rows) {
			throw new Exception('No active admin languages are available for native bootstrap.');
		}

		return $query->rows;
	}

	protected function getPrimaryLanguageId(array $languages) {
		return (int)$languages[0]['language_id'];
	}

	protected function buildCategoryDescriptionPayload($label, array $languages) {
		$descriptions = array();

		foreach ($languages as $language) {
			$descriptions[(int)$language['language_id']] = array(
				'name'             => $label,
				'description'      => '',
				'meta_title'       => $label,
				'meta_description' => '',
				'meta_keyword'     => ''
			);
		}

		return $descriptions;
	}

	protected function buildAttributeDescriptionPayload($label, array $languages) {
		$descriptions = array();

		foreach ($languages as $language) {
			$descriptions[(int)$language['language_id']] = array('name' => $label);
		}

		return $descriptions;
	}

	protected function ensureNativeCategoryNodes(array $nodes, array $languages, $primary_language_id) {
		foreach ($nodes as $node_code => $node) {
			$parent_category_id = 0;

			if (!empty($node['parent_code'])) {
				$parent_category_id = $this->getMappedNodeCategoryId($node['parent_code']);

				if (!$parent_category_id) {
					throw new Exception('Missing native parent category for node ' . $node_code . '.');
				}
			}

			$category_id = $this->getMappedNodeCategoryId($node_code);

			if (!$category_id || !$this->nativeCategoryExists($category_id)) {
				$category_id = $this->findExistingCategoryByParentAndName($parent_category_id, $node['label'], $primary_language_id);
			}

			if (!$category_id) {
				$category_id = $this->model_catalog_category->addCategory(array(
					'parent_id'             => $parent_category_id,
					'top'                   => 0,
					'column'                => 1,
					'sort_order'            => 0,
					'status'                => 1,
					'image'                 => '',
					'category_store'        => array(0),
					'category_filter'       => array(),
					'category_layout'       => array(),
					'category_seo_url'      => array(),
					'category_description'  => $this->buildCategoryDescriptionPayload($node['label'], $languages)
				));
			}

			$this->ensureCategoryDescriptions($category_id, $node['label'], $languages);
			$this->ensureCategoryStoreAssignment($category_id, 0);

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_category_node_map` SET `node_code` = '" . $this->db->escape($node_code) . "', `category_id` = '" . (int)$category_id . "', `parent_node_code` = '" . $this->db->escape($node['parent_code']) . "', `is_leaf` = '" . (!empty($node['is_leaf']) ? 1 : 0) . "', `is_managed` = '1', `date_modified` = NOW()");

			if (!empty($node['is_leaf']) && !empty($node['leaf_code'])) {
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_category_map` SET `leaf_code` = '" . $this->db->escape($node['leaf_code']) . "', `category_id` = '" . (int)$category_id . "', `is_primary` = '1', `date_modified` = NOW()");
			}
		}
	}

	protected function ensureNativeAttributeGroups(array $groups, array $languages, $primary_language_id) {
		$group_map = array();

		foreach ($groups as $group_code => $group) {
			$attribute_group_id = $this->getMappedAttributeGroupId($group_code);

			if (!$attribute_group_id || !$this->nativeAttributeGroupExists($attribute_group_id)) {
				$attribute_group_id = $this->findExistingAttributeGroupByName($group['label'], $primary_language_id);
			}

			if (!$attribute_group_id) {
				$attribute_group_id = $this->model_catalog_attribute_group->addAttributeGroup(array(
					'sort_order' => (int)$group['sort_order'],
					'attribute_group_description' => $this->buildAttributeDescriptionPayload($group['label'], $languages)
				));
			}

			$this->ensureAttributeGroupDescriptions($attribute_group_id, $group['label'], $languages);
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_attribute_group_map` SET `group_code` = '" . $this->db->escape($group_code) . "', `attribute_group_id` = '" . (int)$attribute_group_id . "', `is_managed` = '1', `date_modified` = NOW()");

			$group_map[$group_code] = (int)$attribute_group_id;
		}

		return $group_map;
	}

	protected function ensureNativeAttributes(array $attributes, array $group_map, array $languages, $primary_language_id) {
		$registry = $this->getRegistry();

		foreach ($attributes as $attribute_code => $attribute) {
			if (empty($group_map[$attribute['group_code']])) {
				throw new Exception('Missing native attribute group for ' . $attribute_code . '.');
			}

			$attribute_group_id = (int)$group_map[$attribute['group_code']];
			$attribute_id = $this->getMappedAttributeId($attribute_code);

			if (!$attribute_id || !$this->nativeAttributeExists($attribute_id)) {
				$attribute_id = $this->findExistingAttributeByGroupAndName($attribute_group_id, $attribute['label'], $primary_language_id);
			}

			if (!$attribute_id) {
				$attribute_id = $this->model_catalog_attribute->addAttribute(array(
					'attribute_group_id' => $attribute_group_id,
					'sort_order' => (int)$attribute['sort_order'],
					'attribute_description' => $this->buildAttributeDescriptionPayload($attribute['label'], $languages)
				));
			}

			$this->ensureAttributeDescriptions($attribute_id, $attribute['label'], $languages);
			$this->db->query("UPDATE `" . DB_PREFIX . "attribute` SET `attribute_group_id` = '" . $attribute_group_id . "', `sort_order` = '" . (int)$attribute['sort_order'] . "' WHERE `attribute_id` = '" . (int)$attribute_id . "'");
			$input_type = isset($registry['attributes'][$attribute_code])
				? $registry['attributes'][$attribute_code]['input_type']
				: (isset($registry['ranges'][$attribute_code]) ? $registry['ranges'][$attribute_code]['input_type'] : 'text');

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_attribute_map` SET `attribute_code` = '" . $this->db->escape($attribute_code) . "', `attribute_id` = '" . (int)$attribute_id . "', `group_code` = '" . $this->db->escape($attribute['group_code']) . "', `input_type` = '" . $this->db->escape($input_type) . "', `is_managed` = '1', `date_modified` = NOW()");
		}
	}

	protected function backfillManagedProductsNativeBindings() {
		$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "kotlyars_catalog_product` ORDER BY `product_id` ASC");

		foreach ($query->rows as $row) {
			$this->syncManagedNativeProductState((int)$row['product_id'], $this->getProductFoundation((int)$row['product_id']));
		}
	}

	protected function syncManagedNativeProductState($product_id, array $foundation) {
		if (empty($foundation['leaf_code'])) {
			return;
		}

		$leaf_category_id = $this->getMappedLeafCategoryId($foundation['leaf_code']);

		if ($leaf_category_id) {
			$this->ensureProductLeafCategory($product_id, $leaf_category_id);
		}

		$this->syncManagedNativeProductAttributes($product_id, $foundation);
	}

	protected function ensureProductLeafCategory($product_id, $category_id) {
		$query = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "' AND `category_id` = '" . (int)$category_id . "' LIMIT 1");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '" . (int)$product_id . "', `category_id` = '" . (int)$category_id . "'");
		}
	}

	protected function syncManagedNativeProductAttributes($product_id, array $foundation) {
		$registry = $this->getRegistry();
		$attribute_map = $this->getManagedNativeAttributeMap();

		if (!$attribute_map) {
			return;
		}

		$languages = $this->getActiveAdminLanguages();
		$managed_attribute_ids = array_map('intval', array_values($attribute_map));

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' AND `attribute_id` IN (" . implode(',', $managed_attribute_ids) . ")");

		$projected_values = array();
		$leaf_attribute_rules = $this->getLeafAttributeRules($foundation['leaf_code'], $registry['leaf_attributes']);
		$leaf_range_rules = $this->getLeafRangeRules($foundation['leaf_code'], $registry['leaf_ranges']);

		foreach ($leaf_attribute_rules as $attribute_code => $rule) {
			$value_key = isset($foundation['attributes'][$attribute_code]) ? $foundation['attributes'][$attribute_code] : '';

			if ($value_key !== '' && isset($rule['options'][$value_key]) && isset($attribute_map[$attribute_code])) {
				$projected_values[$attribute_code] = $rule['options'][$value_key];
			}
		}

		if (isset($leaf_range_rules['carat']) && isset($attribute_map['carat'])) {
			$carat_value = isset($foundation['ranges']['carat']) ? $foundation['ranges']['carat'] : '';

			if ($carat_value !== '' && is_numeric($carat_value)) {
				$projected_values['carat'] = $this->formatProjectedNumericValue($carat_value);
			}
		}

		foreach ($projected_values as $attribute_code => $text) {
			$attribute_id = (int)$attribute_map[$attribute_code];

			foreach ($languages as $language) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_id . "', `attribute_id` = '" . $attribute_id . "', `language_id` = '" . (int)$language['language_id'] . "', `text` = '" . $this->db->escape($text) . "'");
			}
		}
	}

	protected function formatProjectedNumericValue($value) {
		$formatted = number_format((float)$value, 4, '.', '');

		return rtrim(rtrim($formatted, '0'), '.');
	}

	protected function getManagedNativeAttributeMap() {
		$query = $this->db->query("SELECT `attribute_code`, `attribute_id` FROM `" . DB_PREFIX . "kotlyars_catalog_attribute_map` WHERE `is_managed` = '1'");
		$map = array();

		foreach ($query->rows as $row) {
			$map[$row['attribute_code']] = (int)$row['attribute_id'];
		}

		return $map;
	}

	protected function getMappedLeafCategoryId($leaf_code) {
		$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "kotlyars_catalog_category_map` WHERE `leaf_code` = '" . $this->db->escape($leaf_code) . "' LIMIT 1");

		return $query->num_rows ? (int)$query->row['category_id'] : 0;
	}

	protected function getMappedNodeCategoryId($node_code) {
		$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "kotlyars_catalog_category_node_map` WHERE `node_code` = '" . $this->db->escape($node_code) . "' LIMIT 1");

		return $query->num_rows ? (int)$query->row['category_id'] : 0;
	}

	protected function getMappedAttributeGroupId($group_code) {
		$query = $this->db->query("SELECT `attribute_group_id` FROM `" . DB_PREFIX . "kotlyars_catalog_attribute_group_map` WHERE `group_code` = '" . $this->db->escape($group_code) . "' LIMIT 1");

		return $query->num_rows ? (int)$query->row['attribute_group_id'] : 0;
	}

	protected function getMappedAttributeId($attribute_code) {
		$query = $this->db->query("SELECT `attribute_id` FROM `" . DB_PREFIX . "kotlyars_catalog_attribute_map` WHERE `attribute_code` = '" . $this->db->escape($attribute_code) . "' LIMIT 1");

		return $query->num_rows ? (int)$query->row['attribute_id'] : 0;
	}

	protected function nativeCategoryExists($category_id) {
		$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category_id . "' LIMIT 1");

		return (bool)$query->num_rows;
	}

	protected function nativeAttributeGroupExists($attribute_group_id) {
		$query = $this->db->query("SELECT `attribute_group_id` FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "' LIMIT 1");

		return (bool)$query->num_rows;
	}

	protected function nativeAttributeExists($attribute_id) {
		$query = $this->db->query("SELECT `attribute_id` FROM `" . DB_PREFIX . "attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' LIMIT 1");

		return (bool)$query->num_rows;
	}

	protected function findExistingCategoryByParentAndName($parent_id, $name, $language_id) {
		$query = $this->db->query("SELECT `c`.`category_id` FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (`c`.`category_id` = `cd`.`category_id`) WHERE `c`.`parent_id` = '" . (int)$parent_id . "' AND `cd`.`language_id` = '" . (int)$language_id . "' AND `cd`.`name` = '" . $this->db->escape($name) . "'");

		if ($query->num_rows > 1) {
			throw new Exception('Ambiguous native category match for "' . $name . '" under parent ID ' . (int)$parent_id . '.');
		}

		return $query->num_rows ? (int)$query->row['category_id'] : 0;
	}

	protected function findExistingAttributeGroupByName($name, $language_id) {
		$query = $this->db->query("SELECT `attribute_group_id` FROM `" . DB_PREFIX . "attribute_group_description` WHERE `language_id` = '" . (int)$language_id . "' AND `name` = '" . $this->db->escape($name) . "'");

		if ($query->num_rows > 1) {
			throw new Exception('Ambiguous native attribute group match for "' . $name . '".');
		}

		return $query->num_rows ? (int)$query->row['attribute_group_id'] : 0;
	}

	protected function findExistingAttributeByGroupAndName($attribute_group_id, $name, $language_id) {
		$query = $this->db->query("SELECT `a`.`attribute_id` FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (`a`.`attribute_id` = `ad`.`attribute_id`) WHERE `a`.`attribute_group_id` = '" . (int)$attribute_group_id . "' AND `ad`.`language_id` = '" . (int)$language_id . "' AND `ad`.`name` = '" . $this->db->escape($name) . "'");

		if ($query->num_rows > 1) {
			throw new Exception('Ambiguous native attribute match for "' . $name . '".');
		}

		return $query->num_rows ? (int)$query->row['attribute_id'] : 0;
	}

	protected function ensureCategoryDescriptions($category_id, $label, array $languages) {
		foreach ($languages as $language) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category_id . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($label) . "', `description` = '', `meta_title` = '" . $this->db->escape($label) . "', `meta_description` = '', `meta_keyword` = ''");
		}
	}

	protected function ensureCategoryStoreAssignment($category_id, $store_id) {
		$query = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "category_to_store` WHERE `category_id` = '" . (int)$category_id . "' AND `store_id` = '" . (int)$store_id . "' LIMIT 1");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . (int)$category_id . "', `store_id` = '" . (int)$store_id . "'");
		}
	}

	protected function ensureAttributeGroupDescriptions($attribute_group_id, $label, array $languages) {
		foreach ($languages as $language) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$attribute_group_id . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($label) . "'");
		}
	}

	protected function ensureAttributeDescriptions($attribute_id, $label, array $languages) {
		foreach ($languages as $language) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$attribute_id . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($label) . "'");
		}
	}

	protected function getTableCount($table_suffix) {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . $table_suffix . "`");

		return (int)$query->row['total'];
	}

	protected function getModificationXml() {
		$file = DIR_SYSTEM . 'config/kotlyars_catalog_foundation.ocmod.xml';

		if (!is_file($file)) {
			return '';
		}

		return file_get_contents($file);
	}
}
