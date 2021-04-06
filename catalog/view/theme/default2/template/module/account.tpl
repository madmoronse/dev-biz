<?php $customer_group_id = $this->customer->getCustomerGroupId();?>
<div class="box">
  <div class="box-heading account-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-account">
    <li><i><img src="../image/cart.png"></i><a href="/cart/">Корзина</a></li>
      <?php if (!$logged) { ?>
      <?php if (1==2){?><li><i class="fa fa-sign-in account-icons"></i><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li><?php }?>
      <li><i class="fa fa-pencil account-icons"></i><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
      <li><i class="fa fa-question-circle account-icons"></i><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></li>
      <?php } ?>
      <li><i class="fa fa-user account-icons"></i><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <?php if ($logged) { ?>
      <?php if ($customer_group_id != 3) {?><li><i class="fa fa-pencil-square-o account-icons"></i><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li><?php }?>
      <li><i class="fa fa-unlock-alt account-icons"></i><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
      <?php } ?>
      <?php if ($customer_group_id == 4) {?> <li><i class="fa fa-unlock-alt account-icons"></i><a href="<?php echo $comment; ?>"><?php echo $text_comment; ?></a></li><?php }?> 
      <?php if ($customer_group_id != 3) {?><li><i class="fa fa-home account-icons"></i><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li><?php }?>
      <li><i class="fa fa-heart account-icons"></i><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><i class="fa fa-file-o account-icons"></i><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
    <li><i class="fa fa-truck account-icons"></i><a href="<?php echo $tracking_number; ?>"><?php echo $text_tracking_number; ?></a></li>
    <li><i class="fa fa-usd account-icons"></i><a href="<?php echo $invoice; ?>"><?php echo $text_invoice; ?></a></li>
      <!-- <li><i class="fa fa-download account-icons"></i><a href="<?php /* echo $download; */ ?>"><?php /* echo $text_download; */ ?></a></li> -->
      <!-- <li><i class="fa fa-exchange account-icons"></i><a href="<?php /* echo $return; */ ?>"><?php /* echo $text_return; */ ?></a></li> -->
      <!-- <li><i class="fa fa-credit-card account-icons"></i><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>-->
      <li><i class="fa fa-envelope-o account-icons"></i><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      <?php if ($is_teacher) { ?>
      <li><i class="fa fa-envelope-o account-icons"></i><a href="<?php echo $teacher; ?>"><?php echo $text_teacher; ?></a></li>
      <?php } ?>
      <?php if ($logged) { ?>
      <li><i class="fa fa-sign-out account-icons"></i><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
      <?php if ($customer_group_id == 4) { ?><li><i class="fa fa-info-circle account-icons"></i><a href="/index.php?route=information/information&information_id=24">Условия работы с нами и порядок выплаты наценки</a></li><?php }?>
      <?php } ?>
    </ul>
  </div>
</div>
