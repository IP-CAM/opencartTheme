<?php

class ModelExtensionCheckoutQuoteSystem extends Model {

	public function removeQuotes() {
		$column_query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'date_approved'");
		if ($column_query->num_rows) {
			$query = $this->db->query("SELECT `quote_id`, `date_approved` FROM `" . DB_PREFIX . "oe_quote` WHERE `date_approved` != '0000-00-00 00:00:00' AND `date_approved` != ''");
			if ($query->num_rows) {
				foreach ($query->rows as $row) {
					$approved = strtotime($row['date_approved']);
					$end_date = $approved + (86400 * $this->config->get('module_quote_system_quote_expire'));
					$day = date('d', $end_date);
					$month = date('m', $end_date);
					$year = date('Y', $end_date);
					$end_date = mktime(0,0,0,$month,$day,$year);
					if ($end_date < time()) {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_history` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
					}
				}
			}
		}
		return;
	}

	public function checkEmail($email) {
		$account_query = $this->db->query("SELECT `email` FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		if ($account_query->num_rows) {
			return true;
		}
		$quote_account_query = $this->db->query("SELECT `email` FROM `" . DB_PREFIX . "oe_quote_account` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		if ($quote_account_query->num_rows) {
			return true;
		}
		return false;
	}

	public function createQuoteAccount($data) {
		$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `country_id` = '" . (int)$data['country_id'] . "'");
		if ($country_query->num_rows) {
			$country_name = $country_query->row['name'];
			$address_format = $country_query->row['address_format'];
		} else {
			$country_name = '';
			$address_format = '';
		}
		$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$data['zone_id'] . "'");
		if ($zone_query->num_rows) {
			$zone_name = $zone_query->row['name'];
		} else {
			$zone_name = '';
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_account` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `telephone` = '" . $this->db->escape($data['telephone']) . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', `company` = '" . $this->db->escape($data['company']) . "', `address_1` = '" . $this->db->escape($data['address_1']) . "', `address_2` = '" . $this->db->escape($data['address_2']) . "', `city` = '" . $this->db->escape($data['city']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `country` = '" . $this->db->escape($country_name) . "', `country_id` = '" . (int)$data['country_id'] . "', `zone` = '" . $this->db->escape($zone_name) . "', `zone_id` = '" . (int)$data['zone_id'] . "', `address_format` = '" . $this->db->escape($address_format) . "'");
		return $this->db->getLastId();
	}

	public function getQuoteAccount($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		if ($query->num_rows < 1) {
			$query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_account` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			if ($query2->num_rows) {
				return $query2->row;
			} else {
				return false;
			}
		} else {
			return $query->row;
		}
	}

	public function getQuoteGuestId() {
		$account_id = 0;
		$guest_id = 0;
		$query = $this->db->query("SELECT MAX(quote_account_id) as account_id FROM `" . DB_PREFIX . "oe_quote_account`");
		if ($query->num_rows) {
			$account_id = $query->row['account_id'];
		}
		$query2 = $this->db->query("SELECT MAX(quote_guest_id) as guest_id FROM `" . DB_PREFIX . "oe_quote_cart`");
		if ($query2->num_rows) {
			$guest_id = $query2->row['guest_id'];
		}
		if ($account_id >= $guest_id) {
			$account_id++;
			return $account_id;
		} else {
			$guest_id++;
			return $guest_id;
		}
	}

	public function updateQuoteCart($quote_guest_id, $quote_account_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "oe_quote_cart` SET `quote_account_id` = '" . (int)$quote_account_id . "', `quote_guest_id` = '0' WHERE `quote_guest_id` = '" . (int)$quote_guest_id . "'");
		return;
	}

	public function addQuote($quote_data) {
		$order_status_id = (isset($quote_data['order_status_id']) ? (int)$quote_data['order_status_id'] : (int)$this->config->get('module_quote_system_request_status_id'));
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote` SET `store_id` = '" . (int)$quote_data['store_id'] . "', `store_name` = '" . $this->db->escape($quote_data['store_name']) . "', `store_url` = '" . $this->db->escape($quote_data['store_url']) . "', `customer_id` = '" . (int)$quote_data['customer_id'] . "', `customer_group_id` = '" . (int)$quote_data['customer_group_id'] . "', `quote_account_id` = '" . (int)$quote_data['quote_account_id'] . "', `firstname` = '" . $this->db->escape($quote_data['firstname']) . "', `lastname` = '" . $this->db->escape($quote_data['lastname']) . "', `email` = '" . $this->db->escape($quote_data['email']) . "', `telephone` = '" . $this->db->escape($quote_data['telephone']) . "', `payment_firstname` = '" . $this->db->escape($quote_data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($quote_data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape($quote_data['payment_company']) . "', `payment_address_1` = '" . $this->db->escape($quote_data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($quote_data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($quote_data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($quote_data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape($quote_data['payment_country']) . "', `payment_country_id` = '" . (int)$quote_data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape($quote_data['payment_zone']) . "', `payment_zone_id` = '" . (int)$quote_data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape($quote_data['payment_address_format']) . "', `payment_custom_field` = '" . (isset($quote_data['payment_custom_field']) ? serialize($quote_data['payment_custom_field']) : '') . "', `shipping_firstname` = '" . $this->db->escape($quote_data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($quote_data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape($quote_data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape($quote_data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($quote_data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($quote_data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($quote_data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape($quote_data['shipping_country']) . "', `shipping_country_id` = '" . (int)$quote_data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape($quote_data['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$quote_data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape($quote_data['shipping_address_format']) . "', `shipping_custom_field` = '" . (isset($quote_data['shipping_custom_field']) ? serialize($quote_data['shipping_custom_field']) : '') . "', `shipping_method` = '" . $this->db->escape($quote_data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape($quote_data['shipping_code']) . "', `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$quote_data['language_id'] . "', `total` = '" . (float)$quote_data['total'] . "', `currency_id` = '" . (int)$quote_data['currency_id'] . "', `currency_code` = '" . $this->db->escape($quote_data['currency_code']) . "', `currency_value` = '" . (float)$quote_data['currency_value'] . "', `ip` = '" . $this->db->escape($quote_data['ip']) . "', `forwarded_ip` = '" . $this->db->escape($quote_data['forwarded_ip']) . "', `user_agent` = '" . $this->db->escape($quote_data['user_agent']) . "', `accept_language` = '" . $this->db->escape($quote_data['accept_language']) . "', `comment` = '" . $this->db->escape($quote_data['comment']) . "', `customer_added` = '1', `ref_no` = '" . (isset($quote_data['ref_no']) ? $this->db->escape($quote_data['ref_no']) : '') . "', `no_tax` = '" . (isset($quote_data['no_tax']) ? (int)$quote_data['no_tax'] : 0) . "', `pp_link` = '" . (isset($quote_data['pp_link']) ? (int)$quote_data['pp_link'] : '0') . "', `soft_quote` = '" . (isset($quote_data['soft_quote']) ? (int)$quote_data['soft_quote'] : 0) . "', `date_added` = NOW()");
		$quote_id = $this->db->getLastId();
		if (isset($quote_data['quoteproducts']) && !empty($quote_data['quoteproducts'])) {
			foreach ($quote_data['quoteproducts'] as $product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_product` SET `quote_id` = '" . (int)$quote_id . "', `product_id` = '" . (int)$product['product_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '0.0000', `total` = '0.0000', `tax` = '0.0000', `sku` = '" . $this->db->escape($product['sku']) . "', `upc` = '" . $this->db->escape($product['upc']) . "', `location` = '" . $this->db->escape($product['location']) . "', `shipping` = '" . (int)$product['shipping'] . "', `image` = '" . $this->db->escape($product['image']) . "', `sort_order` = '" . (int)$product['sort_order'] . "', `tax_class_id` = '" . (int)$product['tax_class_id'] . "', `weight` = '" . (float)$product['weight'] / (int)$product['quantity'] . "', `weight_class_id` = '" . (int)$product['weight_class_id'] . "', `length` = '" . (float)$product['length'] . "', `length_class_id` = '" . (int)$product['length_class_id'] . "', `width` = '" . (float)$product['width'] . "', `height` = '" . (float)$product['height'] . "', `custom_product` = '" . (int)$product['custom_product'] . "', `manufacturer_id` = '" . (isset($product['manufacturer_id']) ? (int)$product['manufacturer_id'] : 0) . "'");
				$quote_product_id = $this->db->getLastId();
				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_option` SET `quote_id` = '" . (int)$quote_id . "', `quote_product_id` = '" . (int)$quote_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		} else {
			foreach ($quote_data['products'] as $product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_product` SET `quote_id` = '" . (int)$quote_id . "', `product_id` = '" . (int)$product['product_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '" . (float)$product['price'] . "', `total` = '" . (float)$product['total'] . "', `tax` = '" . (float)$product['tax'] . "', `sku` = '" . $this->db->escape($product['sku']) . "', `upc` = '" . $this->db->escape($product['upc']) . "', `location` = '" . $this->db->escape($product['location']) . "', `shipping` = '" . (int)$product['shipping'] . "', `image` = '" . $this->db->escape($product['image']) . "', `sort_order` = '" . (int)$product['sort_order'] . "', `tax_class_id` = '" . (int)$product['tax_class_id'] . "', `weight` = '" . (float)$product['weight'] / (int)$product['quantity'] . "', `weight_class_id` = '" . (int)$product['weight_class_id'] . "', `length` = '" . (float)$product['length'] . "', `length_class_id` = '" . (int)$product['length_class_id'] . "', `width` = '" . (float)$product['width'] . "', `height` = '" . (float)$product['height'] . "', `custom_product` = '" . (int)$product['custom_product'] . "', `manufacturer_id` = '" . (isset($product['manufacturer_id']) ? (int)$product['manufacturer_id'] : 0) . "'");
				$quote_product_id = $this->db->getLastId();
				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_option` SET `quote_id` = '" . (int)$quote_id . "', `quote_product_id` = '" . (int)$quote_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		if (isset($quote_data['totals'])) {
			foreach ($quote_data['totals'] as $total) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_total` SET `quote_id` = '" . (int)$quote_id . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
			}
		}
		if ($this->config->get('module_quote_system_subtract_stock') && !$quote_data['soft_quote']) {
			if (isset($quote_data['quoteproducts']) && !empty($quote_data['quoteproducts'])) {
				foreach($quote_data['quoteproducts'] as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `subtract` = '1'");
					foreach ($product['option'] as $option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}
			} else {
				foreach($quote_data['products'] as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `subtract` = '1'");
					foreach ($product['option'] as $option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}
			}
		}
		if (isset($this->session->data['quote_account_id'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_cart` WHERE `quote_account_id` = '" . (int)$this->session->data['quote_account_id'] . "'");
		}
		if (isset($this->session->data['quote_guest_id'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_cart` WHERE `quote_guest_id` = '" . (int)$this->session->data['quote_guest_id'] . "'");
		}
		if (isset($quote_data['order_status_id'])) {
			$this->addQuoteHistory($quote_id, $quote_data['order_status_id'], $quote_data['notify'], $quote_data['comment'], 0);
		} else {
			$this->addQuoteHistory($quote_id, $this->config->get('module_quote_system_ready_status_id'), $quote_data['notify'], $quote_data['comment'], 0);
		}
		return $quote_id;
	}

	public function editQuote($quote_id, $quote_data, $comment = '') {
		$this->db->query("UPDATE `" . DB_PREFIX . "oe_quote` SET `store_id` = '" . (int)$quote_data['store_id'] . "', `store_name` = '" . $this->db->escape($quote_data['store_name']) . "', `store_url` = '" . $this->db->escape($quote_data['store_url']) . "', `customer_id` = '" . (int)$quote_data['customer_id'] . "', `customer_group_id` = '" . (int)$quote_data['customer_group_id'] . "', `quote_account_id` = '" . (int)$quote_data['quote_account_id'] . "', `firstname` = '" . $this->db->escape($quote_data['firstname']) . "', `lastname` = '" . $this->db->escape($quote_data['lastname']) . "', `email` = '" . $this->db->escape($quote_data['email']) . "', `telephone` = '" . $this->db->escape($quote_data['telephone']) . "', `payment_firstname` = '" . $this->db->escape($quote_data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($quote_data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape($quote_data['payment_company']) . "', `payment_address_1` = '" . $this->db->escape($quote_data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($quote_data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($quote_data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($quote_data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape($quote_data['payment_country']) . "', `payment_country_id` = '" . (int)$quote_data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape($quote_data['payment_zone']) . "', `payment_zone_id` = '" . (int)$quote_data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape($quote_data['payment_address_format']) . "', `payment_custom_field` = '" . (isset($quote_data['payment_custom_field']) ? serialize($quote_data['payment_custom_field']) : '') . "', `shipping_firstname` = '" . $this->db->escape($quote_data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($quote_data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape($quote_data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape($quote_data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($quote_data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($quote_data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($quote_data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape($quote_data['shipping_country']) . "', `shipping_country_id` = '" . (int)$quote_data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape($quote_data['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$quote_data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape($quote_data['shipping_address_format']) . "', `shipping_custom_field` = '" . (isset($quote_data['shipping_custom_field']) ? serialize($quote_data['shipping_custom_field']) : '') . "', `shipping_method` = '" . $this->db->escape($quote_data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape($quote_data['shipping_code']) . "', `order_status_id` = '" . (int)$quote_data['order_status_id'] . "', `language_id` = '" . (int)$quote_data['language_id'] . "', `total` = '" . (float)$quote_data['total'] . "', `currency_id` = '" . (int)$quote_data['currency_id'] . "', `currency_code` = '" . $this->db->escape($quote_data['currency_code']) . "', `currency_value` = '" . (float)$quote_data['currency_value'] . "', `ip` = '" . $this->db->escape($quote_data['ip']) . "', `forwarded_ip` = '" . $this->db->escape($quote_data['forwarded_ip']) . "', `user_agent` = '" . $this->db->escape($quote_data['user_agent']) . "', `accept_language` = '" . $this->db->escape($quote_data['accept_language']) . "', `comment` = '" . $this->db->escape($quote_data['comment']) . "', `customer_added` = '1', `ref_no` = '" . (isset($quote_data['ref_no']) ? $this->db->escape($quote_data['ref_no']) : '') . "', `no_tax` = '" . (isset($quote_data['no_tax']) ? (int)$quote_data['no_tax'] : 0) . "', `pp_link` = '" . (isset($quote_data['pp_link']) ? (int)$quote_data['pp_link'] : '0') . "', `soft_quote` = '" . (isset($quote_data['soft_quote']) ? (int)$quote_data['soft_quote'] : 0) . "', `date_modified` = NOW() WHERE `quote_id` = '" . (int)$quote_id . "'");
		if ($this->config->get('module_quote_system_subtract_stock') && !$quote_data['soft_quote']) {
			$original_quote_products = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
			foreach ($original_quote_products->rows as $original_quote_product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` + " . (int)$original_quote_product['quantity'] . ") WHERE `product_id` = '" . (int)$original_quote_product['product_id'] . "' AND `subtract` = '1'");
				$original_quote_product_options = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$original_quote_product['quote_product_id'] . "'");
				foreach ($original_quote_product_options->rows as $original_quote_product_option) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` + " . (int)$original_quote_product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$original_quote_product_option['product_option_value_id'] . "' AND `subtract` = '1'");
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "'");
		if (isset($quote_data['quoteproducts']) && !empty($quote_data['quoteproducts'])) {
			foreach ($quote_data['quoteproducts'] as $product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_product` SET `quote_id` = '" . (int)$quote_id . "', `product_id` = '" . (int)$product['product_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '" . (float)$product['price'] . "', `total` = '" . (float)$product['total'] . "', `tax` = '" . (float)$product['tax'] . "', `sku` = '" . $this->db->escape($product['sku']) . "', `upc` = '" . $this->db->escape($product['upc']) . "', `location` = '" . $this->db->escape($product['location']) . "', `shipping` = '" . (int)$product['shipping'] . "', `image` = '" . $this->db->escape($product['image']) . "', `sort_order` = '" . (int)$product['sort_order'] . "', `tax_class_id` = '" . (int)$product['tax_class_id'] . "', `weight` = '" . (float)$product['weight'] / (int)$product['quantity'] . "', `weight_class_id` = '" . (int)$product['weight_class_id'] . "', `length` = '" . (float)$product['length'] . "', `length_class_id` = '" . (int)$product['length_class_id'] . "', `width` = '" . (float)$product['width'] . "', `height` = '" . (float)$product['height'] . "', `custom_product` = '" . (int)$product['custom_product'] . "', `manufacturer_id` = '" . (isset($product['manufacturer_id']) ? (int)$product['manufacturer_id'] : 0) . "'");
				$quote_product_id = $this->db->getLastId();
				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_option` SET `quote_id` = '" . (int)$quote_id . "', `quote_product_id` = '" . (int)$quote_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		} else {
			foreach ($quote_data['products'] as $product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_product` SET `quote_id` = '" . (int)$quote_id . "', `product_id` = '" . (int)$product['product_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '" . (float)$product['price'] . "', `total` = '" . (float)$product['total'] . "', `tax` = '" . (float)$product['tax'] . "', `sku` = '" . $this->db->escape($product['sku']) . "', `upc` = '" . $this->db->escape($product['upc']) . "', `location` = '" . $this->db->escape($product['location']) . "', `shipping` = '" . (int)$product['shipping'] . "', `image` = '" . $this->db->escape($product['image']) . "', `sort_order` = '" . (int)$product['sort_order'] . "', `tax_class_id` = '" . (int)$product['tax_class_id'] . "', `weight` = '" . (float)$product['weight'] / (int)$product['quantity'] . "', `weight_class_id` = '" . (int)$product['weight_class_id'] . "', `length` = '" . (float)$product['length'] . "', `length_class_id` = '" . (int)$product['length_class_id'] . "', `width` = '" . (float)$product['width'] . "', `height` = '" . (float)$product['height'] . "', `custom_product` = '" . (int)$product['custom_product'] . "', `manufacturer_id` = '" . (isset($product['manufacturer_id']) ? (int)$product['manufacturer_id'] : 0) . "'");
				$quote_product_id = $this->db->getLastId();
				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_option` SET `quote_id` = '" . (int)$quote_id . "', `quote_product_id` = '" . (int)$quote_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "'");
		if (isset($quote_data['totals'])) {
			foreach ($quote_data['totals'] as $total) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_total` SET `quote_id` = '" . (int)$quote_id . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
			}
		}
		if ($this->config->get('module_quote_system_subtract_stock') && !$quote_data['soft_quote']) {
			if (isset($quote_data['quoteproducts']) && !empty($quote_data['quoteproducts'])) {
				foreach ($quote_data['quoteproducts'] as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `subtract` = '1'");
					foreach ($product['option'] as $option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}
			} else {
				foreach ($quote_data['products'] as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `subtract` = '1'");
					foreach ($product['option'] as $option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` - " . (int)$product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}
			}
		}
		$this->addQuoteHistory($quote_id, $quote_data['order_status_id'], $quote_data['notify'], $comment, 1);
		return;
	}

	public function addQuoteHistory($quote_id, $order_status_id, $notify = 0, $comment = '', $add_edit = 0) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_history` SET `quote_id` = '" . (int)$quote_id . "', `order_status_id` = '" . (int)$order_status_id . "', `notify` = '" . (int)$notify . "', `comment` = '" . $this->db->escape($comment) . "', `date_added` = NOW()");
		$query = $this->db->query("SELECT `date_approved` FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		if ($order_status_id == $this->config->get('module_quote_system_ready_status_id') && ($query->row['date_approved'] == '0000-00-00 00:00:00' || $query->row['date_approved'] == '')) {
			$this->db->query("UPDATE `" . DB_PREFIX . "oe_quote` SET `date_approved` = NOW() WHERE `quote_id` = '" . (int)$quote_id . "'");
		}
		if ($notify) {
			if ($this->config->get('module_quote_system_ready_status_id') == $order_status_id) {
				$this->sendCustomerEmail($quote_id, $notify, $comment);
			}
			if (in_array('order', (array)$this->config->get('config_mail_alert')) && !$add_edit) {
				$this->sendAdminEmail($quote_id, $notify, $comment);
			}
		}
		return;
	}
	
	public function sendCustomerEmail($quote_id, $notify, $comment) {
		$quote_info = $this->getQuote($quote_id);
		$this->load->model('localisation/language');
		$language_info = $this->model_localisation_language->getLanguage($quote_info['language_id']);
		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}
		$language = new Language($language_code);
		$language->load($language_code);
		$language->load('extension/mail/quote_system');
		$quote_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$quote_info['order_status_id'] . "' AND language_id = '" . (int)$quote_info['language_id'] . "'");
		if ($quote_status_query->num_rows) {
			$quote_status = $quote_status_query->row['name'];
		} else {
			$quote_status = '';
		}
		$subject = sprintf($language->get('text_quote_subject'), html_entity_decode($quote_info['store_name'], ENT_QUOTES, 'UTF-8'), $quote_id);
		$data = array();
		$data['title'] = sprintf($language->get('text_quote_subject'), $quote_info['store_name'], $quote_id);
		if (isset($this->session->data['oe']['order_entry'])) {
			if ($this->config->get('module_quote_system_quote_expire')) {
				$data['text_greeting'] = sprintf($language->get('text_quote_greeting_admin_exp'), $this->config->get('module_quote_system_quote_expire'));
			} else {
				$data['text_greeting'] = $language->get('text_quote_greeting_admin');
			}
		} else {
			$data['text_greeting'] = $language->get('text_quote_greeting_catalog');
		}
		$data['text_link'] = $language->get('text_quote_link');
		$data['text_quote_detail'] = $language->get('text_quote_detail');
		$data['text_instruction'] = $language->get('text_quote_instruction');
		$data['text_quote_id'] = $language->get('text_quote_id');
		$data['text_date_added'] = $language->get('text_quote_date_added');
		$data['text_email'] = $language->get('text_quote_email');
		$data['text_telephone'] = $language->get('text_quote_telephone');
		$data['text_ip'] = $language->get('text_quote_ip');
		$data['text_quote_status'] = $language->get('text_quote_status');
		if (isset($this->session->data['customer']['password'])) {
			$data['text_account'] = sprintf($language->get('text_account'), $this->session->data['customer']['email'], $this->session->data['customer']['password']);
		} else {
			$data['text_account'] = '';
		}
		$data['text_product'] = $language->get('text_quote_product');
		$data['text_model'] = $language->get('text_quote_model');
		$data['text_quantity'] = $language->get('text_quote_quantity');
		$data['text_price'] = $language->get('text_quote_price');
		$data['text_total'] = $language->get('text_quote_total');
		$data['text_footer'] = $language->get('text_quote_footer');
		$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
		$data['link'] = $quote_info['store_url'] . 'index.php?route=extension/account/quote_system/info&squote_id=' . $quote_id;
		$data['store_name'] = $quote_info['store_name'];
		$data['store_url'] = $quote_info['store_url'];
		$data['customer_id'] = $quote_info['customer_id'];
		$data['quote_id'] = $quote_id;
		$data['date_added'] = date($language->get('date_format_short'), strtotime($quote_info['date_added']));
		$data['email'] = $quote_info['email'];
		$data['telephone'] = $quote_info['telephone'];
		$data['ip'] = $quote_info['ip'];
		$data['quote_status'] = $quote_status;
		if ($comment && $notify) {
			$data['comment'] = nl2br($comment);
		} else {
			$data['comment'] = '';
		}
		$quote_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->load->model('tool/upload');
		$this->load->model('tool/image');
		$data['products'] = array();
		foreach ($quote_product_query->rows as $product) {
			$option_data = array();
			$quote_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$product['quote_product_id'] . "'");
			$image = $this->model_tool_image->resize($product['image'], 60, 60);
			foreach ($quote_option_query->rows as $option) {
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
				'image'			=> $image,
				'sort_order'	=> $product['sort_order'],
				'option'		=> $option_data,
				'quantity'		=> $product['quantity'],
				'price'			=> $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quote_info['currency_code'], $quote_info['currency_value']),
				'total'			=> $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value'])
			);
		}
		$sort_order = array();
		foreach ($data['products'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $data['products']);
		$data['totals'] = array();
		$quote_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "' ORDER BY sort_order ASC");
		if ($quote_total_query->num_rows) {
			foreach ($quote_total_query->rows as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $quote_info['currency_code'], $quote_info['currency_value']),
				);
			}
		}
		$html = $this->load->view('extension/mail/quote_system', $data);
		if (isset($this->session->data['oe']['order_entry'])) {
			$text = $language->get('text_quote_greeting_admin') . "\n\n";
		} else {
			$text = $language->get('text_quote_greeting_catalog') . "\n\n";
		}
		$text .= $language->get('text_quote_link') . "\n\n";
		$text .= $quote_info['store_url'] . 'index.php?route=extension/account/quote_system/info&squote_id=' . $quote_id . "\n\n";
		$text .= $language->get('text_quote_id') . ' ' . $quote_id . "\n";
		$text .= $language->get('text_quote_date_added') . ' ' . date($language->get('date_format_short'), strtotime($quote_info['date_added'])) . "\n";
		$text .= $language->get('text_quote_status') . ' ' . $quote_status . "\n\n";
		if ($comment && $notify) {
			$text .= $language->get('text_quote_instruction') . "\n\n";
			$text .= $comment . "\n\n";
		}
		$text .= $language->get('text_quote_products') . "\n";
		foreach ($quote_product_query->rows as $product) {
			$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
			$quote_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . $product['quote_product_id'] . "'");
			foreach ($quote_option_query->rows as $option) {
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
				$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
			}
		}
		if ($quote_total_query->num_rows) {
			$text .= "\n";
			$text .= $language->get('text_quote_totals') . "\n";
			foreach ($quote_total_query->rows as $total) {
				$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $quote_info['currency_code'], $quote_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
			}
		}
		if ($quote_info['comment']) {
			$text .= $language->get('text_quote_comment') . "\n\n";
			$text .= $quote_info['comment'] . "\n\n";
		}
		$text .= $language->get('text_quote_footer') . "\n\n";
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($quote_info['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($quote_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html);
		$mail->setText($text);
		$mail->send();
		return;
	}

	public function sendAdminEmail($quote_id, $notify, $comment) {
		$quote_info = $this->getQuote($quote_id);
		$this->load->model('localisation/language');
		$language_info = $this->model_localisation_language->getLanguage($quote_info['language_id']);
		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}
		$language = new Language($language_code);
		$language->load($language_code);
		$language->load('extension/mail/quote_system');
		$quote_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . $quote_info['order_status_id'] . "' AND `language_id` = '" . (int)$quote_info['language_id'] . "'");
		if ($quote_status_query->num_rows) {
			$quote_status = $quote_status_query->row['name'];
		} else {
			$quote_status = '';
		}
		$subject = sprintf($language->get('text_quote_subject'), html_entity_decode($quote_info['store_name'], ENT_QUOTES, 'UTF-8'), $quote_id);
		$text  = $language->get('text_quote_received') . "\n\n";
		$text .= $language->get('text_quote_id') . ' ' . $quote_id . "\n";
		$text .= $language->get('text_quote_date_added') . ' ' . date($language->get('date_format_short'), strtotime($quote_info['date_added'])) . "\n";
		$text .= $language->get('text_quote_status') . ' ' . $quote_status . "\n\n";
		$text .= $language->get('text_quote_products') . "\n";
		$quote_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		foreach ($quote_product_query->rows as $product) {
			$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ')' . "\n";
			$quote_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . $product['quote_product_id'] . "'");
			foreach ($quote_option_query->rows as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
				}
				$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
			}
		}
		$quote_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "' ORDER BY sort_order ASC");
		if ($quote_total_query->num_rows) {
			$text .= "\n";
			$text .= $language->get('text_quote_totals') . "\n";
			foreach ($quote_total_query->rows as $total) {
				$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $quote_info['currency_code'], $quote_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
			}
		}
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($quote_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText($text);
		$mail->send();
		return;
	}

	public function buy() {
		$this->load->language('extension/account/quote_system');
		$this->load->model('extension/account/quote_system');
		$this->cart->clear();
		$this->cart->clearquote();
		$this->session->data['quote_id'] = $this->request->post['quote_id'];
		$quote_info = $this->model_extension_account_quote_system->getQuote($this->request->post['quote_id']);
		if (isset($quote_info['no_tax']) && $quote_info['no_tax']) {
			$this->session->data['oe']['no_tax'] = 1;
		}
		$quote_totals = $this->model_extension_account_quote_system->getQuoteTotals($this->request->post['quote_id']);
		foreach ($quote_totals as $quote_total) {
			if ($quote_total['code'] == 'shipping') {
				if (isset($quote_info['shipping_code']) && $quote_info['shipping_code'] == 'oe_custom_shipping.oe_custom_shipping') {
					$this->session->data['oe']['custom_shipping']['title'] = $quote_total['title'];
					$this->session->data['oe']['custom_shipping']['cost'] = $quote_total['value'];
				}
			}
		}
		$quote_products = $this->model_extension_account_quote_system->getQuoteProducts($this->request->post['quote_id']);
		$this->load->model('catalog/product');
		foreach ($quote_products as $quote_product) {
			$quote_options = $this->model_extension_account_quote_system->getQuoteOptions($this->request->post['quote_id'], $quote_product['quote_product_id']);
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
					'tax_class_id'		=> $this->config->get('custom_products_tax_class_id'),
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
		if ($this->cart->hasProducts()) {
			$json['success'] = $this->language->get('text_success');
			$this->load->model('extension/extension');
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array();
				$results = $this->model_extension_extension->getExtensions('total');
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}
				array_multisort($sort_order, SORT_ASC, $results);
				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);
						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}
				}
				$sort_order = array();
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
				array_multisort($sort_order, SORT_ASC, $total_data);
			}
			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		} else {
			$json['redirect'] = str_replace('&amp;', '&', $this->url->link('extension/account/quote_system/info', 'quote_id=' . $this->request->post['quote_id']));
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getQuote($quote_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return $query->row;
	}

	public function getQuoteProducts($quote_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return $query->rows;
	}

	public function getQuoteOptions($quote_id, $quote_product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$quote_product_id . "'");
		return $query->rows;
	}

}

?>