<?php
class ModelExtensionTmdTmdMyWinningBid extends Model {
	public function getTmdAuctionWinners($customer_id, $data=array()) {
  		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_winner WHERE customer_id = '".$customer_id."'";

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

		return $query->rows;
	}

	public function getAuctionProductOrder($product_id) {

		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_order_product WHERE product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTmdAuctionProduct($product_id) {
			$tmdauction_status = $this->config->get('tmdauction_status');
			if(!empty($tmdauction_status)){

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "tmdproduct_auction pa ON (pa.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and pa.product_id = '".$product_id."'";

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
	}
	}
	public function getTotalproducts(){
		$sql="select count(*) as total from `" . DB_PREFIX . "tmdauction_winner` where customer_id='".$this->customer->getId()."'";
		$query=$this->db->query($sql);
		return $query->row['total'];
	}
	
}