<div class="box">
  <div class="box-heading latest-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product owl-carousel owl-theme" id="latest-carousel">
      <?php foreach ($products as $product) { ?>
      <div class="item" style="border:1px solid #fdfdfd;">
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
		
		<div class="manufacturer"><a href="<?php echo $product['manufacturer'];?>" ><?php echo $product['manufacturer']; ?></a></div>
		
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
       
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<script>
	$(document).ready(function() {			
			$("#latest-carousel").owlCarousel({
		autoPlay : true,
		rewindSpeed: 1,
		scrollPerPage: true,
		 stopOnHover : true,
			responsiveBaseWidth: "#latest-carousel",
			itemsCustom: [[190, 1],[670, 3], [890, 4]]
		});
	});
</script>