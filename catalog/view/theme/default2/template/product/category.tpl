[{header}][{column_left}][{column_right}]

<div id="content">
  <div class="breadcrumb" >
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>

	 <div class="limit" style="float:right;"><b><?php echo $text_limit; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>


  </div>
	<div style="clear: right;">
		[{content_top}]
	</div>

	<div class="showbox">
		<div class="loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
			</svg>
		</div>
		<div class="showbox__text">Пожалуйста, подождите, идет загрузка</div>
	</div>


  <!-- <h1 class="category-h1" ><?php /* echo $heading_title; */ ?></h1> -->

    <?php $customer_group_id = $this->customer->getCustomerGroupId();
	  if (isset($this->request->get['path'])) { //тек. категория. null - если не раздел категорий
		$allCategories = explode('_',$this->request->get['path']);
        $category = end($allCategories);
                } else {
        $category = null;
                }
				if ($category == 1163 ){
							if ($customer_group_id < 2) { ?>
								<!--<div class="wrapper" style="text-align:center;"><a style="text-decoration:none;color:red;font-size:24px;" href="index.php?route=information/information&information_id=19">Условия акции «SALE»</a></div>-->
						<?php
							}
						}
				 ?>

  <?php if ($categories) { ?>
  <div class="category-list" style="display:none;">
	<h2><?php echo $text_refine; ?></h2>
    <ul>
      <?php foreach ($categories as $category) { ?>
      <li><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>"></a><span><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></span></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <?php if ($products) { ?>
  <div class="product-filter" style="display:none;">
    <div class="display"><b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><?php echo $text_grid; ?></a></div>

	<div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
    <div class="sort"><b><?php echo $text_sort; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="product-grid">
    <?php 

	$customer_group_id = $this->customer->getCustomerGroupId();

		foreach ($products as $product) { 

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
	<div id="grid-image-block">
      <?php if ($product['thumb']) { ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['fullname']; ?>" /></a></div>
      <?php } ?>

	  <?php $show_additional_images = 0; if ($show_additional_images == 1) { ?>
			<?php if (isset($product['thumb2'])) { ?>
				<div class="image2"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb2']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
			<?php } ?>
		<?php } ?>
	</div>



	  <div class="name">

		<?php

			if (strlen($product['fullname'])>0) { ?>

				<a href="<?php echo $product['href']; ?>"><?php echo $product['fullname']; ?></a>

			<?php } else { ?>

				<a href="<?php echo $product['href']; ?>"><?php echo $product['manufacturer'] . " " . $product['name']; ?></a>

			<?php } ?>

		<div><?php

			$customer_group_id = $this->customer->getCustomerGroupId();
			/*if ($customer_group_id>1)*/ echo $product['sku'];

		?></div>
	  </div>
      <div class="description"><?php echo $product['description']; ?></div>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>



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


	  <?php if ($product['options']) { echo '<div class="options" id="option_';?>
				<?php echo $product['product_id']; ?>">
					<?php foreach ($product['options'] as $option) { ?>
						<?php $exist = array_reduce($option['option_value'], function($carry, $item) { return $carry + $item['quantity']; }, 0); ?>
						<?php if ($option['type'] == 'radio' && $exist) { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
							  <b><?php if ($option['name']='Размер'){echo 'Размеры в наличии';}; ?>:</b><br style="margin-bottom: 1x;" />
							  <?php foreach ($option['option_value'] as $option_value) { ?>
							  <?php if ($option_value['quantity'] > 0){ ?>
							  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" style="display: none;"/>
							  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" onclick="window.location.href='<?php echo $product['href']; ?>'"><?php echo $option_value['name']; ?>
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

      <!--<div class="rating"><img src="catalog/view/theme/default2/image/stars-<?php /* echo $product['rating']; */?>.png" alt="<?php /*echo $product['reviews']; */?>" /></div> -->

     <?php /*?> <div class="cart">
        <?php $super_ceni = 1;
		$customer_group_id = $this->customer->getCustomerGroupId();
	  $this->load->model('catalog/category');
	  $categories  = $this->model_catalog_product->getCategories($product['product_id']);
                if ($categories){
                    foreach ($categories as $category) {
                        if($category['category_id'] == 1163) {
							if ($customer_group_id < 3) {
								$super_ceni = 2;
							}
						}
					}
				} ?>


        <input type="button" value="<?php echo $button_cart; ?>" onclick="add_bc('<?php /*запрещаем учетке для оптовиков покупать товар*/ /* if (2650 != $this->customer->getId()) echo $product['product_id'] . "','" . $super_ceni; ?>'); return true;" class="button" />
      </div>
      <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
      <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div><?php */?>
    </div>
    <?php } ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>

    <?php /*if ($thumb || $description) { echo '<div class="category-info">' */ ?>

    <?php /* if ($thumb) { */ ?>
    <!-- <div class="image"><img src="<?php /*echo $thumb; */ ?>" alt="<?php /* echo $heading_title; */ ?>" /></div> -->
    <?php /* } */ ?>
    <?php if ($description && $description != "<p></p>") { echo '<div class="category-info">' ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <?php /* } */ ?>


  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  [{content_bottom}]
<script type="text/javascript"><!--
function display(view) {
	if (view == 'list') {
		/*view = 'grid';

		$('.product-grid').attr('class', 'product-list');

		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '<div class="category_help"><b>Добавить в избранное</b><i></i></div></div>';



			html += '  <div class="compare">' + $(element).find('.compare').html() + '<div class="category_help"><b>Добавить к сравнению</b><i></i></div></div>';
			html += '</div>';

			html += '<div class="left">';

			var image = $(element).find('.image').html();

			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}

			var price = $(element).find('.price').html();

			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}

			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';



			var option = $(element).find('.options').html();
			if (option != null) {
				html += '  <div class="options">' + option + '</div>';
			}

			var rating = $(element).find('.rating').html();




			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}

			html += '</div>';

			$(element).html(html);
		});

		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');

		$.totalStorage('display', 'list');
	*/} else {
		$('.product-list').attr('class', 'product-grid');

		$('.product-grid > div').each(function(index, element) {
			html = '';

			var sku_label = $(element).find('.grid-label-block').html();
			    sku_label2 = $(element).find('.grid-label-block2').html();

			if (sku_label != null) {
				html += '<div class="grid-label-block">' + sku_label + '</div>';
			}

			if (sku_label2 != null) {
				html += '<div class="grid-label-block2">' + sku_label2 + '</div>';
			}


			html += '<div id="grid-image-block">';

			var image = $(element).find('.image').html();

			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}


			var image2 = $(element).find('.image2').html();

			if (image2 != null) {
				html += '<div class="image2">' + image2 + '</div>';
			}
			html += '</div>';
			var discount = $(element).find('.product-discount').html();
			if (discount != null) {
		  	html += '<div class="product-discount">' + $(element).find('.product-discount').html() + '</div>';
			}
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';

			var price = $(element).find('.price').html();

			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}


			var rating = $(element).find('.rating').html();

			/*if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}*/
			var option = $(element).find('.options').html();
			if (option != null) {
				html += '  <div class="options">' + option + '</div>';
			}

			var in_stock = $(element).find('.in_stock').html();
			if (in_stock != null) {
				html += ' <b class="in_stock">' + in_stock + '</b></br>';
			}

			//html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			//html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '<div class="category_help"><b>Добавить в избранное</b><i></i></div></div>';
			//html += '<div class="compare">' + $(element).find('.compare').html() + '<div class="category_help"><b>Добавить к сравнению</b><i></i></div></div>';

			$(element).html(html);
		});

		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');

		$.totalStorage('display', 'grid');
	}
	$(document).ready(name_scroll);
}

view = $.totalStorage('display');

if (view) {
	//display(view);
	display('grid');
} else {
	display('grid');
}


		/*$('.product-grid .image a img').live('hover', function() {
			$(this).parent().parent().hide();
		})
		$('.product-grid .image a img').live('mouseleave', function() {
			$(this).parent().parent().parent().find(".image2").show();
		});



		$('.product-grid .image2 a img').live('mouseleave', function() {
			$(this).parent().parent().hide();
		})
		$('.product-grid .image2 a img').live('mouseleave', function() {
			$(this).parent().parent().parent().find(".image").show();
		});*/



//--></script>


[{footer}]
