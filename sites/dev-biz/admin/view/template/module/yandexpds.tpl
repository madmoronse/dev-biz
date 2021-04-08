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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
    
   
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
       		 <table class="form">
              <tr>
                <td><span class="required">*</span>Ключ (key)</td>
                <td><input type="text" name="yandexpds_config[key]"  size="20" value="<?=(!empty($yandexpds_config['key']))?$yandexpds_config['key']:'' ?>" />
                  </td>
              </tr>
                <tr>
                <td><span class="required">*</span>ID поиска (searchid)</td>
                <td><input type="text" name="yandexpds_config[searchid]"  size="20" value="<?=(!empty($yandexpds_config['searchid']))?$yandexpds_config['searchid']:'' ?>" />
                  </td>
              </tr>
                <tr>
                <td><span class="required">*</span>Имя пользователя (login)</td>
                <td><input type="text" name="yandexpds_config[login]"  size="20" value="<?=(!empty($yandexpds_config['login']))?$yandexpds_config['login']:'' ?>" />
                  </td>
              </tr>
              
              
                <tr>
                <td>Лог</td>
                <td><textarea rows="10" cols="45"><?=(!empty($yandexpds_log))?$yandexpds_log:'' ?></textarea>
                  </td>
              </tr>
              
       			
       			
       			
       			</table>

      </form>
    </div>
    
       <div id="ocjoy-copyright">Модуль  "Яндекс™.ПДС v0.9.1" разработан командой OcJoy в 2014 году. Вопросы по техподдержке и работе модуля отправляйте через сайт <a href="http://support.ocjoy.com/" target="_blank">OcJoy</a> .<br></div>
  </div>
</div>


<style type="text/css">
#ocjoy-copyright {padding:15px 15px;border:1px solid #ccc;margin-top:15px;box-shadow:0 0px 5px rgba(0,0,0,0.1);clear: both;}
</style>

<?php echo $footer; ?>