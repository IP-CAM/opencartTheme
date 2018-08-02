<?php

class ModelExtensionSaleQuoteSystem extends Model {

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

	public function editQuote($quote_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "oe_quote` SET `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "', `date_modified` = NOW() WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_quote_history` SET `quote_id` = '" . (int)$quote_id . "', `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "', `notify` = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `date_added` = NOW()");
		foreach ($data['price'] as $quote_product_id => $price) {
			$quantity = $data['quantity'][$quote_product_id];
			$total = $quantity * $price;
			$this->db->query("UPDATE `" . DB_PREFIX . "oe_quote_product` SET `price` = '" . (float)$price . "', `total` = '" . (float)$total . "' WHERE `quote_product_id` = '" . (int)$quote_product_id . "'");
		}
		return;
	}

	public function deleteQuote($quote_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		if ($this->config->get('module_quote_system_subtract_stock')) {
			$product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
			foreach ($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` + " . (int)$product['quantity'] . ") WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `subtract` = '1'");
				$option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$product['quote_product_id'] . "'");
				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` + " . (int)$product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "' AND `subtract` = '1'");
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_history` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return;
	}

	public function getPendingQuotes($data = array()) {
		$return_data = array();
		$sql = "SELECT quote_id, CONCAT(firstname, ' ', lastname) AS customer, date_added FROM `" . DB_PREFIX . "oe_quote` WHERE `order_status_id` = '" . (int)$this->config->get('module_quote_system_request_status_id') . "'";
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND quote_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		$sort_data = array(
			'o.order_id',
			'customer',
			'o.date_added',
			'o.date_modified',
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'o.order_id') {
				$sql .= " ORDER BY quote_id";
			} elseif ($data['sort'] == 'customer') {
				$sql .= " ORDER BY customer";
			} elseif ($data['sort'] == 'o.date_added') {
				$sql .= " ORDER BY date_added";
			} else {
				$sql .= " ORDER BY date_modified";
			}
		} else {
			$sql .= " ORDER BY quote_id";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start2']) || isset($data['limit'])) {
			if ($data['start2'] < 0) {
				$data['start2'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start2'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$product_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
				$return_data[] = array(
					'quote_id'		=> $row['quote_id'],
					'customer'		=> $row['customer'],
					'products'		=> $product_query->row['total'],
					'date_added'	=> $row['date_added']					
				);
			}
		}
		return $return_data;
	}

	public function getTotalPendingQuotes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `order_status_id` = '" . (int)$this->config->get('module_quote_system_request_status_id') . "'";
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND quote_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getReadyQuotes($data = array()) {
		$return_data = array();
		$sql = "SELECT quote_id, CONCAT(firstname, ' ', lastname) AS customer, currency_code, currency_value, total, date_added, date_modified FROM `" . DB_PREFIX . "oe_quote` WHERE `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "'";
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND quote_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
		$sort_data = array(
			'o.order_id',
			'customer',
			'o.date_added',
			'o.date_modified',
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'o.order_id') {
				$sql .= " ORDER BY quote_id";
			} elseif ($data['sort'] == 'customer') {
				$sql .= " ORDER BY customer";
			} elseif ($data['sort'] == 'o.date_added') {
				$sql .= " ORDER BY date_added";
			} else {
				$sql .= " ORDER BY date_modified";
			}
		} else {
			$sql .= " ORDER BY quote_id";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start3']) || isset($data['limit'])) {
			if ($data['start3'] < 0) {
				$data['start3'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start3'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$row['quote_id'] . "'");
				$products = 0;
				foreach ($product_query->rows as $row2) {
					$products++;
				}
				$return_data[] = array(
					'quote_id'			=> $row['quote_id'],
					'customer'			=> $row['customer'],
					'currency_code'		=> $row['currency_code'],
					'currency_value'	=> $row['currency_value'],
					'products'			=> $products,
					'total'				=> $row['total'],
					'date_added'		=> $row['date_added'],
					'date_modified'		=> $row['date_modified']
				);
			}
		}
		return $return_data;
	}

	public function getTotalReadyQuotes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "'";
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND quote_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
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

	public function getQuoteTotals($quote_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "' ORDER BY sort_order");
		return $query->rows;
	}

	public function getQuoteStatuses() {
		$query = $this->db->query("SELECT * FROM `". DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' AND (`order_status_id` = '" . (int)$this->config->get('module_quote_system_request_status_id') . "' OR `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "')");
		return $query->rows;
	}

	public function delete($quote_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_history` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return;
	}

	public function convertQuote($quote_id) {
		$quote_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		$order_status_id = $this->config->get('module_order_entry_order_status');
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET `store_id` = '" . (int)$quote_query->row['store_id'] . "', `store_name` = '" . $this->db->escape($quote_query->row['store_name']) . "', `store_url` = '" . $this->db->escape($quote_query->row['store_url']) . "', `customer_id` = '" . (int)$quote_query->row['customer_id'] . "', `customer_group_id` = '" . (int)$quote_query->row['customer_group_id'] . "', `firstname` = '" . $this->db->escape($quote_query->row['firstname']) . "', `lastname` = '" . $this->db->escape($quote_query->row['lastname']) . "', `email` = '" . $this->db->escape($quote_query->row['email']) . "', `telephone` = '" . $this->db->escape($quote_query->row['telephone']) . "', `payment_firstname` = '" . $this->db->escape($quote_query->row['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($quote_query->row['payment_lastname']) . "', `payment_company` = '" . $this->db->escape($quote_query->row['payment_company']) . "', `payment_address_1` = '" . $this->db->escape($quote_query->row['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($quote_query->row['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($quote_query->row['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($quote_query->row['payment_postcode']) . "', `payment_country` = '" . $this->db->escape($quote_query->row['payment_country']) . "', `payment_country_id` = '" . (int)$quote_query->row['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape($quote_query->row['payment_zone']) . "', `payment_zone_id` = '" . (int)$quote_query->row['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape($quote_query->row['payment_address_format']) . "', `payment_custom_field` = '" . (isset($quote_query->row['payment_custom_field']) ? serialize($quote_query->row['payment_custom_field']) : '') . "', `shipping_firstname` = '" . $this->db->escape($quote_query->row['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($quote_query->row['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape($quote_query->row['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape($quote_query->row['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($quote_query->row['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($quote_query->row['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($quote_query->row['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape($quote_query->row['shipping_country']) . "', `shipping_country_id` = '" . (int)$quote_query->row['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape($quote_query->row['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$quote_query->row['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape($quote_query->row['shipping_address_format']) . "', `shipping_custom_field` = '" . (isset($quote_query->row['shipping_custom_field']) ? serialize($quote_query->row['shipping_custom_field']) : '') . "', `shipping_method` = '" . $this->db->escape($quote_query->row['shipping_method']) . "', `shipping_code` = '" . $this->db->escape($quote_query->row['shipping_code']) . "', `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$quote_query->row['language_id'] . "', `total` = '" . (float)$quote_query->row['total'] . "', `currency_id` = '" . (int)$quote_query->row['currency_id'] . "', `currency_code` = '" . $this->db->escape($quote_query->row['currency_code']) . "', `currency_value` = '" . (float)$quote_query->row['currency_value'] . "', `ip` = '" . $this->db->escape($quote_query->row['ip']) . "', `forwarded_ip` = '" . $this->db->escape($quote_query->row['forwarded_ip']) . "', `user_agent` = '" . $this->db->escape($quote_query->row['user_agent']) . "', `accept_language` = '" . $this->db->escape($quote_query->row['accept_language']) . "', `comment` = '" . $this->db->escape($quote_query->row['comment']) . "', `date_added` = NOW()");
		$order_id = $this->db->getLastId();
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order` SET `order_id` = '" . (int)$order_id . "', `ref_no` = '" . (isset($quote_query->row['ref_no']) ? $this->db->escape($quote_query->row['ref_no']) : '') . "', `no_tax` = '" . (isset($quote_query->row['no_tax']) ? (int)$quote_query->row['no_tax'] : 0) . "', `pp_link` = '" . (isset($quote_query->row['pp_link']) ? (int)$quote_query->row['pp_link'] : '0') . "'");
		$oe_order_id = $this->db->getLastId();
		if ($this->config->get('module_sales_agents_status')) {
			$this->db->query("UPDATE `" . DB_PREFIX . "oe_order` SET `sales_agent_id` = '" . (isset($quote_query->row['sales_agent_id']) ? (int)$quote_query->row['sales_agent_id'] : 0) . "' WHERE `oe_order_id` = '" . (int)$oe_order_id . "'");
		}
		$quote_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		foreach ($quote_product_query->rows as $quote_product) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET `order_id` = '" . (int)$order_id . "', `product_id` = '" . (int)$quote_product['product_id'] . "', `name` = '" . $this->db->escape($quote_product['name']) . "', `model` = '" . $this->db->escape($quote_product['model']) . "', `quantity` = '" . (int)$quote_product['quantity'] . "', `price` = '" . (float)$quote_product['price'] . "', `total` = '" . (float)$quote_product['total'] . "', `tax` = '" . (float)$quote_product['tax'] . "'");
			$order_product_id = $this->db->getLastId();
			$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order_product` SET `oe_order_id` = '" . (int)$oe_order_id . "', `order_product_id` = '" . (int)$order_product_id . "', `order_id` = '" . (int)$order_id . "', `product_id` = '" . (int)$quote_product['product_id'] . "', `image` = '" . $this->db->escape($quote_product['image']) . "', `notax` = '" . (int)$quote_product['notax'] . "', `shipping` = '" . (int)$quote_product['shipping'] . "'");
			$oe_order_product_id = $this->db->getLastId();
			if ($this->config->get('module_custom_products_status')) {
				$this->db->query("UPDATE `" . DB_PREFIX . "oe_order_product` SET `custom_product` = '" . (int)$quote_product['custom_product'] . "', `sort_order` = '" . (int)$quote_product['sort_order'] . "', `sku` = '" . $this->db->escape($quote_product['sku']) . "', `upc` = '" . $this->db->escape($quote_product['upc']) . "', `location` = '" . $this->db->escape($quote_product['location']) . "', `tax_class_id` = '" . (int)$quote_product['tax_class_id'] . "', `weight` = '" . (float)$quote_product['weight'] . "', `weight_class_id` = '" . (int)$quote_product['weight_class_id'] . "', `length` = '" . (float)$quote_product['length'] . "', `length_class_id` = '" . (int)$quote_product['length_class_id'] . "', `width` = '" . (float)$quote_product['width'] . "', `height` = '" . (float)$quote_product['height'] . "', `manufacturer_id` = '" . (int)$quote_product['manufacturer_id'] . "'");
			}
			$quote_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_option` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$quote_product['quote_product_id'] . "'");
			if ($quote_option_query->num_rows) {
				foreach ($quote_option_query->rows as $quote_option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` SET `order_id` = '" . (int)$order_id . "', `order_product_id` = '" . (int)$order_product_id . "', `product_option_id` = '" . (int)$quote_option['product_option_id'] . "', `product_option_value_id` = '" . (int)$quote_option['product_option_value_id'] . "', `name` = '" . $this->db->escape($quote_option['name']) . "', `value` = '" . $this->db->escape($quote_option['value']) . "', `type` = '" . $this->db->escape($quote_option['type']) . "'");
				}
			}
		}
		if ($this->config->get('total_optfee_status') && $this->config->get('total_optdisc_status')) {
			$this->db->query("UPDATE `" . DB_PREFIX . "oe_optfeedisc` SET `order_id` = '" . (int)$order_id . "' WHERE `quote_id` = '" . (int)$quote_id . "'");
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET `order_id` = '" . (int)$order_id . "', `order_status_id` = '" . (int)$order_status_id . "', `comment` = 'Converted from a quote', `date_added` = NOW()");
		$quote_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "'");
		foreach ($quote_total_query->rows as $quote_total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($quote_total['code']) . "', `title` = '" . $this->db->escape($quote_total['title']) . "', `value` = '" . (float)$quote_total['value'] . "', `sort_order` = '" . (int)$quote_total['sort_order'] . "'");
		}
		if ($order_id && $oe_order_id) {
			$this->delete($quote_id);
			return 1;
		} else {
			return 0;
		}
	}

	public function sendCustomerEmail($quote_id, $notify, $comment) {
		$quote_info = $this->getQuote($quote_id);
		$this->load->model('localisation/language');
		$language_info = $this->model_localisation_language->getLanguage($quote_info['language_id']);
		if ($language_info) {
			$language_code = $language_info['code'];
			$language_directory = $language_info['directory'];
		} else {
			$language_code = '';
			$language_directory = '';
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
		if ($this->config->get('module_quote_system_quote_expire')) {
			$data['text_greeting'] = sprintf($language->get('text_quote_greeting_admin_exp'), $this->config->get('module_quote_system_quote_expire'));
		} else {
			$data['text_greeting'] = $language->get('text_quote_greeting_admin');
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
		$data['logo'] = HTTPS_CATALOG . 'image/' . $this->config->get('config_logo');
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

	public function updateTables() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'ref_no'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN ref_no varchar(40) COLLATE utf8_general_ci NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'no_tax'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN no_tax tinyint(1) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'total'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN total decimal(15,4) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'date_approved'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN date_approved datetime NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'pp_link'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN pp_link tinyint(1) NOT NULL DEFAULT 0");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote_product` LIKE 'manufacturer_id'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_product` ADD COLUMN manufacturer_id int(11) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote_product` LIKE 'sort_order'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_product` ADD COLUMN sort_order int(11) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'soft_quote'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN soft_quote tinyint(1) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote_account` LIKE 'company'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN company varchar(40) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN address_1 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN address_2 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN city varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN postcode varchar(10) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN country varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN country_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN zone varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN zone_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote_account` ADD COLUMN address_format text COLLATE utf8_general_ci NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'payment_firstname'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_firstname varchar(32) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_lastname varchar(32) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_company varchar(40) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_address_1 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_address_2 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_city varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_postcode varchar(10) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_country varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_country_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_zone varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_zone_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_address_format text COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN payment_custom_field text COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_firstname varchar(32) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_lastname varchar(32) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_company varchar(40) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_address_1 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_address_2 varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_city varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_postcode varchar(10) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_country varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_country_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_zone varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_zone_id int(11) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_address_format text COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_custom_field text COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_method varchar(128) COLLATE utf8_general_ci NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN shipping_code varchar(128) COLLATE utf8_general_ci NOT NULL");
		}
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_total` (
			`quote_total_id` int(11) NOT NULL auto_increment,
			`quote_id` int(11) NOT NULL,
			`code` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`title` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`value` decimal(15,4) NOT NULL,
			`sort_order` int(3) NOT NULL,
			PRIMARY KEY (`quote_total_id`)
		);");
		return;
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote` (
			`quote_id` int(11) NOT NULL auto_increment,
			`store_id` int(11) NOT NULL,
			`store_name` varchar(64) COLLATE utf8_general_ci NOT NULL,
			`store_url` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`customer_id` int(11) NOT NULL,
			`customer_group_id` int(11) NOT NULL,
			`quote_account_id` int(11) NOT NULL,
			`firstname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`lastname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`email` varchar(96) COLLATE utf8_general_ci NOT NULL,
			`telephone` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`payment_firstname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`payment_lastname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`payment_company` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`payment_address_1` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`payment_address_2` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`payment_city` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`payment_postcode` varchar(10) COLLATE utf8_general_ci NOT NULL,
			`payment_country` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`payment_country_id` int(11) NOT NULL,
			`payment_zone` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`payment_zone_id` int(11) NOT NULL,
			`payment_address_format` text COLLATE utf8_general_ci NOT NULL,
			`payment_custom_field` text COLLATE utf8_general_ci NOT NULL,
			`shipping_firstname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`shipping_lastname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`shipping_company` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`shipping_address_1` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_address_2` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_city` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_postcode` varchar(10) COLLATE utf8_general_ci NOT NULL,
			`shipping_country` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_country_id` int(11) NOT NULL,
			`shipping_zone` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_zone_id` int(11) NOT NULL,
			`shipping_address_format` text COLLATE utf8_general_ci NOT NULL,
			`shipping_custom_field` text COLLATE utf8_general_ci NOT NULL,
			`shipping_method` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping_code` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`order_status_id` int(11) NOT NULL,
			`language_id` int(11) NOT NULL,
			`total` decimal(15,4) NOT NULL,
			`currency_id` int(11) NOT NULL,
			`currency_code` varchar(3) COLLATE utf8_general_ci NOT NULL,
			`currency_value` decimal(15,8) NOT NULL,
			`ip` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`forwarded_ip` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`user_agent` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`accept_language` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`comment` text COLLATE utf8_general_ci NOT NULL,
			`customer_added` tinyint(1) NOT NULL,
			`payment_method` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`ref_no` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`no_tax` tinyint(1) NOT NULL,
			`pp_link` tinyint(1) NOT NULL DEFAULT 0,
			`soft_quote` tinyint(1) NOT NULL,
			`date_added` datetime NOT NULL,
			`date_approved` datetime NOT NULL,
			`date_modified` datetime NOT NULL,
			PRIMARY KEY (`quote_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_account` (
			`quote_account_id` int(11) NOT NULL auto_increment,
			`firstname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`lastname` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`email` varchar(96) COLLATE utf8_general_ci NOT NULL,
			`telephone` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`customer_group_id` int(11) NOT NULL,
			`salt` varchar(9) COLLATE utf8_general_ci NOT NULL,
			`password` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`custom_field` text COLLATE utf8_general_ci NOT NULL,
			`company` varchar(40) COLLATE utf8_general_ci NOT NULL,
			`address_1` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`address_2` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`city` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`postcode` varchar(10) COLLATE utf8_general_ci NOT NULL,
			`country` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`country_id` int(11) NOT NULL,
			`zone` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`zone_id` int(11) NOT NULL,
			`address_format` text COLLATE utf8_general_ci NOT NULL,
			PRIMARY KEY (`quote_account_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_cart` (
			`quote_cart_id` int(11) NOT NULL auto_increment,
			`customer_id` int(11) NOT NULL,
			`quote_account_id` int(11) NOT NULL,
			`quote_guest_id` int(11) NOT NULL,
			`session_id` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`product_id` int(11) NOT NULL,
			`recurring_id` int(11) NOT NULL,
			`option` text COLLATE utf8_general_ci NOT NULL,
			`quantity` int(5) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`quote_cart_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_history` (
			`quote_history_id` int(11) NOT NULL auto_increment,
			`quote_id` int(11) NOT NULL,
			`order_status_id` int(11) NOT NULL,
			`notify` tinyint(1) NOT NULL,
			`comment` text COLLATE utf8_general_ci NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`quote_history_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_option` (
			`quote_option_id` int(11) NOT NULL auto_increment,
			`quote_id` int(11) NOT NULL,
			`quote_product_id` int(11) NOT NULL,
			`product_option_id` int(11) NOT NULL,
			`product_option_value_id` int(11) NOT NULL,
			`name` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`value` text COLLATE utf8_general_ci NOT NULL,
			`type` varchar(32) COLLATE utf8_general_ci NOT NULL,
			PRIMARY KEY (`quote_option_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_product` (
			`quote_product_id` int(11) NOT NULL auto_increment,
			`quote_id` int(11) NOT NULL,
			`product_id` int(11) NOT NULL,
			`name` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`model` varchar(64) COLLATE utf8_general_ci NOT NULL,
			`quantity` int(4) NOT NULL,
			`price` decimal(15,4) NOT NULL,
			`total` decimal(15,4) NOT NULL,
			`tax` decimal(15,4) NOT NULL,
			`sku` varchar(64) COLLATE utf8_general_ci NOT NULL,
			`upc` varchar(12) COLLATE utf8_general_ci NOT NULL,
			`location` varchar(128) COLLATE utf8_general_ci NOT NULL,
			`shipping` tinyint(1) NOT NULL,
			`image` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`tax_class_id` int(11) NOT NULL,
			`weight` decimal(15,8) NOT NULL,
			`weight_class_id` int(11) NOT NULL,
			`length` decimal(15.8) NOT NULL,
			`length_class_id` int(11) NOT NULL,
			`width` decimal(15,8) NOT NULL,
			`height` decimal(15,8) NOT NULL,
			`notax` tinyint(1) NOT NULL,
			`custom_product` tinyint(1) NOT NULL,
			`manufacturer_id` int(11) NOT NULL,
			`sort_order` int(11) NOT NULL,
			PRIMARY KEY (`quote_product_id`)
		);");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_quote_total` (
			`quote_total_id` int(11) NOT NULL auto_increment,
			`quote_id` int(11) NOT NULL,
			`code` varchar(32) COLLATE utf8_general_ci NOT NULL,
			`title` varchar(255) COLLATE utf8_general_ci NOT NULL,
			`value` decimal(15,4) NOT NULL,
			`sort_order` int(3) NOT NULL,
			PRIMARY KEY (`quote_total_id`)
		);");
		if ($this->config->get('module_sales_agents_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'sales_agent_id'");
			if ($query->num_rows < 1) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN sales_agent_id int(11) NOT NULL");
			}
		}
		if ($this->config->get('module_order_entry_status')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_modules` SET `module_name` = 'Quote System', `module_code` = 'quote_system'");
		}
		return;
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_account`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_cart`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_history`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_option`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_product`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_quote_total`");
		if ($this->config->get('module_order_entry_status')) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_modules` WHERE `module_code` = 'quote_system'");
		}
		return;
	}

	public function uninstall2() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = 'module' AND `code` = 'quote_system'");
		return;
	}

}

?>