<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
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
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">

                <tr>
                    <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                    <td><input type="text" name="filter_email" required value="<?php echo $email; ?>" /></td>
                </tr>

                <tr>
                    <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input type="text" name="name" required value="<?php echo $name; ?>" /></td>

                </tr>
            </table>
            </form>

            <div>
                <table class="list">
                    <thead>
                    <tr>
                        <td class="left"><?php echo $student_email; ?></td>
                        <td class="left"><?php echo $student_name; ?></td>
                        <td class="right"><?php echo $student_phone; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($students) { ?>
                    <?php foreach ($students as $student) { ?>
                    <tr>
                        <td class="left"><?php echo $student['email']; ?></td>
                        <td class="left"><?php echo $student['firstname']. ' ' . $student['middlename'] . ' ' .  $student['lastname']; ?></td>
                        <td class="right"><?php echo $student['phone']; ?></td>
                    </tr>
                    <?php } } else { ?>
                    <tr>
                        <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="pagination"><?php echo $pagination; ?></div>
            </div>

        </div>
    </form>
    <?php echo $content_bottom; ?></div>

<script>

var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

        $.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_email\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.email,
						value: item.customer_id,
                        name: item.name
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_email\']').val(ui.item.label);
		$('input[name=\'name\']').val(ui.item.name);	
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
    </script>

<?php echo $footer; ?>