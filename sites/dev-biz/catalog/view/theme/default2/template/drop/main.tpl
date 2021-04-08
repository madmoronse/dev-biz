
  <?php echo $header ?>
    <div class="business-top">
      <div class="container">
        <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-10">
            <div class="business-title__container">
              <h1 class="business-title">ПРИГЛАШАЕМ К СОТРУДНИЧЕСТВУ<br> ОПТОВЫХ ПОКУПАТЕЛЕЙ И ПРОДАВЦОВ <br> РАБОТАЮЩИХ ПО СИСТЕМЕ ДРОПШИППИНГА</h1>
            </div>
            <div class="business-description__container">
              <div class="business-description">Став нашим партнёром, вы сможете не только открыть свое дело с нуля, но и развивать уже существующий бизнес в новом направлении</div>
            </div>
            <div class="row">
              <div class="business-more__container">
                <div class="col-lg-1 col-md-1"></div>
                <div class="col-lg-4 col-md-4"><a class="button button--business-more" href="<?php echo $drop_link ?>">Подробнее о Дропшиппинге</a>
                  <ul class="business__list">
                    <li class="business__list-item">Бизнес без вложений</li>
                    <li class="business__list-item">Ежедневная отправка товара</li>
                    <li class="business__list-item">Высокая конкурентноспособность</li>
                  </ul>
                </div>
                <div class="col-lg-2 col-md-1"></div>
                <div class="col-lg-4 col-md-4"><a class="button button--business-more" href="<?php echo $opt_link ?>">Подробнее об оптовых закупках</a>
                  <ul class="business__list">
                    <li class="business__list-item">Минимальная стоимость заказа от 15 000 руб.</li>
                    <li class="business__list-item">Возможность закупок без размерных рядов</li>
                    <li class="business__list-item">Низкая стоимость доставки</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="business-profit">
      <div class="container">
        <div class="business-profit__title">Почему с нами удобно работать?</div>
        <div class="business-profit__icon-container">
          <div class="row">
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit1.png);"></div>
                <div class="business-profit__icon-text">Вы приобретаете весь интересующий Вас товар в одном месте</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit2.png);"></div>
                <div class="business-profit__icon-text">Наш персональный менеджер будет вести историю взаиморасчетов и контролировать Ваши заказы от момента приема до получения</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit3.png);"></div>
                <div class="business-profit__icon-text">Для постоянных клиентов предусмотрены индивидуальные условия работы</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit4.png);"></div>
                <div class="business-profit__icon-text">Вся наша продукция соответствует современным стандартам</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit5.png);"></div>
                <div class="business-profit__icon-text">Наши коллекции формируются с учетом пожеланий клиентов</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="business-profit__icon-item">
                <div class="business-profit__icon-img" style="background-image: url(/catalog/view/theme/default2/image/new-business/icon/profit6.png);"></div>
                <div class="business-profit__icon-text">Вы заранее оповещены о предстоящих поступлениях товара</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form id="sendAjaxForm" action="<?php echo $send?>" method="POST" class="business-cooperation">
      <div class="container">
        <div class="business-cooperation__title">Хотите узнать больше и стать нашим партнёром?</div>
        <div class="business-cooperation__top-text">Выберите вид сотрудничества:</div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-2">
            <input class="filter__checkbox" id="drop" name="type" value="1" type="radio" checked="">
            <label class="filter__label" for="drop">Дропшиппинг</label>
          </div>
          <div class="col-md-2">
            <input class="filter__checkbox" id="opt" name="type" value="2" type="radio">
            <label class="filter__label" for="opt">Оптовые закупки</label>
          </div>
          <div class="col-md-4"></div>
        </div>
        <div class="business-cooperation__input-container">
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
              <div class="input__form-element">
                <label class="label-text" for="user-name">Имя<span class="label-text--red">*</span></label>
                <input class="input-text input--required" id="user-name" name="name" type="text">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input__form-element">
                <label class="label-text" for="user-phone">Телефон<span class="label-text--red">*</span></label>
                <input class="input-text input--phone input--required" id="user-phone" name="tel" type="text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
              <div class="input__form-element">
                <label class="label-text" for="user-mail">E-mail<span class="label-text--red">*</span></label>
                <input class="input-text input--mail input--required" id="user-mail" name="email" type="text">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input__form-element">
                <label class="label-text" for="user-city">Город<span class="label-text--red">*</span></label>
                <input class="input-text input--required" id="user-city" name="city" type="text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <div class="label-text">Ваше сообщение<span class="label-text--red">*</span></div>
              <textarea class="input-text input-text--textarea input--required" name="text"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <input class="button button--red button--left button--text-upper" type="submit" value="Отправить заявку">
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="business-managers">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="business-managers__text">Наши менеджеры</div>
          </div>
          <div class="manager-container">
            <div class="col-lg-2"></div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="manager-item">
                <div class="row">
                  <div class="col-md-5 col-sm-5 col-xs-5"><img src="/catalog/view/theme/default2/image/new-business/manager/0.png" width="83" height="83" alt="photo manager"></div>
                  <div class="col-md-7 col-sm-7 col-xs-7">
                    <p class="manager-item__head">Никита</p>
                    <p class="manager-item__text">Дропшипинг</p>
                    <div class="manager-item__link-container"><a href="tel:+79535983161" class="manager-item__text manager-item__head--number manager-item__phone">+ 7 (953) 598-31-61</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text" href="https://wa.me/79535983161">WhatsApp</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text manager-item__text--social-vk" href="https://vk.com/id226685002">Вконтакте</a></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="manager-item">
                <div class="row">
                  <div class="col-md-5 col-sm-5 col-xs-5"><img src="/catalog/view/theme/default2/image/new-business/manager/2.png" width="83" height="83" alt="photo manager"></div>
                  <div class="col-md-7 col-sm-7 col-xs-7">
                    <p class="manager-item__head">Татьяна</p>
                    <p class="manager-item__text">Дропшипинг</p>
                    <div class="manager-item__link-container"><a href="tel:+79082049680" class="manager-item__text manager-item__head--number manager-item__phone">+ 7 (908) 204-96-80</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text" href="https://wa.me/79082049680">WhatsApp</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text manager-item__text--social-vk" href="https://vk.com/id425855021">Вконтакте</a></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="manager-item">
                <div class="row">
                  <div class="col-md-5 col-sm-5 col-xs-5"><img src="/catalog/view/theme/default2/image/new-business/manager/1.png" width="83" height="83" alt="photo manager"></div>
                  <div class="col-md-7 col-sm-7 col-xs-7">
                    <p class="manager-item__head">Алеся</p>
                    <p class="manager-item__text">Оптовые закупки</p>
                    <div class="manager-item__link-container"><a href="tel:+79504189114" class="manager-item__text manager-item__head--number manager-item__phone">+ 7 (950) 418-91-14</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text" href="https://wa.me/79504189114">WhatsApp</a></div>
                    <div class="manager-item__link-container"><a class="manager-item__text manager-item__text--social-vk" href="https://vk.com/id279411202">Вконтакте</a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo $footer ?>
