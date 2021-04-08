<div class="wrapper">
<?php if (isset($error_warning)) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (count($shipping_methods) > 0)  { ?>
<table cellspacing="0" cellpadding="0" class="delivery-table">
	<tr>
  	<th><?php echo $text_prepayment; ?></th>
    <th><?php echo $text_delivery; ?></th>
    <th><?php echo $text_dcost; ?></th>
    <th><?php echo $text_payment; ?></th>

    <th><?php echo $text_choose; ?></th>
  </tr>
<?php foreach ($shipping_methods as $key => $shipping_method) {
    switch ($shipping_method['delivery']) {
        case 'Почта России':
            $delivery = '<img src="/catalog/view/theme/default2/image/rm.png" alt="" />';
        break;
        case 'СДЭК':
            $delivery = '<img src="/catalog/view/theme/default2/image/cdek.png" alt="" />';
            $style = "font-size:10px;display:block;";
            if (!NEOS_CART_HYBRID) $style .= "margin-top:-10px;";
            if ($shipping_method['payment'] != 'Предоплата 100%')  {
                $shipping_method['payment'] .= '<a href="/usloviya-nalozhennogo-platezha-sdek/"
                                                   target="_blank" 
                                                   style="' . $style . '">
                                                   Условия наложенного платежа СДЭК</a>';
            }
        break;
        default:
            $delivery = '';
        break;
    }
    $dcost = (intval($shipping_method['dcost'])) ? $shipping_method['dcost'].'ք' : $shipping_method['dcost'];
?>
    <tr>
        <td><?php echo $shipping_method['payment'] ?></td>
        <td><?php echo $delivery ?></td>
        <td><?php echo $dcost ?></td>
        <td><?php echo $shipping_method['place'] ?></td>
        <td><input type="radio" name="delivery-way" value="<?php echo $key?>" <?php if (isset($shipping_method['selected'])) echo 'checked' ?>/></td>
    </tr>
<?php } ?>
</table>
<p id="errors"></p>
    <?php  if ($customer_group_id == 2) { ?>
    <form class="payment-form" id="checkout-form" style="border-right: none;">
        <div class="payment-form__item">
          <?php if (!NEOS_CART_HYBRID) { ?> <span class="required">* </span><?php } ?><label for="prepayment">Предоплата</label>
          <input type="text" name="prepayment" autocomplete="off" id="prepayment" value="<?php echo $prepayment; ?>" class="large-field" />
          <p class="help-block">Укажите сумму предоплаты, которую клиент уже внёс за заказ</p>

            <?php if ($replacement_for != '') {
                $input_replacement_for = "";
                $btn_class = 'is-active';
            } else {
                $input_replacement_for = "display: none;";
                $btn_class = '';
            }?>
        <?php if (NEOS_CART_HYBRID) { ?>
            <textarea name="comment" id="comment" class="large-field"><?= $comment ?></textarea>
            <p class="help-block">Укажите дополнительную информацию, которую вы считаете важной</p>
        <?php } ?>
          <a class="btn btn--regular btn--sm <?php echo $btn_class; ?>" href="#" data-input="#replacement_for">Обмен по прошлому заказу</a>
          <input type="text" name="replacement_for" id="replacement_for" value="<?php echo $replacement_for; ?>" class="large-field" style="<?php echo $input_replacement_for?> width: 100%;"/>  
          <p class="help-block" id="replacement_for_label" style="<?php echo  $input_replacement_for ?>>">Укажите номер заказа, по которому хотите провести обмен</p>
        </div>
    </form>
    <?php } ?>
<br />
</div>
<script>
(function () {
    var shipping = new Shipping();
    shipping.init();
    $('#replacement_for').forceNumeric();
    $('#prepayment').forceNumeric();
    $('[data-input]').on('click', toggleInput);
})();
</script>
<?php
    if (isset($delivery_way)) {
        if ($shipping_methods[$delivery_way]['dcost'] != 'Бесплатно') {
            $deliverycost = $shipping_methods[$delivery_way]['dcost']."ք";
        }
        $fullcost = $shipping_methods[$delivery_way]['fullcost']."ք";
    }
?>
<table id="checkout-table-info">
  <tr>
    <th colspan="2"><b>Заказ:</b></th>
  </tr>
  <tr>
    <td>Стоимость товаров:</td>
    <td><?php echo $cost_val; ?>ք</td>
  </tr>
  <tr>
    <td>Доставка:</td>
    <td><?php echo $deliverycost; ?></td>
  </tr>
  <tr>
    <td>Итого к оплате:</td>
    <td><?php echo $fullcost; ?></td>
  </tr>
</table>
<?php } else { ?>
<p>
 Доставка в указанный Вами город рассчитывается индивидуально. Обратитесь в чат за помощью.
</p>
<?php } ?>
<?php if (NEOS_CART_HYBRID) { ?>
    <div class="buttons">
        <div class="right">
          <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="button" />
        </div>
      </div>
    <div id="hybrid-cart" class="hidden"></div>
<?php } ?>