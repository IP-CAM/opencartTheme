<?php
class ControllerExtensionModuledeldttime extends Controller {	
	private $error = array();
	private $modpath = 'module/deldttime'; 
	private $modtpl = 'default/template/module/deldttime.tpl'; 
	private $modname = 'deldttime';
	private $modtext = 'Delivery Date Time Slot At Checkout';
	private $modid = '28132';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com'; 
	private $langid = 0;
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->langid = (int)$this->config->get('config_language_id');
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
			$this->modtpl = 'extension/module/deldttime';
			$this->modpath = 'extension/module/deldttime';
		} else if(substr(VERSION,0,3)=='2.2') {
			$this->modtpl = 'module/deldttime';
		} 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'module_deldttime';
		} 
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
 	} 
	
	public function index() {		
		if(isset($this->session->data['shipping_method']) && isset($this->session->data['order_id'])) { 
			$smcode = explode('.',$this->session->data['shipping_method']['code']);
			$this->checkdeldatetimefield();
			
			$this->load->language($this->modpath);
			$data['del_date'] = $this->language->get('del_date');
			$data['del_time'] = $this->language->get('del_time');
			$data['btn_save'] = $this->language->get('btn_save');
			$data['btn_loging_text'] = $this->language->get('btn_loging_text');
			
			$data['modpath'] = $this->modpath;
 			
			$this->load->model('extension/deldttime');
			$data['deldttime_getformat'] = $this->model_extension_deldttime->getformat();
			$getphpdateformat = $this->model_extension_deldttime->getphpdateformat();
			
			$data['deldttime_status'] = $this->setvalue($this->modname.'_status');
			$data['deldttime_enable_time'] = $this->setvalue($this->modname.'_enable_time');
			$data['deldttime_setting'] = $this->config->get($this->modname.'_setting');
			$data['stng'] = $data['deldttime_setting'];
			
			if ($data['deldttime_status'] && isset($data['stng']['shipping_method']) && in_array($smcode[0], $data['stng']['shipping_method'])) {
				
				$data['weekend'] = isset($data['stng']['weekend']) ? $data['stng']['weekend'] : array();
				$data['disabledate'] = isset($data['stng']['disabledate']) ? explode(",",trim($data['stng']['disabledate'])) : array();
				// echo $this->session->data['deldttime_dateslot'];  echo $this->session->data['deldttime_timeslot']; 
				
				$data['valdatearray'] = array();
				for($x = (int)$data['deldttime_setting']['fromxday']; $x<=(int)$data['deldttime_setting']['toxday']; $x++) {
					$data['valdatearray'][] = date($getphpdateformat, strtotime("+".$x." day"));
				}
				
				return $this->load->view($this->modtpl, $data);
			} 
		}
	}
	
	public function savedata() {
		$this->load->language($this->modpath);
		
		$this->load->model('extension/deldttime');
		
		$json = array();
		
		if(isset($this->request->post['deldttime_dateslot']) && $this->request->post['deldttime_dateslot'] != '') {
			$this->session->data['deldttime_dateslot'] = $this->request->post['deldttime_dateslot'];
			
			$deldttime_date = $this->model_extension_deldttime->getdatewithformatDB($this->session->data['deldttime_dateslot']);
 			
			$this->db->query("UPDATE " . DB_PREFIX . "order SET deldttime_dateslot='" . $this->db->escape($deldttime_date) . "' WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
			
			$json['success'] = $this->language->get('txt_save_success');
 		} else {
			$json['error']['error_del_date'] = $this->language->get('txt_error_del_date');
		}
		
		$deldttime_enable_time = $this->setvalue($this->modname.'_enable_time');
		if($deldttime_enable_time) { 
			if(isset($this->request->post['deldttime_timeslot']) && $this->request->post['deldttime_timeslot'] != '') {
				$this->session->data['deldttime_timeslot'] = $this->request->post['deldttime_timeslot'];
				
				$deldttime_time = $this->model_extension_deldttime->gettimewithformatDB($this->session->data['deldttime_timeslot']);
				
				$this->db->query("UPDATE " . DB_PREFIX . "order SET deldttime_timeslot='" . $this->db->escape($deldttime_time) . "' WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
				
				$json['success'] = $this->language->get('txt_save_success');
			} else {
				$json['error']['error_del_time'] = $this->language->get('txt_error_del_time');
			}
		}	
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
	
	private function setvalue($postfield) {
		return $this->config->get($postfield);
	}
	
	private function checkdeldatetimefield() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'deldttime_dateslot' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `deldttime_dateslot` DATE NOT NULL ");
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'deldttime_timeslot' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `deldttime_timeslot` VARCHAR( 50 ) NOT NULL ");
		}
	}
}