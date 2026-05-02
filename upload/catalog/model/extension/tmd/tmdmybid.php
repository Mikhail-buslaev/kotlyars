<?php
class ModelExtensionTmdTmdMyBid extends Model {
	public function getTmdMyBids($customer_id, $data= array()) {

		$sql = "SELECT max(bid_amount)  as bid_amount,max(bid_id) as bid_id FROM " . DB_PREFIX . "tmdauction_bid WHERE customer_id = '" . (int)$customer_id . "' and bid_id<>0 group by product_id,customer_id order by bid_amount DESC";

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

	public function getAuctionProductOrder($product_id) {
		$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){

		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_order_product WHERE product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
	}

	public function getTmdAuctionWinner($product_id) {
		$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){
		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_winner WHERE product_id = '".$product_id."'";

		$sql .= " GROUP BY auction_id";

		$sort_data = array(
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY auction_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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


		return $query->row;
	}else{
		return 0;
	}
	}
	public function getTotalTmdMyBids(){
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tmdauction_winner where customer_id='".$this->customer->getId()."'");
		return $query->row['total'];
	}

	public function getTmdAuctionProduct($product_id) {
		$tmdauction_status = $this->config->get('tmdauction_status');
		if(!empty($tmdauction_status)){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdproduct_auction WHERE product_id=" . (int)$product_id . "");
		return $query->row;
	}
}
}