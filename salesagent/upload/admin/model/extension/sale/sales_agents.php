<?php

class ModelExtensionSaleSalesAgents extends Model {

	public function addSalesAgent($data) {
		$user_group_id = $this->config->get('sales_agents_user_group_id');
		$user_id_query = $this->db->query("SELECT MAX(user_id) as max_user_id FROM `" . DB_PREFIX . "oe_sales_agents`");
		$user_id = 100000;
		if ($user_id_query->num_rows) {
			if ($user_id_query->row['max_user_id'] >= $user_id) {
				$user_id = $user_id_query->row['max_user_id'] + 1;
			}
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_sales_agents` SET `user_id` = '" . (int)$user_id . "', `user_group_id` = '" . (int)$user_group_id . "', `username` = '" . $this->db->escape($data['username']) . "', `salt` = '" . $this->db->escape($salt = token(9)) . "', `password` = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `image` = '" . $this->db->escape($data['image']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");
		$sales_agent_id = $this->db->getLastId();
		return $sales_agent_id;
	}

	public function editSalesAgent($sales_agent_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "oe_sales_agents` SET `username` = '" . $this->db->escape($data['username']) . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `image` = '" . $this->db->escape($data['image']) . "', `status` = '" . (int)$data['status'] . "' WHERE `sales_agent_id` = '" . (int)$sales_agent_id . "'");
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "oe_sales_agents` SET `salt` = '" . $this->db->escape($salt = token(9)) . "', `password` = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE `sales_agent_id` = '" . (int)$sales_agent_id . "'");
		}
		return;
	}

	public function deleteSalesAgent($sales_agent_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_sales_agents` WHERE `sales_agent_id` = '" . (int)$sales_agent_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_sales_agents_to_customers` WHERE `sales_agent_id` = '" . (int)$sales_agent_id . "'");
		return;
	}

	public function getSalesAgent($sales_agent_id) {
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = osa.user_group_id) AS user_group FROM `" . DB_PREFIX . "oe_sales_agents` osa WHERE osa.sales_agent_id = '" . (int)$sales_agent_id . "'");
		return $query->row;
	}
	
	public function getSalesAgentByUserId($user_id) {
		$query = $this->db->query("SELECT `sales_agent_id` FROM `" . DB_PREFIX . "oe_sales_agents` WHERE `user_id` = '" . (int)$user_id . "'");
		if ($query->num_rows) {
			return $query->row['sales_agent_id'];
		} else {
			return 0;
		}
	}
	public function getSalesAgentByUsername($username) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_sales_agents` WHERE `username` = '" . $this->db->escape($username) . "'");
		return $query->row;
	}

	public function getSalesAgentByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "oe_sales_agents` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getSalesAgents($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "oe_sales_agents`";
		$sort_data = array(
			'lastname',
			'status',
			'date_added'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalSalesAgents() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_sales_agents`");
		return $query->row['total'];
	}

	public function getCustomerCount($sales_agent_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oe_sales_agents_to_customers` WHERE `sales_agent_id` = '" . (int)$sales_agent_id . "'");
		return $query->row['total'];
	}

	public function getCustomerSalesAgentName($customer_id) {
		$query = $this->db->query("SELECT CONCAT(osa.firstname, ' ', osa.lastname) AS name FROM `" . DB_PREFIX . "oe_sales_agents_to_customers` o2c LEFT JOIN `" . DB_PREFIX . "oe_sales_agents` osa ON (o2c.sales_agent_id = osa.sales_agent_id) WHERE o2c.customer_id = '" . (int)$customer_id . "'");
		if ($query->num_rows) {
			return $query->row['name'];
		} else {
			return;
		}
	}

	public function getCustomerSalesAgentId($customer_id) {
		$query = $this->db->query("SELECT `sales_agent_id` FROM `" . DB_PREFIX . "oe_sales_agents_to_customers` WHERE `customer_id` = '" . (int)$customer_id . "'");
		if ($query->num_rows) {
			return $query->row['sales_agent_id'];
		} else {
			return 0;
		}
	}

	public function getOrderSalesAgent($order_id) {
		$query = $this->db->query("SELECT `sales_agent_id` FROM `" . DB_PREFIX . "oe_order` WHERE `order_id` = '" . (int)$order_id . "'");
		return $query->row['sales_agent_id'];
	}

	public function getOrderSalesAgentName($order_id) {
		$query = $this->db->query("SELECT CONCAT(osa.firstname, ' ', osa.lastname) AS name FROM `" . DB_PREFIX . "oe_order` o LEFT JOIN `" . DB_PREFIX . "oe_sales_agents` osa ON (o.sales_agent_id = osa.sales_agent_id) WHERE o.order_id = '" . (int)$order_id . "'");
		if ($query->num_rows) {
			return $query->row['name'];
		} else {
			return;
		}
	}

	public function getQuoteSalesAgent($quote_id) {
		$query = $this->db->query("SELECT `sales_agent_id` FROM `" . DB_PREFIX . "oe_quote` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return $query->row['sales_agent_id'];
	}

	public function getQuoteSalesAgentName($quote_id) {
		$query = $this->db->query("SELECT CONCAT(osa.firstname, ' ', osa.lastname) AS name FROM `" . DB_PREFIX . "oe_quote` oq LEFT JOIN `" . DB_PREFIX . "oe_sales_agents` osa ON (oq.sales_agent_id = osa.sales_agent_id) WHERE oq.quote_id = '" . (int)$quote_id . "'");
		if ($query->num_rows) {
			return $query->row['name'];
		} else {
			return;
		}
	}

	public function getLoggedSalesAgentId($user_id) {
		$sales_agent_id = 0;
		$query = $this->db->query("SELECT `sales_agent_id` FROM `" . DB_PREFIX . "oe_sales_agents` WHERE `user_id` = '" . (int)$user_id . "'");
		if ($query->num_rows) {
			$sales_agent_id = $query->row['sales_agent_id'];
		}
		return $sales_agent_id;
	}

	public function getSalesAgentOrders($sales_agent_id, $data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'shipping' GROUP BY ot.order_id)) AS shipping, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'optfeedisc' AND ot.value > 0 GROUP BY ot.order_id)) AS other_fees, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND (ot.code = 'coupon' OR (ot.code = 'optfeedisc' AND ot.value < 0)) GROUP BY ot.order_id)) AS coupon, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "oe_order` oo ON (o.order_id = oo.order_id) WHERE oo.sales_agent_id = '" . (int)$sales_agent_id . "'";
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$sql .= " ORDER BY o.date_added DESC";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function checkDb() {
		if ($this->config->get('module_order_entry_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'sales_agent_id'");
			if ($query->num_rows < 1) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` ADD COLUMN sales_agent_id int(11) NOT NULL");
			}
		}
		if ($this->config->get('module_quote_system_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'sales_agent_id'");
			if ($query->num_rows < 1) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN sales_agent_id int(11) NOT NULL");
			}
		}
		return;
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_sales_agents` (
				`sales_agent_id` int(11) NOT NULL auto_increment,
				`user_id` int(11) NOT NULL,
				`user_group_id` int(11) NOT NULL,
				`firstname` varchar(32) COLLATE utf8_general_ci NOT NULL,
				`lastname` varchar(32) COLLATE utf8_general_ci NOT NULL,
				`username` varchar(20) COLLATE utf8_general_ci NOT NULL,
				`email` varchar(96) COLLATE utf8_general_ci NOT NULL,
				`password` varchar(40) COLLATE utf8_general_ci NOT NULL,
				`salt` varchar(9) COLLATE utf8_general_ci NOT NULL,
				`image` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`status` tinyint(1) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`sales_agent_id`)
			)
			ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_sales_agents_to_customers` (
				`sales_agent_id` int(11) NOT NULL,
				`customer_id` int(11) NOT NULL
			)
			ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		if ($this->config->get('module_order_entry_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'sales_agent_id'");
			if ($query->num_rows < 1) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` ADD COLUMN sales_agent_id int(11) NOT NULL");
			}
			$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_modules` SET `module_name` = 'Sales Agents', `module_code` = 'sales_agents'");
		}
		if ($this->config->get('module_quote_system_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'sales_agent_id'");
			if ($query->num_rows < 1) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` ADD COLUMN sales_agent_id int(11) NOT NULL");
			}
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'report', `code` = 'sales_agents_report'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'report_sales_agents_report', `key` = 'report_sales_agents_report_status', `value` = '1', `serialized` = '0'");
		return;
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_sales_agents`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_sales_agents_to_customers`");
		if ($this->config->get('module_order_entry_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'sales_agent_id'");
			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` DROP COLUMN sales_agent_id");
			}
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oe_modules` WHERE `module_code` = 'sales_agents'");
		}
		if ($this->config->get('module_quote_system_status')) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_quote` LIKE 'sales_agent_id'");
			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_quote` DROP COLUMN sales_agent_id");
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = 'report' AND `code` = 'sales_agents_report'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'report_sales_agents_report'");
		return;
	}

	public function uninstall2() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = 'module' AND `code` = 'sales_agents'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = 'report' AND `code` = 'sales_agents_report'");
		return;
	}

}

?>