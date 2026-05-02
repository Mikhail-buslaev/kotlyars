<?php
class ModelExtensionTmdTmdAuction extends Model {

public function install() {
$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tmdauction_bid` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `bid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`bid_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tmdauction_order_product` (
  `auction_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`auction_order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tmdauction_winner` (
  `winner_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`winner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tmdproduct_auction` (
  `auction_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `auto_finish` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `starting_bids` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `minimum_quantity` int(11) NOT NULL,
  `maximum_quantity` int(11) NOT NULL,
  PRIMARY KEY (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$this->db->query("ALTER TABLE oc_tmdauction_winner CHANGE price price DECIMAL(15,4) NOT NULL DEFAULT '0.0000'");

	}
	public function uninstall() {
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."tmdauction_bid`");
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."tmdauction_order_product`");
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."tmdauction_winner`");
	$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."tmdproduct_auction`");
	$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` like 'tmdauction_%'");


	}

	public function addTmdAuctionProduct($data) {

		if(isset($data['auto_finish'])){
			$auto_finish = $data['auto_finish'];
		}else{
			$auto_finish = 0;
		}

		if(isset($data['active'])){
			$active  = $data['active'];
		}else{
			$active  = 0;
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "tmdproduct_auction SET auto_finish = " . (int)$auto_finish . ",product_id = " . (int)$data['product_id'] . ",active = " . (int)$active . ",start_time = '" . $this->db->escape($data['start_time']) . "',end_time = '" . $this->db->escape($data['end_time']) . "',starting_bids = '" . $this->db->escape($data['starting_bids']) . "',max_price = '" . $this->db->escape($data['max_price']) . "',minimum_quantity = '" . $this->db->escape($data['minimum_quantity']) . "',maximum_quantity = '" . $this->db->escape($data['maximum_quantity']) . "'");

		$auction_id = $this->db->getLastId();
		return $auction_id;
	}
	
	public function editTmdAuctionProduct($auction_id,$data) {

		if(isset($data['auto_finish'])){
			$auto_finish = $data['auto_finish'];
		}else{
			$auto_finish = 0;
		}

		if(isset($data['active'])){
			$active  = $data['active'];
		}else{
			$active  = 0;
		}
		$this->db->query("UPDATE " . DB_PREFIX . "tmdproduct_auction SET auto_finish = " . (int)$auto_finish . ",active = " . (int)$active . ",start_time = '" . $this->db->escape($data['start_time']) . "',end_time = '" . $this->db->escape($data['end_time']) . "',starting_bids = '" . $this->db->escape($data['starting_bids']) . "',max_price = '" . $this->db->escape($data['max_price']) . "',minimum_quantity = '" . $this->db->escape($data['minimum_quantity']) . "',maximum_quantity = '" . $this->db->escape($data['maximum_quantity']) . "' WHERE auction_id = '" . (int)$auction_id . "'");

	}

	//Delete
	public function deleteTmdAuctionProduct($auction_id) {
		$pro_info = $this->getTmdAuctionProduct($auction_id);
		if(!empty($pro_info['product_id'])){
			$product_id = $pro_info['product_id'];
		}else{
			$product_id =33;
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdproduct_auction WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdauction_bid WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdauction_winner WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdauction_order_product WHERE product_id = '" . (int)$product_id . "'");

	}

	//Get By Id
	public function getTmdAuctionProduct($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdproduct_auction WHERE auction_id=" . (int)$auction_id . "");

		return $query->row;
	}

	//Get By Id
	public function getTmdAuctionProductByproId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdproduct_auction WHERE product_id=" . (int)$product_id . "");

		return $query->row;
	}

	//Get List
	public function getTmdAuctionProducts($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "tmdproduct_auction pa ON (pa.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and auction_id<>0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_start_bids'])) {
			$sql .= " AND pa.starting_bids LIKE '" . $this->db->escape($data['filter_start_bids']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_start_date'])) {
			$sql .= " AND DATE(pa.start_time) >= '" . $data['filter_start_date'] . "'";
		}

		if (!empty($data['filter_end_date'])) {
			$sql .= " AND DATE(pa.end_time) <= '" . $data['filter_end_date'] . "'";
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

	//Pagination
	public function getTotalTmdAuctionProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "tmdproduct_auction pa ON (pa.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and auction_id<>0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_start_bids'])) {
			$sql .= " AND pa.starting_bids LIKE '" . $this->db->escape($data['filter_start_bids']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_start_date'])) {
			$sql .= " AND DATE(pa.start_time) >= '" . $data['filter_start_date'] . "'";
		}

		if (!empty($data['filter_end_date'])) {
			$sql .= " AND DATE(pa.end_time) <= '" . $data['filter_end_date'] . "'";
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
