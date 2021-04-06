<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2 class="h2-head"><?php echo $text_edit_address; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_title; ?></td>
          <td><input type="text" name="title" required value="<?php echo $title; ?>" />
          <?php if ($error_title) { ?>
            <span class="error"><?php echo $error_title; ?></span>
          <?php } ?>
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_comment; ?></td>
          <td><textarea id="comment" name="content" rows="8" required><?php echo $content; ?></textarea>
            <?php if ($error_content) { ?>
              <span class="error"><?php echo $error_content; ?></span>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?>