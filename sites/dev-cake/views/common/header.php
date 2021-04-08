<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/style.css">
    <title> <?php echo $title; ?> </title>
</head>
<body>
	<nav class="top-right open" style="display: none;">
		<?php foreach ($category as $value): ?>
		  <a class="disc <?php echo 'l' . $value['id']; ?>">
		    <div><?php echo $value['title']; ?></div>
		  </a>
  		<?php endforeach ?>
		<a class="disc l7 toggle">
		</a>
	</nav>
<div id="head">
	<div class="header">
		<div class="logo">
			<a href="/"><img src="/image/logo.png"></a>
		</div>
		<div class="menu">
			<ul>
				<?php foreach ($category as $value): ?>
					<a href="#">
						<li><?php echo $value['title']; ?></li>
					</a>
				<?php endforeach ?>
					<a href="#">
						<li>Отзывы</li>
					</a>
					<a href="#">
						<li>Контакты</li>
					</a>
			</ul>
		</div>
		<div class="cart">
			<a onclick="getCart();"><img src="/image/icons/basket_96252.png" width="18" height="18"></a>
			<span id="cart-total"><?php echo $countToCart; ?></span>
		</div>
	</div>
</div>
<div class="social" style="display: none;">
	<img src="../../image/icons/telegram.png">
	<img src="../../image/icons/instagram.png">
	<img src="../../image/icons/whatsapp.png">
</div>
<div id="cart" class="popup">
    <div class="popup_body">
        <div class="popup_content popup__content-cart">
        </div>
    </div>
</div>



