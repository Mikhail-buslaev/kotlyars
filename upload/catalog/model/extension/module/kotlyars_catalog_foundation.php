<?php
class ModelExtensionModuleKotlyarsCatalogFoundation extends Model {
	protected function getRegistry() {
		$this->load->config('kotlyars_catalog');

		return array(
			'product_types'        => (array)$this->config->get('kotlyars_catalog_product_types'),
			'categories'           => (array)$this->config->get('kotlyars_catalog_category_registry'),
			'attribute_definitions'=> (array)$this->config->get('kotlyars_catalog_attribute_definitions'),
			'category_filters'     => (array)$this->config->get('kotlyars_catalog_category_filter_map'),
			'filter_definitions'   => (array)$this->config->get('kotlyars_catalog_filter_definitions'),
			'auction_placeholders' => (array)$this->config->get('kotlyars_catalog_auction_placeholders')
		);
	}

	public function getProductFoundation($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product` WHERE `product_id` = '" . (int)$product_id . "'");

		if (!$query->num_rows) {
			return array(
				'product_type'  => 'fixed_price',
				'category_code' => '',
				'attributes'    => array()
			);
		}

		$value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "kotlyars_catalog_product_value` WHERE `product_id` = '" . (int)$product_id . "'");

		$attributes = array();

		foreach ($value_query->rows as $row) {
			if ($row['value_key'] !== null && $row['value_key'] !== '') {
				$attributes[$row['attribute_code']] = $row['value_key'];
			} elseif ($row['value_decimal'] !== null) {
				$attributes[$row['attribute_code']] = (float)$row['value_decimal'];
			} elseif ($row['value_int'] !== null) {
				$attributes[$row['attribute_code']] = (int)$row['value_int'];
			} else {
				$attributes[$row['attribute_code']] = $row['value_text'];
			}
		}

		return array(
			'product_type'  => $query->row['product_type'],
			'category_code' => $query->row['category_code'],
			'attributes'    => $attributes
		);
	}

	public function augmentProductInfo(array $product_info) {
		if (empty($product_info['product_id'])) {
			return $product_info;
		}

		$registry = $this->getRegistry();
		$foundation = $this->getProductFoundation($product_info['product_id']);
		$category = isset($registry['categories'][$foundation['category_code']]) ? $registry['categories'][$foundation['category_code']] : array();
		$product_type = isset($registry['product_types'][$foundation['product_type']]) ? $registry['product_types'][$foundation['product_type']] : $registry['product_types']['fixed_price'];

		$product_info['product_type'] = $product_type['code'];
		$product_info['governance_category_code'] = $foundation['category_code'];
		$product_info['governance_category_label'] = isset($category['label']) ? $category['label'] : '';
		$product_info['can_add_to_cart'] = (int)$product_type['cart_available'];
		$product_info['can_checkout'] = (int)$product_type['checkout_available'];
		$product_info['catalog_contract'] = array(
			'product_type'        => $product_type['code'],
			'category_code'       => $foundation['category_code'],
			'category_label'      => isset($category['label']) ? $category['label'] : '',
			'filter_index'        => $this->buildFilterIndex($product_info, $foundation, $registry),
			'future_auction_data' => $registry['auction_placeholders']
		);

		return $product_info;
	}

	protected function buildFilterIndex(array $product_info, array $foundation, array $registry) {
		$filter_codes = isset($registry['category_filters'][$foundation['category_code']]) ? $registry['category_filters'][$foundation['category_code']] : array();
		$filter_index = array();

		foreach ($filter_codes as $filter_code) {
			if (!isset($registry['filter_definitions'][$filter_code])) {
				continue;
			}

			$definition = $registry['filter_definitions'][$filter_code];

			if ($definition['source'] === 'product_type') {
				$filter_index[$filter_code] = $foundation['product_type'];
				continue;
			}

			if ($definition['attribute_code'] === 'price') {
				$filter_index[$filter_code] = isset($product_info['price']) ? $product_info['price'] : null;
			} elseif ($definition['attribute_code'] === 'brand') {
				$filter_index[$filter_code] = isset($product_info['manufacturer']) ? $product_info['manufacturer'] : null;
			} elseif ($definition['attribute_code'] === 'weight') {
				$filter_index[$filter_code] = isset($product_info['weight']) ? $product_info['weight'] : null;
			} elseif ($definition['attribute_code'] === 'quantity') {
				$filter_index[$filter_code] = isset($product_info['quantity']) ? $product_info['quantity'] : null;
			} elseif ($definition['attribute_code'] === 'location') {
				$filter_index[$filter_code] = isset($product_info['location']) ? $product_info['location'] : null;
			} else {
				$filter_index[$filter_code] = isset($foundation['attributes'][$definition['attribute_code']]) ? $foundation['attributes'][$definition['attribute_code']] : null;
			}
		}

		return $filter_index;
	}
}
