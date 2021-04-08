<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <h1><?php echo $heading_title; ?></h1>
    <div class="checkout checkoutd">
            <div id="payment-address">
                <div class="checkout-content"></div>
            </div>
            <div id="shipping-address">
                <div class="checkout-content"></div>
            </div>
            <div id="shipping-method">
                <div class="checkout-content"></div>
            </div>
        <div id="payment-method" style="display:none">
            <div class="checkout-content"></div>
        </div>
        <div id="confirm">
            <div class="checkout-content"></div>
        </div>
    </div>
    <?php echo $content_bottom; ?></div>

<script type="text/javascript"><!--

    $(document).ready(function() {
        $.ajax({
            url: 'index.php?route=checkoutf/payment_address',
            dataType: 'html',
            success: function(html) {
                $('#payment-address .checkout-content').html(html);

                /*$('#payment-address .checkout-content').slideDown('slow');*/
                $('#payment-address #button-payment-address').click();

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Payment Address
    $('#button-payment-address').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkoutf/payment_address/validate',
            type: 'post',
            data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-address').attr('disabled', true);
                $('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-payment-address').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                console.log(json);
                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }
                    if (json['error']['middlename']) {
                        $('#payment-address input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['company_id']) {
                        $('#payment-address input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
                    }

                    if (json['error']['tax_id']) {
                        $('#payment-address input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['address_2']) {
                        $('#payment-address input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
                    }

                    if (json['error']['address_3']) {
                        $('#payment-address input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                } else {
                    <?php if ($shipping_required) { ?>
                    $.ajax({
                        url: 'index.php?route=checkoutf/shipping_addressd',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-address .checkout-content').html(html);

                            $('#payment-address .checkout-content').slideUp('slow');

                            $('#shipping-address .checkout-content').slideDown('slow');

                            $('#payment-address .checkout-heading a').remove();
                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();

                            $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                    <?php } else { ?>
                    $.ajax({
                        url: 'index.php?route=checkoutf/payment_method',
                        dataType: 'html',
                        success: function(html) {
                            $('#payment-method .checkout-content').html(html);

                            $('#payment-address .checkout-content').slideUp('slow');

                            $('#payment-method .checkout-content').slideDown('slow');

                            $('#payment-address .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();

                            $('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                    <?php } ?>

                    $.ajax({
                        url: 'index.php?route=checkoutf/payment_address',
                        dataType: 'html',
                        success: function(html) {
                            $('#payment-address .checkout-content').html(html);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    // Shipping Address
    $('#shipping-address input[name=\'postcode\']').live('keyup change', function() {
        let postcode = $('#shipping-address input[name=\'postcode\']').val();
        if (postcode.length == 6){
            getAddressByPostcode();
        }
    });

    $('#shipping-address input, #shipping-address select').live('keyup change', function() {
        let postcode = $('#shipping-address input[name=\'postcode\']').val();
        if (postcode.length == 6){

            if (typeof(timerId) != "undefined") {
                clearTimeout(timerId);
            }            
            timerId = setTimeout(loadShippingMethods, 1000);

        } else {
            $('#shipping-method .checkout-content').empty();
        }
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    //Country live search BEGIN
    $('#shipping-address #country').live('keyup click', function() {
        $('.country_result').remove();
        $('#shipping-address select[name="country_id"] option:selected').prop('selected', false);
        // $('#shipping-address select[name="zone_id"] option:selected').prop('selected', false);
        // $('#shipping-address #zone').val('');
        let country = capitalizeFirstLetter($('#shipping-address #country').val());

        if (country.length < 3){
            return;
        }

        let options = $('#shipping-address select[name="country_id"] option');
        let result = new Array();
        $.map(options, function(e) {
            let value = $(e).val();
            let text = $(e).text();
            if (result.length < 6 && text.startsWith(country)) {
                result[text]=value;
            }
        })

        if (result){
            $('#shipping-address select[name="country_id"]').after('<div  class="autocomplete_result_block country_result"><div id="close_country_result" class="close_autocomplete_result_block" onclick="close_country_result()">X</div></div>');
            for (let key in result) {
                $('.autocomplete_result_block').append('<div class="autocomplete_result_block_item" onclick="setCountry(this)" id="val_' + result[key] + '">'+ key + '</div>');
            }
        } else {
            $('#shipping-address select[name="country_id"]').after('<div  class="autocomplete_result_block country_result"><div id="close_country_result" class="close_autocomplete_result_block" onclick="close_country_result()">X</div class="autocomplete_result_block_item">Страна не найдена</div>');
        }
    });

    function close_country_result() {
        $('.country_result').remove();
    }

    function setCountry(el) {
        let value = $(el).attr('id').replace('val_','');
        $('#shipping-address select[name="country_id"] option[value="'+value+'"]').prop('selected', true);
        $('#country').val($(el).text());
        $('.country_result').remove();
        $('#shipping-address select[name=\'country_id\']').trigger('change');
    }
    //Country live search END

    //Zone live search BEGIN
    $('#shipping-address #zone').live('keyup click', function() {
        $('.zone_result').remove();
        $('#shipping-address select[name="zone_id"] option:selected').prop('selected', false);
        let zone = capitalizeFirstLetter($('#shipping-address #zone').val());

        if (zone.length < 3){
            return;
        }

        let options = $('#shipping-address select[name="zone_id"] option');
        let result = new Array();
        $.map(options, function(e) {
            let value = $(e).val();
            let text = $(e).text();
            if (result.length < 6 && text.includes(zone)) {
                result[text]=value;
            }
        })

        if (result){
            $('#shipping-address select[name="zone_id"]').after('<div  class="autocomplete_result_block zone_result"><div id="close_zone_result" class="close_autocomplete_result_block" onclick="close_zone_result()">X</div></div>');
            for (let key in result) {
                $('.autocomplete_result_block').append('<div class="autocomplete_result_block_item" onclick="setzone(this)" id="val_' + result[key] + '">'+ key + '</div>');
            }
        } else {
            $('#shipping-address select[name="zone_id"]').after('<div  class="autocomplete_result_block zone_result"><div id="close_zone_result" class="close_autocomplete_result_block" onclick="close_zone_result()">X</div class="autocomplete_result_block_item">Страна не найдена</div>');
        }
    });

    function close_zone_result() {
        $('.zone_result').remove();
    }

    function setzone(el) {
        let value = $(el).attr('id').replace('val_','');
        $('#shipping-address select[name="zone_id"] option[value="'+value+'"]').prop('selected', true);
        $('#zone').val($(el).text());
        $('.zone_result').remove();
        $('#shipping-address select[name=\'zone_id\']').trigger('change');
    }
    //Zone live search END


    function getAddressByPostcode() {
        let postcode = $('#shipping-address input[name=\'postcode\']').val();
        $.ajax({
            url: 'index.php?route=checkoutf/shipping_addressd/getAddressByPostcode',
            type: 'post',
            data: 'postcode='+postcode,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.city !== undefined) {

                    $('#country').val('Российская Федерация');
                    $('#shipping-address select[name="country_id"] option[value="'+data.country_id+'"]').prop('selected', true);

                    // if (!$('#zone').val() ) {
                        $('#zone').val(data.zone_name);
                        $('#shipping-address select[name="zone_id"] option[value="' + data.zone_id + '"]').prop('selected', true);
                    // }

                    // if ($('#shippingCity').val() == '' ) {
                        if (data.zone_name == 'Москва' && data.city == ''){
                            $('#shippingCity').val(data.zone_name);
                        } else {
                            $('#shippingCity').val(data.city);
                        }
                    // }

                }
            }
        });
    }

    function loadShippingMethods() {
        $('#shipping-method .checkout-content').empty();
        $.ajax({
            url: 'index.php?route=checkoutf/shipping_addressd/validate',
            type: 'post',
            data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-address').attr('disabled', true);
                $('#button-shipping-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-shipping-address').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                console.log(json);
                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) { /*
                    if (json['error']['warning']) {
                        $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }
                    if (json['error']['middlename']) {
                        $('#shipping-address input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['address_2']) {
                        $('#shipping-address input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
                    }

                    if (json['error']['address_3']) {
                        $('#shipping-address input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }*/
                } else {
                    $.ajax({
                        url: 'index.php?route=checkoutf/shipping_methodd',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-method .checkout-content').html(html);

                            // $('#shipping-address .checkout-content').slideUp('slow');

                            $('#shipping-method .checkout-content').slideDown('slow');

                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();

                            $('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

                            $.ajax({
                                url: 'index.php?route=checkoutf/shipping_addressd',
                                dataType: 'html',
                                success: function(html) {
                                    // $('#shipping-address .checkout-content').html(html);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    $.ajax({
                        url: 'index.php?route=checkoutf/payment_address',
                        dataType: 'html',
                        success: function(html) {
                            $('#payment-address .checkout-content').html(html);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }


    $('#button-shipping-method').live('click', function() {

        if ($('#replacement_for').val() !== '' && $('#order_for_replacement_exist:enabled').val() == 'false' ){
            alert('Вы указали несуществующий заказ для обмена');
            return false;
        }

        $.ajax({
            url: 'index.php?route=checkoutf/shipping_addressd/validate',
            type: 'post',
            data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping_method_confirmation'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-shipping-method').attr('disabled', true);
                $('#button-shipping-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-shipping-method').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                console.log(json);
                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }
                    if (json['error']['middlename']) {
                        $('#shipping-address input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['address_2']) {
                        $('#shipping-address input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
                    }

                    if (json['error']['address_3']) {
                        $('#shipping-address input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }
                    $('body, html').scrollTop($('#content').offset().top);
                } else {
                    var ddd = "#shipping-method input[type=\'radio\']:checked, #shipping-method textarea";
                    if ($("#cs").length > 0) {
                        ddd = ddd + ", #shipping-method input[name=\'shipping_cost\']";
                    }
                    if ($("#hybrid-cart").length) {
                        ddd = ddd + ", input[name=\'delivery-way\']:checked";
                    }
                    if ($("#md").length > 0) {

                        ddd = ddd + ", #shipping-method input[name=\'markupdropshipping\']";
                    }

                    if ($("#prepayment").length > 0) {
                        ddd = ddd + ", #shipping-method input[name=\'prepayment\']";
                    }

                    if ($("#ps").length > 0) {
                        ddd = ddd + ", #shipping-method input[name=\'passport-seria\']";
                    }

                    if ($("#pn").length > 0) {
                        ddd = ddd + ", #shipping-method input[name=\'passport-number\']";
                    }

                    if ($("#replacement_for:enabled").length > 0) {
                        ddd = ddd + ", #shipping-address input[name=\'replacement_for\']";
                    }

                    if ($("#buybuysu_bc").length > 0) {
                        ddd = ddd + ", #shipping-method input[name=\'buybuysu_bc\']:checked";
                    }

                    if ($('#cdek_extra_options_form').length > 0) {
                        ddd = ddd + ', #cdek_extra_options_form input:checked'
                    }

                    var counts = $('.price_drop_row').length;
                    if (counts > 0) {
                        var markupError = false;
                        for (i = 1; i <= counts; i++) {
                            var $input = $('#shipping-method input[name="price_drop_' + i + '"]');
                            var $value = $('#shipping-method #total_margin_drop_' + i);
                            var value = $value.text();
                            if (!value || +value < 0) {
                                $input.css('border', '1px solid red');
                                markupError = true;
                            }
                            ddd = ddd + ", #shipping-method input[name=\'price_drop_" + i + "\']";
                        }
                    }
                    if (markupError) {
                        $('.warning, .error').remove();
                        $('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">Исправьте ошибку в ВАШЕЙ наценке!<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                        $('.warning').fadeIn('slow');
                        $('body, html').scrollTop($('#shipping-method .warning').offset().top);
                        return;
                    }
                    $.ajax({
                        url: 'index.php?route=checkoutf/shipping_methodd/validate',
                        type: 'post',
                        data: $(ddd),
                        dataType: 'json',
                        beforeSend: function () {
                            $('#button-shipping-method').attr('disabled', true);
                            $('#button-shipping-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
                        },
                        complete: function () {
                            $('#button-shipping-method').attr('disabled', false);
                            $('.wait').remove();
                        },
                        success: function (json) {
                            console.log(json);
                            $('.warning, .error').remove();

                            if (json['redirect']) {
                                location = json['redirect'];
                            } else if (json['error']) {
                                if (json['error']['warning']) {
                                    $('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                                    $('.warning').fadeIn('slow');
                                    $('body, html').scrollTop($('#shipping-method .warning').offset().top);
                                }
                            } else {
                                $.ajax({
                                    url: 'index.php?route=checkoutf/payment_method',
                                    dataType: 'html',
                                    success: function (html) {
                                        $('#payment-method .checkout-content').html(html);

                                        $('#shipping-address .checkout-content').hide();
                                        $('#shipping-method .checkout-content').hide();

                                        // $('#payment-method .checkout-content').show();

                                        $('#shipping-method .checkout-heading a').remove();
                                        $('#payment-method .checkout-heading a').remove();

                                        $('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                });
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            }
        });
    });

    $('#button-payment-method').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkoutf/payment_method/validate',
            type: 'post',
            data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-payment-method').attr('disabled', true);
                $('#button-payment-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('#button-payment-method').attr('disabled', false);
                $('.wait').remove();
            },
            success: function(json) {
                console.log(json);
                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkoutf/confirm',
                        dataType: 'html',
                        success: function(html) {
                            $('#confirm .checkout-content').html(html);

                            $('#payment-method .checkout-content').hide();

                            $('#confirm .checkout-content').show();

                            $('#payment-method .checkout-heading a').remove();

                            $('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
                            // $('#button-confirm').before('<a href="/index.php?route=checkout/cartd" class="button">Редактировать наценку</a>');
                            $('#button-confirm').before('<a onclick="returnToShippingMethod();" class="button">Редактировать заказ</a>');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });


    function returnToShippingMethod() {
        $('#confirm .checkout-content').hide();
        $('#shipping-address .checkout-content').show();
        $('#shipping-method .checkout-content').show();
    }
    //--></script>
<?php echo $footer; ?>
