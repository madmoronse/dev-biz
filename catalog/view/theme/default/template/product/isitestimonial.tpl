<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<?php echo '<script type="text/javascript" src="'. $this->config->get('config_url') . 'catalog/view/javascript/jquery/ajaxupload/ajaxupload.3.5.js"></script>'; ?>
<script type="text/javascript" >
	$(function(){
		var btnUpload=$('#upload');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: 'index.php?route=product/testimonial/add_image',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
					status.text('Загружать можно только изображения в формате JPG, PNG или GIF');
					return false;
				}
				status.text('Загружается...');
			},
			onComplete: function(file, response){
				//On completion clear the status
				status.text('');
				//Add uploaded file to list
				filename=response;
				if(response!="error"){
					
					$('#upload').text('Прикрепить еще фото');
					if ($('input[name=\'image1\']').attr('value') =='') {
						$('input[name=\'image1\']').val("./image/testimonials/" +filename);
						$('<li style="border:1px solid #333;"></li>').appendTo('#files').html('<img src="./image/testimonials/'+filename+'" alt="" /><br /><br /><span class="img1" id="rem_img">удалить фото</span>');
					} 
					else if ($('input[name=\'image2\']').attr('value') =='') {
						$('input[name=\'image2\']').val("./image/testimonials/" +filename);
						$('<li style="border:1px solid #333;"></li>').appendTo('#files').html('<img src="./image/testimonials/'+filename+'" alt="" /><br /><br /><span class="img2" id="rem_img">удалить фото</span>');
					}
					else if ($('input[name=\'image3\']').attr('value') =='') {
						$('input[name=\'image3\']').val("./image/testimonials/" +filename);
						$('<li style="border:1px solid #333;"></li>').appendTo('#files').html('<img src="./image/testimonials/'+filename+'" alt="" /><br /><br /><span class="img3" id="rem_img">удалить фото</span>');						
					} 
					if (($('input[name=\'image1\']').attr('value') !='') && ($('input[name=\'image2\']').attr('value') !='') && ($('input[name=\'image3\']').attr('value') !='')) {
						
						$('#upload').hide();
						$('#status').text('Можно прикрепить не более 3-х фото');
					}
					
				} else{
					$('<li></li>').appendTo('#files').text(file).addClass('error');
				}
			}
		});
		
	});
</script>

<script type="text/javascript" >
     <!--
    $('#rem_img').live('click', function() {
        var sr_im = $(this).parent().find('img').attr("src");
		$.ajax({
            url: 'index.php?route=product/testimonial/delete_image',
            type: 'post',
            data: {
                src_image : sr_im
            },	
        });
		$('#status').text('');
		$('#upload').show();
		$(this).parent().remove();
		if ($(this).hasClass("img1")){
			if ($('input[name=\'image1\']').attr('value') !='') {
				$('input[name=\'image1\']').val('');
			} 
		}
		if ($(this).hasClass("img2")){
			if ($('input[name=\'image2\']').attr('value') !='') {
				$('input[name=\'image2\']').val('');
			} 
		}
		if ($(this).hasClass("img3")){
			if ($('input[name=\'image3\']').attr('value') !='') {
				$('input[name=\'image3\']').val('');				
			} 
		}
		
		
    });
     //-->
 </script>

<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title ?></h1>
    </div>
  </div>
  <div class="middle">
  	
  	<div class="content" style="margin: 20px 0;padding:20px;"><p><?php echo $text_conditions ?></p></div>
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="testimonial">
	<div class="content">
        <table width="100%" style="padding:20px">
          <tr>
            <td><?php echo $entry_name ?><br />
              <input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?>
		</td>
          </tr>
		  <!--<tr>
            <td><?php //echo $entry_title ?><br />
              <!--<input type="text" name="title" value="<?php //echo $title; ?>" size = 90 /> 
              <?php //if ($error_title) { ?>
              <span class="error"><?php //echo $error_title; ?></span>
              <?php //} ?></td>
          </tr> -->
		  <tr>
             <td><?php echo $entry_city ?><br />
			<input type="text" name="city" value="<?php echo $city; ?>" />
			</td>
          </tr>
          <tr>
            <td>
		  <?php echo $entry_email ?><br />
              <input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><br><?php echo $entry_rating; ?> &nbsp;&nbsp;&nbsp; <span><?php echo $entry_bad; ?></span>&nbsp;
        		<input type="radio" name="rating" value="1" style="margin: 0;" <?php if ( $rating == 1 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="2" style="margin: 0;" <?php if ( $rating == 2 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="3" style="margin: 0;" <?php if ( $rating == 3 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="4" style="margin: 0;" <?php if ( $rating == 4 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="5" style="margin: 0;" <?php if ( $rating == 5 ) echo 'checked="checked"';?> />
        		&nbsp; <span><?php echo $entry_good; ?></span><br /><br>

          	</td>
          </tr>
          <tr>
            <td><?php echo $entry_enquiry ?><span class="required">*</span><br />
              <textarea name="description" style="width: 99%;" rows="10"><?php echo $description; ?></textarea><br />

              <?php if ($error_enquiry) { ?>
              <span class="error"><?php echo $error_enquiry; ?></span>
              <?php } ?></td>
          </tr>
		  
		  <tr>
				<td> 
					<div id="upload" ><span>Прикрепить фотографию<span></div><span id="status" ></span>
						<ul id="files" ></ul>
					</div>
					<input type="text" name="image1" value="<?php echo $image1; ?>" style="display:none"/>
					<input type="text" name="image2" value="<?php echo $image2; ?>" style="display:none"/>
					<input type="text" name="image3" value="<?php echo $image3; ?>" style="display:none"/>
				</td>
		  </tr>
		  
		  	<!--<tr>
				<td> <?php echo $entry_image1; ?> <br /> 
				<input type="text" name="image1" value="<?php echo $image1; ?>" style="width: 306px;"/></td>
		    </tr>
			<tr>
				<td> <?php echo $entry_image2; ?> <br /> 
				<input type="text" name="image2" value="<?php echo $image2; ?>" style="width: 306px;"/></td>
		    </tr>
			<tr>
				<td> <?php echo $entry_image3; ?> <br /> 
				<input type="text" name="image3" value="<?php echo $image3; ?>" style="width: 306px;"/></td>
		    </tr>			
          -->
          
          <tr>
            <td>
              <?php if ($error_captcha) { ?>
              <span class="error"><?php echo $error_captcha; ?></span>
              <?php } ?>
              
              <img src="index.php?route=information/contact/captcha" /> <br>
		<?php echo $entry_captcha; ?><span class="required">*</span> <br>

              <input type="text" name="captcha" value="<?php echo $captcha; ?>" /><br>
		</td>
          </tr>
        </table>
	  </div>
      <div class="buttons">
        <table width=100%>
          <tr>
            <td width=50%><a  onclick="$('#testimonial').submit();" class="button"><span><?php echo $button_send; ?></span></a></td>
		<td align="right"><a class="button" href="<?php echo $showall_url;?>"><span><?php echo $show_all; ?></span></a>
		</td>
          </tr>
        </table>

      </div>
    </form>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 