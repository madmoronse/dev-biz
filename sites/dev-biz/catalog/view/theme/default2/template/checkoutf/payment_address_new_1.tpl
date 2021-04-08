<div>
<div id="payment-new">
      <form class="payment-form" id="checkout-form">
        <div class="payment-form__item">
          <span class="required">* </span><label for="firstname"><?php echo $entry_enter_fio; ?></label>
          <input type="text" name="firstname" autocomplete="off" id="firstname" value="<?= $lastname." ".$firstname." ".$middlename ?>" class="large-field" />
          <p class="help-block"><?php echo $text_fio; ?></p>
        </div>
        <div class="payment-form__item">
          <span class="required">* </span><label for="email"><?php echo $entry_enter_email; ?></label>
          <input type="text" autocomplete="off" id="email" name="email" value="<?= $email ?>" class="large-field" />
          <p class="help-block"><?php echo $text_email; ?></p>
        </div>
        <div class="payment-form__item">
          <span class="required">* </span><label for="telephone"><?php echo $entry_enter_telephone; ?></label>
          <input type="text" autocomplete="off" id="telephone" name="telephone" value="<?= $telephone ?>" class="large-field" />
          <p class="help-block"><?php echo $text_telephone; ?></p>
        </div>
      </form>
      <div class="payment-information">
        <div class="checkout-products">
          <?
            $i = 0;
            foreach($products as $product){
              $i++;
              if($i > 3) break;
              ?>
              <div class="checkout-products__item">
                <a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt=""></a>
                <div class="description">
                  <div class="title"><?=$product['name']?></div>
                  <div class="quantity"><?=$product['quantity']?> шт.</div>
                </div>
                <div class="cost"><?=$product['total']?></div>
              </div>
              <?
            }
          ?>
          <div class="checkout-products__total">
            <p>
              Сумма заказа: <span class="total"><?=$total['text'] ?></span>
              <span class="info">Вам доступна бесплатная доставка</span>
            </p>
          </div>
        </div>
      </div>
      <script>
        $('#telephone').inputmask({"mask": "+7 (999) 999-9999"});
      </script>
</div>
</div>
