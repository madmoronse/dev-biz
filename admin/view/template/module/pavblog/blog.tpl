<?php  echo $header;  ?>
	 

  
 <div id="content">
	  <div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	  </div>
	  <?php if ($error_warning) { ?>
	  <div class="warning"><?php 
	  
	  if( is_array($error_warning) ) {
		echo implode("<br>",$error_warning);
	 } else {
		echo $error_warning;
	  }
	  ?>
	  
	  </div>
	  <?php } ?>
		<div class="box">
		   
		   <div class="toolbar"><?php require( dirname(__FILE__).'/toolbar.tpl' ); ?></div>
		  
			<div class="heading">
			  <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
				<div class="buttons">
				  <a onclick="$('#form').submit();" class="button"><?php echo $this->language->get("button_save"); ?></a>
				  <a onclick="__submit('save-edit')" class="button"><?php echo $this->language->get('button_save_edit'); ?></a>
				  <a onclick="__submit('save-new')" class="button"><?php echo $this->language->get('button_save_new'); ?></a>
				  <?php if( $blog['blog_id'] ) { ?>	
					<a  href="<?php echo $action_delete;?>" class="button action-delete"><?php echo $this->language->get("button_delete"); ?></a>	
				  <?php } ?>
				</div>  
			</div>
			
			<div class="content">
				<div class="box-columns">
					
					 
					<form id="form" enctype="multipart/form-data" method="post" action="<?php echo $action;?>">
							<input type="hidden" name="action_mode" id="action_mode" value=""/>
							<div class="htabs pavhtabs">
								<a href="#tab-general"><?php echo $this->language->get("text_general"); ?></a>
								<a href="#tab-gallery"><?php echo $this->language->get("text_gallery"); ?></a>
								<a href="#tab-meta"><?php echo $this->language->get("text_meta"); ?></a>
							</div>
							
							<div id="tab-general">
									<input type="hidden" name="pavblog_blog[blog_id]" value="<?php echo $blog['blog_id'];?>"/>
									<div>
										 <table class="form">
											<tr>
												<td><?php echo $this->language->get('entry_category_id');?></td>
												<td><?php echo $menus;?></td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_status');?></td>
												<td>
													<select name="pavblog_blog[status]">
													<?php foreach( $yesno as $k=>$v ) { ?>
														<option value="<?php echo $k;?>" <?php if( $k==$blog['status'] ) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_featured');?></td>
												<td>
													<select name="pavblog_blog[featured]">
													<?php foreach( $yesno as $k=>$v ) { ?>
														<option value="<?php echo $k;?>" <?php if( $k==$blog['featured'] ) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_hits');?></td>
												<td>
													<input type="text" name="pavblog_blog[hits]" value="<?php echo $blog['hits'];?>">
												</td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_tags');?></td>
												<td>
													<input type="text" name="pavblog_blog[tags]" value="<?php echo $blog['tags'];?>" size="150">
													<br><i><?php echo $this->language->get('text_explain_tags');?></i>
												</td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_created');?></td>
												<td>
													<input type="text" name="pavblog_blog[created]" value="<?php echo $blog['created'];?>" class="date">
												</td>
											</tr>
											<tr>
												<td><?php echo $this->language->get('entry_creator');?></td>
												<td>
													<select name="pavblog_blog[user_id]">
														<?php foreach( $users as $user ) { ?> 
														<option value="<?php echo $user['user_id'];?>" <?php if( $user['user_id'] == $blog['user_id'] ){ ?>selected="selected"<?php } ?>><?php echo $user['username'];?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr>
											<td><?php echo $this->language->get('entry_keyword');?></td>
											<td><input type="text" name="pavblog_blog[keyword]" size="100" value="<?php echo $blog['keyword'];?>"/></td>
										</tr>
									  </table>
									</div>
									
									<div id="language-blog" class="htabs pavtabs">
										<?php foreach ($languages as $language) { ?>
										<a href="#tab-language-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
										<?php } ?>
									  </div>
									  <?php foreach ($languages as $language) { ?>
									  <div id="tab-language-<?php echo $language['language_id']; ?>">
										<table class="form">
										  <tr>
											<td><?php echo $this->language->get('entry_title');?></td>
											<td><input name="pavblog_blog_description[<?php echo $language['language_id'];?>][title]" size="120" value="<?php echo $pavblog_blog_descriptions[$language['language_id']]['title'];?>"/></td>
										  </tr>
										   <tr>
											<td><?php echo $this->language->get('entry_description');?></td>
											<td>
												<textarea name="pavblog_blog_description[<?php echo $language['language_id'];?>][description]"  rows="6" cols="10"><?php echo $pavblog_blog_descriptions[$language['language_id']]['description'];?></textarea>
											</td>
										  </tr>
										   </tr>
										   <tr>
											<td><?php echo $this->language->get('entry_content');?></td>
											<td>
												<textarea name="pavblog_blog_description[<?php echo $language['language_id'];?>][content]"  rows="6" cols="10"><?php echo $pavblog_blog_descriptions[$language['language_id']]['content'];?></textarea>
											</td>
										  </tr>
										  <tr>
											<td>Ссылка на товар (для кнопки "Купить")</td>
											<td><input name="pavblog_blog_description[<?php echo $language['language_id'];?>][item_link]" size="120" value="<?php echo $pavblog_blog_descriptions[$language['language_id']]['item_link'];?>"/></td>
										  </tr>
										  <tr>
											<td>Текст на кнопке</td>
											<td>
                                                <input name="pavblog_blog_description[<?php echo $language['language_id'];?>][item_button_text]" size="120" value="<?php echo $pavblog_blog_descriptions[$language['language_id']]['item_button_text'];?>"/></td>
										  </tr>
                                          <tr>
                                            <td>Добавить товары</td>
                                            <td><textarea name="pavblog_blog_description[<?php echo $language['language_id'];?>][blog_products]" onkeyup="this.value = this.value.replace (/[^0-9\n]/, '')" autocomplete="nope" cols="40" rows="30"><?php echo $pavblog_blog_descriptions[$language['language_id']]['blog_products'];; ?></textarea></td>
										</table>
									  </div>
									  <?php } ?>
									 
							</div>
							<div id="tab-gallery">
								<div>
									 <table class="form">
									
										<tr>
											<td><?php echo $this->language->get('entry_image');?></td>
										
											<td class="left"><div class="image"><img src="<?php echo $blog['thumb']; ?>" alt="" id="blog-thumb" />
										  <input type="hidden" name="pavblog_blog[image]" value="<?php echo $blog['image']; ?>" id="blog-image"  />
										  <br />
										  <a onclick="image_upload('blog-image', 'blog-thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#blog-thumb').attr('src', '<?php echo $no_image; ?>'); $('#blog-image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
				  	
										 </tr>

										<?php if($_REQUEST["id"]): ?>
										<tr>
										 	<td>Галерея</td>
										 	<td>
										 		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
										 		<link href="/admin/view/stylesheet/fileinput.css" media="all" rel="stylesheet" type="text/css" />
										 		<style type="text/css">
										 			.clearfix { display: block; }
										 			.kv-file-zoom, .file-drag-handle { display: none; }
										 		</style>
										 		<script src="/admin/view/javascript/fileinput.js"></script>
										 		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
										 		<input id="pav_images" name="pav_images[]" type="file" multiple class="file-loading">
												<script>
												var $input = $("#pav_images");
												$input.fileinput({
												    uploadUrl: "/upload.php?act=add&blog_id=<?php echo $blog['blog_id']?>", // server upload action
													uploadAsync: true,
												    minFileCount: 1,
												    maxFileCount: 10,
												    showUpload: false, // hide upload button
    												showRemove: false, // hide remove button
												    overwriteInitial: false,
												    <?php if(count($blog['images']) > 0): ?>
													    initialPreview: [
													    	<?php foreach ($blog['images'] as $key => $image): ?>
													        	"http://<?php echo $_SERVER['HTTP_HOST']; ?>/image/data/Blog/pavimages/<?php echo $image['image']; ?>",
													        <?php endforeach; ?>
													    ],
													    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
    													initialPreviewFileType: 'image', // image is the default and can be overridden in config below
    													initialPreviewConfig: [
    														<?php foreach ($blog['images'] as $key => $image): 
    															$size = filesize($_SERVER["DOCUMENT_ROOT"]."/image/data/Blog/pavimages/".$image['image']);
    															?>
													        	{
													        		caption: "<?php echo $image['image']; ?>", 
													        		size: <?php echo $size; ?>, 
													        		width: "120px", 
													        		url: "/upload.php?act=delete&image_id=<?php echo $image['id']?>", 
													        		key: <?php echo ($key+1); ?>
													        	},
													      	<?php endforeach; ?>
													    ]
													<?php endif; ?>
												}).on("filebatchselected", function(event, files) {
												    // trigger upload method immediately after files are selected
												    $input.fileinput("upload");
												}).on('fileuploaded', function(event, data, previewId, index) {
													//  оставим это на будущее	
													//console.log(event, data, previewId, index);
												});
												</script>
										 	</td>
										</tr>
										<? endif; ?>
										 
										<tr>
											<td><?php echo $this->language->get('entry_video_code');?></td>
											<td><textarea  name="pavblog_blog[video_code]" rows="6" cols="40"><?php echo $blog['video_code'];?></textarea></td>
										</tr>
			
			
								  </table>
								</div>
							</div>
							<div id="tab-meta">
								<div class="pav-content" style="width:100%">
									<table class="form">
										
										<tr>
											<td><?php echo $this->language->get('entry_meta_title');?></td>
											<td><input type="text" name="pavblog_blog[meta_title]" value="<?php echo $blog['meta_title'];?>"/></td>
										</tr>
										<tr>
											<td><?php echo $this->language->get('entry_meta_keyword');?></td>
											<td><textarea  name="pavblog_blog[meta_keyword]"><?php echo $blog['meta_keyword'];?></textarea></td>
										</tr>
										<tr>
											<td><?php echo $this->language->get('entry_meta_description');?></td>
											<td><textarea  name="pavblog_blog[meta_description]" rows="6" cols="40"><?php echo $blog['meta_description'];?></textarea></td>
										</tr>
									
										
									</table>
								</div>
							</div>
					</form>
				 
				</div>
				
				
			</div>
		</div>	
		
		
 </div>
 <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
 <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
 <script type="text/javascript">
 $('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.pavhtabs a').tabs();
	$('.pavtabs a').tabs();
	function __submit( val ){
		$("#action_mode").val( val );
		$("#form").submit();
	}
	$(".action-delete").click( function(){ 
		return confirm( "<?php echo $this->language->get("text_confirm_delete");?>" );
	} );
  <?php foreach ($languages as $language) { ?>
CKEDITOR.replace('pavblog_blog_description[<?php echo $language['language_id']; ?>][description]', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
  <?php foreach ($languages as $language) { ?>
CKEDITOR.replace('pavblog_blog_description[<?php echo $language['language_id']; ?>][content]', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>

function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
 </script>
<?php echo $footer; ?>
