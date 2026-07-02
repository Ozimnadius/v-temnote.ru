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
<div class="news-list">

  <? if (!empty($arParams["HEADER_TEXT"]) || !empty($arParams["BUTTON_TEXT"])): ?>
    <div class="news-list__header">
      <? if (!empty($arParams["HEADER_TEXT"])): ?>
        <h2 class="news-list__heading"><?= $arParams["HEADER_TEXT"] ?></h2>
      <? endif; ?>

      <? if (!empty($arParams["BUTTON_TEXT"])): ?>
        <a class="news-list__button btn btn--outline" href="<?= !empty($arParams["BUTTON_URL"]) ? $arParams["BUTTON_URL"] : '/news/' ?>">
          <?= $arParams["BUTTON_TEXT"] ?>
          <svg class="news-list__button-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      <? endif; ?>
    </div>
  <? endif; ?>

  <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
    <div class="news-list__pager news-list__pager--top">
      <?= $arResult["NAV_STRING"] ?>
    </div>
  <? endif; ?>

  <div class="news-list__grid">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
      <?
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
      ?>
      <?
      $showDetailLink = !$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]);
      ?>
      <article class="news-list__item"
               id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
          <? if ($showDetailLink): ?>
            <a class="news-list__picture" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
              <img
                class="news-list__image"
                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                loading="lazy"
              >
            </a>
          <? else: ?>
            <div class="news-list__picture">
              <img
                class="news-list__image"
                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                loading="lazy"
              >
            </div>
          <? endif; ?>
        <? endif; ?>

        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
          <p class="news-list__date text-14"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></p>
        <? endif; ?>

        <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
          <div class="news-list__title text-24">
            <? if ($showDetailLink): ?>
              <a class="news-list__link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
            <? else: ?>
              <?= $arItem["NAME"] ?>
            <? endif; ?>
          </div>
        <? endif; ?>

        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
          <div class="news-list__text text-14"><?= $arItem["PREVIEW_TEXT"] ?></div>
        <? endif; ?>
      </article>
    <? endforeach; ?>
  </div>

  <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <div class="news-list__pager">
      <?= $arResult["NAV_STRING"] ?>
    </div>
  <? endif; ?>

</div>
