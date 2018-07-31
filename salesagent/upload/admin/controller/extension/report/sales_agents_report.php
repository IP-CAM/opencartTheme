<?php
class ControllerExtensionReportSalesAgentsReport extends Controller {

	public function index() {
		$this->load->language('extension/report/sales_agents_report');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/report/sales_agents_report', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$this->load->model('extension/sale/sales_agents');
		$data['sales_agents'] = array();
		$sales_agents = array();
		if (isset($this->session->data['sales_agent_id'])) {
			$sales_agents[] = $this->model_extension_sale_sales_agents->getSalesAgent($this->session->data['sales_agent_id']);
		} else {
			$sales_agents = $this->model_extension_sale_sales_agents->getSalesAgents();
		}
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id
		);
		if ($sales_agents) {
			foreach ($sales_agents as $sales_agent) {
				if ($sales_agent['firstname']) {
					$name = $sales_agent['firstname'] . ' ' . $sales_agent['lastname'];
				} else {
					$name = $sales_agent['username'];
				}
				$agent_orders = $this->model_extension_sale_sales_agents->getSalesAgentOrders($sales_agent['sales_agent_id'], $filter_data);
				if ($agent_orders) {
					foreach ($agent_orders as $agent_order) {
						$net_sales = $agent_order['total'] - ($agent_order['shipping'] + $agent_order['tax'] + $agent_order['other_fees'] + -$agent_order['coupon']);
						$data['sales_agents'][] = array(
							'sales_agent_id'	=> $sales_agent['sales_agent_id'],
							'name'				=> $name,
							'orders'			=> $agent_order['orders'],
							'products'			=> ($agent_order['products'] ? $agent_order['products'] : 0),
							'gross_sales'		=> $this->currency->format($agent_order['total'], $this->config->get('config_currency')),
							'shipping'			=> $this->currency->format(-$agent_order['shipping'], $this->config->get('config_currency')),
							'tax'				=> $this->currency->format(-$agent_order['tax'], $this->config->get('config_currency')),
							'other_fees'		=> $this->currency->format(-$agent_order['other_fees'], $this->config->get('config_currency')),
							'coupon'			=> $this->currency->format($agent_order['coupon'], $this->config->get('config_currency')),
							'net_sales'			=> $this->currency->format($net_sales, $this->config->get('config_currency'))
						);
					}
				} else {
					$data['sales_agents'][] = array(
						'sales_agent_id'	=> $sales_agent['sales_agent_id'],
						'name'				=> $name,
						'orders'			=> 0,
						'products'			=> 0,
						'gross_sales'		=> $this->currency->format(0, $this->config->get('config_currency')),
						'shipping'			=> $this->currency->format(0, $this->config->get('config_currency')),
						'tax'				=> $this->currency->format(0, $this->config->get('config_currency')),
						'other_fees'		=> $this->currency->format(0, $this->config->get('config_currency')),
						'coupon'			=> $this->currency->format(0, $this->config->get('config_currency')),
						'net_sales'			=> $this->currency->format(0, $this->config->get('config_currency'))
					);
				}
			}
		}
		$data['user_token'] = $this->session->data['user_token'];
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/report/sales_agents_report', $data));
	}

	public function export() {
		$this->load->language('extension/report/sales_agents_report');
		$this->load->model('extension/sale/sales_agents');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}
		$sales_agents = array();
		if (isset($this->session->data['sales_agent_id'])) {
			$sales_agents[] = $this->model_extension_sale_sales_agents->getSalesAgent($this->session->data['sales_agent_id']);
		} else {
			$sales_agents = $this->model_extension_sale_sales_agents->getSalesAgents();
		}
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id
		);
		$filename = '';
		if ($sales_agents) {
			$headers = array($this->language->get('column_name'), $this->language->get('column_orders'), $this->language->get('column_products'), $this->language->get('column_gross_sales'), $this->language->get('column_shipping'), $this->language->get('column_tax'), $this->language->get('column_other_fees'), $this->language->get('column_coupon'), $this->language->get('column_net_sales'));
			$filename = 'sales_agents_report_' . date('m-d-y', time()) . '_' . date('H-i', time()) . '.csv';
			$fp = fopen($filename, 'w');
			fputcsv($fp, $headers);
			foreach ($sales_agents as $sales_agent) {
				if ($sales_agent['firstname']) {
					$name = $sales_agent['firstname'] . ' ' . $sales_agent['lastname'];
				} else {
					$name = $sales_agent['username'];
				}
				$agent_orders = $this->model_extension_sale_sales_agents->getSalesAgentOrders($sales_agent['sales_agent_id'], $filter_data);
				$orders = 0;
				$products = 0;
				$gross_sales = 0;
				$shipping = 0;
				$tax = 0;
				$other_fees = 0;
				$coupon = 0;
				$net_sales = 0;
				if ($agent_orders) {
					foreach ($agent_orders as $agent_order) {
						$net_sales = $agent_order['total'] - ($agent_order['shipping'] + $agent_order['tax'] + $agent_order['other_fees'] + -$agent_order['coupon']);
						$net_sales = $this->currency->format($net_sales, $this->config->get('config_currency'));
						$orders = $agent_order['orders'];
						$products = ($agent_order['products'] ? $agent_order['products'] : 0);
						$gross_sales = $this->currency->format($agent_order['total'], $this->config->get('config_currency'));
						$shipping = $this->currency->format(-$agent_order['shipping'], $this->config->get('config_currency'));
						$tax = $this->currency->format(-$agent_order['tax'], $this->config->get('config_currency'));
						$other_fees = $this->currency->format(-$agent_order['other_fees'], $this->config->get('config_currency'));
						$coupon = $this->currency->format($agent_order['coupon'], $this->config->get('config_currency'));
					}
				}
				$fields = array(html_entity_decode($name), $orders, $products, $gross_sales, $shipping, $tax, $other_fees, $coupon, $net_sales);
				fputcsv($fp, $fields);
			}
			fclose($fp);
		}
		$this->response->setOutput(json_encode($filename));
	}

}

?>