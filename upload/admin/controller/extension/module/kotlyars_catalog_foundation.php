<?php
class ControllerExtensionModuleKotlyarsCatalogFoundation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/kotlyars_catalog_foundation');
		$this->load->model('setting/setting');
		$this->load->model('extension/module/kotlyars_catalog_foundation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_kotlyars_catalog_foundation', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array(
			array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			),
			array(
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			),
			array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/kotlyars_catalog_foundation', 'user_token=' . $this->session->data['user_token'], true)
			)
		);

		$data['action'] = $this->url->link('extension/module/kotlyars_catalog_foundation', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$data['register_modification'] = $this->url->link('extension/module/kotlyars_catalog_foundation/registerModification', 'user_token=' . $this->session->data['user_token'], true);
		$data['refresh_modifications'] = $this->url->link('marketplace/modification/refresh', 'user_token=' . $this->session->data['user_token'], true);
		$data['module_kotlyars_catalog_foundation_status'] = isset($this->request->post['module_kotlyars_catalog_foundation_status']) ? $this->request->post['module_kotlyars_catalog_foundation_status'] : $this->config->get('module_kotlyars_catalog_foundation_status');
		$data['overview'] = $this->model_extension_module_kotlyars_catalog_foundation->getOverview();
		$data['modification_info'] = $this->model_extension_module_kotlyars_catalog_foundation->getModificationStatus();
		$data['entry_status'] = $this->language->get('entry_status');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_modification_status_registered'] = $this->language->get('text_modification_status_registered');
		$data['text_modification_status_missing'] = $this->language->get('text_modification_status_missing');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_register_modification'] = $this->language->get('button_register_modification');
		$data['button_refresh_modifications'] = $this->language->get('button_refresh_modifications');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/kotlyars_catalog_foundation', $data));
	}

	public function install() {
		$this->load->model('extension/module/kotlyars_catalog_foundation');
		$this->load->model('setting/setting');

		$this->model_extension_module_kotlyars_catalog_foundation->install();
		$this->model_setting_setting->editSetting('module_kotlyars_catalog_foundation', array(
			'module_kotlyars_catalog_foundation_status' => 1
		));
	}

	public function uninstall() {
		$this->load->model('extension/module/kotlyars_catalog_foundation');
		$this->model_extension_module_kotlyars_catalog_foundation->uninstall();
	}

	public function registerModification() {
		$this->load->language('extension/module/kotlyars_catalog_foundation');

		if (!$this->user->hasPermission('modify', 'extension/module/kotlyars_catalog_foundation')) {
			$this->session->data['error_warning'] = $this->language->get('error_permission');
			$this->response->redirect($this->url->link('extension/module/kotlyars_catalog_foundation', 'user_token=' . $this->session->data['user_token'], true));

			return;
		}

		$this->load->model('extension/module/kotlyars_catalog_foundation');

		try {
			$this->model_extension_module_kotlyars_catalog_foundation->installModification();
			$this->session->data['success'] = $this->language->get('text_modification_registered');
		} catch (Exception $exception) {
			$this->session->data['error_warning'] = sprintf($this->language->get('error_modification_register'), $exception->getMessage());
		}

		$this->response->redirect($this->url->link('extension/module/kotlyars_catalog_foundation', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function productTab($setting = array()) {
		$this->load->language('extension/module/kotlyars_catalog_foundation');
		$this->load->model('extension/module/kotlyars_catalog_foundation');

		$data = $this->model_extension_module_kotlyars_catalog_foundation->getProductFoundationState(
			isset($setting['product_id']) ? (int)$setting['product_id'] : 0,
			isset($setting['post']) ? (array)$setting['post'] : array(),
			isset($setting['product_category']) ? (array)$setting['product_category'] : array(),
			isset($setting['errors']) ? (array)$setting['errors'] : array()
		);

		$data['heading_panel'] = $this->language->get('heading_panel');
		$data['entry_product_type'] = $this->language->get('entry_product_type');
		$data['entry_category_code'] = $this->language->get('entry_category_code');
		$data['entry_governance_attributes'] = $this->language->get('entry_governance_attributes');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_filter_preview'] = $this->language->get('text_filter_preview');
		$data['text_no_filters'] = $this->language->get('text_no_filters');
		$data['text_phase_boundary'] = $this->language->get('text_phase_boundary');
		$data['text_auction_placeholders'] = $this->language->get('text_auction_placeholders');
		$data['text_native_field'] = $this->language->get('text_native_field');
		$data['text_supported_product_types'] = $this->language->get('text_supported_product_types');
		$data['text_selected_category'] = $this->language->get('text_selected_category');
		$data['text_no_attributes'] = $this->language->get('text_no_attributes');
		$data['text_category_alignment'] = $this->language->get('text_category_alignment');
		$data['help_product_type'] = $this->language->get('help_product_type');
		$data['help_category_code'] = $this->language->get('help_category_code');

		return $this->load->view('extension/module/kotlyars_catalog_foundation_product_tab', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/kotlyars_catalog_foundation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
