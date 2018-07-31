<?php

class ControllerExtensionSaleSalesAgents extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/sale/sales_agents');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/sale/sales_agents');
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/sale/sales_agents');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/sale/sales_agents');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_sale_sales_agents->addSalesAgent($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/sale/sales_agents');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/sale/sales_agents');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_sale_sales_agents->editSalesAgent($this->request->get['sales_agent_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/sale/sales_agents');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/sale/sales_agents');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $sales_agent_id) {
				$this->model_extension_sale_sales_agents->deleteSalesAgent($sales_agent_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
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
			'href' => $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$data['add'] = $this->url->link('extension/sale/sales_agents/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/sale/sales_agents/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['sales_agents'] = array();
		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		$sales_agent_total = $this->model_extension_sale_sales_agents->getTotalSalesAgents();
		$results = $this->model_extension_sale_sales_agents->getSalesAgents($filter_data);
		foreach ($results as $result) {
			$data['sales_agents'][] = array(
				'sales_agent_id'	=> $result['sales_agent_id'],
				'username'			=> $result['username'],
				'agent_name'		=> $result['firstname'] . ' ' . $result['lastname'],
				'email'				=> $result['email'],
				'customers'			=> $this->model_extension_sale_sales_agents->getCustomerCount($result['sales_agent_id']),
				'customer_href'		=> $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_sales_agent_id=' . $result['sales_agent_id'], true),
				'status'			=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'				=> $this->url->link('extension/sale/sales_agents/edit', 'user_token=' . $this->session->data['user_token'] . '&sales_agent_id=' . $result['sales_agent_id'] . $url, true)
			);
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
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['sort_username'] = $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . '&sort=username' . $url, true);
		$data['sort_status'] = $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $sales_agent_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($sales_agent_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($sales_agent_total - $this->config->get('config_limit_admin'))) ? $sales_agent_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $sales_agent_total, ceil($sales_agent_total / $this->config->get('config_limit_admin')));
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/sale/sales_agents_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['sales_agent_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
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
		} else {
			$data['error_email'] = '';
		}
		$url = '';
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
			'href' => $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		if (!isset($this->request->get['sales_agent_id'])) {
			$data['action'] = $this->url->link('extension/sale/sales_agents/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/sale/sales_agents/edit', 'user_token=' . $this->session->data['user_token'] . '&sales_agent_id=' . $this->request->get['sales_agent_id'] . $url, true);
		}
		$data['cancel'] = $this->url->link('extension/sale/sales_agents', 'user_token=' . $this->session->data['user_token'] . $url, true);
		if (isset($this->request->get['sales_agent_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$sales_agent_info = $this->model_extension_sale_sales_agents->getSalesAgent($this->request->get['sales_agent_id']);
		}
		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} elseif (!empty($sales_agent_info)) {
			$data['username'] = $sales_agent_info['username'];
		} else {
			$data['username'] = '';
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
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($sales_agent_info)) {
			$data['firstname'] = $sales_agent_info['firstname'];
		} else {
			$data['firstname'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($sales_agent_info)) {
			$data['lastname'] = $sales_agent_info['lastname'];
		} else {
			$data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($sales_agent_info)) {
			$data['email'] = $sales_agent_info['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($sales_agent_info)) {
			$data['image'] = $sales_agent_info['image'];
		} else {
			$data['image'] = '';
		}
		$this->load->model('tool/image');
		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($sales_agent_info) && $sales_agent_info['image'] && is_file(DIR_IMAGE . $sales_agent_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($sales_agent_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($sales_agent_info)) {
			$data['status'] = $sales_agent_info['status'];
		} else {
			$data['status'] = 0;
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/sale/sales_agents_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/sale/sales_agents')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
			$this->error['username'] = $this->language->get('error_username');
		}
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}
		$sales_agent_info = $this->model_extension_sale_sales_agents->getSalesAgentByUsername($this->request->post['username']);
		if (!isset($this->request->get['sales_agent_id'])) {
			if ($sales_agent_info) {
				$this->error['warning'] = $this->language->get('error_username_exists');
			}
		} else {
			if ($sales_agent_info && ($this->request->get['sales_agent_id'] != $sales_agent_info['sales_agent_id'])) {
				$this->error['warning'] = $this->language->get('error_username_exists');
			}
		}
		$sales_agent_email = $this->model_extension_sale_sales_agents->getSalesAgentByEmail($this->request->post['email']);
		if (!isset($this->request->get['sales_agent_id'])) {
			if ($sales_agent_email) {
				$this->error['warning'] = $this->language->get('error_email_exists');
			}
		} else {
			if ($sales_agent_email && ($this->request->get['sales_agent_id'] != $sales_agent_info['sales_agent_id'])) {
				$this->error['warning'] = $this->language->get('error_email_exists');
			}
		}
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		if ($this->request->post['password'] || (!isset($this->request->get['sales_agent_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}
			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/sale/sales_agents')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

}