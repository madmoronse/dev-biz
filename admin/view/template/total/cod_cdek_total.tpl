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
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><?php echo $entry_title; ?></td>
						<td>
							<?php foreach ($languages as $language) { ?>
							<input type="text" name="cod_cdek_total_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cod_cdek_total_title[$language['language_id']]) ? $cod_cdek_total_title[$language['language_id']] : ''; ?>" />
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_sort_order; ?></td>
						<td><input type="text" name="cod_cdek_total_sort_order" value="<?php echo $cod_cdek_total_sort_order; ?>" size="1" /></td>
					</tr>
				</table>
				<input type="hidden" name="cod_cdek_total_status" value="1" />
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?>