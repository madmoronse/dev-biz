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

  	<link rel="stylesheet" href="/catalog/view/theme/default2/stylesheet/about.css">

	<div class="about-page">
		<div class="txt_area">
			<div class="descr" style="padding-top:20px;padding-left:20px;">
				<p style="text-align:left;margin-bottom: 0px;">При заказе товара наложенным платежом курьерской службой СДЭК, в одном заказе может быть собрано не более 4-ёх товаров.</p> 
				<p style="text-align:left;margin-bottom: 0px;">Если вы хотите приобрести больше 4-ёх товаров, то  стоимость доставки будет выше.</p>
				<p style="text-align:left;margin-bottom: 0px;">Точную стоимость доставки товара вы можете узнать у менеджеров Outmaxshop.ru</p>
			</div>
		</div>

	</div>



    <div class="buttons">
        <div class="right"><a href="http://bizoutmax.ru/" class="button">На главную</a></div>
    </div>
  	<?php echo $content_bottom; ?>
</div>


<?php echo $footer; ?>