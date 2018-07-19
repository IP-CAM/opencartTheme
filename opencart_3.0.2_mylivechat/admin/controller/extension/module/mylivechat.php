<?php 

class ControllerExtensionModuleMyLiveChat extends Controller {
	
	private $error = array(); 

	public function index() {
		
		$this->load->language('extension/module/mylivechat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_mylivechat', $this->request->post);				
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

	
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$data ['entry_code'] = $this->language->get('entry_code');
		$data ['entry_displaytype'] = $this->language->get('entry_displaytype');
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['module_mylivechat_code'])) {
			$data['error_mylivechat_code'] = $this->error['module_mylivechat_code'];
		} else {
			$data['error_mylivechat_code'] = '';
		}


  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/mylivechat', 'user_token=' . $this->session->data['user_token'], true)
   		);
				
		$data['action'] = $this->url->link('extension/module/mylivechat', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
	
		if (isset ( $this->request->post ['module_mylivechat_status'] )) {
			$data ['module_mylivechat_status'] = $this->request->post ['module_mylivechat_status'];
		} else {
			$data ['module_mylivechat_status'] = $this->config->get ( 'module_mylivechat_status' );
		}
		if (isset ( $this->request->post ['module_mylivechat_code'] )) {
			$data ['module_mylivechat_code'] = $this->request->post ['module_mylivechat_code'];
		} else {
			$data ['module_mylivechat_code'] = $this->config->get ( 'module_mylivechat_code' );
		}
		if (isset ( $this->request->post ['module_mylivechat_displaytype'] )) {
			$data ['module_mylivechat_displaytype'] = $this->request->post ['module_mylivechat_displaytype'];
		} else {
			$data ['module_mylivechat_displaytype'] = $this->config->get ( 'module_mylivechat_displaytype' );
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/mylivechat', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/mylivechat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}