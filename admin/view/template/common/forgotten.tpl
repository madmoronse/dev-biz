<?php echo $header; ?>
<div id="content">  
  <div class="login_page" style="width: 520px; min-height: 300px; margin-top: 100px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><?php echo $heading_title; ?></h1>
      
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
        <p><?php echo $text_email; ?></p>
        <?php if ($error_warning) { ?>
			<div class="login_reset_error"><?php echo $error_warning; ?></div>
		<?php } ?>
		<table class="form">
			<tr>
				<td><?php echo $entry_email; ?></td>
				<td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
				<td><a onclick="$('#forgotten').submit();" class="login_button"><?php echo $button_reset; ?></a></td>  
				<td><a href="<?php echo $cancel; ?>" class="login_button"><?php echo $button_cancel; ?></a></td>
			</tr>
			</table>
		</form>
	  
    </div>
  </div>
</div>
<?php echo $footer; ?>