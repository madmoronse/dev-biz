<?php if($tags || $categories || $options || $manufacturers || $attributes || $price_slider) { ?>
<div id="filterpro_box" class="box" style="border-radius: 0px 0px 4px 4px!important;">
<!--<div class="box-heading"><?php /* echo $heading_title; */ ?><div style="height: 15px; float: right;">
	</div></div> -->
<div class="box-content filterpro" >
<form id="filterpro">
<?php if (count($discounts) != 0) { ?>
<div class="filterpro__section">
	<div class="filterpro__title filterpro__title--big">
		Размер скидки:
	</div>
	<div class="option_box filterpro-discount">
		<input type="radio" class="filterpro-discount" id="discount_all">
		<label for="discount_all">Все</label>
	</div>
	<?php foreach ($discounts as $discount) { ?>
	<div class="option_box filterpro-discount" <?php if ($request_discount == $discount['value']) echo ' active'; ?>>
		<input type="radio" class="filterpro-discount" name="discount" id="discount_<?php echo $discount['value']?>" value="<?php echo $discount['value']?>"><label for="discount_<?php echo $discount['value']?>"><?php echo $discount['value']?>%</label>
	</div>
	<?php } ?>
</div>
<?php } ?>
<div class="filterpro__section">
	<div class="filterpro__title">
		Фильтры:
	</div>
	<div class="option_box" <?php if(!$instock_visible) echo 'style="display:none"'; ?>>
		<input type="checkbox" class="filtered" name="instock" id="instock" <?php if($instock_checked) echo 'checked="checked"'; ?>><label for="instock"><?php echo $text_instock?></label>
	</div>


		<?php if($manufacturers) { ?>
		<?php foreach($manufacturers as $manufacturer) { ?>
		<input type="hidden" class="m_name" id="m_<?php echo $manufacturer['manufacturer_id']?>" value="<?php echo $manufacturer['name']?>">
			<?php } ?>
		<?php } ?>

		<?php if($options) { ?>
		<?php foreach($options as $option) { ?>
			<?php foreach($option['option_values'] as $option_value) { ?>
			<input type="hidden" class="o_name" id="o_<?php echo $option_value['option_value_id']?>" value="<?php echo $option_value['name']?>">
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<?php if($category_id !== false) { ?>
			<input type="hidden" name="category_id" value="<?php echo $category_id ?>">
		<?php } ?>
		<?php if(isset($manufacturer_id)) { ?>
			<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id ?>">
		<?php }?>
	<input type="hidden" name="page" id="filterpro_page" value="0">
	<input type="hidden" name="path" value="<?php echo $path ?>">
	<input type="hidden" name="sort" id="filterpro_sort" value="">
	<input type="hidden" name="order" id="filterpro_order" value="">
	<input type="hidden" name="limit" id="filterpro_limit" value="">
	<input type="hidden" id="filterpro_container" value="<?php echo $filterpro_container?>">
	<script type="text/javascript">
		function afterload(){
			<?php echo $filterpro_afterload; ?>
		}
	</script>

	<div class="option_box" <?php if(!$price_slider) { //echo 'style="display:none"';
	}?>style="max-width: 210px;min-width: 125px;">
	<div class="option_name hided"><?php echo $text_price_range?></div>
	<div class="price_slider collapsible" style="display:none;">
		<?php if($symbol_left){ echo "<label>". $symbol_left  . "</label>";}?>
		<label>от</label>
		<input type="text" id="min_price" value="0" name="min_price" readonly="readonly" class="price_limit">
		<label> до </label>
		<input type="text" id="max_price" value="0" name="max_price" readonly="readonly" class="price_limit">
		<?php if($symbol_right){ echo "<label>" . $symbol_right . "</label>";}?>
		<div id="slider-range"></div>
	</div>
	</div>

		<?php if($categories) { ?>
	<div class="option_box" style="min-width: 100px;">
		<div class="option_name <?php if(!$expanded_categories){echo "hided";}?>"><?php echo $text_categories; ?></div>
		<div class="collapsible" <?php if(!$expanded_categories){echo 'style="display:none"';}?>>
			<div id="filter_categories">
				<?php foreach($categories as $category) { ?>
						<input id="cat_<?php echo $category['category_id']; ?>" class="filtered"
							type="checkbox" name="categories[]"
							value="<?php echo $category['category_id']; ?>">

						<div class="categories"><label for="cat_<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label></div>
				<?php } ?>
			</div>
		</div>
	</div>
		<?php } ?>

		<?php if($tags) { ?>
	<div class="option_box">
		<div class="option_name <?php if(!$expanded_tags){echo "hided";}?>"><?php echo $text_tags; ?></div>
		<div class="collapsible" <?php if(!$expanded_tags){echo 'style="display:none"';}?>>
			<table id="filter_tags">
				<?php foreach($tags as $tag) { ?>
				<tr>
					<td>
						<input id="tag_<?php echo $tag['tag']; ?>" class="filtered"
							type="checkbox" name="tags[]"
							value="<?php echo $tag['tag']; ?>">
					<!--</td>
					<td>-->
						<label for="tag_<?php echo $tag['tag']; ?>"><?php echo $tag['name']; ?></label>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
		<?php } ?>


		<?php if($manufacturers) { ?>
	<div class="option_box" style="max-width: 210px;min-width: 130px;">
		<div class="option_name <?php if(!$expanded_manufacturer){echo "hided";}?>"><?php echo $text_manufacturers; ?></div>
		<div class="collapsible" <?php if(!$expanded_manufacturer){echo 'style="display:none"';}?>>
		<input type="button" class="filter_apply" value="Применить">
			<?php if($display_manufacturer == 'select') { ?>
			<div>
				<select name="manufacturer[]" class="filtered">
					<option value=""><?php echo $text_all?></option>
					<?php foreach($manufacturers as $manufacturer) { ?>
					<option id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="manufacturer_value"
							value="<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></option>
					<?php } ?>
				</select>
			</div>
			<?php } elseif($display_manufacturer == 'checkbox') { ?>
			<div>
				<?php foreach($manufacturers as $manufacturer) { ?>

						<input id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="list-options manufacturer_value filtered"
							type="checkbox" name="manufacturer[]"
							value="<?php echo $manufacturer['manufacturer_id']?>">

						<label class="manufacturers" for="manufacturer_<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></label>

				<?php } ?>
			</div>
			<?php } elseif($display_manufacturer == 'radio') { ?>
			<table>
				<?php foreach($manufacturers as $manufacturer) { ?>
				<tr>
					<td>
						<input id="manufacturer_<?php echo $manufacturer['manufacturer_id']?>" class="manufacturer_value filtered"
							type="radio" name="manufacturer[]"
							value="<?php echo $manufacturer['manufacturer_id']?>">
					<!--</td>
					<td>-->
						<label for="manufacturer_<?php echo $manufacturer['manufacturer_id']?>"><?php echo $manufacturer['name']?></label>
					</td>
				</tr>
				<?php } ?>
			</table>
			<?php }?>
		</div>
	</div>
		<?php } ?>

		<?php if($attributes) { ?>
		<?php foreach($attributes as $attribute_group_id => $attribute) { ?>
		<?php if($attr_group) { ?>
		<div class="option_box" style="min-width: 80px;">
			<div class="attribute_group_name"><?php echo $attribute['name']; ?></div>
		<?php } ?>

			<?php foreach($attribute['attribute_values'] as $attribute_value_id => $attribute_value) { ?>
			<div class="attribute_box <?php if($attr_group=="0") echo "option_box"; ?>" <?php if ($attribute_value['name']=='Распродажа') echo 'style="display:none"'; ?>>

				<div class="option_name <?php if(!$attribute_value['expanded']){echo "hided";}?>"><?php echo $attribute_value['name']=='Вид спорта' ? 'Категория' : $attribute_value['name']; ?></div>
				<div class="collapsible" <?php if(!$attribute_value['expanded']){echo 'style="display:none"';}?>>
					<?php if($attribute_value['display'] == 'select') { ?>
					<div>
						<select class="filtered" name="attribute_value[<?php echo $attribute_value_id?>][]">
							<option value=""><?php echo $text_all?></option>
							<?php foreach($attribute_value['values'] as $i => $value) { ?>
							<option class="a_name"
									at_v_i="<?php echo $attribute_value_id . '_' . $value ?>"
									at_v_t="<?php echo $attribute_value_id . '_' . htmlspecialchars(preg_replace('/\s+|\n|\r|\s+$/m', '_', $value)) ?>"
									data-value="<?php echo $value ?>"
									value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php }?>
						</select>
					</div>
					<?php } elseif($attribute_value['display'] == 'checkbox') { ?>
					<input type="button" class="filter_apply" value="Применить">
					<div>
						<?php foreach($attribute_value['values'] as $i => $value) { ?>
							<input class="filtered a_name list-attributes"
									id="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									type="checkbox" name="attribute_value[<?php echo $attribute_value_id?>][]"
									at_v_i="<?php echo $attribute_value_id . '_' . $value; ?>"
									value="<?php echo $value ?>">
								<label class="attributes" for="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									at_v_t="<?php echo $attribute_value_id . '_' . htmlspecialchars(preg_replace('/\s+|\n|\r|\s+$/m', '_', $value)); ?>"
									data-value="<?php echo $value; ?>"
									value="<?php echo $value ?>"><?php echo $value?></label>
							<?php } ?>
					</div>
					<?php } elseif($attribute_value['display'] == 'radio') { ?>
					<table>
						<?php foreach($attribute_value['values'] as $i => $value) { ?>
						<tr>
							<td>
								<input class="filtered a_name"
									id="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									type="radio" name="attribute_value[<?php echo $attribute_value_id?>][]"
									at_v_i="<?php echo $attribute_value_id . '_' . $value ?>"
									value="<?php echo $value ?>">
							</td>
							<td>
								<label for="attribute_value_<?php echo $attribute_value_id . $i; ?>"
									at_v_t="<?php echo $attribute_value_id . '_' . htmlspecialchars(preg_replace('/\s+|\n|\r|\s+$/m', '_', $value)) ?>"
									data-value="<?php echo $value ?>"
									value="<?php echo $value ?>"><?php echo $value?></label>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php } elseif($attribute_value['display'] == 'slider') { ?>
					<table style="width:100%">
						<tr>
							<td style="width: 50%"><span id="attribute_label_<?php echo $attribute_value_id; ?>_min">0</span>
								<input type="hidden" id="attribute_value_<?php echo $attribute_value_id; ?>_min" name="attr_slider[<?php echo $attribute_value_id?>][min]" value="">
							</td>
							<td><span id="attribute_label_<?php echo $attribute_value_id; ?>_max">&infin;</span>
								<input type="hidden" id="attribute_value_<?php echo $attribute_value_id; ?>_max" name="attr_slider[<?php echo $attribute_value_id?>][max]" value="">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div id="slider-range-<?php echo $attribute_value_id; ?>" style="margin-left: 5px;margin-right: 5px;"></div>
							</td>
						</tr>
					</table>
					<script>
						var attr_arr_<?php echo $attribute_value_id; ?> = ['', <?php echo implode(',', $attribute_value['values']); ?>, ''];
						$('#slider-range-<?php echo $attribute_value_id; ?>').slider({
							range:true,
							min:0,
							max:<?php echo count($attribute_value['values'])+1 ; ?>,
							values:[0, <?php echo count($attribute_value['values'])+1 ; ?>],
							slide:function (a, b) {
								var min = attr_arr_<?php echo $attribute_value_id; ?>[b.values[0]];
								var max = attr_arr_<?php echo $attribute_value_id; ?>[b.values[1]];
								$("#attribute_label_<?php echo $attribute_value_id; ?>_min").html(min ? (min + '<?php echo $attribute_value['suffix']; ?>') : '0');
								$("#attribute_label_<?php echo $attribute_value_id; ?>_max").html(max?(max + '<?php echo $attribute_value['suffix']; ?>'):'&infin;');
							},
							stop:function (a, b) {
								var min = attr_arr_<?php echo $attribute_value_id; ?>[b.values[0]];
								var max = attr_arr_<?php echo $attribute_value_id; ?>[b.values[1]];
								$("#attribute_value_<?php echo $attribute_value_id; ?>_min").val(min);
								$("#attribute_value_<?php echo $attribute_value_id; ?>_max").val(max);
								iF()
							}
						});
					</script>
					</table>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php if($attr_group) { ?>
		</div>
			<?php } ?>
			<?php } ?>
		<?php } ?>

		<?php if($options) { ?>
		<?php foreach($options as $option) { ?>
		<div class="option_box" style="min-width:130px;">
			<div class="option_name <?php if(!$option['expanded']){echo "hided";}?>"><?php echo $option['name']; ?></div>
			<?php if($option['display'] == 'select') { ?>
			<div class="collapsible" <?php if(!$option['expanded']){echo 'style="display:none"';}?>>
		<input type="button" class="filter_apply" value="Применить">
				<select class="filtered" name="option_value[<?php echo $option['option_id']?>][]">
					<option value=""><?php echo $text_all?></option>
					<?php foreach($option['option_values'] as $option_value) { ?>
					<option class="option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
							value="<?php echo $option_value['option_value_id'] ?>"><?php echo $option_value['name']?></option>
					<?php }?>
				</select>
			</div>
			<?php } elseif($option['display'] == 'checkbox') { ?>
			<div class="collapsible" <?php if(!$option['expanded']){echo 'style="display:none"';}?>>
			<input type="button" class="filter_apply" value="Применить">
				<?php foreach($option['option_values'] as $option_value) { ?>
					<input class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
							type="checkbox" name="option_value[<?php echo $option['option_id']?>][]"
							value="<?php echo $option_value['option_value_id']?>">
					<!--</td>
					<td>-->

						<label <?php if($option['name']=='Размер обуви' || $option['name']=='Размер одежды') {echo 'class="size"';}?> for="option_value_<?php echo $option_value['option_value_id']?>"><?php echo $option_value['name']?></label>


				<?php } ?>

			</div>
			<?php } elseif($option['display'] == 'radio') { ?>
			<table class="collapsible" <?php if(!$option['expanded']){echo 'style="display:none"';}?>>
				<?php foreach($option['option_values'] as $option_value) { ?>
				<tr>
					<td>
						<input class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
							type="radio" name="option_value[<?php echo $option['option_id']?>][]"
							value="<?php echo $option_value['option_value_id']?>">
					<!--</td>
					<td>-->
						<label for="option_value_<?php echo $option_value['option_value_id']?>"><?php echo $option_value['name']?></label>
					</td>
				</tr>
				<?php } ?>
			</table>
			<?php } elseif($option['display'] == 'image') { ?>
			<div class="collapsible" <?php if(!$option['expanded']) { echo 'style="display:none"'; }?>>
				<?php foreach($option['option_values'] as $option_value) { ?>

						<input style="display: none;" class="filtered option_value" id="option_value_<?php echo $option_value['option_value_id']?>"
							type="checkbox" name="option_value[<?php echo $option['option_id']?>][]"
							value="<?php echo $option_value['option_value_id']?>">
						<img src="<?php echo $option_value['thumb'];?>"/>

				<?php } ?>
			</div>
			<?php }?>
		</div>
			<?php } ?>
		<?php } ?>
		<?php if ($category_id != 1163) { ?>
			<div class="option_box filterpro-discount">
				<input type="checkbox" class="filterpro-discount" name="filter" id="sale-products" value="1163">
				<label for="sale-products">Акционные товары</label>
			</div>
		<?php } ?>
		<!--
		<div class="option_box">
			<div class="option_name hided"><a style="text-decoration:none; font-size: 14px; color:#f00;" href="/promo/discount-20-percents/">Скидка 20%</div>
		</div>
		
		<div class="option_box">
			<div class="option_name hided"><a style="text-decoration:none; font-size: 14px; color:#f00;" href="/promo/discount-30-percents/">Скидка 30%</div>
		</div>
		
		<div class="option_box">
			<div class="option_name hided"><a style="text-decoration:none; font-size: 14px; color:#f00;" href="/promo/discount-40-percents/">Скидка 40%</div>
		</div>
		
		<div class="option_box">
			<div class="option_name hided"><a style="text-decoration:none; font-size: 14px; color:#f00;" href="/promo/discount-50-percents/">Скидка 50%</div>
		</div>
		-->
		
		<div style="display:inline-block;margin-top:6px;margin-right:10px; float:right;"><a class="clear_filter" ><?php echo $clear_filter?></a></div>
	</div>
	<div class="filterpro-chosen">
		<div class="filterpro__title">
			Выбрано:
		</div>
		<div class="filterpro-chosen__wrapper"></div>
	</div>
</form>
</div>

</div>
<?php } ?>
<script type="text/javascript">
  $(document).ready(function() {
    (function() {
        var checker = $('.option_box').find('input[type="checkbox"]:checked');
        if(checker.length == 0){
          checker.parents('.option_box').removeClass('active');
        }else{
          checker.parents('.option_box').addClass('active');
        }
    })();
  });
</script>
