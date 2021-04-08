<div class="box">
  <div class="box-heading bestseller-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product owl-carousel owl-theme" id="bestseller-carousel">
      <?php foreach ($products as $product) { ?>
      <div class="item">
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="rating"><img src="catalog/view/theme/default2/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" title="<?php echo $product['reviews']; ?>" /></div>
        <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
				<div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"></a></div>
				<div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"></a></div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<script>
	$(document).ready(function() {
		$("#bestseller-carousel").owlCarousel({
			responsiveBaseWidth: "#bestseller-carousel",
			itemsCustom: [[190, 1], [890, 4]]
		});
	});
</script>