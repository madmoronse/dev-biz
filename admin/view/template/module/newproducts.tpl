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
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tbody>
            <tr>
              <td><?php echo $entry_lifetime_in_weeks; ?></td>
              <td>
                <input id="working-hours" type="text" name="lifetime_in_weeks" value="<?php echo $lifetime_in_weeks; ?>" size="2" />									
                <?php if (isset($error['lifetime_in_weeks'])) { ?>
                <span class="error"><?php echo $error['lifetime_in_weeks']; ?></span>
                <?php } ?>
              </td>
            </tr>
          </tbody>
        </table>
        <table id="categories" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_category; ?></td>
              <td class="left"><?php echo $entry_product; ?></td>
            </tr>
          </thead>
          <?php $row = 0; ?>
          <tbody>
          <?php foreach ($categories as $category) { ?>
            <tr id="category-row<?php echo $row; ?>">
              <td class="left">
                <?php echo $category['name'] . ' (id: ' . $category['category_id'] . ')' ?>
                <input type="hidden" name="categories[<?php echo $row; ?>][name]" value="<?php echo $category['name'] ?>" />
                <input type="hidden" name="categories[<?php echo $row; ?>][category_id]" value="<?php echo $category['category_id'] ?>" />
              </td>
              <td class="left">
                <textarea
                  name="categories[<?php echo $row; ?>][products]"
                  rows="10"
                  cols="30"
                ><?php echo $category['products']; ?></textarea>
              </td>
            </tr>
          <?php $row++; ?>
          <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>