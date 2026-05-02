<?php
class ControllerExtensionTmdTmdauctioncss extends Controller {

	public function index() {
	
		header("Content-type: text/css", true);
		
		$data['mainhdcolor'] 		= $this->config->get('tmdauction_mainheadingclr');
		$data['winingtextcolor'] 		= $this->config->get('tmdauction_winingtextcolor');
		$data['clockcolor'] 		= $this->config->get('tmdauction_clockcolor');
        
		$data['winningbtnbg'] 		= $this->config->get('tmdauction_mywinningprobtncolor');
		$data['winningbtncolor'] 	= $this->config->get('tmdauction_mywinningprobtncolortext');
		
		$data['bidbtnbgcolor'] 		= $this->config->get('tmdauction_bidbtnbgcolor');
		$data['bidbtntextcolor'] 	= $this->config->get('tmdauction_bidbtntextcolor');
        
		$data['biddingbtnbg'] 		= $this->config->get('tmdauction_mybiddingprobtncolor');
		$data['biddingbtncolor'] 	= $this->config->get('tmdauction_mybiddingprobtncolortext');
        
		$data['bidlistbtnbg'] 		= $this->config->get('tmdauction_bidlistbtncolor');
		$data['bidlistbtncolor'] 	= $this->config->get('tmdauction_bidlistbtncolortext');
        
		$data['detailbtnbg'] 		= $this->config->get('tmdauction_auctiondetailbtncolor');
		$data['detailbtncolor'] 	= $this->config->get('tmdauction_auctiondetailbtncolortext');
        
        $data['timerbg'] 			= $this->config->get('tmdauction_timerbg');
		$data['timercolor'] 		= $this->config->get('tmdauction_timercolor');
        
		
        echo ".auction-category{background:".$data['timerbg']."!important;}";
        
        echo "#auction-main .coundown-main #seconds,#auction-main .coundown-main #seconds span,#auction-main .coundown-main #minutes,#auction-main .coundown-main #minutes span,#auction-main .coundown-main #hours,#auction-main .coundown-main #hours span,#auction-main .coundown-main #days,#auction-main .coundown-main #days span,.auction-category .timer span,.auction-category .seconds,.auction-category .minutes,.auction-category .hours,.auction-category .days,.auction-category .days span, .auction-category #timer div span{color:".$data['timercolor']."!important;border-color:".$data['timercolor']."!important;}";
        
        echo ".auction-header{color:".$data['mainhdcolor']."!important;}";
		
        echo ".auction-category .clockbtn .fa{color:".$data['clockcolor']."!important;}";
		
        echo ".auction-category .auction-text{color:".$data['winingtextcolor']."!important;}";
		
        echo ".extension-tmdauction .btn-primary{background:".$data['winningbtnbg']."!important;color:".$data['winningbtncolor']."!important;border-color:".$data['winningbtnbg']."!important;}";
		
        echo "#auction-main .btn.btn-info.bid-button{background:".$data['bidbtnbgcolor']."!important;color:".$data['bidbtntextcolor']."!important;border-color:".$data['bidbtnbgcolor']."!important;}";
       
        echo ".extension-tmdauction .btn-success{background:".$data['biddingbtnbg']."!important;color:".$data['biddingbtncolor']."!important;border-color:".$data['biddingbtnbg']."!important;}";
		
		echo ".details-btn #aution-details{background:".$data['detailbtnbg']."!important;color:".$data['detailbtncolor']."!important;border-color:".$data['detailbtnbg']."!important;}";
        
        echo ".details-btn #bidding-list{background:".$data['bidlistbtnbg']."!important;color:".$data['bidlistbtncolor']."!important;border-color:".$data['bidlistbtnbg']."!important;}";
	}
}
