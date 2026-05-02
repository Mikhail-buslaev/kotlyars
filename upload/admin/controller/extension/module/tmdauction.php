<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionModuleTmdAuction extends Controller {
	
	private $error = array();
	public function install(){
		$this->load->model('extension/tmd/tmdauction');
		$this->model_extension_tmd_tmdauction->install();
	}	
	public function uninstall(){
		$this->load->model('extension/tmd/tmdauction');
		$this->model_extension_tmd_tmdauction->uninstall();
	}
	public function index() {
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdauction',
		'eid'=>'MzcwMDk',
		'route'=>'extension/module/tmdauction',
		);
		$tmdauction=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->document->addScript('view/javascript/summernote/summernote.js');
		$this->document->addScript('view/javascript/summernote/opencart.js');
		$this->document->addScript('view/javascript/colorbox/jquery.minicolors.js');
		$this->document->addStyle('view/javascript/summernote/summernote.css"');
		$this->document->addStyle('view/javascript/colorbox/jquery.minicolors.css"');
		
		if(isset($this->session->data['token'])){
			$tokenexchange 		= 'token=' . $this->session->data['token'];
			$data['token'] 		= $this->session->data['token'];
		} else{
			$tokenexchange 		='user_token=' . $this->session->data['user_token'];
			$data['user_token'] = $this->session->data['user_token'];
		}

		$this->load->language('extension/module/tmdauction');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if(isset($this->request->post['tmdauction_status'])) {

				$status=$this->request->post['tmdauction_status'];
			}
			
			$postdata['module_tmdauction_status']=$status;

			$this->model_setting_setting->editSetting('module_tmdauction',$postdata);
			
			$this->model_setting_setting->editSetting('tmdauction', $this->request->post);

			$this->db->query("DELETE FROM " . DB_PREFIX ."seo_url  WHERE `query` = 'extension/tmd/tmdauction'");
		
			if(isset($this->request->post['tmdauction_auctionpageseourl'])) {
				foreach ($this->request->post['tmdauction_auctionpageseourl'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'extension/tmd/tmdauction', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

			$this->db->query("DELETE FROM " . DB_PREFIX ."seo_url  WHERE `query` = 'extension/tmd/tmdmywinningbid'");
			if(isset($this->request->post['tmdauction_mywinningbidseourl'])) {
				foreach ($this->request->post['tmdauction_mywinningbidseourl'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'extension/tmd/tmdmywinningbid', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

			$this->db->query("DELETE FROM " . DB_PREFIX ."seo_url  WHERE `query` = 'extension/tmd/tmdmybid'");
			if(isset($this->request->post['tmdauction_mybidseourl'])) {
				foreach ($this->request->post['tmdauction_mybidseourl'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'extension/tmd/tmdmybid', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/tmdauction', $tokenexchange . '&type=module', true));
		}

		$data['heading_title'] 					= $this->language->get('heading_title');

if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenexchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', $tokenexchange . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/module/tmdauction', $tokenexchange, true)
		);

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['action'] = $this->url->link('extension/module/tmdauction', $tokenexchange, true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['tmdauction_banner'])) {
			$data['tmdauction_banner'] = $this->request->post['tmdauction_banner'];
		} else {
			$data['tmdauction_banner'] = $this->config->get('tmdauction_banner');
		}

		$this->load->model('tool/image');
	
		if (isset($this->request->post['tmdauction_banner']) && is_file(DIR_IMAGE . $this->request->post['tmdauction_banner'])) {
			$data['thumbheaderlogo'] = $this->model_tool_image->resize($this->request->post['tmdauction_banner'], 100, 100);
		} elseif (!empty($this->config->get('tmdauction_banner'))) {
			$data['thumbheaderlogo'] = $this->model_tool_image->resize($this->config->get('tmdauction_banner'), 100, 100);
		} else {
			$data['thumbheaderlogo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholderheaderlogo'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		if (isset($this->request->post['tmdauction_status'])) {
			$data['tmdauction_status'] = $this->request->post['tmdauction_status'];
		} else {
			$data['tmdauction_status'] = $this->config->get('tmdauction_status');
		}

		if (isset($this->request->post['tmdauction_limit'])) {
			$data['tmdauction_limit'] = $this->request->post['tmdauction_limit'];
		} else {
			$data['tmdauction_limit'] = $this->config->get('tmdauction_limit');
		}

		if (isset($this->request->post['tmdauction_auction_pro_limit'])) {
			$data['tmdauction_auction_pro_limit'] = $this->request->post['tmdauction_auction_pro_limit'];
		} else {
			$data['tmdauction_auction_pro_limit'] = $this->config->get('tmdauction_auction_pro_limit');
		}
		if (isset($this->request->post['tmdauction_bidder_name'])) {
			$data['tmdauction_bidder_name'] = $this->request->post['tmdauction_bidder_name'];
		} else {
			$data['tmdauction_bidder_name'] = $this->config->get('tmdauction_bidder_name');
		}

		if (isset($this->request->post['tmdauction_height'])) {
			$data['tmdauction_height'] = $this->request->post['tmdauction_height'];
		} else {
			$data['tmdauction_height'] = $this->config->get('tmdauction_height');
		}
		if (isset($this->request->post['tmdauction_width'])) {
			$data['tmdauction_width'] = $this->request->post['tmdauction_width'];
		} else {
			$data['tmdauction_width'] = $this->config->get('tmdauction_width');
		}

		if (isset($this->request->post['tmdauction_bidding_amount'])) {
			$data['tmdauction_bidding_amount'] = $this->request->post['tmdauction_bidding_amount'];
		} else {
			$data['tmdauction_bidding_amount'] = $this->config->get('tmdauction_bidding_amount');
		}

		if (isset($this->request->post['tmdauction_date_added'])) {
			$data['tmdauction_date_added'] = $this->request->post['tmdauction_date_added'];
		} else {
			$data['tmdauction_date_added'] = $this->config->get('tmdauction_date_added');
		}

		if (isset($this->request->post['tmdauction_bid_type'])) {
			$data['tmdauction_bid_type'] = $this->request->post['tmdauction_bid_type'];
		} else {
			$data['tmdauction_bid_type'] = $this->config->get('tmdauction_bid_type');
		}

		if (isset($this->request->post['tmdauction_total_bid'])) {
			$data['tmdauction_total_bid'] = $this->request->post['tmdauction_total_bid'];
		} else {
			$data['tmdauction_total_bid'] = $this->config->get('tmdauction_total_bid');
		}

		if (isset($this->request->post['tmdauction_latest_bid'])) {
			$data['tmdauction_latest_bid'] = $this->request->post['tmdauction_latest_bid'];
		} else {
			$data['tmdauction_latest_bid'] = $this->config->get('tmdauction_latest_bid');
		}

		if (isset($this->request->post['tmdauction_manimum_quantity'])) {
			$data['tmdauction_manimum_quantity'] = $this->request->post['tmdauction_manimum_quantity'];
		} else {
			$data['tmdauction_manimum_quantity'] = $this->config->get('tmdauction_manimum_quantity');
		}

		if (isset($this->request->post['tmdauction_maximum_quantity'])) {
			$data['tmdauction_maximum_quantity'] = $this->request->post['tmdauction_maximum_quantity'];
		} else {
			$data['tmdauction_maximum_quantity'] = $this->config->get('tmdauction_maximum_quantity');
		}

		if (isset($this->request->post['tmdauction_bid_start_date'])) {
			$data['tmdauction_bid_start_date'] = $this->request->post['tmdauction_bid_start_date'];
		} else {
			$data['tmdauction_bid_start_date'] = $this->config->get('tmdauction_bid_start_date');
		}

		if (isset($this->request->post['tmdauction_bid_stop_date'])) {
			$data['tmdauction_bid_stop_date'] = $this->request->post['tmdauction_bid_stop_date'];
		} else {
			$data['tmdauction_bid_stop_date'] = $this->config->get('tmdauction_bid_stop_date');
		}

		if (isset($this->request->post['tmdauction_maxbid'])) {
			$data['tmdauction_maxbid'] = $this->request->post['tmdauction_maxbid'];
		} else {
			$data['tmdauction_maxbid'] = $this->config->get('tmdauction_maxbid');
		}

		if (isset($this->request->post['tmdauction_mail_status'])) {
			$data['tmdauction_mail_status'] = $this->request->post['tmdauction_mail_status'];
		} else {
			$data['tmdauction_mail_status'] = $this->config->get('tmdauction_mail_status');
		}

		if (isset($this->request->post['tmdauction_order_date_limit'])) {
			$data['tmdauction_order_date_limit'] = $this->request->post['tmdauction_order_date_limit'];
		} else {
			$data['tmdauction_order_date_limit'] = $this->config->get('tmdauction_order_date_limit');
		}

		if (isset($this->request->post['tmdauction_shoppingcartstatus'])) {
			$data['tmdauction_shoppingcartstatus'] = $this->request->post['tmdauction_shoppingcartstatus'];
		} else {
			$data['tmdauction_shoppingcartstatus'] = $this->config->get('tmdauction_shoppingcartstatus');
		}
		
		if (isset($this->request->post['tmdauction_auctionpageseourl'])) {
			$data['tmdauction_auctionpageseourl'] = $this->request->post['tmdauction_auctionpageseourl'];
		} else {
			$data['tmdauction_auctionpageseourl'] = $this->config->get('tmdauction_auctionpageseourl');
		}
		
		if (isset($this->request->post['tmdauction_mywinningbidseourl'])) {
			$data['tmdauction_mywinningbidseourl'] = $this->request->post['tmdauction_mywinningbidseourl'];
		} else {
			$data['tmdauction_mywinningbidseourl'] = $this->config->get('tmdauction_mywinningbidseourl');
		}
		
		if (isset($this->request->post['tmdauction_mybidseourl'])) {
			$data['tmdauction_mybidseourl'] = $this->request->post['tmdauction_mybidseourl'];
		} else {
			$data['tmdauction_mybidseourl'] = $this->config->get('tmdauction_mybidseourl');
		}

		if (isset($this->request->post['tmdauction_mail_langauge'])) {
			$data['tmdauction_mail_langauge'] = $this->request->post['tmdauction_mail_langauge'];
		} elseif ($this->config->get('tmdauction_mail_langauge')) {
			$data['tmdauction_mail_langauge'] = $this->config->get('tmdauction_mail_langauge');
		} else {
			$data['tmdauction_mail_langauge'] = array();
		}

		if (isset($this->request->post['tmdauction_langauge'])) {
			$data['tmdauction_langauge'] = $this->request->post['tmdauction_langauge'];
		} elseif ($this->config->get('tmdauction_langauge')) {
			$data['tmdauction_langauge'] = $this->config->get('tmdauction_langauge');
		} else {
			$data['tmdauction_langauge'] = array();
		}

		if (isset($this->request->post['tmdauction_custom_css'])) {
			$data['tmdauction_custom_css'] = $this->request->post['tmdauction_custom_css'];
		} else {
			$data['tmdauction_custom_css'] = $this->config->get('tmdauction_custom_css');
		}

		if (isset($this->request->post['tmdauction_mainheadingclr'])) {
			$data['tmdauction_mainheadingclr'] = $this->request->post['tmdauction_mainheadingclr'];
		} else {
			$data['tmdauction_mainheadingclr'] = $this->config->get('tmdauction_mainheadingclr');
		}
        
		if (isset($this->request->post['tmdauction_winingtextcolor'])) {
			$data['tmdauction_winingtextcolor'] = $this->request->post['tmdauction_winingtextcolor'];
		} else {
			$data['tmdauction_winingtextcolor'] = $this->config->get('tmdauction_winingtextcolor');
		}
		
		if (isset($this->request->post['tmdauction_clockcolor'])) {
			$data['tmdauction_clockcolor'] = $this->request->post['tmdauction_clockcolor'];
		} else {
			$data['tmdauction_clockcolor'] = $this->config->get('tmdauction_clockcolor');
		}
		
		if (isset($this->request->post['tmdauction_bidbtntextcolor'])) {
			$data['tmdauction_bidbtntextcolor'] = $this->request->post['tmdauction_bidbtntextcolor'];
		} else {
			$data['tmdauction_bidbtntextcolor'] = $this->config->get('tmdauction_bidbtntextcolor');
		}
		
        if (isset($this->request->post['tmdauction_timerbg'])) {
			$data['tmdauction_timerbg'] = $this->request->post['tmdauction_timerbg'];
		} else {
			$data['tmdauction_timerbg'] = $this->config->get('tmdauction_timerbg');
		}

		if (isset($this->request->post['tmdauction_timercolor'])) {
			$data['tmdauction_timercolor'] = $this->request->post['tmdauction_timercolor'];
		} else {
			$data['tmdauction_timercolor'] = $this->config->get('tmdauction_timercolor');
		}
		if (isset($this->request->post['tmdauction_bidbtnbgcolor'])) {
			$data['tmdauction_bidbtnbgcolor'] = $this->request->post['tmdauction_bidbtnbgcolor'];
		} else {
			$data['tmdauction_bidbtnbgcolor'] = $this->config->get('tmdauction_bidbtnbgcolor');
		}

		if (isset($this->request->post['tmdauction_mywinningprobtncolor'])) {
			$data['tmdauction_mywinningprobtncolor'] = $this->request->post['tmdauction_mywinningprobtncolor'];
		} else {
			$data['tmdauction_mywinningprobtncolor'] = $this->config->get('tmdauction_mywinningprobtncolor');
		}

		if (isset($this->request->post['tmdauction_mywinningprobtncolortext'])) {
			$data['tmdauction_mywinningprobtncolortext'] = $this->request->post['tmdauction_mywinningprobtncolortext'];
		} else {
			$data['tmdauction_mywinningprobtncolortext'] = $this->config->get('tmdauction_mywinningprobtncolortext');
		}

		if (isset($this->request->post['tmdauction_mybiddingprobtncolor'])) {
			$data['tmdauction_mybiddingprobtncolor'] = $this->request->post['tmdauction_mybiddingprobtncolor'];
		} else {
			$data['tmdauction_mybiddingprobtncolor'] = $this->config->get('tmdauction_mybiddingprobtncolor');
		}

		if (isset($this->request->post['tmdauction_mybiddingprobtncolortext'])) {
			$data['tmdauction_mybiddingprobtncolortext'] = $this->request->post['tmdauction_mybiddingprobtncolortext'];
		} else {
			$data['tmdauction_mybiddingprobtncolortext'] = $this->config->get('tmdauction_mybiddingprobtncolortext');
		}

		if (isset($this->request->post['tmdauction_bidlistbtncolor'])) {
			$data['tmdauction_bidlistbtncolor'] = $this->request->post['tmdauction_bidlistbtncolor'];
		} else {
			$data['tmdauction_bidlistbtncolor'] = $this->config->get('tmdauction_bidlistbtncolor');
		}

		if (isset($this->request->post['tmdauction_bidlistbtncolortext'])) {
			$data['tmdauction_bidlistbtncolortext'] = $this->request->post['tmdauction_bidlistbtncolortext'];
		} else {
			$data['tmdauction_bidlistbtncolortext'] = $this->config->get('tmdauction_bidlistbtncolortext');
		}

		if (isset($this->request->post['tmdauction_auctiondetailbtncolor'])) {
			$data['tmdauction_auctiondetailbtncolor'] = $this->request->post['tmdauction_auctiondetailbtncolor'];
		} else {
			$data['tmdauction_auctiondetailbtncolor'] = $this->config->get('tmdauction_auctiondetailbtncolor');
		}

		if (isset($this->request->post['tmdauction_auctiondetailbtncolortext'])) {
			$data['tmdauction_auctiondetailbtncolortext'] = $this->request->post['tmdauction_auctiondetailbtncolortext'];
		} else {
			$data['tmdauction_auctiondetailbtncolortext'] = $this->config->get('tmdauction_auctiondetailbtncolortext');
		}

		// Time Zone Start
		$data['timezones'] = array();
		$time_infos = timezone_identifiers_list();
		foreach ($time_infos as $key => $result) {
			$data['timezones'][] = array(
				'id' => $key,
				'name' => $result
			);
		}

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		if (isset($this->request->post['tmdauction_timezone'])) {
			$data['tmdauction_timezone'] = $this->request->post['tmdauction_timezone'];
		} else {
			$data['tmdauction_timezone'] = $this->config->get('tmdauction_timezone');
		}
		
		$data['cronurl']    =HTTPS_CATALOG.'index.php?route=extension/tmd/tmdauctioncron';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tmdauction', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tmdauction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$tmdauction=$this->config->get('tmdkey_tmdauction');
		if (empty(trim($tmdauction))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/module/tmdauction', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		return !$this->error;
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdauction',
			'eid'=>'MzcwMDk',
			'route'=>'extension/module/tmdauction',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}