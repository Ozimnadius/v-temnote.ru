<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$showPicture = ($arParams["DISPLAY_PICTURE"] ?? "") !== "N";
$showName = ($arParams["DISPLAY_NAME"] ?? "") !== "N";
$showPreviewText = ($arParams["DISPLAY_PREVIEW_TEXT"] ?? "") !== "N";

foreach ($arResult["ITEMS"] as &$arItem) {
  $picture = is_array($arItem["PREVIEW_PICTURE"] ?? null) ? $arItem["PREVIEW_PICTURE"] : [];
  $name = (string)($arItem["NAME"] ?? "");
  $previewText = (string)($arItem["PREVIEW_TEXT"] ?? "");
  $detailText = (string)($arItem["DETAIL_TEXT"] ?? "");
  $link = trim((string)($arItem["PROPERTIES"]["LINK"]["VALUE"] ?? ""));
  $linkText = trim((string)($arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"] ?? "")) ?: "Подробнее";
  $isTextFirst = ($arItem["PROPERTIES"]["CARD_TYPE"]["VALUE_XML_ID"] ?? "") === "text_top";

  $arItem["ASIDE_CARD"] = [
    "CLASS" => "aside-list__card aside-list__card--" . ($isTextFirst ? "text-first" : "image-first"),
    "HAS_PICTURE" => $showPicture && !empty($picture),
    "PICTURE" => $picture,
    "HAS_NAME" => $showName && $name !== "",
    "NAME" => $name,
    "HAS_PREVIEW_TEXT" => $showPreviewText && $previewText !== "",
    "PREVIEW_TEXT" => $previewText,
    "HAS_DETAIL_TEXT" => $detailText !== "",
    "DETAIL_TEXT" => $detailText,
    "HAS_LINK" => $link !== "",
    "LINK" => $link,
    "LINK_TEXT" => $linkText,
  ];
}
unset($arItem);
