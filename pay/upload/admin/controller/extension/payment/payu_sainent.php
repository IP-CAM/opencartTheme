<?php 
/**
 * @author     Prashant Bhagat
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    1.0
 * @Website    http://www.sainent.com/
 */

class ControllerExtensionPaymentPayuSainent extends Controller {
	private $error = array(); 

	public function index() {

		$this->load->language('extension/payment/payu_sainent');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_payu_sainent', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_fail'] = $this->language->get('text_fail');
		$data['demo'] = $this->language->get('demo');		
		$data['entry_merchantid1'] = $this->language->get('entry_merchantid1');
		$data['entry_salt1'] = $this->language->get('entry_salt1');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_pg'] = $this->language->get('entry_pg');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_total'] = $this->language->get('help_total');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['entry_merchantid1'] = $this->language->get('entry_merchantid1');
		$data['entry_salt1'] = $this->language->get('entry_salt1');
		$data['entry_currency1'] = $this->language->get('entry_currency1');
		
		$data['entry_merchantid'] = $this->language->get('entry_merchantid');
		$data['entry_salt'] = $this->language->get('entry_salt');
		$data['entry_currency'] = $this->language->get('entry_currency');
		
		$data['help_merchantid'] = $this->language->get('help_merchantid');
		$data['help_salt'] = $this->language->get('help_salt');
        $data['help_currency'] = $this->language->get('help_currency');
		

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_order_status_payubiz'] = $this->language->get('tab_order_status_payubiz');
		$data['entry_captured_order_status'] = $this->language->get('entry_captured_order_status');
		$data['entry_bounced_order_status'] = $this->language->get('entry_bounced_order_status');
		$data['entry_dropped_order_status'] = $this->language->get('entry_dropped_order_status');
		$data['entry_failed_order_status'] = $this->language->get('entry_failed_order_status');		
		$data['entry_user_cancelled_order_status'] = $this->language->get('entry_user_cancelled_order_status');
		$data['entry_inprogress_order_status'] = $this->language->get('entry_inprogress_order_status');
		$data['entry_initiated_order_status'] = $this->language->get('entry_initiated_order_status');
		$data['entry_auto_refund_order_status'] = $this->language->get('entry_auto_refund_order_status');
		$data['entry_pending_order_status'] = $this->language->get('entry_pending_order_status');
		$data['entry_auth_order_status'] = $this->language->get('entry_auth_order_status');
		$data['entry_cancelled_order_status'] = $this->language->get('entry_cancelled_order_status');
		
		$data['CreditCard'] = $this->language->get('CreditCard');
		$data['DebitCard'] = $this->language->get('DebitCard');
		$data['NetBanking'] = $this->language->get('NetBanking');
		$data['PayUMoney'] = $this->language->get('PayUMoney');
		$data['PayUbiz'] = $this->language->get('PayUbiz');

		if (isset($this->request->post['payment_payu_sainent_captured_order_status_id'])) {
			$data['payment_payu_sainent_captured_order_status_id'] = $this->request->post['payment_payu_sainent_captured_order_status_id'];
		} else {
			$data['payment_payu_sainent_captured_order_status_id'] = $this->config->get('payment_payu_sainent_captured_order_status_id');
		}
		
		if (isset($this->request->post['payment_payu_sainent_bounced_order_status_id'])) {
			$data['payment_payu_sainent_bounced_order_status_id'] = $this->request->post['payment_payu_sainent_bounced_order_status_id'];
		} else {
			$data['payment_payu_sainent_bounced_order_status_id'] = $this->config->get('payment_payu_sainent_bounced_order_status_id');
		}

		if (isset($this->request->post['payment_payu_sainent_auth_order_status_id'])) {
			$data['payment_payu_sainent_auth_order_status_id'] = $this->request->post['payment_payu_sainent_auth_order_status_id'];
		} else {
			$data['payment_payu_sainent_auth_order_status_id'] = $this->config->get('payment_payu_sainent_auth_order_status_id');
		}

		if (isset($this->request->post['payment_payu_sainent_dropped_order_status_id'])) {
			$data['payment_payu_sainent_dropped_order_status_id'] = $this->request->post['payment_payu_sainent_dropped_order_status_id'];
		} else {
			$data['payment_payu_sainent_dropped_order_status_id'] = $this->config->get('payment_payu_sainent_dropped_order_status_id');
		}

		if (isset($this->request->post['payment_payu_sainent_failed_order_status_id'])) {
			$data['payment_payu_sainent_failed_order_status_id'] = $this->request->post['payment_payu_sainent_failed_order_status_id'];
		} else {
			$data['payment_payu_sainent_failed_order_status_id'] = $this->config->get('payment_payu_sainent_failed_order_status_id');
		}

		if (isset($this->request->post['payment_payu_sainent_user_cancelled_order_status_id'])) {
			$data['payment_payu_sainent_user_cancelled_order_status_id'] = $this->request->post['payment_payu_sainent_user_cancelled_order_status_id'];
		} else {
			$data['payment_payu_sainent_user_cancelled_order_status_id'] = $this->config->get('payment_payu_sainent_user_cancelled_order_status_id');
		}
				
		if (isset($this->request->post['payment_payu_sainent_inprogress_order_status_id'])) {
			$data['payment_payu_sainent_inprogress_order_status_id'] = $this->request->post['payment_payu_sainent_inprogress_order_status_id'];
		} else {
			$data['payment_payu_sainent_inprogress_order_status_id'] = $this->config->get('payment_payu_sainent_inprogress_order_status_id');
		}
				
		if (isset($this->request->post['payment_payu_sainent_initiated_order_status_id'])) {
			$data['payment_payu_sainent_initiated_order_status_id'] = $this->request->post['payment_payu_sainent_initiated_order_status_id'];
		} else {
			$data['payment_payu_sainent_initiated_order_status_id'] = $this->config->get('payment_payu_sainent_initiated_order_status_id');
		}	
				
	    if (isset($this->request->post['payment_payu_sainent_auto_refund_order_status_id'])) {
			$data['payment_payu_sainent_auto_refund_order_status_id'] = $this->request->post['payment_payu_sainent_auto_refund_order_status_id'];
		} else {
			$data['payment_payu_sainent_auto_refund_order_status_id'] = $this->config->get('payment_payu_sainent_auto_refund_order_status_id');
		}
				
	    if (isset($this->request->post['payment_payu_sainent_pending_order_status_id'])) {
			$data['payment_payu_sainent_pending_order_status_id'] = $this->request->post['payment_payu_sainent_pending_order_status_id'];
		} else {
			$data['payment_payu_sainent_pending_order_status_id'] = $this->config->get('payment_payu_sainent_pending_order_status_id');
		}																								

		if (isset($this->request->post['payment_payu_sainent_bankcode_val'])) {
			$data['payment_payu_sainent_bankcode_val'] = $this->request->post['payment_payu_sainent_bankcode_val'];
		} else {
			$data['payment_payu_sainent_bankcode_val'] = $this->config->get('payment_payu_sainent_bankcode_val');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

        if (isset($this->error['salt'])) {
			$data['error_salt'] = $this->error['salt'];
		} else {
			$data['error_salt'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/payu_sainent', 'user_token=' . $this->session->data['user_token'], 'SSL'),
   		);
				
		$data['action'] = $this->url->link('extension/payment/payu_sainent', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL');
		
		if (isset($this->request->post['payment_payu_sainent_merchantid1'])) {
			$data['payment_payu_sainent_merchantid1'] = $this->request->post['payment_payu_sainent_merchantid1'];
		} else {
			$data['payment_payu_sainent_merchantid1'] = $this->config->get('payment_payu_sainent_merchantid1');
		}
		
		if (isset($this->request->post['payment_payu_sainent_salt1'])) {
			$data['payment_payu_sainent_salt1'] = $this->request->post['payment_payu_sainent_salt1'];
		} else {
			$data['payment_payu_sainent_salt1'] = $this->config->get('payment_payu_sainent_salt1');
		}

		if (isset($this->request->post['payment_payu_sainent_currency1'])) {
			$data['payment_payu_sainent_currency1'] = $this->request->post['payment_payu_sainent_currency1'];
		} else {
			$data['payment_payu_sainent_currency1'] = $this->config->get('payment_payu_sainent_currency1');
		}
		
		if (isset($this->request->post['payment_payu_sainent_merchantid'])) {
			$data['payment_payu_sainent_merchantid'] = $this->request->post['payment_payu_sainent_merchantid'];
		} else {
			$data['payment_payu_sainent_merchantid'] = $this->config->get('payment_payu_sainent_merchantid');
		}
		
		if (isset($this->request->post['payment_payu_sainent_salt'])) {
			$data['payment_payu_sainent_salt'] = $this->request->post['payment_payu_sainent_salt'];
		} else {
			$data['payment_payu_sainent_salt'] = $this->config->get('payment_payu_sainent_salt');
		}

		if (isset($this->request->post['payment_payu_sainent_currency'])) {
			$data['payment_payu_sainent_currency'] = $this->request->post['payment_payu_sainent_currency'];
		} else {
			$data['payment_payu_sainent_currency'] = $this->config->get('payment_payu_sainent_currency');
		}

		if (isset($this->request->post['payment_payu_sainent_test'])) {
			$data['payment_payu_sainent_test'] = $this->request->post['payment_payu_sainent_test'];
		} else {
			$data['payment_payu_sainent_test'] = $this->config->get('payment_payu_sainent_test');
		}
		
		if (isset($this->request->post['payment_payu_sainent_total'])) {
			$data['payment_payu_sainent_total'] = $this->request->post['payment_payu_sainent_total'];
		} else {
			$data['payment_payu_sainent_total'] = $this->config->get('payment_payu_sainent_total'); 
		} 
				
		if (isset($this->request->post['payment_payu_sainent_order_status_id'])) {
			$data['payment_payu_sainent_order_status_id'] = $this->request->post['payment_payu_sainent_order_status_id'];
		} else {
			$data['payment_payu_sainent_order_status_id'] = $this->config->get('payment_payu_sainent_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_payu_sainent_geo_zone_id'])) {
			$data['payment_payu_sainent_geo_zone_id'] = $this->request->post['payment_payu_sainent_geo_zone_id'];
		} else {
			$data['payment_payu_sainent_geo_zone_id'] = $this->config->get('payment_payu_sainent_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_payu_sainent_status'])) {
			$data['payment_payu_sainent_status'] = $this->request->post['payment_payu_sainent_status'];
		} else {
			$data['payment_payu_sainent_status'] = $this->config->get('payment_payu_sainent_status');
		}

		if (isset($this->request->post['payment_payu_sainent_payment_gateway'])) {
			$data['payment_payu_sainent_payment_gateway'] = $this->request->post['payment_payu_sainent_payment_gateway'];
		} else {
			$data['payment_payu_sainent_payment_gateway'] = $this->config->get('payment_payu_sainent_payment_gateway');
		}
		
		if (isset($this->request->post['payment_payu_sainent_sort_order'])) {
			$data['payment_payu_sainent_sort_order'] = $this->request->post['payment_payu_sainent_sort_order'];
		} else {
			$data['payment_payu_sainent_sort_order'] = $this->config->get('payment_payu_sainent_sort_order');
		}
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('extension/payment/payu_sainent', $data));

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/payu_sainent')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payment_payu_sainent_merchantid1']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
			
		if (!$this->request->post['payment_payu_sainent_salt1']) {
			$this->error['salt'] = $this->language->get('error_salt');
		}
		
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>