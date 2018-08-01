<?php

class ModelExtensionSaleOrderEntry extends Model {

	public function checkEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(strtolower($email)) . "'");
		return $query->row['total'];
	}

	public function getCustomerOrders($data) {
		$sql = "SELECT o.*, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = '" . (int)$data['customer_id'] . "' AND o.order_status_id > 0 ORDER BY o.date_added DESC LIMIT 0," . $data['limit'];
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getUserGroupId($user_id) {
		$query = $this->db->query("SELECT `user_group_id` FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int)$user_id . "'");
		return $query->row['user_group_id'];
	}

	public function getModules() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_modules` ORDER BY `module_name`");
		return $query->rows;
	}

	public function enable($module_id) {
		$module_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_modules` WHERE `oe_module_id` = '" . (int)$module_id . "'");
		if ($module_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'module_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'payment_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'shipping_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'total_" . $module_query->row['module_code'] . "_status'");
			return 1;
		} else {
			return 0;
		}
	}
	
	public function disable($module_id) {
		$module_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_modules` WHERE `oe_module_id` = '" . (int)$module_id . "'");
		if ($module_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '0' WHERE `key` = 'module_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '0' WHERE `key` = 'payment_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '0' WHERE `key` = 'shipping_" . $module_query->row['module_code'] . "_status'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '0' WHERE `key` = 'total_" . $module_query->row['module_code'] . "_status'");
			return 1;
		} else {
			return 0;
		}
	}

	public function getRewardPoints($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");
		return $query->row['total'];
	}
	
	public function getStoreCredit($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");
		return $query->row['total'];
	}

	public function getOeCost($product_id) {
		$query = $this->db->query("SELECT `cost` FROM `" . DB_PREFIX . "oe_product_cost` WHERE `product_id` = '" . (int)$product_id . "'");
		if ($query->num_rows) {
			return $query->row['cost'];
		} else {
			return;
		}
	}

	public function getProducts($data = array()) {
		$customer_group_id = $data['group_id'];
		$sql = "SELECT DISTINCT *, (SELECT price FROM `" . DB_PREFIX . "product_discount` pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM `" . DB_PREFIX . "product_special` ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		if ($data['store']) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)";
		}
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if ($this->config->get('oe_fulltext_search')) {
			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['name']) . "%'";
			$sql .= " OR p.model LIKE '%" . $this->db->escape($data['name']) . "%'";
			$sql .= " OR p.sku LIKE '%" . $this->db->escape($data['name']) . "%')";
		} else {
			$sql .= " AND (pd.name LIKE '" . $this->db->escape($data['name']) . "%'";
			$sql .= " OR p.model LIKE '" . $this->db->escape($data['name']) . "%'";
			$sql .= " OR p.sku LIKE '" . $this->db->escape($data['name']) . "%')";
		}
		if ($data['store']) {
			$sql .= " AND p2s.store_id = '" . $data['store_id'] . "'";
		}
		if (!$data['disabled']) {
			$sql .= " AND p.status = '1'";
		}
		if (!$data['quantity']) {
			$sql .= " AND p.quantity > 0";
		}
		$sql .= " GROUP BY p.product_id";
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);
		$sql .= " ORDER BY pd.name";
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

	public function getQuoteTotals($quote_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_quote_total` WHERE `quote_id` = '" . (int)$quote_id . "'");
		return $query->rows;
	}

	public function getOeCustomers($data = array()) {
		$sql = "SELECT *, c.custom_field AS c_custom_field, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM `" . DB_PREFIX . "customer` c LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.customer_group_id = cgd.customer_group_id)";
		if ($this->config->get('oe_company_search')) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "address` a ON (a.address_id = c.address_id)";
		}
		$sql .= " WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if (!empty($data['filter_name']) && $this->config->get('oe_company_search')) {
			$sql .= " AND (CONCAT(LCASE(c.firstname), ' ', LCASE(c.lastname)) LIKE '" . $this->db->escape(strtolower($data['filter_name'])) . "%' OR LCASE(c.email) LIKE '" . $this->db->escape(strtolower($data['filter_name'])) . "%' OR LCASE(a.company) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%' OR c.telephone LIKE '" . $this->db->escape($data['filter_name']) . "%')";
		} elseif (!empty($data['filter_name'])) {
			$sql .= " AND (CONCAT(LCASE(c.firstname), ' ', LCASE(c.lastname)) LIKE '" . $this->db->escape(strtolower($data['filter_name'])) . "%' OR LCASE(c.email) LIKE '" . $this->db->escape(strtolower($data['filter_name'])) . "%' OR c.telephone LIKE '" . $this->db->escape($data['filter_name']) . "%')";
		}
		$sort_data = array(
			'name',
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function updateTables() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_product_cost` (
				`oe_product_cost_id` int(11) NOT NULL auto_increment,
				`product_id` int(11) NOT NULL,
				`cost` decimal(15,4) NOT NULL,
				PRIMARY KEY (`oe_product_cost_id`)
			) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'ref_no'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` ADD COLUMN ref_no varchar(100) COLLATE utf8_general_ci NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'no_tax'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` ADD COLUMN no_tax tinyint(1) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order` LIKE 'pp_link'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order` ADD COLUMN pp_link tinyint(1) NOT NULL DEFAULT 0");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order_product` LIKE 'image'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order_product` ADD COLUMN image varchar(255) COLLATE utf8_general_ci NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order_product` LIKE 'manufacturer_id'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order_product` ADD COLUMN manufacturer_id int(11) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order_product` LIKE 'tax_class_id'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order_product` ADD COLUMN tax_class_id int(11) NOT NULL");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "oe_order_product` LIKE 'sort_order'");
		if ($query->num_rows < 1) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "oe_order_product` ADD COLUMN sort_order int(11) NOT NULL");
		}
		$ext_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'module' AND `code` = 'order_entry'");
		if ($ext_query->num_rows < 1) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'oe', `key` = 'oe_status', `value` = '1', `serialized` = '0'");
		}
		return;
	}

	public function updateOrders() {
		$start = 0;
		do {
			$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT " . $start . ",1000");
			$start += 1000;
			$count = 0;
			if ($order_query->num_rows) {
				foreach ($order_query->rows as $order) {
					$count++;
					$order_exists = $this->db->query("SELECT * FROM `" . DB_PREFIX . "oe_order` WHERE `order_id` = '" . (int)$order['order_id'] . "'");
					if ($order_exists->num_rows < 1) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order` SET `order_id` = '" . (int)$order['order_id'] . "'");
						$oe_order_id = $this->db->getLastId();
						$order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order['order_id'] . "'");
						if ($order_product_query->num_rows) {
							$new_product_id = 500000;
							foreach ($order_product_query->rows as $order_product) {
								if ($order_product['product_id'] == 0) {
									$product_id = $new_product_id;
									$custom_product = 1;
									$new_product_id++;
								} else {
									$product_id = $order_product['product_id'];
									$custom_product = 0;
								}
								$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order_product` SET `oe_order_id` = '" . (int)$oe_order_id . "', `order_product_id` = '" . (int)$order_product['order_product_id'] . "', `order_id` = '" . (int)$order['order_id'] . "', `product_id` = '" . (int)$product_id . "', `notax` = '0', `custom_product` = '" . (int)$custom_product . "'");
							}
						}
					}
				}
			}
		} while ($order_query->num_rows > 0);
		return;
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_order` (
				`oe_order_id` int(11) NOT NULL auto_increment,
				`order_id` int(11) NOT NULL UNIQUE,
				`ref_no` varchar(100) COLLATE utf8_general_ci NOT NULL,
				`no_tax` tinyint(1) NOT NULL,
				`pp_link` tinyint(1) NOT NULL DEFAULT 0,
				PRIMARY KEY (`oe_order_id`)
			) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_order_product` (
				`oe_order_product_id` int(11) NOT NULL auto_increment,
				`oe_order_id` int(11) NOT NULL,
				`order_product_id` int(11) NOT NULL,
				`order_id` int(11) NOT NULL,
				`product_id` int(11) NOT NULL,
				`image` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`notax` tinyint(1) NOT NULL,
				`shipping` tinyint(1) NOT NULL,
				`custom_product` tinyint(1) NOT NULL,
				`sort_order` int(11) NOT NULL,
				PRIMARY KEY (`oe_order_product_id`)
			) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_product_cost` (
				`oe_product_cost_id` int(11) NOT NULL auto_increment,
				`product_id` int(11) NOT NULL,
				`cost` decimal(15,4) NOT NULL,
				PRIMARY KEY (`oe_product_cost_id`)
			) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oe_modules` (
				`oe_module_id` int(11) NOT NULL auto_increment,
				`module_name` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`module_code` varchar(255) COLLATE utf8_general_ci NOT NULL,
				PRIMARY KEY (`oe_module_id`)
			) ENGINE=MyISAM COLLATE=utf8_general_ci;
		");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "modification` MODIFY COLUMN `xml` longtext COLLATE utf8_general_ci NOT NULL");
		$start = 0;
		do {
			$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT " . $start . ",1000");
			$start += 1000;
			$count = 0;
			if ($order_query->num_rows) {
				foreach ($order_query->rows as $order) {
					$count++;
					$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order` SET `order_id` = '" . (int)$order['order_id'] . "'");
					$oe_order_id = $this->db->getLastId();
					$order_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order['order_id'] . "'");
					if ($order_product_query->num_rows) {
						$new_product_id = 500000;
						foreach ($order_product_query->rows as $order_product) {
							if ($order_product['product_id'] == 0) {
								$product_id = $new_product_id;
								$custom_product = 1;
								$new_product_id++;
							} else {
								$product_id = $order_product['product_id'];
								$custom_product = 0;
							}
							$this->db->query("INSERT INTO `" . DB_PREFIX . "oe_order_product` SET `oe_order_id` = '" . (int)$oe_order_id . "', `order_product_id` = '" . (int)$order_product['order_product_id'] . "', `order_id` = '" . (int)$order['order_id'] . "', `product_id` = '" . (int)$product_id . "', `notax` = '0', `custom_product` = '" . (int)$custom_product . "'");
						}
					}
				}
			}
		} while ($order_query->num_rows > 0);
		return;
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_order`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_order_product`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_product_cost`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oe_modules`");
		return;
	}

}

?>