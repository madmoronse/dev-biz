<div class="box">
  <div class="box-heading affilate-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-account">
      <?php if (!$logged) { ?>
      <li><i class="fa fa-sign-in account-icons"></i><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
			<li><i class="fa fa-pencil account-icons"></i><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
      <li><i class="fa fa-question-circle account-icons"></i><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></li>
      <?php } ?>
      <li><i class="fa fa-user account-icons"></i><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <?php if ($logged) { ?>
      <li><i class="fa fa-pencil-square-o account-icons"></i><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
      <li><i class="fa fa-unlock-alt account-icons"></i><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
      <?php } ?>
      <li><i class="fa fa-money account-icons"></i><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
      <li><i class="fa fa-code account-icons"></i><a href="<?php echo $tracking; ?>"><?php echo $text_tracking; ?></a></li>
      <li><i class="fa fa-credit-card account-icons"></i><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
      <?php if ($logged) { ?>
      <li><i class="fa fa-sign-out account-icons"></i><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
