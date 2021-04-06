<?php 
	$customer_group_id = $this->customer->getCustomerGroupId();
	if ($customer_group_id != 3 and $customer_group_id != 4) { ?>

<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content" style="padding: 20px;"><?php echo $message; ?></div>
</div>


<?php } ?>