<?php
class ControllerExtensionTmdTmdAuctionWinner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/tmd/tmdauctionwinner');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/tmdauctionwinner');

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

		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
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

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
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
			'href' => $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . $url, true)
		);

		$data['winning_products'] = array();

		$filter_data = array(
			'filter_name'	  		=> $filter_name,
			'filter_customer_name'	=> $filter_customer_name,
			'filter_price' 			=> $filter_price,
			'sort'            		=> $sort,
			'order'           		=> $order,
			'start'           		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           		=> $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$this->load->model('extension/tmd/tmdauctionwinner');

		$auction_total 	= $this->model_extension_tmd_tmdauctionwinner->getTotalTmdAuctionWinners($filter_data);
		$results 		= $this->model_extension_tmd_tmdauctionwinner->getTmdAuctionWinners($filter_data);

		foreach ($results as $result) {
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);

			if(!empty($customer_info['firstname']) && !empty($customer_info['lastname'])){
				$customername = $customer_info['firstname'].' '.$customer_info['lastname'];
			}else{
				$customername = '';
			}
			if(!empty($customer_info['email'])){
				$customeremail = $customer_info['email'];
			}else{
				$customeremail = '';
			}

			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if(!empty($product_info['name'])){
				$productname = $product_info['name'];
			}else{
				$productname = '';
			}

			if (is_file(DIR_IMAGE . $product_info['image'])) {
				$image = $this->model_tool_image->resize($product_info['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['winning_products'][] = array(
				'bid_id' 			=> $result['bid_id'], 
				'bid_amount' 		=> $this->currency->format($result['price'], $this->config->get('config_currency')), 
				'date_added' 		=> $result['date_added'], 
				'end_date' 			=> $result['end_date'], 
				'image' 			=> $image, 
				'product_name' 		=> $productname, 
				'customer_name' 	=> $customername, 
				'customer_email' 	=> $customeremail, 
			);
		}


		$data['heading_title'] 			= $this->language->get('heading_title');

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

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}


		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] 			= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=pd.name' . $url, true);
		$data['sort_customer_name']	= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.customer_name' . $url, true);
		$data['sort_customer_email']	= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.customer_email' . $url, true);
		$data['sort_date_added']	= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.date_added' . $url, true);
		$data['sort_end_date']	= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.end_date' . $url, true);
		$data['sort_price'] 		= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.price' . $url, true);
		$data['sort_status'] 		= $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . '&sort=p.sort_status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $auction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/tmd/tmdauctionwinner', $tokenexchange . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($auction_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($auction_total - $this->config->get('config_limit_admin'))) ? $auction_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $auction_total, ceil($auction_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_customer_name'] = $filter_customer_name;
		$data['filter_price'] = $filter_price;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmd/tmdauctionwinner_list', $data));
	}
}
