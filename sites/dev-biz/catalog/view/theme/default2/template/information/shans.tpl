<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <div class="h1_description" style='font-family: "verdana";line-height:30px'>
  <h1 style="color:#FF4444;text-align:center;"><?php echo $heading_title; ?></h1>  
	
	<div>
	<hr style="color:red;" />
	<br />
		<!--<h1 style="font-size:18px;color:red;">- Самый сильный, смелый и необыкновенный человек, победить которого практически невозможно - кто он?</h1>-->
	<div style="text-align: center;margin-left: auto;margin-right: auto;"><img src="image/data/question/1/question1.png" style="width:100%;margin-left:20px;"></div>
	<br />
	<hr style="color:red;" />	<br />
		<h2 style="text-decoration:none;">Если вы угадали, о ком идет речь, то вам нужно:</h1>
		<p>1. Найти в <a href="/obuv/" target="_blank" >КАТАЛОГЕ</a> магазина outmaxshop.ru модель кроссовок, которая идеально подходит под стиль этого человека. Запомнить артикул, указанный рядом с названием модели.</p>
		<p>2. Перейти на <a href="/shans/" target="_blank" style="font-size:16px">страничку викторины</a>, указать там ваши контактные данные, и в поле "ваш ответ на вопрос викторины" написать имя человека, о котором идет речь, и четырехзначный артикул тех кроссовок, которые ему подходят.</p>
		<p>3. Отправить ваш вариант ответа, нажав кнопку "ШАНС". </p>
		<hr /> <br />
		<p>Первые 3 участника, угадавшие имя этого человека и модель кроссовок, получат в подарок пару кроссовок! Следующие 20 участников, приславшие правильные ответы, получат ценные подарки- браслеты, очки, часы, футболки!</p>
	
	</div>
  </div>
 	
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" style='font-family: "verdana";'>
    <div class="contact-info">
		<!--<h2 class="h2-head"><?php /*echo $text_location; */?></h2>
      <div class="content"><div class="left"><b><?php /* echo $text_address; */?></b><br />
        <?php /*echo $store; */?><br />
        <?php /*echo $address; */?></div>
      <div class="right">
        <?php /*if ($telephone) { */ ?>
        <b><?php /*echo $text_telephone; */ ?></b><br />
        <?php /*echo $telephone; */ ?><br />
        <br />
        <?php /* } */ ?>
        <?php /* if ($fax) { */ ?>
        <b><?php /* echo $text_fax; */ ?></b><br />
        <?php /*echo $fax; */ ?>
        <?php /* } */ ?>
      </div>
    </div>
    </div>-->
    <h2 class="h2-head" style="font-size:20px;padding-left:20px;color:#FF3333;"><?php echo $text_contact; ?></h2>
    <div class="content" style="padding:20px;">
    <span style="display: inline-block;width:120px;"><b><?php echo $entry_name; ?></b></span>
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <span style="display: inline-block;width:120px;"><b><?php echo $entry_email; ?></b></span>
    <input type="text" name="email" value="<?php echo $email; ?>" />
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php } ?>
    <br />
    <span style="display: inline-block;width:120px;"><b><?php echo $entry_telephone; ?></b></span>
    <input type="text" name="telephone" value="" />
    <br />
    <?php if ($error_telephone) { ?>
    <span class="error"><?php echo $error_telephone; ?></span>
    <?php } ?>
    <br />
 
	<div style="height:140px;">
		<div style="width:30px;height:140px;border-left: 2px solid #FF4444;border-top: 2px solid #FF4444;border-bottom: 2px solid #FF4444;display:inline-block;position:absolute;"></div>
		<div style="width:600px;height:140px;border-top: 2px dashed #FF4444;border-bottom: 2px dashed #FF4444;display:inline-block;position:absolute;left:50px">
			<div style="margin:20px"><b><?php echo $entry_enquiry; ?></b><br /><br />
				Кто он?  <input type="text" name="his_name" value="" style="width:210px;"/>
				<?php if ($error_his_name) { ?>
				<span class="error" style="display:inline-block;"><?php echo $error_his_name; ?></span>
				<?php } ?>
				<br />	<br />		
				Кроссовки для него, артикул:  <input type="text" name="his_articul" value="" style="width:50px;"/>
				<?php if ($error_his_articul) { ?>
				<span class="error" style="display:inline-block;"><?php echo $error_his_articul; ?></span>
				<?php } ?>
			</div>
		</div>
		<div style="width:30px;height:140px;border-right: 2px solid #FF4444;border-top: 2px solid #FF4444;border-bottom: 2px solid #FF4444;display:inline-block;position:absolute;left:650px"></div>
	</div>
    <br />

    <b><?php echo $entry_captcha; ?></b><br /><br />
    <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
    <br /><br />
    <img src="index.php?route=information/contact/captcha" alt="" />
    <?php if ($error_captcha) { ?>
    <span class="error"><?php echo $error_captcha; ?></span>
    <?php } ?>
	<br />
	 <div class="buttons">
	<div class="center"><input type="submit" value="Шанс" class="button" style="width:120px;font-size:18px;height: 40px;"/></div> 
	</div>
	</div>
	
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>