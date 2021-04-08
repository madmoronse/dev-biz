<div class="slideshow" style="background-image: url('../image/data/baners/ban1.jpg');border-radius: 5px;">
  <div id="slideshow<?php echo $module; ?>" style="max-width:100%!important">
    <?php foreach ($banners as $key => $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>" data-banner-image-id="<?php echo $banner['banner_image_id'] ?>">
    <?php } ?>
    <?php if (empty($banner['video'])) { ?>
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } else { ?>
      <div class="slider-video-wrapper">
        <video src="<?php echo $banner['video']; ?>" id="slider-video-<?php echo $key ?>"></video>
        <span class="slider-video-controls"></span>
      </div>
    <?php } ?>
    <?php if ($banner['link']) { ?>
    </a>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script>
  var slideshow = new Slideshow($('#slideshow<?php echo $module; ?>'));

  /** 
   * Check if window is active 
   */
  (function() {
  var hidden = "hidden";

  // Standards:
  if (hidden in document)
    document.addEventListener("visibilitychange", windowStateChange);
  else if ((hidden = "mozHidden") in document)
    document.addEventListener("mozvisibilitychange", windowStateChange);
  else if ((hidden = "webkitHidden") in document)
    document.addEventListener("webkitvisibilitychange", windowStateChange);
  else if ((hidden = "msHidden") in document)
    document.addEventListener("msvisibilitychange", windowStateChange);
  // IE 9 and lower:
  else if ("onfocusin" in document)
    document.onfocusin = document.onfocusout = windowStateChange;
  // All others:
  else
    window.onpageshow = window.onpagehide
    = window.onfocus = window.onblur = windowStateChange;
    /**
     * 
     * @param {Object} evt 
     */
    function windowStateChange(evt) {
        var v = true, 
            h = false,
            evtMap = {
                focus:v, 
                focusin:v, 
                pageshow:v, 
                blur:h, 
                focusout:h, 
                pagehide:h
            };

        evt = evt || window.event;
        var windowIsActive;
        if (evt.type in evtMap) {
            windowIsActive = evtMap[evt.type];
        } else {
            windowIsActive = this[hidden] ? h : v;
        }

        if (windowIsActive && slideshow.activeVideo && slideshow.activeVideo.source.paused) {
            if (slideshow.isVideoInView(slideshow.activeVideo.source) && 
                !$(slideshow.activeVideo.source).siblings('.slider-video-controls').hasClass('paused')) 
                slideshow.activeVideo.source.play();
        } else if (!windowIsActive && slideshow.activeVideo && !slideshow.activeVideo.source.paused) {
            slideshow.activeVideo.source.pause();
        }
    }
  // set the initial state (but only if browser supports the Page Visibility API)
  if( document[hidden] !== undefined )
    windowStateChange({type: document[hidden] ? "blur" : "focus"});
})();
</script>
