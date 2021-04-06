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
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="apply();" class="button"><?php echo $button_apply; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
	  <div id="tabs" class="htabs">
		<a href="#tab-general">Настройки</a>
		<a href="#tab-buttons">Кнопки социальных сетей</a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div id="tab-general">
			<table class="form">
			  <tr>
				<td><?php echo $entry_discount_value; ?></td>
				<td>
					<table class="list" style="width: 450px;">
					<tr>
						<td width="140">VK
						<td nowrap>
							<input type="checkbox" name="social_discount_vk_like_enabled" id="social_discount_vk_like_enabled"<?php echo $social_discount_vk_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_vk_like_enabled">Like</label>
							<input type="text" name="social_discount_vk_like_value" value="<?php echo $social_discount_vk_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
						<td nowrap>
							<input type="checkbox" name="social_discount_vk_share_enabled" id="social_discount_vk_share_enabled"<?php echo $social_discount_vk_share_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_vk_share_enabled">Share</label>
							<input type="text" name="social_discount_vk_share_value" value="<?php echo $social_discount_vk_share_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
					</tr>
					<tr>
						<td width="140">Facebook
						<td>
							<input type="checkbox" name="social_discount_fb_like_enabled" id="social_discount_fb_like_enabled"<?php echo $social_discount_fb_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_fb_like_enabled">Like</label>
							<input type="text" name="social_discount_fb_like_value" value="<?php echo $social_discount_fb_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
						<td>
							
						</td>
					</tr>
					
					<tr>
						<td width="140">Google Plus
						<td>
							<input type="checkbox" name="social_discount_gp_like_enabled" id="social_discount_gp_like_enabled"<?php echo $social_discount_gp_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_gp_like_enabled">Like</label>
							<input type="text" name="social_discount_gp_like_value" value="<?php echo $social_discount_gp_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
						<td>
							
						</td>
					</tr>
					
					<tr>
						<td width="140">Мой Мир
						<td>
							<input type="checkbox" name="social_discount_mm_like_enabled" id="social_discount_mm_like_enabled"<?php echo $social_discount_mm_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_mm_like_enabled">Like</label>
							<input type="text" name="social_discount_mm_like_value" value="<?php echo $social_discount_mm_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
						<td>
							
						</td>
					</tr>
					
					<tr>
						<td width="140">Одноклассники
						<td>
							<input type="checkbox" name="social_discount_ok_like_enabled" id="social_discount_ok_like_enabled"<?php echo $social_discount_ok_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_ok_like_enabled">Like</label>
							<input type="text" name="social_discount_ok_like_value" value="<?php echo $social_discount_ok_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
						<td>
							
						</td>
					</tr>
					
					<tr>
						<td width="140">Twitter
						<td>
							
						</td>
						<td>
							<input type="checkbox" name="social_discount_tw_like_enabled" id="social_discount_tw_like_enabled"<?php echo $social_discount_tw_like_enabled ? ' checked="checked"' : ''; ?>/><label for="social_discount_tw_like_enabled">Share</label>
							<input type="text" name="social_discount_tw_like_value" value="<?php echo $social_discount_tw_like_value; ?>" size="1" />
							<span class="sd_type"><?php echo $social_discount_type_sign; ?></span>
						</td>
					</tr>
					
					</table>
				</td>
			  </tr>
			  
			   <tr>
				<td><?php echo $entry_discount_type; ?><br/></td>
				<td><select name="social_discount_discount_type" id="social_discount_discount_type">
					<option value="0"<?php if ($social_discount_discount_type == 0) { echo ' selected="selected"'; } ?>><?php echo $enty_social_discount_type_0; ?></option>
					<option value="1"<?php if ($social_discount_discount_type == 1) { echo ' selected="selected"'; } ?>><?php echo $enty_social_discount_type_1; ?></option>
				  </select></td>
			  </tr>
			  
			  
			  <tr>
				<td><?php echo $entry_discount_lifetime; ?><br/><span class="help"><?php echo $entry_discount_lifetime_help; ?></span></td>
				<td><input type="text" name="social_discount_lifetime" value="<?php echo $social_discount_lifetime; ?>" size="20" /></td>
			  </tr>
			  
			  <tr>
				<td><?php echo $entry_discount_method; ?><br/><span class="help"><?php echo $entry_discount_method_help; ?></span></td>
				<td><select name="social_discount_discount_method">
					<?php foreach ($discount_methods as $method_id => $method_name): ?>
					<option value="<?php echo $method_id; ?>"<?php if ($social_discount_discount_method == $method_id) { echo ' selected="selected"'; } ?>><?php echo $method_name; ?></option>
					<?php endforeach; ?>
				  </select></td>
			  </tr>
			  
			  <tr>
				<td valign="top"><?php echo $entry_discount_active_mark; ?><br/><span class="help"><?php echo $entry_discount_active_mark_help; ?></span></td>
				<td><textarea name="social_discount_active_mark" cols="80" rows="5"><?php echo $social_discount_active_mark; ?></textarea>
			  </tr>
			  
			  <tr>
				<td><?php echo $entry_discount_integration; ?><br/><span class="help"><?php echo $entry_discount_integration_help; ?></span></td>
				<td>
					<input type="checkbox" id="social_discount_integration_addthis_enabled" name="social_discount_integration_addthis_enabled"<?php echo $social_discount_integration_addthis_enabled ? ' checked="checked"' : ''; ?>/>
					<label for="social_discount_integration_addthis_enabled">AddThis</label>
					<br/>
					
					<input type="checkbox" id="social_discount_integration_pluso_enabled" name="social_discount_integration_pluso_enabled"<?php echo $social_discount_integration_pluso_enabled ? ' checked="checked"' : ''; ?>/>
					<label for="social_discount_integration_pluso_enabled">Pluso</label>
					<br/>
				</td>
			  </tr>
			  
			  <tr>
				<td><?php echo $entry_sort_order; ?></td>
				<td><input type="text" name="social_discount_sort_order" value="<?php echo $social_discount_sort_order; ?>" size="1" /></td>
			  </tr>
			  
			  <tr>
				<td><?php echo $entry_status; ?></td>
				<td><select name="social_discount_status">
					<?php if ($social_discount_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
			  </tr>
			</table>
		  </div>
		  <div id="tab-buttons">
			<input type="checkbox"<?php echo $social_discount_use_internal_buttons ? ' checked="checked"' : ''; ?> name="social_discount_use_internal_buttons" id="social_discount_use_internal_buttons"><label for="social_discount_use_internal_buttons">Использовать кнопки социальных сетей, заданные ниже.</label>
			
			<table class="form" id="social_discount_button_code">
			<tr>
				<td width="140">VK
				<td style="width: 420px;">
					<textarea name="social_discount_vk_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_vk_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Создайте код кнопки на странице <a target="_blank" href="http://vk.com/developers.php?p=Like">http://vk.com/developers.php?p=Like</a>.
						<li>Скопируйте его в поле слева.
					</ol>
				</td>
			</tr>
			<tr>
				<td width="140">Facebook
				<td>
					<textarea name="social_discount_fb_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_fb_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Перейдите на страницу <a target="_blank" href="http://developers.facebook.com/docs/reference/plugins/like/">http://developers.facebook.com/docs/reference/plugins/like/</a>.
						<li>Создайте код кнопки, при создании выберите HTML5 вариант.
						<li>Скопируйте его в поле слева.
					</ol>
				</td>
			</tr>
			
			<tr>
				<td width="140">Google Plus
				<td>
					<textarea name="social_discount_gp_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_gp_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Создайте код кнопки на странице <a target="_blank" href="https://developers.google.com/+/web/+1button/">https://developers.google.com/+/web/+1button/</a>.
						<li>Обязательно добавьте к тегу <pre>&lt;div class="g-plusone"&gt;</pre> атрибут <pre>data-callback="plusone_share"</pre>, вот так:<br/>
						<pre>&lt;div class="g-plusone" <b>data-callback="plusone_share"</b> data-annotation="inline" data-width="300"&gt;&lt;/div&gt;</pre>
					</ol>
				</td>
			</tr>
			
			<tr>
				<td width="140">Мой Мир
				<td>
					<textarea name="social_discount_mm_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_mm_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Создайте код кнопки на странице <a target="_blank" href="http://api.mail.ru/sites/plugins/share/">http://api.mail.ru/sites/plugins/share/</a>.
					</ol>
				</td>
			</tr>
			
			<tr>
				<td width="140">Одноклассники
				<td>
					<textarea name="social_discount_ok_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_ok_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Создайте код кнопки на странице <a target="_blank" href="http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=23167439">http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=23167439</a>.
					</ol>
				</td>
			</tr>
			
			<tr>
				<td width="140">Twitter
				<td>
					<textarea name="social_discount_tw_button_code" style="width: 400px; height: 70px;"><?php echo $social_discount_tw_button_code; ?></textarea>
				</td>
				<td valign="top" align="left">
					<ol>
						<li>Создайте код кнопки на странице <a target="_blank" href="https://twitter.com/about/resources/buttons#tweet">https://twitter.com/about/resources/buttons#tweet</a>.
					</ol>
				</td>
			</tr>
			</table>
		  </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs(); 

function apply(){
	$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
	$('#form').submit();
}
//--></script>
<script>
$(document).ready(function() {
	$('#social_discount_discount_type').change(function() {
		switch ( $(this).val() ) {
		case "0":
			$('.sd_type').html('%');
			break;
		case "1":
			$('.sd_type').html('<?php echo $this->config->get('config_currency'); ?>');
			break;
		}
	});
	
	$('#social_discount_use_internal_buttons').change(function() {
		if ('undefined' == typeof $(this).attr('checked')) {
			$('#social_discount_button_code textarea').attr("disabled", "disabled");
		} else {
			$('#social_discount_button_code textarea').removeAttr("disabled");
		}
	});
	
});			
</script>
<?php echo $footer; ?>