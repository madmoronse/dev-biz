    <?php foreach ($products as $product) { 

			$SKU_LAB_SHOW='';$YOPO='';
			if ($product['product_label']=='novinka') {$SKU_LAB_SHOW='<div class="grid-label-block">NEW</div>';}
			if ($product['product_label']=='all1090') {$SKU_LAB_SHOW='<div class="grid-label-block2">&nbsp;</div>';}


    ?>
    <div><?php echo $SKU_LAB_SHOW; ?>
			<?php if ($product['discount'] > 0) { $product['special']=str_replace(' ','',str_replace('ք','',$product['price'])); $product['price']=ceil(($product['special']*(100/(100-$product['discount'])))/10)*10;$product['special']=number_format($product['special'], 0, '.', ' ').' руб.';$product['price']=number_format($product['price'], 0, '.', ' ').' руб.'; ?>
			<div class="product-discount">
				<span><?php echo $product['discount'] ?></span>
			</div>
			<?php } ?>
      <?php if ($product['thumb']) { ?>
      <div class="image">
          <a href="<?php echo $product['href']; ?>">
              <img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
              <?php if (isset($category_sticker_image)){ ?>
                  <img src="image/<?php echo $category_sticker_image; ?>" style="position: absolute;left: 20px;"/>
              <?php } else {
                  if ($product['category_sticker_image']){ ?>
                    <img src="image/<?php echo $product['category_sticker_image']; ?>" style="position: absolute;left: 20px;"/>
                  <?php }
              } ?>
          </a>
      </div>
      <?php } ?>

		<?php $show_additional_images = 0; if ($show_additional_images == 1) { ?>
			<?php if (isset($product['thumb2'])) { ?>
				<div class="image2"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb2']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
			<?php } ?>
		<?php } ?>

	  	  <?php if ($product['options']) { echo '<div class="options" id="option_';?>
				<?php echo $product['product_id']; ?>">
					<?php foreach ($product['options'] as $option) { ?>
						<?php if ($option['type'] == 'radio') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php if ($option['name']='Размер'){echo 'Размеры в наличии';}; ?>:</b><br style="margin-bottom: 1x;" />
							  <?php foreach ($option['option_value'] as $option_value) { ?>
							  <?php if ($option_value['quantity'] > 0){ ?>
							  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" style="display: none;"/>
							  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
							  </label>
							<?php } ?>
							<?php } ?>
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'select') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <select name="option[<?php echo $option['product_option_id']; ?>]">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach ($option['option_value'] as $option_value) { ?>
								<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
								</option>
								<?php } ?>
							  </select>
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'checkbox') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <?php foreach ($option['option_value'] as $option_value) { ?>
							  <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
							  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
							  </label>
							  <br />
							  <?php } ?>
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'image') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
								<table class="option-image">
								  <?php foreach ($option['option_value'] as $option_value) { ?>
								  <tr>
									<td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
									<td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
									<td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
									  </label></td>
								  </tr>
								  <?php } ?>
								</table>
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'text') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'textarea') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'file') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <a id="button-option-<?php echo $option['product_option_id']; ?>" class="button"><span><?php echo $button_upload; ?></span></a>
							  <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'date') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'datetime') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
							</div>
						<?php } ?>
						<?php if ($option['type'] == 'time') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php echo $option['name']; ?>:</b><br style="margin-bottom: 6px;" />
							  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
							</div>
						<?php } ?>
					<?php } ?>


			<?php echo '</div>'; } else {echo '<b class="in_stock">В наличии!</b></br>';}?>


	  <div class="name">

		<a href="<?php echo $product['href']; ?>" style=""><?php echo $product['fullname']; ?></a>
		<div><?php echo $product['sku']; ?></div>
	  </div>
      <div class="description"><?php echo $product['description']; ?></div>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>

		<?php if (isset($product['new_old_price']) && $product['new_old_price'] != 0) {?><span class="price-old"><?php echo $product['new_old_price']; ?> руб.</span><?php } ?>

        <?php 

		echo str_replace('ք','руб.',$product['price']); 

	?>
        <?php } else { ?>
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
        <?php } ?>
        <?php if ($product['tax']) { ?>
        <br />
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if ($product['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
      <?php } ?>
      <div class="cart">
        <input type="button" value="<?php echo $button_cart; ?>" onclick="add_bc('<?php echo $product['product_id']; ?>');" class="button" />
      </div>
      <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
      <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
    </div>
    <?php } ?>
