<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<?php echo $content_top; ?>
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
  	<style type="text/css">
	  	.calc-block {
	  		min-height: 266px;
			background: #fff;
			border-top: 1px solid #c9c9c9;
			border-left: 1px solid #c9c9c9;
			border-right: 1px solid #c9c9c9;
			border-bottom: 1px solid #c9c9c9;
			padding: 10px;
			margin-bottom: 20px;
			overflow: auto;
	  	}
	  	.calc-block p.title {
	  		font-size: 14px;
			font-weight: bold;
			margin-bottom: 10px;
	  	}
	  	.calc-block input {
			background: #FFF;
			display: inline-block;
			vertical-align: middle;
			width: 429px;
			height: 30px;
			border: 1px solid #c9c9c9;
			-webkit-border-radius: 3px 3px 3px 3px;
			-moz-border-radius: 3px 3px 3px 3px;
			-khtml-border-radius: 3px 3px 3px 3px;
			border-radius: 3px 3px 3px 3px;
			vertical-align: middle;
			padding: 0 15px;
			-webkit-box-shadow: inset 0 1px 1px 0 rgba(0,0,0,0.1);
			-moz-box-shadow: inset 0 1px 1px 0 rgba(0,0,0,0.1);
			box-shadow: inset 0 1px 1px 0 rgba(0,0,0,0.1);
		}
		.calc-info {
			margin-top: 20px;
			min-height: 170px;
		}
		.calc-block .calc-table {
			width: 100%;
			border: none;
			border-spacing: 0px;
		}
		.calc-block .calc-table tr th, .calc-block .calc-table tr td {
			padding: 5px 10px;
			color: black;
		}
		.calc-block .calc-table tr:nth-child(odd) {
			background-color: #eee;
		}
  	</style>
  	<div class="calc-block">
    	<div class="calc-data">
      		<p class="title">Введите название Вашего населенного пункта:</p>
      		<input name="np" id="np" class="large-field">
      		<p style="font-size:10px;margin-top:3px;color:#ff5555;">Окончательную стоимость уточняйте у оператора</p>	
      		<script type="text/javascript">
	            $(document).ready(function() {
	            	$("#np").autocomplete({
	                	source: function( request, response ) {
	                  		$.ajax({
	                    		url: "index.php?route=checkout/payment_address/address",
	                    		dataType: "json",
	                    		data: {
	                      			q: request.term,
	                      			thing: 'np'
	                    		},
	                    		success: function( data ) {
	                    			$('#calcResponse').html('');
	                      			//console.log(data);
	                      			// Handle 'no match' indicated by [ "" ] response
	                      			response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
	                    		}
	                  		});
	                	},
	                	minLength: 2,
	                	select: function( event, ui ) {
	                		console.log(event);
	                		console.log(ui);
	                		//log( "Selected: " + ui.item.label );
							$("#calcResponse").LoadingOverlay("show");
	                		$.ajax({
	                    		url: "index.php?route=checkout/payment_address/addresscalc",
	                    		dataType: "html",
	                    		data: {
	                      			q: ui.item.value
	                    		},
	                    		success: function( data ) {
	                      			$('#calcResponse').html(data);
	                    		}
							})
								.always(function() {
									$("#calcResponse").LoadingOverlay("hide");
								});
	                	}
	              	});
	            });
	          </script>
      	</div>
      	<div class="calc-info" id="calcResponse">

      	</div>
  	</div>
  	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>