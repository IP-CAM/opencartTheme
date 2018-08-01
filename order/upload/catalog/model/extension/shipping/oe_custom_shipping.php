<?php

class ModelExtensionShippingOeCustomShipping extends Model {

	function getQuote($address) {
		$this->load->language('extension/shipping/oe_custom_shipping');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_oe_custom_shipping_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		if (!$this->config->get('shipping_oe_custom_shipping_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		$method_data = array();
		if ($status) {
			if (isset($this->session->data['oe']['custom_shipping'])) {
				$title = $this->session->data['oe']['custom_shipping']['title'];
				if ($this->config->get('shipping_oe_custom_shipping_display_weight')) {
					$weight = $this->cart->getWeight();
					$description = $this->session->data['oe']['custom_shipping']['title'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
				} else {
					$description = $this->session->data['oe']['custom_shipping']['title'];
				}
				$cost = $this->session->data['oe']['custom_shipping']['cost'];
			} else {
				if ($this->config->get('shipping_oe_custom_shipping_desc')) {
					$desc = $this->config->get('shipping_oe_custom_shipping_desc');
				} else {
					$desc = $this->language->get('text_custom_shipping_desc');
				}
				if ($this->config->get('shipping_oe_custom_shipping_display_weight')) {
					$weight = $this->cart->getWeight();
					$description = sprintf($this->language->get('text_description'), $desc) . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
				} else {
					$description = sprintf($this->language->get('text_description'), $desc);
				}
				if ($this->config->get('shipping_oe_custom_shipping_title')) {
					$title = $this->config->get('shipping_oe_custom_shipping_title');
				} else {
					$title = $this->language->get('text_custom_shipping_title');
				}
				$cost = $this->config->get('shipping_oe_custom_shipping_cost');
			}
			$quote_data = array();
			$quote_data['oe_custom_shipping'] = array(
				'code'         => 'oe_custom_shipping.oe_custom_shipping',
				'title'        => $description,
				'cost'         => $cost,
				'tax_class_id' => $this->config->get('shipping_oe_custom_shipping_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_oe_custom_shipping_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);
			$method_data = array(
				'code'       => 'oe_custom_shipping',
				'title'      => sprintf($this->language->get('text_title'), $title),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_oe_custom_shipping_sort_order'),
				'error'      => false
			);
		}
		return $method_data;
	}

}