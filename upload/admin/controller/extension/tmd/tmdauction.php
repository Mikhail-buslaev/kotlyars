<?php
class ControllerExtensionTmdTmdAuction extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/tmd/tmdauction');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/tmdauction');

		$this->getList();
	}

	public function add() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->load->language('extension/tmd/tmdauction');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/tmdauction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_tmd_tmdauction->addTmdAuctionProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true));
		}

		$this->getForm();
	}

	public function edit() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->load->language('extension/tmd/tmdauction');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/tmdauction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_tmd_tmdauction->editTmdAuctionProduct($this->request->get['auction_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_start_bids'])) {
				$url .= '&filter_start_bids=' . urlencode(html_entity_decode($this->request->get['filter_start_bids'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_start_date'])) {
				$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
			}

			if (isset($this->request->get['filter_end_date'])) {
				$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true));
		}

		$this->getForm();
	}

	public function delete() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->load->language('extension/tmd/tmdauction');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/tmdauction');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $auction_id) {
				$this->model_extension_tmd_tmdauction->deleteTmdAuctionProduct($auction_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_start_bids'])) {
			$filter_start_bids = $this->request->get['filter_start_bids'];
		} else {
			$filter_start_bids = null;
		}

		if (isset($this->request->get['filter_start_date'])) {
			$filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = null;
		}

		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = null;
		}

		if (isset($this->request->get['filter_image'])) {
			$filter_image = $this->request->get['filter_image'];
		} else {
			$filter_image = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_bids'])) {
			$url .= '&filter_start_bids=' . urlencode(html_entity_decode($this->request->get['filter_start_bids'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenexchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true)
		);

		$data['add'] 	= $this->url->link('extension/tmd/tmdauction/add', $tokenexchange . $url, true);
		$data['delete'] = $this->url->link('extension/tmd/tmdauction/delete',$tokenexchange . $url, true);

		$data['auction_products'] = array();

		$filter_data = array(
			'filter_name'	  		=> $filter_name,
			'filter_start_bids'	  	=> $filter_start_bids,
			'filter_start_date'	  	=> $filter_start_date,
			'filter_end_date' 		=> $filter_end_date,
			'filter_image'    		=> $filter_image,
			'sort'            		=> $sort,
			'order'           		=> $order,
			'start'           		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           		=> $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$this->load->model('extension/tmd/tmdauction');

		$auction_total 	= $this->model_extension_tmd_tmdauction->getTotalTmdAuctionProducts($filter_data);

		$results 		= $this->model_extension_tmd_tmdauction->getTmdAuctionProducts($filter_data);

		foreach ($results as $result) {

			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['auction_products'][] = array(
				'auction_id' 		=> $result['auction_id'],
				'product_id' 		=> $result['product_id'],
				'product_name' 		=> $result['name'],
				'image'      		=> $image,
				'auto_finish'       => $result['auto_finish'],
				'start_time'      	=> $result['start_time'],
				'end_time'      	=> $result['end_time'],
				'starting_bids'   	=> $this->currency->format($result['starting_bids'], $this->config->get('config_currency')),
				'max_price'   		=> $result['max_price'],
				'auto_finish'     	=> $result['auto_finish'] ? $this->language->get('text_on') : $this->language->get('text_off'),
				'edit'       		=> $this->url->link('extension/tmd/tmdauction/edit', $tokenexchange . '&auction_id=' . $result['auction_id'] . $url, true)
			);
		}

		$data['heading_title'] 		= $this->language->get('heading_title');

	

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_bids'])) {
			$url .= '&filter_start_bids=' . urlencode(html_entity_decode($this->request->get['filter_start_bids'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if (isset($this->request->get['filter_image'])) {
			$url .= '&filter_image=' . $this->request->get['filter_image'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] 			= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=pd.name' . $url, true);
		$data['sort_start_bids'] 	= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=p.start_bids' . $url, true);
		$data['sort_price'] 		= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=p.price' . $url, true);
		$data['sort_start_date'] 	= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=p.start_date' . $url, true);
		$data['sort_end_date'] 		= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=p.end_date' . $url, true);
		$data['sort_status'] 		= $this->url->link('extension/tmd/tmdauction', $tokenexchange . '&sort=p.sort_status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_bids'])) {
			$url .= '&filter_start_bids=' . urlencode(html_entity_decode($this->request->get['filter_start_bids'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if (isset($this->request->get['filter_image'])) {
			$url .= '&filter_image=' . $this->request->get['filter_image'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination 		= new Pagination();
		$pagination->total 	= $auction_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $this->config->get('config_limit_admin');
		$pagination->url 	= $this->url->link('extension/tmd/tmdauction', $tokenexchange . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] 	= sprintf($this->language->get('text_pagination'), ($auction_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($auction_total - $this->config->get('config_limit_admin'))) ? $auction_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $auction_total, ceil($auction_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] 		= $filter_name;
		$data['filter_start_bids'] 	= $filter_start_bids;
		$data['filter_start_date'] 	= $filter_start_date;
		$data['filter_end_date'] 	= $filter_end_date;
		$data['filter_image'] 		= $filter_image;

		$data['sort'] 		= $sort;
		$data['order'] 		= $order;

		$data['header'] 	= $this->load->controller('common/header');
		$data['column_left']= $this->load->controller('common/column_left');
		$data['footer'] 	= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmd/tmdauction_list', $data));
	}

	protected function getForm() {

		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}
		$this->load->language('extension/tmd/tmdauction');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['auction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

	

		if (isset($this->error['product_id'])) {
			$data['error_product_id'] = $this->error['product_id'];
		} else {
			$data['error_product_id'] = '';
		}

		if (isset($this->error['product_id2'])) {
			$data['error_product_id'] = $this->error['product_id2'];
		} else {
			$data['error_product_id2'] = '';
		}

		if (isset($this->error['max_price'])) {
			$data['error_max_price'] = $this->error['max_price'];
		} else {
			$data['error_max_price'] = '';
		}

		if (isset($this->error['start_time'])) {
			$data['error_start_time'] = $this->error['start_time'];
		} else {
			$data['error_start_time'] = '';
		}

		if (isset($this->error['end_time'])) {
			$data['error_end_time'] = $this->error['end_time'];
		} else {
			$data['error_end_time'] = '';
		}

		if (isset($this->error['starting_bids'])) {
			$data['error_starting_bids'] = $this->error['starting_bids'];
		} else {
			$data['error_starting_bids'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenexchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true)
		);
		if (!isset($this->request->get['auction_id'])) {
			$data['action'] = $this->url->link('extension/tmd/tmdauction/add', $tokenexchange . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/tmd/tmdauction/edit', $tokenexchange . '&auction_id=' . $this->request->get['auction_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/tmd/tmdauction', $tokenexchange . $url, true);

		if (isset($this->request->get['auction_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$auction_info = $this->model_extension_tmd_tmdauction->getTmdAuctionProduct($this->request->get['auction_id']);
		}

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($auction_info)) {
			$data['product_id'] = $auction_info['product_id'];
		} else {
			$data['product_id'] = 0;
		}

		if (!empty($auction_info['product_id'])) {
			$data['pro_id'] = $auction_info['product_id'];
		} else {
			$data['pro_id'] = 0;
		}

		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($data['product_id']);

		if (isset($product_info['name'])) {
			$data['product_name'] = $product_info['name'];
		}else {
			$data['product_name'] = '';
		}

		if (isset($this->request->post['auto_finish'])) {
			$data['auto_finish'] = $this->request->post['auto_finish'];
		} elseif (!empty($auction_info)) {
			$data['auto_finish'] = $auction_info['auto_finish'];
		} else {
			$data['auto_finish'] = '';
		}

		if (isset($this->request->post['active'])) {
			$data['active'] = $this->request->post['active'];
		} elseif (!empty($auction_info)) {
			$data['active'] = $auction_info['active'];
		} else {
			$data['active'] = '';
		}

		if (isset($this->request->post['start_time'])) {
			$data['start_time'] = $this->request->post['start_time'];
		} elseif (!empty($auction_info)) {
			$data['start_time'] = $auction_info['start_time'];
		} else {
			$data['start_time'] = '';
		}

		if (isset($this->request->post['end_time'])) {
			$data['end_time'] = $this->request->post['end_time'];
		} elseif (!empty($auction_info)) {
			$data['end_time'] = $auction_info['end_time'];
		} else {
			$data['end_time'] = '';
		}

		if (isset($this->request->post['starting_bids'])) {
			$data['starting_bids'] = $this->request->post['starting_bids'];
		} elseif (!empty($auction_info)) {
			$data['starting_bids'] = $auction_info['starting_bids'];
		} else {
			$data['starting_bids'] = '';
		}

		if (isset($this->request->post['max_price'])) {
			$data['max_price'] = $this->request->post['max_price'];
		} elseif (!empty($auction_info)) {
			$data['max_price'] = $auction_info['max_price'];
		} else {
			$data['max_price'] = '';
		}

		if (isset($this->request->post['minimum_quantity'])) {
			$data['minimum_quantity'] = $this->request->post['minimum_quantity'];
		} elseif (!empty($auction_info)) {
			$data['minimum_quantity'] = $auction_info['minimum_quantity'];
		} else {
			$data['minimum_quantity'] = '';
		}

		if (isset($this->request->post['maximum_quantity'])) {
			$data['maximum_quantity'] = $this->request->post['maximum_quantity'];
		} elseif (!empty($auction_info)) {
			$data['maximum_quantity'] = $auction_info['maximum_quantity'];
		} else {
			$data['maximum_quantity'] = '';
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmd/tmdauction_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/tmd/tmdauction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((isset($this->request->post['auto_finish']) && $this->request->post['auto_finish'] ==1) && (utf8_strlen($this->request->post['max_price'] < 1) || $this->request->post['max_price'] < $this->request->post['starting_bids'])) {
			$this->error['max_price'] = $this->language->get('error_max_price');
		}

		if(isset($this->request->post['product_id'])){
			$this->load->model('extension/tmd/tmdauction');
			$pro_info = $this->model_extension_tmd_tmdauction->getTmdAuctionProductByproId($this->request->post['product_id']);
		}

		if(isset($pro_info['product_id'])){
			$pro_id = $pro_info['product_id'];
		}else{
			$pro_id = 0;
		}
		if (isset($this->request->post['product_id']) && $this->request->post['product_id'] ==$pro_id && !empty($this->request->post['product_id'])) {
			$this->error['product_id2'] = $this->language->get('error_product_id2');
		}

		if (isset($this->request->post['product_id']) && $this->request->post['product_id'] =='') {
			$this->error['product_id'] = $this->language->get('error_product_id');
		}

		if ($this->request->post['start_time'] =='') {
			$this->error['start_time'] = $this->language->get('error_start_time');
		}

		if ($this->request->post['end_time'] =='') {
			$this->error['end_time'] = $this->language->get('error_end_time');
		}

		if ((utf8_strlen($this->request->post['starting_bids']) < 1 )) {
			$this->error['starting_bids'] = $this->language->get('error_starting_bids');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/tmd/tmdauction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
