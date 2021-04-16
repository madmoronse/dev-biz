<div class="newslist-element">
	<?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
		<a href="<?php echo $blog['link'];?>" class="newslist-element__img"><img src="<?php echo $blog['thumb'];?>" alt="<?php echo $blog['title'];?>"></a>
	<?php } ?>
    <div class="newslist-element__data">
        <a href="<?php echo $blog['link'];?>" class="newslist-element__title-link"><div class="newslist-element__title"><?php echo $blog['title'];?> // <?php echo $blog['category_title'];?></div></a>
        <div class="newslist-metadata">
            <time class="newslist-metadata__time"><?php echo date("d",strtotime($blog['created']));?>.<?php echo date("m",strtotime($blog['created']));?>.<?php echo date("Y",strtotime($blog['created']));?></time>
            <span class="newslist-metadata__viewed"><?php echo $blog['hits'];?></span>
            <div class="newslist-metadata__socials">
            	<script type="text/javascript">(function() {
				  if (window.pluso)if (typeof window.pluso.start == "function") return;
				  if (window.ifpluso==undefined) { window.ifpluso = 1;
				    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
				    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
				    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
				    var h=d[g]('body')[0];
				    h.appendChild(s);
				  }})();</script>
				<div class="pluso" data-background="transparent" data-options="small,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,print"></div>
				<!-- <img src="./img/pluses.png" alt=""> -->
			</div>
        </div>
        <?php if( $config->get('cat_show_description') ) {?>
        	<div class="newslist-text">
	            <?php echo $blog['description'];?>
	        </div>
		<?php } ?>
        <?php if( $config->get('cat_show_readmore') ) { ?>
        	<a href="<?php echo $blog['link'];?>" class="newslist-button">Читать далее</a>
		<?php } ?>
    </div>
</div>

<!--<div class="blog-item">
<?php if( $config->get('cat_show_title') ) { ?>
	<div class="blog-header clearfix">
	<h4 class="blog-title">
		<?php if( $config->get('cat_show_created') ) { ?>
		<span class="created">
			<span class="day"><?php echo date("d",strtotime($blog['created']));?></span>
			<span class="month"><?php echo date("M",strtotime($blog['created']));?></span> /
			<span class="month"><?php echo date("Y",strtotime($blog['created']));?></span>
		</span>
		<?php } ?>
		
		<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a>
	</h4>
	<?php } ?>
	</div>
	<div class="blog-meta">
		<?php if( $config->get('cat_show_author') ) { ?>
		<span class="author"><span><?php echo $this->language->get("text_write_by");?></span> <?php echo $blog['author'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_category') ) { ?>
		<span class="publishin">
			<span><?php echo $this->language->get("text_published_in");?></span>
			<a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a>
		</span>
		<?php } ?>
		
		<?php if( $config->get('cat_show_hits') ) { ?>
		<span class="hits"><span><?php echo $this->language->get("text_hits");?></span> <?php echo $blog['hits'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_comment_counter') ) { ?>
		<span class="comment_count"><span><?php echo $this->language->get("text_comment_count");?></span> <?php echo $blog['comment_count'];?></span>
		<?php } ?>
	</div>
	<div class="blog-body">
		<?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
		<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" align="left"/>
		<?php } ?>
		

		<?php if( $config->get('cat_show_description') ) {?>
		<div class="description">
			<?php echo $blog['description'];?>
		</div>
		<?php } ?>
		<?php if( $config->get('cat_show_readmore') ) { ?>
		<a href="<?php echo $blog['link'];?>" class="readmore"><?php echo $this->language->get('text_readmore');?></a>
		<?php } ?>
	</div>	
</div>-->