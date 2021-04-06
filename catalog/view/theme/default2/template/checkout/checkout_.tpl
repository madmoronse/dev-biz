<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="checkout-body">
    <div class="checkout-title">Оформление заказа: Шаг 1 из 3<?php // echo $text_checkout_option; ?></div>
    <div class="checkout-content">

    </div>
    <div class="checkout-control">
      <button class="button" id="prev-step" act="step-0">Назад</button>
      <span class="info">Нажмите, чтобы перейти на страницу ввода адреса доставки</span>
      <button class="button" id="next-step" act="step-2">Продолжить</button>
    </div>
    <div class="checkout-steps">
      <div class="checkout-steps__item active">
        <span class="number">1</span>
        <span class="text">Контактные данные</span>
      </div>
      <div class="checkout-steps__item">
        <span class="number">2</span>
        <span class="text">Адрес доставки</span>
      </div>
      <div class="checkout-steps__item">
        <span class="number">3</span>
        <span class="text">Варианты доставки</span>
      </div>
    </div>
    <?php /* if (!$logged) { ?>
    <div id="payment-address" >
      <div class="checkout-heading"><span><?php echo $text_checkout_account; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } else { ?>
    <div id="payment-address" style="display:none;">
      <div class="checkout-heading"><span><?php echo $text_checkout_payment_address; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <?php if ($shipping_required) { ?>
    <div id="shipping-address">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_address; ?></div>
      <div class="checkout-content"></div>
    </div>
    <div id="shipping-method">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <div id="payment-method" style="display:none;">
      <div class="checkout-heading"><?php echo $text_checkout_payment_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <div id="confirm">
      <div class="checkout-heading"><?php echo $text_checkout_confirm; ?></div>
      <div class="checkout-content"></div>
    </div>
    */ ?>
  </div>
  <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$('#checkout .checkout-content input[name=\'account\']').live('change', function() {
	if ($(this).attr('value') == 'register') {
		$('.checkout-heading span').html('<?php echo $text_checkout_account; ?>');
	} else {
		$('.checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
	}
});

$('.checkout-heading a').live('click', function() {
	$('.checkout-content').slideUp('slow');

	$(this).parent().parent().find('.checkout-content').slideDown('slow');
});
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_address',
    data: {ste: 1},
		dataType: 'html',
		success: function(html) {
			$('.checkout-content').html(html);

			$('.checkout-content').slideDown('slow');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//next step
$('#next-step').on('click',function(){
  var action = $(this).attr('act');
  switch (action) {
    case "step-2":{
      if (!$("#telephone").inputmask("isComplete")){
          $("input[name='telephone']").after($('<p></p>').addClass('error').text('Заполните данное поле!'));
         break;
       }
       if ($("#email").val() == ""){
         $("input[name='email']").after($('<p></p>').addClass('error').text('Заполните данное поле!'));
          break;
        }
      $.ajax({
        url: 'index.php?route=checkout/payment_address/validate&step=2',
        type: 'post',
        data: $('input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select'),
        dataType: 'json',
        beforeSend: function() {
          $('#button-payment-address').attr('disabled', true);
          $('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
          $('input').removeClass('error');
        },
        complete: function() {
          $('#button-payment-address').attr('disabled', false);
          $('.wait').remove();
        },
        success: function(json) {
          $('.warning, .error').remove();
          if (json['redirect']) {
            location = json['redirect'];
          } else if (json['error']) {
            if (json['error']['warning']) {
              $('.checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
              $('.warning').fadeIn('slow');
            }
            console.log(json['error']);
            for (key in json['error']) {
              $("input[name='"+key+"']").after($('<p></p>').addClass('error').text(json['error'][key]));
            }
            if(json['error']['yrregistered'] !== undefined){
              $.ajax({
                url: "/login/?ajax=1",
                success: function(data){
                    //var json = JSON.parse(data);
                    $('#notification').before('<div class="backg_notif smooth"></div>').html('<div class="success modal-beautify"><img src="catalog/view/theme/default/image/close.png" alt="" class="close" />'+data+'</div>');
                    $('#notification').find('h1').text(json['error']['yrregistered']);
                    $('#notification').find('#email').val(json['email']);
                }
              });
              console.log(json['error']['yrregistered']);
            }
          } else {

            $.ajax({
              url: 'index.php?route=checkout/payment_address&step=2',
              dataType: 'html',
              beforeSend: function() {
                $('.checkout-content').slideUp('slow');
              },
              success: function(html) {
                $('.checkout-content').html(html);
                $('.checkout-content').slideDown('slow');
                $('#prev-step').addClass('active');
                $(".checkout-steps__item").eq(1).addClass('active');
                $('#next-step').attr('act','step-3');
                $('#prev-step').attr('act','step-1');
                $("span.info").text("Нажмите, что бы перейти на страницу выбора способа доставки");
                $('.checkout-title').text('Оформление заказа: Шаг 2 из 3');
              },
              error: function(xhr, ajaxOptions, thrownError) {
                // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
      }
      break;
    case "step-3":{
      $.ajax({
        url: 'index.php?route=checkout/payment_address/validate&step=3',
        type: 'post',
        data: $('input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select, textarea'),
        dataType: 'json',
        beforeSend: function() {
          // $('#button-payment-address').attr('disabled', true);
          // $('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
          $('input').removeClass('error');
        },
        complete: function() {
          $('#button-payment-address').attr('disabled', false);
          $('.wait').remove();
        },
        success: function(json) {
          $('.warning, .error').remove();
          if (json['redirect']) {
            location = json['redirect'];
          }
          if(json['error']){
            if (json['error']['city_not_found']) {
              $("input[name='np']").after($('<p></p>').addClass('error').text(json['error']['city_not_found']));
              setTimeout(function() {
                $( "#np" ).autocomplete("search");
              }, 1000);
            }
            for (key in json['error']) {
              $("input[name='"+key+"']").after($('<p></p>').addClass('error').text(json['error'][key]));
            }
          }else{
            $.ajax({
              url: 'index.php?route=checkout/shipping_method',
              dataType: 'html',
              beforeSend: function() {
                $('.checkout-content').slideUp('slow');
              },
              success: function(html) {
                $('.checkout-content').html(html);
                $(".checkout-steps__item").eq(2).addClass('active');
                $('#next-step').attr('act','confirm');
                $('#next-step').text('Оформить');
                $("span.info").text("");
                $('#prev-step').attr('act','step-2');
                $('.checkout-title').text('Оформление заказа: Шаг 3 из 3');
                $('.checkout-content').slideDown('slow');

              },
              error: function(xhr, ajaxOptions, thrownError) {
                // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });
          }
        }});
      break;
    }
    case "confirm":{
      var inputway = $("input:radio[name='delivery-way']:checked").val();
      if(inputway !== undefined){
        $.ajax({
					url: 'index.php?route=checkout/confirm',
					dataType: 'html',
          data: {deliverytype: inputway},
					success: function(data) {
            try{
               var json = JSON.parse(data);
               if (json['redirect'] != undefined) {
                 location = json['redirect'];
               }
            }catch(e){
               location = "index.php?route=checkout/success";
            }
					},
					error: function(xhr, ajaxOptions, thrownError) {
						// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
      }
      break;
    }
    default:

  }
});
//prev step
$('#prev-step').on('click',function(){
  var action = $(this).attr('act');
  switch(action){
    case 'step-1':{
      $.ajax({
    		url: 'index.php?route=checkout/payment_address',
        data: {ste: 1},
    		dataType: 'html',
        beforeSend: function() {
          $('.checkout-content').slideUp('slow');
        },
    		success: function(html) {
    			$('.checkout-content').html(html);
          $('.checkout-title').text('Оформление заказа: Шаг 1 из 3');
          $(this).removeClass('active').attr('act','step-0');
          $(".checkout-steps__item").eq(1).removeClass('active');
          $('#next-step').attr('act','step-2');
    			$('.checkout-content').slideDown('slow');
          $("span.info").text("Нажмите, чтобы перейти на страницу ввода адреса доставки");
    		},
    		error: function(xhr, ajaxOptions, thrownError) {
    			// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    		}
    	});
      break;
    }
    case 'step-2':{
      $.ajax({
        url: 'index.php?route=checkout/payment_address&step=2',
        dataType: 'html',
        beforeSend: function() {
          $('.checkout-content').slideUp('slow');
        },
        success: function(html) {
          $('.checkout-content').html(html);
          $('.checkout-content').slideDown('slow');
          $('#prev-step').addClass('active');
          $(".checkout-steps__item").eq(2).removeClass('active');
          $('#next-step').attr('act','step-3');
          $('#next-step').text('Продолжить');
          $('#prev-step').attr('act','step-1');
          $("span.info").text("Нажмите, что бы перейти на страницу выбора способа доставки");
          $('.checkout-title').text('Оформление заказа: Шаг 2 из 3');
        },
        error: function(xhr, ajaxOptions, thrownError) {
          // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
      break;
    }
    default:{
      break;
    }
  }
});
// Checkout
$('#button-account').live('click', function() {

	if ($('#quick').prop("checked") == true) {
		$.colorbox({href:'./index.php?route=module/fastorderdialog/open', iframe:true, width:'520', height:'780',overlayClose:false});
	} else {

		$.ajax({
			url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').attr('value'),
			dataType: 'html',
			beforeSend: function() {
				$('#button-account').attr('disabled', true);
				$('#button-account').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('#button-account').attr('disabled', false);
				$('.wait').remove();
			},
			success: function(html) {
				$('.warning, .error').remove();

				$('.checkout-content').html(html);

				$('#checkout .checkout-content').slideUp('slow');

				$('.checkout-content').slideDown('slow');

				$('.checkout-heading a').remove();

				$('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	}
});

// Login
$('#button-login').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/login/validate',
		type: 'post',
		data: $('#checkout #login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#checkout .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Register
$('#button-register').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/register/validate',
		type: 'post',
		data: $('input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').attr('disabled', true);
			$('#button-register').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-register').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('.checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				if (json['error']['middlename']) {
					$('input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}
				if (json['error']['address_2']) {
					$('input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
				}
				if (json['error']['address_3']) {
					$('input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
				}

				if (json['error']['city']) {
					$('input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}

				if (json['error']['password']) {
					$('input[name=\'password\']').after('<span class="error">' + json['error']['password'] + '</span>');
				}

				if (json['error']['confirm']) {
					$('input[name=\'confirm\']').after('<span class="error">' + json['error']['confirm'] + '</span>');
				}
			} else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);

							$('.checkout-content').slideUp('slow');

							$('#shipping-method .checkout-content').slideDown('slow');

							$('#checkout .checkout-heading a').remove();
							$('.checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
							$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');

							$.ajax({
								url: 'index.php?route=checkout/shipping_address',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
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
				} else {
					$.ajax({
						url: 'index.php?route=checkout/shipping_address',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('.checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');

							$('#checkout .checkout-heading a').remove();
							$('.checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('.checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#checkout .checkout-heading a').remove();
						$('.checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('.checkout-content').html(html);

						$('.checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
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

// Payment Address
$('#button-payment-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_address/validate',
		type: 'post',
		data: $('input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select'),
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
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('.checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				if (json['error']['middlename']) {
					$('input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['address_2']) {
					$('input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
				}

				if (json['error']['address_3']) {
					$('input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
				}

				if (json['error']['city']) {
					$('input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($shipping_required) { ?>
				$.ajax({
					url: 'index.php?route=checkout/shipping_address',
					dataType: 'html',
					success: function(html) {
						$('#shipping-address .checkout-content').html(html);

						$('.checkout-content').slideUp('slow');

						$('#shipping-address .checkout-content').slideDown('slow');

						$('.checkout-heading a').remove();
						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('.checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('.checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('.checkout-content').html(html);
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
$('#button-shipping-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping_address/validate',
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
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

						$.ajax({
							url: 'index.php?route=checkout/shipping_address',
							dataType: 'html',
							success: function(html) {
								$('#shipping-address .checkout-content').html(html);
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
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('.checkout-content').html(html);
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

// Guest
$('#button-guest').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/guest/validate',
		type: 'post',
		data: $('input[type=\'text\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest').attr('disabled', true);
			$('#button-guest').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('.checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				if (json['error']['middlename']) {
					$('input[name=\'middlename\']').after('<span class="error">' + json['error']['middlename'] + '</span>');
				}

				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}

				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['company_id']) {
					$('input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}

				if (json['error']['tax_id']) {
					$('input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['address_2']) {
					$('input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
				}

				if (json['error']['address_3']) {
					$('input[name=\'address_3\']').after('<span class="error">' + json['error']['address_3'] + '</span>');
				}

				if (json['error']['city']) {
					$('input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);

							$('.checkout-content').slideUp('slow');

							$('#shipping-method .checkout-content').slideDown('slow');

							$('.checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');

							$.ajax({
								url: 'index.php?route=checkout/guest_shipping',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
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
				} else {
					$.ajax({
						url: 'index.php?route=checkout/guest_shipping',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('.checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');

							$('.checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();

							$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('.checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('.checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('.checkout-heading').append('<a><?php echo $text_modify; ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Guest Shipping
$('#button-guest-shipping').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/guest_shipping/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest-shipping').attr('disabled', true);
			$('#button-guest-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest-shipping').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
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
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
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

$('#button-shipping-method').live('click', function() {
	var ddd = "#shipping-method input[type=\'radio\']:checked, #shipping-method textarea";
    if ($("#cs").length>0){
        ddd= ddd + ", #shipping-method input[name=\'shipping_cost\']";
		}

	if ($("#md").length>0){

        ddd= ddd + ", #shipping-method input[name=\'markupdropshipping\']";
    }

	if ($("#prepayment").length>0){
        ddd= ddd + ", #shipping-method input[name=\'prepayment\']";
    }

	if ($("#replacement_for").length>0){
        ddd= ddd + ", #shipping-method input[name=\'replacement_for\']";
    }

	if ($("#buybuysu_bc").length>0){
        ddd= ddd + ", #shipping-method input[name=\'buybuysu_bc\']:checked";
    }

	var counts = $('.price_drop_row').length;
	if (counts > 0) {

	for (i = 1; i <= counts; i++) {
		ddd= ddd + ", #shipping-method input[name=\'price_drop_"+i+"\']";
		}
	}

	$.ajax({
		url: 'index.php?route=checkout/shipping_method/validate',
		type: 'post',
		data: $(ddd),
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
			$('.warning, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);

						$('#shipping-method .checkout-content').slideUp('slow');

						$('#payment-method .checkout-content').slideDown('slow');

						$('#shipping-method .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();

						$('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
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

$('#button-payment-method').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_method/validate',
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
					url: 'index.php?route=checkout/confirm',
					dataType: 'html',
					success: function(html) {
						$('#confirm .checkout-content').html(html);

						$('#payment-method .checkout-content').slideUp('slow');

						$('#confirm .checkout-content').slideDown('slow');

						$('#payment-method .checkout-heading a').remove();

						$('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
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
//--></script>
<?php echo $footer; ?>
