<?php
class ModelExtensionTmdTmdAuction extends Model {
	//Get List
	public function getTmdAuctionProducts($data = array()) {
		$currenttime = date('Y-m-d H:i:s');

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "tmdproduct_auction pa ON (pa.product_id = pd.product_id) WHERE  pa.product_id NOT IN (SELECT  product_id FROM " . DB_PREFIX . "tmdauction_winner) and pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and auction_id<>0 and pa.end_time >= '".$currenttime."'";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/// Auction ///
	public function AddTmdAuctionBid($product_id,$data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tmdauction_bid SET bid_amount = '" . (float)$data['bid_amount'] . "',product_id = '" . (int)$product_id . "',customer_id = '" . $this->customer->getId() . "', date_added = NOW()");

		$bid_id = $this->db->getLastId();
		return $bid_id;
	}

	public function addTmdAuctionWinner($bid_id) {

		$bid_info = $this->getTmdAuctionBid($bid_id);
		$auc_info = $this->getTmdAuctionProduct($bid_info['product_id']);

		$end_date = $this->config->get('tmdauction_order_date_limit');

		if(isset($end_date)){
			$end_date = $end_date.' days';
		}else{
			$end_date = '5 days';
		}

    	$end = date('Y-m-d H:i:s', strtotime($bid_info['date_added']. ' + '.$end_date));

		$this->db->query("INSERT INTO " . DB_PREFIX . "tmdauction_winner SET price = '" . (float)$bid_info['bid_amount'] . "',product_id = '" . (int)$bid_info['product_id'] . "',bid_id = '" . (int)$bid_id . "',auction_id = '" . (int)$auc_info['auction_id'] . "',customer_id = '" . $bid_info['customer_id'] . "', date_added = '" . $bid_info['date_added'] . "', end_date = '" . $end . "'");

	/// Email Send To Customer ///
		$module_mail_status	=$this->config->get('tmdauction_mail_status');
		$module_status 		= $this->config->get('tmdauction_status');
		if($module_status==1 && $module_mail_status ==1 ) {

			$auctionmailset = $this->config->get('tmdauction_mail_langauge');

			$subject = $auctionmailset[$this->config->get('config_language_id')]['subject'];

			if(!empty($subject)){
				$data['subject'] = $subject;
			} else {
				$data['subject'] = $this->language->get('text_subject');
			}

			$message = $auctionmailset[$this->config->get('config_language_id')]['message'];
			if(!empty($message)){
				$data['message'] = $message;
			} else {
				$data['message'] = $this->language->get('text_message');
			}

			$this->load->model('extension/tmd/tmdmybid');
			$auction_info = $this->model_extension_tmd_tmdmybid->getTmdAuctionWinner($auc_info['product_id']);
			
			if(isset($auction_info['customer_id'])){
				$customer_id = $auction_info['customer_id'];
			}else{
				$customer_id = 0;
			}
			
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($customer_id);

			if(isset($customer_info['firstname'])){
				$firstname = $customer_info['firstname'];
			}else{
				$firstname = '';
			}

			if(isset($customer_info['lastname'])){
				$lastname = $customer_info['lastname'];
			}else{
				$lastname = '';
			}

			$customer_name = $firstname .' '.$lastname;

			if(isset($customer_info['email'])){
				$email = $customer_info['email'];
			}else{
				$email = '';
			}
			
			$this->load->model('extension/tmd/tmdmywinningbid');
			$product_info = $this->model_extension_tmd_tmdmywinningbid->getTmdAuctionProduct($auc_info['product_id']);
			if(!empty($product_info['name'])){
				$product_name = $product_info['name'];
			}else{
				$product_name = '';
			}

			if(isset($auction_info['end_date'])){
				$end_date = $auction_info['end_date'];
			}else{
				$end_date = 'after some Days';
			}

			$find = array(
				'{customer_name}',
				'{email}',
				'{product_id}',
				'{product_name}',
				'{end_date}',
			);

			$replace = array(
				'customer_name' => $customer_name,
				'email'  		=> $email,
				'product_id'  	=> $auc_info['product_id'],
				'product_name'  => $product_name,
				'end_date'  	=> $end_date
			);
			$subject = sprintf($data['subject'], html_entity_decode($data['subject'], ENT_QUOTES, 'UTF-8'));

			$message = str_replace(array("\r\n", "\r", "\n"), '<br/>', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br/>', trim(str_replace($find, $replace, $data['message']))));

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setHtml(html_entity_decode($message));
			$mail->send();
		}
	/// Email Send To Customer ///
	}

	public function getTmdAuctionBids($data,$product_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id=" . (int)$product_id . " ORDER BY date_added DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTmdAuctionBid($bid_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdauction_bid WHERE bid_id=" . (int)$bid_id . "");

		return $query->row;
	}

	public function getTmdAuction($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdproduct_auction WHERE auction_id=" . (int)$auction_id . "");

		return $query->row;
	}

	public function getTmdMaxAuctionBid($product_id) {
			$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){
		$sql = "SELECT MAX(bid_amount) AS current_amount FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id=" . (int)$product_id . "";

		$query = $this->db->query($sql);

		return $query->row['current_amount'];
	}
	}

	public function getTmdExpireProducts() {
		$curentdate = date('Y-m-d H:i:s');
		$sql = "SELECT * FROM `" . DB_PREFIX . "tmdproduct_auction` WHERE (end_time < '".$curentdate."')";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTmdMaxBidByProduct($product_id) {
		$sql = "SELECT max(bid_amount) AS bid_amount,max(bid_id) AS bid_id FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id = '".$product_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getTmdAuctionProduct($product_id) {
		$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdproduct_auction WHERE product_id=" . (int)$product_id . "");
		return $query->row;
	}
	}

	public function getTmdTotalBid($product_id) {
			$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){
		$sql = "SELECT COUNT(bid_id) AS total FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id=" . (int)$product_id . "";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	}

	public function getTotalBid($data,$product_id) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id=" . (int)$product_id . "";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalTmdAuctionProducts() {
		$currenttime = date('Y-m-d H:i:s');
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "tmdproduct_auction pa ON (pa.product_id = pd.product_id) WHERE   pa.product_id NOT IN (SELECT  product_id FROM " . DB_PREFIX . "tmdauction_winner tw) and pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and auction_id<>0 and pa.end_time >= '".$currenttime."'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
/// Auction ///
}