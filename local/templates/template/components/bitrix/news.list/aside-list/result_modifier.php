<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult["ITEMS"] as &$arItem) {
  $picture = isset($arItem["PREVIEW_PICTURE"]) && is_array($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"] : array();
  $name = isset($arItem["NAME"]) ? $arItem["NAME"] : "";
  $previewText = isset($arItem["PREVIEW_TEXT"]) ? $arItem["PREVIEW_TEXT"] : "";
  $detailText = isset($arItem["DETAIL_TEXT"]) ? $arItem["DETAIL_TEXT"] : "";

  $hasPicture = (!isset($arParams["DISPLAY_PICTURE"]) || $arParams["DISPLAY_PICTURE"] != "N") && !empty($picture);
  $hasName = (!isset($arParams["DISPLAY_NAME"]) || $arParams["DISPLAY_NAME"] != "N") && $name !== "";
  $hasPreviewText = (!isset($arParams["DISPLAY_PREVIEW_TEXT"]) || $arParams["DISPLAY_PREVIEW_TEXT"] != "N") && $previewText !== "";
  $hasDetailText = $detailText !== "";

  $link = isset($arItem["PROPERTIES"]["LINK"]["VALUE"]) ? trim((string)$arItem["PROPERTIES"]["LINK"]["VALUE"]) : "";
  $linkText = isset($arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"]) ? trim((string)$arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"]) : "";
  if ($linkText === "") {
    $linkText = "Подробнее";
  }

  $cardTypeValue = isset($arItem["PROPERTIES"]["CARD_TYPE"]["VALUE_XML_ID"]) ? $arItem["PROPERTIES"]["CARD_TYPE"]["VALUE_XML_ID"] : "";
  if (is_array($cardTypeValue)) {
    $cardTypeValue = reset($cardTypeValue);
  }
  if ($cardTypeValue === "" && isset($arItem["PROPERTIES"]["CARD_TYPE"]["VALUE"])) {
    $cardTypeValue = $arItem["PROPERTIES"]["CARD_TYPE"]["VALUE"];
    if (is_array($cardTypeValue)) {
      $cardTypeValue = reset($cardTypeValue);
    }
  }

  $cardType = trim((string)$cardTypeValue);
  $cardTypeNormalized = strtolower($cardType);
  $isTextFirst = in_array($cardTypeNormalized, array("text_first", "text-first", "image_after_text", "text_top", "text-top", "long"), true)
    || strpos($cardTypeNormalized, "text") !== false
    || strpos($cardType, "Текст") !== false
    || strpos($cardType, "текст") !== false;

  $modifier = $isTextFirst ? "text-first" : "image-first";

  $arItem["ASIDE_CARD"] = array(
    "CLASS" => "aside-list__card aside-list__card--" . $modifier,
    "HAS_PICTURE" => $hasPicture,
    "PICTURE" => $hasPicture ? $picture : array(),
    "HAS_NAME" => $hasName,
    "NAME" => $name,
    "HAS_PREVIEW_TEXT" => $hasPreviewText,
    "PREVIEW_TEXT" => $previewText,
    "HAS_DETAIL_TEXT" => $hasDetailText,
    "DETAIL_TEXT" => $detailText,
    "HAS_LINK" => $link !== "",
    "LINK" => $link,
    "LINK_TEXT" => $linkText,
    "TYPE" => $cardType,
    "MODIFIER" => $modifier,
    "IS_TEXT_FIRST" => $isTextFirst,
  );
}
unset($arItem);
