<?php $customer_group_id = $this->customer->getCustomerGroupId(); ?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php if ($og_image) { ?>
<meta property="og:image" content="<?php echo $og_image; ?>" />
<?php } else { ?>
<meta property="og:image" content="<?php echo $logo; ?>" />
<?php } ?>
<meta property="og:site_name" content="<?php echo $name; ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/fonts/bebasneue/bebasneue.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/fonts/opensans/opensans.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/fonts/ubuntu/ubuntu.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/stylesheet/stylesheet.css?20181129" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/stylesheet/stylesheet1200.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
 
    ym(52750312, "init", {
         clickmap:true,
         trackLinks:true,
         accurateTrackBounce:true,
         webvisor:true
    });
 </script>
 <noscript><div><img src="https://mc.yandex.ru/watch/52750312" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
 <!-- /Yandex.Metrika counter -->
<!-- Begin Talk-Me {literal} -->
<script type='text/javascript'>
	(function(d, w, m) {
		window.supportAPIMethod = m;
		var s = d.createElement('script');
		s.type ='text/javascript'; s.id = 'supportScript'; s.charset = 'utf-8';
		s.async = true;
		var id = 'bf766527630d13758f3f9d150c5fb065';
		s.src = 'https://lcab.talk-me.ru/support/support.js?h='+id;
		var sc = d.getElementsByTagName('script')[0];
		w[m] = w[m] || function() { (w[m].q = w[m].q || []).push(arguments); };
		if (sc) sc.parentNode.insertBefore(s, sc); 
		else d.documentElement.firstChild.appendChild(s);
	})(document, window, 'TalkMe');
</script>
<!-- {/literal} End Talk-Me -->
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/neos.min.js?v9"></script>
<script type="text/javascript" src="js/neos_cart.min.js?v6"></script>
<script type="text/javascript" src="catalog/view/javascript/add_bc.js?v1"></script>
<!-- Ubuntu font load -->
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/ubuntu-font.js"></script>
<!-- Owl-carousel -->
<link rel="stylesheet" href="catalog/view/theme/default2/assets/owl-carousel/owl.carousel.css">
<link rel="stylesheet" href="catalog/view/theme/default2/assets/owl-carousel/owl.theme.css">
<script src="catalog/view/theme/default2/assets/owl-carousel/owl.carousel.js"></script>
<!-- Poshytip -->
<script src="catalog/view/theme/default2/assets/poshytip/jquery.poshytip.js"></script>
<link rel="stylesheet" href="catalog/view/theme/default2/assets/poshytip/tip-twitter/tip-twitter.css" type="text/css" />
<!-- Scroll name -->
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/scrollname.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="catalog/view/theme/default2/assets/fortawesome/css/font-awesome.min.css" type="text/css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="css/neos_mobile.css?v35" />
<link rel="stylesheet" type="text/css" href="css/neos.css?v5" media="screen" />
<link rel="stylesheet" type="text/css" href="css/neos_cart.css?v4" media="screen" />
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/inputmask.js"></script>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/jquery.inputmask.js"></script>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/common.js?v5"></script>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/tabs.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript" src="catalog/view/theme/default2/assets/jquery/tabs.js"></script>
<!--<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script> -->
<!--[if lt IE 10]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/stylesheet/ie9.css" />
<![endif]-->
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/stylesheet/ielt9.css" />
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php if ($customer_group_id < 2) { ?>
<?php echo $google_analytics; ?>
<?php } ?>
<?php if ($customer_group_id > 2) { ?>
<script type="text/javascript" src="https://cloudparser.ru/widget/script?hash=4afa8f9e90756f0f919a124a1dfbba19be004edc" async></script>
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/gototop.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default2/stylesheet/gototop.css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>

</head>


<body>
<?php if(!$logged) {?>
<a href="http://bizoutmax.ru/sotrudnichestvo/" style="text-decoration: none;">	
	<div id="top-header-partnership">
		<span class="partnership">Оставить заявку на сотрудничество</span>
	</div>
</a>
<?php } else {?>
	<div id="top-header-logged">
		<div id="logged-name">
			<?php echo str_replace('my-account','order-history',$text_logged); ?>
			<?php if($customer_group_id == 4) {?>
				<span>[Drop]</span>
			<?php } elseif ($customer_group_id == 3) {?>
				<span>[Opt]</span>
			<?php } else {?>
				<span>[Без доступа]</span>
			<?php }?>
			<!--<img src="http://test-oc/image/data/demo/arrow.png" width="8" height="8">-->
			<div id="menu-list">
				<ul>
					<a href="/index.php?route=account/account"><li>Личный кабинет</li></a>
					<a href="/index.php?route=account/order"><li>История заказов</li></a>
					<a href="/index.php?route=account/tracking_number"><li>Почтовые треки</li></a>
					<a href="/index.php?route=account/invoice"><li>Отчёты по продажам</li></a>
			          <?php if ($customer_group_id == 4) {?>
			            <a href="http://bizoutmax.ru/price/Price_Drop.xlsx"><li>Прайс ДРОП</li></a>
			          <?php } elseif ($customer_group_id == 3) {?>
			            <a href="http://bizoutmax.ru/price/Price_Opt.xlsx"><li>Прайс ОПТ</li></a>
			          <?php }else{}?>
					<a href="/index.php?route=account/logout"><li>Выход</li></a>
				</ul>
			</div>
		</div>
		
		<div class="logged-info">
			<div id="help-list">
			<a href="/index.php?route=information/information&information_id=22">Оплата заказа</a> 
			<a href="/index.php?route=information/information&information_id=6">Доставка </a> 
			<a href="/index.php?route=information/information&information_id=8">Связаться с нами</a> 
			<a href="https://www.pochta.ru/tracking" target="_blank">Отследить заказ [Почта]</a> 
			<a href="https://www.cdek.ru/ru/tracking" target="_blank">Отследить заказ [CDEK]</a>
			</div>
		</div>
	</div>
	
<?php }?>

  <div id="top">
    <div id="top-contener">
      <?php if ($_SERVER[HTTP_HOST] != "opt.bizoutmax.ru" ) { echo $cart; } else echo '<a href="http://bizoutmax.ru/create-account/" style="text-decoration: none; position: absolute; right: 20px; color: #fff; font-size: 18px; font-weight: 600; text-align: right; z-index: 999; background: #E31E24; padding: 0 10px;">Регистрация</a>';?> 
      <?php if ($_SERVER[HTTP_HOST] != "opt.bizoutmax.ru" ) { ?>

      	<div id="wishlist_" class="wishlist cart">
		 	<div class="heading">
				<a href="/index.php?route=account/wishlist">
					<?php echo $text_wishlist; ?>
				</a>
			</div>
		</div>

        <div id="welcome">
          <?php if (!$logged) { ?>
          	<?php echo $text_welcome; ?>
          <?php } ?>
        </div>
        <?php } ?>
      <?php echo $language; ?>
      <?php echo $currency; ?>
       <? /* <div id="compare"><a id="compare-total-top"></a></div> */ ?>

      <?php /* if ($customer_group_id < 2) { echo $geoip; } */ ?>
    </div>
  </div>
<?php if (substr_count($title, 'Оформление заказа') < 1){ ?>
<div id="top-link">
	
</div>





<?php } ?>
<?php if (($_SERVER['REQUEST_URI']=="/") || ($_SERVER['REQUEST_URI']=="/index.php ") || ($_SERVER['REQUEST_URI']=="/index.php?route=account/password")) { ?>
<?php } ?>

<div id="container">
<div id="header">

  <?php if ($logo) { ?>
		<div id="logo">
			<?php if ($home == $og_url) { ?>
				<img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
			<?php } else { ?>
				<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
			<?php } ?>
		</div>
  <?php } ?>
  <div id="search">
    <div class="button-search"></div>
    <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
  </div>
	<div id="contact-us" <?php if ($_SERVER[HTTP_HOST] == "opt.bizoutmax.ru" ) { echo 'style="right: 160px;"';}?>></div>
</div>

   <?php if ($slim_banner['slim_banner_status']){ ?>
    <a id="slim_banner_url" href="<?php echo $slim_banner['slim_banner_url']?>">
<div id="slim_banner" style="background-size: contain;height:100px;background-image:url(image/<?php echo $slim_banner['slim_banner_image']?>);">
   
        <p id="slim_banner_time" ></p>
		
	</div>
    </a>
    
	<script type="text/javascript">
        timeend = new Date("<?php echo $slim_banner['slim_banner_timer_date']; ?> <?php echo $slim_banner['slim_banner_timer_time']; ?>");

        // IE и FF по разному отрабатывают getYear()
       // timeend= new Date(timeend.getYear()>1900?(timeend.getYear()+1):(timeend.getYear()+1901),0,1);
        // для задания обратного отсчета до определенной даты укажите дату в формате:
        // timeend= new Date(ГОД, МЕСЯЦ-1, ДЕНЬ);
        // Для задания даты с точностью до времени укажите дату в формате:
        // timeend= new Date(ГОД, МЕСЯЦ-1, ДЕНЬ, ЧАСЫ-1, МИНУТЫ);

        setTimeout(time, 1000);
        function time() {
            today = new Date();
            if (today < timeend) {
            today = Math.floor((timeend - today) / 1000);
            tsec = today % 60;
            today = Math.floor(today / 60);
            if (tsec < 10) tsec = '0' + tsec;
            tmin = today % 60;
            today = Math.floor(today / 60);
            if (tmin < 10) tmin = '0' + tmin;
            thour = today % 24;
            today = Math.floor(today / 24);
            timestr = "<?php echo $slim_banner['slim_banner_timer_header']; ?><span>" + today + " дней " + thour + ":" + tmin + ":" + tsec + "</span>";


            document.getElementById('slim_banner_time').innerHTML = timestr;
            window.setTimeout("time()", 1000);
        }
        }
    </script>
	
	<script>
	$(document).ready(function() {        
	
		if ($(window).width() < 1200){	
			$('#slim_banner').width($(window).width()-20);
			$('#slim_banner').height($(window).width()/12);
		}
  
		$(window).resize(function() {
			if ($(window).width() < 1200){	
				$('#slim_banner').width($(window).width()-20);
				$('#slim_banner').height($(window).width()/12);
			} else {
				$('#slim_banner').width(1200);
			$('#slim_banner').height(100);
			}
        })
    });
	</script>
	
   <script type="text/javascript">

        $("#slim_banner_url").click(function () {
            $.ajax({
                url: 'index.php?route=common/header/slimbannerhit',
                type: 'POST',
                dataType: 'html', // тип данных в ожидаемом ответе

            });
        });

    </script>

    <?php }?>

<?php $showbanner = 1; if ($showbanner == 2) {
		$this->language->load('checkout/checkout');
        if ($customer_group_id == 1 or $customer_group_id == 2 or $customer_group_id == '') { ?>

<?php if (substr_count($_SERVER['REQUEST_URI'], 'home')  or substr_count($_SERVER['REQUEST_URI'], 'category') or $_SERVER['REQUEST_URI'] == "/" and substr_count($title, 'Режим обслуживания') < 1){ ?>
<div style="height: 425px; width:350px">

<a href="index.php?route=information/information&information_id=18"><img src="./image/data/special/sliv_ending.jpg" /></a>

<?php

$time1 = mktime(2, 37, 50, 07, 16, 2016); //15 часов = 8 часов на сарвере
$time2 = time();
$ostatok_vremeni = ($time1 - $time2);
$interval_dney = 86400;
$interval_tovara = 864;
$ostatok_tovara = 3000 - ceil($ostatok_vremeni / $interval_tovara);
$ostatok_dney = ceil($ostatok_vremeni / $interval_dney);


?>
<div><a href="index.php?route=information/information&information_id=18" style="font-size:34px; position:relative; top:-305px; left:873px; color:#333;font-family:georgia;font-weight:bold;text-shadow: -1px -1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(0,0,0, 0.7);text-decoration:none;"><span style="color:##323230;text-shadow: -1px -1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(0,0,0, 0.7);"><?php echo $ostatok_tovara ?> из 3000</span></a></div>

<div><a href="index.php?route=information/information&information_id=18" style="font-size:76px; position:relative; top:-230px; left:933px; color:#333;font-family:georgia;font-weight:bold;text-shadow: -1px -1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(0,0,0, 0.7);text-decoration:none;"><span style="color:#fff;text-shadow: -1px -1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(255,255,255, 0.2), 1px 1px 1px rgba(0,0,0, 0.7);"><?php echo $ostatok_dney ?></span></a></div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>

<?php if ($categories) { 

	$CTGR=$categories;
	$i=0;
	foreach ($categories as $cat) {

		if ($cat['name']=="Новинки") $NOV_MNU=$i;
		if ($cat['name']=="Акция") $SALE_MNU=$i;
		if ($cat['name']=="Скидки") $DISC_MNU=$i;
		$i++;

	}
  if (isset($DISC_MNU) && isset($SALE_MNU)) {
	  $categories[$SALE_MNU]=$CTGR[$DISC_MNU];
    $categories[$DISC_MNU]=$CTGR[$SALE_MNU];
  }
?>
<div id="menu">
	<a id="r-menu-toggle" onclick="$('#r-menu').toggleClass('show');$(this).toggleClass('open-toggle');"></a>
  <ul id="r-menu">
		

    <?php foreach ($categories as $category) { 

		if ($category['name']=='Последние поступления' or $category['name']=='') {} else {

    ?>

    <li 

			<?php 

					if ($category['name']=="Финальная распродажа") {

						echo 'style="display:none!important;"';


					}


					if ($category['name']=="Оптовикам" or $category['name']=="Дропшиппинг") {
						echo 'style="display:none;"';
					}

					if ($category['name']=="Скидки") {
						echo 'style="color:#E31E24!important;"';
					}

					if ($category['menu_button_background_color'] or $category['menu_button_text_color']) {
						echo 'style=""';
					}


			?>


    >

			

			<?php 

				if ($category['children']) { ?>

				<a class="with-child" href="<?php echo $category['href']; 

			?>

			" 

			<?php 



					if ($category['name']=="Финальная распродажа") {

						echo 'style="display:none!important;"';


					}





			?>

			>

			<?php 

					$customer_group_id = $this->customer->getCustomerGroupId(); 

						if ($category['name']=="Финальная распродажа") {
	
							echo 'style="display:none!important;"';
	
	
						}

						if ($category['name']=="Акция" and $customer_group_id < 5) { 


							//echo "<img src='/image/data/special/akciya4.png'>";
							echo "Скидки";


						} else {


							if ($category['name']=="Зима" and $customer_group_id < 5) { 

								echo "<img src='/image/data/special/zima5.png'>";

							} else {

								echo $category['name'];

							}


						} 

			?>

	</a>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } else {?>

				<a href="<?php echo $category['href'];?>" 

					<?php 
					if ($category['menu_button_background_color'] or $category['menu_button_text_color']) {
                        echo 'style="';
                        if ($category['menu_button_background_color']) {
                            echo 'background-color: #' . $category['menu_button_background_color'] . '; padding: 18px 15px 19px 15px;line-height:16px;color:#fff;text-shadow:none;';
                        }
                        if ($category['menu_button_text_color']) {
                            echo 'color: #' . $category['menu_button_text_color'];
                        }
                        echo '"';
                    }
					
					
						if ($category['name']=="Скидки") {
							echo 'style="color:#E31E24!important;"';
						}

					?>

				>


						<?php 


							$customer_group_id = $this->customer->getCustomerGroupId(); 


							echo $category['name'];



						?>
	
					


				</a>


	<?php } ?>
    </li>
    <?php } ?>
    <?php } ?>
<!--<li><a href="sotrudnichestvo">Работа&nbsp;с&nbsp;Outmax</a></li>-->
<li><a href="bonusnaya_programma.html">Бонусная программа</a></li>
<li><a href="partnerskaya_programma.html">Реферальная система</a></li>
<li><a href="about">О&nbsp;нас</a></li>
<!--<li style="padding:0;border:none;"><a href="http://bizoutmax.ru/index.php?route=product/category&path=7607_11111" style="background:#5bc74f;padding: 18px 15px 19px 15px;line-height:16px;color:#fff;text-shadow:none;">Бестселлеры</a></li>-->
<!--<li style="padding:0;border:none;"><a href="super-rasprodazha" style="background:#ea242c;padding: 18px 15px 19px 15px;line-height:16px;color:#fff;text-shadow:none;">Ликвидация</a></li>-->
  </ul>

</div>
<?php } ?>
<div id="notification"></div>

<!--LiveInternet counter<script type="text/javascript">
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//--></script><!--/LiveInternet-->

<script type="text/javascript">
  /* function pingServer() {
      $.ajax({ url: location.href });
   }
   $(document).ready(function() {
      setInterval('pingServer()', 20000);
   });*/
</script>
