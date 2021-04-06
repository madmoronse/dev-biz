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
  	<link rel="stylesheet" href="/catalog/view/theme/default2/stylesheet/business.css">
  	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
  	<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
	<script src="/catalog/view/theme/default2/assets/jquery/jquery.nice-select.js" type="text/javascript"></script>
	<script src="/catalog/view/theme/default2/assets/jquery/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
	<script src="/catalog/view/theme/default2/assets/jquery/jquery.noty.packaged.min.js" type="text/javascript"></script>
	<script src="/catalog/view/theme/default2/assets/jquery/scripts.js" type="text/javascript"></script>
  	<div class="wrapper business-wrapper">
        <img class="business-image" src="/catalog/view/theme/default2/image/business/main.jpg" alt="">
        <p class="business-description">Outmaxshop.ru приглашает к сотрудничеству оптовых покупателей и продавцов работающих по системе дропшиппинга.</p>
        <p class="business-subdescription">Став нашим партнёром, вы сможете не только открыть свое дело с нуля, но и развивать уже существующий бизнес в новом направлении.</p>
        <div class="business-block business-featues">
            <div class="business-featues__title">
                Наши преимущества:
            </div>
            <div class="business-featues__elements">
                <div class="business-featues__element">
                    <a class="title">Быстрая доставка</a>
                    <div class="description">Своевременная доставка клиенту возможна уже на следующий день.<br><br>
					Возможность доставки в любой регион России в кратчайшие сроки.<br><br>
					Мы гарантируем качество товара и упаковки, соблюдения сроков передачи заказа в службу доставки.</div>
                </div>
                <div class="business-featues__element">
                    <a class="title">Ассортимент</a>
                    <div class="description">Outmaxshop.ru представляет огромный ассортимент актуальных моделей спортивной одежды, обуви и аксессуаров известных мировых брендов, таких как Adidas, Asics, Nike, New 	Balance...<br><br>
					Наши товары поставляются напрямую от фабрик производителей, что позволяет устанавливать максимально низкие цены.<br><br>
					Представленный на нашем сайте товар в наличии.</div>
                </div>
                <div class="business-featues__element">
                    <a class="title">Выгодные условия</a>
                    <div class="description">Возможность выбирать любые товары и любые размеры в заказы, представленные на сайте outmaxshop.ru. Не обязательно приобретать строго линейку размеров. <br><br>
					Работая с нами, Вы будете получать с клиента от 500 до 3000 руб с заказа, за счет наших низких цен на товары для Вас.<br><br>
					За счет нашего качественного товара, Ваши клиенты будут оставаться с Вами и будут заказывать снова, а так же советовать Вас своим знакомым.</div>
                </div>
            </div>
        </div>
        <div class="business-block business-form">
            <p class="business-description">Хотите узнать больше и стать нашим партнёром?</p>
            <p class="business-subdescription">Заполните простую форму ниже<br> и мы свяжемся с вами в кратчайшие сроки.</p>
            <form action="" id="dropForm">
                <div class="form-group form-group-inline">
                    <label for="type_select">Вид сотрудничества</label>
                    <select class="for-niceselect" name="type_select" id="type_select">
                        <option value="1">Дропшиппинг</option>
                        <option value="2" selected>Оптовые закупки</option>
                    </select>
                </div>
                <div class="form-group form-group-inline form-group-multi">
                    <div class="group-element name floating">
                        <input type="text" name="name" id="name" class="floating-inp" required>
                        <label for="name">Как к вам обратиться? <span class="red">•</span></label>
                    </div>
                    <div class="group-element tel floating">
                        <input type="tel" name="tel" id="tel" class="floating-inp" required>
                        <label for="tel">Телефон <span class="red">•</span></label>
                    </div>
                    <div class="group-element email floating">
                        <input type="email" name="email" id="email" class="floating-inp" required>
                        <label for="email">Email <span class="red">•</span></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="group-element name floating">
                        <textarea name="text" id="text" class="floating-inp" required rows="5"></textarea>
                        <label for="text">Сообщение <span class="red">•</span></label>
                    </div>
                </div>
                <button class="submit-btn" id="dropSubmit">Отправить</button>
            </form>
        </div>
        <p class="business-subdescription">Или свяжитесь с нашими менеджерами.</p>
        <div class="business-managers">
            <div class="business-manager">
                <div class="business-manager__avatar"><img src="/catalog/view/theme/default2/image/business/av1.jpg" alt=""></div>
                <div class="business-manager__info">
                    <div class="name">Никита</div>
                    <div class="position">Дропшиппинг</div>
                    <div class="business-manager__contacts">
                        <div class="m-contact__element m-contact__tel">
                            <div class="c-icons">
                                <a href="#" class="c-icons__element c-icons__tel"></a>
                                <a href="#" class="c-icons__element c-icons__wa"></a>
                            </div>
                            +7 (953) 598-31-61
                        </div>
                        <div class="m-contact__element m-contact__vk">
                            <div class="c-icons">
                                <a href="https://vk.com/id226685002" target="_blank" class="c-icons__element c-icons__vk"></a>
                            </div>
                            <a href="https://vk.com/id226685002" target="_blank">id226685002</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="business-manager">
                <div class="business-manager__avatar"><img src="/catalog/view/theme/default2/image/business/av3.jpg" alt=""></div>
                <div class="business-manager__info">
                    <div class="name">Татьяна</div>
                    <div class="position">Дропшиппинг</div>
                    <div class="business-manager__contacts">
                        <div class="m-contact__tel">
                            <div class="c-icons">
                                <a href="#" class="c-icons__element c-icons__tel"></a>
                                <a href="#" class="c-icons__element c-icons__wa"></a>
                            </div>
                            +7 (908) 204-96-80
                        </div>
                        <div class="m-contact__vk">
                            <div class="c-icons">
                                <a href="https://vk.com/id425855021" target="_blank" class="c-icons__element c-icons__vk"></a>
                            </div>
                            <a href="https://vk.com/id425855021" target="_blank">id425855021</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="business-manager">
                <div class="business-manager__avatar"><img src="/catalog/view/theme/default2/image/business/av2.jpg" alt=""></div>
                <div class="business-manager__info">
                    <div class="name">Олеся</div>
                    <div class="position">Оптовые закупки</div>
                    <div class="business-manager__contacts">
                        <div class="m-contact__tel">
                            <div class="c-icons">
                                <a href="#" class="c-icons__element c-icons__tel"></a>
                                <a href="#" class="c-icons__element c-icons__wa"></a>
                            </div>
                            +7 (950) 418-91-14
                        </div>
                        <div class="m-contact__vk">
                            <div class="c-icons">
                                <a href="https://vk.com/id279411202" target="_blank" class="c-icons__element c-icons__vk"></a>
                            </div>
                            <a href="https://vk.com/id279411202" target="_blank">id279411202</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="buttons">
        <div class="right"><a href="http://bizoutmax.ru/" class="button">На главную</a></div>
    </div>
  	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>