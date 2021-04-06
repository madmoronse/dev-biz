<?php $customer_group_id = $this->customer->getCustomerGroupId(); ?>
<div id="cart" class="cart">
  <div class="heading">
    <span class="tittle_text"><?php /* echo $heading_title; */ ?></span>
		<a href="/cart" class="cart-icon">
    <?php echo $text_items; ?>
		</a>
	</div>
  <div class="content" style="overflow: auto;max-height:550px">
    <?php if ($products || $vouchers) { ?>
    <div class="mini-cart-info">
      <table>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name'] . ' (Артикул: ' . $product['product_id'] . ')';?></a>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
            </div></td>
          <td class="quantity">x&nbsp;<?php echo $product['quantity']; ?></td>
          <td class="total"><?php echo $product['total']; ?></td>
          <td class="remove">
						<a class="button-remove" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');"></a>
					</td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="image"></td>
          <td class="name"><?php echo $voucher['description']; ?></td>
          <td class="quantity">x&nbsp;1</td>
          <td class="total"><?php echo $voucher['amount']; ?></td>
          <td class="remove">
						<a class="button-remove" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');">x</a>
					</td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="mini-cart-total">
      <table>
        <?php foreach ($totals as $total) { ?>
      <tr>
          <td class="right"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right total-price"><?php echo $total['text']; ?></td>
        </tr> 
        <?php } ?>
      </table>
    </div>
    <div class="checkout"><?php if ($this->customer->getCustomerGroupId() < 2) { /*  */ } ?><a href="<?php echo $cart; /* $checkout; */ ?>" class="redbutton"><?php echo $text_checkout; ?></a></div>
    <?php } else { ?>
    <div class="empty"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
$('.siteerrormessage').colorbox({iframe:true});
//--></script>
<!-- END siteerrormessage-->
