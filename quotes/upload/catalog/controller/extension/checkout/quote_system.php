<?php

class ControllerExtensionCheckoutQuoteSystem extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/checkout/quote_system');
		$this->cart->clearquote();
		$this->document->setTitle($this->language->get('heading_title3'));
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success2'),
			'href' => $this->url->link('extension/checkout/quote_system')
		);
		$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/quote_system', '', true), $this->url->link('information/contact'));
		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('extension/common/quote_system', $data));
	}

	public function add() {
		$this->load->language('extension/checkout/quote_system');
		$this->load->language('checkout/cart');
		$json = array();
		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {
			if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_info['minimum'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
			}
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}
			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);
			if ($recurrings) {
				$recurring_ids = array();
				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}
				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}
			if (!$json) {
				if (!isset($this->session->data['quote_guest_id'])) {
					$this->session->data['quote_guest_id'] = $this->getQuoteGuestId();
				}
				$this->cart->addquote($this->request->post['product_id'], $quantity, $option, $recurring_id);
				$json['success'] = sprintf($this->language->get('text_success_quote'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$json = array();
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->updatequote($key, $value);
			}
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('extension/checkout/quote_system');
		$json = array();
		if (isset($this->request->post['key'])) {
			$this->cart->removequote($this->request->post['key']);
			$this->session->data['success'] = $this->language->get('text_remove_quote');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getQuoteGuestId() {
		$this->load->model('extension/checkout/quote_system');
		$quote_guest_id = $this->model_extension_checkout_quote_system->getQuoteGuestId();
		return $quote_guest_id;
	}

	public function requestQuote() {
		$this->load->language('extension/checkout/quote_system');
		if ($this->cart->hasQuoteProducts()) {
			$this->load->model('extension/checkout/quote_system');
			$order_data = array();
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				$order_data['store_url'] = HTTP_SERVER;
			}
			if ($this->customer->isLogged()) {
				$this->load->model('account/customer');
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
				$this->load->model('account/address');
				$address_info = $this->model_account_address->getAddress($customer_info['address_id']);
				$order_data['customer_id'] = $this->customer->getId();
				$order_data['quote_account_id'] = 0;
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['payment_firstname'] = $address_info['firstname'];
				$order_data['payment_lastname'] = $address_info['lastname'];
				$order_data['payment_company'] = $address_info['company'];
				$order_data['payment_address_1'] = $address_info['address_1'];
				$order_data['payment_address_2'] = $address_info['address_2'];
				$order_data['payment_city'] = $address_info['city'];
				$order_data['payment_postcode'] = $address_info['postcode'];
				$order_data['payment_zone'] = $address_info['zone'];
				$order_data['payment_zone_code'] = $address_info['zone_code'];
				$order_data['payment_zone_id'] = $address_info['zone_id'];
				$order_data['payment_country'] = $address_info['country'];
				$order_data['payment_country_id'] = $address_info['country_id'];
				$order_data['payment_address_format'] = $address_info['address_format'];
				$order_data['shipping_firstname'] = $address_info['firstname'];
				$order_data['shipping_lastname'] = $address_info['lastname'];
				$order_data['shipping_company'] = $address_info['company'];
				$order_data['shipping_address_1'] = $address_info['address_1'];
				$order_data['shipping_address_2'] = $address_info['address_2'];
				$order_data['shipping_city'] = $address_info['city'];
				$order_data['shipping_postcode'] = $address_info['postcode'];
				$order_data['shipping_zone'] = $address_info['zone'];
				$order_data['shipping_zone_code'] = $address_info['zone_code'];
				$order_data['shipping_zone_id'] = $address_info['zone_id'];
				$order_data['shipping_country'] = $address_info['country'];
				$order_data['shipping_country_id'] = $address_info['country_id'];
				$order_data['shipping_address_format'] = $address_info['address_format'];
			} elseif (isset($this->session->data['quote_account_id'])) {
				$order_data['customer_id'] = 0;
				$order_data['quote_account_id'] = $this->session->data['quote_account_id'];
				$order_data['customer_group_id'] = $this->session->data['quote_guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['quote_guest']['firstname'];
				$order_data['lastname'] = $this->session->data['quote_guest']['lastname'];
				$order_data['email'] = $this->session->data['quote_guest']['email'];
				$order_data['telephone'] = $this->session->data['quote_guest']['telephone'];
				$order_data['payment_firstname'] = $this->session->data['quote_guest']['firstname'];
				$order_data['payment_lastname'] = $this->session->data['quote_guest']['lastname'];
				$order_data['payment_company'] = $this->session->data['payment_address']['company'];
				$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
				$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
				$order_data['payment_city'] = $this->session->data['payment_address']['city'];
				$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
				$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
				$order_data['payment_zone_code'] = $this->session->data['payment_address']['zone_code'];
				$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$order_data['payment_country'] = $this->session->data['payment_address']['country'];
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['shipping_firstname'] = $this->session->data['quote_guest']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['quote_guest']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_code'] = $this->session->data['shipping_address']['zone_code'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
			} else {
				$order_data['customer_id'] = 0;
				$order_data['quote_account_id'] = '';
				$order_data['customer_group_id'] = '';
				$order_data['firstname'] = '';
				$order_data['lastname'] = '';
				$order_data['email'] = '';
				$order_data['telephone'] = '';
				$order_data['payment_firstname'] = '';
				$order_data['payment_lastname'] = '';
				$order_data['payment_company'] = '';
				$order_data['payment_address_1'] = '';
				$order_data['payment_address_2'] = '';
				$order_data['payment_city'] = '';
				$order_data['payment_postcode'] = '';
				$order_data['payment_zone'] = '';
				$order_data['payment_zone_code'] = '';
				$order_data['payment_zone_id'] = '';
				$order_data['payment_country'] = '';
				$order_data['payment_country_id'] = '';
				$order_data['payment_address_format'] = '';
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_code'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
			}
			$order_data['shipping_method'] = '';
			$order_data['shipping_code'] = '';
			$order_data['soft_quote'] = 0;
			$order_data['products'] = array();
			foreach ($this->cart->getQuoteProducts() as $product) {
				$option_data = array();
				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
				$order_data['products'][] = array(
					'product_id'		=> $product['product_id'],
					'custom_product'	=> $product['custom_product'],
					'name'				=> $product['name'],
					'model'				=> $product['model'],
					'sku'				=> $product['sku'],
					'upc'				=> $product['upc'],
					'option'			=> $option_data,
					'location'			=> $product['location'],
					'shipping'			=> $product['shipping'],
					'image'				=> $product['image'],
					'tax_class_id'		=> $product['tax_class_id'],
					'sort_order'		=> (isset($product['sort_order']) ? $product['sort_order'] : 0),
					'weight'			=> $product['weight'],
					'weight_class_id'	=> $product['weight_class_id'],
					'length'			=> $product['length'],
					'length_class_id'	=> $product['length_class_id'],
					'width'				=> $product['width'],
					'height'			=> $product['height'],
					'quantity'			=> $product['quantity'],
					'subtract'			=> $product['subtract'],
					'price'				=> $product['price'],
					'total'				=> $product['total'],
					'tax'				=> 0
				);
			}
			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];
			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}
			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}
			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}
			$order_data['order_status_id'] = $this->config->get('module_quote_system_request_status_id');
			$order_data['totals'] = array();
			$order_data['total'] = 0;
			$order_data['comment'] = '';
			$order_data['notify'] = 1;
			$result = $this->model_extension_checkout_quote_system->addQuote($order_data);
			if ($result) {
				$this->response->redirect($this->url->link('extension/checkout/quote_system'));
			} else {
				$this->session->data['error'] = $this->language->get('error_quote');
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_no_quote_products');
			$this->response->redirect($this->url->link('checkout/cart', '', true));
		}
	}

	public function createQuoteAccount() {
		$this->load->language('extension/checkout/quote_system');
		$this->document->setTitle($this->language->get('heading_title1'));
		$this->load->model('extension/checkout/quote_system');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$quote_account = $this->model_extension_checkout_quote_system->createQuoteAccount($this->request->post);
			if (isset($this->session->data['quote_guest_id'])) {
				$this->model_extension_checkout_quote_system->updateQuoteCart($this->session->data['quote_guest_id'], $quote_account);
				unset($this->session->data['quote_guest_id']);
			}
			$this->load->model('localisation/country');
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			$this->load->model('localisation/zone');
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			$this->session->data['quote_account_id'] = $quote_account;
			$this->session->data['quote_guest']['customer_group_id'] = $this->request->post['customer_group_id'];
			$this->session->data['quote_guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['quote_guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['quote_guest']['email'] = $this->request->post['email'];
			$this->session->data['quote_guest']['telephone'] = $this->request->post['telephone'];
			$this->session->data['payment_address']['company'] = $this->request->post['company'];
			$this->session->data['payment_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['payment_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['payment_address']['city'] = $this->request->post['city'];
			$this->session->data['payment_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
			$this->session->data['payment_address']['country'] = $country_info['name'];
			$this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];
			$this->session->data['payment_address']['zone'] = $zone_info['name'];
			$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
			$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
			$this->session->data['shipping_address']['company'] = $this->request->post['company'];
			$this->session->data['shipping_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['shipping_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['shipping_address']['city'] = $this->request->post['city'];
			$this->session->data['shipping_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
			$this->session->data['shipping_address']['country'] = $country_info['name'];
			$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
			$this->session->data['shipping_address']['zone'] = $zone_info['name'];
			$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
			$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
			$this->response->redirect($this->url->link('extension/checkout/quote_system/requestQuote'));
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart', '', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_your_details'),
			'href' => $this->url->link('extension/checkout/quote_system/requestquote', '', true)
		);
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', true));
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}
		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} elseif (isset($this->error['email_exists'])) {
			$data['error_email'] = $this->error['email_exists'];
		} else {
			$data['error_email'] = '';
		}
		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}
		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}
		$data['action'] = $this->url->link('extension/checkout/quote_system/createQuoteAccount', '', true);
		$data['customer_groups'] = array();
		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}
		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}
		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} else {
			$data['company'] = '';
		}
		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} else {
			$data['address_1'] = '';
		}
		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} else {
			$data['address_2'] = '';
		}
		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}
		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} else {
			$data['postcode'] = '';
		}
		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}
		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} else {
			$data['zone_id'] = $this->config->get('config_zone_id');
		}
		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}
		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		$this->session->data['redirect'] = $this->url->link('extension/checkout/quote_system/requestQuote', '', true);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('extension/account/quote_system_guest', $data));
	}

	private function validate() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		$email_exists = $this->model_extension_checkout_quote_system->checkEmail($this->request->post['email']);
		if ($email_exists) {
			$this->error['email_exists'] = $this->language->get('error_email_exists');
		}
		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}
		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}
		return !$this->error;
	}

}

?>