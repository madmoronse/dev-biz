'use strict';

$(document).ready(function () {
   $('.for-niceselect').niceSelect();
   $('.floating-inp').on('input', function () {
      if ($(this).val().length > 0) {
         $(this).addClass('fulling');
      } else {
         $(this).removeClass('fulling');
      }
   });
   $('.business-form input#tel').inputmask({ "mask": "+7 (999) 999-99-99", showMaskOnHover: false });

   	$("form#dropForm").on("submit", function(event) {
	  	event.preventDefault();
	  	var type  = $('#dropForm #type_select').val();
	  	var name  = $('#dropForm #name').val();
	  	var tel   = $('#dropForm #tel').val();
	  	var email = $('#dropForm #email').val();
	  	var text  = $('#dropForm #text').val();
	  	$.ajax({
	        url: "index.php?route=drop/drop/send",
	        type: "POST",
	        dataType: "json",
	        data: {
	        	type  : type,
	        	name  : name,
	        	tel   : tel,
	        	email : email,
	        	text  : text,
	        },
	        beforeSend: function() {
	        	$('#dropSubmit').LoadingOverlay("show");
	        },
	        complete: function() {
	        	$('#dropSubmit').LoadingOverlay("hide");
	        },
	        success: function(data) {
	        	if(data.error==1) {
	        		notify('error', data.mess);	
	        	}
	        	else {
	        		$('#dropForm #type_select, #dropForm #name, #dropForm #tel, #dropForm #email, #dropForm #text').val('');
	        		notify('success', data.mess);
	        	}
	        }
	    });
	});
});

function notify(type, text) {
	var n = noty({
	    text		: text,
	    type		: type,
	    theme       : 'relax',
    	dismissQueue: true,
	    layout		: 'top',
	    speed       : 500,
	    timeout 	: 5000,
	    textAlign   : 'center',
	    easing      : 'swing',
	    animateOpen : {
	    	height  : 'toggle'
	    },
	    animateClose: {
	    	height  : 'toggle'
	    }
	});
	return n; 
}