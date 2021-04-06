<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="/catalog/view/theme/default2/stylesheet/new-business.css?v3">
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
</head>
<body>
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
<header class="header--business">
    <div class="menu--business">
        <div class="container">
        <div class="business-top__text"><a class="text--head-top" href="<?php echo $home ?>"> Перейти на OUTMAXSHOP.RU</a></div>
        </div>
    </div>
    <div class="nav">
        <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-6">
            <a class="nav__logo-link" href="/sotrudnichestvo"><img src="/catalog/view/theme/default2/image/new-business/logo-business.png" alt="Outmax"></a>
            </div> 
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="nav__number">
                <div class="nav__number-text">+7 (953) 598-31-61 Дропшиппинг</div>
                <div class="nav__number-text">+7 (950) 418-91-14 Оптовые закупки</div>
            </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-md-4 col-sm-6">
            <div class="button--business-container">
                <a class="button--business" type="text" href="#sendAjaxForm">Подать заявку на регистрацию</a>
            </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="button-container">
                <a class="button button--red button--text-upper" href="<?php echo $account ?>">Вход</a>
                </div>
            </div>
        </div>
        </div>
    </div>
</header>