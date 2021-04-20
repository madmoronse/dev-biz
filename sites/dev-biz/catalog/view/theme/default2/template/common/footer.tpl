
</div>

<?php if ($_SERVER[HTTP_HOST] != "opt.bizoutmax.ru" ) { ?>
	<div id="footer">
	<?php if ($informations) { ?>


	<div style="width:100%;max-width:1200px;margin:0 auto;">
		<div class="column">
			<ul>
			<?php foreach ($informations as $information) { ?>
			<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		<div class="column">
			<ul>
			<li> <a href="/index.php?route=information/information&information_id=8"><?php echo $text_contact; ?></a>
			<li><a href="/index.php?route=product/testimonial"><?php echo $text_testimonial;  ?></a></li>
			<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
			<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
			<!-- <li><a href="<?php /* echo $voucher; */ ?>"><?php /* echo $text_voucher; */ ?></a></li> -->
			<!-- <li><a href="<?php /* echo $affiliate; */ ?>"><?php /* echo $text_affiliate; */ ?></a></li> -->
			<!-- <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>-->
			<?php $customer_group_id = $this->customer->getCustomerGroupId();
			if ($customer_group_id <3) { ?>
			<li><a href="/index.php?route=information/information&information_id=17"><?php echo $text_special; ?></a></li>
			<?php }?>
		
			</ul>
		</div>
		<div class="column">
			<ul>
			<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
			<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
			<li><a class="partnership-button" href="http://bizoutmax.ru/sotrudnichestvo/">Сотрудничество</a></li>
			<!-- <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li> -->
			<!-- <li><a href="<?php /* echo $newsletter; */ ?>"><?php /* echo $text_newsletter; */ ?></a></li> -->
			</ul>
		</div>
		<div class="column sub">
			<ul class="social">
				<div class="subscribe">
					<b>Подпишитесь на новости</b>
					<input type="text" placeholder="Введите свой email" autocomplete="off" name="subscribe-email" id="subscribe-email" value="">
					<button id="subscribe-button">Подписаться</button>
				</div>
				<li class="socialli" style="margin-left:48px;"><a title="<?php echo "Вконтакте" ?>" target="_blank" href="<?php echo "https://vk.com/outmaxshopru" ?>" class="vk"><span>Вконтакте</span></a></li>
				<li class="socialli"><a title="<?php echo "Instagram" ?>" target="_blank" href="<?php echo "https://www.instagram.com/outmaxshop_/" ?>" class="ig"><span>Instagram</span></a></li>
				<div class="pay-logos" style="text-align: left;"><img src="https://outmaxshop.ru/templates/outmaxshop/img/payment.png" style="width: 215px;margin-top:10px;"></div>
			</ul>

		</div>
	</div>
		<div style="clear:both"></div>
	<div class="footer-bottom">

	<!-- <div style="float:right;margin-top:-7px;margin-bottom:-13px;">
	LiveInternet logo--><!--<a href="//www.liveinternet.ru/click"
	target="_blank"><img src="//counter.yadro.ru/logo?44.1"
	title="LiveInternet"
	alt="" border="0" width="31" height="31"/></a>/LiveInternet-->
	<!--</div> -->

	<div id="powered"><?php echo $powered; ?></div>
	<?php if ($import_data && count($import_data)) { ?>
		<div class="import-data">
			Последнее обновление каталога <?php echo $import_data['finished'] ?> (UTC)
			<br />
			Н: <?php echo $import_data['created'] ?>, О: <?php echo $import_data['updated'] ?>
		</div>
	<?php } ?>
	</div>



	<!-- <div id="in-socium"><div class="share42init" data-url="[url]" data-title="[title]"></div><script type="text/javascript" src="catalog/view/theme/default2/assets/share42/share42/share42.js"></script></div> -->

	</div>

<?php }?> 
<?php $customer_group_id = $this->customer->getCustomerGroupId(); ?>
		<?php  if ($customer_group_id ==2 ) {
		print'
<script type="text/javascript">
   function pingServer() {
      $.ajax({ url: location.href });
   }
   $(document).ready(function() {
      setInterval("pingServer()", 60000);
   });
</script>';
		}?>



<script type="text/javascript">
	$(document).ready(function(){
		$('.breadcrumb a:last-child').removeAttr('href');
	});
	$(document).ready(name_scroll);
</script>
<script type="text/javascript" src="js/neos_mobile.min.js?v1"></script>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/scroll-top.js"></script>
<!-- <script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/poshytip-start.js"></script> -->
<!--[if lt IE 9]><div id="ielt9"></div><![endif]-->


<div class="overlay">
  <div class="overlay-center">
    <img src="/catalog/view/theme/default2/image/ajax_loader2.gif" width="150">
    <p>Идет загрузка...</p>
  </div>
</div>
<div class="overlay-data">
  <div class="overlay-data-center">
    <div class="overlay-data-block">
      <p><b>Для продолжения - подтвердите свой населенный пункт</b></p>
      <div id="overlayData"></div>
    </div>
  </div>
</div>

<!-- <script src="//cdn.callibri.ru/callibri.js" type="text/javascript" charset="utf-8"></script> -->
</body>
</html>
