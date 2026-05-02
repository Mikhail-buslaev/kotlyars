<?php
class ControllerExtensionTmdTmdMyBid extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/tmd/tmdmybid', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
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

		$module_status	= $this->config->get('tmdauction_status');
		if (isset($module_status)) {
			$data['module_status'] = $module_status;
		}else{
			$data['module_status']= 0;
		}

		if($data['module_status'] != 1){
			$this->response->redirect($this->url->link('common/home', '', true));
		}

		$this->load->language('extension/tmd/tmdmybid');

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
		$mybidhedaing = $auctionsetlables[$this->config->get('config_language_id')]['mybidhedaing'];
		$winningtext = $auctionsetlables[$this->config->get('config_language_id')]['winningtext'];
		if(!empty($mainheading)){
			$data['text_auction'] = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
		}else{
			$data['text_auction'] = $this->language->get('text_auction');
 		}
        if(!empty($mybidhedaing)){
			$data['text_mywinning'] = $auctionsetlables[$this->config->get('config_language_id')]['mybidhedaing'];
		}else{
			$data['text_mywinning'] = $this->language->get('heading_title');
		}
      if(!empty($winningtext)){
			$data['text_winningtext'] = $auctionsetlables[$this->config->get('config_language_id')]['winningtext'];
		}else{
			$data['text_winningtext'] = $this->language->get('text_winningtext');
		}


		$data['breadcrumbs'][] = array(
			'text' => $data['text_auction'],
			'href' => $this->url->link('extension/tmd/tmdauction', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $data['text_mywinning'],
			'href' => $this->url->link('account/order', $url, true)
		);

	

		$module_status = $this->config->get('tmdauction_status');

		if($module_status==1) {
			
			$auctionsetlables=$this->config->get('tmdauction_langauge');
		}
		
		if(!empty($auctionsetlables[$this->config->get('config_language_id')]['mybiddingpagehedaing'])){
			$data['heading_title'] = $auctionsetlables[$this->config->get('config_language_id')]['mybiddingpagehedaing'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$customer_id = $this->customer->getId();
		

		$data['products'] = array();

		$this->load->model('catalog/product');
		$this->load->model('account/customer');
		$this->load->model('extension/tmd/tmdmybid');
		$this->load->model('extension/tmd/tmdmywinningbid');
		$this->load->model('extension/tmd/tmdauction');
		$this->load->model('tool/image');

		$filter_data = array(
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$mybid_total = $this->model_extension_tmd_tmdmybid->getTotalTmdMyBids($filter_data);

		$results = $this->model_extension_tmd_tmdmybid->getTmdMyBids($customer_id,$filter_data);
		
		foreach ($results as $result) {
			$bid_info = $this->model_extension_tmd_tmdauction->getTmdAuctionBid($result['bid_id']);
			
			if(!empty($bid_info['date_added'])){
				$date_added = $bid_info['date_added'];
			}else{
				$date_added = '';
			}

			if(!empty($bid_info['bid_amount'])){
				$bid_amount = $bid_info['bid_amount'];
			}else{
				$bid_amount = 0;
			}

			if(!empty($bid_info['product_id'])){
				$product_id = $bid_info['product_id'];
			}else{
				$product_id = 0;
			}

			$product_info = $this->model_extension_tmd_tmdmywinningbid->getTmdAuctionProduct($bid_info['product_id']);
			
			if (!empty($product_info['image'])) {
				$image = $this->model_tool_image->resize($product_info['image'], 350, 350);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 350, 350);
			}

			if(!empty($product_info['name'])){
				$product_name = $product_info['name'];
			}else{
				$product_name = '';
			}

			if(!empty($product_info['description'])){
				$product_description = $product_info['description'];
			}else{
				$product_description = '';
			}

			if(!empty($product_info['end_time'])){
				$end_time = $product_info['end_time'];
			}else{
				$end_time = '';
			}

			if(!empty($product_info['start_time'])){
				$start_time = $product_info['start_time'];
			}else{
				$start_time = '';
			}

			if(!empty($product_info['auction_id'])){
				$auction_id = $product_info['auction_id'];
			}else{
				$auction_id = '';
			}

			if(!empty($product_info['minimum_quantity'])){
				$minimum = $product_info['minimum_quantity'];
			}else{
				$minimum = 1;
			}

		/// Geting Winner Name If Product Is Won ///
			$winer_info = $this->model_extension_tmd_tmdmybid->getTmdAuctionWinner($bid_info['product_id']);
			
			if(!empty($winer_info['customer_id'])){
				$customer_id = $winer_info['customer_id'];
			}else{
				$customer_id =0;
			}

			if(!empty($winer_info['date_added'])){
				$date_added = $winer_info['date_added'];
			}else{
				$date_added ='';
			}

			if(!empty($winer_info['end_date'])){
				$end_date = $winer_info['end_date'];
			}else{
				$end_date ='';
			}

			if(!empty($winer_info['price'])){
				$winner_bid_amount =$this->currency->format($winer_info['price'], $this->config->get('config_currency'), $this->config->get('currency_value'));
			}else{
				$winner_bid_amount =0;
			}
			$cname_cart='';
			$namelist = $this->model_account_customer->getCustomer($customer_id);

			if(!empty($namelist['firstname']) && $customer_id != $bid_info['customer_id']){
				$cname=$namelist['firstname'].' '.$namelist['lastname'] .' ('.$winner_bid_amount.')';
			}else if($customer_id == $bid_info['customer_id']){
				$cname=' ('.$winner_bid_amount.')';
				$cname_cart  ='show';
			}else{
				$cname='';
	
			}
			$auc_pro_info = $this->model_extension_tmd_tmdmybid->getAuctionProductOrder($bid_info['product_id']);

			if(!empty($auc_pro_info['product_id'])){
				$order_product_id = $auc_pro_info['product_id'];
			}else{
				$order_product_id =0;
			}
		/// Geting Winner Name If Product Is Won ///
			
			$data['products'][] = array(
				'bid_id' 		=> $result['bid_id'],
				'product_id' 	=> $product_id,
				'order_product_id' 	=> $order_product_id,
				'date_added' 	=> $date_added,
				'auction_id' 	=> $auction_id,
				'minimum' 		=> $minimum,
				'cname_caret' 	=> $cname_cart,
				'image' 		=> $image,
				'product_name' 	=> $product_name,
				'date_added'  	=> $date_added,
				'order_end_date'=> $end_date,
				'start_time'  	=> strtotime($start_time),
				'end_time' 		=> strtotime($end_time),
					'time'        	  => time(),
				'cname' 		=> $cname,
				'bid_amount' 	=> $this->currency->format($bid_amount, $this->config->get('config_currency'), $this->config->get('currency_value')),
					'date'        	  => strtotime(date('Y-m-d H:i:s')),
				'description' 	=> utf8_substr(strip_tags(html_entity_decode($product_description, ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
				'href'        	=> $this->url->link('product/product&product_id=' . $product_id . $url)
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
		$pagination->total = $mybid_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/tmd/tmdmybid','&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($mybid_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($mybid_total - $limit)) ? $mybid_total : ((($page - 1) * $limit) + $limit), $mybid_total, ceil($mybid_total / $limit));

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/tmd/tmdmybid', $data));
	}
}