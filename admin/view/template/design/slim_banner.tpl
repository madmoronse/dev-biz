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
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tr>
                <td><?php echo $entry_link; ?></td>
                <td><input type="text" name="slim_banner_url" style="width:60%" value="<?php echo $slim_banner_url; ?>"/></td>
            </tr>
            <tr>
                <td><?php echo $entry_header; ?></td>
                <td><input type="text" name="slim_banner_timer_header" style="width:60%" value="<?php echo $slim_banner_timer_header; ?>"/></td>
            </tr>
            <tr>
                <td><?php echo $entry_timer; ?></td>
                <td>
                    <input type="date" name="slim_banner_timer_date" value="<?php echo $slim_banner_timer_date; ?>"/>
                    <input type="time" name="slim_banner_timer_time" value="<?php echo $slim_banner_timer_time; ?>"/>
                </td>
            </tr>
            <tr>
                <td><?php echo $entry_image; ?></td>
                <td>
                    <div class="image">
                        <img src="<?php echo $slim_banner_thumb; ?>" alt=""  id="thumb_slim_banner"/>
                        <input type="hidden" name="slim_banner_image" value="<?php echo $slim_banner_image; ?>"  id="slim_banner_image"/>
                    <br />
                    <a onclick="image_upload('slim_banner_image', 'thumb_slim_banner');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_slim_banner').attr('src', '<?php echo $no_image; ?>'); $('#slim_banner_image').attr('value', '');"><?php echo $text_clear; ?></a>
                    </div></td>
            </tr>
            <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="slim_banner_status">
                        <?php if ($slim_banner_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_hits; ?></td>
                <td><?php echo $slim_banner_hits; ?><input type="hidden" name="slim_banner_hits" id="slim_banner_hits" style="width:60%" value="<?php echo $slim_banner_hits; ?>"/></td>
            </tr>
        </table>


      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
    var oldUrl = $('#' + field).attr('value');
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
    $('#slim_banner_hits').val('0');
}
});
}
},
bgiframe: false,
width: 1000,
height: 400,
resizable: false,
modal: false
});
};
//--></script>

<?php echo $footer; ?>
