<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("iblock")) {
  return;
}

$iblockId = (int)($arParams["IBLOCK_ID"] ?? 0);
$arResult["MENU_SECTIONS"] = [];

if ($iblockId <= 0) {
  return;
}

$sectionIds = [];
$sectionsById = [];

$rsSections = CIBlockSection::GetList(
  ["SORT" => "ASC", "ID" => "ASC"],
  [
    "IBLOCK_ID" => $iblockId,
    "ACTIVE" => "Y",
  ],
  false,
  ["ID", "IBLOCK_ID", "NAME", "SORT", "CODE"]
);

while ($section = $rsSections->GetNext()) {
  $sectionId = (int)$section["ID"];
  $sectionIds[] = $sectionId;
  $buttons = CIBlock::GetPanelButtons(
    $iblockId,
    0,
    $sectionId,
    ["SECTION_BUTTONS" => true, "SESSID" => false]
  );

  $section["EDIT_LINK"] = $buttons["edit"]["edit_section"]["ACTION_URL"] ?? "";
  $section["DELETE_LINK"] = $buttons["edit"]["delete_section"]["ACTION_URL"] ?? "";
  $section["ITEMS"] = [];

  $sectionsById[$sectionId] = $section;
}

if (empty($sectionIds)) {
  return;
}

$sort = [
  $arParams["SORT_BY1"] ?: "SORT" => $arParams["SORT_ORDER1"] ?: "ASC",
  $arParams["SORT_BY2"] ?: "ID" => $arParams["SORT_ORDER2"] ?: "ASC",
];

$rsItems = CIBlockElement::GetList(
  $sort,
  [
    "IBLOCK_ID" => $iblockId,
    "ACTIVE" => "Y",
    "ACTIVE_DATE" => ($arParams["CHECK_DATES"] ?? "Y") === "N" ? "" : "Y",
    "SECTION_ID" => $sectionIds,
    "INCLUDE_SUBSECTIONS" => "N",
  ],
  false,
  false,
  [
    "ID",
    "IBLOCK_ID",
    "IBLOCK_SECTION_ID",
    "NAME",
    "PREVIEW_TEXT",
    "PREVIEW_TEXT_TYPE",
    "PREVIEW_PICTURE",
  ]
);

while ($obItem = $rsItems->GetNextElement()) {
  $arItem = $obItem->GetFields();
  $arItem["PROPERTIES"] = $obItem->GetProperties();

  $sectionId = (int)($arItem["IBLOCK_SECTION_ID"] ?? 0);

  if (!isset($sectionsById[$sectionId])) {
    continue;
  }

  $price = $arItem["PROPERTIES"]["POST"]["VALUE"] ?? "";
  $weight = $arItem["PROPERTIES"]["UF_WEIGHT"]["VALUE"] ?? "";
  $picture = !empty($arItem["PREVIEW_PICTURE"]) ? CFile::GetFileArray($arItem["PREVIEW_PICTURE"]) : null;
  $previewText = trim((string)($arItem["PREVIEW_TEXT"] ?? ""));
  $previewTextType = (string)($arItem["PREVIEW_TEXT_TYPE"] ?? "text");
  $elementButtons = CIBlock::GetPanelButtons(
    $iblockId,
    (int)$arItem["ID"],
    0,
    ["SECTION_BUTTONS" => false, "SESSID" => false]
  );

  if ($previewText !== "" && $previewTextType !== "html") {
    $previewText = nl2br(htmlspecialcharsbx($previewText));
  }

  $arItem["EDIT_LINK"] = $elementButtons["edit"]["edit_element"]["ACTION_URL"] ?? "";
  $arItem["DELETE_LINK"] = $elementButtons["edit"]["delete_element"]["ACTION_URL"] ?? "";
  $arItem["MENU_CARD"] = [
    "PRICE" => is_array($price) ? implode(", ", $price) : (string)$price,
    "WEIGHT" => is_array($weight) ? implode(", ", $weight) : (string)$weight,
    "PICTURE" => $picture,
    "HAS_PICTURE" => !empty($picture["SRC"]),
    "PREVIEW_TEXT" => $previewText,
  ];

  $sectionsById[$sectionId]["ITEMS"][] = $arItem;
}

foreach ($sectionsById as $section) {
  if (!empty($section["ITEMS"])) {
    $arResult["MENU_SECTIONS"][] = $section;
  }
}
