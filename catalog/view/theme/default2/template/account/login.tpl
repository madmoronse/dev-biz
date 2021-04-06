<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div class="modal-wrapper" <? /* id="content" */ ?>><?php echo $content_top; ?>
  <? /* <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div> */ ?>
  <img src="catalog/view/theme/default/image/LOGO.png" width="48" height="48" alt="" style="text-align: center;">
  <h1>Вход</h1>
  <div class="login-content">
    <? /*<div class="left">
      <h2><?php echo $text_new_customer; ?></h2>
      <div class="content">
        <p><b><?php echo $text_register; ?></b></p>
        <p><?php echo $text_register_account; ?></p>
        <a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></div>
    </div>*/ ?>
    <div <? /* class="right" */ ?>>
    <? /*  <h2><?php echo $text_returning_customer; ?></h2> */ ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="content">
          <? /* <p><?php echo $text_i_am_returning_customer; ?></p> */ ?>
					<div class="beautify-inp-lab">
						<label for="email"><?php echo $entry_email; ?></label>
						<input type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="Email"/>
					</div>
					<div class="beautify-inp-lab">
						<label for="password"><?php echo $entry_password; ?></label>
						<input type="password" id="password" name="password" class="password" value="<?php echo $password; ?>" placeholder="Пароль"/>
					</div>
          <div class="beautify-inp-lab forgotten">
            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
          </div>
          <div class="modal-bottom">
            <button type="submit" class="redbutton r"><?php echo $button_login; ?></button>
          </div>
          <div class="beautify-inp-lab register_">
          <span class="register-info">Нет аккаунта?</span>
          <a class="l register_" href="<?php echo $register; ?>"><?php echo $text_register; ?></a>
          </div>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script>
<?php echo $footer; ?>
