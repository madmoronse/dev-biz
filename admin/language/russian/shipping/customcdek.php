<?php
// Heading
$_['heading_title']    = 'Настройки доставки CDEK';

// Text
$_['text_shipping']    = 'Доставка';
$_['text_success']     = 'Настройки модуля обновлены!';
$_['text_short_length']					= 'Д';
$_['text_short_width']					= 'Ш';
$_['text_short_height']					= 'В';
$_['text_markup_option']					= 'Наценка на опции';
$_['text_data_category_help'] = 'Данные по умолчанию для конкретных категорий.';
$_['text_markup_declared_value_help'] = 'Процент наценки от стоимости товаров для разных групп пользователей.';

// Entry
$_['entry_log']							= 'Режим отладки:<span class="help">Все ошибки в работе модуля или расчета доставки будут записаны в лог <br />(Система → Журнал ошибок).</span>';
$_['entry_customcdek_use_fallback'] = 'Использовать старые методы доставки:';
$_['customcdek_pvz']       = 'Выбора пункта выдачи:';
$_['customcdek_door']       = 'До двери:';
$_['customcdek_dressingroom']       = 'Выбор пункта выдачи с примерочной:';
$_['customcdek_status_text']     = 'Статус:';
$_['customcdek_pvz_activation_text'] = 'Показывать список пунктов выдачи:';
$_['customcdek_door_activation_text'] = 'Доставка до двери:';
$_['entry_sort_order'] = 'Порядок сортировки:';
$_['text_markup_full'] = 'Наценки при 100% предоплате:';
$_['entry_markup_door'] = 'Наценка на доставку до двери в %:';
$_['entry_markup_pvz'] = 'Наценка на доставку до пункта выдачи в %:';
$_['entry_markup_dressingroom'] = 'Примерочная в %:';

$_['text_markup_part'] = 'Наценки при частичной предоплате:';
$_['entry_login'] = 'Логин:';
$_['entry_password'] = 'Пароль:';
$_['entry_postalcode'] = 'Почтовый индекс отправителя:';
$_['entry_size']						= 'Размеры отправления<span class="help">Указывается в сантиметрах</span>';
$_['entry_default_weight']				= 'Вес отправления';
$_['entry_timeout'] = 'Время истечения запроса к API';

// Tab
$_['tab_main']							= 'Общие настройки';
$_['tab_markup']						= 'Наценки/Предоплата';
$_['tab_auth']							= 'Авторизация';
$_['tab_data']							= 'Данные';

// Column
$_['column_category']				    = 'ID категории';
$_['column_size']						= 'Размер отправления<span class="help">Указывается в сантиметрах</span>';
$_['column_weight']                     = 'Вес, кг';
$_['column_customer_group']				= 'Группа покупателей';
$_['column_markup']                     = 'Наценка в %';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['error_warning']						= 'Внимательно проверьте форму на ошибки!';
$_['error_cdek_city_from']				= 'Необходимо выбрать город доставки!';
$_['error_tariff_list']					= 'Необходимо выбрать один или несколько тарифов!';
$_['error_curl']						= 'Для работы модуля трубуется расширение CURL!';
$_['error_numeric']						= 'Значение должно быть числом!';
$_['error_positive_numeric']			= 'Значение должно быть больше нуля!';
$_['error_positive_numeric2']			= 'Значение не должно быть меньше нуля!';
$_['error_tariff_item_exists']			= 'Тариф дублируется для географических зон: %s!';
$_['error_discount_exists']				= 'Скидка от указанной суммы уже установлена!';
$_['error_empty']						= 'Значение не заполнено!';
?>