<?php
class ControllerProductGenerateXlsPricelistOpt extends Controller {

    public function index() {
	
		<script type="text/javascript">
		$(document).ready(function() {
			function gen_price() { 
				$('#xls_pricelist').html('<a class="top" ><img src="view/image/loading.gif" /></a>');
				$.ajax({
					url: '<?php echo HTTP_CATALOG; ?>index.php?route=product/xls_pricelist',
					type: 'post',
					data: 'action=generate',
					dataType: 'json',
					success: function(json) {
						
						if (json['redirect']) {
							location = json['redirect'];
						}
						
						if (json['error']) {
							if (json['error']['warning']) {
								$('#xls_pricelist').html('<a class="top" style="color:red;" >' + json['error']['warning'] + '</a>');
								
								$('html, body').animate({ scrollTop: 0 }, 'slow');
							}
						}	 
									
						if (json['success']) {
							$('#xls_pricelist').html('<a class="top" style="color:green;"><?php echo $text_xls_success; ?></a>');
						
							$('html, body').animate({ scrollTop: 0 }, 'slow'); 
						}	
					}
				});
				return false;
			};
			</script>
		
    }
	
	$this->response->setOutput($this->render());
}
?>

