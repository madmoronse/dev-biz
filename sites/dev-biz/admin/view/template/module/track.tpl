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
			<h1><img src="view/image/shipping-cdek.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				 <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				 <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
	</div>
  	<div class="content">
  		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  		<div class="auth-info">
				<table class="form">
					<tbody>
						<tr>
							<td colspan="2"><span class="help"><?php echo $text_help_auth; ?></span></td>
						</tr>
						<tr>
							<td><label for="cdek_auth_login"><?php echo $entry_login; ?></label></td>
							<td><input id="cdek_auth_login" type="text" name="cdek_auth_login" value="<?php echo $cdek_auth_login; ?>" /></td>
						</tr>
						<tr>
							<td><label for="cdek_auth_password"><?php echo $entry_password; ?></label></td>
							<td><input id="cdek_auth_password" type="text" name="cdek_auth_password" value="<?php echo $cdek_auth_password; ?>" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
  	</div>
  </div>