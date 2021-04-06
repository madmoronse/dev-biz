<?php
	$customer_group_id = $this->customer->getCustomerGroupId();

?>
[{header}][{column_left}][{column_right}]
<?php if (isset($photo3d_path)) {  ?>
<script src="/catalog/view/theme/default2/assets/photo3d/photo3d.min.js"></script>
<link rel="stylesheet" type="text/css" href="/catalog/view/theme/default2/assets/photo3d/css/style.css">
<?php } ?>
<div id="content">[{content_top}]
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
	<!-- <h1><?php /* echo  $heading_title; */ ?></h1> -->
  <div class="product-info">

	<?php
		$PRODUCT_LABEL='';
		$this->load->model('catalog/category');
		$categories  = $this->model_catalog_product->getCategories($product_id);
                if ($categories){

			foreach ($categories as $category) {

				if($category['category_id'] == "7609") {

					$SKU_TIME_DELTA=(time()-strtotime($date_modified))/(60*60*24);
					if ($SKU_TIME_DELTA<11) {$PRODUCT_LABEL='<div class="product-info-label">NEW</div>';}
	
				}

				if($category['category_id'] == "7608") {

					//$PRODUCT_LABEL='<div class="product-info-label-opo">1+1=3</div>';
	
				}

				//if ($category['category_id'] == 7608) {$PRODUCT_LABEL='<div class="product-opo-1090">&nbsp;</div>';}


			}
		}
  ?>
  
	<div class="left">
	<?php if ($thumb || $images) { ?>
		<?php if ($thumb) { ?>
			<?php if (isset($photo3d_path)) {  ?>
				<div id="photo3d-mobile-container" style="height:300px; display: none;">
					<img style="width:100%; height: auto;" class="{maxZoom:100, frames:60, yframes:1, useSeparateFrames:true, startImmediately:true, autoPlay:true, horizontalRangeLeft:0, horizontalRangeRight:0, verticalRangeBottom:0, verticalRangeTop:90, firstFrameX:0, firstFrameY:0, productCode:'', useAlternativeRotation: true, minIntervalRotate: 286.666666666667}" src="<?php echo $photo3d_path ?>"/>
				</div>
			<?php } ?>
		<div class="image"><?php echo $PRODUCT_LABEL; ?><a href="<?php echo $popup; ?>" class="colorbox"><img src="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" oncontextmenu="/*return false;*/" /></a></div>

		<div class="real-products"><p>На сайте представлены фотографии реальных товаров</p></div>


		<?php } ?>
		<?php if ($images) { ?>

			<div class="image-additional">
				<?php foreach ($images as $image) { ?>
				<a href="<?php echo $image['popup']; ?>" class="<?php echo $image['class']; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" oncontextmenu="/*return false;*/" /></a>
				<?php } ?>
			</div>
		<?php } ?>
		<?php } ?>
    <!-- Тут начинаются табы-->
    
  <!-- Тут заканчиваются -->
</div> 




    <div class="right">
			<?php if (1==2){
			
			if ($discount > 0) { $special=str_replace(' ','',str_replace('ք','',$price)); $price=ceil(($special*(100/(100-$discount)))/10)*10; $special=number_format($special, 0, '.', ' ').' руб.';$price=number_format($price, 0, '.', ' ').' руб.'; ?>
			<div class="discount">
				<div class="discount-text">
					скидка
				</div>
				<div class="discount-inner">
					<span><?php echo $discount ?></span>
				</div>
			</div>
			<?php } 
			}
			?>
			<div class="description">
				<H1>

					<?php

						if (strlen($fullname)>0) {$header = $fullname;} else {$header=$mpn.' '.$name;}

						echo $header

					?>

				</H1>
				<div>Артикул:
					<?php echo $sku; ?>
					<?php if ($sku and $manufacturer) { echo ' / '; } ?>
					<?php if ($manufacturer) { ?>
						<?php /*echo <span> $text_manufacturer; </span>*/?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>
					<?php } ?>
				</div>





        <?php if ($reward) { ?>
        <?php /*echo <span>$text_reward</span>; */?> <?php //echo $reward; ?><!--<br />-->
        <?php } ?>
        <?php /*<span>echo $text_stock</span>; */?> <?php //echo $stock; ?></div>

      <?php if ($price) { ?>
      <div class="price">
				<?php echo '<span class="price-text">' . $text_price . '</span>'; ?>
        <?php if (!$special) { ?>
			
				<span class="price-default"><?php echo str_replace('ք','',$price); ?></span><span class="price-text"></span>
        <?php } else { ?>
        <span class="price-old"><?php echo $price; ?></span> <span class="price-new"><?php echo $special; ?></span>
        <?php } ?>
        <?php if ($tax) { ?>
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>
        <?php } ?>
        <?php if ($points) { ?>
        <span class="reward" style="display:none"><?php echo $text_points; ?> <?php echo $points; ?></span>
        <?php } ?>
        <?php if ($discounts) { ?>
        <br />
        <div class="discount">
          <?php foreach ($discounts as $discount) { ?>
          <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
          <?php } ?>
        </div>
        <?php } ?>

        <!-- Табы начались -->
        
  <!-- Табы закончились -->
	  <?php

		$super_ceni = 1;
		//$this->load->model('catalog/category');
		//$categories  = $this->model_catalog_product->getCategories($product_id);
		$customer_group_id = $this->customer->getCustomerGroupId();

                if ($categories){

			foreach ($categories as $category) {
							if($category['category_id'] == "1163") {

								if ($customer_group_id < 3) {
									$super_ceni = 2;
								}
							}
			}
		}





	  ?>

      </div>

  <?php
    if(0){

      echo json_encode($attribute_groups);
    }
  ?>

	<?php if ($attribute_groups) { ?>
      <?php foreach ($attribute_groups as $attribute_group) { ?>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
          <div class="attributes_name">
		<?php 

			if ($attribute['name']!='Пол') echo $attribute['name']. ': <label class="attributes_text">' . $attribute['text'] . '</span>'; 

		?>
	  </div>
        <?php } ?>
      <?php } ?>
	<?php } ?>

<?php 
		if ($super_ceni !=2 and ($customer_group_id == 1 or $customer_group_id == "") and 1==0) echo '<div class="free-delivery"><i class="fa fa-truck" aria-hidden="true"></i> Бесплатная доставка Почтой России при 100% предоплате заказов от&nbsp;3&nbsp;000&nbsp;рублей (кроме товаров по акции)</div>';
?>


      <?php } ?>

      <?php if ($options) { $pos_vans = strpos(strtolower($header), 'vans'); ?>
      <div class="options"><?php /*echo $text_option; */?>
        <br />
	<?php 
	if (!empty($productsize)) {
		echo '<h2>' . $productsize['caption'] . ' <a class="colorbox" href="' . $productsize['image'] . '" style="float:right;margin-top:-11px;outline: none;">' . $productsize['text'] . '<img src="/image/data/helpsizes/SizeHelp.png" style="position:relative; top:9px; width:40px;"></img></a></h2>';
	}
	?>
        <?php foreach ($options as $option) { ?>
		<?php if (count($option['option_value']) > 5) { ?>
		<script>
		$( document ).ready(function() {
		if (screen.width < 720) {
			$(".product-info .options").css({"height":"160px"})
		}
		});
		</script>
		<?php }?>

        <?php if ($option['type'] == 'select') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <select name="option[<?php echo $option['product_option_id']; ?>]">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
             (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>




        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <!--<span class="required">*</span>-->
          <?php } ?>
         <!-- <b><?php /*if ($option['name'] = "Размер") {echo "Выберите размер";} else {echo $option['name'];} */?>:</b><br />-->

          <?php foreach ($option['option_value'] as $option_value) { ?>

		  <?php if ($option_value['quantity'] > 0){ ?>

          <div style="display:inline-block; <?php $customer_group_id = $this->customer->getCustomerGroupId(); if ($customer_group_id == 1 or $customer_group_id == "" ) { echo 'width:66px';} else {echo 'width:66px';} ?>;" >
		  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

		  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>" <?php if ($customer_group_id != 1 and $customer_group_id != "" ) {echo 'style="padding:2px;padding-top:10px;padding-bottom:10px;margin-left:0px"';} ?> ><?php if ($option_value['option_id'] == 5) {echo $option_value['name'].' см';} else {echo $option_value['name'];} ?>

            <?php if ($option_value['price']) { ?>


            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)


            <?php } ?>




          </label>


		  </div>
          <!-- <br /> -->
          <?php } ?>
          <?php } ?>

        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'image') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <table class="option-image">
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <tr>
              <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                  <?php if ($option_value['price']) { ?>
                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                  <?php } ?>
                </label></td>
            </tr>
            <?php } ?>
          </table>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'text') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <textarea name="option[<?php echo $option['product_option_id']; ?>]"><?php echo $option['option_value']; ?></textarea>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'file') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'datetime') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'time') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
        </div>
        <br />
        <?php } ?>
        <?php } ?>
      </div>
      <?php } else {?>

	  <?php if ($customer_group_id != 1 and $customer_group_id != "") { echo " <div> Всего в наличии: "; if($stock > 10){ echo ">10";} else {echo $stock;} echo "</div>"; }?>
	  <?php }?>

      <div class="cart">
        <div>
          <?php /*<input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" /> */ ?>
          <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
          <input type="button" value="В корзину" 
			<?php /*запрещаем учетке для оптовиков покупать товар*/ if (2650 != $this->customer->getId()) echo 'id="button-cart"';?> 
			class="button" 
		  />
       

			<span class="links">
				<a onclick="addToWishList('<?php echo $product_id; ?>');"></a>
				<br />
			</span> 
		   
		   <?php if ($customer_group_id <= 2 and 1==0) { ?>
		   <a class="button-quick" id="button-quick">Купить в 1 клик</a>
		   <?php } ?>
		</div>

        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>



      </div>


   






      <?php if ($review_status) { ?>
      <div class="review">
      </div>
      <?php } ?>


<?php /*
	   <div id="tab-review" class="tab-content">
	   <h3 style="margin:0px;margin-bottom:20px;">Отзывы о товаре <?php if ($mpn) { echo $mpn . ' '; } ?>  <?php echo $name; ?><?php if ($manufacturer) {  echo ' ' . $manufacturer; } ?></h3>
    <div id="review"></div>
    <p id="review-title"><?php echo $text_write; ?></p>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b><br />
    <textarea name="text" cols="40" rows="8" style="width: 525px;"></textarea><br />
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" checked/>
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />

    <div class="buttons">
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  */ ?>

	<div id="vk_comment">
							<!-- Put this script tag to the <head> of your page -->
		<script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>

		<script type="text/javascript">/*
		VK.init({apiId: 5352709, onlyWidgets: true});
		*/</script>

		<!-- Put this div tag to the place, where the Comments block will be -->
		<div id="vk_comments"></div>
		<script type="text/javascript">/*
		VK.Widgets.Comments("vk_comments", {limit: 10, width: "auto", attach: "*"});*/
		</script>
	</div>



    </div>
  </div>
  
  <div class="tabs">   <!-- Начало табов -->

    <!-- Кнопки -->
    <?php $check = "";?>
    <?php if($description != "") {?>
      <input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
      <label for="tab-btn-1"><?php echo $tab_description; ?></label>
    <?php } else { ?>
    <?php $check = "checked";} ?>
    
    <?php if($this->customer->getCustomerGroupId() != 3) {?>
      <input type="radio" name="tab-btn" id="tab-btn-2" value="" <?php echo $check; ?>>
      <label for="tab-btn-2"><?php echo $tab_calc; ?></label>
    <?php }?>

    <?php if($this->customer->getCustomerGroupId() == 4 || $this->customer->getCustomerGroupId() == 3) {?>
      <?php if($this->customer->getCustomerGroupId() == 3 && $description == "") {?>
        <input type="radio" name="tab-btn" id="tab-btn-3" value="" <?php echo $check; ?>>
      <?php } ?>  
        <input type="radio" name="tab-btn" id="tab-btn-3" value="">
        <label for="tab-btn-3"><?php echo $tab_payment; ?></label>
    <?php } ?>

    <?php if($this->customer->getCustomerGroupId() != 4 && $this->customer->getCustomerGroupId() != 3) {?>
      <input type="radio" name="tab-btn" id="tab-btn-4" value="">
      <label for="tab-btn-4"><?php echo $tab_partnership; ?></label>
    <?php } ?>
    <!-- Конец блока с кнопками -->

    <!-- Начало блока с блоками -->

    <!-- Описание #tab1 -->
      <div class="tab-content" id="tab-1"><?php echo $description; ?>
      <?php if (0) { ?>
        <div class="tags"><b><?php echo $text_tags; ?></b>
          <?php for ($i = 0; $i < count($tags); $i++) { ?>
          <?php if ($i < (count($tags) - 1)) { ?>
          <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
          <?php } else { ?>
          <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
          <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
      </div>
      <!-- Конец Описание #tab1 -->

      <!-- Калькулятор #tab2 -->
      <div class="calc-block tab-content" id="tab-2">
        <div class="calc-data">
          <p class="title">Введите название Вашего населенного пункта:</p>
          <input name="np" id="np" class="large-field">
          <p style="font-size:10px;margin-top:3px;color:#ff5555;">Окончательная стоимость может отличаться на сумму до 200 рублей. Уточняйте у менеджера.</p> 
          <script type="text/javascript">
              $(document).ready(function() {
                $("#np").autocomplete({
                    source: function( request, response ) {
                        $.ajax({
                          url: "index.php?route=checkout/payment_address/address",
                          dataType: "json",
                          data: {
                              q: request.term,
                              thing: 'np'
                          },
                          success: function( data ) {
                            $('#calcResponse').html('');
                              //console.log(data);
                              // Handle 'no match' indicated by [ "" ] response
                              response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                          }
                        });
                    },
                    minLength: 2,
                    select: function( event, ui ) {
                      console.log(event);
                      console.log(ui);
                      //log( "Selected: " + ui.item.label );
              $("#calcResponse").LoadingOverlay("show");
                      $.ajax({
                          url: "index.php?route=checkout/payment_address/addresscalc",
                          dataType: "html",
                          data: {
                              q: ui.item.value
                          },
                          success: function( data ) {
                              $('#calcResponse').html(data);
                          }
              })
                .always(function() {
                  $("#calcResponse").LoadingOverlay("hide");
                });
                    }
                  });
              });
            </script>
        </div>
        <div class="calc-info" id="calcResponse">

        </div>
      </div>
      <!-- Конец Калькулятор #tab2 -->

      <!-- Оплата и доставка #tab3 -->
      <?php if($this->customer->getCustomerGroupId() == 4 || $this->customer->getCustomerGroupId() == 3) {?>
          <div class="tab-content" id="tab-3">
            <?php if($this->customer->getCustomerGroupId() == 4) {?>
              <?php echo $text_payment_drop; ?>
            <?php } else { ?>
              <?php echo $text_payment_opt;} ?>
          </div>
      <?php  } ?>
      <!-- Конец Оплата и доставка #tab3 -->

      <!-- Сотрудничество #tab4 -->
      <?php if($this->customer->getCustomerGroupId() != 4 && $this->customer->getCustomerGroupId() != 3) {?>
        <div class="tab-content" id="tab-4">
         <?php echo $text_partnership; ?>
        </div>
      <?php  } ?> 
      <!-- Конец Сотрудничество #tab4 -->

  </div> <!-- Конец табов -->


  <?php if ($review_status) { ?>
    
  <?php } ?>
    <?php if ($products) { ?>
		<div id="tab-related" class="tab-content" >
		<h3>РЕКОМЕНДАЦИЯ СТИЛИСТА К ЭТОМУ ТОВАРУ</h3>
        <p >Перед появлением каждого товара на сайте <b>наша команда стилистов</b> прорабатывает его сочетание в современном образе с другими товарами. Учитываются современные тренды, удобство и практичность</p>

		<div class="box-product" id="related-carousel" style="margin-bottom:45px;margin-top:15px;">
        <?php foreach ($products as $allproduct) {

			$categories  = $this->model_catalog_product->getCategories($allproduct['product_id']);

			if ($categories){
				foreach ($categories as $category) {

					$category_info = $this->model_catalog_category->getCategory($category['category_id']);

					if($category_info['name'] == "Кроссовки") {
							$RelatedProd[1][] = $allproduct;
					}
					if($category_info['name'] == "Одежда") {
							$RelatedProd[2][] = $allproduct;
					}
					if($category_info['name'] == "Аксессуары") {
							$RelatedProd[3][] = $allproduct;
					}
					if($category_info['name'] == "Акция") {
							$RelatedProd[4][] = $allproduct;
					}
				}
			}
			}
			if ( isset($RelatedProd)){

			if (isset($RelatedProd[1][0])) { $CurrentIndex = array_rand($RelatedProd[1], 1); $RelatedProdCurr[1] = $RelatedProd[1][$CurrentIndex]; unset($RelatedProd[1][$CurrentIndex]); }
			if (isset($RelatedProd[2][0])) { $CurrentIndex = array_rand($RelatedProd[2], 1); $RelatedProdCurr[2] = $RelatedProd[2][$CurrentIndex]; unset($RelatedProd[2][$CurrentIndex]); }
			if (isset($RelatedProd[3][0])) { $CurrentIndex = array_rand($RelatedProd[3], 1); $RelatedProdCurr[3] = $RelatedProd[3][$CurrentIndex]; unset($RelatedProd[3][$CurrentIndex]); }
			if (isset($RelatedProd[4][0])) { $CurrentIndex = array_rand($RelatedProd[4], 1); $RelatedProdCurr[4] = $RelatedProd[4][$CurrentIndex]; unset($RelatedProd[4][$CurrentIndex]); }

			if (!isset($RelatedProdCurr[1])) {
				if (isset($RelatedProd[2][0])) { $CurrentIndex = array_rand($RelatedProd[2], 1); $RelatedProdCurr[1] = $RelatedProd[2][$CurrentIndex]; unset($RelatedProd[2][$CurrentIndex]); }
				elseif (isset($RelatedProd[3][0])) { $CurrentIndex = array_rand($RelatedProd[3], 1); $RelatedProdCurr[1] = $RelatedProd[3][$CurrentIndex]; unset($RelatedProd[3][$CurrentIndex]); }
				elseif (isset($RelatedProd[4][0])) { $CurrentIndex = array_rand($RelatedProd[4], 1); $RelatedProdCurr[1] = $RelatedProd[4][$CurrentIndex]; unset($RelatedProd[4][$CurrentIndex]); }
			}

			if (!isset($RelatedProdCurr[2])) {
				if (isset($RelatedProd[1][0])) { $CurrentIndex = array_rand($RelatedProd[1], 1); $RelatedProdCurr[2] = $RelatedProd[1][$CurrentIndex]; unset($RelatedProd[1][$CurrentIndex]); }
				elseif (isset($RelatedProd[3][0])) { $CurrentIndex = array_rand($RelatedProd[3], 1); $RelatedProdCurr[2] = $RelatedProd[3][$CurrentIndex]; unset($RelatedProd[3][$CurrentIndex]); }
				elseif (isset($RelatedProd[4][0])) { $CurrentIndex = array_rand($RelatedProd[4], 1); $RelatedProdCurr[2] = $RelatedProd[4][$CurrentIndex]; unset($RelatedProd[4][$CurrentIndex]); }
			}

			if (!isset($RelatedProdCurr[3])) {
				if (isset($RelatedProd[1][0])) { $CurrentIndex = array_rand($RelatedProd[1], 1); $RelatedProdCurr[3] = $RelatedProd[1][$CurrentIndex]; unset($RelatedProd[1][$CurrentIndex]); }
				elseif (isset($RelatedProd[2][0])) { $CurrentIndex = array_rand($RelatedProd[2], 1); $RelatedProdCurr[3] = $RelatedProd[2][$CurrentIndex]; unset($RelatedProd[2][$CurrentIndex]); }
				elseif (isset($RelatedProd[4][0])) { $CurrentIndex = array_rand($RelatedProd[4], 1); $RelatedProdCurr[3] = $RelatedProd[4][$CurrentIndex]; unset($RelatedProd[4][$CurrentIndex]); }
			}

			if (!isset($RelatedProdCurr[4])) {
				if (isset($RelatedProd[1][0])) { $CurrentIndex = array_rand($RelatedProd[1], 1); $RelatedProdCurr[4] = $RelatedProd[1][$CurrentIndex]; unset($RelatedProd[1][$CurrentIndex]); }
				elseif (isset($RelatedProd[2][0])) { $CurrentIndex = array_rand($RelatedProd[2], 1); $RelatedProdCurr[4] = $RelatedProd[2][$CurrentIndex]; unset($RelatedProd[2][$CurrentIndex]); }
				elseif (isset($RelatedProd[3][0])) { $CurrentIndex = array_rand($RelatedProd[3], 1); $RelatedProdCurr[4] = $RelatedProd[3][$CurrentIndex]; unset($RelatedProd[3][$CurrentIndex]); }
			}


			$RelatedProdCurr = $products;//bmv не делим на категории, а выводим все связанные товары, если делить на группы, то эту строку закомментировать
			if ( isset($RelatedProdCurr)){
			foreach ($RelatedProdCurr as $product) { ?>

			<div class="product_div" style="margin-left:4px">
				<?php if ($product['thumb']) { ?>
				<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
				<?php } ?>
				<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['manufacturer'] . " " .$product['name']; ?></a></div>
				<?php if ($product['price'] and false)  { ?>
				<div class="price">
					<?php if (!$product['special']) { ?>
					<?php echo $product['price']; ?>
					<?php } else { ?>
					<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>					

					<div id="social_discount_active"<?php if ($social_discount == false) { echo ' style="display: none;"'; } else { echo ' style="display: inline-block"'; } ?>><?php echo $social_discount_active_mark; ?></div>

					<?php } ?>
				</div>
				<?php } ?>
				
				<?php echo '<div class="sku">Aртикул:' . $product["product_id"]  . '</div>';?>

			</div>
			<?php } ?>
			<?php } ?>
			<?php } ?>

		</div>
		</div>
  <?php } ?>

  [{content_bottom}]</div>
<script type="text/javascript">
$(document).ready(function() {
	$('.colorbox').colorbox({
		current: '',// "Фото {current} из {total}",
    title: false,
    arrowKey: false,
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox-imggal"
	});
});</script>

<script type="text/javascript">
	$(document).ready(function() {
		
		var htmlModal = '<div class="modal-one-click"> <div class="modal-one-click__title">Купить в 1 клик</div><div class="modal-one-click__close"></div><form id="modalOneClickForm" action="" method="get"> <div> <label for="name">Ваше имя <span class="required">*</span></label> <input id="name" type="text" required> </div><div> <label for="phone">Контактный телефон <span class="required">*</span></label> <input id="phone" type="text" required> </div><div> <label for="city">Город <span class="required">*</span></label> <input id="city" type="text" required> </div><div> <label for="comments">Комментарий к заказу</label> <textarea id="comments" cols="10" rows="2"></textarea> </div><div class="modal-one-click__footer"> <button type="submit">Отправить</button> <div class="text"><span class="required">*</span> - обязательно для заполнения</div></div></form> </div>';
		var htmlModalSuccess = '<div class="modal-one-click"> <div class="modal-one-click__title">Купить в 1 клик</div><div class="modal-one-click__close"></div><div class="modal-one-click__body"> __message__ </div></div>';
		var htmlModalError = '<div class="modal-one-click"> <div class="modal-one-click__title modal-one-click__title--error">Купить в 1 клик</div><div class="modal-one-click__close"></div><div class="modal-one-click__body"> <div class="modal-one-click__head"> Ошибка </div><div class="modal-one-click__text"> __error__ </div></div></div>';
		
		var openModal = function (htmlModal, customClass, callback) {
			var neosModal = Neos.modal(htmlModal, { onShow: callback });
			if (customClass) {
				$('.popup-wrapper').addClass(customClass);
			}
			$('.modal-one-click__close').bind('click', neosModal.close);
		}

		var initFormMethods = function () {
			var unexpectedError = function () {
				openModal(htmlModalError.replace('__error__', 'Возникла непредвиденная ошибка. Попробуйте позже!'), 'oneClickModal');
			};
			$('#modalOneClickForm').submit(function(evt) {
				evt.preventDefault();
				var href = window.location.origin + '/index.php?route=module/oneclickbuy/submit';
				$.ajax({
					url: href,
					dataType: 'json',
					type: 'post',
					data: {
						name: $('#name').val(),
						tel: $('#phone').val(),
						city: $('#city').val(),
						comment: $('textarea#comments').val(),
						page: window.location.href
					},
					beforeSend: function () {
						Neos.modal().close()
					},
					success: function(json) {
						if (json.message) {
							openModal(htmlModalSuccess.replace('__message__', json.message), 'oneClickModalSuccess');
						} else if (json.error) {
							openModal(htmlModalError.replace('__error__', json.error), 'oneClickModal');
						} else {
							unexpectedError()
						}
					},
					error: function (data) {
						unexpectedError()
					}
				});
			});
		};

		$('#button-quick').bind('click', function() {
			openModal(htmlModal, 'oneClickModal', initFormMethods);
		});
	});
</script>

<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
		
	$.ajax({
  	url: 'index.php?route=checkout/cart/getPromoInfo',
  	type: 'post',
  	dataType: 'json',
  	beforeSend: function(){
  	},
  	success: function(json) {
		if(json === null){
			var promoinfo = '';
		} else {
			promoinfo = '<a href="' + json['promo_banner_url'] + '"><img src="' + json['promo_banner'] + '" /></a>'
		}
		
		$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
					alert(json['error']['option'][i]);

					}
				}
				if (json['error']['warning']) {
					Neos.alert(json['error']['warning'], 'error');
				}
			}

			/** By Neos - New Version - START */
				if (json['success']) {
					if (json.gifts) {
						Gift.renderPopup(json.gifts);
						return false;
					}
					$('#notification').before('<div class="backg_notif"></div>')
														.html(NeosCart.renderNotification(
															json['success'], 
															<?php echo (int) ($super_ceni == 2) ?>, promoinfo)
														);
					$('#productsincart').html(NeosCart.renderProducts(json['products']))	
					$('.success').fadeIn('slow');
					$('#cart-total').html(json['total']);
					$('html, body').animate({ scrollTop: 0 }, 'slow');
					// Close 
					$('.close_success').on('click', function(){
							$('.close').trigger('click');
							$('.backg_notif').remove();
					});
					// Close
					$('.close').on('click', function(){
							$('.backg_notif').remove();
					});

			}
			/** By Neos - New Version - END */
		}
	});
	  
		}
	});
	
	
	
});
function owlCarousel() {
	$(".image-additional").addClass('owl-carousel').addClass('owl-theme').owlCarousel({
		responsiveBaseWidth: ".image-additional",
		itemsCustom: [[400, 4]]
	});
}
function init3d() {
	if ($('#photo3d-mobile-container').length === 0) {
		return;
	}
	if ($(window).width() < 768) {
		$('#photo3d-mobile-container').show();
		var imgHeight = $('#image').outerHeight();
		$('#image').hide();
		if (!$('#photo3d-mobile-container').find('span').length) {
			$('#photo3d-mobile-container').height(imgHeight);
			$('#photo3d-mobile-container').find('img').Photo3Dconfig();
		}
	} else {
		$('#photo3d-mobile-container').hide();
		$('#image').show();
	}
}
init3d();
owlCarousel();
$(window).on('resize', init3d);
// 3d photo click
$('.photo3d-thumb').on('click', function(event) {
	event.preventDefault();
	// Show popup
	if ($(window).width() >= 768) {
		var href = $(this).attr('href');
		var image = new Image();
		image.onload = function() {
			$.colorbox({
				html: '<img id="3d-photo-colorbox" style="max-height: 600px" class="{maxZoom:100, frames:60, yframes:1, useSeparateFrames:true, startImmediately:true, autoPlay:true, horizontalRangeLeft:0, horizontalRangeRight:0, verticalRangeBottom:0, verticalRangeTop:50, firstFrameX:0, firstFrameY:0, productCode:\'\', useAlternativeRotation: true, minIntervalRotate: 286.666666666667}" src="'+href+'"/>',
				scrolling: false,
				onComplete: function() {
					$('#3d-photo-colorbox').Photo3Dconfig();
				}
			});
		};
		image.src = href;
	} else {
		$('#image').hide();
		$('#photo3d-mobile-container').show();
	}
});

$('#button-quick-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
					alert(json['error']['option'][i]);

					}
				}
			}

			if (json['success']) {

			 $.colorbox({
				href: './index.php?route=module/fastorderdialog/open',
				iframe: true,
				width: '530',
				height: '790',
				overlayClose: false
			});

			}
		}
	});
});



//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default2/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);

		$('.error').remove();

		if (json['success']) {
			alert(json['success']);

			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
		}

		if (json['error']) {
		alert(json['error']);
			//$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
		}

		$('.loading').remove();
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');

	$('#review').load(this.href);

	$('#review').fadeIn('slow');

	return false;
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default2/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}

			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
				$('#review-form').hide();
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	if ($.browser.msie && $.browser.version == 6) {
		$('.date, .datetime, .time').bgIframe();
	}

	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
});
//--></script>
<script type="text/javascript">
	$(document).ready(function() {

		$('.cart input[type="text"]').before('<div class="plus-minus"><span class="plus"></span><span class="minus"></span></div>');
    $('.cart .minus').click(function () {
        var $input = $('.cart input[type="text"]');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.cart .plus').click(function () {
        var $input = $('.cart input[type="text"]');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
        });
	});
</script>
<h3>Альтернативные товары:</h3>
<div class="product-grid similar-products">
  <?php
    if($similar){
      foreach($similar as $sim_product){
        $price = floatval($sim_product['price']);
        if (!$sim_product['special']) {
              $price = intval($price) . ' руб.';
          } else {
              $price = '<span class="price-old">' . intval($price) . ' руб.' . '</span> <span class="price-new">' . $sim_product['special'] . '</span>';
          }
        echo <<<HTML
        <div>
          <div class="image">
            <a href="{$sim_product['href']}">
              <img src="{$sim_product['thumb']}">
            </a>
          </div>
          <div class="name">
              <a href="{$sim_product['href']}">{$sim_product['mname']} {$sim_product['name']}</a>
              <!-- <div class="sku">артикул: {$sim_product['sku']}</div> -->
          </div>
          <div class="price">
            {$price}
          </div>
        </div>
HTML;
      }
    }
  ?>
</div>


<script>
	$(document).ready(function() {
		$("#related-carousel").owlCarousel({
		autoPlay : true,
		rewindSpeed: 1,
		scrollPerPage: true,
		 stopOnHover : true,
			responsiveBaseWidth: "#related-carousel",
			itemsCustom: [[190, 1],[670, 3], [890, 4]]
		});
	});
</script>


[{footer}]

