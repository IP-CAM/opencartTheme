<?php
/* Partial Payment Total for OpenCart v.3.0.x 
 *
* @version 3.2.0
 * @date 15/03/2018
 * @author Kestutis Banisauskas
 * @Smartechas
 */
class ModelExtensionTotalPartialPaymentTotal extends Model {
	public function getTotal($total) {
		
		
		if (isset($this->session->data['partial_payment_total'])) {
			$data['partial_payment_total'] = $this->session->data['partial_payment_total'];
		} else {
			$data['partial_payment_total'] = '';
		}
			$data['amount'] = max(0, $total['total']);
			
			/* Condition for customer group */
			$status = true;
		if ($status && $this->config->get('total_partial_payment_total_customer_group')) {
			if (isset($this->session->data['guest']) && in_array(0, $this->config->get('total_partial_payment_total_customer_group'))) {
				$status = true;
			} elseif ($this->customer->isLogged() && $this->session->data['customer_id']) {
				$this->load->model('account/customer');

				$customer = $this->model_account_customer->getCustomer($this->session->data['customer_id']);

				if (in_array($customer['customer_group_id'], $this->config->get('total_partial_payment_total_customer_group'))) {
					$this->session->data['customer_group_id'] = $customer['customer_group_id'];

					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		}
		
		/* Condition for categories and products */
		if ($status && ($this->config->get('total_partial_payment_total_category') || count(explode(',', $this->config->get('total_partial_payment_total_xproducts'))) > 0)) {
			$allowed_categories = $this->config->get('total_partial_payment_total_category');

			$xproducts = explode(',', $this->config->get('total_partial_payment_total_xproducts'));

			$cart_products = $this->cart->getProducts();

			foreach ($cart_products as $cart_product) {
				$product = array();

				if ($xproducts && in_array($cart_product['product_id'], $xproducts)) {
					$status = false;
					break;
				} else {
					$product = $this->db->query("SELECT GROUP_CONCAT(`category_id`) as `categories` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$cart_product['product_id'] . "'");
					$product = $product->row;

					$product = explode(',', $product['categories']);

					if ($allowed_categories){

					if ($product && count(array_diff($product, $allowed_categories)) > 0) {
						$status = false;
						break;
						}
					}
				}
			}
		}
		
		if ($data['partial_payment_total'] && $status) {
			$this->load->language('extension/total/partial_payment_total');
			
				$percents = explode(',', $this->config->get('total_partial_payment_total_percent'));
				$data['total_partial_payment_total_percent'] = '';
				
				$this->load->model('checkout/order');
        		

				foreach ($percents as $percent) {
					
					$data = explode(':', $percent);
					$data['amount'] = max(0, $total['total']);
					
					if ($data[0] >= $data['amount']) {
						if (isset($data[1])) {
							$data['total_partial_payment_total_percent'] = $data[1];
						} 
						
						break;
				}
			}
		
			$data['total_partial_payment_total_percent'] = isset($data['total_partial_payment_total_percent']) ? $data['total_partial_payment_total_percent'] : '';
			
			$data['partial_amount'] = $data['amount']*$data['total_partial_payment_total_percent']/100;
		if ($data['partial_amount'] != '0') {
			$total['totals'][] = array(
				'code'       => 'partial_payment_total',
				'title'      => sprintf($this->language->get('text_partial_payment_total'), $data['total_partial_payment_total_percent']),
				'value'      => $data['partial_amount'],
				'sort_order' => $this->config->get('total_partial_payment_total_sort_order')
				
			);
	
			$total['total'] = $data['partial_amount'];
			}
		}
	}
}