<?php
class ModelExtensiondeldttime extends Controller {	
	private $error = array();
	private $modpath = 'extension/deldttime'; 
 	private $modname = 'deldttime';
	private $modtext = 'Delivery Date Time Slot At Checkout';
	private $modid = '28132';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com'; 
	private $langid = 0;
	private $defaultdate = 1;	// Y-m-d = 2017-12-31
	//private $defaultdate = 2;	// m/d/Y = 31/12/2017
	//private $defaultdate = 3;	// d/m/Y = 12/31/2017
 	//private $defaultdate = 4;	// m.d.Y = 31.12.2017
	//private $defaultdate = 5;	// d.m.Y = 12.31.2017
	 
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->langid = (int)$this->config->get('config_language_id');
		 
  		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'module_deldttime';
		} 
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
		
		$data['stng'] = $this->config->get((substr(VERSION,0,3)>='3.0') ? 'module_deldttime_setting' : 'deldttime_setting');
 		
		$this->defaultdate = isset($data['stng']['dateformat']) ? $data['stng']['dateformat'] : 1;
 	} 
	
	public function getformat() {
		if($this->defaultdate == 1) {
			return "YYYY-MM-DD";
		} else if($this->defaultdate == 2) {
 			return "MM/DD/YYYY";
		} else if($this->defaultdate == 3) {
 			return "DD/MM/YYYY";
 		} else if($this->defaultdate == 4) {
			return "MM.DD.YYYY";
		} else if($this->defaultdate == 5) {
			return "DD.MM.YYYY"; 
 		} else {
			return "YYYY-MM-DD";
		}
	}
	
	public function getphpdateformat() {
		if($this->defaultdate == 1) {
			return "Y-m-d";
		} else if($this->defaultdate == 2) {
 			return "m/d/Y";
		} else if($this->defaultdate == 3) {
 			return "d/m/Y";
 		} else if($this->defaultdate == 4) {
			return "m.d.Y";
		} else if($this->defaultdate == 5) {
			return "d.m.Y"; 
 		} else {
			return "Y-m-d";
		}
	}
	
	public function getdatewithformatdisplay($datevalue) {
		if($datevalue == '' || $datevalue == '---' || $datevalue == '0000-00-00') {
			return '---';
		} else {
			if($this->defaultdate == 1) {
				return date("Y-m-d", strtotime($datevalue));
				
			} else if($this->defaultdate == 2) {
 				return date("m/d/Y", strtotime($datevalue));
			} else if($this->defaultdate == 3) {
				return date("d/m/Y", strtotime($datevalue));
				
			} else if($this->defaultdate == 4) {
				return date("m.d.Y", strtotime($datevalue));
			} else if($this->defaultdate == 5) {
				return date("d.m.Y", strtotime($datevalue));
				
			} else {
				return date("Y-m-d", strtotime($datevalue));
			}
		}
	} 
	
	public function getdatewithformatDB($datevalue) {
		if($datevalue == '' || $datevalue == '---' || $datevalue == '0000-00-00') {
			return '---';
		} else {
			$datevalue = str_replace(array("/",".","-"),"-",$datevalue);
			
			if($this->defaultdate == 1) {
				return date("Y-m-d", strtotime($datevalue));
				
			} else if($this->defaultdate == 2) {	// m/d/Y = 31/12/2017
				
				$parts = explode("-",$datevalue);
				$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
 				return date("Y-m-d", strtotime($yyyy_mm_dd));
				
			} else if($this->defaultdate == 3) {	// d/m/Y = 12/31/2017
			
				$parts = explode("-",$datevalue);
				$yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
				return date("Y-m-d", strtotime($yyyy_mm_dd));
				
			} else if($this->defaultdate == 4) {	// m.d.Y = 31.12.2017
				
				$parts = explode("-",$datevalue);
				$yyyy_mm_dd = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
				return date("Y-m-d", strtotime($datevalue));
				
			} else if($this->defaultdate == 5) {	// d.m.Y = 12.31.2017
				
				$parts = explode("-",$datevalue);
				$yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
				return date("Y-m-d", strtotime($datevalue));
				
			} else {
				return date("Y-m-d", strtotime($datevalue));
			}
 		}
	} 


	// for time 
	public function gettimewithformatDB($timevalue) {
		if($timevalue == '' || $timevalue == '---' || $timevalue == '00:00:00') {
			return '---';
		} else {
			return $timevalue; 
		}
	} 
}