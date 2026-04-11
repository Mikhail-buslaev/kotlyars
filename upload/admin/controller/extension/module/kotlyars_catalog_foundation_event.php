<?php
class ControllerExtensionModuleKotlyarsCatalogFoundationEvent extends Controller {
	public function addProductAfter(&$route, &$args, &$output) {
		if (!$output || !isset($args[0]) || !is_array($args[0])) {
			return;
		}

		try {
			$this->load->model('extension/module/kotlyars_catalog_foundation');
			$this->model_extension_module_kotlyars_catalog_foundation->saveProductFoundation((int)$output, $args[0]);
		} catch (Exception $exception) {
			$this->log->write('Kotlyars Catalog Foundation addProductAfter: ' . $exception->getMessage());
		}
	}

	public function editProductAfter(&$route, &$args, &$output) {
		if (!isset($args[0]) || !isset($args[1]) || !is_array($args[1])) {
			return;
		}

		try {
			$this->load->model('extension/module/kotlyars_catalog_foundation');
			$this->model_extension_module_kotlyars_catalog_foundation->saveProductFoundation((int)$args[0], $args[1]);
		} catch (Exception $exception) {
			$this->log->write('Kotlyars Catalog Foundation editProductAfter: ' . $exception->getMessage());
		}
	}

	public function deleteProductBefore(&$route, &$args) {
		if (!isset($args[0])) {
			return;
		}

		try {
			$this->load->model('extension/module/kotlyars_catalog_foundation');
			$this->model_extension_module_kotlyars_catalog_foundation->deleteProductFoundation((int)$args[0]);
		} catch (Exception $exception) {
			$this->log->write('Kotlyars Catalog Foundation deleteProductBefore: ' . $exception->getMessage());
		}
	}
}
