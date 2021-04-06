<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form autocomplete="off" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		ID проекта:<br>
		<input type="text" name="project_id" value="<?php echo $project_id_value; ?>"><br><br>		
		Имя пользователя:<br>
		<input type="text" name="username" value="<?php echo $username_value; ?>"><br><br>
		Пароль:<br>
		<input type="text" name="password" value="<?php echo $password_value; ?>"><br><br>
		<input type="submit" value="Сохранить">
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>