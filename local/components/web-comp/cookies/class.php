<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CookiesComponent extends CBitrixComponent{

    public function onPrepareComponentParams($arParams)
    {
        $arParams['COOKIE_LIFETIME'] = trim($arParams['COOKIE_LIFETIME']);
        $arParams['DELAY'] = trim($arParams['DELAY']);
        $arParams['TEXT'] = trim($arParams['TEXT']);
        $arParams['BTN_TEXT'] = trim($arParams['BTN_TEXT']);
        $arParams['DEBUG'] = $arParams['DEBUG'] === 'Y';
        return $arParams;
    }

    public function executeComponent()
    {
        /*if($this->startResultCache())
        {

            $this->includeComponentTemplate();
        }*/
        $this->includeComponentTemplate();
    }
}