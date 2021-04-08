<?php echo $header ?>
    <div class="top-image top-image--opt" style="background-image: url(/catalog/view/theme/default2/image/new-business/opt.png);">
      <div class="top-image__cover"></div>
      <div class="top-image__text-container">
        <div class="container">
          <div class="row">
            <div class="drop-top__text">Выходи на новый уровень продаж с помощью оптовых закупок на специальных условиях</div>
          </div>
        </div>
      </div>
    </div>
    <div class="drop-container">
      <div class="container">
        <div class="drop-container__item">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="drop-container__title">Минимальный стартовый капитал</div>
              <div class="drop-container__description">Минимальная сумма заказа от 15.000 руб.</div>
              <div class="drop-container__text">Весь ассортимент представленный у нас на сайте,<br />
                всегда в наличии на нашем складе
              </div>
            </div>
          </div>
        </div>
        <div class="drop-container__item">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="drop-container__title">Весь товар в наличие</div>
              <div class="drop-container__description">Больше не нужно ждать</div>
              <div class="drop-container__text">Весь ассортимент представленный нами всегда есть в наличии
                <br>
                на наших складах и в нужном Вам объеме
              </div>
            </div>
          </div>
        </div>
        <div class="drop-container__item">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="drop-container__title">Возможность закупок без размерных рядов</div>
              <div class="drop-container__description">Покупай что хочешь и в любом количестве</div>
              <div class="drop-container__text">Наши условия дают возможность
                <br>
                выкупать товар в нужном Вам размере и количестве
              </div>
            </div>
          </div>
        </div>
        <div class="drop-container__item">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="drop-container__title">Постоянное обновление товара</div>
              <div class="drop-container__description">Мы знаем желания своего клиента, а значит и Вашего</div>
              <div class="drop-container__text">Еженедельное пополнение ходового товара и поступление новых моделей с нашей стороны,<br />не оставит Вас без заказов
              </div>
            </div>
          </div>
        </div>
        <div class="drop-container__item">
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="drop-container__title">Дешевая и быстрая доставка</div>
              <div class="drop-container__description">Самые выгодные условия</div>
              <div class="drop-container__text">Наш товар доставляется Вам по самым низким тарифам
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="drop-connection">
      <form id="sendAjaxForm" action="<?php echo $send?>" method="POST">
        <input type="hidden" name="type" value="2" />
        <div class="container">
          <div class="drop-connection__title">Хотите попробовать</div>
          <div class="drop-connection__description">Заполните простую форму заявки на сотрудничество или свяжитесь с одним из наших менеджеров</div>
          <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <div class="drop-connection__container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="input__form-element">
                      <label class="label-text" for="user-name">Имя<span class="label-text--red">*</span></label>
                      <input class="input-text input--name input--required" id="user-name" name="name" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input__form-element">
                      <label class="label-text" for="user-name">E-mail<span class="label-text--red">*</span></label>
                      <input class="input-text input--mail input--required" id="user-name" name="email" type="text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input__form-element">
                      <label class="label-text" for="user-name">Телефон<span class="label-text--red">*</span></label>
                      <input class="input-text input--phone input--required" id="user-name" name="tel" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input__form-element">
                      <label class="label-text" for="user-name">Город<span class="label-text--red">*</span></label>
                      <input class="input-text input--required" id="user-name" name="city" type="text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="label-text">Ваше сообщение<span class="label-text--red">*</span></div>
                    <textarea class="input-text input-text--textarea input--required" name="text"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1"></div>
            <div class="col-lg-3 col-md-5 col-sm-5">
              <div class="drop-connection__manager-container drop-connection__manager-container--opt">
                <div class="row">
                  <div class="drop-connection__manager">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-5"><img src="/catalog/view/theme/default2/image/new-business/manager/1.png" width="83" height="83" alt="photo manager"></div>
                        <div class="col-md-7 col-sm-7 col-xs-7">
                            <p class="manager-item__head">Алеся</p>
                            <p class="manager-item__text">Оптовые закупки</p>
                            <div class="manager-item__link-container"><a href="tel:+79504189114" class="manager-item__text manager-item__head--number manager-item__phone">+ 7 (950) 418-91-14</a></div>
                            <div class="manager-item__link-container"><a class="manager-item__text" href="https://wa.me/79504189114">WhatsApp</a> /<a class="manager-item__text manager-item__text--social" href="https://vk.com/id279411202">Вконтакте</a></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-2 col-sm-2"></div>
            <div class="col-lg-3 col-md-4 col-sm-4">
              <input class="button button--red button--left button--text-upper" type="submit" value="Отправить заявку">
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php echo $footer ?>