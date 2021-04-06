<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="checkout-body">
    <div class="checkout-title">Оформление заказа: Шаг 1 из <?php echo $max_step; ?></div>
    <div class="checkout-content">

    </div>
    <div class="checkout-control">
      <button class="button" id="prev-step" act="step-0">Назад</button>
      <span class="info">Нажмите, чтобы перейти на страницу ввода адреса доставки</span>
      <button class="button" id="next-step" act="step-2">Продолжить</button>
    </div>
    <div class="checkout-steps">
      <?php foreach ($steps as $key => $step) { ?>
      <div class="checkout-steps__item checkout-steps__item--<?php echo $max_step; ?> <?php echo ($key == 1) ? 'active' : ''; ?>">
        <span class="number"><?php echo $key; ?></span>
        <span class="text"><?php echo $step; ?></span>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php echo $content_bottom; ?>
</div>
<script>
(function() {
  var checkout = new Checkout(<?php print json_encode($frontend_options); ?>);
  checkout.init();
})();
</script>
<?php echo $footer; ?>
