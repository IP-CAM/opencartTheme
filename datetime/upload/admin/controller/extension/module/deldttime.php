<?php
class ControllerExtensionModuledeldttime extends Controller { 	
	private $error = array();
	private $modpath = 'module/deldttime'; 
	private $modtpl = 'module/deldttime.tpl';
	private $modtpl_orderinfo = 'module/deldttimeorderinfo.tpl';	
	private $modname = 'deldttime';
	private $modtext = 'Delivery Date Time Slot At Checkout';
	private $modid = '28132';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com';
	private $token_str = '';
	private $modurl = 'extension/module';
	private $modurltext = '';

	public function __construct($registry) {
		parent::__construct($registry);
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
			$this->modtpl = 'extension/module/deldttime';
			$this->modtpl_orderinfo = 'extension/module/deldttimeorderinfo';
			$this->modpath = 'extension/module/deldttime';
		} else if(substr(VERSION,0,3)=='2.2') {
			$this->modtpl = 'module/deldttime';
			$this->modtpl_orderinfo = 'module/deldttimeorderinfo';
		} 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'module_deldttime';
			$this->modurl = 'marketplace/extension'; 
			$this->token_str = 'user_token=' . $this->session->data['user_token'] . '&type=module';
		} else if(substr(VERSION,0,3)=='2.3') {
			$this->modurl = 'extension/extension';
			$this->token_str = 'token=' . $this->session->data['token'] . '&type=module';
		} else {
			$this->token_str = 'token=' . $this->session->data['token'];
		}
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
 	} 
	
	public function index() {
		$data = $this->load->language($this->modpath);
		$this->modurltext = $this->language->get('text_extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->modname, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if(! (isset($this->request->post['svsty']) && $this->request->post['svsty'] == 1)) {
				$this->response->redirect($this->url->link($this->modurl, $this->token_str, $this->modssl));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');
 		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
 		$data['entry_status'] = $this->language->get('entry_status');
  		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->token_str, $this->modssl)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->modurltext,
			'href' => $this->url->link($this->modurl, $this->token_str, $this->modssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->modpath, $this->token_str, $this->modssl)
		);

		$data['action'] = $this->url->link($this->modpath, $this->token_str, $this->modssl);
		
		$data['cancel'] = $this->url->link($this->modurl, $this->token_str , $this->modssl); 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$data['user_token'] = $this->session->data['user_token'];
		} else {
			$data['token'] = $this->session->data['token'];
		}
		
		$this->load->model('setting/store');
 		$data['stores'] = $this->model_setting_store->getStores();
		
		$data['shipping_method'] = $this->getSM();

		$data[$this->modname.'_status'] = $this->setvalue($this->modname.'_status');
		$data[$this->modname.'_enable_time'] = $this->setvalue($this->modname.'_enable_time');
		$data[$this->modname.'_setting'] = $this->setvalue($this->modname.'_setting'); 
		$data['stng'] = $data[$this->modname.'_setting'];
		
		$data['stng']['dateformat'] = isset($data['stng']['dateformat']) ? $data['stng']['dateformat'] : 1;
		$data['stng']['weekend'] = isset($data['stng']['weekend']) ? $data['stng']['weekend'] : array(7);
		
 		$data['modname'] = $this->modname;
		$data['modemail'] = $this->modemail;
  		  
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->modtpl, $data));
	}
	
	public function orderinfo($order_id) {
		if($order_id) { 
			$this->load->language($this->modpath);
			$this->load->language('sale/order');
			
			$this->load->model('sale/order');
			$this->load->model('extension/deldttime');
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			if($order_info) { 
 				$data['order_id'] = $order_id;
				
				$data['deldttime_getformat'] = $this->model_extension_deldttime->getformat();
				$data['modpath'] = $this->modpath;
				
				$data['deldttime_status'] = $this->setvalue($this->modname.'_status');
				$data['deldttime_enable_time'] = $this->setvalue($this->modname.'_enable_time');
				$data['deldttime_setting'] = $this->setvalue($this->modname.'_setting'); 
				$data['stng'] = $data['deldttime_setting'];
				
				if(substr(VERSION,0,3)>='3.0') { 
					$data['user_token'] = $this->session->data['user_token'];
				} else {
					$data['token'] = $this->session->data['token'];
				}
				
				$data['del_date'] = $this->language->get('del_date');
				$data['del_time'] = $this->language->get('del_time');
				
				$data['deldttime_dateslot'] = isset($order_info['deldttime_dateslot']) ? $order_info['deldttime_dateslot'] : '';
				$data['deldttime_timeslot'] = isset($order_info['deldttime_timeslot']) ? $order_info['deldttime_timeslot'] : '';
 				
 				$data['modname'] = $this->modname;
				$data['modemail'] = $this->modemail;
				  
				return $this->load->view($this->modtpl_orderinfo, $data);
			}
		}
	} 

	public function savedateslot() {
		if($this->request->get['order_id'] && $this->request->post['input_deldttime_dateslot']) {
			
			$this->load->model('extension/deldttime');
			$val_deldttime_dateslot = $this->model_extension_deldttime->getdatewithformatDB($this->request->post['input_deldttime_dateslot']);
			
  			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET deldttime_dateslot = '".$val_deldttime_dateslot."' where order_id = ".(int)$this->request->get['order_id']);
		} 
	}
	
	public function savetimeslot() {
		if($this->request->get['order_id'] && $this->request->post['input_deldttime_timeslot']) {
			
			$this->load->model('extension/deldttime');
			$val_deldttime_timeslot = $this->model_extension_deldttime->gettimewithformatDB($this->request->post['input_deldttime_timeslot']);
   
 			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET deldttime_timeslot = '".$val_deldttime_timeslot."' where order_id = ".(int)$this->request->get['order_id']);
		} 
	}
	
	public function getSM() {
		$sm = array();
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->load->model('setting/extension');
			$extensions = $this->model_setting_extension->getInstalled('shipping');
		} else {
			$this->load->model('extension/extension');
			$extensions = $this->model_extension_extension->getInstalled('shipping');
		}
 		
 		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') {
			$files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php', GLOB_BRACE);
		} else {
			$files = glob(DIR_APPLICATION . 'controller/shipping/*.php', GLOB_BRACE);
		}
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php'); 				
				$checkstatus = false;
				if(substr(VERSION,0,3)>='3.0') { 
					$checkstatus = $this->config->get('shipping_' . $extension . '_status');
				} else {
					$checkstatus = $this->config->get($extension . '_status');	
				}
				
				if($checkstatus) {
					if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') {
						$lang = $this->load->language('extension/shipping/' . $extension);
					} else {
						$lang = $this->load->language('shipping/' . $extension);
					}
					$sm[$extension] = $lang['heading_title'];
				}
			}
		}
		return $sm;
	}
	
	protected function setvalue($postfield) {
		if (isset($this->request->post[$postfield])) {
			$postfield_value = $this->request->post[$postfield];
		} else {
			$postfield_value = $this->config->get($postfield);
		} 	
 		return $postfield_value;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->modpath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'deldttime_dateslot' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `deldttime_dateslot` DATE NOT NULL ");
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'deldttime_timeslot' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `deldttime_timeslot` VARCHAR( 50 ) NOT NULL ");
		}
		
		@mail($this->modemail,
		"Extension Installed",
		"Hello!" . "\r\n" .  
		"Extension Name :  ".$this->modtext."" ."\r\n". 
		"Extension ID : ".$this->modid ."\r\n". 
		"Version : " . VERSION. "\r\n". 
		"Installed At : " .HTTP_CATALOG ."\r\n". 
		"Licence Start Date : " .date("Y-m-d") ."\r\n".  
		"Licence Expiry Date : " .date("Y-m-d", strtotime('+1 year'))."\r\n". 
		"From: ".$this->config->get('config_email'),
		"From: ".$this->config->get('config_email'));      
	}
	public function uninstall() { 
		//$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP deldttime_dateslot ");
		//$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` DROP deldttime_timeslot ");		
		
		@mail($this->modemail,
		"Extension Uninstalled",
		"Hello!" . "\r\n" .  
		"Extension Name :  ".$this->modtext."" ."\r\n". 
		"Extension ID : ".$this->modid ."\r\n". 
		"Version : " . VERSION. "\r\n". 
		"Installed At : " .HTTP_CATALOG ."\r\n". 
		"Licence Start Date : " .date("Y-m-d") ."\r\n".  
		"Licence Expiry Date : " .date("Y-m-d", strtotime('+1 year'))."\r\n". 
		"From: ".$this->config->get('config_email'),
		"From: ".$this->config->get('config_email'));        
	}  
}