<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class YandexMapComponent extends CBitrixComponent{

    public function onPrepareComponentParams($arParams)
    {
        $arParams['TITLE'] = trim($arParams['TITLE']);
        $arParams['RATING_WIDGET_URL'] = trim($arParams['RATING_WIDGET_URL']);
        $arParams['RATING_TITLE'] = trim($arParams['RATING_TITLE']);
        $arParams['CENTER_LAT'] = trim($arParams['CENTER_LAT']);
        $arParams['CENTER_LNG'] = trim($arParams['CENTER_LNG']);
        $arParams['ZOOM'] = trim($arParams['ZOOM']);
        $arParams['PLACEMARK_LABEL'] = trim($arParams['PLACEMARK_LABEL']);
        $arParams['MAP_ARIA_LABEL'] = trim($arParams['MAP_ARIA_LABEL']);
        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult['API_KEY'] = COption::GetOptionString("fileman", "yandex_map_api_key", "");

        if ($this->arResult['API_KEY'] !== "") {
            $yandexMapApiUrl = "https://api-maps.yandex.ru/v3/?apikey=" . rawurlencode($this->arResult['API_KEY']) . "&lang=ru_RU";
            \Bitrix\Main\Page\Asset::getInstance()->addString('<script src="' . htmlspecialcharsbx($yandexMapApiUrl) . '"></script>');
        }

        $this->includeComponentTemplate();
    }
}
