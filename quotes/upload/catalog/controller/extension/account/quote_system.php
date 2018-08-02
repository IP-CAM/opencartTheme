<?php

class ControllerExtensionAccountQuoteSystem extends Controller {

	private $error = array();

	public function index() {
		if (!$this->customer->isLogged() && !isset($this->session->data['quote_account_id'])) {
			$this->session->data['redirect'] = $this->url->link('extension/account/quote_system', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->language('extension/account/quote_system');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/account/quote_system', $url, true)
		);
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['page2'])) {
			$page2 = $this->request->get['page2'];
		} else {
			$page2 = 1;
		}
		$data['quote_requests'] = array();
		$this->load->model('extension/account/quote_system');
		$quote_request_total = $this->model_extension_account_quote_system->getTotalQuoteRequests();
		$quote_requests = $this->model_extension_account_quote_system->getQuoteRequests(($page - 1) * 10, 10);
		foreach ($quote_requests as $result) {
			$product_total = $this->model_extension_account_quote_system->getTotalQuoteProductsByQuoteId($result['quote_id']);
			$data['quote_requests'][] = array(
				'quote_id'		=> $result['quote_id'],
				'name'			=> $result['firstname'] . ' ' . $result['lastname'],
				'status'		=> $result['status'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'		=> $product_total,
				'href'			=> $this->url->link('extension/account/quote_system/info', 'quote_id=' . $result['quote_id'], true)
			);
		}
		$pagination = new Pagination();
		$pagination->total = $quote_request_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('extension/account/quote_system', 'page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($quote_request_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($quote_request_total - 10)) ? $quote_request_total : ((($page - 1) * 10) + 10), $quote_request_total, ceil($quote_request_total / 10));
		$data['ready_quotes'] = array();
		$quote_ready_total = $this->model_extension_account_quote_system->getTotalQuoteReady();
		$ready_quotes = $this->model_extension_account_quote_system->getQuoteReady(($page2 - 1) * 10, 10);
		foreach ($ready_quotes as $result2) {
			$product_total2 = $this->model_extension_account_quote_system->getTotalQuoteProductsByQuoteId($result2['quote_id']);
			$data['ready_quotes'][] = array(
				'quote_id'		=> $result2['quote_id'],
				'name'			=> $result2['firstname'] . ' ' . $result2['lastname'],
				'status'		=> $result2['status'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result2['date_added'])),
				'products'		=> $product_total2,
				'href'			=> $this->url->link('extension/account/quote_system/info', 'quote_id=' . $result2['quote_id'], true)
			);
		}
		$pagination2 = new Pagination();
		$pagination2->total = $quote_ready_total;
		$pagination2->page = $page2;
		$pagination2->limit = 10;
		$pagination2->url = $this->url->link('extension/account/quote_system', 'page2={page2}', true);
		$data['pagination2'] = $pagination2->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($quote_ready_total) ? (($page2 - 1) * 10) + 1 : 0, ((($page2 - 1) * 10) > ($quote_ready_total - 10)) ? $quote_ready_total : ((($page2 - 1) * 10) + 10), $quote_ready_total, ceil($quote_ready_total / 10));
		$data['continue'] = $this->url->link('common/home', '', true);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('extension/account/quote_system_list', $data));
	}

	public function info() {
		$this->load->language('extension/account/quote_system');
		if (isset($this->request->get['squote_id'])) {
			$quote_id = $this->request->get['squote_id'];
		} elseif (isset($this->request->get['quote_id'])) {
			$quote_id = $this->request->get['quote_id'];
		} else {
			$quote_id = 0;
		}
		if (!$this->customer->isLogged() && !isset($this->session->data['quote_account_id'])) {
			$this->session->data['redirect'] = $this->url->link('extension/account/quote_system/info', 'quote_id=' . $quote_id, true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->model('extension/account/quote_system');
		$quote_info = $this->model_extension_account_quote_system->getQuote($quote_id);
		if ($quote_info) {
			$this->document->setTitle($this->language->get('text_quote'));
			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/account/quote_system', $url, true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quote'),
				'href' => $this->url->link('extension/account/quote_system/info', 'quote_id=' . $quote_id . $url, true)
			);
			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];
				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
			$data['quote_id'] = $quote_id;
			if ($quote_info['order_status_id'] == $this->config->get('module_quote_system_ready_status_id')) {
				$data['show_buy'] = 1;
			} else {
				$data['show_buy'] = 0;
			}
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($quote_info['date_added']));
			$this->load->model('tool/upload');
			$data['products'] = array();
			$products = $this->model_extension_account_quote_system->getQuoteProducts($quote_id);
			foreach ($products as $product) {
				$option_data = array();
				$options = $this->model_extension_account_quote_system->getQuoteOptions($quote_id, $product['quote_product_id']);
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
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				$data['products'][] = array(
					'name'			=> $product['name'],
					'model'			=> $product['model'],
					'option'		=> $option_data,
					'quantity'		=> $product['quantity'],
					'price'			=> $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quote_info['currency_code'], $quote_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value'])
				);
			}
			$data['totals'] = array();
			$totals = $this->model_extension_account_quote_system->getQuoteTotals($quote_id);
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $quote_info['currency_code'], $quote_info['currency_value']),
				);
			}
			$data['comment'] = nl2br($quote_info['comment']);
			$data['histories'] = array();
			$results = $this->model_extension_account_quote_system->getQuoteHistories($quote_id);
			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}
			if (file_exists(DIR_APPLICATION . 'view/javascript/giftTeaser/main.js')) {
				$data['giftTeaser'] = 1;
				$data['buy'] = $this->url->link('extension/account/quote_system/buy', 'quote_id=' . $quote_id, true);
			} else {
				$data['giftTeaser'] = 0;
				$data['buy'] = $this->url->link('extension/account/quote_system/buy', 'quote_id=' . $quote_id, true);
			}
			$data['continue'] = $this->url->link('extension/account/quote_system', '', true);
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$this->response->setOutput($this->load->view('extension/account/quote_system_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_quote'));
			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/account/quote_system', '', true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quote'),
				'href' => $this->url->link('extension/account/quote_system/info', 'quote_id=' . $quote_id, true)
			);
			$data['continue'] = $this->url->link('account/order', '', true);
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function buy() {
		$this->load->language('extension/account/quote_system');
		$this->load->model('extension/account/quote_system');
		$this->cart->clear();
		$this->cart->clearquote();
		$this->session->data['quote_id'] = $this->request->get['quote_id'];
		$quote_info = $this->model_extension_account_quote_system->getQuote($this->request->get['quote_id']);
		if (isset($quote_info['no_tax']) && $quote_info['no_tax']) {
			$this->session->data['oe']['no_tax'] = 1;
		}
		$quote_totals = $this->model_extension_account_quote_system->getQuoteTotals($this->request->get['quote_id']);
		foreach ($quote_totals as $quote_total) {
			if ($quote_total['code'] == 'shipping') {
				if (isset($quote_info['shipping_code']) && $quote_info['shipping_code'] == 'oe_custom_shipping.oe_custom_shipping') {
					$this->session->data['oe']['custom_shipping']['title'] = $quote_total['title'];
					$this->session->data['oe']['custom_shipping']['cost'] = $quote_total['value'];
				}
			}
		}
		$quote_products = $this->model_extension_account_quote_system->getQuoteProducts($this->request->get['quote_id']);
		$this->load->model('catalog/product');
		foreach ($quote_products as $quote_product) {
			$quote_options = $this->model_extension_account_quote_system->getQuoteOptions($this->request->get['quote_id'], $quote_product['quote_product_id']);
			$option_data = array();
			if ($quote_options) {
				foreach ($quote_options as $quote_option) {
					$option_data[$quote_option['product_option_id']] = $quote_option['product_option_value_id'];
				}
			}
			$key = $this->cart->add($quote_product['product_id'], $quote_product['quantity'], $option_data);
			$product_info = $this->model_catalog_product->getProduct($quote_product['product_id']);
			if ($product_info && $product_info['price'] != $quote_product['price']) {
				$this->session->data['quote']['price'][$key] = $quote_product['price'];
				$this->session->data['quote']['quantity'][$key] = $quote_product['quantity'];
			} else {
				$weight_class_name = '';
				$weight_class_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class_description`");
				foreach ($weight_class_query->rows as $weight_class) {
					if ($weight_class['weight_class_id'] == $quote_product['weight_class_id']) {
						$weight_class_name = $weight_class['unit'];
						break;
					}
				}
				$this->session->data['oe']['custom_product'][$key] = array(
					'product_id'		=> $quote_product['product_id'],
					'custom_product'	=> 1,
					'name'				=> $quote_product['name'],
					'model'				=> $quote_product['model'],
					'sku'				=> $quote_product['sku'],
					'upc'				=> $quote_product['upc'],
					'location'			=> $quote_product['location'],
					'shipping'			=> $quote_product['shipping'],
					'image'				=> '',
					'quantity'			=> $quote_product['quantity'],
					'price'				=> $quote_product['price'],
					'notax'				=> $quote_product['notax'],
					'sort_order'		=> (isset($quote_product['sort_order']) ? $quote_product['sort_order'] : 0),
					'tax_class_id'		=> $this->config->get('module_custom_products_tax_class_id'),
					'weight'			=> $quote_product['weight'],
					'weight_class_id'	=> $quote_product['weight_class_id'],
					'weight_class_name'	=> $weight_class_name,
					'length'			=> $quote_product['length'],
					'length_class_id'	=> $quote_product['length_class_id'],
					'width'				=> $quote_product['width'],
					'height'			=> $quote_product['height'],
					'manufacturer_id'	=> $quote_product['manufacturer_id']
				);
			}
		}
		$this->response->redirect($this->url->link('checkout/cart', '', true));
	}

}

?>