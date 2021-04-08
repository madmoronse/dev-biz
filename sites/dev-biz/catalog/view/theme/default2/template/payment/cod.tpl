<div class="buttons">
  <div class="right">
    <?php if ($this->customer->getCustomerGroupId() == 3) {echo '<span style="font-weight:bold; font-size:16px;">После оформления заказа с Вами свяжется наш менеджер =>  </span>'; }?>
    <?php if ($this->customer->getCustomerGroupId() == 4) {echo '<a href="/index.php?route=information/information&information_id=23" class="button" target="_blank"> Реквизиты для оплаты заказа</a>'; }?>
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
(function(){
	var user_group = <?php echo $this->customer->getCustomerGroupId(); ?>;
	var error_message = 'Возникла непредвиденная ошибка, попробуйте обновить страницу' 
						+ ' и оформить заказ повторно';
	function confirmOrder(user_group)
	{
		if (user_group == 2 || user_group == 3 || user_group == 4) {
			var link = 'index.php?route=checkoutf/confirm/confirm';
			$.ajax({ 
				type: 'get',
				url: link,
				dataType: 'JSON',
				success: function(json) {
					if (!json || json.success == false)  {
						window.Neos.alert(error_message, 'error');
						return false;
					}
					if (json.success == true) {
						confirmPayment();
					} else if (json.redirect) {
						window.location = json.redirect;
					} else {
						window.Neos.alert(error_message, 'error');
					}
				},
				error: function() {
					window.Neos.alert(error_message, 'error');
				}
			});
		} else {
			confirmPayment();
		}
	}
	function confirmPayment()
	{
		$.ajax({ 
			type: 'get',
			url: 'index.php?route=payment/cod/confirm',
			data: $("input[name=\'prepayment\'], input[name=\'replacement_for\'], input[name=\'buybuysu_bc\']"),
			success: function() {
				window.location = '<?php echo $continue; ?>';
			}		
		});
	}
	$('#button-confirm').bind('click', function() {
		if (user_group == 4 || user_group == 2) {

			if  ($('.check_photo').length > 0) {
				document.getElementById('button-confirm').style.display="none";
				confirmOrder(user_group);
			} else {
				var confirm_order = confirm("Вы не прикрепили фото чека. Завершить оформление заказа?");
				if (confirm_order == true) {document.getElementById('button-confirm').style.display="none"; confirmOrder(user_group);}
			}
		} else {
			document.getElementById('button-confirm').style.display="none";
			confirmOrder(user_group);
		}
	});
})();
//--></script> 
