<?php
class ControllerExtensionTmdTmdMyWinningBid extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/tmd/tmdmywinningbid', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$module_status	= $this->config->get('tmdauction_status');
		if (isset($module_status)) {
			$data['module_status'] = $module_status;
		}else{
			$data['module_status']= 0;
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$auctionlimit = $this->config->get('tmdauction_limit');
		if (!empty($auctionlimit)) {
			$limit =  $this->config->get('tmdauction_limit');
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		if($data['module_status'] != 1){
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/tmd/tmdmywinningbid');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$auctionsetlables=$this->config->get('tmdauction_langauge');
		$mainheading = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
		$mywinningbidhedaing = $auctionsetlables[$this->config->get('config_language_id')]['mywinningbidhedaing'];
		if(!empty($mainheading)){
			$data['text_tmdauction'] = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
		}else{
			$data['text_tmdauction'] = $this->language->get('text_tmdauction');
 		}
        if(!empty($mywinningbidhedaing)){
			$data['text_tmdwinning'] = $auctionsetlables[$this->config->get('config_language_id')]['mywinningbidhedaing'];
		}else{
			$data['text_tmdwinning'] = $this->language->get('text_tmdwinning');
		}


		$data['breadcrumbs'][] = array(
			'text' => $data['text_tmdauction'],
			'href' => $this->url->link('extension/tmd/tmdauction', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $data['text_tmdwinning'],
			'href' => $this->url->link('extension/tmd/tmdmywinningbid', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

	

		$module_status = $this->config->get('tmdauction_status');

			if($module_status==1) {
				
				$auctionsetlables=$this->config->get('tmdauction_langauge');
			}
			
			if(!empty($auctionsetlables[$this->config->get('config_language_id')]['mywinningpageheading'])){
				$data['text_winning'] = $auctionsetlables[$this->config->get('config_language_id')]['mywinningpageheading'];
			} else {
				$data['text_winning'] = $this->language->get('text_winning');
			}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$this->load->model('extension/tmd/tmdauction');
		$this->load->model('extension/tmd/tmdmywinningbid');
		$this->load->model('account/customer');

		$filter_data = array(
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);
		$data['products'] = array();
		$winnbid_total = $this->model_extension_tmd_tmdmywinningbid->getTotalproducts($filter_data);
		$results = $this->model_extension_tmd_tmdmywinningbid->getTmdAuctionWinners($this->customer->getId(),$filter_data);
		foreach ($results as $result) {
			$bid_info = $this->model_extension_tmd_tmdauction->getTmdAuctionBid($result['bid_id']);

			$product_info = $this->model_extension_tmd_tmdmywinningbid->getTmdAuctionProduct($result['product_id']);

			if(!empty($product_info['name'])){
				$product_name = $product_info['name'];
			}else{
				$product_name = '';
			}

			if (is_file(DIR_IMAGE . $product_info['image'])) {
				$image = $this->model_tool_image->resize($product_info['image'], 350, 350);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 350, 350);
			}

			if(!empty($product_info['description'])){
				$description = $product_info['description'];
			}else{
				$description = '';
			}

			if(!empty($product_info['minimum_quantity'])){
				$minimum = $product_info['minimum_quantity'];
			}else{
				$minimum = 1;
			}


			$auc_pro_info = $this->model_extension_tmd_tmdmywinningbid->getAuctionProductOrder($result['product_id']);

			if(!empty($auc_pro_info['product_id'])){
				$order_product_id = $auc_pro_info['product_id'];
			}else{
				$order_product_id =0;
			}

			$data['products'][] = array(
				'bid_amount' 			=> $this->currency->format($this->tax->calculate($result['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'date_added' 			=> $result['date_added'], 
				'image' 				=> $image, 
				'minimum' 				=> $minimum, 
				'product_name' 			=> $product_name, 
				'product_id' 			=> $result['product_id'], 
				'date' 					=> date('Y-m-d H:i:s'), 
				'order_end_date' 		=> $result['end_date'], 
				'order_product_id' 		=> $order_product_id, 
				'description' 			=> utf8_substr(strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
				'href'        	=> $this->url->link('product/product&product_id=' . $result['product_id'] . $url) 
			
			);

		}
		$url = '';
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $winnbid_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/tmd/tmdmywinningbid', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($winnbid_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($winnbid_total - $limit)) ? $winnbid_total : ((($page - 1) * $limit) + $limit), $winnbid_total, ceil($winnbid_total / $limit));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/tmd/tmdmywinningbid', $data));
	}
}