<?php $customer_group_id = $this->customer->getCustomerGroupId();?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content"><?php echo $content_top; ?>
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <h1 style="height: 100px;">
            <?php echo "История заказов"?>
			
            <form enctype="multipart/form-data" method="post" style="float:right;margin-right:20px;">
                <span style="float:right;font-size:14px!important;font-weight:bold;">Фильтр по статусам заказов
				<select name="statusFilter">
                    <option value='%%' <?php if(!isset($statusFilter)){echo 'selected="selected"';}?>>Любой</option>
                    <?php foreach ($ordersStatuses as $ordersStatus){ ?>
                        <option <?php if(isset($statusFilter) and $statusFilter == $ordersStatus['order_status_id'] ){echo 'selected="selected"';}?> value='<?php echo $ordersStatus['order_status_id'];?>'><?php echo $ordersStatus['name'];?></option>
                    <?php } ?>
                </select></span><br />
                <span style="font-size:14px!important;font-weight:bold;">Поиск заказа по Ф.И.О или номеру</span> <input type="text" name="customer-f-i-o" placeholder="Введите Ф.И.О. получателя" <?php if (isset($NameFilter) and $NameFilter !=''){ echo 'value="' . $NameFilter . '"';} else echo 'value=""';?> >
                <input type="submit" value="Поиск">
            </form>

        </h1>
		
		 <?php if (isset($TotalNotPayedMarkup) and 1==0) echo "<div style='font-size:16px !important;font-weight:bold; background-color: #fff;padding: 10px 10px 10px 20px;'>К выплате: $TotalNotPayedMarkup руб.</div><br />";
		 ?>
		 
        <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>

                <div class="order-list wrapper">
                <h2 class="h2-head">
                    <span class="order-id"><?php echo $text_order_id; ?> <b><?php echo $order['order_id']; echo "</b></span>";?>
                    <?php if ($order['tracking_number'] && strlen($order['tracking_number']) == 10) { ?> 
                        <?php echo "<span class='track-number'>Трек-номер: <a onclick=tracking('".$order['tracking_number']."');><b>" .$order['tracking_number']. "</b></a></span>"; 
                    } else { echo "<span class='track-number'>Трек-номер: <b>" .$order['tracking_number']. "</b></span>"; } ?>


                    <?php if ($order['replacement_for']) { ?>
                        <?php echo ($order['tracking_number'] ? '<br />' : '') ?>
                        <b><?php echo $text_replacement_for ?></b> <?php echo $order['replacement_for'] ?>
                    <?php } ?>
                    
                    <span class="order-status"><?php echo $text_status; ?> <b><?php echo $order['status']; ?></b></span>
                    </h2>

                    <div class="order-content">
                        <div><b><?php echo $text_customer; ?></b> <?php echo $order['name']; ?><br />
                            <b><?php echo $text_total; ?></b> <?php echo $order['total']; ?><br />
                            <?php if (isset($order['orderMarkupDropshipping']) AND $order['status'] == 'Сделка Завершена. Выплачено') {echo '<b>Наценка дропшиппера: </b>'; ?> <?php echo $order['orderMarkupDropshipping']; }?></div>
                            <div><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
                            <b><?php echo $text_products; ?></b> <?php echo $order['products']; ?></div>
                        <div class="order-info"><a class="button" href="<?php echo $order['href']; ?>">Детали заказа</a>

                            <?php if ($customer_group_id > 1) { ?>

                                &nbsp;&nbsp;<a href="<?php echo $order[reorder]; ?>"><img src="catalog/view/theme/default/image/reorder.png" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" /></a>

                            <?php	} ?>

                        </div>
						<?php if (isset($order['PayoutDate']) AND $order['PayoutDate'] != '01.01.1970' AND $order['status'] == "Сделка Завершена. Выплачено") { echo '<div style="margin-top: 10px; text-align:right;">Дата выплаты: <b>' . $order['PayoutDate'] . '</b></div>';}?>
                    </div>
                </div>

            <?php } ?>
            <div class="pagination"><?php echo $pagination; ?></div>
        <?php } else { ?>
            <div class="content"><?php echo $text_empty; ?></div>
        <?php } ?>
        <div class="buttons">
            <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
        </div>
        <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>