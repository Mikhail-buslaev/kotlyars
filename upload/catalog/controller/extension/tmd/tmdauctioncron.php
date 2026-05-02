<?php
class ControllerExtensionTmdTmdAuctionCron extends Controller {
	public function index() {
		$this->load->model('extension/tmd/tmdauction');
		$this->load->model('extension/tmd/tmdmybid');
		$auctionmailcount = 0;
		$results = $this->model_extension_tmd_tmdauction->getTmdExpireProducts();

		foreach ($results as $result) {
			
			$win_info = $this->model_extension_tmd_tmdmybid->getTmdAuctionWinner($result['product_id']);

			if (!isset($win_info['product_id'])) {

				$bid_info = $this->model_extension_tmd_tmdauction->getTmdMaxBidByProduct($result['product_id']);

				if (isset($bid_info['bid_id'])){
					$bid_id = $bid_info['bid_id'];
				}else{
					$bid_id = 0;
				}
				if ($bid_id != 0) {
					$this->model_extension_tmd_tmdauction->addTmdAuctionWinner($bid_id);
					$auctionmailcount++;
				}
			}
		}

		echo $auctionmailcount.' '."Mail Sent";
	}
}
