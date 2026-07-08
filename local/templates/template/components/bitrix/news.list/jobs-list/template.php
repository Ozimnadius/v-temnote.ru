<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="jobs-list">
  <?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <div class="jobs-list__pager jobs-list__pager--top">
      <?=$arResult["NAV_STRING"]?>
    </div>
  <?endif;?>

  <?if(!empty($arResult["ITEMS"])):?>
    <div class="jobs-list__grid">
      <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")));
        $title = str_replace("\xE2\x80\xA8", "\n", $arItem["NAME"]);
        ?>
        <article class="jobs-list__item" id="<?=$this->GetEditAreaId($arItem["ID"]);?>">
          <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
            <h2 class="jobs-list__title"><?=nl2br(htmlspecialcharsbx(htmlspecialcharsback($title)))?></h2>
          <?endif;?>

          <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
            <div class="jobs-list__content">
              <?=$arItem["PREVIEW_TEXT"]?>
            </div>
          <?endif;?>
        </article>
      <?endforeach;?>
    </div>
  <?endif;?>

  <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <div class="jobs-list__pager">
      <?=$arResult["NAV_STRING"]?>
    </div>
  <?endif;?>
</div>
