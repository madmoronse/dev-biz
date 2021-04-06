<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="language"><span class="tittle_text"><?php echo $text_language; ?></span>
		<div class="languages">
			<?php foreach ($languages as $language) { ?>
				<?php if ($language['code'] == $language_code) { ?>
					<div onclick="$('.languages-list').slideToggle(150);" class="language-show" title="<?php echo $language['name']; ?>">
						<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().submit();" />
						<?php echo $language['name']; ?>
					</div>
				<?php } ?>
			<?php } ?>
			<div class="languages-list">
				<?php foreach ($languages as $language) { ?>
					<?php if ($language['code'] == $language_code) { ?>
						<a title="<?php echo $language['name']; ?>"><b><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"/><?php echo $language['name']; ?></b></a>
					<?php } else { ?>
						<a title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().parent().parent().submit();"><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"/><?php echo $language['name']; ?></a>
					<?php }?>
				<?php }?>
			</div>
		</div>
    <input type="hidden" name="language_code" value="" />
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
  </div>
</form>
<?php } ?>
