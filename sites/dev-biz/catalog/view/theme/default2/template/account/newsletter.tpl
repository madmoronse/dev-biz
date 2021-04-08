<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="content content-newsletter">
      <table class="form">
        <thead>
        <tr>
          <td style="text-align: left;"><?php echo $entry_newsletter; ?></td>
        </tr>
      </thead>
        <tr>
          <td style="text-align: left;">
            <h4 class="newsletter-offer"><?php echo$offer_newsletter?></h4><br>
            <?php if ($newsletter) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" class="sub-radio" id="yes"/>
            <label for="yes"><?php echo $text_yes; ?>&nbsp;</label><br><br>
            <input type="radio" name="newsletter" value="0" class="sub-radio" id="no"/>
            <label for="no"><?php echo $text_no; ?></label>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" class="sub-radio" id="yes"/>
            <label for="yes"><?php echo $text_yes; ?>&nbsp;</label><br><br>
            <input type="radio" name="newsletter" value="0" checked="checked" class="sub-radio" id="no"/>
            <label for="no"><?php echo $text_no; ?></label>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>