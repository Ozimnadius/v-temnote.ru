<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */

foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem["LINK"] = trim((string)($arItem["PROPERTIES"]["UF_LINK"]["VALUE"] ?? ""));
}
unset($arItem);
