<?php
class ControllerExtensionModuleTmdBid extends Controller {
	public function index() {
		$this->load->model('extension/tmdauction');
		$status = true;

		$module_status	= $this->config->get('tmdauction_status');
		if ($status) {
			$data['module_status'] = $module_status;
		}else{
			$data['module_status'] = 0;
		}

		$status = $this->config->get('tmdbid_status');

		if ($status) {
			$this->load->language('extension/module/tmdbid');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_tmdbid'] = $this->language->get('text_tmdbid');

			$data['tmdbid_id'] = $this->config->get('config_tmdbid_id');

			$data['total_bid'] 			= $this->config->get('tmdauction_total_bid');
			$data['latest_bid'] 		= $this->config->get('tmdauction_latest_bid');
			$data['manimum_quantity'] 	= $this->config->get('tmdauction_manimum_quantity');
			$data['maximum_quantity'] 	= $this->config->get('tmdauction_maximum_quantity');
			$data['bid_start_date'] 	= $this->config->get('tmdauction_bid_start_date');
			$data['bid_stop_date'] 		= $this->config->get('tmdauction_bid_stop_date');
			$data['maxbid'] 			= $this->config->get('tmdauction_maxbid');
			$data['text_hurry_up'] 			= $this->config->get('text_hurry_up');
			$data['text_expired'] 			= $this->config->get('text_expired');
			
			$data['text_participating'] 	= $this->language->get('text_participating');
			$data['text_normal_bid'] 		= $this->language->get('text_normal_bid');
			$data['button_bid_now'] 		= $this->language->get('button_bid_now');

			if($data['module_status']==1) {

				$this->load->model('tool/image');
				$tmdauction_banner = $this->config->get('tmdauction_banner');
				if(!empty($tmdauction_banner)){
					$data['thumbheaderlogo'] = $this->model_tool_image->resize($tmdauction_banner, 1300, 1000);
				}else{
					$data['thumbheaderlogo'] =$this->model_tool_image->resize('no_image.png', 1300, 1000);
				}

				$auctionsetlables=$this->config->get('tmdauction_langauge');
				$titleinpropage 	= $auctionsetlables[$this->config->get('config_language_id')]['titleinpropage'];
				
				$inputplace 		= $auctionsetlables[$this->config->get('config_language_id')]['inputplace'];
				
				$biddinglist 		= $auctionsetlables[$this->config->get('config_language_id')]['biddinglist'];
				$biddername 		= $auctionsetlables[$this->config->get('config_language_id')]['biddername'];
				$biddingamount 		= $auctionsetlables[$this->config->get('config_language_id')]['biddingamount'];
				$dateadded 			= $auctionsetlables[$this->config->get('config_language_id')]['dateadded'];
				$bidtype 			= $auctionsetlables[$this->config->get('config_language_id')]['bidtype'];
				$auctiondetail 		= $auctionsetlables[$this->config->get('config_language_id')]['auctiondetail'];
				$totalbid 			= $auctionsetlables[$this->config->get('config_language_id')]['totalbid'];
				$currentbid 		= $auctionsetlables[$this->config->get('config_language_id')]['currentbid'];
				$minquantity 		= $auctionsetlables[$this->config->get('config_language_id')]['minquantity'];
				$maxquantity 		= $auctionsetlables[$this->config->get('config_language_id')]['maxquantity'];
				$starttime 			= $auctionsetlables[$this->config->get('config_language_id')]['starttime'];
				$stoptime 			= $auctionsetlables[$this->config->get('config_language_id')]['stoptime'];
				$bidbutton 			= $auctionsetlables[$this->config->get('config_language_id')]['bidbutton'];
			}
			
			if(!empty($inputplace)){
				$data['text_hurry_up'] = $inputplace;
			} else {
				$data['text_hurry_up'] = $this->language->get('text_hurry_up');
			}
			
			if(!empty($titleinpropage)){
				$data['entry_titleinpropage'] = $titleinpropage;
			} else {
				$data['entry_titleinpropage'] = $this->language->get('entry_titleinpropage');
			}

			if(!empty($biddinglist)){
				$data['text_bidding_list'] = $biddinglist;
			} else {
				$data['text_bidding_list'] = $this->language->get('text_bidding_list');
			}

			if(!empty($biddername)){
				$data['text_bidder_name'] = $biddername;
			} else {
				$data['text_bidder_name'] = $this->language->get('text_bidder_name');
			}

			if(!empty($biddingamount)){
				$data['text_bidder_amount'] = $biddingamount;
			} else {
				$data['text_bidder_amount'] = $this->language->get('text_bidder_amount');
			}

			if(!empty($dateadded)){
				$data['text_bidder_date_add'] = $dateadded;
			} else {
				$data['text_bidder_date_add'] = $this->language->get('text_bidder_date_add');
			}

			if(!empty($bidtype)){
				$data['text_bid_type'] = $bidtype;
			} else {
				$data['text_bid_type'] = $this->language->get('text_bid_type');
			}

			if(!empty($auctiondetail)){
				$data['text_auction_detail'] = $auctiondetail;
			} else {
				$data['text_auction_detail'] = $this->language->get('text_auction_detail');
			}

			if(!empty($totalbid)){
				$data['text_total_bid'] = $totalbid;
			} else {
				$data['text_total_bid'] = $this->language->get('text_total_bid');
			}

			if(!empty($currentbid)){
				$data['text_current_bid'] = $currentbid;
			} else {
				$data['text_current_bid'] = $this->language->get('text_current_bid');
			}

			if(!empty($minquantity)){
				$data['text_min_quantity'] = $minquantity;
			} else {
				$data['text_min_quantity'] = $this->language->get('text_min_quantity');
			}

			if(!empty($maxquantity)){
				$data['text_max_quantity'] = $maxquantity;
			} else {
				$data['text_max_quantity'] = $this->language->get('text_max_quantity');
			}

			if(!empty($starttime)){
				$data['text_start_time'] = $starttime;
			} else {
				$data['text_start_time'] = $this->language->get('text_start_time');
			}

			if(!empty($stoptime)){
				$data['text_stop_time'] = $stoptime;
			} else {
				$data['text_stop_time'] = $this->language->get('text_stop_time');
			}

			if(!empty($bidbutton)){
				$data['button_bid_now'] = $bidbutton;
			} else {
				$data['button_bid_now'] = $this->language->get('button_bid_now');
			}

			$data['tmdbids'] = array();

			$data['tmdbids'][] = array(
				'tmdbid_id' => 0,
				'name'     => $this->language->get('text_default'),
				'url'      => HTTP_SERVER . 'index.php?route=common/home&session_id=' . $this->session->getId()
			);

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
			$data['product_id'] = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
			$data['product_id'] = 0;
		}
	
		$this->load->model('extension/tmdauction');
		$this->load->model('extension/tmdmywinningbid');
		$auction_info = $this->model_extension_tmdmywinningbid->getTmdAuctionProduct($product_id);

	

		$data['total'] 		= $this->model_extension_tmdauction->getTmdTotalBid($product_id);
		$max_bid 			= $this->model_extension_tmdauction->getTmdMaxAuctionBid($product_id);
		$this->load->model('extension/tmdmywinningbid');
		$product_info = $this->model_extension_tmdmywinningbid->getTmdAuctionProduct($product_id);
		if(isset($max_bid)){
			$data['max_bid'] = $this->currency->format($this->tax->calculate($max_bid, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		}else{
			$data['max_bid'] = $this->language->get('text_firstbidder');
		}

		if(!empty($auction_info['auction_id'])){
			$data['auction_id'] = $auction_info['auction_id'];
		}else{
			$data['auction_id'] = 0;
		}

		if(!empty($auction_info['active'])){
			$data['active'] = $auction_info['active'];
		}else{
			$data['active'] = 0;
		}

		if(!empty($auction_info['start_time'])){
			$data['start_time'] = strtotime($auction_info['start_time']);
		}else{
			$data['start_time'] = '';
		}
		
		if(!empty($auction_info['product_id'])){
			$data['auction_info'] = $auction_info['product_id'];
		}else{
			$data['auction_info'] = '';
		}

		if(!empty($auction_info['end_time'])){
			$data['end_time'] = strtotime($auction_info['end_time']);
			$data['endtime'] = $auction_info['end_time'];
		}else{
			$data['end_time'] = '';
		}

		$data['time'] = time();
		$data['currentdate'] = date('Y-m-d H:i:s');

		$start_time=$data['start_time'];
		$end_time=$data['end_time'];
		$currentdate=strtotime(date('Y-m-d H:i:s'));
		if($start_time < $currentdate && $end_time >= $currentdate)
		{
			$data['active_couter']=1;
		} 
		else
		{
			$data['active_couter']=0;
		}
	
		if(!empty($auction_info['start_time'])){
			$data['start_time'] = $auction_info['start_time'];
		}else{
			$data['start_time'] = '';
		}

		if(!empty($auction_info['minimum_quantity'])){
			$data['minimum_quantity'] = $auction_info['minimum_quantity'];
		}else{
			$data['minimum_quantity'] = 'NA';
		}

		if(!empty($auction_info['maximum_quantity'])){
			$data['maximum_quantity'] = $auction_info['maximum_quantity'];
		}else{
			$data['maximum_quantity'] = 'NA';
		}

		$this->load->model('extension/tmdmybid');
		$win_info = $this->model_extension_tmdmybid->getTmdAuctionWinner($product_id);
		if(isset($win_info['product_id'])){
			$data['win_product_id'] = $win_info['product_id'];
		}else{
			$data['win_product_id'] = 0;
		}
		
		$data['tmdmybid']=str_replace('&amp;','&',$this->url->link('extension/module/tmdbid/bidPages&product_id='.$product_id.''));
			return $this->load->view('extension/module/tmdbid', $data);
		}
	}

	public function tmdTimestamp() {
		
		if (isset($this->request->get['auction_id'])) {
			$auction_id = (int)$this->request->get['auction_id'];
		} else {
			$auction_id = 0;
		}

		$this->load->model('extension/tmdauction');
		$auction_info = $this->model_extension_tmdauction->getTmdAuction($auction_id);

		if(isset($auction_info['end_time'])){
			$end_time = strtotime($auction_info['end_time']);
		}else{
			$end_time = '';
		}

		$currentdate_local = date('Y-m-d H:i:s');
		$currentdate = date('Y-m-d H:i:s');

		if ($end_time >=strtotime($currentdate) ) {
			$json['currentdate'] = date('Y-m-d H:i:s');
		
		}else{
			$json['currentdate'] = 0;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function bidPages() {
		$this->load->model('extension/tmdauction');
		$status = true;

		$module_status	= $this->config->get('tmdauction_status');
		if ($status) {
			$data['module_status'] = $module_status;
		}else{
			$data['module_status'] = 0;
		}

		$this->load->language('extension/module/tmdbid');
		if($module_status==1) {
			
			$auctionsetlables=$this->config->get('tmdauction_langauge');
			
			if(!empty($auctionsetlables[$this->config->get('config_language_id')]['normalbildlisttext'])){
				$data['text_normal_bid'] = $auctionsetlables[$this->config->get('config_language_id')]['normalbildlisttext'];
			} else {
				$data['text_normal_bid'] = $this->language->get('text_normal_bid');
			}
		}


		$data['bidder_name'] 		= $this->config->get('tmdauction_bidder_name');
		$data['bidding_amount'] 	= $this->config->get('tmdauction_bidding_amount');
		$data['date_added'] 		= $this->config->get('tmdauction_date_added');
		$data['bid_type'] 			= $this->config->get('tmdauction_bid_type');

		if($data['module_status']==1) {

			$auctionsetlables=$this->config->get('tmdauction_langauge');
			$biddername 	= $auctionsetlables[$this->config->get('config_language_id')]['biddername'];
			$biddingamount 	= $auctionsetlables[$this->config->get('config_language_id')]['biddingamount'];
			$dateadded 		= $auctionsetlables[$this->config->get('config_language_id')]['dateadded'];
			$bidtype 		= $auctionsetlables[$this->config->get('config_language_id')]['bidtype'];

		}

		if(!empty($biddername)){
			$data['text_bidder_name'] = $biddername;
		} else {
			$data['text_bidder_name'] = $this->language->get('text_bidder_name');
		}

		if(!empty($biddingamount)){
			$data['text_bidder_amount'] = $biddingamount;
		} else {
			$data['text_bidder_amount'] = $this->language->get('text_bidder_amount');
		}

		if(!empty($dateadded)){
			$data['text_bidder_date_add'] = $dateadded;
		} else {
			$data['text_bidder_date_add'] = $this->language->get('text_bidder_date_add');
		}

		if(!empty($bidtype)){
			$data['text_bid_type'] = $bidtype;
		} else {
			$data['text_bid_type'] = $this->language->get('text_bid_type');
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$url='';

		$bidlimit = $this->config->get('tmdauction_limit');
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $bidlimit;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$data['bid_details'] =array();
		$filter_data = array(
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
		
		
		$bidtotal_info 		= $this->model_extension_tmdauction->getTotalBid($filter_data,$product_id);
		$bid_info 			= $this->model_extension_tmdauction->getTmdAuctionBids($filter_data,$product_id);
		foreach ($bid_info as $result) {
			
			$this->load->model('account/customer');
			$bidder_info 		= $this->model_account_customer->getCustomer($result['customer_id']);
			$this->load->model('extension/tmdmywinningbid');
			$product_info = $this->model_extension_tmdmywinningbid->getTmdAuctionProduct($product_id);

			if(!empty($bidder_info['firstname'])){
				$biddername = $bidder_info['firstname'] .' '.$bidder_info['lastname'];
			}else{
				$biddername = '';
			}

			$data['bid_details'][] = array(
				'bid_id' 			=> $result['bid_id'],
				'biddername' 		=> $biddername,
				'bid_amount'      	=> $this->currency->format($this->tax->calculate($result['bid_amount'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'date_added'      	=> $result['date_added'],
			);
		}

		$pagination = new Pagination();
		$pagination->total = $bidtotal_info;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url 	= $this->url->link('extension/module/tmdbid/bidPages&product_id='.$product_id,'page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($bidtotal_info) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($bidtotal_info - $limit)) ? $bidtotal_info : ((($page - 1) * $limit) + $limit), $bidtotal_info, ceil($bidtotal_info / $limit));


		return $this->response->setOutput($this->load->view('extension/module/tmdbidpage', $data));;
	}

	public function addBid() {
		$this->load->language('extension/module/tmdbid');
		$this->load->model('catalog/product');
		$this->load->model('extension/tmdauction');

		if (!$this->customer->isLogged()) {
			$json['error_login'] 	= $this->language->get('error_login');
		}else{

			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else {
				$product_id = 0;
			}

			$min_amount_info = $this->model_extension_tmdauction->getTmdAuctionProduct($product_id);

			if(isset($min_amount_info['start_time'])){
				$data['starttime'] = $min_amount_info['start_time'];
			} else {
				$data['starttime'] = '';
			}
			
			$data['date'] = date('Y-m-d H:i:s');

			$last_amount 	= $this->model_extension_tmdauction->getTmdMaxAuctionBid($product_id);
			if(isset($last_amount)){
				$max_bid = $last_amount;
			}else{
				$max_bid = $min_amount_info['starting_bids'];
			}
			if($max_bid >= $this->request->post['bid_amount'] ){
				$json['error_amount'] = $this->language->get('error_amount').' '.$max_bid;
			}else if($min_amount_info['starting_bids'] >= $this->request->post['bid_amount']){
				$json['error_amount'] = $this->language->get('error_amount').' '.$min_amount_info['starting_bids'];
			}else{
				$bid_id = $this->model_extension_tmdauction->AddTmdAuctionBid($product_id, $this->request->post);

				$bid_info = $this->model_extension_tmdauction->getTmdAuctionBid($bid_id);
				$this->load->model('extension/tmdmywinningbid');
				$product_info = $this->model_extension_tmdmywinningbid->getTmdAuctionProduct($product_id);
				if(!empty($bid_info['bid_amount'])){
					$json['bid_amount'] = $this->currency->format($this->tax->calculate($bid_info['bid_amount'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				}else{
					$json['bid_amount'] = '';
				}

				if(!empty($bid_info['date_added'])){
					$json['date_added'] = $bid_info['date_added'];
				}else{
					$json['date_added'] = '';
				}

				if(!empty($bid_info['customer_id'])){
					$customer_id = $bid_info['customer_id'];
				}else{
					$customer_id = 0;
				}

				$this->load->model('account/customer');
				$customer_info = $this->model_account_customer->getCustomer($customer_id);

				if(!empty($customer_info['firstname'])){
					$json['customer_name'] = $customer_info['firstname'] .' '.$customer_info['lastname'] ;
				}else{
					$json['customer_name'] ='';
				}

				$json['success'] 	= $this->language->get('text_success');
				$json['total'] 		= $this->model_extension_tmdauction->getTmdTotalBid($product_id);

				if($this->request->post['bid_amount'] >= $min_amount_info['max_price'] && $min_amount_info['auto_finish'] == 1){
					$this->load->model('extension/tmdmywinningbid');
					$customer_info 	= $this->model_extension_tmdauction->addTmdAuctionWinner($bid_id);
					$json['won'] 	= $this->language->get('won');
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	/// Auction ///
}
