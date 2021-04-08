<?php $customer_group_id = $this->customer->getCustomerGroupId();?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
    <div id="content"><?php echo $content_top; ?>
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>

    <style type="text/css">
	.cdek-info-order {
		padding: 30px;
		max-width: 900px;
		margin: 0 auto;
		border: 1px solid #c9c9c9;
		border-radius: 5px;
	}
	.cdek-order-description {
		font-weight: 600;
	}
	.cdek-order-cityname {
		
	}
	table {
		width: 100%;
		border-collapse: collapse;
	}
	table tr {
		width: 100%;
	}
	table tr td {
		border-bottom: 1px solid black;
		width: 33%;
		padding: 7px;
	}
	table thead tr td {
		border: 1px solid #c9c9c9;
		font-weight: 600;
	}
	table thead {
		background-color: #fafafa;
	}
</style>


<div class="cdek-info-order">
	<table>
		<thead>
			<tr>
				<td>Город</td>
				<td>Дата</td>
				<td>Статус</td>
			</tr>
		</thead>
		<tbody>

	<?php foreach($track_info as $key => $value) {?>
			<tr>
				<td class="cdek-order-cityname"><?php echo $value['CityName']; ?></td>
				<td class="cdek-order-date"><?php echo substr($value['Date'], 0, -15); ?></td>
				<td class="cdek-order-description"><?php echo $value['Description']; ?></td>
			</tr>
	<?php }?>
		</tbody>
	</table>
</div>

<h1>dasdasd</h1>

           <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>