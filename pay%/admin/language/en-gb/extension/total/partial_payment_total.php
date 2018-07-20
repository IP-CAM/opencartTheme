<?php
/* Partial Payment Total for OpenCart v.3.0.x 
 *
 * @version 3.2.0
 * @date 15/03/2018
 * @author Kestutis Banisauskas
 * @Smartechas
 */
// Heading
$_['heading_title']	        = '<span style="font-weight: bold; color: green;">Partial Payment<br /></span>';
$_['heading_title_main']    = 'Partial Payment';


// Text
$_['text_extension']        = 'Order Totals';
$_['text_success']          = 'Success: You have modified Partial payment  total!';
$_['text_edit']             = 'Edit Partial Payment Total';
$_['text_payment_pending']  = 'Payment pending';
$_['text_request_payment']  = 'Request Payment ';
$_['text_subject']          = 'Payment Request ';
$_['text_order_id']         = 'Your Order';
$_['text_payment_request']  = 'Please click this link to pay the balance for this order. ';
$_['text_thank_you']        = 'Thank You  for buying <br /> <strong>%s</strong>';
$_['text_sent']             = 'Request to pay balance have been sent';
$_['text_sending']          = 'The request for payment of the balance  to the buyer is sending';
$_['error_email']           = 'Email Error';

// Entry
$_['entry_total']		    = 'Total';
$_['entry_status']          = 'Status';
$_['entry_sort_order']      = 'Sort Order';
$_['entry_geo_zone']	    = 'Geo Zone';
$_['entry_percent']         = 'Partial payment in percent';
$_['entry_subject']         = 'Pending Payment Subject';
$_['entry_message']         = 'Pending Payment Message';
$_['entry_category']        = 'Allowed Categories';
$_['entry_product_ids']     = 'Excluded Product IDs';
$_['entry_customer_group']  = 'Allowed Customer Groups';
$_['entry_tax_class']       = 'Tax Class';



// Help
$_['help_percent']          = 'Enter Partial payment in percent: 100.00:15,150.00:25,300.00:45 (Cart_total:Partial_payment_percent,Cart_total:Partial_payment_percent,Cart_total:Partial_payment_percent...';
$_['help_total']		    = 'The checkout total the order must reach before this payment method becomes active.'; 
$_['help_total_sort']	    = 'Module sort order number must be greater than &quot;Total&quot; Sorting number.';
$_['help_category']         = 'Select for which categories the payment option will be available. Leave blank if no restriction.';
$_['help_product_ids']      = 'Add product IDs separated by comma(,) for which the method will not be available.';
$_['help_customer_group']   = 'The customer must be in these customer groups before this payment method becomes active. Leave blank if there is no restriction.';


// Error
$_['error_permission']      = 'Warning: You do not have permission to modify partial payment  total!';