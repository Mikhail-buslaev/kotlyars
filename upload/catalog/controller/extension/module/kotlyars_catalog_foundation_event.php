<?php
class ControllerExtensionModuleKotlyarsCatalogFoundationEvent extends Controller {
	public function columnLeftAfter(&$route, &$args, &$output) {
		if (($this->request->get['route'] ?? '') !== 'product/category' || empty($this->request->get['path'])) {
			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');

		$category_id = $this->model_extension_module_kotlyars_catalog_foundation->getCategoryIdFromPath();
		$context = $this->model_extension_module_kotlyars_catalog_foundation->getCategoryFilterContext($category_id);

		if (!$context['is_leaf']) {
			return;
		}

		$filter_output = $this->load->controller('extension/module/kotlyars_catalog_foundation/filterBlock', array(
			'category_id' => $category_id
		));

		if ($filter_output) {
			$output = $filter_output . $output;
		}
	}

	public function getProductAfter(&$route, &$args, &$output) {
		if (!$output || !is_array($output)) {
			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');
		$output = $this->model_extension_module_kotlyars_catalog_foundation->augmentProductInfo($output);
	}

	public function getProductsAfter(&$route, &$args, &$output) {
		if (!$output || !is_array($output)) {
			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');

		foreach ($output as $key => $product_info) {
			if (is_array($product_info)) {
				$output[$key] = $this->model_extension_module_kotlyars_catalog_foundation->augmentProductInfo($product_info);
			}
		}
	}
}
