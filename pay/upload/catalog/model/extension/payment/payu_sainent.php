<?php 
/**
 * @author     Prashant Bhagat
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    1.0
 * @Website    http://www.sainent.com/
 */
 
class ModelExtensionPaymentPayuSainent extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('extension/payment/payu_sainent');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_payu_sainent_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payment_payu_sainent_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_payu_sainent_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true; 
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'payu_sainent',
        		'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_payu_sainent_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	
	
}
?>