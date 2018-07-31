<?php

class ModelExtensionModuleSalesAgents extends Model {

	public function getOrderSalesAgent($order_id) {
		$query = $this->db->query("SELECT CONCAT(osa.firstname, ' ', osa.lastname) AS name FROM `" . DB_PREFIX . "oe_order` oo LEFT JOIN `" . DB_PREFIX . "oe_sales_agents` osa ON (oo.sales_agent_id = osa.sales_agent_id) WHERE oo.order_id = '" . (int)$order_id . "'");
		if ($query->num_rows) {
			return $query->row['name'];
		} else {
			return;
		}
	}

}

?>