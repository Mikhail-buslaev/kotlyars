<?php
class ControllerExtensionModuleKotlyarsCatalogFoundationEvent extends Controller {
	public function categoryControllerBefore(&$route, &$args) {
		if (($this->request->get['route'] ?? '') !== 'product/category' || empty($this->request->get['path'])) {
			return;
		}

		if ($this->config->get('config_theme') !== 'journal3') {
			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');

		$category_id = $this->model_extension_module_kotlyars_catalog_foundation->getCategoryIdFromPath();
		$context = $this->model_extension_module_kotlyars_catalog_foundation->getCategoryFilterContext($category_id);

		if (!$context['is_leaf']) {
			return;
		}

		$this->document->addStyle('catalog/view/theme/journal3/stylesheet/kotlyars_catalog_foundation.css');
	}

	public function categoryViewBefore(&$route, &$args) {
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

		if (!$filter_output) {
			return;
		}

		if (!empty($args['column_left']) && strpos($args['column_left'], '<aside') !== false) {
			$args['column_left'] = preg_replace('/(<aside\b[^>]*>)/i', '$1' . $filter_output, $args['column_left'], 1);
			return;
		}

		if (empty($args['column_left'])) {
			$args['column_left'] = '<aside id="column-left" class="side-column">' . $filter_output . '</aside>';
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
