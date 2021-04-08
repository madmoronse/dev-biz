<?php echo $header; ?>

<div id="product-content">
	<?php foreach ($product as $value): ?>
		<div class="product">
			<div class="product-left">
				<div class="product__image"><img src="/image/products/<?php echo $value['image']; ?>" width="128" height="128"></div>
			</div>
			<div class="product-right">
				<div class="product__title"><h1><?php echo $value['title']; ?></h1></div>
				<div class="product__description"><?php echo $value['description']; ?></div>
				<div class="product__price"><?php echo $value['price']; ?><span> руб/кг</span></div>
				<div class="product__weight">
					<span>Вес: </span>
					<input type="number" name="weight">
				</div>
				<a onclick="addToCart(<?php echo $value['id']; ?>);"><button style="color: black; padding: 5px;">Добавить в корзину</button></a>
			</div>
		</div>
	<?php endforeach ?>
</div>


<div id="popup" class="popup">
    <div class="popup_body">
        <div class="popup_content">
            <div class="popup_text">
                Товар добавлен в корзину!
            </div>
            <div class="popup_close close-popup">Закрыть</div>
        </div>
    </div>
</div>
<?php echo $footer; ?>