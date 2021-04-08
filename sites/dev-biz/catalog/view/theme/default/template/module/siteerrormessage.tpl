 <!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
<meta charset="UTF-8" />
</head>
  
    
  <style type="text/css">
  body  {
  font-family: Arial, Sans-Serif;
  font-size: 13px;
  background-color: #d6e5f4;/*���� ����*/
  padding: 10px;
  text-align: center;
  margin:0;
   }
   
  form {padding:0;margin:0;}
  h3 {font-size:14px; }
  input, textarea {
  background-color: #F6F6F6;
  border: solid 1px #33677F;
  display: inline;
  margin-bottom:10px;
  width: 217px;
  text-align: center;
  }
  input[name="time1"],input[name="time2"]{width: 98px; display:inline; }
  textarea {padding-top:5px; height: 45px;}
  input[type="checkbox"]{width: 15px; height:15px; display:inline; }
  input {line-height:20px; height:25px;}
  .buttons input {height:30px;}
  .buttons input:hover {border: solid 1px #33677F; background-color:#D3E4FC;}
  .activeField { background-color: #ffffff;  border: solid 1px #33677F; }
  .idle  {  border: solid 1px #85b1de;  background-color:#FFFFFF;  }
  .error {color:#FF0000;}
   span.success{ font-size:16px;   text-align:center;  color:#18650E;  display:block;  }
</style>


  <body>
  
  <h3><?php echo $heading_title; ?></h3><br />
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
     
	
	<?php echo $entry_enquiry; ?><br /><br />
    <textarea name="enquiry" cols="20" rows="2" class="idle" onblur="this.className='idle'" onfocus="this.className='activeField'" style="width:245px; height:130px; resize: none;" ><?php echo $enquiry; ?> </textarea>
    <br />
		
	<input type="hidden" name="link_page" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
		 
	<?php if (isset($success)) { ?>
    <span class="success"><?php echo $success; ?></span>
   <?php } else { ?>
	 
	<?php if ($siteerrormessage_setting['capcha']==1) { ?>
    <div class="ihomos">
	<?php if ($error_capcha) { ?>
    <span class="error"><?php echo $error_capcha; ?></span>
    <?php } ?>	<br />
	<?php echo $qs; ?> <BR />
    <?php echo $no; ?>:<input type="checkbox" name="irobot_no" value="0" checked="checked" />
	<?php echo $yes; ?>:<input type="checkbox" name="irobot_yes" value="1"  />
    </div>
	<?php } ?>
<div><?php echo $entry_captcha; ?><br />
      <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
      <?php if ($error_captcha) { ?>
      <div class="error"><?php echo $error_captcha; ?></div>
      <?php } ?>
      <br />
      <img src="index.php?route=information/contact/captcha" /></div>
	<br />
	<div class="buttons">
      <input type="submit" value="<?php echo $button_send; ?>" />
    </div>
	 <?php } ?>
	 
  </form>
  
  </body>
  </html>
