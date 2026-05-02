<?php
class ModelExtensionTmdTmdAuctionWinner extends Model {
	
	public function getTmdAuctionWinners($data = array()) {
  		$sql = "SELECT * FROM " . DB_PREFIX . "tmdauction_winner WHERE winner_id<>0";

		if (!empty($data['filter_name'])) {
			$sql .= " AND product_id LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND customer_id LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

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

	public function getTotalTmdAuctionWinners($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tmdauction_winner WHERE winner_id<>0";

		if (!empty($data['filter_name'])) {
			$sql .= " AND product_id LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_name'])) {
			$sql .= " AND customer_id LIKE '" . $this->db->escape($data['filter_customer_name']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
