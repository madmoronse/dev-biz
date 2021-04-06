<div  content="noindex, follow" style="display: none;">
<div id="permission-helper"><?php echo html_entity_decode($details_text[$lang_id]['name'], ENT_QUOTES, 'UTF-8'); ?></div>
<div id="ielt9-helper"><?php echo html_entity_decode($cap_text[$lang_id]['name'], ENT_QUOTES, 'UTF-8'); ?></div>
<script type="text/javascript">
$(document).ready(function() {
	$('#compare-total-top').html('<?php echo $text_compare; ?>').attr({href: 'index.php?route=product/compare'});
	$('#contact-us').html('<div class="phone"><?php echo $phone_number_text; ?></div><div class="email">e-mail: <a href="mailto:<?php echo $email_text; ?>"><?php echo $email_text; ?></a></div>');
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
