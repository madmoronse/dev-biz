<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
      <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  
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
  <div class="product-grid" >
    <?php foreach ($products as $product) { ?>
    <div>
      <?php if ($product['thumb']) { ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
      <?php } ?>
	  
	  
	  <div class="name">
		
		<a href="<?php echo $product['href']; ?>" style="font-weight:bold;font-size:14px;color:#333;"><?php echo $product['manufacturer'] . " " . $product['name']; ?></a> 
		<div><?php echo $product['sku']; ?></div>
	  </div>
      <div class="description"><?php echo $product['description']; ?></div>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>
        <?php echo $product['price']; ?>
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
							  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" style="display: none;"/>
							  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
							  </label>
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
      
      <div class="cart">
        <?php $super_ceni = 1;
	  $this->load->model('catalog/category');
	  $categories  = $this->model_catalog_product->getCategories($product['product_id']);
                if ($categories){
                    foreach ($categories as $category) {
                        if($category['category_id'] == 1163) { $super_ceni = 2;
                   }
                }
			} ?>
	  
	  
        <input type="button" value="<?php echo $button_cart; ?>" onclick="add_bc('<?php /*запрещаем учетке для оптовиков покупать товар*/ if (2650 != $this->customer->getId()) echo $product['product_id'] . "','" . $super_ceni; ?>');" class="button" />
      </div>
      <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
      <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
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
 
  </div><?php echo $content_bottom; ?>
 <script type="text/javascript"><!--
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
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
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';

						
			$(element).html(html);
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');
		
		$.cookie('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
					
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
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
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			
			$(element).html(html);
		});	
					
		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');
		
		$.cookie('display', 'grid');
	}
}

view = $.cookie('display');

if (view) {
	display(view);
} else {
	display('grid');
}
//--></script> 
<?php echo $footer; ?>