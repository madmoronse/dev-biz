<?php  $customer_group_id = $this->customer->getCustomerGroupId(); ?>
<div  content="noindex, follow" style="display: none;">
<div id="permission-helper"><?php echo html_entity_decode($details_text[$lang_id]['name'], ENT_QUOTES, 'UTF-8'); ?></div>
<div id="ielt9-helper"><?php echo html_entity_decode($cap_text[$lang_id]['name'], ENT_QUOTES, 'UTF-8'); ?></div>
<script type="text/javascript">
$(document).ready(function() {
	$('#compare-total-top').html('<?php echo $text_compare; ?>').attr({href: 'index.php?route=product/compare'});
	//$('#contact-us').html('<div><a href="https://vk.com/outmax" class="phone" style="color:#777;"><?php echo $phone_number_text; ?></a></div><div class="email">e-mail: <a href="mailto:<?php echo $email_text; ?>"><?php echo $email_text; ?></a></div>      <div class="email">skype: <a href="skype:<?php echo $skype_text; ?>?chat"><?php echo $skype_text; ?></a></div>');
	
	<?php /*if ($customer_group_id == 3) { ?>
		$('#contact-us').html('<div style="margin-top:18px;color:#E31E24;font-size:22px;font-weight:600;text-align:right;">+7-950-418-91-14</div><div style="font-height:14px;text-align:right;">Александр</div>'); 
	<?php } elseif ($customer_group_id == 4) { ?>	
		$('#contact-us').html('<div style="color:#E31E24;font-size:18px;font-weight:600;text-align:right;">+7-953-598-31-61</div><span style="font-height:14px;text-align:right;">Никита</span><div style="color:#E31E24;font-size:18px;font-weight:600;text-align:right;">+7-908-204-96-80</div><span style="font-height:14px;text-align:right;">Татьяна</span>'); 
	<?php } else { ?>	
	
	$('#contact-us').html('<div class="phone"><a href="tel:88005054251" class="callibri_phone">8-800-505-42-51</a></div><div style="font-height:14px;text-align:right;">ЗВОНОК БЕСПЛАТНЫЙ</div>'); 
	
	<?php } */?>	
	
	$('#contact-us').html('<div style="color:#000;font-size:16px;font-weight:normal; font-family: Open Sans; margin-top:35px;">8-391-98-98-395 </div><span style="font-size:9px !important; font-family: Open Sans; color: #505050; text-align:right;">Дропшиппинг | Оптовые закупки </span> <br> <span style="font-size:9px; font-family: Open Sans; color: #505050; text-align:right;">По будням с 5:00 до 14:00 (МСК)</span>'); 
	
	var permission = $('#permission-helper').html();	
	$('#permission').html(permission);
	var ielt9 = $('#ielt9-helper').html();
	$('#ielt9').html(ielt9);
	$('.box-product .wishlist a').attr({title: '<?php echo $wishlist_text; ?>'});
	$('.box-product .compare a').attr({title: '<?php echo $compare_text; ?>'});
	$('#powered').prepend('<?php echo $designed_text; ?>');
	$('#r-menu-toggle').text('<?php echo $mega_menu_text; ?>');
});
</script>
</div>
