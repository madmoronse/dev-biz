<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
  <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
  <script type="text/javascript">
  	$(document).ready(function() {
	  	$(".newsdetails-gallery__previews").slick({
		    infinite: false,
		    variableWidth: true,
		    arrows: false
		});
		$('.newsdetails-gallery__previews').find('.img-wrapper').on("click", function () {
		    var org = $(this).find('img').attr('org');
		    $('.newsdetails-gallery__thumb').find('img').attr('src', org);
		});
	});
  </script>
  <div class="pav-category">
    <!--новости-->

	<!--Текущая версия-->
	
	<div class="newsdetails-title--new"><?php echo $heading_title; ?></div>
	<div class="newsdetails--new">
        <div class="newsdetails-data">
			<div class="newsdetails-metadata--new">
				<time class="newsdetails-metadata__time--new"><?php $cdata = explode("-", $blog['created']); echo $cdata[2] .'.'. $cdata[1] .'.'. $cdata[0];?></time>
				<span class="newsdetails-metadata__viewed--new"><?php echo $blog['hits'];?></span>
			</div>
			<div class="newsdetails-text--new">
				<?php echo $content;?>
				<!-- Nike Air Force 1 легендарный стиль в новом исполнении. В низких универсальных кроссовках Nike Air Force 1 сочетаются классический стиль и новые детали. Верх, выполненный из кожи и текстиля, придает высокую прочность и удобную посадку. Металлическая бирка AF-1 на шнурках символизирует легендарную модель. Немаркированная резиновая подошва обеспечивает лучшее сцепления и прочность, а точка опоры в передней части стопы и в пятке плавную смену направления движения. -->
			</div>
			<?php if(count($blog['images']) > 0): ?>
			
				<div class="newsdetails-gallery--new">
					<?php foreach ($blog['images'] as $key => $image): ?>
						<div class="img-wrapper">
							<img src="/image/data/Blog/pavimages/<?php echo $image['image']; ?>" alt="">
						</div>
			                <?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php if( $blog['video_code'] ) { ?>
				<p style="text-align: center"><?php echo html_entity_decode($blog['video_code'], ENT_QUOTES, 'UTF-8');?></p>
			<?php } ?>

			

			<?php if($blog_products){ ?>
                <div class="box-product blog_products">
                <?php foreach ($blog_products as $product){?>

                    <div class="item" style="">
                        <div id="grid-image-block">
                        <?php if ($product['thumb']) { ?>

                                <div class="image">
                                    <a href="<?php echo $product['href']; ?>">
                                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                                    </a>
                                </div>

                        <?php } ?>
                        </div>
                        <div class="name">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            <div >Артикул: <?php echo $product['sku']; ?></div>
                        </div>

                        <?php if ($product['price']) { ?>
                            <div class="price">
                                <?php if (!$product['special']) { ?>
                                    <?php echo $product['price']; ?>
                                <?php } else { ?>
                                    <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>



                    </div>

                <?php } ?>
                </div>

            <?php } ?>
			
			
			<?php if($blog['item_link']): ?>
				<a href="<?php echo $blog['item_link']; ?>" target="_blank" class="newsdetails-button--new"><p><?php if ($blog['item_button_text']) { echo $blog['item_button_text']; } else echo 'Перейти к товару'; ?></p></a>
			<?php endif;?>

		</div>
	</div>
	
	<!--новости-->
	<?php /*  
	

	<!--Старая версия-->
	<!--
    <div class="newsdetails-title"><?php echo $heading_title; ?> // <?php echo $blog['category_title'];?></div>
    <div class="newsdetails">
        <div class="newsdetails-data">
            <div class="newsdetails-metadata">
                <time class="newsdetails-metadata__time"><?php $cdata = explode("-", $blog['created']); echo $cdata[2] .'.'. $cdata[1] .'.'. $cdata[0];?></time>
                <span class="newsdetails-metadata__viewed"><?php echo $blog['hits'];?></span>
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
					
					<!-- <img src="/catalog/view/theme/default2/image/img/pluses.png" alt=""> -->
		<!--
				</div>
            </div>
            <div class="newsdetails-text">
            	<?php echo $content;?>
                <!-- Nike Air Force 1 легендарный стиль в новом исполнении. В низких универсальных кроссовках Nike Air Force 1 сочетаются классический стиль и новые детали. Верх, выполненный из кожи и текстиля, придает высокую прочность и удобную посадку. Металлическая бирка AF-1 на шнурках символизирует легендарную модель. Немаркированная резиновая подошва обеспечивает лучшее сцепления и прочность, а точка опоры в передней части стопы и в пятке плавную смену направления движения. -->
            </div>

            <!-- <ul class="newsdetails-params">
                <li>Цвета: White/Black-Concord Purple</li>
                <li>Модель#: 311046-106</li>
                <li>Релиз: 1 декабря 2016</li>
                <li>Цена: $160</li>
            </ul> -->
			
		<!--
            <?php if($blog['item_link']): ?>
            	<a href="<?php echo $blog['item_link']; ?>" target="_blank" class="newsdetails-button">Купить</a>
            <?php endif;?>
        </div>
        <?php if(count($blog['images']) > 0): ?>
	        <div class="newsdetails-gallery">
	            <div class="newsdetails-gallery__thumb">
	                <img src="<?php echo $blog['images'][0]['image_org']; ?>" alt="">
	            </div>
	            <div class="newsdetails-gallery__previews">
	            	<?php foreach ($blog['images'] as $key => $image): ?>
	                	<div class="img-wrapper"><img src="<?php echo $image['image_small']; ?>" org="<?php echo $image['image_org']; ?>" alt=""></div>
	                <?php endforeach; ?>
	            </div>
	        </div>
	    <?php endif; ?>
    </div>  -->
    <!--новости-->
</div>


*/ ?>

<?/*?><div class="pav-blog">
	<h1><?php echo $heading_title; ?></h1>
		<?php if( $blog['thumb_large'] ) { ?>
			<div class="image">
				<img src="<?php echo $blog['thumb_large'];?>" title="<?php echo $blog['title'];?>"/>
			</div>
			<?php } ?>
			
		<div class="blog-meta">
			
			<?php if( $config->get('blog_show_author') ) { ?>
			<!--<span class="author"><span><?php //echo $this->language->get("text_write_by");?></span> <?php //echo $blog['author'];?></span>-->
			<?php } ?>
			<?php if( $config->get('blog_show_category') ) { ?>
			<!--<span class="publishin">
				<span><?php //echo $this->language->get("text_published_in");?></span>
				<a href="<?php //echo $blog['category_link'];?>" title="<?php //echo $blog['category_title'];?>"><?php //echo $blog['category_title'];?></a>
			</span>-->
			<?php } ?>
			<?php if( $config->get('blog_show_created') ) { ?>
			<span class="created"><span><?php echo $this->language->get("text_created_date");?> <?php echo $blog['created'];?></span></span>
			<?php } ?>
			<?php if( $config->get('blog_show_hits') ) { ?>
			<span class="hits"><span><?php echo $this->language->get("text_hits");?></span> <?php echo $blog['hits'];?></span>
			<?php } ?>
			<?php if( $config->get('blog_show_comment_counter') ) { ?>
			<span class="comment_count"><span><?php echo $this->language->get("text_comment_count");?></span> <?php echo $blog['comment_count'];?></span>
			<?php } ?>
		</div>
		
		 <div class="description clearfix"><?php echo $description;?></div>
		 <div class="blog-content clearfix">
				<div class="content-wrap clearfix">
				<?php echo $content;?>
				</div>
			<?php if( $blog['video_code'] ) { ?>
			<div class="pav-video clearfix"><?php echo html_entity_decode($blog['video_code'], ENT_QUOTES, 'UTF-8');?></div>
			<?php } ?>
		
		
		
		 </div>
		 
		
		 <?php if( !empty($tags) ) { ?>
		 <div class="blog-tags">
			<b><?php echo $this->language->get('text_tags');?></b>
			<?php foreach( $tags as $tag => $tagLink ) { ?>
				<a href="<?php echo $tagLink; ?>" title="<?php echo $tag; ?>"><?php echo $tag; ?></a>
			<?php } ?>
		 </div>
		 <?php } ?>
		 <div class="blog-social clearfix">
				
				<div class="social-wrap">
					<div class="social-heading"><b><?php echo $this->language->get('text_like_this');?> </b> </div>
					
					<div class="share42init" style="display:inline-block;"></div><script type="text/javascript" src="catalog/view/theme/default2/assets/share42/share42/share42.js"></script>
				</div>	
		 </div>
		 <div class="blog-bottom clearfix">
				<?php if( !empty($samecategory) ) { ?>
				<div class="pavcol2">
					<h4><?php echo $this->language->get('text_in_same_category');?></h4>
					<ul>
						<?php foreach( $samecategory as $item ) { ?>
						<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
				<?php if( !empty($related) ) { ?>
				<div class="pavcol2">
					<h4><?php echo $this->language->get('text_in_related_by_tag');?></h4>
					<ul>
						<?php foreach( $related as $item ) { ?>
						<li><a href="<?php echo $this->url->link('pavblog/blog',"id=".$item['blog_id']);?>"><?php echo $item['title'];?></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
		</div>
		
		 <div class="pav-comment">
			<?php if( $config->get('blog_show_comment_form') ) { ?>
				<?php if( $config->get('comment_engine') == 'diquis' ) { ?>
			    <div id="disqus_thread"></div>
					<script type="text/javascript">
						/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */ /*
						var disqus_shortname = '<?php echo $config->get('diquis_account');?>'; // required: replace example with your forum shortname

						/* * * DON'T EDIT BELOW THIS LINE * * */ /*
						(function() {
							var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
							dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
							(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    
				<?php } elseif( $config->get('comment_engine') == 'facebook' ) { ?>
				<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) {return;}
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $config->get("facebook_appid");?>";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-comments" data-href="<?php $link; ?>" 
							data-num-posts="<?php echo $config->get("comment_limit");?>" data-width="<?php echo $config->get("facebook_width")?>">
					</div>
				<?php }else { ?>
					<?php if( count($comments) ) { ?>
					<h4><?php echo $this->language->get('text_list_comments'); ?></h4>
					<div class="pave-listcomments">
						<?php foreach( $comments as $comment ) {  $default='';?>
						<div class="comment-item clearfix" id="comment<?php echo $comment['comment_id'];?>">
							
							<img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $comment['email'] ) ) ) . "?d=" . urlencode( $default ) . "&s=60" ?>" align="left"/>
							<div class="comment-wrap">
								<div class="comment-meta">
								<span class="comment-created"><?php echo $this->language->get('text_created');?> <span><?php echo $comment['created'];?></span></span>
								<span class="comment-postedby"><?php echo $this->language->get('text_postedby');?> <span><?php echo $comment['user'];?></span></span>
								<span class="comment-link"><a href="<?php echo $link;?>#comment<?php echo $comment['comment_id'];?>"><?php echo $this->language->get('text_comment_link');?></a></span>
								</div>
								<?php echo $comment['comment'];?>
							</div>
						</div>
						<?php } ?>
						<div class="pagination">
							<?php echo $pagination;?>
						</div>
					</div>
					<?php } ?>
					<h4><?php echo $this->language->get("text_leave_a_comment");?></h4>
					<form action="<?php echo $comment_action;?>" method="post" id="comment-form">
						<div class="message" style="display:none"></div>
						<div class="input-group">
							<label for="comment-user"><?php echo $this->language->get('entry_name');?></label>
							<input name="comment[user]" value="" id="comment-user"/>
						</div>
						<!--<div class="input-group">
							<label for="comment-email"><?php //echo $this->language->get('entry_email');?></label>
							<input name="comment[email]" value="" id="comment-email"/>
						</div>-->
						<div class="input-group">
							<label for="comment-comment"><?php echo $this->language->get('entry_comment');?></label>
							<textarea name="comment[comment]"  id="comment-comment"></textarea>
						</div>
						<?php if( $config->get('enable_recaptcha') ) { ?>
						<div class="recaptcha">
							<?php echo $recaptcha; ?>
						</div>
						<?php } ?>
						<input type="hidden" name="comment[blog_id]" value="<?php echo $blog['blog_id']; ?>" />
						<br/>
						<div class="buttons">
							<button class="btn btn-submit" type="submit">
								<span><?php echo $this->language->get('text_submit');?></span>
							</button>
						</div>
					</form>
					<script type="text/javascript">
						$( "#comment-form .message" ).hide();
						$("#comment-form").submit( function(){
							$.ajax( {type: "POST",url:$("#comment-form").attr("action"),data:$("#comment-form").serialize(), dataType: "json",}).done( function( data ){
								if( data.hasError ){
									$( "#comment-form .message" ).html( data.message ).show();	
								}else {
									location.href='<?php echo str_replace("&amp;","&",$link);?>';
								}
							} );
							return false;
						} );
						
					</script>
				<?php } ?>
			<?php } ?>
		 </div>
  </div><?*/?>

  
  <?php echo $content_bottom; ?></div>
</div>
<?php echo $footer; ?>