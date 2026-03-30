<?php
class ControllerExtensionModuleKotlyarsCatalogFoundationEvent extends Controller {
	public function deleteProductBefore(&$route, &$args) {
		if (!isset($args[0])) {
			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');
		$this->model_extension_module_kotlyars_catalog_foundation->deleteProductFoundation((int)$args[0]);
	}
}
