<div class="left">
  <h2><?php echo $text_new_customer; ?></h2>
  <p><?php echo $text_checkout; ?></p>
  <label for="register">
    <?php if ($account == 'register') { ?>
    <input type="radio" name="account" value="register" id="register" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="register" id="register" />
    <?php } ?>
    <b><?php echo $text_register; ?></b></label><br />
	
	<label for="quick">
    <?php if ($account == 'quick') { ?>
    <input type="radio" name="account" value="quick" id="quick" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="quick" id="quick" />
    <?php } ?>
    <b><?php echo $text_quick; ?></b></label><br />
	
<?php if ($guest_checkout) { ?>
  <label for="guest">
    <?php if ($account == 'guest') { ?>
    <input type="radio" name="account" value="guest" id="guest" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="guest" id="guest" />
    <?php } ?>
    <b><?php echo $text_guest; ?></b></label>

  <?php } ?>
  <p><?php echo $text_register_account; ?></p>
  <input type="button" value="<?php echo $button_continue; ?>" id="button-account" class="button" />
  <br />
  <br />
</div>
<div id="login" class="right">
  <h2><?php echo $text_returning_customer; ?></h2>
  <p><?php echo $text_i_am_returning_customer; ?></p>
	<div class="email">
		<b><?php echo $entry_email; ?></b>
		<input type="text" name="email" value="" />
	</div>
	<div class="password">
		<b><?php echo $entry_password; ?></b>
		<input type="password" name="password" value="" />
	</div>
	<div class="fogotten-login">
		<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
		<input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" />
	</div>
</div>