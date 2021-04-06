<?php echo $header; ?>
<div id="content">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
    <div ><?php echo $files; ?></div>
	<script>
        var strurl = "<?php echo $home;?>";
        strurl = strurl.replace("&amp;","&");
        setTimeout(function() { document.location.href =strurl }, 10000);
    </script>
<?php echo $footer; ?>