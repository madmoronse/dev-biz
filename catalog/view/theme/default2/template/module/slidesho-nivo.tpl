<div class="slideshow" style="background-image: url('../image/data/baners/ban1.jpg');border-radius: 5px;">
  <div id="slideshow<?php echo $module; ?>" class="nivoSlider" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; max-width:100%!important">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#slideshow<?php echo $module; ?>').nivoSlider({pauseTime:4000, effect: 'fade',animation: 'slow'});
});
--></script>
<div style="margin-bottom:20px;"><a href="https://t.me/outmax" target="_blank"><img src="/image/telegramm_chanel.png"></a></div>