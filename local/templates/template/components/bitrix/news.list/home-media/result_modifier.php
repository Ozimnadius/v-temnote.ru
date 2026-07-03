<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */

foreach ($arResult["ITEMS"] as &$arItem) {
    $iconId = (int)($arItem["PROPERTIES"]["ICON"]["VALUE"] ?? 0);
    $iconFile = $iconId ? CFile::GetFileArray($iconId) : [];

    $arItem["ICON"] = $iconFile ? [
        "SRC" => $iconFile["SRC"] ?? "",
        "WIDTH" => (int)($iconFile["WIDTH"] ?? 0),
        "HEIGHT" => (int)($iconFile["HEIGHT"] ?? 0),
    ] : [];

    $arItem["LINK"] = trim((string)($arItem["PROPERTIES"]["LINK"]["VALUE"] ?? ""));
}
unset($arItem);
