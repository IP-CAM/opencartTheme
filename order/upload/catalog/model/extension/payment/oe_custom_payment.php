<?php

class ModelExtensionPaymentOeCustomPayment extends Model {

	public function getMethod($address, $total) {
		$method_data = array();
		if (isset($this->session->data['oe']['order_entry'])) {
			$this->load->language('extension/payment/oe_custom_payment');
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_oe_custom_payment_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			if (!$this->config->get('payment_oe_custom_payment_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
			if ($status) {
				$method_data = array(
					'code'       => 'oe_custom_payment',
					'title'      => $this->config->get('payment_oe_custom_payment_title'),
					'terms'      => '',
					'sort_order' => $this->config->get('payment_oe_custom_payment_sort_order')
				);
			}
		}
		return $method_data;
	}

}