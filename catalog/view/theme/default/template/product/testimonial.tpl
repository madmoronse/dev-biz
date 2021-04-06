<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="f-left"><h1><?php echo $heading_title; ?></h1></div>
    <div class="f-right">
    		<a class="redbutton button-add" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a>
    </div>
    <div class="center">

    </div>
  </div>
  <div class="middle">

    <?php if (true/*$testimonials*/) { ?>

      <?php foreach ($testimonials as $testimonial) { ?>

      <div class="content testimonial">
        <div class="left">
          <div class="title">
            <span class="name"><?=$testimonial['name']?></span>
            <span class="info">
            <?= $testimonial['city']?>
            <? if($testimonial['city'] != '') echo " | "; ?>
            <?=$testimonial['date_added']; ?>
          </span>
          </div>
          <div class="description"><?=$testimonial['description']?></div>
          <?php if ($testimonial['image1'] != '' or $testimonial['image2'] != '' or $testimonial['image3'] != '') { ?>
            <div class="photos">
              <?php if ($testimonial['image1'] != '') {
        			echo '<a href="' . $testimonial['image1'] . '" class="colorbox"><img src="' . $testimonial['image1'] . '"/></a>';
        			} ?>
        			<?php if ($testimonial['image2'] != '') {
        			echo '<a href="' . $testimonial['image2'] . '" class="colorbox"><img src="' . $testimonial['image2'] . '"/></a>';
        			} ?>
        			<?php if ($testimonial['image3'] != '') {
        			echo '<a href="' . $testimonial['image3'] . '" class="colorbox"><img src="' . $testimonial['image3'] . '"/></a>';
        			} ?>
            </div>
            <?php } ?>
        </div>
        <div class="right">
          <?php if ($testimonial['rating']) { ?>
            <?php echo $text_average; ?><br>
            <img src="catalog/view/theme/default/image/testimonials/stars-<?php echo $testimonial['rating'] . '.png'; ?>" style="margin-top: 2px;" />
          <?php } ?>
        </div>
      </div>
      <? /* <table class="content" style="padding:20px;" width="100%" border=0>
      <tr>
         <td valign="top" style="text-align:left;" colspan="2"><?php //if ($testimonial['title'] != '') echo '<p style="margin-bottom:22px;">Тема: '.$testimonial['title'].'</p>'; ?></td>
      </tr>
      <tr >


		<?php if ($testimonial['image1'] != '' or $testimonial['image2'] != '' or $testimonial['image3'] != '') { echo '<td  style="background:#f5f5f5;width:1px; padding:10px; border:solid #eee 1px;overflow:hidden;white-space: nowrap;padding-bottom: 0px;"> <b style="display:block; text-align:center;" >Ваше фото:</b>';} ?>

			<?php if ($testimonial['image1'] != '') {
			echo '<a href="' . $testimonial['image1'] . '" class="colorbox"><img src="' . $testimonial['image1'] . '" style="max-width:150px;max-height:100px; padding:10px 20px 20px 10px;" /></a>';
			} ?>
			<?php if ($testimonial['image2'] != '') {
			echo '<a href="' . $testimonial['image2'] . '" class="colorbox"><img src="' . $testimonial['image2'] . '" style="max-width:150px;max-height:100px;padding:10px 20px 20px 10px;" /></a>';
			} ?>
			<?php if ($testimonial['image3'] != '') {
			echo '<a href="' . $testimonial['image3'] . '" class="colorbox"><img src="' . $testimonial['image3'] . '" style="max-width:150px;max-height:100px;padding:10px 20px 20px 10px;" /></a>';
			} ?>
			</td>
			<td style="background:#f5f5f5;text-align:left;padding-left:10px;padding-right:10px;border:solid #eee 1px;vertical-align:top;">
               <p style="padding-top:10px;"><b>Отзыв: </b></br>
			   <?php echo $testimonial['description']; ?></p>
			</td>
			<td style="width:150px;background:#f5f5f5;text-align:left;padding:10px;border:solid #eee 1px;vertical-align:top;">
			<div style="font-size: 0.9em; text-align: center;">
				<?php if ($testimonial['rating']) { ?>
                <?php echo $text_average; ?><br>
                  <img src="catalog/view/theme/default/image/testimonials/stars-<?php echo $testimonial['rating'] . '.png'; ?>" style="margin-top: 2px;" />
                  <?php } ?><br><i><?php echo $testimonial['name'].'<br />'.$testimonial['city'].'<br />'.$testimonial['date_added']; ?></i>
			</div>

            <?php if ($testimonial['image1'] != '') { echo '</td>';} ?>
      </tr>

	</table> */ ?>
      <?php } ?>

    	<?php if ( isset($pagination)) { ?>
    		<div class="pagination"><?php echo $pagination;?></div>
    	<? /*	<div class="buttons" align="right"><a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a></div> */ ?>
    	<?php }?>

    	<?php if (isset($showall_url)) { ?>
    			<div class="buttons" align="right"><? /*<a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a> &nbsp;*/ ?><a class="button" href="<?php echo $showall_url;?>" title="<?php echo $showall;?>"><span><?php echo $showall;?></span></a></div>
    	<?php }?>
    <?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
    current: '',
    title: false,
    arrowKey: false,
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script>
<?php echo $footer; ?>
