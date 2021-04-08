<?php
// Heading
$_['heading_title']    = 'Social Discount';

$_['button_apply']     = 'Apply';
// Text
$_['text_success']     = 'Success: You have modified social-discount total!';

// Entry
$_['entry_status']     = 'Status:';
$_['entry_discount_value'] = 'Discount:';
$_['entry_sort_order'] = 'Sort Order:';

$_['entry_discount_lifetime'] = 'Discount lifetime:';
$_['entry_discount_lifetime_help'] = 'In seconds. For example: for one week you should write 604800 (60*60*24*7). 0 defines no limit.';

$_['entry_discount_method'] = 'Discount calculate method:';
$_['entry_discount_method_help'] = 'By default, discount is calculated from main product price and sum with current special.<br/>In case of main price is fictive you should use calculation "from special price".';

$_['entry_discount_active_mark'] = 'Social discount mark';
$_['entry_discount_active_mark_help'] = 'Is added for price in case of active social discount.';
$_['entry_default_discount_active_mark'] = '(discount for a like)';

$_['entry_discount_type']         = 'Discount type';
$_['enty_social_discount_type_0'] = 'Percent (%)';
$_['enty_social_discount_type_1'] = 'Fixed (%s)';

$_['entry_discount_integration'] = 'Thirdparty services integration';
$_['entry_discount_integration_help'] = 'ATTENTION! No one of the thirdparty services sends garantee information about success post link on the client\'s wall. That\'s why in case of using thirdparty services discount provided <b>immediately after clicking share button</b>.';


// Error
$_['error_permission'] = 'Warning: You do not have permission to modify social-discount total!';
?>