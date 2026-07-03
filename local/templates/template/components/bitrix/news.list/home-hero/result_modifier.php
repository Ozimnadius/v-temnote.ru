<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */

foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem["PHOTO_MOBILE"] = null;
    $fileId = (int)($arItem["PROPERTIES"]["UF_PHOTO_MOBILE"]["VALUE"] ?? 0);
    if ($fileId > 0) {
        $file = CFile::GetFileArray($fileId);
        if (is_array($file)) {
            $arItem["PHOTO_MOBILE"] = $file;
        }
    }
}
unset($arItem);
