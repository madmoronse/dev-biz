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
                <td><?php echo $entry_block_name; ?></td>
                <td><input type="text" name="newsblock_module[1][block_name]" style="width:30%" value="<?php echo $newsblock_module[1]['block_name']; ?>"/></td>
            </tr>
            <tr>
                <td><?php echo $entry_blog_name; ?></td>
                <td><input type="text" name="blog_name" style="width:30%" value=""/></td>

            </tr>
            <tr>
                <td><?php echo $entry_added_blogs; ?></td>
                <td>
                    <div id="newsblock" class="scrollbox" style="width: 650px;">
                    <?php $class = 'odd'; ?>
                    <?php if(isset($newsblock_module[1]['news'])){
                    foreach ($newsblock_module[1]['news'] as $currentNews) { ?>
                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                        <div id="newsblock<?php echo $currentNews['blog_description'][1]['blog_id']; ?>" class="<?php echo $class; ?>"><?php echo $currentNews['blog_description'][1]['title']; ?> <?php echo ' (blog_id: ' . $currentNews['blog_description'][1]['blog_id'] . ') '; ?><img src="view/image/delete.png" alt="" />
                            <input type="hidden" value="<?php echo $currentNews['blog_description'][1]['blog_id']; ?>" />

                        </div>
                    <?php } ?>

                    <?php } ?>
                </div><br />

                <input type="hidden" style="width:95%" name="newsblock_module[1][selectedNews]" value="<?php echo $newsblock_module[1]['selectedNews']; ?>" /></td>
            </tr>
            <tr>
                <td><?php echo $entry_layout; ?></td>
                <td><select name="newsblock_module[1][layout_id]">
                        <?php foreach ($layouts as $layout) { ?>
                            <?php if ($layout['layout_id'] == $newsblock_module[1]['layout_id']) { ?>
                                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_position; ?></td>
                <td><select name="newsblock_module[1][position]>">
                        <?php if ($newsblock_module[1]['position'] == 'content_top') { ?>
                            <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                        <?php } else { ?>
                            <option value="content_top"><?php echo $text_content_top; ?></option>
                        <?php } ?>
                        <?php if ($newsblock_module[1]['position'] == 'content_bottom') { ?>
                            <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                        <?php } else { ?>
                            <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                        <?php } ?>
                        <?php if ($newsblock_module[1]['position'] == 'column_left') { ?>
                            <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                        <?php } else { ?>
                            <option value="column_left"><?php echo $text_column_left; ?></option>
                        <?php } ?>
                        <?php if ($newsblock_module[1]['position'] == 'column_right') { ?>
                            <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                        <?php } else { ?>
                            <option value="column_right"><?php echo $text_column_right; ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="newsblock_module[1][status]">
                        <?php if ($newsblock_module[1]['status']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_sort_order; ?></td>
                <td><input type="text" name="newsblock_module[1][sort_order]" value="<?php echo $newsblock_module[1]['sort_order']; ?>" size="3" /></td>
            </tr>
        </table>


      </form>
    </div>
  </div>
</div>


<script type="text/javascript"><!--
    $('input[name=\'blog_name\']').autocomplete({
        delay: 500,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=module/newsblock/getblogs&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.blog_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('#newsblock' + ui.item.value).remove();

            $('#newsblock').append('<div id="news' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" value="' + ui.item.value + '" /></div>');

            $('#newsblock div:odd').attr('class', 'odd');
            $('#newsblock div:even').attr('class', 'even');

            data = $.map($('#newsblock input'), function(element){
                return $(element).attr('value');
            });

            $('input[name=\'newsblock_module[1][selectedNews]\']').attr('value', data.join());

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });

    $('#newsblock div img').live('click', function() {
        $(this).parent().remove();

        $('#newsblock div:odd').attr('class', 'odd');
        $('#newsblock div:even').attr('class', 'even');

        data = $.map($('#newsblock input'), function(element){
            return $(element).attr('value');
        });

        $('input[name=\'newsblock_module[1][selectedNews]\']').attr('value', data.join());
    });
    //--></script>


<?php echo $footer; ?>
