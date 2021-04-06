<?php if (count($currencies) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="currency"><span class="tittle_text"><?php echo $text_currency; ?></span>
		<div class="currensys">
			<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $currency_code) { ?>
					<div onclick="$('.currensys-list').slideToggle(150);" class="currensy-show" title="<?php echo $currency['title']; ?>"><?php echo $currency['code']; ?></div>
				<?php } ?>
			<?php } ?>
			<div class="currensys-list">
				<?php foreach ($currencies as $currency) { ?>
					<?php if ($currency['code'] == $currency_code) { ?>
						<?php if ($currency['symbol_left']) { ?>
							<a title="<?php echo $currency['title']; ?> (<?php echo $currency['symbol_left']; ?>)"><b><?php echo $currency['title']; ?></b></a>
						<?php } else { ?>
							<a title="<?php echo $currency['title']; ?> (<?php echo $currency['symbol_right']; ?>)"><b><?php echo $currency['title']; ?></b></a>
						<?php } ?>
					<?php } else { ?>
						<?php if ($currency['symbol_left']) { ?>
							<a title="<?php echo $currency['title']; ?> (<?php echo $currency['symbol_left']; ?>)" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().parent().parent().parent().submit();"><?php echo $currency['title']; ?></a>
						<?php } else { ?>
							<a title="<?php echo $currency['title']; ?> (<?php echo $currency['symbol_right']; ?>)" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().parent().parent().parent().submit();"><?php echo $currency['title']; ?></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
    <input type="hidden" name="currency_code" value="" />
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
  </div>
</form>
<?php } ?>
