<div id="geoip">
    <div class="content">
        <span id="geoip-text">
            <span><?php echo $text_zone; ?>: </span> <span class="zone"><?php echo $zone; ?></span>
        </span>
        <span id="geoip-search">
            <span><?php echo $text_search_zone; ?>: </span>
            <input type="text" id="geoip-search-field" placeholder="<?php echo $text_search_placeholder; ?>">
        </span>
    </div>
</div>
<div class="pr-autocomplete"></div>
<script type="text/javascript">
    $(function() {

        var geoip = {
            searchField:$('#geoip-search-field'),
            text:       $('#geoip-text'),
            search:     $('#geoip-search')
        };

        geoip.searchField.autocomplete({
            source:'<?php echo HTTP_SERVER; ?>index.php?route=module/geoip/search',
            minLength: 2,
            appendTo: '.pr-autocomplete',
            select:function(e, ui) {
                $.ajax({
                    url: '<?php echo HTTP_SERVER; ?>index.php?route=module/geoip/save',
                    type:'get',
                    data:'fias_id=' + ui.item.fias_id,
                    success:function() {
                        location.reload();
                    }
                });
            }
        });

        geoip.searchField.focus(function() {
            $(this).val('');
        }).blur(function() {
                    geoip.text.show();
                    geoip.search.hide();
                });

        geoip.text.click(function() {
            $(this).hide();
            geoip.search.show();
            geoip.searchField.focus();
        });
    });
</script>