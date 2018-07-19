<?php
class ControllerExtensionModuleMyLiveChat extends Controller {
	
	public function index() {
		$this->load->language('extension/module/mylivechat');

		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$mylivechatid = $this->config->get ( 'module_mylivechat_code' );
		$displaytype = $this->config->get ( 'module_mylivechat_displaytype' );


      $tempstr = "<script type=\"text/javascript\">function add_chatinline(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatinline.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatinline();</script>";
		switch ($displaytype) {
			case "0" :
				
$tempstr = "<script type=\"text/javascript\">function add_chatinline(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatinline.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatinline();</script>";
				break;
			case "1" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\">function add_chatbutton(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatbutton.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatbutton();</script>";
				break;
			case "2" :
				$tempstr = "<script type=\"text/javascript\">function add_chatwidget(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatwidget.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatwidget();</script>";
				break;
			case "3" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\">function add_chatbox(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatbox.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatbox();</script>";
				break;
			case "4" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\">function add_chatlink(){var hccid=" . $mylivechatid . ";var nt=document.createElement(\"script\");nt.async=true;nt.src=\"https://mylivechat.com/chatlink.aspx?hccid=\"+hccid;var ct=document.getElementsByTagName(\"script\")[0];ct.parentNode.insertBefore(nt,ct);}add_chatlink();</script>";
				break;
			default :
				break;
		}
		$data ['code'] = $tempstr;
		
		return $this->load->view('extension/module/mylivechat', $data);
	}
}