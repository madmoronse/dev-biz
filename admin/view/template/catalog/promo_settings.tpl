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

            <h1><?php echo $entry_common_settings; ?></h1>
            <table class="form">

                <tr>
                    <td>
                        <b><?php echo $entry_status; ?></b>
                    </td>
                    <td>
                        <select name="promo_banner_status">
                            <?php if ($promo_banner_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo $entry_promo_banner; ?></td>
                    <td>
                        <div class="image">
                            <img src="<?php echo $promo_banner_thumb; ?>" alt=""  id="thumb_promo_banner"/>
                            <input type="hidden" name="promo_banner" value="<?php echo $promo_banner; ?>"  id="promo_banner"/>
                            <br />
                            <a onclick="image_upload('promo_banner', 'thumb_promo_banner');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_promo_banner').attr('src', '<?php echo $no_image; ?>'); $('#promo_banner').attr('value', '');"><?php echo $text_clear; ?></a>
                        </div></td>
                </tr>


                <tr>
                    <td>
                        <b><?php echo $entry_promo_banner_url; ?></b>
                    </td>
                    <td>
                        <input type="text" name="promo_banner_url" value="<?php echo $promo_banner_url; ?>"/>
                    </td>
                </tr>

            </table>

            <h1><?php echo $entry_one_plus_one_is_three; ?></h1>
            <table class="form">
                <tr>
                    <td>
                        <b><?php echo $entry_status; ?></b>
                    </td>
                    <td>
                        <select name="one_plus_one_status">
                            <?php if ($one_plus_one_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><?php echo $entry_test_user; ?></b>
                    </td>
                    <td>
                        <input type="text" class="test_user" onfocus="$('.test_user').val('');" name="one_plus_one_test_user" value="<?php echo $one_plus_one_test_user; ?>"/>
                    </td>
                </tr>
            </table>
            <h1><?php echo $entry_shapka_v_podarok; ?></h1>
            <table class="form">
                <tr>
                    <td>
                        <b><?php echo $entry_status; ?></b>
                    </td>
                    <td>   <select name="shapka_v_podarok_status">
                            <?php if ($shapka_v_podarok_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><?php echo $entry_test_user; ?></b>
                    </td>
                    <td>
                        <input type="text" class="test_user" onfocus="$('.test_user').val('');" name="shapka_v_podarok_test_user" value="<?php echo $shapka_v_podarok_test_user; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><?php echo $entry_shapka_v_podarok_conditions; ?></b>
                    </td>
                    <td>
                        <input type="text" name="shapka_v_podarok_conditions" value="<?php echo $shapka_v_podarok_conditions; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><?php echo $entry_shapka_v_podarok_free_products; ?></b>
                    </td>
                    <td>
                        <textarea name="shapka_v_podarok_free_products" onkeyup="this.value = this.value.replace (/[^0-9\n]/, '')" autocomplete="nope" cols="20" rows="20"><?php echo $shapka_v_podarok_free_products; ?></textarea>
                    </td>

                </tr>
            </table>

        <h1><?php echo $entry_total_discount ?></h1>
        <table class="form">
            <tr>
                <td>
                    <b><?php echo $entry_status; ?></b>
                </td>
                <td>
                    <select name="total_discount_status">
                        <?php if ($total_discount_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php echo $entry_test_user; ?></b>
                </td>
                <td>
                    <input type="text" class="test_user" onfocus="$('.test_user').val('');" name="total_discount_test_user" value="<?php echo $total_discount_test_user; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php echo $entry_total_discount_conditions; ?></b>
                </td>
                <td>
                    <input type="text" name="total_discount_conditions" value="<?php echo $total_discount_conditions; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php echo $entry_total_discount_products; ?></b>
                </td>
                <td>
                    <textarea name="total_discount_products" onkeyup="this.value = this.value.replace (/[^0-9\n]/, '')" autocomplete="nope" cols="20" rows="20"><?php echo $total_discount_products; ?></textarea>
                </td>
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
