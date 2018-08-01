<?php

class ControllerExtensionSaleOrderEntry extends Controller {
	private $error = array();

	public function add() {
		$this->load->language('sale/order');
		$this->load->language('extension/sale/order_entry');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');
		$this->load->model('extension/sale/order_entry');
		$user_group_id = $this->model_extension_sale_order_entry->getUserGroupId($this->user->getId());
		$oe_create_orders = $this->config->get('module_order_entry_create_orders');
		if (is_array($oe_create_orders) && in_array($user_group_id, $oe_create_orders)) {
			$this->getForm();
		} else {
			$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true));
		}
	}

	public function edit() {
		$this->load->language('sale/order');
		$this->load->language('extension/sale/order_entry');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');
		$this->load->model('extension/sale/order_entry');
		$user_group_id = $this->model_extension_sale_order_entry->getUserGroupId($this->user->getId());
		$oe_edit_orders = $this->config->get('module_order_entry_edit_orders');
		if (is_array($oe_edit_orders) && in_array($user_group_id, $oe_edit_orders)) {
			$this->getForm();
		} else {
			$url = '';
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_order_status'])) {
				$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
	}

	public function copy() {
		$this->load->language('sale/order');
		$this->load->language('extension/sale/order_entry');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');
		$this->load->model('extension/sale/order_entry');
		$user_group_id = $this->model_extension_sale_order_entry->getUserGroupId($this->user->getId());
		$oe_edit_orders = $this->config->get('module_order_entry_edit_orders');
		if (is_array($oe_edit_orders) && in_array($user_group_id, $oe_edit_orders)) {
			$this->session->data['copy_order'] = 1;
			$this->getForm();
		} else {
			$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true));
		}
	}

	public function getForm() {
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}
		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = null;
		}
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (file_exists(DIR_APPLICATION . 'controller/shipping/oe_custom.php')) {
			unlink(DIR_APPLICATION . 'controller/shipping/oe_custom.php');
		}
		if (file_exists(DIR_APPLICATION . 'language/english/shipping/oe_custom.php')) {
			unlink(DIR_APPLICATION . 'language/english/shipping/oe_custom.php');
		}
		if (file_exists(DIR_APPLICATION . 'view/template/shipping/oe_custom.tpl')) {
			unlink(DIR_APPLICATION . 'view/template/shipping/oe_custom.tpl');
		}
		if (file_exists(DIR_CATALOG . 'model/shipping/oe_custom.php')) {
			unlink(DIR_CATALOG . 'model/shipping/oe_custom.php');
		}
		$this->load->model('customer/customer');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$this->load->model('customer/customer');
		$data['user_token'] = $this->session->data['user_token'];
		$data['module_order_entry_save_new_customer'] = $this->config->get('module_order_entry_save_new_customer');
		$data['module_order_entry_order_notification'] = $this->config->get('module_order_entry_order_notification');
		$data['show_previous_orders'] = $this->config->get('module_order_entry_previous_orders');
		$data['module_order_entry_require_email'] = $this->config->get('module_order_entry_require_email');
		$data['module_order_entry_require_lastname'] = $this->config->get('module_order_entry_require_lastname');
		$data['module_order_entry_require_telephone'] = $this->config->get('module_order_entry_require_telephone');
		$data['module_order_entry_require_city'] = $this->config->get('module_order_entry_require_city');
		$data['module_order_entry_require_zone'] = $this->config->get('module_order_entry_require_zone');
		$data['module_order_entry_allow_zero_qty'] = $this->config->get('module_order_entry_allow_zero_qty');
		$data['product_column_option'] = 0;
		$data['product_column_price'] = 0;
		$data['product_column_pricet'] = 0;
		$data['product_column_total'] = 0;
		$data['product_column_totalt'] = 0;
		$data['product_column_notax'] = 0;
		$data['product_column_cost'] = 0;
		$data['product_column_image'] = 0;
		$prod_cols = $this->config->get('module_order_entry_product_columns');
		if (is_array($prod_cols)) {
			if (in_array('option', $prod_cols)) {
				$data['product_column_option'] = 1;
			}
			if (in_array('price', $prod_cols)) {
				$data['product_column_price'] = 1;
			}
			if (in_array('pricet', $prod_cols)) {
				$data['product_column_pricet'] = 1;
			}
			if (in_array('total', $prod_cols)) {
				$data['product_column_total'] = 1;
			}
			if (in_array('totalt', $prod_cols)) {
				$data['product_column_totalt'] = 1;
			}
			if (in_array('notax', $prod_cols)) {
				$data['product_column_notax'] = 1;
			}
			if (in_array('cost', $prod_cols)) {
				$data['product_column_cost'] = 1;
			}
			if (in_array('image', $prod_cols)) {
				$data['product_column_image'] = 1;
			}
		}
		$url = '';
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['order_quote'] = 'order';
		if ($this->config->get('config_secure')) {
			$data['store_url'] = HTTPS_CATALOG;
		} else {
			$data['store_url'] = HTTP_CATALOG;
		}
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		} elseif (isset($this->request->get['quote_id'])) {
			$this->load->model('extension/sale/quote_system');
			$order_info = $this->model_extension_sale_quote_system->getQuote($this->request->get['quote_id']);
			$data['order_quote'] = 'quote';
		} elseif (isset($this->request->get['quote'])) {
			$this->load->model('extension/sale/quote_system');
			$data['order_quote'] = 'quote';
		}
		if ($data['order_quote'] == 'quote') {
			$data['payment_methods'][] = array(
				'code'		=> 'catalog_customer',
				'title'		=> $this->language->get('text_catalog_customer')
			);
			$data['payment_methods'][] = array(
				'code'		=> 'pp_standard',
				'title'		=> $this->language->get('text_paypal_link')
			);
		}
		if (!empty($order_info) || !empty($quote_info)) {
			if ($data['order_quote'] == 'quote') {
				$data['quote_id'] = (isset($this->request->get['quote_id']) ? $this->request->get['quote_id'] : 0);
				$data['order_id'] = 0;
				$data['order_balance'] = 0;
				$data['soft_quote'] = (isset($order_info['soft_quote']) ? $order_info['soft_quote'] : 0);
			} else {
				$data['quote_id'] = 0;
				if (isset($this->session->data['copy_order'])) {
					$data['new_order'] = $this->language->get('text_copy_order');
					$data['order_id'] = 0;
					$data['copy_order'] = 1;
					unset($this->session->data['copy_order']);
				} else {
					$data['order_id'] = $this->request->get['order_id'];
				}
				$data['order_balance'] = $order_info['total'];
				$data['soft_quote'] = 0;
			}
			$data['store_id'] = $order_info['store_id'];
			$data['store_name'] = $order_info['store_name'];
			$this->load->model('localisation/currency');
			$currency_info = $this->model_localisation_currency->getCurrency($order_info['currency_id']);
			if ($currency_info['symbol_left']) {
				$data['currency_info'] = '( ' . $currency_info['symbol_left'] . ' ) ' . $currency_info['title'];
			} else {
				$data['currency_info'] = '( ' . $currency_info['symbol_right'] . ' ) ' . $currency_info['title'];
			}
			$data['customer'] = (isset($order_info['customer']) ? $order_info['customer'] : '');
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_group_id'] = $order_info['customer_group_id'];
			$this->load->model('customer/customer_group');
			$customer_group = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);
			if (isset($customer_group['name'])) {
				$data['customer_group'] = $customer_group['name'];
			} else {
				$data['customer_group'] = '';
			}
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['account_custom_field'] = (isset($order_info['custom_field']) ? $order_info['custom_field'] : array());
			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_country'] = $order_info['payment_country'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_zone'] = $order_info['payment_zone'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
			if ($data['order_quote'] == 'order') {
				$data['payment_method'] = $order_info['payment_method'];
				if ($order_info['payment_code']) {
					$data['payment_code'] = $order_info['payment_code'];
				} else {
					$data['payment_code'] = 'oe_custom_payment';
				}
			} else {
				$data['payment_method'] = '';
				$data['payment_code'] = '';
			}
			if ($order_info['shipping_code'] || ($order_info['shipping_address_1'] && $order_info['shipping_city'])) {
				$data['shipping_firstname'] = $order_info['shipping_firstname'];
				$data['shipping_lastname'] = $order_info['shipping_lastname'];
				$data['shipping_company'] = $order_info['shipping_company'];
				$data['shipping_address_1'] = $order_info['shipping_address_1'];
				$data['shipping_address_2'] = $order_info['shipping_address_2'];
				$data['shipping_city'] = $order_info['shipping_city'];
				$data['shipping_postcode'] = $order_info['shipping_postcode'];
				$data['shipping_country_id'] = $order_info['shipping_country_id'];
				$data['shipping_country'] = $order_info['shipping_country'];
				$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
				$data['shipping_zone'] = $order_info['shipping_zone'];
				$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
				$data['shipping_method'] = $order_info['shipping_method'];
				$data['shipping_code'] = $order_info['shipping_code'];
				if ($data['order_quote'] == 'order') {
					$data['shipping_required'] = 1;
				} else {
					$data['shipping_required'] = 0;
				}
			} else {
				$data['shipping_firstname'] = $order_info['payment_firstname'];
				$data['shipping_lastname'] = $order_info['payment_lastname'];
				$data['shipping_company'] = $order_info['payment_company'];
				$data['shipping_address_1'] = $order_info['payment_address_1'];
				$data['shipping_address_2'] = $order_info['payment_address_2'];
				$data['shipping_city'] = $order_info['payment_city'];
				$data['shipping_postcode'] = $order_info['payment_postcode'];
				$data['shipping_country_id'] = $order_info['payment_country_id'];
				$data['shipping_country'] = $order_info['payment_country'];
				$data['shipping_zone_id'] = $order_info['payment_zone_id'];
				$data['shipping_zone'] = $order_info['payment_zone'];
				$data['shipping_custom_field'] = $order_info['payment_custom_field'];
				$data['shipping_method'] = '';
				$data['shipping_code'] = 'oe_custom_shipping.oe_custom_shipping';
				$data['shipping_required'] = 0;
			}
			if ($order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_postcode'] == $order_info['shipping_postcode']) {
				$data['shipping_address_same'] = 1;
			} else {
				$data['shipping_address_same'] = 0;
			}
			if ($data['shipping_code'] == 'oe_custom_shipping.oe_custom_shipping') {
				$data['custom_shipping_title'] = $data['shipping_method'];
			} else {
				$data['custom_shipping_title'] = '';
			}
			$data['order_products'] = array();
			if ($data['order_quote'] == 'order') {
				$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
			} else {
				$products = $this->model_extension_sale_quote_system->getQuoteProducts($this->request->get['quote_id']);
			}
			$this->load->model('catalog/product');
			foreach ($products as $product) {
				$notax = 0;
				$custom_product = 0;
				$sort_order = 0;
				if ($data['order_quote'] == 'order') {
					$oe_product_info = $this->model_sale_order->getOeOrderProducts($this->request->get['order_id'], $product['order_product_id']);
					if ($oe_product_info) {
						$custom_product = $oe_product_info['custom_product'];
						$notax = $oe_product_info['notax'];
						$shipping = $oe_product_info['shipping'];
						$sort_order = $oe_product_info['sort_order'];
					}
					$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
				} else {
					$custom_product = $product['custom_product'];
					$notax = $product['notax'];
					$shipping = $product['shipping'];
					$sort_order = $product['sort_order'];
					$options = $this->model_extension_sale_quote_system->getQuoteOptions($this->request->get['quote_id'], $product['quote_product_id']);
				}
				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
				if (isset($product['image']) && $product['image']) {
					$image = $product['image'];
					$thumb = $this->model_tool_image->resize($product['image'], 100, 100);
				} elseif (isset($oe_product_info['image']) && $oe_product_info['image']) {
					$image = $oe_product_info['image'];
					$thumb = $this->model_tool_image->resize($oe_product_info['image'], 100, 100);
				} elseif (!empty($product_info) && $product_info['image']) {
					$image = $product_info['image'];
					$thumb = $this->model_tool_image->resize($product_info['image'], 100, 100);
				} else {
					$image = '';
					$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
				}
				$cost = $this->model_extension_sale_order_entry->getOeCost($product['product_id']);
				$data['order_products'][] = array(
					'product_id'		=> $product['product_id'],
					'name'				=> $product['name'],
					'model'				=> $product['model'],
					'option'			=> $options,
					'quantity'			=> $product['quantity'],
					'price'				=> $product['price'],
					'total'				=> $product['total'],
					'notax'				=> $notax,
					'cost'				=> $cost,
					'shipping'			=> $shipping,
					'custom_product'	=> $custom_product,
					'image'				=> $image,
					'thumb'				=> $thumb,
					'sort_order'		=> $sort_order,
					'reward'			=> (isset($product['reward']) ? $product['reward'] : 0)
				);
			}
			if ($data['order_quote'] == 'order') {
				$data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);
				$data['ref_no'] = $this->model_sale_order->getRefNo($order_info['order_id']);
				$data['no_tax'] = $this->model_sale_order->getNoTax($order_info['order_id']);
			} else {
				$data['order_vouchers'] = array();
				$data['ref_no'] = $this->model_sale_order->getRefNoQuote($order_info['quote_id']);
				$data['no_tax'] = $this->model_sale_order->getNoTaxQuote($order_info['quote_id']);
			}
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
			$data['store_credit'] = '';
			$data['custom_shipping_cost'] = '';
			$data['order_totals'] = array();
			if ($data['order_quote'] == 'order') {
				$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				foreach ($order_totals as $order_total) {
					$start = strpos($order_total['title'], '(') + 1;
					$end = strrpos($order_total['title'], ')');
					if ($start && $end) {
						$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
					}
					if ($order_total['code'] == 'credit') {
						$data['store_credit'] = -$order_total['value'];
					}
					if ($order_total['code'] == 'shipping') {
						if ($data['shipping_code'] == 'oe_custom_shipping.oe_custom_shipping') {
							$data['custom_shipping_cost'] = $order_total['value'];
						}
					}
				}
				$points_avail = $this->model_extension_sale_order_entry->getRewardPoints($order_info['customer_id']);
				if ($data['reward']) {
					$points_avail += $data['reward'];
				}
				$data['points_avail'] = sprintf($this->language->get('text_points_avail'), $points_avail);
				$data['credit_avail'] = $this->model_extension_sale_order_entry->getStoreCredit($order_info['customer_id']);
				if ($data['store_credit']) {
					$data['credit_avail'] += $data['store_credit'];
				}
			} elseif ($data['order_quote'] == 'quote') {
				$order_totals = $this->model_extension_sale_order_entry->getQuoteTotals($this->request->get['quote_id']);
				foreach ($order_totals as $order_total) {
					$start = strpos($order_total['title'], '(') + 1;
					$end = strrpos($order_total['title'], ')');
					if ($start && $end) {
						$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
					}
					if ($order_total['code'] == 'credit') {
						$data['store_credit'] = -$order_total['value'];
					}
					if ($order_total['code'] == 'shipping') {
						if ($data['shipping_code'] == 'oe_custom_shipping.oe_custom_shipping') {
							$data['custom_shipping_cost'] = $order_total['value'];
						}
					}
				}
				$data['points_avail'] = $this->model_extension_sale_order_entry->getRewardPoints($order_info['customer_id']);
				if ($data['reward']) {
					$data['points_avail'] += $data['reward'];
				}
			}
			$data['order_status_id'] = $order_info['order_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = (isset($order_info['affiliate_id']) ? $order_info['affiliate_id'] : 0);
			$data['affiliate'] = (isset($order_info['affiliate_firstname']) ? $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'] : '');
			$data['currency_code'] = $order_info['currency_code'];
			$data['language_id'] = $order_info['language_id'];
		} else {
			$data['order_id'] = 0;
			$data['quote_id'] = 0;
			$data['soft_quote'] = 0;
			$data['new_order'] = $this->language->get('text_new_order');
			$data['new_quote'] = $this->language->get('text_new_quote');
			$data['order_balance'] = 0;
			$data['store_id'] = 0;
			$data['store_name'] = '';
			$data['currency_info'] = '';
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('module_order_entry_customer_group');
			$data['customer_group'] = '';
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['account_custom_field'] = array();
			$data['addresses'] = array();
			$data['shipping_address_same'] = 1;
			$data['shipping_required'] = 0;
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = $this->config->get('config_country_id');
			$data['payment_country'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_zone'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = $this->config->get('config_country_id');
			$data['shipping_country'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';
			$data['custom_shipping_title'] = '';
			$data['custom_shipping_cost'] = '';
			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();
			if ($data['order_quote'] == 'order') {
				$data['order_status_id'] = $this->config->get('module_order_entry_status');
			} else {
				$data['order_status_id'] = $this->config->get('module_quote_system_request_status_id');
			}
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');
			$data['language_id'] = $this->config->get('config_language_id');
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
			$data['store_credit'] = '';
			$data['ref_no'] = '';
			$data['no_tax'] = 0;
			$data['oe_customer_group'] = $this->config->get('oe_customer_group');
			$data['points_avail'] = sprintf($this->language->get('text_points_avail'), 0);
			$data['credit_avail'] = 0;
		}
		// Stores
		$this->load->model('setting/store');
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			'href'     => $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG
		);
		$results = $this->model_setting_store->getStores();
		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'href'     => $this->request->server['HTTPS'] ? str_replace("http", "https", $result['url']) : $result['url']
			);
		}
		// Languages
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		// Customer Groups
		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		// Custom Fields
		$this->load->model('customer/custom_field');
		$data['custom_fields'] = array();
		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);
		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}
		$this->load->model('localisation/order_status');
		if ($data['order_quote'] == 'order') {
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		} else {
			$data['order_statuses'] = $this->model_extension_sale_quote_system->getQuoteStatuses();
		}
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['voucher_min'] = $this->config->get('config_voucher_min');
		$this->load->model('sale/voucher_theme');
		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		// API login
		$this->load->model('user/api');
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
		if ($api_info && $this->user->hasPermission('modify', 'extension/sale/order_entry')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);
			$session->start();
			$this->model_user_api->deleteApiSessionBySessonId($session->getId());
			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
			$session->data['api_id'] = $api_info['api_id'];
			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;
		$data['sort'] = $sort;
		$data['sort_order'] = $order;
		$data['page'] = $page;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/sale/order_entry_one_form', $data));
	}

	public function checkEmail() {
		$this->load->model('extension/sale/order_entry');
		$json = "new";
		if ($this->request->get['email']) {
			$result = $this->model_extension_sale_order_entry->checkEmail($this->request->get['email']);
			if ($result) {
				$json = "exists";
			}
		} else {
			$json = "noaccount";
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getCustomerOrders() {
		$json = array();
		$this->load->language('extension/sale/order_entry');
		$this->load->model('extension/sale/order_entry');
		$this->load->model('sale/order');
		$filter_data = array(
			'customer_id'	=> $this->request->get['customer_id'],
			'sort'			=> 'date_added',
			'order'			=> 'DESC',
			'start'			=> 0,
			'limit'			=> ($this->config->get('module_order_entry_previous_count') ? $this->config->get('module_order_entry_previous_count') : 10)
		);
		$orders = $this->model_extension_sale_order_entry->getCustomerOrders($filter_data);
		$json['orders'] = '';
		if ($orders) {
			$json['has_orders'] = 1;
			foreach ($orders as $order) {
				$order_products = $this->model_sale_order->getOrderProducts($order['order_id']);
				$a = 0;
				$json['orders'] .= '<tr>';
				$json['orders'] .= '	<td class="text-left">' . $order['order_id'] . '</td>';
				$json['orders'] .= '	<td class="text-left">' . date($this->language->get('date_format_short'), strtotime($order['date_added'])) . '</td>';
				$json['orders'] .= '	<td class="text-left">';
				foreach ($order_products as $order_product) {
					if (!$a) {
						$json['orders'] .= $order_product['name'] . ' &nbsp;<b>x' . $order_product['quantity'] . '</b>';
					} else {
						$json['orders'] .= ', ' . $order_product['name'] . ' &nbsp;<b>x' . $order_product['quantity'] . '</b>';
					}
				}
				$json['orders'] .= '	</td>';
				$json['orders']	.= '	<td class="text-right">' . $this->currency->format($order['total'], $order['currency_code'], $order['currency_value']) . '</td>';
				$json['orders'] .= '	<td class="text-left">' . $order['payment_method'] . '</td>';
				$json['orders'] .= '	<td class="text-left">' . $order['shipping_method'] . '</td>';
				$json['orders'] .= '	<td class="text-left">' . $order['status'] . '</td>';
				$json['orders'] .= '</tr>';
			}
		} else {
			$json['has_orders'] = 0;
			$json['orders'] .= '<tr>';
			$json['orders'] .= '	<td class="text-center" colspan="7">' . $this->language->get('text_no_previous_orders') . '</td>';
			$json['orders'] .= '</tr>';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();
		$this->load->model('extension/sale/order_entry');
		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$limit = 10;
		$filter_data = array(
			'name'		=> $this->request->get['filter_name'],
			'store'		=> $this->config->get('oe_selected_store'),
			'store_id'	=> $this->request->get['store_id'],
			'group_id'	=> $this->request->get['customer_group_id'],
			'quantity'	=> $this->config->get('oe_allow_zero_qty'),
			'disabled'	=> $this->config->get('oe_allow_disabled'),
			'start'		=> 0,
			'limit'		=> 10
		);
		$results = $this->model_extension_sale_order_entry->getProducts($filter_data);
		foreach ($results as $result) {
			$option_data = array();
			$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);
			foreach ($product_options as $product_option) {
				$option_info = $this->model_catalog_option->getOption($product_option['option_id']);
				if ($option_info) {
					$product_option_value_data = array();
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
						$total_price = false;
						if ($option_value_info) {
							if ($result['special']) {
								if ($product_option_value['price_prefix'] == '+') {
									/*$total_price = $this->currency->format($this->tax->calculate($product_option_value['price'] + $result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($product_option_value['price'] + $result['special'], $this->config->get('config_currency'));
								} elseif ($product_option_value['price_prefix'] == '-') {
									/*$total_price = $this->currency->format($this->tax->calculate($result['special'] - $product_option_value['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($result['special'] - $product_option_value['price'], $this->config->get('config_currency'));
								} else {
									/*$total_price = $this->currency->format($this->tax->calculate($product_option_value['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($product_option_value['price'], $this->config->get('config_currency'));
								}
							} else {
								if ($product_option_value['price_prefix'] == '+') {
									/*$total_price = $this->currency->format($this->tax->calculate($product_option_value['price'] + $result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($product_option_value['price'] + $result['price'], $this->config->get('config_currency'));
								} elseif ($product_option_value['price_prefix'] == '-') {
									/*$total_price = $this->currency->format($this->tax->calculate($result['price'] - $product_option_value['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($result['price'] - $product_option_value['price'], $this->config->get('config_currency'));
								} else {
									/*$total_price = $this->currency->format($this->tax->calculate($product_option_value['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));*/
									$total_price = $this->currency->format($product_option_value['price'], $this->config->get('config_currency'));
								}
							}
							$product_option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $option_value_info['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'total_price'			  => $total_price,
								'price_prefix'            => $product_option_value['price_prefix']
							);
						}
					}
					$option_data[] = array(
						'product_option_id'    => $product_option['product_option_id'],
						'product_option_value' => $product_option_value_data,
						'option_id'            => $product_option['option_id'],
						'name'                 => $option_info['name'],
						'type'                 => $option_info['type'],
						'value'                => (isset($product_option['value']) ? $product_option['value'] : ''),
						'required'             => $product_option['required']
					);
				}
			}
			$price = $result['price'];
			if ($result['discount']) {
				$price = $result['discount'];
			}
			if ($result['special']) {
				$price = $result['special'];
			}
			$json[] = array(
				'product_id'	=> $result['product_id'],
				'name'			=> '[ ' . $result['product_id'] . ' ] ' . strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				'model'			=> $result['model'],
				'sku'			=> $result['sku'],
				'option'		=> $option_data,
				'price'			=> $price
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customerautocomplete() {
		$this->load->language('extension/sale/order_entry');
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/sale/order_entry');
			$filter_name = $this->request->get['filter_name'];
			$this->load->model('customer/customer');
			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);
			$results = $this->model_extension_sale_order_entry->getOeCustomers($filter_data);
			foreach ($results as $result) {
				if (!empty($result['c_custom_field'])) {
					$custom_field = json_decode($result['c_custom_field'], true);
				} else {
					$custom_field = array();
				}
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'custom_field'      => $custom_field,
					'address'           => $this->model_customer_customer->getAddresses($result['customer_id']),
					'reward_points'		=> sprintf($this->language->get('text_points_avail'), $this->model_extension_sale_order_entry->getRewardPoints($result['customer_id'])),
					'store_credit'		=> $this->model_extension_sale_order_entry->getStoreCredit($result['customer_id'])
				);
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clearOrderEntry() {
		unset($this->session->data['oe']);
		$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/sale/order_entry')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

}