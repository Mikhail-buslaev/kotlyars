<?php
class ControllerExtensionModuleKotlyarsCatalogFoundationEvent extends Controller {
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
