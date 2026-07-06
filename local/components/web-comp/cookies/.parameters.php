<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "COOKIE_LIFETIME" => array(
            "PARENT" => "BASE",
            "NAME" => "Срок действия cookie в днях",
            "TYPE" => "STRING",
            "DEFAULT" => "365",
        ),
        "DELAY" => array(
            "PARENT" => "BASE",
            "NAME" => "Задержка показа баннера в мс",
            "TYPE" => "STRING",
            "DEFAULT" => "2000",
        ),
        "TEXT" => array(
            "PARENT" => "BASE",
            "NAME" => "Текст",
            "TYPE" => "TEXT",
            "DEFAULT" => "Этот сайт использует файлы cookie для повышения удобства работы. Продолжая пользоваться сайтом, вы даете свое согласие на использование файлов cookie",
            "ROWS" => "10",
            "COLS" => "40",
        ),
        "BTN_TEXT" => array(
            "PARENT" => "BASE",
            "NAME" => "Текст кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "Принять и закрыть",
        ),
        "DEBUG" => array(
            "PARENT" => "BASE",
            "NAME" => "Включить отладочное логирование",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
    ),
);
?>
