<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<?php echo $content_top; ?>
  	<div class="breadcrumb">
    	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
    		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    	<?php } ?>
  	</div>
  	<h1><?php echo $heading_title; ?>
    	<?php if ($weight) { ?>
    	&nbsp;(<?php echo $weight; ?>)
    	<?php } ?>
  	</h1>

  	<link rel="stylesheet" href="/catalog/view/theme/default2/stylesheet/about.css">

	<div class="about-page">
		<div class="top_banner"></div>
		<div class="txt_area">
			<div class="descr" style="width:740px;padding-top:calc(175px - 90px);margin-left:calc(50% - 370px);">
				<p>В основе любой компании имеется хорошо расказанная история. Такая история есть и у нас.</p>
				<p><b>Outmaxshop</b> - это быстро развивающийся интернет-магазин брендовой одежды и обуви.</p>
				<p>Мы делаем покупки в интернете доступными и удобными для вас!</p>
			</div>
		</div>
		<div class="mix_area">
			<div class="ls"><div class="descr" style="width:300px;margin-top:calc(200px - 45px);margin-left:calc(50% - 150px);"><p>Более <b>5 000</b> человек по всей России<br>ежедневно заходит на наш сайт.<br>И мы рады помочь каждому!</p></div></div>
			<div class="rs" style="background: 50% 50%/cover no-repeat;background-image:url('/catalog/view/theme/default2/image/about/about1.jpg');"></div>
			<div class="clear"></div>
		</div>
		<div class="mix_area">
			<div class="ls" style="background: 50% 50%/cover no-repeat;background-image:url('/catalog/view/theme/default2/image/about/about2.jpg');"></div>
			<div class="rs"><div class="descr" style="width:310px;margin-top:calc(200px - 70px);margin-left:calc(50% - 155px);"><p>История начинается <b>с 2012 года</b>,<br>когда был открыт наш первый офис.</p><p>Теперь мы одна из ведущих площадок<br>не только в своем регионе, но и в СНГ.</p></div></div>
			<div class="clear"></div>
		</div>
		<div class="middle_banner"></div>
		<div class="txt_area">
			<div class="descr" style="width:1000px;padding-top:calc(175px - 110px);margin-left:calc(50% - 500px);">
				<p><b>Наша команда всегда</b> ищет умных и активных людей для сотрудничества.</p>
				<p>Если ты являешься владельцем бизнеса, или хочешь стать им, <b>Outmaxshop готов сотрудничать</b> и помогать тебе в этом.</p>
				<p>Мы демократизировали технологии для малого бизнеса, создавая простые решения в сотрудничестве с нашими партнерами.</p>
				<p>Это позволяет эффективно развиваться обеим сторонам.</p>
			</div>
		</div>
		<div class="mix_area">
			<div class="ls" style="background: 50% 50%/cover no-repeat;background-image:url('/catalog/view/theme/default2/image/about/about3.jpg');"></div>
			<div class="rs"><div class="descr" style="width:310px;margin-top:calc(200px - 90px);margin-left:calc(50% - 155px);"><p>От тебя требуется сосредоточиться<br>на собственном деле,<br>и общении с твоими заказчиками.<br>Об остальном позаботимся мы.<br>Узнать больше о нашем предложении<br>ты можешь <a href="/sotrudnichestvo/">тут</a>.</p></div></div>
			<div class="clear"></div>
		</div>
		<div class="txt_area">
			<div class="descr" style="width:500px;padding-top:calc(175px - 80px);margin-left:calc(50% - 250px);">
				<p><H3>Спасибо, что уделил время для знакомства с нами</H3></p>
				<p>Ты всегда можешь написать нам интересующий тебя вопрос,<br>или подписаться на наши новости и обновления!</p>
			</div>
		</div>

	</div>



    <div class="buttons">
        <div class="right"><a href="http://bizoutmax.ru/" class="button">На главную</a></div>
    </div>
  	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>