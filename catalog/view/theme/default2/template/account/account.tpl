<?php echo $header; ?>
<?php $customer_group_id = $this->customer->getCustomerGroupId();?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <h2 class="h2-head"><?php echo $text_my_account; ?></h2>
  <div class="content">
    <ul class="account-item-list">
      <?php if ($customer_group_id != 3) {?><li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li><?php }?>
      <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
      <?php if ($customer_group_id != 3) {?><li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li><?php }?>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>

    <?php 

      //if ($customer_group_id == 2 or $customer_group_id == 4) echo '<li style="margin-top:5px;"><a href="/sp/" style="background:#f9bcc2;padding:3px 10px;text-decoration:none;" target="_blank">Выставить счет на предоплату (тестовый режим)</a></li>';

    ?>
    </ul>
  </div>
  <h2 class="h2-head"><?php echo $text_my_orders; ?></h2>
  <div class="content">
    <ul class="account-item-list">
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <!-- <li><a href="<?php /* echo $download; */ ?>"><?php /* echo $text_download; */ ?></a></li> -->
      <?php if ($reward) { ?>
      <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
      <?php } ?>
      <!-- <li><a href="<?php /* echo $return; */ ?>"><?php /*echo $text_return; */ ?></a></li> -->
      <!-- <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>-->
    </ul>
  </div>
  <h2 class="h2-head"><?php echo $text_my_newsletter; ?></h2>
  <div class="content">
    <ul class="account-item-list">
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <?php echo $content_bottom; ?></div>
  <?php if ($isQiwi == true) { ?>
<script type="text/javascript">
  $('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style=""> <span>У Вас не указан кошелек для выплаты! <br> Свяжитесь со своим менеджером, для того чтобы указать кошелек :)</span> <img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

  $('.success').fadeIn('slow');

  $('html, body').animate({ scrollTop: 0 }, 'slow');

  $('.close_success').on('click', function(){
  $('.close').trigger('click');
  $('.backg_notif').remove();
  });

  $('.close').on('click', function(){
  $('.backg_notif').remove();
  });
</script>
<?php } ?>
<?php echo $footer; ?>
