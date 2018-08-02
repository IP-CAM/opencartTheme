<?php

class ControllerExtensionModuleQuoteSystem extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/quote_system');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_quote_system', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/quote_system', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['update_tables'] = $this->url->link('extension/module/quote_system/updateTables', 'user_token=' . $this->session->data['user_token'], true);
		$data['action'] = $this->url->link('extension/module/quote_system', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		if (isset($this->request->post['module_quote_system_catalog_status'])) {
			$data['module_quote_system_catalog_status'] = $this->request->post['module_quote_system_catalog_status'];
		} else {
			$data['module_quote_system_catalog_status'] = $this->config->get('module_quote_system_catalog_status');
		}
		if (isset($this->request->post['module_quote_system_request_status_id'])) {
			$data['module_quote_system_request_status_id'] = $this->request->post['module_quote_system_request_status_id'];
		} else {
			$data['module_quote_system_request_status_id'] = $this->config->get('module_quote_system_request_status_id');
			if (!$data['module_quote_system_request_status_id']) {
				$data['module_quote_system_request_status_id'] = $this->config->get('config_order_status_id');
			}
		}
		if (isset($this->request->post['module_quote_system_ready_status_id'])) {
			$data['module_quote_system_ready_status_id'] = $this->request->post['module_quote_system_ready_status_id'];
		} else {
			$data['module_quote_system_ready_status_id'] = $this->config->get('module_quote_system_ready_status_id');
		}
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		if (isset($this->request->post['module_quote_system_subtract_stock'])) {
			$data['module_quote_system_subtract_stock'] = $this->request->post['module_quote_system_subtract_stock'];
		} else {
			$data['module_quote_system_subtract_stock'] = $this->config->get('module_quote_system_subtract_stock');
		}
		if (isset($this->request->post['module_quote_system_guest'])) {
			$data['module_quote_system_guest'] = $this->request->post['module_quote_system_guest'];
		} else {
			$data['module_quote_system_guest'] = $this->config->get('module_quote_system_guest');
		}
		if (isset($this->request->post['module_quote_system_quote_expire'])) {
			$data['module_quote_system_quote_expire'] = $this->request->post['module_quote_system_quote_expire'];
		} else {
			$data['module_quote_system_quote_expire'] = $this->config->get('module_quote_system_quote_expire');
		}
		if (isset($this->request->post['module_quote_system_status'])) {
			$data['module_quote_system_status'] = $this->request->post['module_quote_system_status'];
		} else {
			$data['module_quote_system_status'] = $this->config->get('module_quote_system_status');
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/quote_system', $data));
	}

	public function deleteQuote() {
		$this->load->language('extension/module/quote_system');
		$this->load->model('extension/sale/quote_system');
		$this->model_extension_sale_quote_system->delete($this->request->get['quote_id']);
		$this->session->data['success'] = $this->language->get('text_delete_success');
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
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
		if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}
		if (isset($this->request->get['page3'])) {
			$url .= '&page3=' . $this->request->get['page3'];
		}
		$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}

	public function printQuote() {
		$this->load->language('sale/order');
		$this->load->language('extension/module/quote_system_language');
		$data['title'] = $this->language->get('text_quote');
		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		$this->load->model('extension/sale/quote_system');
		$this->load->model('setting/setting');
		$data['text_invoice'] = $this->language->get('text_quote');
		$data['orders'] = array();
		$quote_info = $this->model_extension_sale_quote_system->getQuote($this->request->get['quote_id']);
		if ($quote_info) {
			$store_info = $this->model_setting_setting->getSetting('config', $quote_info['store_id']);
			if ($store_info) {
				$store_address = $store_info['config_address'];
				$store_email = $store_info['config_email'];
				$store_telephone = $store_info['config_telephone'];
			} else {
				$store_address = $this->config->get('config_address');
				$store_email = $this->config->get('config_email');
				$store_telephone = $this->config->get('config_telephone');
			}
			$invoice_no = '';
			if ($quote_info['payment_address_format']) {
				$format = $quote_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
			$replace = array(
				'firstname' => $quote_info['payment_firstname'],
				'lastname'  => $quote_info['payment_lastname'],
				'company'   => $quote_info['payment_company'],
				'address_1' => $quote_info['payment_address_1'],
				'address_2' => $quote_info['payment_address_2'],
				'city'      => $quote_info['payment_city'],
				'postcode'  => $quote_info['payment_postcode'],
				'zone'      => $quote_info['payment_zone'],
				'zone_code' => '',
				'country'   => $quote_info['payment_country']
			);
			$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			if ($quote_info['shipping_address_format']) {
				$format = $quote_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
			$replace = array(
				'firstname' => $quote_info['shipping_firstname'],
				'lastname'  => $quote_info['shipping_lastname'],
				'company'   => $quote_info['shipping_company'],
				'address_1' => $quote_info['shipping_address_1'],
				'address_2' => $quote_info['shipping_address_2'],
				'city'      => $quote_info['shipping_city'],
				'postcode'  => $quote_info['shipping_postcode'],
				'zone'      => $quote_info['shipping_zone'],
				'zone_code' => '',
				'country'   => $quote_info['shipping_country']
			);
			$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			$this->load->model('tool/upload');
			$product_data = array();
			$products = $this->model_extension_sale_quote_system->getQuoteProducts($this->request->get['quote_id']);
			foreach ($products as $product) {
				$option_data = array();
				$options = $this->model_extension_sale_quote_system->getQuoteOptions($this->request->get['quote_id'], $product['quote_product_id']);
				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
					);
				}
				$product_data[] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quote_info['currency_code'], $quote_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value'])
				);
			}
			$voucher_data = array();
			$total_data = array();
			$totals = $this->model_extension_sale_quote_system->getQuoteTotals($this->request->get['quote_id']);
			foreach ($totals as $total) {
				$total_data[] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $quote_info['currency_code'], $quote_info['currency_value']),
				);
			}
			$data['orders'][] = array(
				'order_id'	         => $this->request->get['quote_id'],
				'invoice_no'         => $invoice_no,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($quote_info['date_added'])),
				'store_name'         => $quote_info['store_name'],
				'store_url'          => rtrim($quote_info['store_url'], '/'),
				'store_address'      => nl2br($store_address),
				'store_email'        => $store_email,
				'store_telephone'    => $store_telephone,
				'email'              => $quote_info['email'],
				'telephone'          => $quote_info['telephone'],
				'shipping_address'   => $shipping_address,
				'shipping_method'    => $quote_info['shipping_method'],
				'payment_address'    => $payment_address,
				'payment_method'     => $quote_info['payment_method'],
				'product'            => $product_data,
				'voucher'            => $voucher_data,
				'total'              => $total_data,
				'comment'            => nl2br($quote_info['comment'])
			);
		}
		$this->response->setOutput($this->load->view('sale/order_invoice', $data));
	}

	public function resendEmail() {
		$this->load->language('extension/module/quote_system_language');
		$this->load->model('extension/sale/quote_system');
		$this->model_extension_sale_quote_system->sendCustomerEmail($this->request->get['quote_id'], 1, '');
		$json['success'] = $this->language->get('text_resend_success');
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function convertQuote() {
		$this->load->language('extension/module/quote_system_language');
		$this->load->model('extension/sale/quote_system');
		$converted = $this->model_extension_sale_quote_system->convertQuote($this->request->get['quote_id']);
		if ($converted) {
			$json['success'] = 1;
			$this->session->data['success'] = $this->language->get('text_convert_success');
		} else {
			$json['error'] = $this->language->get('error_converting');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/quote_system')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function updateTables() {
		$this->load->language('extension/module/quote_system');
		$this->load->model('extension/sale/quote_system');
		$this->model_extension_sale_quote_system->updateTables();
		$this->session->data['success'] = $this->language->get('text_update_tables_success');
		$this->response->redirect($this->url->link('extension/module/quote_system', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function install() {
		$this->load->model('extension/sale/quote_system');
		if ($this->config->get('module_order_entry_status')) {
			$this->model_extension_sale_quote_system->install();
		} else {
			$this->model_extension_sale_quote_system->uninstall2();
		}
		return;
	}
	
	public function uninstall() {
		$this->load->model('extension/sale/quote_system');
		$this->model_extension_sale_quote_system->uninstall();
		return;
	}

}

?>