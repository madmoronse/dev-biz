$(document).ready(function() {

  $(document).mouseup(function (e) {
    var container = $("#notification");
    if ((e.which === 1)&&(container.has(e.target).length === 0)){
        $(".close").trigger("click");
        // ismouseleave = false;\
        $('.backg_notif').remove();
    }
  });

  $('.option_box').find('input[type="checkbox"]').on('change', function(){
    var checker = $(this).parents('.option_box').find('input[type="checkbox"]:checked').length;
    if(checker == 0){
      $(this).parents('.option_box').removeClass('active');
    }else{
      $(this).parents('.option_box').addClass('active');
    }
  });

  

  $('body').on('click','#login-modal',function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
      url: url,
      success: function(data){
          //var json = JSON.parse(data);
          $('#notification').before('<div class="backg_notif smooth"></div>').html('<div class="success modal-beautify"><img src="catalog/view/theme/default/image/close-5.png" alt="" class="close" />'+data+'</div>');
      }
    });
  });

  $('body').on('click','#cdek-info-order',function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
      url: url,
      success: function(data){
          //var json = JSON.parse(data);
          $('#notification').before('<div class="backg_notif smooth"></div>').html('<div class="success modal-beautify"><img src="catalog/view/theme/default/image/close-5.png" alt="" class="close" />'+data+'</div>');
      }
    });
  });

  $("body").on("click","a.information.modalLink",function(e) {
    e.preventDefault();
    var url = $(this).attr('ajaxurl');
    $.ajax({
      url: url,
      success: function(data){
          var json = JSON.parse(data);
          $('#notification').before('<div class="backg_notif"></div>').html('<div class="success success_add_to_cart"><div id="notification_top">'+json.heading_title+'</div><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /><div id="productsincart_block">'+json.description+'</div></div>');
      }
    });

  });

  $('#subscribe-button').on('click',function() {
    var emailfield = $('#subscribe-email');
    if(emailfield !== undefined){
      var email = emailfield.val();
      if(email != ""){
        $.ajax({
          url: "/index.php?route=account/subscribe",
          data: {email:email},
          success: function(data) {
            try{
               var json = JSON.parse(data);
               if (!json) {
                alert('Произошла ошибка, попробуйте позже.');
                return false;
               }
               if(json.success !== undefined){
                 alert(json.success);
               }else if(json.error !== undefined){
                 alert(json.error);
               }else if(json.warning !== undefined){
                 alert(json.warning);
               }
               emailfield.val("");
            }
            catch(e){
               alert('Произошла ошибка, попробуйте позже.');
            }
          }
        });
      }
    }
  });

	/* Search */
	$('.button-search').bind('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';

		var search = $('input[name=\'search\']').attr('value');

		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}

		location = url;
	});

	$('#header input[name=\'search\']').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			url = $('base').attr('href') + 'index.php?route=product/search';

			var search = $('input[name=\'search\']').attr('value');

			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}

			location = url;
		}
	});

	/* Ajax Cart */
	// $('#cart > .heading a').live('click', function() {
	// 	$('#cart').addClass('active');
  //
	// 	$('#cart').load('index.php?route=module/cart #cart > *');
  //
	// 	$('#cart').live('mouseleave', function() {
	// 		$(this).removeClass('active');
	// 	});
	// });

  $('#cart').on('hover', '.heading a', function() {
		// if(carthover){
      $('#cart').addClass('active');

  		$('#cart').load('index.php?route=module/cart #cart > * ');

      $('#cart-total-block').load('index.php?route=module/cart #cart-total-block > *');

  		$('#cart').on('mouseleave', function() {
  			$(this).removeClass('active');
  		});
    //}
	});

	/* Mega Menu */
	$('#menu ul > li > a + div').each(function(index, element) {
		// IE6 & IE7 Fixes
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
			var category = $(element).find('a');
			var columns = $(element).find('ul').length;

			$(element).css('width', (columns * 143) + 'px');
			$(element).find('ul').css('float', 'left');
		}

		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 1) + 'px');
		}
	});

	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');

			$('#column-right + #content').css('margin-right', '195px');

			$('.box-category ul li a.active + ul').css('display', 'block');
		}

		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});

			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});
		}
	}

	$('.success .close, .warning img, .attention img, .information img').live('click', function() {
		$('#popup-promo-info').remove();
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
      $('.backg_notif').remove();
		});
	});

  $(document).mouseleave(function(event) {
    if(ismouseleave === false){
      ismouseleave = true;
      var autoShowFullCart = sessionStorage.getItem('autoShowFullCart');
      var autoShowEmptyCart = sessionStorage.getItem('autoShowEmptyCart');
      if(autoShowFullCart == null){
        showCartOnExit('full');
      }else if(autoShowEmptyCart == null){
        showCartOnExit('empty');
      }
    }

  });

});

var ismouseleave = false;

var carthover = false;

function showNotification(html, closeCallback) {
  $('#notification')
    .before('<div class="backg_notif smooth"></div>')
    .html('<div class="success modal-beautify"><img src="catalog/view/theme/default/image/close.png" alt="" class="close" id="neos-notification-close"/>'+html+'</div>');
  $('#neos-notification-close').on('click', function(event) {
    event.preventDefault();
    if (typeof closeCallback === 'function') closeCallback();
  });
}

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

function showCartOnExit(whatis) {
  var autoShowFullCart = sessionStorage.getItem('autoShowFullCart');
  var autoShowEmptyCart = sessionStorage.getItem('autoShowEmptyCart');

	$.ajax({
  	url: 'index.php?route=checkout/cart/showCart',
  	type: 'post',
  	dataType: 'json',
  	beforeSend: function(){
  	},
  	success: function(json) {
    	//$('.success, .warning, .attention, information, .error').remove();

      if(json === null){
        return;
      }

      	if (json['error']) {
      		if (json['error']['warning']) {
      			$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
      			$('.warning').fadeIn('slow');
      		}
      		if (json['error']) {
      			if (json['error']['option']) {
      				for (i in json['error']['option']) {
      					$('.options-close-' + product_id).fadeIn();
      					$('.popup-options-' + product_id).fadeIn();
      					$('.modal-overlay-' + product_id).fadeIn();
      					//$('#option-' + i).after('<span class="error">' + /*json['error']['option'][i]*/ 'Выберите размер!' +  '</span>');
      					alert(json['error']['option'][i]);
      					//$('#option-' + i).after('<div class="error">' + 'Выберите размер!' +  '</div>');
      				}
      			}
      		}
      	}

        if((json['products'] != undefined)&&(json['showcart'] == 'autoShowFullCart')){
          if((whatis == 'empty')&&(autoShowFullCart)){
            return;
          }
          // sessionStorage.removeItem('autoShowEmptyCart');
          $('#notification').before('<div class="backg_notif no-bg"></div>').html('<div class="success success_add_to_cart" style="display: none;"><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /><div id="productsincart_block"><table id="productsincart"><tr id="productsincart_head"><td colspan="4" id="productsincart_head_td">Товары в корзине:<td></tr></table></div><br /><span id="success_add_to_cart_continue"><a class="button close_success">Продолжить покупки</a></span><span id="success_add_to_cart_cart"><a class="button" href="./index.php?route=checkout/cart">Перейти в корзину</a></span><span id="success_add_to_cart_order"><a class="button" href="./index.php?route=checkout/checkout">Оформить заказ</a></span></div>');
    	    for (index = 0; index < json['products'].length; ++index) {
      				$('#productsincart').append('<tr><td class="image"><a href="' + json['products'][index]['href'] + '"><img src="' + json['products'][index]['thumb'] +'" alt="' + json['products'][index]['name'] + '" title="' + json['products'][index]['name'] + '"/></a></td><td class="name"><a href="' + json['products'][index]['href'] +'">' + json['products'][index]['name'] +' (Артикул: '+ json['products'][index]['product_id'] +')</a><div><small id="cart_option_'+ index +'"></small><br /></div></td><td class="quantity"><div class="spinner"><button onclick="incDecCart(this)" operation="inc">+</button><input type="text" value="'+json['products'][index]['quantity']+'" name="quantity" pkey="'+json['products'][index]['key']+'" disabled><button onclick="incDecCart(this)" operation="dec">-</button></div></td><td class="total">'+json['products'][index]['total']+'</td></tr>');

      				if (json['products'][index]['option'].length > 0) {
      					$('#cart_option_'+ index).append(json['products'][index]['option'][0]['name']+': '+json['products'][index]['option'][0]['value'])
      				}
			    }
          $('.success').fadeIn('slow');
          $('#cart-total').html(json['total']);
          $('html, body').animate('none');
          $('.modal-overlay').fadeOut();
          $('.popup-options').fadeOut();
          $('.close_success').on('click', function(){
          $('.close').trigger('click');
          $('.backg_notif').remove();
          });
          $('.close').on('click', function(){
            // $('.backg_notif').remove();
            // ismouseleave = false;
          });
          // $(document).mouseup(function (e) {
          //   var container = $("#notification");
          //   if (container.has(e.target).length === 0){
          //       container.fadeOut('slow');
          //       $('.backg_notif').remove();
          //       // ismouseleave = false;
          //   }
          // });
          sessionStorage.setItem('autoShowFullCart', true);
        }
        /*else if(json['showcart'] == 'autoShowEmptyCart'){
          if((whatis == 'full')&&(autoShowEmptyCart)){
            return;
          }
          // sessionStorage.removeItem('autoShowFullCart');
          $('#notification').before('<div class="backg_notif no-bg"></div>').html('<div class="success" style="display: none;"><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /><div id="info_notification_block"></div><br /><span id="req_result"></span></div>');
          $('#info_notification_block').append(json['backlink']);
          $('.success').fadeIn('slow');
          $('html, body').animate('none');
          $('.modal-overlay').fadeOut();
          $('.popup-options').fadeOut();
          $('.close_success').on('click', function(){
            $('.close').trigger('click');
            $('.backg_notif').remove();
          });
          $('#sendemailforquestionbutton').click(function() {
            var email = $('#emailforquestion'), eval = email.val();
            if( /(.+)@(.+){2,}\.(.+){2,}/.test(eval) ){
              email.removeClass('red-color');
              $.ajax({
              	url: 'index.php?route=checkout/cart/sendemailforquestion',
                data: 'email='+eval,
              	type: 'post',
              	dataType: 'json',
                success: function(json) {
                    if(json['success']){
                      $('#info_notification_block').html(json['success'])
                    }else if(json['error']){
                      $('#req_result').html(json['error']);
                    }
                }
              });
            } else {
              email.addClass('red-color');
            }
          });
          $('.close').on('click', function(){
            // ismouseleave = false;
            // $('.backg_notif').remove();
          });
          // $(document).mouseup(function (e) {
          //   var container = $("#notification");
          //   if (container.has(e.target).length === 0){
          //       container.fadeOut('slow');
          //       $('.backg_notif').remove();
          //       // ismouseleave = false;
          //   }
          // });
          sessionStorage.setItem('autoShowEmptyCart', true);
        }*/
    }
	});
}

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#cart-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
}
function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

        $('.close_success').on('click', function(){
          $('.close').trigger('click');
          $('.backg_notif').remove();
          });

          $('.close').on('click', function(){
          $('.backg_notif').remove();
          });
					
			}
		}
	});
}

function addToCompare(product_id) {
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#compare-total').html(json['total']);
				$('#compare-total-top').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('.close_success').on('click', function(){
					$('.close').trigger('click');
					$('.backg_notif').remove();
					});

					$('.close').on('click', function(){
					$('.backg_notif').remove();
					});

			}
		}
	});
}

function tracking(track) {
  $.ajax({
    url: 'index.php?route=account/track/track',
    type: 'post',
    data: 'track=' + track,
    dataType: 'json',
    success: function(json) {
        if (json['success']) {
            $('#notification').before('<div class="backg_notif"></div>').html('<div class="cdek-info-order"> <table><thead><tr><td>Город</td><td>Дата</td><td>Статус</td></tr></thead><tbody></tbody></table><span class="close">х</span></div>');
            var inf = '';

            for (var i = 0; i < json['success'].length; i++) {
                inf += '<tr> <td class="cdek-order-cityname">' + json['success'][i]['CityName'] + '</td> <td class="cdek-order-date">' + json['success'][i]['Date'].slice(0, -15) + '</td> <td class="cdek-order-description">' + json['success'][i]['Description'] + '</td> </tr>';
                $('.cdek-info-order').children('table').children('tbody').html(inf);
            }


            $('html, body').animate({scrollTop: 0}, 'slow');

            $('.close_success').on('click', function () {
                $('.close').trigger('click');
                $('.backg_notif').remove();
            });

            $('.close').on('click', function () {
                $('.backg_notif').remove();
                $('.cdek-info-order').remove();
            });
    } else {
            $('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

            $('.success').fadeIn('slow');

            $('#compare-total').html(json['total']);
            $('#compare-total-top').html(json['total']);

            $('html, body').animate({ scrollTop: 0 }, 'slow');

            $('.close_success').on('click', function(){
                $('.close').trigger('click');
                $('.backg_notif').remove();
            });

            $('.close').on('click', function(){
                $('.backg_notif').remove();
            });
        }
    }
  })
}

function setComment(comment_id) {
  $.ajax({
    url: 'index.php?route=account/comment/getComment',
    data: 'comment_id=' + comment_id,
    dataType: 'json',
    success: function(json) {
      if (json['success']) {
        $('#comment').html(json['success']['content']);
      } else {
        alert(json['error']);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus + ', ' + errorThrown); 
      alert("К сожалению, что-то пошло не так. Не удалось загрузить комментарий :(");
    }
  })
}

function incDecCart(element) {
  var input = $(element).parent().children('input');
  var operation = $(element).attr('operation');
  var name = input.attr('name'), val = parseInt(input.val()), pkey = input.attr('pkey');
  if(operation == 'inc'){
    input.val(val+1);
  }else if(operation == 'dec'){
    input.val(val-1);
  }else{
    return;
  }
  val = parseInt(input.val());
  if(Number.isInteger(val)){
    $.ajax({
  	url: 'index.php?route=checkout/cart',
  	type: 'post',
  	data: "val="+val+"&incdec=1&pkey="+pkey,
    success: function(data) {
        var product = JSON.parse(data);
        if(product != null){

          $(element).parent().parent().parent().children('.total').html(product.total_val);
        }
        if(val === 0){
          $(element).parent().parent().parent().remove();
        }
      }
    })
  }

}
