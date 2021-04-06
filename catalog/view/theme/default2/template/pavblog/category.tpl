<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> 
    <?php if ($breadcrumb['text'] == "Новости") {$show_news = 0; } else {$show_news = 1; } } ?>
  </div>
  
  
  <div class="pav-category">
	<h1 class="newslist-title"><?php echo $heading_title; ?></h1>
		<?php /*if( !empty($children) ) { ?>
		<div class="pav-children clearfix">
			<!--<h3><?php //echo $this->language->get('text_children');?></h3>-->
			<div class="children-wrap">
				
				<?php 
				$cols = (int)$config->get('children_columns');
				foreach( $children as $key => $sub )  { $key = $key + 1;?>
					<div class="pavcol<?php echo $cols;?>">
						<div class="children-inner">
							<?php if( $sub['thumb'] ) { ?>								
									<a href="<?php echo $sub['link']; ?>" title="<?php echo $sub['title']; ?>">	
										<img src="<?php echo $sub['thumb'];?>"/>
										<div class="thumbtext">												
											<label><?php echo $sub['title']; ?></label>
										</div>										
									</a> 
								
							<?php } ?>
							
							
						</div>
					</div>
					<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
						<div class="clearfix"></div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php }*/ ?>
		<div class="newslist">
			<?php //if ($show_news == 1 ) { //прячем новости в категориях
				if( count($leading_blogs) ) { ?>
					<?php foreach( $leading_blogs as $key => $blog ) { ?>
						<?php require( '_item.tpl' ); ?>
					<?php } ?>
				<?php } ?>

				<?php
				if ( count($secondary_blogs) ) { ?>
					<?php foreach( $secondary_blogs as $key => $blog ) { ?>
						<?php require( '_item.tpl' ); ?>
					<?php } ?>
				<?php }
			//} // Закончили прятать новости в категориях ?>
		</div>
		<?php if( $total ) { ?>	
			<div class="pav-pagination pagination"><?php echo $pagination;?></div>
		<?php } ?>
  </div>

 <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>