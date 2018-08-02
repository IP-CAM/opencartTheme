<?php

class ControllerExtensionApiQuoteSystem extends Controller {
	private $error = array();

	public function add() {
		$this->load->language('api/order');
		$this->load->model('extension/checkout/quote_system');
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}
			$products = $this->cart->getProducts();
			foreach ($products as $product) {
				$product_total = 0;
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}
				if ($product['minimum'] > $product_total) {
					$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
					break;
				}
			}
			if (!$json) {
				$order_data = array();
				$order_data['store_id'] = $this->config->get('config_store_id');
				$order_data['store_name'] = $this->config->get('config_name');
				$order_data['store_url'] = $this->config->get('config_url');
				if (isset($this->request->post['store']) && $this->request->post['store']) {
					$this->load->model('setting/store');
					$store_info = $this->model_setting_store->getStore($this->request->post['store']);
					if ($store_info) {
						$order_data['store_id'] = $this->request->post['store'];
						$order_data['store_name'] = $store_info['name'];
						$order_data['store_url'] = $store_info['url'];
					}
				}
				if ($this->session->data['customer']['customer_id'] > 0) {
					$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
					$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
					$order_data['quote_account_id'] = 0;
				} else {
					$quote_account = $this->model_extension_checkout_quote_system->checkEmail($this->session->data['customer']['email']);
					if ($quote_account) {
						$account_data = $this->model_extension_checkout_quote_system->getQuoteAccount($this->session->data['customer']['email']);
						$order_data['customer_id'] = 0;
						$order_data['quote_account_id'] = $account_data['quote_account_id'];
						$order_data['customer_group_id'] = $account_data['customer_group_id'];
					} else {
						$length = 8;
						$password = "";
						$pw_chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789!";
						$maxlength = strlen($pw_chars);
						if ($length > $maxlength) {
							$length = $maxlength;
						}
						$i = 0;
						while ($i < $length) {
							$char = substr($pw_chars, mt_rand(0, $maxlength-1), 1);
							if (!strstr($password, $char)) { 
								$password .= $char;
								$i++;
							}
						}
						if (isset($this->session->data['payment_address'])) {
							$firstname = $this->session->data['payment_address']['firstname'];
							$lastname = $this->session->data['payment_address']['lastname'];
							$company = $this->session->data['payment_address']['company'];
							$address_1 = $this->session->data['payment_address']['address_1'];
							$address_2 = $this->session->data['payment_address']['address_2'];
							$city = $this->session->data['payment_address']['city'];
							$postcode = $this->session->data['payment_address']['postcode'];
							$zone = $this->session->data['payment_address']['zone'];
							$zone_id = $this->session->data['payment_address']['zone_id'];
							$country = $this->session->data['payment_address']['country'];
							$country_id = $this->session->data['payment_address']['country_id'];
							$address_format = $this->session->data['payment_address']['address_format'];
							$custom_field = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());
						} else {
							$firstname = '';
							$lastname = '';
							$company = '';
							$address_1 = '';
							$address_2 = '';
							$city = '';
							$postcode = '';
							$zone = '';
							$zone_id = 0;
							$country = '';
							$country_id = 0;
							$address_format = '';
							$custom_field = array();
						}
						$account_info = array(
							'firstname'			=> $this->session->data['customer']['firstname'],
							'lastname'			=> $this->session->data['customer']['lastname'],
							'email'				=> $this->session->data['customer']['email'],
							'telephone'			=> $this->session->data['customer']['telephone'],
							'customer_group_id'	=> ($this->session->data['customer']['customer_group_id'] > 0) ? $this->session->data['customer']['customer_group_id'] : $this->config->get('config_customer_group_id'),
							'password'			=> $password,
							'company'			=> $company,
							'address_1'			=> $address_1,
							'address_2'			=> $address_2,
							'city'				=> $city,
							'postcode'			=> $postcode,
							'zone'				=> $zone,
							'zone_id'			=> $zone_id,
							'country'			=> $country,
							'country_id'		=> $country_id,
							'address_format'	=> $address_format,
							'custom_field'		=> $custom_field
						);
						$new_account = $this->model_extension_checkout_quote_system->createQuoteAccount($account_info);
						$account_data = $this->model_extension_checkout_quote_system->getQuoteAccount($this->session->data['customer']['email']);
						$order_data['customer_id'] = 0;
						$order_data['quote_account_id'] = $account_data['quote_account_id'];
						$order_data['customer_group_id'] = $account_data['customer_group_id'];
						$this->session->data['customer']['password'] = $password;
					}
				}
				$order_data['firstname'] = $this->session->data['customer']['firstname'];
				$order_data['lastname'] = $this->session->data['customer']['lastname'];
				$order_data['email'] = $this->session->data['customer']['email'];
				$order_data['telephone'] = $this->session->data['customer']['telephone'];
				$order_data['custom_field'] = $this->session->data['customer']['custom_field'];
				if (isset($this->session->data['payment_address'])) {
					$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
					$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
					$order_data['payment_company'] = $this->session->data['payment_address']['company'];
					$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
					$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
					$order_data['payment_city'] = $this->session->data['payment_address']['city'];
					$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
					$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
					$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
					$order_data['payment_country'] = $this->session->data['payment_address']['country'];
					$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
					$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
					$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());
				} elseif (isset($this->session->data['shipping_address'])) {
					$order_data['payment_firstname'] = $this->session->data['shipping_address']['firstname'];
					$order_data['payment_lastname'] = $this->session->data['shipping_address']['lastname'];
					$order_data['payment_company'] = $this->session->data['shipping_address']['company'];
					$order_data['payment_address_1'] = $this->session->data['shipping_address']['address_1'];
					$order_data['payment_address_2'] = $this->session->data['shipping_address']['address_2'];
					$order_data['payment_city'] = $this->session->data['shipping_address']['city'];
					$order_data['payment_postcode'] = $this->session->data['shipping_address']['postcode'];
					$order_data['payment_zone'] = $this->session->data['shipping_address']['zone'];
					$order_data['payment_zone_id'] = $this->session->data['shipping_address']['zone_id'];
					$order_data['payment_country'] = $this->session->data['shipping_address']['country'];
					$order_data['payment_country_id'] = $this->session->data['shipping_address']['country_id'];
					$order_data['payment_address_format'] = $this->session->data['shipping_address']['address_format'];
					$order_data['payment_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());
				} else {
					$order_data['payment_firstname'] = '';
					$order_data['payment_lastname'] = '';
					$order_data['payment_company'] = '';
					$order_data['payment_address_1'] = '';
					$order_data['payment_address_2'] = '';
					$order_data['payment_city'] = '';
					$order_data['payment_postcode'] = '';
					$order_data['payment_zone'] = '';
					$order_data['payment_zone_id'] = '';
					$order_data['payment_country'] = '';
					$order_data['payment_country_id'] = '';
					$order_data['payment_address_format'] = '';
					$order_data['payment_custom_field'] = array();
				}
				if (isset($this->session->data['shipping_address'])) {
					$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
					$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
					$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
					$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
					$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
					$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
					$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
					$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
					$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
					$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
					$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
					$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
					$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());
					if (isset($this->session->data['shipping_method']['title'])) {
						$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
					} else {
						$order_data['shipping_method'] = '';
					}
					if (isset($this->session->data['shipping_method']['code'])) {
						$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
					} else {
						$order_data['shipping_code'] = '';
					}
				} else {
					$order_data['shipping_firstname'] = '';
					$order_data['shipping_lastname'] = '';
					$order_data['shipping_company'] = '';
					$order_data['shipping_address_1'] = '';
					$order_data['shipping_address_2'] = '';
					$order_data['shipping_city'] = '';
					$order_data['shipping_postcode'] = '';
					$order_data['shipping_zone'] = '';
					$order_data['shipping_zone_id'] = '';
					$order_data['shipping_country'] = '';
					$order_data['shipping_country_id'] = '';
					$order_data['shipping_address_format'] = '';
					$order_data['shipping_custom_field'] = array();
					$order_data['shipping_method'] = '';
					$order_data['shipping_code'] = '';
				}
				$order_data['products'] = array();
				foreach ($this->cart->getProducts() as $product) {
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
						'product_id'			=> $product['product_id'],
						'name'					=> $product['name'],
						'model'					=> $product['model'],
						'option'				=> $option_data,
						'download'				=> $product['download'],
						'quantity'				=> $product['quantity'],
						'subtract'				=> $product['subtract'],
						'price'					=> $product['price'],
						'total'					=> $product['total'],
						'tax'					=> $this->tax->getTax($product['price'], $product['tax_class_id']),
						'sort_order'			=> $product['sort_order'],
						'reward'				=> $product['reward'],
						'sku'					=> (isset($product['sku']) ? $product['sku'] : ''),
						'upc'					=> (isset($product['upc']) ? $product['upc'] : ''),
						'location'				=> (isset($product['location']) ? $product['location'] : ''),
						'shipping'				=> $product['shipping'],
						'image'					=> $product['image'],
						'tax_class_id'			=> $product['tax_class_id'],
						'weight'				=> $product['weight'] / $product['quantity'],
						'weight_class_id'		=> $product['weight_class_id'],
						'length'				=> $product['length'],
						'length_class_id'		=> $product['length_class_id'],
						'width'					=> $product['width'],
						'height'				=> $product['height'],
						'notax'					=> $product['notax'],
						'custom_product'		=> (isset($product['custom_product']) ? $product['custom_product'] : 0)
					);
				}
				$order_data['vouchers'] = array();
				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);
				$this->load->model('setting/extension');
				$sort_order = array();
				$results = $this->model_setting_extension->getExtensions('total');
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}
				array_multisort($sort_order, SORT_ASC, $results);
				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}
				$sort_order = array();
				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
				array_multisort($sort_order, SORT_ASC, $totals);
				$order_data['totals'] = $totals;
				if (isset($this->request->post['comment'])) {
					$order_data['comment'] = $this->request->post['comment'];
				} else {
					$order_data['comment'] = '';
				}
				$order_data['total'] = $total;
				if (isset($this->request->cookie['tracking'])) {
					$order_data['tracking'] = $this->request->cookie['tracking'];
					$subtotal = $this->cart->getSubTotal();
					$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);
					if ($affiliate_info) {
						$order_data['affiliate_id'] = $affiliate_info['customer_id'];
						$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
					}
					$this->load->model('checkout/marketing');
					$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);
					if ($marketing_info) {
						$order_data['marketing_id'] = $marketing_info['marketing_id'];
					} else {
						$order_data['marketing_id'] = 0;
					}
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';
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
				$order_data['ref_no'] = $this->request->post['ref_no'];
				$order_data['no_tax'] = (isset($this->request->post['no_tax']) ? 1 : 0);
				$order_data['order_status_id'] = $this->request->post['order_status_id'];
				$order_data['soft_quote'] = (isset($this->request->post['soft_quote']) ? 1 : 0);
				$this->load->model('extension/checkout/quote_system');
				if (isset($this->request->post['save_customer']) && $this->request->post['save_customer']) {
					$this->load->model('account/customer');
					$length = 8;
					$password = "";
					$pw_chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789!";
					$maxlength = strlen($pw_chars);
					if ($length > $maxlength) {
						$length = $maxlength;
					}
					$i = 0;
					while ($i < $length) {
						$char = substr($pw_chars, mt_rand(0, $maxlength-1), 1);
						if (!strstr($password, $char)) { 
							$password .= $char;
							$i++;
						}
					}
					if (isset($this->session->data['shipping_address']['company'])) {
						$company = $this->session->data['shipping_address']['company'];
						$address_1 = $this->session->data['shipping_address']['address_1'];
						$address_2 = $this->session->data['shipping_address']['address_2'];
						$city = $this->session->data['shipping_address']['city'];
						$postcode = $this->session->data['shipping_address']['postcode'];
						$country_id = $this->session->data['shipping_address']['country_id'];
						$zone_id = $this->session->data['shipping_address']['zone_id'];
					} elseif (isset($this->session->data['payment_address']['company'])) {
						$company = $this->session->data['payment_address']['company'];
						$address_1 = $this->session->data['payment_address']['address_1'];
						$address_2 = $this->session->data['payment_address']['address_2'];
						$city = $this->session->data['payment_address']['city'];
						$postcode = $this->session->data['payment_address']['postcode'];
						$country_id = $this->session->data['payment_address']['country_id'];
						$zone_id = $this->session->data['payment_address']['zone_id'];
					} else {
						$company = '';
						$address_1 = '';
						$address_2 = '';
						$city = '';
						$postcode = '';
						$country_id = 0;
						$zone_id = 0;
					}
					$customer_data = array(
						'customer_group_id'		=> $this->session->data['customer']['customer_group_id'],
						'firstname'				=> $this->session->data['customer']['firstname'],
						'lastname'				=> $this->session->data['customer']['lastname'],
						'email'					=> $this->session->data['customer']['email'],
						'telephone'				=> $this->session->data['customer']['telephone'],
						'password'				=> $password,
						'company'				=> $company,
						'address_1'				=> $address_1,
						'address_2'				=> $address_2,
						'city'					=> $city,
						'postcode'				=> $postcode,
						'country_id'			=> $country_id,
						'zone_id'				=> $zone_id,
						'order_entry'			=> 1,
						'store_id'				=> (isset($this->request->post['store']) ? $this->request->post['store'] : (isset($this->request->post['store_id']) ? $this->request->post['store_id'] : 0)),
						'notify_customer'		=> (isset($this->request->post['notify_customer']) ? $this->request->post['notify_customer'] : 0)
					);
					$order_data['customer_id'] = $this->model_account_customer->addCustomer($customer_data);
					$this->session->data['customer']['customer_id'] = $order_data['customer_id'];
				}
				if (isset($this->request->post['notify_customer_order']) && $this->request->post['notify_customer_order']) {
					$order_data['notify'] = 1;
				} else {
					$order_data['notify'] = 0;
				}
				$json['quote_id'] = $this->model_extension_checkout_quote_system->addQuote($order_data);
				if (isset($this->request->post['order_status_id'])) {
					$order_status_id = $this->request->post['order_status_id'];
				} else {
					$order_status_id = $this->config->get('config_order_status_id');
				}
				$this->cart->clear();
				$json['success'] = $this->language->get('text_success');
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
	
	public function edit() {
		$this->load->language('api/order');
		$json = array();
		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/checkout/quote_system');
			if (isset($this->request->get['quote_id'])) {
				$quote_id = $this->request->get['quote_id'];
			} else {
				$quote_id = 0;
			}
			$order_info = $this->model_extension_checkout_quote_system->getQuote($quote_id);
			if ($order_info) {
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');
				}
				$products = $this->cart->getProducts();
				foreach ($products as $product) {
					$product_total = 0;
					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}
					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
						break;
					}
				}
				if (!$json) {
					$order_data = array();
					$order_data['store_id'] = $this->config->get('config_store_id');
					$order_data['store_name'] = $this->config->get('config_name');
					$order_data['store_url'] = $this->config->get('config_url');
					if (isset($this->request->post['store']) && $this->request->post['store']) {
						$this->load->model('setting/store');
						$store_info = $this->model_setting_store->getStore($this->request->post['store']);
						if ($store_info) {
							$order_data['store_id'] = $this->request->post['store'];
							$order_data['store_name'] = $store_info['name'];
							$order_data['store_url'] = $store_info['url'];
						}
					}
					if ($this->session->data['customer']['customer_id'] > 0) {
						$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
						$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
						$order_data['quote_account_id'] = 0;
					} else {
						$quote_account = $this->model_extension_checkout_quote_system->checkEmail($this->session->data['customer']['email']);
						if ($quote_account) {
							$account_data = $this->model_extension_checkout_quote_system->getQuoteAccount($this->session->data['customer']['email']);
							$order_data['customer_id'] = 0;
							$order_data['customer_group_id'] = $account_data['customer_group_id'];
							$order_data['quote_account_id'] = $account_data['quote_account_id'];
						} else {
							$account_info = array(
								'firstname'			=> $this->session->data['customer']['firstname'],
								'lastname'			=> $this->session->data['customer']['lastname'],
								'email'				=> $this->session->data['customer']['email'],
								'telephone'			=> $this->session->data['customer']['telephone'],
								'customer_group_id'	=> ($this->session->data['customer']['customer_group_id'] > 0) ? $this->session->data['customer']['customer_group_id'] : $this->config->get('config_customer_group_id'),
								'password'			=> ''
							);
							$new_account = $this->model_extension_checkout_quote_system->createQuoteAccount($account_info);
							$account_data = $this->model_extension_checkout_quote_system->getQuoteAccount($this->session->data['customer']['email']);
							$order_data['customer_id'] = 0;
							$order_data['customer_group_id'] = $account_data['customer_group_id'];
							$order_data['quote_account_id'] = $account_data['quote_account_id'];
						}
					}
					$order_data['firstname'] = $this->session->data['customer']['firstname'];
					$order_data['lastname'] = $this->session->data['customer']['lastname'];
					$order_data['email'] = $this->session->data['customer']['email'];
					$order_data['telephone'] = $this->session->data['customer']['telephone'];
					$order_data['custom_field'] = $this->session->data['customer']['custom_field'];
					if (isset($this->session->data['payment_address'])) {
						$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
						$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
						$order_data['payment_company'] = $this->session->data['payment_address']['company'];
						$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
						$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
						$order_data['payment_city'] = $this->session->data['payment_address']['city'];
						$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
						$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
						$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
						$order_data['payment_country'] = $this->session->data['payment_address']['country'];
						$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
						$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
						$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());
					} elseif (isset($this->session->data['shipping_address'])) {
						$order_data['payment_firstname'] = $this->session->data['shipping_address']['firstname'];
						$order_data['payment_lastname'] = $this->session->data['shipping_address']['lastname'];
						$order_data['payment_company'] = $this->session->data['shipping_address']['company'];
						$order_data['payment_address_1'] = $this->session->data['shipping_address']['address_1'];
						$order_data['payment_address_2'] = $this->session->data['shipping_address']['address_2'];
						$order_data['payment_city'] = $this->session->data['shipping_address']['city'];
						$order_data['payment_postcode'] = $this->session->data['shipping_address']['postcode'];
						$order_data['payment_zone'] = $this->session->data['shipping_address']['zone'];
						$order_data['payment_zone_id'] = $this->session->data['shipping_address']['zone_id'];
						$order_data['payment_country'] = $this->session->data['shipping_address']['country'];
						$order_data['payment_country_id'] = $this->session->data['shipping_address']['country_id'];
						$order_data['payment_address_format'] = $this->session->data['shipping_address']['address_format'];
						$order_data['payment_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());
					} else {
						$order_data['payment_firstname'] = '';
						$order_data['payment_lastname'] = '';
						$order_data['payment_company'] = '';
						$order_data['payment_address_1'] = '';
						$order_data['payment_address_2'] = '';
						$order_data['payment_city'] = '';
						$order_data['payment_postcode'] = '';
						$order_data['payment_zone'] = '';
						$order_data['payment_zone_id'] = '';
						$order_data['payment_country'] = '';
						$order_data['payment_country_id'] = '';
						$order_data['payment_address_format'] = '';
						$order_data['payment_custom_field'] = array();
					}
					if (isset($this->session->data['shipping_address'])) {
						$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
						$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
						$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
						$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
						$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
						$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
						$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
						$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
						$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
						$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
						$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
						$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
						$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());
						if (isset($this->session->data['shipping_method']['title'])) {
							$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
						} else {
							$order_data['shipping_method'] = '';
						}
						if (isset($this->session->data['shipping_method']['code'])) {
							$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
						} else {
							$order_data['shipping_code'] = '';
						}
					} else {
						$order_data['shipping_firstname'] = '';
						$order_data['shipping_lastname'] = '';
						$order_data['shipping_company'] = '';
						$order_data['shipping_address_1'] = '';
						$order_data['shipping_address_2'] = '';
						$order_data['shipping_city'] = '';
						$order_data['shipping_postcode'] = '';
						$order_data['shipping_zone'] = '';
						$order_data['shipping_zone_id'] = '';
						$order_data['shipping_country'] = '';
						$order_data['shipping_country_id'] = '';
						$order_data['shipping_address_format'] = '';
						$order_data['shipping_custom_field'] = array();
						$order_data['shipping_method'] = '';
						$order_data['shipping_code'] = '';
					}
					$order_data['products'] = array();
					foreach ($this->cart->getProducts() as $product) {
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
							'product_id'			=> $product['product_id'],
							'name'					=> $product['name'],
							'model'					=> $product['model'],
							'option'				=> $option_data,
							'download'				=> $product['download'],
							'quantity'				=> $product['quantity'],
							'subtract'				=> $product['subtract'],
							'price'					=> $product['price'],
							'total'					=> $product['total'],
							'tax'					=> $this->tax->getTax($product['price'], $product['tax_class_id']),
							'sort_order'			=> $product['sort_order'],
							'reward'				=> $product['reward'],
							'sku'					=> (isset($product['sku']) ? $product['sku'] : ''),
							'upc'					=> (isset($product['upc']) ? $product['upc'] : ''),
							'location'				=> (isset($product['location']) ? $product['location'] : ''),
							'shipping'				=> $product['shipping'],
							'image'					=> $product['image'],
							'tax_class_id'			=> $product['tax_class_id'],
							'weight'				=> $product['weight'] / $product['quantity'],
							'weight_class_id'		=> $product['weight_class_id'],
							'length'				=> $product['length'],
							'length_class_id'		=> $product['length_class_id'],
							'width'					=> $product['width'],
							'height'				=> $product['height'],
							'notax'					=> $product['notax'],
							'custom_product'		=> (isset($product['custom_product']) ? $product['custom_product'] : 0)
						);
					}
					$order_data['vouchers'] = array();
					$totals = array();
					$taxes = $this->cart->getTaxes();
					$total = 0;
					$total_data = array(
						'totals' => &$totals,
						'taxes'  => &$taxes,
						'total'  => &$total
					);
					$this->load->model('setting/extension');
					$sort_order = array();
					$results = $this->model_setting_extension->getExtensions('total');
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
					}
					array_multisort($sort_order, SORT_ASC, $results);
					foreach ($results as $result) {
						if ($this->config->get('total_' . $result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}
					$sort_order = array();
					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
					array_multisort($sort_order, SORT_ASC, $totals);
					$order_data['totals'] = $totals;
					if (isset($this->request->post['comment'])) {
						$order_data['comment'] = $this->request->post['comment'];
					} else {
						$order_data['comment'] = '';
					}
					$order_data['total'] = $total;
					if (isset($this->request->cookie['tracking'])) {
						$order_data['tracking'] = $this->request->cookie['tracking'];
						$subtotal = $this->cart->getSubTotal();
						$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);
						if ($affiliate_info) {
							$order_data['affiliate_id'] = $affiliate_info['customer_id'];
							$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
						} else {
							$order_data['affiliate_id'] = 0;
							$order_data['commission'] = 0;
						}
						$this->load->model('checkout/marketing');
						$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);
						if ($marketing_info) {
							$order_data['marketing_id'] = $marketing_info['marketing_id'];
						} else {
							$order_data['marketing_id'] = 0;
						}
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
						$order_data['marketing_id'] = 0;
						$order_data['tracking'] = '';
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
					$order_data['ref_no'] = $this->request->post['ref_no'];
					$order_data['no_tax'] = (isset($this->request->post['no_tax']) ? 1 : 0);
					$order_data['order_status_id'] = $this->request->post['order_status_id'];
					$order_data['soft_quote'] = (isset($this->request->post['soft_quote']) ? 1 : 0);
					if (isset($this->request->post['notify_customer_order']) && $this->request->post['notify_customer_order']) {
						$order_data['notify'] = 1;
					} else {
						$order_data['notify'] = 0;
					}
					$this->model_extension_checkout_quote_system->editQuote($quote_id, $order_data);
					if (isset($this->request->post['order_status_id'])) {
						$order_status_id = $this->request->post['order_status_id'];
					} else {
						$order_status_id = $this->config->get('config_order_status_id');
					}
					$this->cart->clear();
					$json['success'] = $this->language->get('text_success');
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
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

}

?>