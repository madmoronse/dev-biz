<div class="box">
  <div class="box-heading info-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-info">
      <?php foreach ($informations as $information) {
	  $customer_group_id = $this->customer->getCustomerGroupId();
	  if ($customer_group_id > 2) {?>
	  <?php if ($information['title'] != "Политика Безопасности" && $information['title'] != "Условия соглашения" && $information['title'] != "Как с нами связаться" && $information['title'] != "Слив" && $information['title'] != "Слив 2" && $information['title'] != "Акция 1+1=3") { ?>
      <li ><a href="<?php echo $information['href']; ?>" ajaxurl="<?php if($information['ajaxurl']){ echo $information['ajaxurl'];}; ?>" class="<?php if($information['modal']){ echo 'modalLink information';}; ?>"><?php echo $information['title']; ?></a>
		<?php if ($information['title'] == "Оплата и доставка") {echo "<p style='text-align:left;word-spacing:4px;white-space: normal;padding: 0 10px; color:#999;'>Доставка осуществляется почтой России наложенным платежом. </br>Сроки доставки составляют в среднем 5-15 дней, в зависимости от удаленности Вашего региона.</p>";} ?>
      <?php } ?></li>
	  <?php } else { ?>
	  <?php if ($information['title'] != "Политика Безопасности" && $information['title'] != "Условия соглашения" && $information['title'] != "Как с нами связаться" && $information['title'] != "Слив" && $information['title'] != "Слив 2" && $information['title'] != "Акция 1+1=3") { ?>
      <li ><a href="<?php echo $information['href']; ?>" ajaxurl="<?php if($information['ajaxurl']){ echo $information['ajaxurl'];}; ?>" class="<?php if($information['modal']){ echo 'modalLink information';}; ?>" <?php if ($information['title'] == "Слив-3") {echo "style='color:#E00!important'";}?>><?php echo $information['title']; ?></a>
		<?php if ($information['title'] == "Оплата и доставка") {echo "<p style='text-align:left;word-spacing:4px;white-space: normal;padding: 0 10px; color:#999;'>Доставка осуществляется почтой России наложенным платежом. </br>Сроки доставки составляют в среднем 5-15 дней, в зависимости от удаленности Вашего региона.</p>";} ?>
      <?php } ?></li>
	  <?php } ?>
	  <?php } ?>
      <li><a href="/index.php?route=information/information&information_id=8"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
</div>
