<?php $customer_group_id = $this->customer->getCustomerGroupId();?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1 style="overflow: auto;">
	<?php echo "Трек-номера"; ?>
      <form class="search-order" enctype="multipart/form-data" method="post" action="/index.php?route=account/tracking_number/filter/" style="font-size:14px!important;font-weight:bold;float: right;margin: 10px;">

                    Дата с <input type="date" name="date_start" value="<?php echo $filters['date_start'];?>">
                    по <input type="date" name="date_end" value="<?php echo $filters['date_end'];?>">
                    <input style="width: 243px;" type="text" name="order_id" placeholder="Введите номер заказа" value="<?php echo $filters['order_id'];?>">

          <input type="submit" value="Поиск">
      </form>
		

  </h1>
  <?php if ($orders) { ?>
      <div class="wrapper">
          <table class="list tracks_table">
              <thead>
              <tr>
                  <td class="left">Ф.И.О.</td>
                  <td class="left">№ заказа</td>
                  <td class="left">Статус</td>
                  <td class="left">Трек-номер</td>
                  <td class="left">Дата отправки</td>
                  <td class="right">сумма заказа</td>
              </tr>
              </thead>
              <tbody>
  <?php foreach ($orders as $order) { ?>
    <tr>
            <td class="left"><?php echo $order['name']; ?></td>
            <td class="left"><a href="<?php echo $order['href']; ?>" target="_blank"><?php echo $order['order_id'];?></a></td>
            <td class="left"><?php echo $order['status']; ?></td>
            <td class="left"><?php echo $order['tracking_number']; ?></td>
            <td class="left"><?php echo $order['track_date_added']; ?></td>
            <td class="right"><?php echo $order['total']; ?></td>
    </tr>


  <?php } ?>
              </tbody></table> </div>
  <div class="pagination"><?php echo $pagination; ?></div>

  <?php } else { ?>
  <div class="content"><?php echo $text_tracks_empty; ?></div>
  <?php } ?>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>