<?php echo $header; ?>
<div id="content">
		<div class="login_page" style="width: 350px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
			<div class="heading">
			<h1><?php echo $text_login; ?></h1>
			</div>
			<div class="content" style="min-height: 150px; overflow: hidden;">
			<?php if ($success) { ?>
			<div class="login_success"><?php echo $success; ?></div>
			<?php } ?>
			<?php if ($error_warning) { ?>
			<div class="login_error"><?php echo $error_warning; ?></div>
			<?php } ?>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 97%;">
				<tr>
					<td><?php echo $entry_username; ?><br />
					<input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;width: 100%;" />
					<br />
					<br />
					<?php echo $entry_password; ?><br />
					<input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;width: 100%;" />
					<?php if ($forgotten) { ?>
					<br /><br />
					<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align: right;"><a onclick="$('#form').submit();" class="login_button"><?php echo $button_login; ?></a></td>
				</tr>
				</table>
				<?php if ($redirect) { ?>
				<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
				<?php } ?>
			</form>
			</div>
		</div>
</div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
<?php echo $footer; ?>