<?php echo $header ?>
<hr>
<div class="business-container">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <h1>Прокси API</h1>
                <h2>Список методов</h2>
                <h3>Получение токена доступа</h3>
                <p><strong>Метод:</strong> http://bizoutmax.ru/index.php?route=drop/proxy/token</p>
                <p>
                    <strong>Запрос:</strong>
                    <pre>
POST

grant_type=password
username={логин пользователя}
password={пароль}</pre>
                </p>
                <p>
                <strong>Ответ:</strong>
                <pre>
{
    "access_token": "string",
    "token_type": "bearer",
    "expires_in": "integer"
}</pre>
                </p>
                <p>
                    <strong>Дополнительно:</strong><br>
                    Время жизни токена указано в поле "expires_in" в секундах, после чего необходимо получить новый токен.
                    Включаем токен в последующие запросы к API через заголовок X-Authorization.
                    Пример:
                    <pre>
X-Authorization: Bearer {token}</pre>
                </p>
                <h3>Получение истории по заказу (необходима авторизация)</h3>
                <p>Детально <a href="https://confluence.cdek.ru/pages/viewpage.action?pageId=15616129#id-%D0%9F%D1%80%D0%BE%D1%82%D0%BE%D0%BA%D0%BE%D0%BB%D0%BE%D0%B1%D0%BC%D0%B5%D0%BD%D0%B0%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%BC%D0%B8(v1.5)-4.10.Status%D0%9E%D1%82%D1%87%D0%B5%D1%82%22%D0%A1%D1%82%D0%B0%D1%82%D1%83%D1%81%D1%8B%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%BE%D0%B2%22" target="_blank">документация СДЭК</a></p>
                <p><strong>Метод:</strong> http://bizoutmax.ru/index.php?route=drop/proxy/track</p>
                <p>
                    <strong>Запрос:</strong><br>
                    Передаем в POST теле XML запрос к API СДЭК, но без указания атрибутов Account, Secure, Date - они будут добавлены автоматически.
                    Пример запроса в СДЭК:
<pre>
&#x3C;?xml version=&#x22;1.0&#x22; encoding=&#x22;UTF-8&#x22;?&#x3E;
&#x3C;StatusReport ShowHistory=&#x22;1&#x22;&#x3E;
    &#x3C;Order DispatchNumber=&#x22;100000000&#x22; /&#x3E;
&#x3C;/StatusReport&#x3E;
</pre>
                <p>
                    <strong>Ответ:</strong><br>
                    Смотреть документацию СДЭК. В случае ошибки валидации входных параметров сервисом проксирования будет возвращен JSON.<br>
                    Пример ответа от СДЭК:
<pre>
&#x3C;?xml version=&#x22;1.0&#x22; encoding=&#x22;UTF-8&#x22;?&#x3E;
&#x3C;StatusReport DateFirst=&#x22;2000-12-31T17:00:00+00:00&#x22; DateLast=&#x22;2019-01-14T06:47:23+00:00&#x22; &#x3E;
    &#x3C;Order ActNumber=&#x22;OM-XXXXXX&#x22; Number=&#x22;OMOM-XXXXXX&#x22; DispatchNumber=&#x22;100000000&#x22;  DeliveryDate=&#x22;2018-11-17T13:29:00+01:00&#x22; RecipientName=&#x22;&#x418;&#x432;&#x430;&#x43D;&#x43E;&#x432; &#x418;&#x432;&#x430;&#x43D; &#x418;&#x432;&#x430;&#x43D;&#x43E;&#x432;&#x438;&#x447;&#x22; &#x3E;
        &#x3C;Status Date=&#x22;2018-11-17T03:29:57+00:00&#x22; Code=&#x22;4&#x22; Description=&#x22;&#x412;&#x440;&#x443;&#x447;&#x435;&#x43D;&#x22; CityCode=&#x22;288&#x22; CityName=&#x22;&#x412;&#x43B;&#x430;&#x434;&#x438;&#x432;&#x43E;&#x441;&#x442;&#x43E;&#x43A;&#x22;&#x3E;
            &#x3C;State Date=&#x22;2018-11-09T06:31:43+00:00&#x22; Code=&#x22;1&#x22; Description=&#x22;&#x421;&#x43E;&#x437;&#x434;&#x430;&#x43D;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-10T09:10:04+00:00&#x22; Code=&#x22;13&#x22; Description=&#x22;&#x41F;&#x440;&#x438;&#x43D;&#x44F;&#x442; &#x43D;&#x430; &#x441;&#x43A;&#x43B;&#x430;&#x434; &#x442;&#x440;&#x430;&#x43D;&#x437;&#x438;&#x442;&#x430;&#x22; CityCode=&#x22;54137&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A; (&#x41D;&#x430; &#x41A;&#x420;&#x410;&#x421;&#x422;&#x42D;&#x426;)&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T05:20:04+00:00&#x22; Code=&#x22;19&#x22; Description=&#x22;&#x412;&#x44B;&#x434;&#x430;&#x43D; &#x43D;&#x430; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43A;&#x443; &#x432; &#x433;.-&#x442;&#x440;&#x430;&#x43D;&#x437;&#x438;&#x442;&#x435;&#x22; CityCode=&#x22;54137&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A; (&#x41D;&#x430; &#x41A;&#x420;&#x410;&#x421;&#x422;&#x42D;&#x426;)&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T08:15:52+00:00&#x22; Code=&#x22;3&#x22; Description=&#x22;&#x41F;&#x440;&#x438;&#x43D;&#x44F;&#x442; &#x43D;&#x430; &#x441;&#x43A;&#x43B;&#x430;&#x434; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x438;&#x442;&#x435;&#x43B;&#x44F;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T08:24:44+00:00&#x22; Code=&#x22;19&#x22; Description=&#x22;&#x412;&#x44B;&#x434;&#x430;&#x43D; &#x43D;&#x430; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43A;&#x443; &#x432; &#x433;.-&#x442;&#x440;&#x430;&#x43D;&#x437;&#x438;&#x442;&#x435;&#x22; CityCode=&#x22;54137&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A; (&#x41D;&#x430; &#x41A;&#x420;&#x410;&#x421;&#x422;&#x42D;&#x426;)&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T08:24:45+00:00&#x22; Code=&#x22;3&#x22; Description=&#x22;&#x41F;&#x440;&#x438;&#x43D;&#x44F;&#x442; &#x43D;&#x430; &#x441;&#x43A;&#x43B;&#x430;&#x434; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x438;&#x442;&#x435;&#x43B;&#x44F;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T09:04:57+00:00&#x22; Code=&#x22;6&#x22; Description=&#x22;&#x412;&#x44B;&#x434;&#x430;&#x43D; &#x43D;&#x430; &#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43A;&#x443; &#x432; &#x433;.-&#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x438;&#x442;&#x435;&#x43B;&#x435;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-11T09:06:46+00:00&#x22; Code=&#x22;7&#x22; Description=&#x22;&#x421;&#x434;&#x430;&#x43D; &#x43F;&#x435;&#x440;&#x435;&#x432;&#x43E;&#x437;&#x447;&#x438;&#x43A;&#x443; &#x432; &#x433;.-&#x43E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x438;&#x442;&#x435;&#x43B;&#x435;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-14T00:59:30+00:00&#x22; Code=&#x22;21&#x22; Description=&#x22;&#x41E;&#x442;&#x43F;&#x440;&#x430;&#x432;&#x43B;&#x435;&#x43D; &#x432; &#x433;.-&#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x442;&#x435;&#x43B;&#x44C;&#x22; CityCode=&#x22;278&#x22; CityName=&#x22;&#x41A;&#x440;&#x430;&#x441;&#x43D;&#x43E;&#x44F;&#x440;&#x441;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-17T03:16:56+00:00&#x22; Code=&#x22;9&#x22; Description=&#x22;&#x412;&#x441;&#x442;&#x440;&#x435;&#x447;&#x435;&#x43D; &#x432; &#x433;.-&#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x442;&#x435;&#x43B;&#x435;&#x22; CityCode=&#x22;288&#x22; CityName=&#x22;&#x412;&#x43B;&#x430;&#x434;&#x438;&#x432;&#x43E;&#x441;&#x442;&#x43E;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-17T03:24:06+00:00&#x22; Code=&#x22;12&#x22; Description=&#x22;&#x41F;&#x440;&#x438;&#x43D;&#x44F;&#x442; &#x43D;&#x430; &#x441;&#x43A;&#x43B;&#x430;&#x434; &#x434;&#x43E; &#x432;&#x43E;&#x441;&#x442;&#x440;&#x435;&#x431;&#x43E;&#x432;&#x430;&#x43D;&#x438;&#x44F;&#x22; CityCode=&#x22;288&#x22; CityName=&#x22;&#x412;&#x43B;&#x430;&#x434;&#x438;&#x432;&#x43E;&#x441;&#x442;&#x43E;&#x43A;&#x22; /&#x3E;
            &#x3C;State Date=&#x22;2018-11-17T03:29:57+00:00&#x22; Code=&#x22;4&#x22; Description=&#x22;&#x412;&#x440;&#x443;&#x447;&#x435;&#x43D;&#x22; CityCode=&#x22;288&#x22; CityName=&#x22;&#x412;&#x43B;&#x430;&#x434;&#x438;&#x432;&#x43E;&#x441;&#x442;&#x43E;&#x43A;&#x22; /&#x3E;
        &#x3C;/Status&#x3E;
        &#x3C;Reason Code=&#x22;&#x22; Description=&#x22;&#x22; Date=&#x22;&#x22;&#x3E;&#x3C;/Reason&#x3E;
        &#x3C;DelayReason Code=&#x22;&#x22; Description=&#x22;&#x22; Date=&#x22;&#x22; &#x3E;&#x3C;/DelayReason&#x3E;
    &#x3C;/Order&#x3E;
&#x3C;/StatusReport&#x3E;
</pre>
                </p>
                <h3>Расчёт стоимости доставки</h3>
                <p><strong>Метод:</strong> http://bizoutmax.ru/index.php?route=drop/proxy/calculateShipping</p>
                <p>
                    <strong>Запрос:</strong>
                    <pre>
POST
{
    "postcode": "string",
    "products": [
        {
            "product_id": "integer",
            "quantity": "integer"
        }
    ]
}
</pre>
                </p>
                <p>
                <strong>Ответ:</strong>
                <pre>
[
    {
        "name": "string",
        "delivery_cost": "integer",
        "order_cost": "integer",
        "order_prepayment": "integer"
    }
]</pre>
                </p>
                <h2>Ошибки сервиса проксирования</h2>
                <p>
                <strong>Неверный формат запроса</strong>
<pre>
HTTP 400 Bad request
{
    "error": {
        "message": "Bad request"
    }
}</pre>
                <strong>Необходима авторизация</strong>
<pre>
HTTP 401 Unauthorized
{
    "error": {
        "message": "Unauthorized"
    }
}</pre>
                <strong>Доступ запрещен</strong>
<pre>
HTTP 403 Forbidden
{
    "error": {
        "message": "Forbidden"
    }
}</pre>
                <strong>Неверные входные данные</strong>

<pre>
HTTP 422 Unprocessable Entity
{
    "error": {
        "message": "Unprocessable Entity"
    }
}</pre>
                <strong>Превышено количество запросов</strong>
<pre>
HTTP 429 Too Many Requests
{
        "error": {
        "message": "Too Many Requests"
    }
}</pre>
                </p>
            </div>
        </div>
    </div>
</div>
<hr>
<?php echo $footer ?>