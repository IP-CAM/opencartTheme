<?php

class ModelExtensionPaymentOePendingPayment extends Model {

	public function getMethod($address, $total) {
		$method_data = array();
		if (isset($this->session->data['oe']['order_entry'])) {
			$this->load->language('extension/payment/oe_pending_payment');
			$method_data = array(
				'code'       => 'oe_pending_payment',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_oe_pending_payment_sort_order')
			);
		}
		return $method_data;
	}

}