<?php

// Heading
$_['heading_title']					= 'Order Entry Sales Agents Add-on';

// Text
$_['text_yes']						= 'Yes';
$_['text_no']						= 'No';
$_['text_agent_only']				= 'Agent Only';
$_['text_all_orders']				= 'All Orders';
$_['text_success']					= 'Success: You have modified Order Entry Sales Agents!';
$_['text_edit']						= 'Edit Order Entry Sales Agents';
$_['text_module']					= 'Modules';
$_['text_oe_install']				= 'Order Entry System must be installed and enabled before you can install Sales Agents.  If you have not purchased Order Entry System yet, you can purchase it <a href="%s">here</a> or <a href="%s">here</a>.  After you have purchased and downloaded, please install it using the instructions located <a href="%s">here</a> and then you can install the Sales Agents add-on.';
$_['text_warning']					= 'Warning!!';

// Entry
$_['entry_use_logged']				= 'Use Logged In User as Agent';
$_['entry_user_group']				= 'Sales Agent User Group';
$_['entry_show_sales_agent']		= 'Sales Agent on Customer List';
$_['entry_show_agent_order_list']	= 'Sales Agent on Order List';
$_['entry_customer_orders']			= 'Agent Only Order Add/Edit';
$_['entry_sales_report']			= 'Sales Agent Report Data';
$_['entry_status']					= 'Status';

// Button

// Help
$_['help_use_logged']				= 'If set to Yes and the logged in user is a sales agent, the order will be set to this sales agent automatically.  If set to No or the logged in user is not a sales agent, the customer sales agent will be used if one has been set for that customer';
$_['help_show_sales_agent']			= 'If set to Yes, a column will be added to the Customer list screen showing the sales agent assigned to that customer.';
$_['help_show_agent_order_list']	= 'If set to Yes, a column will be added to the Order list screen showing the sales agent for the order (if any)';
$_['help_customer_orders']			= 'If set to Yes, only the agent assigned to the customer can create or edit orders for that customer.  If set to No, any agent can create or edit orders for that customer.  Administrators can create and edit orders for any customer';
$_['help_sales_report']				= 'The Sales Agents report can be set to show only sales the agent entered for the customer or to show all orders for that agents customers regardless of how they were created.  Set to Agent Only to only show sales the sales agent added via Order Entry.  Set to All Orders to show orders that both the sales agent created as well as catalog side orders for the sales agents customers.';

// Error
$_['error_permission']				= 'Warning: You do not have permission to modify Order Entry Sales Agents module!';
$_['error_installation']			= 'Installation Error';

?>