<?php

class ControllerExtensionApiOrderEntry extends Controller {
	private $error = array();

	public function setLanguage() {
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			$this->session->data['language'] = $this->config->get('config_language');
			$this->session->data['oe'] = array();
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setStoreCredit() {
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['store_credit']) && $this->request->post['store_credit'] > 0) {
				$this->session->data['oe']['store_credit'] = $this->request->post['store_credit'];
			} else {
				unset($this->session->data['oe']['store_credit']);
			}
			if (isset($this->request->post['coupon']) && $this->request->post['coupon']) {
				$this->session->data['coupon'] = $this->request->post['coupon'];
				$this->session->data['oe']['coupon'] = $this->request->post['coupon'];
			} else {
				unset($this->session->data['coupon']);
			}
			if (isset($this->request->post['reward']) && $this->request->post['reward'] >= 0) {
				$this->session->data['reward'] = $this->request->post['reward'];
				$this->session->data['oe']['reward'] = $this->request->post['reward'];
			} else {
				unset($this->session->data['reward']);
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setTax() {
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if ($this->request->post['no_tax']) {
				$this->session->data['oe']['no_tax'] = 1;
			} else {
				unset($this->session->data['oe']['no_tax']);
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getCustomShipping() {
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->session->data['shipping_methods'])) {
				$json['shipping_methods'] = $this->session->data['shipping_methods'];
			}
			if (isset($this->request->post['custom_shipping_title']) && $this->request->post['custom_shipping_title']) {
				if (isset($this->session->data['shipping_methods'])) {
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['title'] = $this->request->post['custom_shipping_title'];
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['cost'] = $this->request->post['custom_shipping_cost'];
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['text'] = $this->currency->format($this->tax->calculate($this->request->post['custom_shipping_cost'], $this->config->get('oe_custom_shipping_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
					$this->session->data['oe']['custom_shipping']['title'] = $this->request->post['custom_shipping_title'];
					$this->session->data['oe']['custom_shipping']['cost'] = $this->request->post['custom_shipping_cost'];
					$json['title'] = $this->request->post['custom_shipping_title'];
					$json['cost'] = $this->request->post['custom_shipping_cost'];
				}
			} elseif (isset($this->session->data['oe']['custom_shipping']) && $this->session->data['oe']['custom_shipping']['title']) {
				$json['title'] = $this->session->data['oe']['custom_shipping']['title'];
				$json['cost'] = $this->session->data['oe']['custom_shipping']['cost'];
			} else {
				$json['title'] = $this->config->get('oe_custom_shipping_title');
				$json['cost'] = $this->currency->format($this->config->get('oe_custom_shipping_cost'), $this->session->data['currency'], false, false);
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function applyCustomShipping() {
		$this->load->language('extension/shipping/oe_custom_shipping');
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if ($this->request->post['title'] != '' && $this->request->post['cost'] != '') {
				if (isset($this->session->data['shipping_methods'])) {
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['title'] = $this->request->post['title'];
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['cost'] = $this->request->post['cost'];
					$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['text'] = $this->currency->format($this->tax->calculate($this->request->post['cost'], $this->config->get('oe_custom_shipping_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
				}
				$this->session->data['oe']['custom_shipping']['title'] = $this->request->post['title'];
				$this->session->data['oe']['custom_shipping']['cost'] = $this->request->post['cost'];
				$json['shipping_methods'] = $this->session->data['shipping_methods'];
			} else {
				unset($this->session->data['oe']['custom_shipping']);
				$json['error']['warning'] = $this->language->get('error_custom_shipping');
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCustomShipping() {
		$this->load->language('extension/shipping/oe_custom_shipping');
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->session->data['shipping_methods']) && isset($this->session->data['oe']['custom_shipping'])) {
				$json['title'] = $this->config->get('oe_custom_shipping_title');
				$json['cost'] = $this->currency->format($this->config->get('oe_custom_shipping_cost'), $this->session->data['currency'], false, false);
				unset($this->session->data['oe']['custom_shipping']);
				$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['title'] = $this->config->get('oe_custom_shipping_title');
				$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['cost'] = $this->currency->format($this->config->get('oe_custom_shipping_cost'), $this->session->data['currency'], false, false);
				$this->session->data['shipping_methods']['oe_custom_shipping']['quote']['oe_custom_shipping']['text'] = $this->currency->format($this->tax->calculate($this->config->get('oe_custom_shipping_cost'), $this->config->get('oe_custom_shipping_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
			}
			if (isset($this->session->data['shipping_methods'])) {
				$json['shipping_methods'] = $this->session->data['shipping_methods'];
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setShippingMethod() {
		$json = array();
		$json['shipping_code'] = '';
		if (isset($this->request->post['shipping_method']) && $this->request->post['shipping_method']) {
			$shipping_method = $this->request->post['shipping_method'];
		} elseif (isset($this->request->get['shipping_method']) && $this->request->get['shipping_method']) {
			$shipping_method = $this->request->get['shipping_method'];
		}
		if (isset($this->session->data['shipping_methods']) && isset($shipping_method)) {
			$shipping = explode('.', $shipping_method);
			if (isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				$json['shipping_code'] = $shipping_method;
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setPaymentMethod() {
		$json = array();
		$json['payment_code'] = '';
		if (isset($this->request->post['payment_method']) && $this->request->post['payment_method']) {
			$payment_method = $this->request->post['payment_method'];
		} elseif (isset($this->request->get['payment_method']) && $this->request->get['payment_method']) {
			$payment_method = $this->request->get['payment_method'];
		}
		if (isset($this->session->data['payment_methods']) && isset($payment_method)) {
			if (isset($this->session->data['payment_methods'][$payment_method])) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$payment_method];
				$json['payment_code'] = $payment_method;
			}
		}
		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: *');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getZoneCode() {
		$json = array();
		$this->load->model('localisation/zone');
		$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
		if ($zone_info) {
			$json['zone_code'] = $zone_info['code'];
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}

?>