<div class="box">
    <div class="box-heading " style="color:#000;font-weight:bold;"><?php if($newsblock_module[1]['block_name'])echo $newsblock_module[1]['block_name']; else echo $text_title;?></div>
    <div class="box-content" style="padding: 0 10px;">
        <div class="box-product owl-carousel owl-theme" id="newsblock-carousel" style="margin: 15px;">
            <? foreach ($news as $curNews) { ?>
                <div class="item" style="border:1px solid #fdfdfd;">
                    <div class="mainpagenews" >
                        <a href = "http://bizoutmax.ru/index.php?route=pavblog/blog&id=<?php echo $curNews['blog_id']; ?>"  >
                            <img src = "<?php  echo $curNews['image']; ?>" >
                            <span><?php  echo $curNews['title']; ?></span >
                            <span><?php echo $text_more; ?></span >
                        </a >
                    </div >
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#newsblock-carousel").owlCarousel({

            autoPlay: 4000, //Set AutoPlay to 3 seconds
            loop: true,

            items : 3,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3]

        });
    });
</script>

