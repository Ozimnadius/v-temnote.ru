<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "TITLE" => array(
            "PARENT" => "BASE",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Посмотреть на карте",
        ),
        "RATING_WIDGET_URL" => array(
            "PARENT" => "BASE",
            "NAME" => "Адрес виджета рейтинга (пусто — не показывать)",
            "TYPE" => "STRING",
            "DEFAULT" => "https://yandex.ru/sprav/widget/rating-badge/1325192770?type=award&theme=dark",
        ),
        "RATING_TITLE" => array(
            "PARENT" => "BASE",
            "NAME" => "Описание виджета рейтинга (title)",
            "TYPE" => "STRING",
            "DEFAULT" => "Рейтинг ресторана В темноте?! на Яндекс Картах",
        ),
        "CENTER_LAT" => array(
            "PARENT" => "BASE",
            "NAME" => "Широта центра карты",
            "TYPE" => "STRING",
            "DEFAULT" => "55.784690",
        ),
        "CENTER_LNG" => array(
            "PARENT" => "BASE",
            "NAME" => "Долгота центра карты",
            "TYPE" => "STRING",
            "DEFAULT" => "37.614140",
        ),
        "ZOOM" => array(
            "PARENT" => "BASE",
            "NAME" => "Масштаб карты",
            "TYPE" => "STRING",
            "DEFAULT" => "17",
        ),
        "PLACEMARK_LABEL" => array(
            "PARENT" => "BASE",
            "NAME" => "Подпись метки на карте",
            "TYPE" => "STRING",
            "DEFAULT" => "Ресторан В ТЕМНОТЕ?!",
        ),
        "MAP_ARIA_LABEL" => array(
            "PARENT" => "BASE",
            "NAME" => "Описание карты (aria-label)",
            "TYPE" => "STRING",
            "DEFAULT" => "Темная карта ресторана В темноте?!",
        ),
    ),
);
?>
