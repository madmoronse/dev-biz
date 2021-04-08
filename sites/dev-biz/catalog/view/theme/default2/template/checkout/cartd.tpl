<?php $customer_group_id = $this->customer->getCustomerGroupId(); ?>
<?php echo $header; ?>
<?php if ($attention) { ?>
<div class="attention"><?php echo $attention; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<script type="text/javascript">
    function check_is_order_new() {
        var message_html = [
            '<div class="success success_add_to_cart">',
            '<div>',
            '<h2>Уважаемый клиент!</h2> ',
            '<p style="text-align: center;">Вы уже оформили заказ, который ожидает обработки нашими операторами</p> ',
            '<p style="text-align: center;"><b>Обработка заказов происходит с 6:00 до 19:00 (мск)</b></p> ',
            '<p style="text-align: center;">Наши операторы ОБЯЗАТЕЛЬНО свяжутся с вами в рабочее время для уточнения деталей заказа</p> ',
            '<p style="text-align: center;">Пожалуйста, не создавайте одинаковые повторные заказы, это замедляет их обработку.</p>',
            '</div>',
            '<br>',
            '<span id="button-left_notif"><a class="button" href="<?php echo $checkout; ?>">Продолжить</a></span>',
            '<span id="button-right_notif"><a class="button" href=\"/index.php?route=account/order/info&order_id=<?=$this->session->data["order_new_id"]?>\">Перейти к заказу</a></span>',
            '</div>'
        ].join('');

        $('#notification').before('<div class="notif-overlay"></div>').html(message_html);
        $('.success').fadeIn('slow');
    }
</script>
<div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
    </h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart-form">
        <div class="cart-info">
            <table>
                <thead>
                <tr>
                    <td class="image"><?php echo $column_image; ?></td>
                    <td class="model"><?php echo $column_sku; ?></td>
                    <td class="name"><?php echo $column_name; ?></td>
                    <td class="price"><?php echo $column_drop_price; ?></td>
                    <td class="quantity"><?php echo $column_quantity; ?></td>
                    <td class="total"><?php echo $column_total; ?></td>
                    <td class="total"><?php echo $column_drop_sell; ?></td>
                    <td class="total"><?php echo $column_drop_profit; ?></td>
                    <td class="total"><?php echo $column_drop_charge; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php $product_num = 0;
			    foreach ($products as $product) {
                    $price_total += $product['total'];
                    $product_num++;?>
                    <tr <?php if (!$product['stock']) { echo "class='OutOfStockTr'"; }?> id="product_<?php echo str_replace(':','',$product['key']); ?>">
                    <td class="image"><?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                        <?php } ?></td>
                    <td class="model"><?php echo $product['product_id']; ?></td>
                    <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        <?php if (!$product['stock']) { ?>
                        <span class="stock">***</span>
                        <?php } ?>
                        <div>
                            <?php foreach ($product['option'] as $option) { ?>
                            - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                            <?php } ?>
                        </div>
                        <?php if ($product['reward']) { ?>
                        <small><?php //echo $product['reward']; ?></small>
                        <?php } ?></td>
                    <td class="price" nowrap id="price_<?= $product_num?>"><?php echo $product['price']; ?></td>
                    <td class="quantity" nowrap><input id="quantity_<?= $product_num?>" type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
                        &nbsp;
                        <input type="image" src="catalog/view/theme/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />
                        &nbsp;<a onclick="remove_from_cart('<?php echo $product['key']; ?>')"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
                    <td class="total" id="total_<?= $product_num?>" nowrap><?php echo $product['total']; ?></td>
                    <td class="price" nowrap><input class="price_drop_row price_drop_<?= $product_num?>" name="price_drop[<?= $product['key']; ?>]" value="<?= $price_drop[$product['key']]; ?>"></input> руб.</td>
                    <td class="margin_drop" nowrap id="margin_drop_<?php echo $product_num; ?>">0</td>
                    <td class="total_margin_drop" id="total_margin_drop_<?php echo $product_num; ?>">0</td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($vouchers as $vouchers) { ?>
                    <tr>
                        <td class="image"></td>
                        <td class="name"><?php echo $vouchers['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity"><input type="text" name="" value="1" size="1" disabled="disabled" />
                            &nbsp;<a href="<?php echo $vouchers['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
                        <td class="price"><?php echo $vouchers['amount']; ?></td>
                        <td class="total"><?php echo $vouchers['amount']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <input name="markupdropshipping" id="md" type="hidden">
    </form>

    <script>
        function remove_from_cart(product) {
            $.ajax({
                url: '/index.php?route=checkout/cartd',
                type: 'get',
                data: 'remove='+product,
                dataType: 'json',
                success: function (result) {
                    if (result.success){
                        product = product.replace(':','');
                        $('#product_'+product).remove();
                        $('#cart-form').submit();
                    }
                }
            });
        }

        $('.price_drop_row').bind("change keyup input click", function() {
            // $(this).removeAttr('style');
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');
            }
        });

        $('.quantity input').bind("change keyup input click", function() {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');
            }


            let cartItem = $(this).attr("id").replace("quantity_", "");
            let price = parseInt($('#price_'+cartItem).text().replace(' ',''));
            let newTotal = $(this).val() * price;
            $("#total_"+cartItem).text(newTotal.toLocaleString() + " руб.");
            calculate_margin();
        });

        function calculate_margin() {
            var counts = $('.price_drop_row').length;
            let totalvalue = 0;
            for (i = 1; i <= counts; i++) {
                let price_drop = $('.price_drop_'+i).val();
                let quantity = $('#quantity_'+i).val()
                let price = parseInt($('#price_'+i).text().replace(' ',''));

                totalvalue = Number (totalvalue) +  Number (price_drop) * Number (quantity) - Number (price) * Number (quantity);
                $('#total_drop_'+i).text(Number (price_drop) * Number (quantity));
                $('#margin_drop_'+i).text(Number (price_drop) - Number (price));
                $('#total_margin_drop_'+i).text((Number (price_drop) - Number (price)) * Number (quantity));

            }

            $('#total_margin_total').text(totalvalue);
            $('#total_drop_total').text(totalvalue+<?php if(isset($price_total)) echo $price_total;?>);
            var order_total = parseFloat($('#shipping_total').text()) + totalvalue+<?php if(isset($price_total)) echo $price_total;?>;
            $('#order_total').text(order_total);

            if (totalvalue >= 0) {
                    $('#markupdropshipping').text(totalvalue.toLocaleString() + ' руб.');
                    $('#md').val(totalvalue);
                    let total = parseInt($('#total_sum').text().replace(' ',''));
                    total = total + totalvalue;
                    $('#total_price').text(total + ' руб.');
            } else {
                $('#md').val('Слишком маленькая наценка');
                $('#markupdropshipping').text('не указана');
            }
        }

        $('.price_drop_row').keyup(calculate_margin);

        function formSubmitAjax(){
            calculate_margin();
            if ($('#md').val() == 'Слишком маленькая наценка') {
                alert('Вы ввели слишком маленькую наценку!');
            } else {
                var form = $('#cart-form');
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        document.location.href = '<?php echo $checkout; ?>';
                    }
                });
            }
        }

        $( document ).ready(function() {
            calculate_margin();
        });

    </script>


    <?php if ($coupon_status || $voucher_status || $reward_status || $shipping_status) { ?>
    <h2 class="small-head" style="display:none;"><?php echo $text_next; ?></h2>
    <div class="content" style="display:none;">
        <p><?php echo $text_next_choice; ?></p>
        <table class="radio">
            <?php if ($coupon_status) { ?>
            <tr class="highlight">
                <td><?php if ($next == 'coupon') { ?>
                    <input type="radio" name="next" value="coupon" id="use_coupon" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="next" value="coupon" id="use_coupon" />
                    <?php } ?></td>
                <td><label for="use_coupon"><?php echo $text_use_coupon; ?></label></td>
            </tr>
            <?php } ?>
            <?php if ($voucher_status) { ?>
            <tr class="highlight">
                <td><?php if ($next == 'voucher') { ?>
                    <input type="radio" name="next" value="voucher" id="use_voucher" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="next" value="voucher" id="use_voucher" />
                    <?php } ?></td>
                <td><label for="use_voucher"><?php echo $text_use_voucher; ?></label></td>
            </tr>
            <?php } ?>
            <?php if ($reward_status) { ?>
            <tr class="highlight">
                <td><?php if ($next == 'reward') { ?>
                    <input type="radio" name="next" value="reward" id="use_reward" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="next" value="reward" id="use_reward" />
                    <?php } ?></td>
                <td><label for="use_reward"><?php echo $text_use_reward; ?></label></td>
            </tr>
            <?php } ?>
            <?php if ($shipping_status) { ?>
            <tr class="highlight">
                <td><?php if ($next == 'shipping') { ?>
                    <input type="radio" name="next" value="shipping" id="shipping_estimate" checked="checked" />
                    <?php } else { ?>
                    <input type="radio" name="next" value="shipping" id="shipping_estimate" />
                    <?php } ?></td>
                <td><label for="shipping_estimate"><?php echo $text_shipping_estimate; ?></label></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div class="cart-module">
        <div id="coupon" class="content" style="display: <?php echo ($next == 'coupon' ? 'block' : 'none'); ?>;">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <?php echo $entry_coupon; ?>&nbsp;
                <input type="text" name="coupon" value="<?php echo $coupon; ?>" />
                <input type="hidden" name="next" value="coupon" />
                &nbsp;
                <input type="submit" value="<?php echo $button_coupon; ?>" class="button" />
            </form>
        </div>
        <div id="voucher" class="content" style="display: <?php echo ($next == 'voucher' ? 'block' : 'none'); ?>;">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <?php echo $entry_voucher; ?>&nbsp;
                <input type="text" name="voucher" value="<?php echo $voucher; ?>" />
                <input type="hidden" name="next" value="voucher" />
                &nbsp;
                <input type="submit" value="<?php echo $button_voucher; ?>" class="button" />
            </form>
        </div>
        <div id="reward" class="content" style="display: <?php echo ($next == 'reward' ? 'block' : 'none'); ?>;">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <?php echo $entry_reward; ?>&nbsp;
                <input type="text" name="reward" value="<?php echo $reward; ?>" />
                <input type="hidden" name="next" value="reward" />
                &nbsp;
                <input type="submit" value="<?php echo $button_reward; ?>" class="button" />
            </form>
        </div>
        <div id="shipping" class="content" style="display: <?php echo ($next == 'shipping' ? 'block' : 'none'); ?>;">
            <p><?php echo $text_shipping_detail; ?></p>
            <table>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_country; ?></td>
                    <td><select name="country_id">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $country_id) { ?>
                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
                    <td><select name="zone_id">
                        </select></td>
                </tr>
                <tr>
                    <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
                    <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
                </tr>
            </table>
            <input type="button" value="<?php echo $button_quote; ?>" id="button-quote" class="button" />
        </div>
    </div>
    <?php } ?>
    <div class="cart-total">
        <table id="total">
            <?php foreach ($totals as $total) { ?>
            <tr>
                <td class="right"><b><?php echo $total['title']; ?>:</b></td>
                <td class="right"
                    <?php if ($total['title'] == 'Наценка дропшиппера на заказ' ) {echo 'id="markupdropshipping"';}?>
                    <?php if ($total['title'] == 'Итого' ) {echo 'id="total_price"';}?>
                    <?php if ($total['title'] == 'Сумма' ) {echo 'id="total_sum"';}?>
                ><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div class="buttons">
        <? //if($_SERVER["REMOTE_ADDR"]=='188.0.30.39'): ?>
        <? if($this->session->data['is_order_new']==1 && $customer_group_id < 2): ?>
        <div class="right"><a href="#" class="button"><?php echo $button_checkout; ?></a></div>

        <? else: ?>
        <div class="right">
            <a <?php if ($customerIsNotLoggedIn) {
                    echo 'onclick="event.preventDefault(); $(\'#login-modal\').click();"';
                } else {
                echo 'onclick="event.preventDefault(); formSubmitAjax()"';
            } ?> class="button">
                <?php echo $button_checkout; ?>
            </a>
        </div>
        <? endif; ?>

        <? /*else: ?>
        <div class="right"><a href="<?php echo $checkout; ?>" class="button"><?php echo $button_checkout; ?></a></div>
        <? endif;*/ ?>
        <div class="left"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_shopping; ?></a></div>
    </div>
    <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
    $('input[name=\'next\']').bind('change', function() {
        $('.cart-module > div').hide();

        $('#' + this.value).show();
    });
    //--></script>
<?php if ($shipping_status) { ?>
<script type="text/javascript"><!--
    $('#button-quote').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/cartd/quote',
            type: 'post',
            data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#button-quote').attr('disabled', true);
                $('#button-quote').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-quote').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                $('.success, .warning, .attention, .error').remove();

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');

                        $('html, body').animate({ scrollTop: 0 }, 'slow');
                    }

                    if (json['error']['country']) {
                        $('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }
                }

                if (json['shipping_method']) {
                    html  = '<h2><?php echo $text_shipping_method; ?></h2>';
                    html += '<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">';
                    html += '  <table class="radio">';

                    for (i in json['shipping_method']) {
                        html += '<tr>';
                        html += '  <td colspan="3"><b>' + json['shipping_method'][i]['title'] + '</b></td>';
                        html += '</tr>';

                        if (!json['shipping_method'][i]['error']) {
                            for (j in json['shipping_method'][i]['quote']) {
                                html += '<tr class="highlight">';

                                if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
                                    html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" /></td>';
                                } else {
                                    html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" /></td>';
                                }

                                html += '  <td><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</label></td>';
                                html += '  <td style="text-align: right;"><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['text'] + '</label></td>';
                                html += '</tr>';
                            }
                        } else {
                            html += '<tr>';
                            html += '  <td colspan="3"><div class="error">' + json['shipping_method'][i]['error'] + '</div></td>';
                            html += '</tr>';
                        }
                    }

                    html += '  </table>';
                    html += '  <br />';
                    html += '  <input type="hidden" name="next" value="shipping" />';

                <?php if ($shipping_method) { ?>
                        html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" />';
                    <?php } else { ?>
                        html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" disabled="disabled" />';
                    <?php } ?>

                    html += '</form>';

                    $.colorbox({
                        overlayClose: true,
                        opacity: 0.5,
                        width: '600px',
                        height: '400px',
                        href: false,
                        html: html
                    });

                    $('input[name=\'shipping_method\']').bind('change', function() {
                        $('#button-shipping').attr('disabled', false);
                    });
                }
            }
        });
    });
    //--></script>
<script type="text/javascript"><!--
    $('select[name=\'country_id\']').bind('change', function() {
        $.ajax({
            url: 'index.php?route=checkout/cartd/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#postcode-required').show();
                } else {
                    $('#postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'country_id\']').trigger('change');
    //--></script>
<?php } ?>
<?php echo $footer; ?>
