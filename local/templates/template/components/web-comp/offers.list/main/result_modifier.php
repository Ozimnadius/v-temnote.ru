<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult["ITEMS"] as &$arItem) {
  $name = trim((string)($arItem["~NAME"] ?? ""));
  $previewText = (string)($arItem["PREVIEW_TEXT"] ?? "");
  $picture = is_array($arItem["PREVIEW_PICTURE"] ?? null) ? $arItem["PREVIEW_PICTURE"] : array();
  $link = trim((string)($arItem["PROPERTIES"]["LINK"]["VALUE"] ?? ""));
  $linkText = trim((string)($arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"] ?? "")) ?: "Подробнее";
  $style = ($arItem["PROPERTIES"]["OFFER_STYLE"]["VALUE_XML_ID"] ?? "") === "accent"
    ? "accent"
    : "standard";
  $pictureAlt = trim((string)($picture["DESCRIPTION"] ?? "")) ?: $name;

  $arItem["OFFER_CARD"] = array(
    "CLASS" => "offers-list__item offers-list__item--" . $style,
    "HAS_PICTURE" => !empty($picture["SRC"]),
    "PICTURE_SRC" => (string)($picture["SRC"] ?? ""),
    "PICTURE_WIDTH" => (int)($picture["WIDTH"] ?? 0),
    "PICTURE_HEIGHT" => (int)($picture["HEIGHT"] ?? 0),
    "PICTURE_ALT" => $pictureAlt,
    "HAS_NAME" => $name !== "",
    "NAME" => $name,
    "HAS_TEXT" => $previewText !== "",
    "TEXT" => $previewText,
    "HAS_LINK" => $link !== "",
    "LINK" => $link,
    "LINK_TEXT" => $linkText,
  );
}
unset($arItem);
