<?php
class ModelExtensionModuleKotlyarsCatalogFoundation extends Model {
	protected function getRegistry() {
		$this->load->config('kotlyars_catalog');

		return array(
			'product_types'          => (array)$this->config->get('kotlyars_catalog_product_types'),
			'categories'             => (array)$this->config->get('kotlyars_catalog_category_registry'),
			'attribute_definitions'  => (array)$this->config->get('kotlyars_catalog_attribute_definitions'),
			'category_attributes'    => (array)$this->config->get('kotlyars_catalog_category_attribute_map'),
			'filter_definitions'     => (array)$this->config->get('kotlyars_catalog_filter_definitions'),
			'category_filters'       => (array)$this->config->get('kotlyars_catalog_category_filter_map'),
			'auction_placeholders'   => (array)$this->config->get('kotlyars_catalog_auction_placeholders')
		);
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_product` (
			`product_id` INT(11) NOT NULL,
			`product_type` VARCHAR(32) NOT NULL DEFAULT 'fixed_price',
			`category_code` VARCHAR(128) NOT NULL,
			`date_added` DATETIME NOT NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`product_id`),
			KEY `product_type` (`product_type`),
			KEY `category_code` (`category_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_value` (
			`product_id` INT(11) NOT NULL,
			`attribute_code` VARCHAR(64) NOT NULL,
			`value_text` TEXT NULL,
			`value_decimal` DECIMAL(15,4) NULL,
			`value_int` INT(11) NULL,
			`value_key` VARCHAR(64) NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`product_id`, `attribute_code`),
			KEY `attribute_code` (`attribute_code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_map` (
			`category_code` VARCHAR(128) NOT NULL,
			`category_id` INT(11) NOT NULL,
			`is_primary` TINYINT(1) NOT NULL DEFAULT '0',
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`category_code`, `category_id`),
			KEY `category_id` (`category_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_delete_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_products');

		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_admin_delete_product', 'admin/model/catalog/product/deleteProduct/before', 'extension/module/kotlyars_catalog_foundation_event/deleteProductBefore');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_catalog_get_product', 'catalog/model/catalog/product/getProduct/after', 'extension/module/kotlyars_catalog_foundation_event/getProductAfter');
		$this->model_setting_event->addEvent('kotlyars_catalog_foundation_catalog_get_products', 'catalog/model/catalog/product/getProducts/after', 'extension/module/kotlyars_catalog_foundation_event/getProductsAfter');
	}

	public function uninstall() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_admin_delete_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_product');
		$this->model_setting_event->deleteEventByCode('kotlyars_catalog_foundation_catalog_get_products');

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_product_value`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_category_map`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "kotlyars_catalog_product`");
	}

	public function getOverview() {
		$registry = $this->getRegistry();

		$extension_attribute_count = 0;
		$core_attribute_count = 0;

		foreach ($registry['attribute_definitions'] as $attribute_definition) {
			if ($attribute_definition['storage'] === 'core') {
				$core_attribute_count++;
			} else {
				$extension_attribute_count++;
			}
		}

		return array(
			'category_count'            => count($registry['categories']),
			'attribute_count'           => count($registry['attribute_definitions']),
			'extension_attribute_count' => $extension_attribute_count,
			'core_attribute_count'      => $core_attribute_count,
			'filter_count'              => count($registry['filter_definitions']),
			'product_types'             => count($registry['product_types'])
		);
	}

	public function getProductFoundation($product_id) {
		$product_id = (int)$product_id;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . $product_id . "'");

		if (!$query->num_rows) {
			return array(
				'product_id'   => $product_id,
				'product_type' => 'fixed_price',
				'category_code'=> '',
				'attributes'   => array()
			);
		}

		$value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_value` WHERE `product_id` = '" . $product_id . "'");

		$attributes = array();

		foreach ($value_query->rows as $row) {
			if ($row['value_key'] !== null && $row['value_key'] !== '') {
				$attributes[$row['attribute_code']] = $row['value_key'];
			} elseif ($row['value_decimal'] !== null) {
				$attributes[$row['attribute_code']] = $row['value_decimal'];
			} elseif ($row['value_int'] !== null) {
				$attributes[$row['attribute_code']] = $row['value_int'];
			} else {
				$attributes[$row['attribute_code']] = $row['value_text'];
			}
		}

		return array(
			'product_id'    => $product_id,
			'product_type'  => $query->row['product_type'],
			'category_code' => $query->row['category_code'],
			'attributes'    => $attributes
		);
	}

	public function getProductFoundationState($product_id, array $posted = array(), array $selected_categories = array(), array $errors = array()) {
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($posted);

		if (!$foundation['category_code'] && $product_id) {
			$foundation = $this->getProductFoundation($product_id);
		}

		$category_code = $foundation['category_code'];
		$category = isset($registry['categories'][$category_code]) ? $registry['categories'][$category_code] : array();
		$category_attributes = isset($registry['category_attributes'][$category_code]) ? $registry['category_attributes'][$category_code] : array();
		$category_filters = isset($registry['category_filters'][$category_code]) ? $registry['category_filters'][$category_code] : array();

		$category_options = array();

		foreach ($registry['categories'] as $registry_category) {
			$category_options[$registry_category['domain_label']][] = array(
				'code'                  => $registry_category['code'],
				'label'                 => $registry_category['label'],
				'supports_product_types'=> $registry_category['supports_product_types']
			);
		}

		$attribute_requirement_map = array();

		foreach ($category_attributes as $attribute_rule) {
			$attribute_requirement_map[$attribute_rule['code']] = $attribute_rule;
		}

		$attribute_rows = array();

		foreach ($registry['attribute_definitions'] as $attribute_code => $definition) {
			$applicable_categories = $this->getApplicableCategoriesForAttribute($attribute_code, $registry['category_attributes']);
			$current_value = isset($foundation['attributes'][$attribute_code]) ? $foundation['attributes'][$attribute_code] : '';
			$is_required = isset($attribute_requirement_map[$attribute_code]) ? (bool)$attribute_requirement_map[$attribute_code]['required'] : false;

			$attribute_rows[] = array(
				'code'                  => $attribute_code,
				'label'                 => $definition['label'],
				'input_type'            => $definition['input_type'],
				'storage'               => $definition['storage'],
				'core_label'            => isset($definition['core_label']) ? $definition['core_label'] : '',
				'options'               => isset($definition['options']) ? $definition['options'] : array(),
				'value'                 => $current_value,
				'required'              => $is_required,
				'applicable_categories' => $applicable_categories,
				'visible'               => in_array($category_code, $applicable_categories),
				'error'                 => isset($errors['attributes'][$attribute_code]) ? $errors['attributes'][$attribute_code] : ''
			);
		}

		$filter_preview = array();

		foreach ($category_filters as $filter_code) {
			if (isset($registry['filter_definitions'][$filter_code])) {
				$filter_preview[] = $registry['filter_definitions'][$filter_code];
			}
		}

		return array(
			'foundation'         => $foundation,
			'product_types'      => $registry['product_types'],
			'category_options'   => $category_options,
			'selected_category'  => $category,
			'attribute_rows'     => $attribute_rows,
			'filter_preview'     => $filter_preview,
			'errors'             => $errors,
			'selected_categories'=> $selected_categories,
			'auction_placeholders' => $registry['auction_placeholders']
		);
	}

	public function validateProductFoundation(array $data) {
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($data);
		$errors = array();

		if (!isset($registry['product_types'][$foundation['product_type']])) {
			$errors['product_type'] = 'Unsupported product type.';
		}

		if (!$foundation['category_code'] || !isset($registry['categories'][$foundation['category_code']])) {
			$errors['category_code'] = 'Select a governed catalog category.';
			return $errors;
		}

		$category = $registry['categories'][$foundation['category_code']];

		if (!in_array($foundation['product_type'], $category['supports_product_types'])) {
			$errors['product_type'] = 'Selected product type is not allowed for this catalog category.';
		}

		if (empty($data['product_category'])) {
			$errors['category_alignment'] = 'Assign at least one OpenCart category for storefront taxonomy.';
		}

		$linked_category_ids = $this->getLinkedCategoryIds($foundation['category_code']);

		$selected_product_categories = isset($data['product_category']) ? array_map('intval', (array)$data['product_category']) : array();

		if ($linked_category_ids && !array_intersect($selected_product_categories, $linked_category_ids)) {
			$errors['category_alignment'] = 'Selected OpenCart categories do not match the configured governance mapping.';
		}

		if ($foundation['category_code'] === 'precious_metals.gold.industrial_gold' && $foundation['product_type'] !== 'fixed_price') {
			$errors['product_type'] = 'Industrial gold must remain fixed price only.';
		}

		if (isset($registry['category_attributes'][$foundation['category_code']])) {
			foreach ($registry['category_attributes'][$foundation['category_code']] as $attribute_rule) {
				if (empty($attribute_rule['required'])) {
					continue;
				}

				$attribute_code = $attribute_rule['code'];
				$attribute_definition = $registry['attribute_definitions'][$attribute_code];
				$value = $this->extractAttributeValue($attribute_code, $attribute_definition, $data, $foundation);

				if ($value === '' || $value === null) {
					$errors['attributes'][$attribute_code] = $attribute_definition['label'] . ' is required for ' . $category['label'] . '.';
				}
			}
		}

		return $errors;
	}

	public function saveProductFoundation($product_id, array $data) {
		$product_id = (int)$product_id;
		$registry = $this->getRegistry();
		$foundation = $this->normalizeProductFoundation($data);

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "kotlyars_catalog_product` SET `product_id` = '" . $product_id . "', `product_type` = '" . $this->db->escape($foundation['product_type']) . "', `category_code` = '" . $this->db->escape($foundation['category_code']) . "', `date_added` = NOW(), `date_modified` = NOW()");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_value` WHERE `product_id` = '" . $product_id . "'");

		foreach ($foundation['attributes'] as $attribute_code => $value) {
			if (!isset($registry['attribute_definitions'][$attribute_code])) {
				continue;
			}

			$definition = $registry['attribute_definitions'][$attribute_code];

			if ($definition['storage'] !== 'extension') {
				continue;
			}

			$value_text = 'NULL';
			$value_decimal = 'NULL';
			$value_int = 'NULL';
			$value_key = 'NULL';

			if ($definition['input_type'] === 'decimal') {
				$value_decimal = "'" . (float)$value . "'";
			} elseif ($definition['input_type'] === 'integer') {
				$value_int = "'" . (int)$value . "'";
			} elseif ($definition['input_type'] === 'select') {
				$value_key = "'" . $this->db->escape($value) . "'";
			} else {
				$value_text = "'" . $this->db->escape($value) . "'";
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "kotlyars_catalog_product_value` SET `product_id` = '" . $product_id . "', `attribute_code` = '" . $this->db->escape($attribute_code) . "', `value_text` = " . $value_text . ", `value_decimal` = " . $value_decimal . ", `value_int` = " . $value_int . ", `value_key` = " . $value_key . ", `date_modified` = NOW()");
		}
	}

	public function deleteProductFoundation($product_id) {
		$product_id = (int)$product_id;

		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product_value` WHERE `product_id` = '" . $product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . $product_id . "'");
	}

	protected function normalizeProductFoundation(array $data) {
		$foundation = isset($data['kotlyars_catalog_foundation']) ? (array)$data['kotlyars_catalog_foundation'] : array();

		return array(
			'product_type'  => isset($foundation['product_type']) ? $foundation['product_type'] : 'fixed_price',
			'category_code' => isset($foundation['category_code']) ? $foundation['category_code'] : '',
			'attributes'    => isset($foundation['attributes']) ? (array)$foundation['attributes'] : array()
		);
	}

	protected function extractAttributeValue($attribute_code, array $attribute_definition, array $data, array $foundation) {
		if ($attribute_definition['storage'] === 'core') {
			$core_field = $attribute_definition['core_field'];

			if ($core_field === 'manufacturer_id') {
				return !empty($data['manufacturer_id']) ? (int)$data['manufacturer_id'] : '';
			}

			return isset($data[$core_field]) ? $data[$core_field] : '';
		}

		return isset($foundation['attributes'][$attribute_code]) ? trim((string)$foundation['attributes'][$attribute_code]) : '';
	}

	protected function getLinkedCategoryIds($category_code) {
		$category_query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "kotlyars_catalog_category_map` WHERE `category_code` = '" . $this->db->escape($category_code) . "'");

		return array_map('intval', array_column($category_query->rows, 'category_id'));
	}

	protected function getApplicableCategoriesForAttribute($attribute_code, array $category_attribute_map) {
		$categories = array();

		foreach ($category_attribute_map as $category_code => $attribute_rules) {
			foreach ($attribute_rules as $attribute_rule) {
				if ($attribute_rule['code'] === $attribute_code) {
					$categories[] = $category_code;
				}
			}
		}

		return $categories;
	}
}
