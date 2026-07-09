<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class OffersListComponent extends CBitrixComponent
{
  public function onPrepareComponentParams($arParams)
  {
    $arParams["IBLOCK_TYPE"] = trim((string)($arParams["IBLOCK_TYPE"] ?? ""));
    $arParams["IBLOCK_ID"] = (int)($arParams["IBLOCK_ID"] ?? 0);

    $elementIds = $arParams["ELEMENT_IDS"] ?? array();
    if (!is_array($elementIds)) {
      $elementIds = array($elementIds);
    }

    $elementIds = array_map("intval", $elementIds);
    $elementIds = array_filter($elementIds);
    $arParams["ELEMENT_IDS"] = array_values(array_unique($elementIds));

    return $arParams;
  }

  public function executeComponent()
  {
    $this->arResult = array(
      "ITEMS" => array(),
    );

    if (
      !Loader::includeModule("iblock")
      || $this->arParams["IBLOCK_ID"] <= 0
      || empty($this->arParams["ELEMENT_IDS"])
    ) {
      $this->includeComponentTemplate();
      return;
    }

    if ($this->startResultCache()) {
      CIBlock::registerWithTagCache($this->arParams["IBLOCK_ID"]);

      $elementResult = CIBlockElement::GetList(
        array("SORT" => "ASC", "ID" => "ASC"),
        array(
          "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
          "ID" => $this->arParams["ELEMENT_IDS"],
          "ACTIVE" => "Y",
          "ACTIVE_DATE" => "Y",
        ),
        false,
        false,
        array(
          "ID",
          "IBLOCK_ID",
          "NAME",
          "SORT",
          "PREVIEW_PICTURE",
          "PREVIEW_TEXT",
          "PREVIEW_TEXT_TYPE",
        )
      );

      while ($elementObject = $elementResult->GetNextElement()) {
        $item = $elementObject->GetFields();
        $item["PROPERTIES"] = $elementObject->GetProperties();

        if (!empty($item["PREVIEW_PICTURE"])) {
          $item["PREVIEW_PICTURE"] = CFile::GetFileArray($item["PREVIEW_PICTURE"]);
        }

        $buttons = CIBlock::GetPanelButtons(
          $item["IBLOCK_ID"],
          $item["ID"],
          0,
          array("SECTION_BUTTONS" => false, "SESSID" => false)
        );
        $item["EDIT_LINK"] = $buttons["edit"]["edit_element"]["ACTION_URL"] ?? "";
        $item["DELETE_LINK"] = $buttons["edit"]["delete_element"]["ACTION_URL"] ?? "";

        $this->arResult["ITEMS"][] = $item;
      }

      $this->includeComponentTemplate();
    }
  }
}
