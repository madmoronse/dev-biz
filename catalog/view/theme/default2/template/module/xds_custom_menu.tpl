<div id="xds-menu">
	<ul class="links">
		<?php if (isset($item1[$lang_id]['name']) & $item1[$lang_id]['name'] !='') {?>
		<li><a href="<?php echo $item1_href; ?>"><?php echo $item1[$lang_id]['name']; ?></a></li>
		<?php } ?>
		<?php if (isset($item2[$lang_id]['name']) & $item2[$lang_id]['name'] !='') {?>
		<li><a href="<?php echo $item2_href; ?>"><?php echo $item2[$lang_id]['name']; ?></a></li>
		<?php } ?>
		<?php if (isset($item3[$lang_id]['name']) & $item3[$lang_id]['name'] !='') {?>
		<li><a href="<?php echo $item3_href; ?>"><?php echo $item3[$lang_id]['name']; ?></a></li>
		<?php } ?>
		<?php if (isset($item4[$lang_id]['name']) & $item4[$lang_id]['name'] !='') {?>
		<li><a href="<?php echo $item4_href; ?>"><?php echo $item4[$lang_id]['name']; ?></a></li>
		<?php } ?>
		<?php if (isset($item5[$lang_id]['name']) & $item5[$lang_id]['name'] !='') {?>
		<li><a href="<?php echo $item5_href; ?>"><?php echo $item5[$lang_id]['name']; ?></a></li>
		<?php } ?>
	</ul>
	<ul class="social">
		<?php if (isset($odnoklassniki_href) & $odnoklassniki_href !='') {?>
		<li><a title="<?php echo $odnoklassniki_title; ?>" target="_blank" href="<?php echo $odnoklassniki_href; ?>" class="ok"></a></li>
		<?php } ?>
		<?php if (isset($vkontakte_href) & $vkontakte_href !='') {?>
		<li><a title="<?php echo $vkontakte_title; ?>" target="_blank" href="<?php echo $vkontakte_href; ?>" class="vk"></a></li>
		<?php } ?>
		<?php if (isset($facebook_href) & $facebook_href !='') {?>
		<li><a title="<?php echo $facebook_title; ?>" target="_blank" href="<?php echo $facebook_href; ?>" class="fb"></a></li>
		<?php } ?>
		<?php if (isset($twitter_href) & $twitter_href !='') {?>
		<li><a title="<?php echo $twitter_title; ?>" target="_blank" href="<?php echo $twitter_href; ?>" class="tw"></a></li>
		<?php } ?>
		<?php if (isset($googleplus_href) & $googleplus_href !='') {?>
		<li><a title="<?php echo $googleplus_title; ?>" target="_blank" href="<?php echo $googleplus_href; ?>" class="gp"></a></li>
		<?php } ?>
	</ul>
	<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#xds-menu + .slideshow').prev().addClass('with-slider');
	});
	--></script>
</div>