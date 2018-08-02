<?php

class ModelExtensionAccountQuoteSystem extends Model {

	public function getQuote($quote_id) {
		$found = 0;
		if ($this->customer->isLogged()) {
			$quote_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");
			$found = 1;
		} elseif (isset($this->session->data['quote_account_id'])) {
			$quote_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_account_id` = '" . (int)$this->session->data['quote_account_id'] . "'");
			$found = 1;
		} 
		if ($found && $quote_query->num_rows) {
			return $quote_query->row;
		} else {
			return false;
		}
	}

	public function getQuoteRequests($start = 0, $limit = 20) {
		$found = 0;
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 1;
		}
		if ($this->customer->isLogged()) {
			$query = $this->db->query("SELECT q.quote_id, q.firstname, q.lastname, os.name as status, q.date_added, q.currency_code, q.currency_value FROM `" . DB_PREFIX . "oe_quote` q LEFT JOIN `" . DB_PREFIX . "order_status` os ON (q.order_status_id = os.order_status_id) WHERE q.customer_id = '" . (int)$this->customer->getId() . "' AND q.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND q.order_status_id = '" . (int)$this->config->get('module_quote_system_request_status_id') . "' ORDER BY q.quote_id DESC LIMIT " . (int)$start . "," . (int)$limit);
			$found = 1;
		} elseif (isset($this->session->data['quote_account_id'])) {
			$query = $this->db->query("SELECT q.quote_id, q.firstname, q.lastname, os.name as status, q.date_added, q.currency_code, q.currency_value FROM `" . DB_PREFIX . "oe_quote` q LEFT JOIN `" . DB_PREFIX . "order_status` os ON (q.order_status_id = os.order_status_id) WHERE q.quote_account_id = '" . (int)$this->session->data['quote_account_id'] . "' AND q.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND q.order_status_id = '"  . (int)$this->config->get('module_quote_system_request_status_id') . "' ORDER BY q.quote_id DESC LIMIT " . (int)$start . "," . (int)$limit);
			$found = 1;
		}
		if ($found) {
			return $query->rows;
		} else {
			return false;
		}
	}

	public function getQuoteReady($start = 0, $limit = 20) {
		$found = 0;
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 1;
		}
		if ($this->customer->isLogged()) {
			$query = $this->db->query("SELECT q.quote_id, q.firstname, q.lastname, os.name as status, q.date_added, q.currency_code, q.currency_value FROM `" . DB_PREFIX . "oe_quote` q LEFT JOIN `" . DB_PREFIX . "order_status` os ON (q.order_status_id = os.order_status_id) WHERE q.customer_id = '" . (int)$this->customer->getId() . "' AND q.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND q.order_status_id = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "' ORDER BY q.quote_id DESC LIMIT " . (int)$start . "," . (int)$limit);
			$found = 1;
		} elseif (isset($this->session->data['quote_account_id'])) {
			$query = $this->db->query("SELECT q.quote_id, q.firstname, q.lastname, os.name as status, q.date_added, q.currency_code, q.currency_value FROM `" . DB_PREFIX . "oe_quote` q LEFT JOIN `" . DB_PREFIX . "order_status` os ON (q.order_status_id = os.order_status_id) WHERE q.quote_account_id = '" . (int)$this->session->data['quote_account_id'] . "' AND q.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND q.order_status_id = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "' ORDER BY q.quote_id DESC LIMIT " . (int)$start . "," . (int)$limit);
			$found = 1;
		}
		if ($found) {
			return $query->rows;
		} else {
			return false;
		}
	}

	public function getQuoteProduct($quote_id, $quote_product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "' AND `quote_product_id` = '" . (int)$quote_product_id . "'");
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
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "' ORDER BY sort_order ASC");
		return $query->rows;
	}

	public function getQuoteHistories($quote_id) {
		$query = $this->db->query("SELECT qh.date_added, os.name AS status, qh.comment, qh.notify FROM `" . DB_PREFIX . "oe_quote_history` qh LEFT JOIN `" . DB_PREFIX . "order_status` os ON qh.order_status_id = os.order_status_id WHERE qh.quote_id = '" . (int)$quote_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY qh.date_added");
		return $query->rows;
	}

	public function getTotalQuoteRequests() {
		$found = 0;
		if ($this->customer->isLogged()) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `order_status_id` = '" . (int)$this->config->get('module_quote_system_request_status_id') . "'");
		} elseif (isset($this->session->data['quote_account_id'])) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_account_id` = '" . (int)$this->session->data['quote_account_id'] . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `order_status_id` = '" . (int)$this->config->get('module_quote_system_request_status_id') . "'");
		}
		return $query->row['total'];
	}

	public function getTotalQuoteReady() {
		$found = 0;
		if ($this->customer->isLogged()) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "'");
		} elseif (isset($this->session->data['quote_account_id'])) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_account_id` = '" . (int)$this->session->data['quote_account_id'] . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `order_status_id` = '" . (int)$this->config->get('module_quote_system_ready_status_id') . "'");
		}
		return $query->row['total'];
	}

	public function getTotalQuoteProductsByQuoteId($quote_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_quote_product` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return $query->row['total'];
	}

}

?>