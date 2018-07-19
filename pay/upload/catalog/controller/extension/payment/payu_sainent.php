<?php
/**
 * @author     Prashant Bhagat
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    1.0
 * @Website    http://www.sainent.com/
 */
 
class ControllerExtensionPaymentPayuSainent extends Controller {

	public function index() {	
    	$data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->model('checkout/order');
		$this->language->load('extension/payment/payu_sainent');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $currency_code = $order_info['currency_code'];

		$payu_merchantids = $this->config->get('payment_payu_sainent_merchantid');
		$payu_salts = $this->config->get('payment_payu_sainent_salt');
		$payu_currencies = $this->config->get('payment_payu_sainent_currency');	
		
		if($currency_code == $this->config->get('payment_payu_sainent_currency1')){
			$key = $this->config->get('payment_payu_sainent_merchantid1');        		
			$salt = $this->config->get('payment_payu_sainent_salt1');
		} else {
			foreach ($payu_currencies as $index => $value) {
				if($value == $currency_code){
					$key = $payu_merchantids[$index];        		
					$salt = $payu_salts[$index];
				}
			}
		}
		
        if(isset($key, $salt)){ 
			$data['merchant'] = $key;
	        //$calculatedAmount_INR = $order_info['total'];    
			$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			if($order_info['currency_code'] != 'INR'){
				$calculatedAmount_INR = $this->currency->convert( $total, $order_info['currency_code'],'INR');
			}else{
				$calculatedAmount_INR = $total;
			}
			$calculatedAmount_INR = round($calculatedAmount_INR,2);			
			if($this->config->get('payment_payu_sainent_test')=='demo'){
				$data['action'] = 'https://test.payu.in/_payment.php';
				$txnid        = 	$this->session->data['order_id'] + 56799034;
			}else{
				$data['action'] = 'https://secure.payu.in/_payment.php';
				$txnid        = 	$this->session->data['order_id'];
			}        
			$data['key'] = $key;
			$data['salt'] = $salt;
			$data['txnid'] = $txnid;
			$data['amount'] = $calculatedAmount_INR;
			$data['productinfo'] = 'opencart products information';
			$data['firstname'] = $order_info['payment_firstname'];
			$data['Lastname'] = $order_info['payment_lastname'];
			$data['Zipcode'] = $order_info['payment_postcode'];
			$data['email'] = $order_info['email'];
			$data['phone'] = $order_info['telephone'];
			$data['address1'] = $order_info['payment_address_1'];
	        $data['address2'] = $order_info['payment_address_2'];
	        $data['state'] = $order_info['payment_zone'];
	        $data['city']=$order_info['payment_city'];
	        $data['country']=$order_info['payment_country'];
	        $data['pg']= '';
	        $data['bankcode']=$this->config->get('payment_payu_sainent_bankcode_val');
			$data['service_provider'] = $this->config->get('payment_payu_sainent_payment_gateway');
			
	        $data['surl'] = $this->url->link('extension/payment/payu_sainent/callback');//HTTP_SERVER.'/index.php?route=extension/payment/payu/callback';
	        $data['Furl'] = $this->url->link('extension/payment/payu_sainent/callback');//HTTP_SERVER.'/index.php?route=extension/payment/payu/callback';
			$data['curl'] = $this->url->link('extension/payment/payu_sainent/callback'); 
			$key          =  $key;
			$amount       = (int)$order_info['total'];
			$productInfo  = $data['productinfo'];
		    $firstname    = $order_info['payment_firstname'];
			$email        = $order_info['email'];
			$salt         = $salt;
			
			$Hash=hash('sha512', $key.'|'.$txnid.'|'.$calculatedAmount_INR.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt); 
			
			$data['user_credentials'] = $this->data['key'].':'.$this->data['email'];
			$data['Hash'] = $Hash;
	    } else
	    {
          echo '<h4 style="color:red">WARNING: Something Went wrong with configuration Please put Key and salt with refrence to Curreny: '.$currency_code.' !!</h4>';
	    }

		
			/////////////////////////////////////End Payu Vital  Information /////////////////////////////////
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/payu_sainent')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/payu_sainent', $data);
		} else {
			return $this->load->view('extension/payment/payu_sainent', $data);
		}		
		
		
		
	}
	
	public function callback() {

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $currency_code = $order_info['currency_code'];
        
		$payu_merchantids = $this->config->get('payment_payu_sainent_merchantid');
		$payu_salts = $this->config->get('payment_payu_sainent_salt');
		$payu_currencies = $this->config->get('payment_payu_sainent_currency');	
		
		if($currency_code == $this->config->get('payment_payu_sainent_currency1')){
			$key = $this->config->get('payment_payu_sainent_merchantid1');        		
			$salt = $this->config->get('payment_payu_sainent_salt1');
		} else {
			foreach ($payu_currencies as $index => $value) {
				if($value == $currency_code){
					$key = $payu_merchantids[$index];        		
					$salt = $payu_salts[$index];
				}
			}
		}
		
			
		if (isset($this->request->post['key']) && ($this->request->post['key'] == isset($key))) {
			$this->language->load('extension/payment/payu_sainent');
			
			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = HTTP_SERVER;
			} else {
				$data['base'] = HTTPS_SERVER;
			}
		
			$data['charset'] = $this->language->get('charset');
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			$data['text_response'] = $this->language->get('text_response');
			$data['text_success'] = $this->language->get('text_success');
			$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$data['text_failure'] = $this->language->get('text_failure');
			$data['text_cancelled'] = $this->language->get('text_cancelled');
			$data['text_cancelled_wait'] = sprintf($this->language->get('text_cancelled_wait'), $this->url->link('checkout/checkout'));
			$data['text_pending'] = $this->language->get('text_pending');
			$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout'));
			
			$this->load->model('checkout/order');
			if($this->config->get('payment_payu_sainent_test')=='demo'){
				$orderid = $this->request->post['txnid']- date("YmdHis");
			} else {
				$orderid = $this->request->post['txnid'];
			}
			 
			$order_info = $this->model_checkout_order->getOrder($orderid);
			
			$key          		=  	$this->request->post['key'];
			$amount      		= 	$this->request->post['amount'];
			$productInfo  		= 	$this->request->post['productinfo'];
			$firstname    		= 	$this->request->post['firstname'];
			$email        		=	$this->request->post['email'];
			$salt        		= 	$salt;
			$txnid		 		=   $this->request->post['txnid'] ;
			$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||||||||||';
			$keyArray 	  		= 	explode("|",$keyString);
			$reverseKeyArray 	= 	array_reverse($keyArray);
			$reverseKeyString	=	implode("|",$reverseKeyArray);	
			if (isset($this->request->post['status']) && $this->request->post['status'] == 'success') {
			 	
			 	$saltString     = $salt.'|'.$this->request->post['status'].'|'.$reverseKeyString;
				$sentHashString = strtolower(hash('sha512', $saltString));
			 	$responseHashString=$this->request->post['hash'];
				
				$order_id = $this->request->post['txnid'];
				$message = '';
				$message .= 'orderId: ' . $this->request->post['txnid'] . "\n";
				$message .= 'Transaction Id: ' . $this->request->post['mihpayid'] . "\n";
				foreach($this->request->post as $k => $val){
					$message .= $k.': ' . $val . "\n";
				}
				
				if($sentHashString==$this->request->post['hash']){

					if($this->request->post['unmappedstatus'] == 'captured'){
						$payment_payu_sainent_captured_order_status_id = $this->config->get('payment_payu_sainent_captured_order_status_id');
						$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_captured_order_status_id);
					} elseif ($this->request->post['unmappedstatus'] == 'auth'){
						$payment_payu_sainent_auth_order_status_id = $this->config->get('payment_payu_sainent_auth_order_status_id');
						$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_auth_order_status_id);
					}

					$data['continue'] = $this->url->link('checkout/success');
					$data['column_left'] = $this->load->controller('common/column_left');
					$data['column_right'] = $this->load->controller('common/column_right');
					$data['content_top'] = $this->load->controller('common/content_top');
					$data['content_bottom'] = $this->load->controller('common/content_bottom');
					$data['footer'] = $this->load->controller('common/footer');
					$data['header'] = $this->load->controller('common/header');
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/payu_sainent_success')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/payu_sainent_success', $data));
					} else {
						$this->response->setOutput($this->load->view('extension/payment/payu_sainent_success', $data));
					}	
				}
			}else {
    			$data['continue'] = $this->url->link('checkout/checkout');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

		        if(isset($this->request->post['status']) && $this->request->post['unmappedstatus'] == 'userCancelled'){
					$payment_payu_sainent_user_cancelled_order_status_id = $this->config->get('payment_payu_sainent_user_cancelled_order_status_id');
					$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_user_cancelled_order_status_id);

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/payu_sainent_cancelled')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/payu_sainent_cancelled', $data));
					} else {
						$payment_payu_sainent_cancelled_order_status_id = $this->config->get('payment_payu_sainent_cancelled_order_status_id');
						$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_cancelled_order_status_id);
						$this->response->setOutput($this->load->view('extension/payment/payu_sainent_cancelled', $data));
					}
				} else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/payu_sainent_failure')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/payu_sainent_failure', $data));
					} else {
						$this->response->setOutput($this->load->view('extension/payment/payu_sainent_failure', $data));
					}
				}					
			}
		}

		
			
		if($this->request->post['unmappedstatus'] == 'initiated'){	
			$payment_payu_sainent_initiated_order_status_id = $this->config->get('payment_payu_sainent_initiated_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_initiated_order_status_id);
		} elseif ($this->request->post['unmappedstatus'] == 'in progress'){
			$payment_payu_sainent_inprogress_order_status_id = $this->config->get('payment_payu_sainent_inprogress_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_inprogress_order_status_id);
		} elseif ($this->request->post['unmappedstatus'] == 'dropped'){
			$payment_payu_sainent_dropped_order_status_id = $this->config->get('payment_payu_sainent_dropped_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_dropped_order_status_id);
		} elseif ($this->request->post['unmappedstatus'] == 'bounced'){
			$payment_payu_sainent_bounced_order_status_id = $this->config->get('payment_payu_sainent_bounced_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_bounced_order_status_id);
		} elseif ($this->request->post['unmappedstatus'] == 'failed'){
			$payment_payu_sainent_failed_order_status_id = $this->config->get('payment_payu_sainent_failed_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_failed_order_status_id);
		} elseif ($this->request->post['unmappedstatus'] == 'pending'){
			$payment_payu_sainent_pending_order_status_id = $this->config->get('payment_payu_sainent_pending_order_status_id');
			$this->model_checkout_order->addOrderHistory($orderid, $payment_payu_sainent_pending_order_status_id);
		} 

		if(isset($orderid)){
			$sql2 = "UPDATE " . DB_PREFIX . "order SET custom_field = 'mihpayid :-" .$this->request->post['mihpayid'] ."' WHERE order_id= '" .$orderid . "'";
			$query2 = $this->db->query($sql2);	
		}				
							
	}
}
?>
