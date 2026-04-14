<?php
class ControllerExtensionModuleKotlyarsCatalogFoundation extends Controller {
	public function filterBlock($setting = array()) {
		if (($this->request->get['route'] ?? '') !== 'product/category' || empty($this->request->get['path'])) {
			return '';
		}

		$this->load->language('extension/module/kotlyars_catalog_foundation');
		$this->load->model('extension/module/kotlyars_catalog_foundation');

		$category_id = isset($setting['category_id'])
			? (int)$setting['category_id']
			: $this->model_extension_module_kotlyars_catalog_foundation->getCategoryIdFromPath();

		$data = $this->model_extension_module_kotlyars_catalog_foundation->getLeafFilterBlockData($category_id);

		if (!$data) {
			return '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_current_leaf'] = $this->language->get('text_current_leaf');
		$data['text_filterable_attributes'] = $this->language->get('text_filterable_attributes');
		$data['text_range_filters'] = $this->language->get('text_range_filters');
		$data['text_allowed_range'] = $this->language->get('text_allowed_range');
		$data['text_available_range'] = $this->language->get('text_available_range');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_reset'] = $this->language->get('button_reset');
		$data['form_action'] = 'index.php';
		$data['form_route'] = 'product/category';
		$data['current_path'] = (string)$this->request->get['path'];

		return $this->load->view('extension/module/kotlyars_catalog_foundation', $data);
	}
}
