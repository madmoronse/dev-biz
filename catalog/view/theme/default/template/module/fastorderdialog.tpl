 <!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
<meta charset="UTF-8" />

<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/jquery-1.7.2.min.js"></script>

</head>
  
    
  <style type="text/css">
  body  {
  font-family: Arial, Sans-Serif;
  font-size: 13px;
  /*background-color: #d6e5f4;/*���� ����*/*/
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
  margin-bottom:15px;
  width: 217px;
  text-align: center;
  }
  br{margin-top:0px;margin-bottom:0px;}
  .right{text-align: right;}
  .remove { text-align: right; }
  .remove .button-remove { background: url(../image/remove-small-split.png) top no-repeat;
	display: inline-block;
	width:15px;
	height:15px;
	vertical-align: middle;
	cursor:pointer;
	}
	.remove .button-remove:hover {
		background-position: bottom;
	}
  .quantity{color: #6fb251;text-align: right;}
  .total{font-weight: 500;text-align: right;}
  input[name="time1"],input[name="time2"]{width: 98px; display:inline; }
  input[name="captcha"]{width: 80px}
  textarea {padding-top:5px; height: 45px;}
  input[type="checkbox"]{width: 15px; height:15px; display:inline; }
  input {line-height:20px; height:25px;}
  .buttons input {height:30px;}
  .buttons input:hover {border: solid 1px #33677F; background-color:#D3E4FC;}
  .activeField { background-color: #ffffff;  border: solid 1px #33677F; }
  .idle  {  border: solid 1px #85b1de;  background-color:#FFFFFF;  }
  .error {color:#FF0000;}
   span.success{ font-size:16px;   vertical-align:middle; text-align:center;  color:#18650E;  display:block; position: absolute; top: 40%; width:80%; left: 10%;}
</style>


  <body>
  <?php if (isset($success)) { 
    
  ?>
  
  
    <span class="success"><?php echo $success; ?></span>
	
	
   <?php } else { ?>
	
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    
	
	
	<h2><?php echo $heading_title;?></h2>
	
	<?php echo $entry_name; ?><br />
	<input type="text" name="name" value="<?php echo $name ?>" />	
	<br/>
	
	<?php echo $entry_city; ?><br />
	<input type="text" name="city" value="<?php echo $city ?>" />	
	<br/>
	
	
	<?php echo $entry_email; ?><br />
	<input type="text" placeholder="xxx@xxx.xxx" name="email" value="<?php echo $email; ?>" />	
	<br/>
	
	
	<?php echo $entry_tel; ?><br />
	<?php if ($error_tel) { ?>
      <div class="error"><?php echo $error_tel; ?></div>
      <?php } ?>
	<input type="text" placeholder="+7-xxx-xxx-xx-xx" name="tel" value="<?php echo $tel; ?>" />	
	<br/>
	
	<?php echo $entry_enquiry; ?><br />
    <textarea name="enquiry" cols="20" rows="2" class="idle" onblur="this.className='idle'" onfocus="this.className='activeField'" style="width:245px; height:60px; resize: none;" ><?php echo $enquiry; ?> </textarea>
    <br />
	<input type="hidden" name="link_page" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
	 
		 
	<?php if ($fastorderdialog_setting['capcha']==1) { ?>
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
          
      <span><input type="text" name="captcha" value="<?php echo $captcha; ?>" /></span>
      <span style="vertical-align: middle; display: inline-block; padding-top: 10px; height: 40px;"><img src="index.php?route=information/contact/captcha" /></span></div>
	  
	   <?php if ($error_captcha) { ?>
      <div class="error"><?php echo $error_captcha; ?></div>
      <?php } ?>
	  
	<br />
	<div class="buttons">
      <input type="submit" value="<?php echo $button_send; ?>"/>
    </div>
	
	 
  </form>
  
  <br />
  
  <a style="font-size: 14px;" target="_blank" href="/shipping-and-payment.html" ><?php echo $text_shipping_and_payment; ?></a>
  
  <h3><?php echo $text_items; ?></h3>
  
  <?php if ($products) { ?>
    <div class="fastcart" style="border:1px solid #33677F;height:166px; width:480px; overflow-x: scroll;overflow: auto;">
      <table class="right" style="margin: auto; background:#FFF;float: right; width: 100%;">
        <?php foreach ($products as $product) { ?>
        <tr >
          <td class="image" style="border-bottom: 1px dotted #d2d2d2;"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name" style="border-bottom: 1px dotted #d2d2d2;"><a href="<?php echo $product['href']; ?>"><?php echo $product['name'] . ' (' . $articul . ': ' . $product['product_id'] . ')';?></a>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
            </div></td>
          <td class="quantity" style="border-bottom: 1px dotted #d2d2d2;">x&nbsp;<?php echo $product['quantity']; ?></td>
          <td class="total" style="border-bottom: 1px dotted #d2d2d2;"><?php echo $product['total']; ?></td>
          <td class="remove" style="border-bottom: 1px dotted #d2d2d2;">
						<a class="button-remove" title="<?php echo $entry_remove; ?>" onclick="location = 'index.php?route=module/fastorderdialog/open&remove=<?php echo $product['key']; ?>';"></a>
					</td>
        </tr>
        <?php } ?>
       
      </table>
	  
	  <table style="float:right">
        <?php foreach ($totals as $total) { ?>
        <tr >
          <td class="right"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right total-price"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
	  
    </div>
	
	<br/>

    <?php } ?>
  
   <?php } ?>
  </body>
  </html>
