<?php

class ControllerExtensionModuleOrderEntry extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/order_entry');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/sale/order_entry');
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_order_entry', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_oe'])) {
			$data['error_warning'] = $this->session->data['error_oe'];
			unset($this->session->data['error_oe']);
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
			'href' => $this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['action'] = $this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true);
		$data['update_tables'] = $this->url->link('extension/module/order_entry/updateTables', 'user_token=' . $this->session->data['user_token'], true);
		$data['update_orders'] = $this->url->link('extension/module/order_entry/updateOrders', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		if (isset($this->request->post['module_order_entry_status'])) {
			$data['module_order_entry_status'] = $this->request->post['module_order_entry_status'];
		} else {
			$data['module_order_entry_status'] = $this->config->get('module_order_entry_status');
		}
		$this->load->model('user/user_group');
		$data['user_groups'] = $this->model_user_user_group->getUserGroups();
		if (isset($this->request->post['module_order_entry_create_orders'])) {
			$data['module_order_entry_create_orders'] = $this->request->post['module_order_entry_create_orders'];
		} else {
			$oe_create_orders = $this->config->get('module_order_entry_create_orders');
			if (is_array($oe_create_orders)) {
				$data['module_order_entry_create_orders'] = $oe_create_orders;
			} else {
				$data['module_order_entry_create_orders'] = array();
			}
		}
		if (isset($this->request->post['module_order_entry_edit_orders'])) {
			$data['module_order_entry_edit_orders'] = $this->request->post['module_order_entry_edit_orders'];
		} else {
			$oe_edit_orders = $this->config->get('module_order_entry_edit_orders');
			if (is_array($oe_edit_orders)) {
				$data['module_order_entry_edit_orders'] = $oe_edit_orders;
			} else {
				$data['module_order_entry_edit_orders'] = array();
			}
		}
		if (isset($this->request->post['module_order_entry_delete_orders'])) {
			$data['module_order_entry_delete_orders'] = $this->request->post['module_order_entry_delete_orders'];
		} else {
			$oe_delete_orders = $this->config->get('module_order_entry_delete_orders');
			if (is_array($oe_delete_orders)) {
				$data['module_order_entry_delete_orders'] = $oe_delete_orders;
			} else {
				$data['module_order_entry_delete_orders'] = array();
			}
		}
		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		if (isset($this->request->post['module_order_entry_customer_group'])) {
			$data['module_order_entry_customer_group'] = $this->request->post['module_order_entry_customer_group'];
		} else {
			$data['module_order_entry_customer_group'] = $this->config->get('module_order_entry_customer_group');
		}
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		if (isset($this->request->post['module_order_entry_order_status'])) {
			$data['module_order_entry_order_status'] = $this->request->post['module_order_entry_order_status'];
		} else {
			$data['module_order_entry_order_status'] = $this->config->get('module_order_entry_order_status');
		}
		if (isset($this->request->post['module_order_entry_save_new_customer'])) {
			$data['module_order_entry_save_new_customer'] = $this->request->post['module_order_entry_save_new_customer'];
		} else {
			$data['module_order_entry_save_new_customer'] = $this->config->get('module_order_entry_save_new_customer');
		}
		if (isset($this->request->post['module_order_entry_order_notification'])) {
			$data['module_order_entry_order_notification'] = $this->request->post['module_order_entry_order_notification'];
		} else {
			$data['module_order_entry_order_notification'] = $this->config->get('module_order_entry_order_notification');
		}
		if (isset($this->request->post['module_order_entry_selected_store'])) {
			$data['module_order_entry_selected_store'] = $this->request->post['module_order_entry_selected_store'];
		} else {
			$data['module_order_entry_selected_store'] = $this->config->get('module_order_entry_selected_store');
		}
		if (isset($this->request->post['module_order_entry_allow_disabled'])) {
			$data['module_order_entry_allow_disabled'] = $this->request->post['module_order_entry_allow_disabled'];
		} else {
			$data['module_order_entry_allow_disabled'] = $this->config->get('module_order_entry_allow_disabled');
		}
		if (isset($this->request->post['module_order_entry_allow_zero_qty'])) {
			$data['module_order_entry_allow_zero_qty'] = $this->request->post['module_order_entry_allow_zero_qty'];
		} else {
			$data['module_order_entry_allow_zero_qty'] = $this->config->get('module_order_entry_allow_zero_qty');
		}
		if (isset($this->request->post['module_order_entry_fulltext_search'])) {
			$data['module_order_entry_fulltext_search'] = $this->request->post['module_order_entry_fulltext_search'];
		} else {
			$data['module_order_entry_fulltext_search'] = $this->config->get('module_order_entry_fulltext_search');
		}
		if (isset($this->request->post['module_order_entry_previous_orders'])) {
			$data['module_order_entry_previous_orders'] = $this->request->post['module_order_entry_previous_orders'];
		} else {
			$data['module_order_entry_previous_orders'] = $this->config->get('module_order_entry_previous_orders');
		}
		if (isset($this->request->post['module_order_entry_previous_count'])) {
			$data['module_order_entry_previous_count'] = $this->request->post['module_order_entry_previous_count'];
		} else {
			$data['module_order_entry_previous_count'] = $this->config->get('module_order_entry_previous_count');
		}
		if (isset($this->request->post['module_order_entry_require_telephone'])) {
			$data['module_order_entry_require_telephone'] = $this->request->post['module_order_entry_require_telephone'];
		} else {
			$data['module_order_entry_require_telephone'] = $this->config->get('module_order_entry_require_telephone');
		}
		if (isset($this->request->post['module_order_entry_require_lastname'])) {
			$data['module_order_entry_require_lastname'] = $this->request->post['module_order_entry_require_lastname'];
		} else {
			$data['module_order_entry_require_lastname'] = $this->config->get('module_order_entry_require_lastname');
		}
		if (isset($this->request->post['module_order_entry_require_email'])) {
			$data['module_order_entry_require_email'] = $this->request->post['module_order_entry_require_email'];
		} else {
			$data['module_order_entry_require_email'] = $this->config->get('module_order_entry_require_email');
		}
		if (isset($this->request->post['module_order_entry_require_city'])) {
			$data['module_order_entry_require_city'] = $this->request->post['module_order_entry_require_city'];
		} else {
			$data['module_order_entry_require_city'] = $this->config->get('module_order_entry_require_city');
		}
		if (isset($this->request->post['module_order_entry_require_zone'])) {
			$data['module_order_entry_require_zone'] = $this->request->post['module_order_entry_require_zone'];
		} else {
			$data['module_order_entry_require_zone'] = $this->config->get('module_order_entry_require_zone');
		}
		if (isset($this->request->post['module_order_entry_company_search'])) {
			$data['module_order_entry_company_search'] = $this->request->post['module_order_entry_company_search'];
		} else {
			$data['module_order_entry_company_search'] = $this->config->get('module_order_entry_company_search');
		}
		if (isset($this->request->post['module_order_entry_product_columns'])) {
			$data['module_order_entry_product_columns'] = $this->request->post['module_order_entry_product_columns'];
		} else {
			$data['module_order_entry_product_columns'] = $this->config->get('module_order_entry_product_columns');
			if (!is_array($data['module_order_entry_product_columns'])) {
				$data['module_order_entry_product_columns'] = array();
			}
		}
		$data['oe_modules'] = array();
		$results = $this->model_extension_sale_order_entry->getModules();
		foreach ($results as $result) {
			$action = array();
			if ($this->config->get('module_' . $result['module_code'] . '_status') || $this->config->get('total_' . $result['module_code'] . '_status') || $this->config->get('payment_' . $result['module_code'] . '_status') || $this->config->get('shipping_' . $result['module_code'] . '_status')) {
				$status = $this->language->get('text_enabled');
				$action[] = array(
					'text'	=> $this->language->get('text_disable'),
					'href'	=> $this->url->link('extension/module/order_entry/disable', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $result['oe_module_id'], true)
				);
			} else {
				$status = $this->language->get('text_disabled');
				$action[] = array(
					'text'	=> $this->language->get('text_enable'),
					'href'	=> $this->url->link('extension/module/order_entry/enable', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $result['oe_module_id'], true)
				);
			}
			$data['oe_modules'][] = array(
				'module_id'	=> $result['oe_module_id'],
				'name'		=> $result['module_name'],
				'status'	=> $status,
				'action'	=> $action
			);
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/order_entry', $data));
	}

	public function enable() {
		$this->load->language('extension/module/order_entry');
		if (isset($this->request->get['module_id']) && $this->validate()) {
			$this->load->model('extension/sale/order_entry');
			$result = $this->model_extension_sale_order_entry->enable($this->request->get['module_id']);
			if ($result == 1) {
				$this->session->data['success'] = $this->language->get('text_enable_success');
			} else {
				$this->session->data['error_oe'] = $this->language->get('error_module_id');
			}
		} else {
			$this->session->data['error_oe'] = $this->language->get('error_permission');
		}
		$this->response->redirect($this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true));
	}
	
	public function disable() {
		$this->load->language('extension/module/order_entry');
		if (isset($this->request->get['module_id']) && $this->validate()) {
			$this->load->model('extension/sale/order_entry');
			$result = $this->model_extension_sale_order_entry->disable($this->request->get['module_id']);
			if ($result == 1) {
				$this->session->data['success'] = $this->language->get('text_disable_success');
			} else {
				$this->session->data['error_oe'] = $this->language->get('error_module_id');
			}
		} else {
			$this->session->data['error_oe'] = $this->language->get('error_permission');
		}
		$this->response->redirect($this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/order_entry')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function updateTables() {
		$this->load->language('extension/module/order_entry');
		$this->load->model('extension/sale/order_entry');
		$this->model_extension_sale_order_entry->updateTables();
		$this->session->data['success'] = $this->language->get('text_update_tables_success');
		$this->response->redirect($this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function updateOrders() {
		$this->load->language('extension/module/order_entry');
		$this->load->model('extension/sale/order_entry');
		$this->model_extension_sale_order_entry->updateOrders();
		$this->session->data['success'] = $this->language->get('text_update_orders_success');
		$this->response->redirect($this->url->link('extension/module/order_entry', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function install() {
		$this->load->model('extension/sale/order_entry');
		$this->model_extension_sale_order_entry->install();
		return;
	}

	public function uninstall() {
		$this->load->model('extension/sale/order_entry');
		$this->model_extension_sale_order_entry->uninstall();
		return;
	}

}