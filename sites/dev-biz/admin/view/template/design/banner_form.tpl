<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
        <table id="images" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_title; ?></td>
              <td class="left"><?php echo $entry_link; ?></td>
              <td class="left"><?php echo $entry_image; ?></td>
              <!-- By Neos - Video Upload - Start -->
              <td class="left"><?php echo $entry_video; ?></td>
              <!-- By Neos - Video Upload - End -->
              <td></td>
            </tr>
          </thead>
          <?php $image_row = 0; ?>
          <?php foreach ($banner_images as $banner_image) { ?>
          <tbody class="MoveableRow" id="image-row<?php echo $image_row; ?>">
            <tr>
              <td class="left">
                <input type="hidden" name="banner_image[<?php echo $image_row; ?>][banner_image_id]" value="<?php echo $banner_image['banner_image_id']?>" />
                <p>Переходов: <?php echo $banner_image['follows'] ?></p> 
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['title'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_banner_image[$image_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_banner_image[$image_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
              <td class="left"><input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" /></td>
              <td class="left"><div class="image"><img src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                  <input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="image<?php echo $image_row; ?>"  />
                  <br />
                  <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
              <!-- By Neos - Video Upload - Start -->
              <td class="left">
                  <input type="file" class="banner_video_upload" accept="video/mp4" />
                  <input type="hidden" class="banner_video_source" name="banner_image[<?php echo $image_row; ?>][video]" value="<?php echo $banner_image['video']; ?>" id="video<?php echo $image_row; ?>" />
                  <br />
                  <a onclick="deleteVideo(event);" href="<?php echo $banner_image['video']; ?>"><?php echo $text_clear; if ($banner_image['video']) echo " ({$banner_image['video']})"?></a>
              </td>
              <!-- By Neos - Video Upload - End -->
              <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a><a class="down_button button">down</a><a class="up_button button">up</a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_banner; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var loadInterval,
    addDots = false,
    simpleUploadOptions = {
  "uploadUrl": 'index.php?route=tool/upload/uploadVideo&token=<?php echo $token; ?>&force_upload=1',
  "beforeUpload": function(data, input) {
    var container = input.parent(); 
    $('.banner_video_upload').prop('disabled', true);
    container.append('<p class="autoload-info">Идет загрузка...</p>');
    loadInterval = setInterval(function() {
      var text = container.find('.autoload-info').text();
      var length = (text.match(/\./g) || []).length;
      if (length > 0 && !addDots) {
        container.find('.autoload-info').text(text.replace(/\.$/, ''));
      } else {
        addDots = true;
        if (length >= 2) addDots = false;
        container.find('.autoload-info').text(text + '.');
      }
    }, 300);
  },
  "afterUpload": function(data, input) {
    alert('Файл успешно загружен');
    clearInterval(loadInterval);
    $('.banner_video_upload').prop('disabled', false);
    var container = input.parent(); 
    container.find('.autoload-info').remove();
    container.find('.banner_video_source').val(data.video);
    container.find('a').attr('href', data.video);
    var text = container.find('a').text();
    container.find('a').text(text + ' (' + data.video + ')');
  },
  "errorUpload": function(message, input) {
    alert(message);
    var container = input.parent();
    container.find('.autoload-info').remove();
    $('.banner_video_upload').prop('disabled', false);
  },
  "uploadKey": "video"
};

var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '<tr>';
    html += '<td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '</td>';	
	html += '<td class="left"><input type="text" name="banner_image[' + image_row + '][link]" value="" /></td>';	
	html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
  html += '<td class="left"><input type="file" class="banner_video_upload" accept="video/mp4" /><input type="hidden" class="banner_video_source" name="banner_image['+ image_row  + '][video]" id="video'+ image_row  + '" /><br /><a onclick="deleteVideo(event);"><?php echo $text_clear; ?></a></td>';
  html += '<td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a><a class="down_button button">down</a><a class="up_button button">up</a></td>';
	html += '</tr>';
	html += '</tbody>'; 
	
	$('#images tfoot').before(html);
	$('.banner_video_upload').simpleUpload(simpleUploadOptions);
	image_row++;
}
//--></script>
<script type="text/javascript"><!--
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
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
/** By Neos - Start */
$('.banner_video_upload').simpleUpload(simpleUploadOptions);

function deleteVideo(event) {
  event.preventDefault();
  var source = $(event.target);
  var videoName = source.attr('href');
  var container = source.parent();
  if (!videoName || !videoName.trim()) return false;
  $.ajax({
    "url": 'index.php?route=tool/upload/deleteVideo&token=<?php echo $token; ?>',
    "type": "POST",
    "data": {
      "filename": videoName
    },
    "dataType": "JSON",
    "success": function(data) {
      if (data.error) {
        alert(data.error);
        return false;
      }
      container.find('.banner_video_upload').val(null).trigger('change');
      container.find('.banner_video_source').val(null).trigger('change');
      source.attr('href', '');
      source.text('Очистить');
    },
    "error": function() {
      alert('Не удалось удалить видео');
    }
  });
}

$(document).ready(function() {
  $('.down_button').live('click', function () {
    var rowToMove = $(this).parents('tbody.MoveableRow:first');
    var next = rowToMove.next('tbody.MoveableRow')
    if (next.length == 1) { next.after(rowToMove); }
    });

  $('.up_button').live('click', function () {
    var rowToMove = $(this).parents('tbody.MoveableRow:first');
    var prev = rowToMove.prev('tbody.MoveableRow')
    if (prev.length == 1) { prev.before(rowToMove); }
    });
});
/** By Neos - End */
//--></script> 
<?php echo $footer; ?>
