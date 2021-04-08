<?php echo $header; ?>
<div class="intro">
	<div class="intro-content">
		 <div class="left-content">
            <div class="intro-content">
                    <div class="intro-text">
                        <h1>Торт - это всегда праздник!</h1><br>
                        <p>
                            Торт всегда был одним из самых наилучших лакомств для любителей десертов.
                        </p><br>
                        <div class="button-block">
                            <a href="#form">
                                <button class="button btn-1">Посмотреть каталог</button>
                            </a>
                        </div>
                    </div>
            </div>
        </div>
	</div>
</div>
<div class="wrapper">
    <div class="category section">
    	<div class="item">
            <?php foreach ($categories as $value): ?>
                <a href="products/getList/<?php echo $value['id']; ?>" class="category-it-link"><div class="category-it <?php echo 'it-' . $value['id']; ?>"><h1><?php echo $value['title']; ?></h1></div></a>
            <?php endforeach ?>
        </div>
    </div>
    <div class="section-2 section">

    </div>
    <div class="section-3 section">

    </div>
</div>
<?php echo $footer; ?>
