<?php
class ModelExtensionModuleKotlyarsCatalogFoundation extends Model {
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
}
