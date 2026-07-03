<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<? if (!empty($arResult["ITEMS"])): ?>
  <div class="home-service">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
      <?
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
      ?>
      <article class="home-service__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
          <h2 class="home-service__name"><?= $arItem["NAME"] ?></h2>
        <? endif; ?>

        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
          <div class="home-service__body">
            <svg class="home-service__icon" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M30 21L24 33H33V51H15V33L21 21H30ZM54 21L48 33H57V51H39V33L45 21H54Z" fill="currentColor"/>
            </svg>

            <div class="home-service__text"><?= $arItem["PREVIEW_TEXT"] ?></div>
          </div>
        <? endif; ?>
      </article>
    <? endforeach; ?>
  </div>
<? endif; ?>
