<?php

class ControllerExtensionModuleSalesAgents extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/sales_agents');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_sales_agents', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
			'href' => $this->url->link('extension/module/sales_agents', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['action'] = $this->url->link('extension/module/sales_agents', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		if (isset($this->request->post['module_sales_agents_use_logged'])) {
			$data['module_sales_agents_use_logged'] = $this->request->post['module_sales_agents_use_logged'];
		} else {
			$data['module_sales_agents_use_logged'] = $this->config->get('module_sales_agents_use_logged');
		}
		if (isset($this->request->post['module_sales_agents_user_group_id'])) {
			$data['module_sales_agents_user_group_id'] = $this->request->post['module_sales_agents_user_group_id'];
		} else {
			$data['module_sales_agents_user_group_id'] = $this->config->get('module_sales_agents_user_group_id');
		}
		$this->load->model('user/user_group');
		$data['user_groups'] = $this->model_user_user_group->getUserGroups();
		if (isset($this->request->post['module_sales_agents_show_sales_agent'])) {
			$data['module_sales_agents_show_sales_agent'] = $this->request->post['module_sales_agents_show_sales_agent'];
		} else {
			$data['module_sales_agents_show_sales_agent'] = $this->config->get('module_sales_agents_show_sales_agent');
		}
		if (isset($this->request->post['module_sales_agents_show_agent_order_list'])) {
			$data['module_sales_agents_show_agent_order_list'] = $this->request->post['module_sales_agents_show_agent_order_list'];
		} else {
			$data['module_sales_agents_show_agent_order_list'] = $this->config->get('module_sales_agents_show_agent_order_list');
		}
		if (isset($this->request->post['module_sales_agents_customer_orders'])) {
			$data['module_sales_agents_customer_orders'] = $this->request->post['module_sales_agents_customer_orders'];
		} else {
			$data['module_sales_agents_customer_orders'] = $this->config->get('module_sales_agents_customer_orders');
		}
		if (isset($this->request->post['module_sales_agents_sales_report'])) {
			$data['module_sales_agents_sales_report'] = $this->request->post['module_sales_agents_sales_report'];
		} else {
			$data['module_sales_agents_sales_report'] = $this->config->get('module_sales_agents_sales_report');
		}
		if (isset($this->request->post['module_sales_agents_status'])) {
			$data['module_sales_agents_status'] = $this->request->post['module_sales_agents_status'];
		} else {
			$data['module_sales_agents_status'] = $this->config->get('module_sales_agents_status');
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/sales_agents', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/sales_agents')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/sale/sales_agents');
		if ($this->config->get('module_order_entry_status')) {
			$this->model_extension_sale_sales_agents->install();
		} else {
			$this->model_extension_sale_sales_agents->uninstall2();
		}
		return;
	}
	
	public function uninstall() {
		$this->load->model('extension/sale/sales_agents');
		$this->model_extension_sale_sales_agents->uninstall();
		return;
	}

}

?>