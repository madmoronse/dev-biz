<?php echo $header; ?>
    <div id="content">
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <?php if ($error_warning) { ?>
            <div class="warning"><?php echo $error_warning; ?></div>
        <?php } ?>
        <div class="box">
            <div class="heading">
                <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>

                <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a
                            onclick="location = '<?php echo $cancel; ?>';"
                            class="button"><?php echo $button_cancel; ?></a></div>
            </div>
            <div class="content">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_set_zone; ?></td>
                            <td>
                                <input type="radio" name="geoip_setting[set_zone]" value="1"
                                        <?php echo !empty($geoip_setting['set_zone']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_yes; ?>
                                <input type="radio" name="geoip_setting[set_zone]" value="0"
                                        <?php echo empty($geoip_setting['set_zone']) ? ' checked="checked"' : ''; ?>/>
                                <?php echo $text_no; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_currency_for_ru; ?></td>
                            <td>
                                <select name="geoip_setting[currency_for_ru]">
                                    <option value="0"><?php echo $text_none; ?></option>
                                    <?php foreach ($currencies as $currency) {
                                        ?>
                                        <option value="<?php echo $currency['code']; ?>"
                                                <?php echo !empty($geoip_setting['currency_for_ru']) && $geoip_setting['currency_for_ru'] == $currency['code'] ? ' selected' : ''; ?>>
                                            <?php echo $currency['title']; ?>
                                        </option>
                                    <?php
                                    }?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_currency_for_ua; ?></td>
                            <td>
                                <select name="geoip_setting[currency_for_ua]">
                                    <option value="0"><?php echo $text_none; ?></option>
                                    <?php foreach ($currencies as $currency) {
                                        ?>
                                        <option value="<?php echo $currency['code']; ?>"
                                                <?php echo !empty($geoip_setting['currency_for_ua']) && $geoip_setting['currency_for_ua'] == $currency['code'] ? ' selected' : ''; ?>>
                                        <?php echo $currency['title']; ?>
                                        </option>
                                    <?php
                                    }?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <h3><?php echo $text_geo_messages; ?></h3>
                    <table id="rules" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_key; ?></td>
                            <td class="left"><?php echo $entry_zone; ?></td>
                            <td class="left"><?php echo $entry_value; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $rule_row = 0; ?>
                        <?php foreach ($rules as $rule) { ?>
                            <tbody id="rule-row<?php echo $rule_row; ?>">
                            <tr>
                                <td class="left"><input type="text"
                                                        name="geoip_rule[<?php echo $rule_row; ?>][key]"
                                                        value="<?php echo $rule['key']; ?>"/>
                                    <?php if (isset($error_key[$rule_row])) { ?>
                                        <span class="error"><?php echo $error_key[$rule_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <input type="text" name="" value="<?php echo $rule['fias_name']; ?>" class="row-fias-name"/>
                                    <input type="hidden" name="geoip_rule[<?php echo $rule_row; ?>][fias_id]"
                                           value="<?php echo $rule['fias_id']; ?>" class="row-fias-id"/>
                                    <?php if (isset($error_fias[$rule_row])) { ?>
                                        <span class="error"><?php echo $error_fias[$rule_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <textarea name="geoip_rule[<?php echo $rule_row; ?>][value]"><?php echo $rule['value']; ?></textarea>
                                </td>

                                <td class="left"><a onclick="$('#rule-row<?php echo $rule_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $rule_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="left"><a onclick="addRule();"
                                                class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>

                    <h3><?php echo $text_geo_redirects; ?></h3>
                    <table id="redirects" class="list">
                        <thead>
                        <tr>
                            <td class="left"><?php echo $entry_zone; ?></td>
                            <td class="left"><?php echo $entry_subdomain; ?></td>
                            <td></td>
                        </tr>
                        </thead>
                        <?php $redirect_row = 0; ?>
                        <?php foreach ($redirects as $redirect) { ?>
                            <tbody id="redirect-row<?php echo $redirect_row; ?>">
                            <tr>
                                <td class="left">
                                    <input type="text" name="" value="<?php echo $redirect['fias_name']; ?>" class="row-fias-name"/>
                                    <input type="hidden" name="geoip_redirect[<?php echo $redirect_row; ?>][fias_id]"
                                           value="<?php echo $redirect['fias_id']; ?>" class="row-fias-id"/>
                                    <?php if (isset($error_redirect_fias[$redirect_row])) { ?>
                                        <span class="error"><?php echo $error_redirect_fias[$redirect_row]; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="left">
                                    <input type="text" name="geoip_redirect[<?php echo $redirect_row; ?>][value]" value="<?php echo $redirect['value']; ?>"/>
                                    <?php if (isset($error_subdomain[$redirect_row])) { ?>
                                        <span class="error"><?php echo $error_subdomain[$redirect_row]; ?></span>
                                    <?php } ?>
                                </td>

                                <td class="left"><a onclick="$('#redirect-row<?php echo $redirect_row; ?>').remove();"
                                                    class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                            </tbody>
                            <?php $redirect_row++; ?>
                        <?php } ?>
                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="left"><a onclick="addRedirect();" class="button"><?php echo $button_add_rule; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
    var rule_row = <?php echo $rule_row; ?>;

    function addRule() {
        html = '<tbody id="rule-row' + rule_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="geoip_rule[' + rule_row + '][key]"/></td>';
        html += '    <td class="left"><input type="text" name="" class="row-fias-name"/><input type="hidden" name="geoip_rule[' + rule_row + '][fias_id]" class="row-fias-id"/></td>';
        html += '    <td class="left"><textarea name="geoip_rule[' + rule_row + '][value]"/></td>';
        html += '    <td class="left"><a onclick="$(\'#rule-row' + rule_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#rules tfoot').before(html);

        rule_row++;
    }
    
    var redirect_row = <?php echo $redirect_row; ?>;

    function addRedirect() {
        html = '<tbody id="redirect-row' + redirect_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="text" name="" class="row-fias-name"/><input type="hidden" name="geoip_redirect[' + redirect_row + '][fias_id]" class="row-fias-id"/></td>';
        html += '    <td class="left"><input type="text" name="geoip_redirect[' + redirect_row + '][value]"/></td>';
        html += '    <td class="left"><a onclick="$(\'#redirect-row' + redirect_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#redirects tfoot').before(html);

        redirect_row++;
    }
//--></script>
<script type="text/javascript"><!--
    $('#rules, #redirects').on('focus', '.row-fias-name', function() {
        if (!$(this).data('autocomplete')) {
            addAutocomplete($(this));
        }
    });

    $('.row-fias-name').each(function() {
        addAutocomplete($(this));
    });

    function addAutocomplete(el) {
        el.autocomplete({
            source:    'index.php?route=module/geoip/search&token=<?php echo $token; ?>',
            minLength: 2,
            select:    function(e, ui) {
                $(this).next('.row-fias-id').val(ui.item.fias_id);
            }
        });
    }
//--></script>
