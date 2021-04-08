<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
        <h1><img src="view/image/module.png" alt=""> <b><?php echo $heading_title ?></b></h1>
        <div class="buttons">
            <a onclick="$('#form').submit();" class="button"><span>Сохранить</span></a>
            <a onclick="location = '/admin/index.php?route=extension/module&token=<?php echo $token ?>';" class="button"><span>Отменить</span></a>
        </div>
    </div>
    <div class="content">
        <form action="/admin/index.php?route=module/catalog_gifts&token=<?php echo $token ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tbody>
                    <tr>
                        <td><?php echo $entity_list ?></td>
                        <td>
                            <textarea type="text" name="catalog_gifts_list" rows="6" cols="80"><?php echo $catalog_gifts_list ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entity_rules ?></td>
                        <td>
                            <textarea type="text" name="catalog_gifts_rules" rows="1" cols="80"><?php echo $catalog_gifts_rules ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entity_show_info; ?></td>
                        <td><select name="catalog_gifts_show_info">
                            <?php if ($catalog_gifts_show_info) { ?>
                            <option value="1" selected="selected"><?php echo $entity_yes; ?></option>
                            <option value="0"><?php echo $entity_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $entity_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $entity_no; ?></option>
                            <?php } ?>
                            </select></td>
                    </tr>

            </tbody>
        </table>
        </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>