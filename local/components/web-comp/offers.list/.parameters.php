<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule("iblock")) {
  return;
}

$iblockTypes = CIBlockParameters::GetIBlockTypes();
$iblocks = array();
$elements = array();

$iblockFilter = array("ACTIVE" => "Y");
if (!empty($arCurrentValues["IBLOCK_TYPE"])) {
  $iblockFilter["TYPE"] = $arCurrentValues["IBLOCK_TYPE"];
}
if (isset($_REQUEST["site"])) {
  $iblockFilter["SITE_ID"] = $_REQUEST["site"];
}

$iblockResult = CIBlock::GetList(array("SORT" => "ASC"), $iblockFilter);
while ($iblock = $iblockResult->Fetch()) {
  $iblocks[$iblock["ID"]] = "[" . $iblock["ID"] . "] " . $iblock["NAME"];
}

$iblockId = isset($arCurrentValues["IBLOCK_ID"]) ? (int)$arCurrentValues["IBLOCK_ID"] : 0;
if ($iblockId > 0) {
  $elementResult = CIBlockElement::GetList(
    array("SORT" => "ASC", "NAME" => "ASC", "ID" => "ASC"),
    array("IBLOCK_ID" => $iblockId),
    false,
    false,
    array("ID", "NAME", "ACTIVE")
  );

  while ($element = $elementResult->Fetch()) {
    $label = "[" . $element["ID"] . "] " . $element["NAME"];
    if ($element["ACTIVE"] !== "Y") {
      $label .= " (" . GetMessage("WEB_COMP_OFFERS_LIST_ELEMENT_INACTIVE") . ")";
    }
    $elements[$element["ID"]] = $label;
  }
}

$arComponentParameters = array(
  "GROUPS" => array(),
  "PARAMETERS" => array(
    "IBLOCK_TYPE" => array(
      "PARENT" => "BASE",
      "NAME" => GetMessage("WEB_COMP_OFFERS_LIST_IBLOCK_TYPE"),
      "TYPE" => "LIST",
      "VALUES" => $iblockTypes,
      "DEFAULT" => "",
      "REFRESH" => "Y",
    ),
    "IBLOCK_ID" => array(
      "PARENT" => "BASE",
      "NAME" => GetMessage("WEB_COMP_OFFERS_LIST_IBLOCK_ID"),
      "TYPE" => "LIST",
      "VALUES" => $iblocks,
      "DEFAULT" => "",
      "ADDITIONAL_VALUES" => "Y",
      "REFRESH" => "Y",
    ),
  ),
);

if ($iblockId > 0) {
  $arComponentParameters["PARAMETERS"]["ELEMENT_IDS"] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("WEB_COMP_OFFERS_LIST_ELEMENT_IDS"),
    "TYPE" => "LIST",
    "VALUES" => $elements,
    "MULTIPLE" => "Y",
    "SIZE" => 10,
    "ADDITIONAL_VALUES" => "N",
  );
}

$arComponentParameters["PARAMETERS"]["CACHE_TIME"] = array(
  "DEFAULT" => 36000000,
);
