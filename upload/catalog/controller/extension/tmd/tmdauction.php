<?php
class ControllerExtensionTmdTmdAuction extends Controller {
	public function index() {
		$this->load->language('extension/tmd/tmdauction');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$module_status	= $this->config->get('tmdauction_status');
		if (isset($module_status)) {
			$data['module_status'] = $module_status;
		}else{
			$data['module_status']= 0;
		}

		if($data['module_status'] != 1){
			$this->response->redirect($this->url->link('common/home', '', true));
		}
		

		$data['mybid'] 		= $this->url->link('extension/tmd/tmdmybid');

		$data['mywinbid'] 	= $this->url->link('extension/tmd/tmdmywinningbid');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$auction_limit=$this->config->get('tmdauction_auction_pro_limit');
		if (!empty($auction_limit)) {
			$limit = $auction_limit;
		} else {
			$limit = 20;
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$auctionsetlables=$this->config->get('tmdauction_langauge');
		$mainheading = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
		if(!empty($mainheading)){
			$data['text_mainheading'] = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
		}else{
			$data['text_mainheading'] = $this->language->get('text_tmdauction');
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_mainheading'],
			'href' => $this->url->link('extension/tmd/tmdauction')
		);
			$this->document->setTitle($this->language->get('heading_title'));
			$data['heading_title'] 				= $this->language->get('heading_title');
			
			$data['text_compare'] 				= sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		
			$module_status = $this->config->get('tmdauction_status');

			if($module_status==1) {
				
				$auctionsetlables=$this->config->get('tmdauction_langauge');
				$mainheading = $auctionsetlables[$this->config->get('config_language_id')]['mainheading'];
				$mywinningprobtntext = $auctionsetlables[$this->config->get('config_language_id')]['mywinningprobtntext'];
				$mybiddingprobtntext = $auctionsetlables[$this->config->get('config_language_id')]['mybiddingprobtntext'];
			}
			
			
				if(!empty($mainheading)){
					$data['heading_title'] = $mainheading;
				} else {
					$data['heading_title'] = $this->language->get('heading_title');
				}
				
				if(!empty($mywinningprobtntext)){
					$data['button_mywinningproduct'] = $mywinningprobtntext;
				} else {
					$data['button_mywinningproduct'] = $this->language->get('button_mywinningproduct');
				}
				
				if(!empty($mybiddingprobtntext)){
					$data['button_mybiddingproduct'] = $mybiddingprobtntext;
				} else {
					$data['button_mybiddingproduct'] = $this->language->get('button_mybiddingproduct');
				}
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$filter_data = array(
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$data['products'] = array();

			$this->load->model('extension/tmd/tmdauction');
			$result_total 	= $this->model_extension_tmd_tmdauction->getTotalTmdAuctionProducts();
			$results 		= $this->model_extension_tmd_tmdauction->getTmdAuctionProducts($filter_data);

			foreach ($results as $result) {

				$this->load->model('extension/tmd/tmdmybid');
				$win_info = $this->model_extension_tmd_tmdmybid->getTmdAuctionWinner($result['product_id']);
				
				if(isset($win_info['product_id'])){
					$win_product_id = $win_info['product_id'];
				}else{
					$win_product_id = 0;
				}

				if (is_file(DIR_IMAGE . $result['image'])) {
					$image = $this->model_tool_image->resize($result['image'], 350, 350);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 350, 350);
				}
				
				$data['products'][] = array(
					'auction_id'  	=> $result['auction_id'],
					'win_product_id'  => $win_product_id,
					'product_id'  	  => $result['product_id'],
					'name'        	  => $result['name'],
					'end_time'  	  => strtotime($result['end_time']),
					'start_time'  	  => strtotime($result['start_time']),
					'date'        	  => strtotime(date('Y-m-d H:i:s')),
					'time'        	  => time(),
					'description'     => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 30) . '..',
					'image'        	  => $image,
					'href'        	  => $this->url->link('product/product&product_id=' . $result['product_id'] . $url)
				);
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $result_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('extension/tmd/tmdauction', 'page={page}', true);

			$data['pagination'] = $pagination->render();

			$data['results'] 	= sprintf($this->language->get('text_pagination'), ($result_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_total - $limit)) ? $result_total : ((($page - 1) * $limit) + $limit), $result_total, ceil($result_total / $limit));

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/tmd/tmdauction', $data));
	}
}
