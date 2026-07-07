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
<div class="menu-list">
  <?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <div class="menu-list__pager menu-list__pager--top">
      <?=$arResult["NAV_STRING"]?>
    </div>
  <?endif;?>

  <?foreach($arResult["MENU_SECTIONS"] as $section):?>
    <?
    $sectionEditId = "section_" . $section["ID"];
    $this->AddEditAction($sectionEditId, $section["EDIT_LINK"], CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_EDIT"));
    $this->AddDeleteAction($sectionEditId, $section["DELETE_LINK"], CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
    ?>
    <details class="menu-list__section" id="<?=$this->GetEditAreaId($sectionEditId);?>" open>
      <summary class="menu-list__header">
        <h2 class="menu-list__title">
          <?=htmlspecialcharsbx($section["NAME"])?>
        </h2>
        <span class="menu-list__icon" aria-hidden="true"></span>
      </summary>

      <div class="menu-list__panel">
        <div class="menu-list__panel-inner">
          <div class="menu-list__grid">
            <?foreach($section["ITEMS"] as $arItem):?>
              <?
              $card = $arItem["MENU_CARD"];
              $cardClass = "menu-list__card";

              if ($card["HAS_PICTURE"]) {
                $cardClass .= " menu-list__card--image";
              }

              $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
              $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")]);
              ?>
              <article class="<?=$cardClass?>" id="<?=$this->GetEditAreaId($arItem["ID"]);?>">
                <?if($card["HAS_PICTURE"]):?>
                  <div class="menu-list__image-box">
                    <img
                      class="menu-list__image"
                      src="<?=$card["PICTURE"]["SRC"]?>"
                      width="<?=$card["PICTURE"]["WIDTH"]?>"
                      height="<?=$card["PICTURE"]["HEIGHT"]?>"
                      alt="<?=htmlspecialcharsbx($card["PICTURE"]["ALT"] ?: $arItem["NAME"])?>"
                      loading="lazy"
                    >
                  </div>
                <?endif;?>

                <div class="menu-list__body">
                  <?if($arParams["DISPLAY_NAME"] !== "N" && $arItem["NAME"]):?>
                    <h3 class="menu-list__name">
                      <?=htmlspecialcharsbx($arItem["NAME"])?>
                    </h3>
                  <?endif;?>

                  <?if($arParams["DISPLAY_PREVIEW_TEXT"] !== "N" && $card["PREVIEW_TEXT"]):?>
                    <div class="menu-list__description">
                      <?=$card["PREVIEW_TEXT"]?>
                    </div>
                  <?endif;?>

                  <div class="menu-list__meta">
                    <?if($card["PRICE"] !== ""):?>
                      <div class="menu-list__price">
                        <?=htmlspecialcharsbx($card["PRICE"])?>
                      </div>
                    <?endif;?>

                    <?if($card["WEIGHT"] !== ""):?>
                      <div class="menu-list__weight">
                        <?=htmlspecialcharsbx($card["WEIGHT"])?>
                      </div>
                    <?endif;?>
                  </div>
                </div>
              </article>
            <?endforeach;?>
          </div>
        </div>
      </div>
    </details>
  <?endforeach;?>

  <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <div class="menu-list__pager menu-list__pager--bottom">
      <?=$arResult["NAV_STRING"]?>
    </div>
  <?endif;?>
</div>
