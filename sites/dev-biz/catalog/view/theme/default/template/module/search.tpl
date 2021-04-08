<?php $customer_group_id = $this->customer->getCustomerGroupId(); ?>
<?php echo str_replace('Поиск - ','',$header); ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

<div class="content" style="border-radius: 4px; box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);-webkit-border-radius: 4px;-moz-border-radius: 4px;">
  <?php echo '<h1 style="margin-bottom: 0px;font-family:\'BebasNeueRegular\';font-size:40px;color:#333;">' . ucwords(str_replace('Поиск - ','',$heading_title)) . '</h1>' ; ?>
</div>
  <?php /*echo '<b>' . $text_critea . '</b>'; */ ?>



  <?php /*echo '<h2>' . $text_search . '</h2>'; */?>

  <?php if ($products) { ?>

  <div class="product-list">
    <?php 

	$customer_group_id = $this->customer->getCustomerGroupId();

		foreach ($products as $product) { 
			$SKU_LAB_SHOW='';$YOPO='';
			if ( $product['product_label']=='novinka' )	{$SKU_LAB_SHOW='<div class="grid-label-block">NEW</div>';}
			if ($product['product_label']=='all1090') {$SKU_LAB_SHOW='<div class="grid-label-block2">&nbsp;</div>';}


    ?>
    <div><?php echo $SKU_LAB_SHOW; ?>
	<?php if ($product['discount'] > 0) { $product['special']=str_replace(' ','',str_replace('ք','',$product['price'])); $product['price']=ceil(($product['special']*(100/(100-$product['discount'])))/10)*10;$product['special']=number_format($product['special'], 0, '.', ' ').' руб.';$product['price']=number_format($product['price'], 0, '.', ' ').' руб.'; ?>
	<div class="product-discount">
		<span><?php echo $product['discount'] ?></span>
	</div>
	<?php } ?>
      <?php if ($product['thumb']) { ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
      <?php } ?>
	  
	  
	  <?php if ($product['thumb2']) { ?>
			<div class="image2"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb2']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
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
					
				
			<?php echo '</div>'; } ?>
	  
      <div class="name"><a href="<?php echo $product['href']; ?>" style="font-weight:bold;font-size:14px;color:#333;">

	<?php 

		if (strlen($product['fullname'])>0) {

			echo $product['fullname']; 

		} else {

			echo $product['manufacturer'] . " " . $product['name']; 

		}
		

	?></a>

	  <div><?php echo $product['sku']; ?></div>
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

      <!--<div class="rating"><img src="catalog/view/theme/default2/image/stars-<?php /*echo $product['rating']; */?>.png" alt="<?php /*echo $product['reviews'];*/ ?>" /></div>-->

	  
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
	  
	  
      <?php /*?><div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="add_bc('<?php echo $product['product_id'] . "','" . $super_ceni; ?>');" class="button" /></div>
      <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
      <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div><?php */?>
    </div>
    <?php } ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#content input[name=\'search\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'filter_category_id\']').bind('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').attr('disabled', 'disabled');
		$('input[name=\'sub_category\']').removeAttr('checked');
	} else {
		$('input[name=\'sub_category\']').removeAttr('disabled');
	}
});

$('select[name=\'filter_category_id\']').trigger('change');

$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';
	
	var search = $('#content input[name=\'search\']').attr('value');
	
	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var filter_category_id = $('#content select[name=\'filter_category_id\']').attr('value');
	
	if (filter_category_id > 0) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}
	
	var sub_category = $('#content input[name=\'sub_category\']:checked').attr('value');
	
	if (sub_category) {
		url += '&sub_category=true';
	}
		
	var filter_description = $('#content input[name=\'description\']:checked').attr('value');
	
	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

function display(view) {

	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
						
			var image2 = $(element).find('.image2').html();

			if (image2 != null) {
				html += '<div class="image2">' + image2 + '</div>';
			}
			
			
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';
						
			$(element).html(html);
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');
		
		$.totalStorage('display', 'list'); 
	} else {
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

			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
								
			
			var image2 = $(element).find('.image2').html();

			if (image2 != null) {
				html += '<div class="image2">' + image2 + '</div>';
			}
			
			
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
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
			
			var option = $(element).find('.options').html();
			if (option != null) {
				html += '  <div class="options">' + option + '</div>';
			}	
						
			//html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			//html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			//html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';
			
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
<?php echo $footer; ?>