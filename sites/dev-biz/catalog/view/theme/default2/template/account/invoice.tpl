<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
	<h1 style="overflow: auto;">
		Отчеты по продажам
	</h1>
  <div class="content">
  <?php 
	if ( count($files) > 0 ) {
		foreach($files as $key=>$file){
			echo "<a href='/index.php?route=account/invoice/downloadInvoice/&file=" . $file . "' >" . $file . "</a><br/> ";
		}
	} else {
		echo "<b>Отчётов нет</b>";
	}
		
  ?>
  </div>

  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  
  


 
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>


