

$(document).ready(function(){





	$(".createprepayorder").click(function() {


		var $order_summ = $("input[name='order_summ']", "#createprepayorder");
		    $buyer_fio = $("input[name='buyer_fio']", "#createprepayorder");
		    $buyer_email = $("input[name='buyer_email']", "#createprepayorder");
		    $buyer_phone = $("input[name='buyer_phone']", "#createprepayorder");

		if ($order_summ.val()=="" || $buyer_fio.val()==""  || $buyer_email.val()==""  || $buyer_phone.val()=="") {

			$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
			setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 5000);


		} else {		    

			$.ajax({

				  type: 'POST',
				  url: '/sp/create_order_handler/',
				  data: {
					order_summ: $order_summ.val(),
					buyer_fio: $buyer_fio.val(),
					buyer_email: $buyer_email.val(),
					buyer_phone: $buyer_phone.val()
				  },

				  success: function(data){

					var res = JSON.parse(data);

					if (res.error) {

						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 5000);


					} else {


						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');

						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 5000);


						
						//свернуть окно и ...
						setTimeout( function() {parent.jQuery.fn.fancybox.close(); }, 2000);

						// подсветить новый заказ
						setTimeout( function() { $('#table_header').after(res.new_line); }, 2500);
						

						

					}


				  }

			});




		}	



	});






	$(".attachorder").click(function() {


		var $order_id = $("input[name='order_id']", "#attachorder");
		    $pay_order_id = $("input[name='pay_order_id']", "#attachorder");

		if ($order_id.val()=="" || $pay_order_id.val()=="") {

			$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
			setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 5000);


		} else {		    

			$.ajax({

				  type: 'POST',
				  url: '/spbuh/attach_order_handler/',
				  data: {
					order_id: $order_id.val(),
					pay_order_id: $pay_order_id.val()
				  },

				  success: function(data){

					var res = JSON.parse(data);

					if (res.error) {

						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 3000);

						if (res.err_realy) {

							$("#realy_yes").css("visibility","visible");

						}


					} else {


						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');

						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 3000);


						
						//свернуть окно и ...

						setTimeout( function() {parent.jQuery.fn.fancybox.close(); }, 2000);


						// подсветить новый заказ


						setTimeout( function() { $('#' + res.pay_order_id).html(res.new_line);$('#' + res.pay_order_id).css("background-color","#c8eac5"); }, 2500);
						

						

					}


				  }

			});




		}	



	});





	$(".forceattach").click(function() {


		var $order_id = $("input[name='order_id']", "#attachorder");
		    $pay_order_id = $("input[name='pay_order_id']", "#attachorder");
		    $force = 'forceattach';

		if ($order_id.val()=="" || $pay_order_id.val()=="") {

			$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
			setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 5000);


		} else {		    

			$.ajax({

				  type: 'POST',
				  url: '/spbuh/attach_order_handler/',
				  data: {
					order_id: $order_id.val(),
					pay_order_id: $pay_order_id.val(),
					force: $force
				  },

				  success: function(data){

					var res = JSON.parse(data);

					if (res.error) {

						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');
						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 1000);

						if (res.err_realy) {

							$("#realy_yes").css("visibility","visible");

						}


					} else {


						$(".error_message").html(res.msg);
						$(".error_message").addClass('on animated fadeIn dur5delay1 fadeOut');

						setTimeout(function() { $(".error_message").removeClass('on animated fadeIn dur5delay1 fadeOut') }, 1000);


						
						//свернуть окно и ...

						setTimeout( function() {parent.jQuery.fn.fancybox.close(); }, 2000);


						// подсветить новый заказ


						setTimeout( function() { $('#' + res.pay_order_id).html(res.new_line);$('#' + res.pay_order_id).css("background-color","#c8eac5"); }, 2500);
						

						

					}


				  }

			});




		}	



	});









});









