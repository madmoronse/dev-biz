<footer>
    <div style="width:100%;max-width:1200px;margin:0 auto;">
      <div class="column">
        <ul>
          <li><a href="/index.php?route=calc/calc">Расчет стоимости доставки</a></li>
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <div class="column">
        <ul>
          <li><a href="/index.php?route=information/information&amp;information_id=8"><?php echo $text_contact; ?></a></li>
          <li> <a href="/index.php?route=information/information&amp;information_id=23">Реквизиты компании</a>
          <li><a href="/index.php?route=product/testimonial"><?php echo $text_testimonial;  ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
		  <li><a href="/index.php?route=information/information&information_id=17"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="column">
        <ul>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        </ul>
      </div>
      <div class="column sub">
        <ul class="social">
          <form action="/index.php" method="GET" class="subscribe">
            <b>Подпишитесь на новости</b>
            <input type="hidden" name="route" value="account/subscribe" />
            <input type="text" placeholder="Введите свой email" autocomplete="off" name="email" id="subscribe-email" value="" class="input-text input--mail input--required">
            <button id="subscribe-button">Подписаться</button>
          </form>
          <li class="socialli" style="margin-left:48px;"><a title="Вконтакте" target="_blank" href="https://vk.com/outmaxshopru" class="vk"><span>Вконтакте</span></a></li>
          <li class="socialli"><a title="Instagram" target="_blank" href="https://www.instagram.com/outmaxshop_/" class="ig"><span>Instagram</span></a></li>
        </ul>
      </div>
    </div>
    <div style="clear:both"></div>
    <div class="footer-bottom">
      <div class="powered"><?php echo $powered; ?></div>
    </div>
    <div class="pay-logos" style="text-align:center;"><img src="/image/pay_logos.jpg" style="height:32px;margin-top:10px;"></div>
</footer>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="/js/new-business.min.js?v1"></script>
</body>
</html>