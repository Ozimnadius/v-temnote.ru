<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

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

Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/swiper/swiper-bundle.css');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/swiper/swiper-bundle.min.js');
?>
<? if (!empty($arResult["ITEMS"])): ?>
  <div class="home-about">

    <? if (!empty($arParams["INTRO_TEXT"])): ?>
      <div class="home-about__intro">
        <?= $arParams["~INTRO_TEXT"] ?>
      </div>
    <? endif; ?>


      <div class="home-about__slider">
        <div class="home-about__swiper swiper">
          <div class="home-about__wrapper swiper-wrapper">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
              <?
              $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
              $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
              ?>
              <article class="home-about__slide swiper-slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <? if ($arParams["DISPLAY_PICTURE"] != "N" && $arItem["PREVIEW_PICTURE"]): ?>
                  <div class="home-about__picture">
                    <img
                      class="home-about__image"
                      src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                      width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                      height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                      alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                      title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                      loading="lazy"
                    >
                  </div>
                <? endif; ?>

                <? if ($arItem["PREVIEW_TEXT"]): ?>
                  <div class="home-about__card-text">
                    <?= $arItem["PREVIEW_TEXT"] ?>
                  </div>
                <? endif; ?>
              </article>
            <? endforeach; ?>
          </div>
        </div>
      </div>


    <? if (!empty($arParams["NOTICE_TEXT"])): ?>
      <div class="home-about__notice">
        <div class="home-about__notice-icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none">
            <path d="M32.0007 58.6663C46.6673 58.6663 58.6673 46.6663 58.6673 31.9997C58.6673 17.333 46.6673 5.33301 32.0007 5.33301C17.334 5.33301 5.33398 17.333 5.33398 31.9997C5.33398 46.6663 17.334 58.6663 32.0007 58.6663ZM32.8007 19.4397C33.334 18.9597 33.974 18.6663 34.6673 18.6663C35.3873 18.6663 36.0007 18.9597 36.5607 19.4397C37.0673 19.9997 37.334 20.6397 37.334 21.333C37.334 22.053 37.0673 22.6663 36.5607 23.2263C36.0007 23.733 35.3873 23.9997 34.6673 23.9997C33.974 23.9997 33.334 23.733 32.8007 23.2263C32.294 22.6663 32.0007 22.053 32.0007 21.333C32.0007 20.6397 32.294 19.9997 32.8007 19.4397ZM26.134 31.9197C26.134 31.9197 31.9207 27.333 34.0273 27.1463C36.0007 26.9863 35.6007 29.253 35.414 30.4263L35.3873 30.5863C35.014 31.9997 34.5607 33.7063 34.1073 35.333C33.094 39.0397 32.1073 42.6663 32.3473 43.333C32.614 44.2397 34.2673 43.093 35.4673 42.293C35.6273 42.1863 35.7607 42.0797 35.894 41.9997C35.894 41.9997 36.1073 41.7863 36.3207 42.0797C36.374 42.1597 36.4273 42.2397 36.4807 42.293C36.7207 42.6663 36.854 42.7997 36.534 43.013L36.4273 43.0663C35.8407 43.4663 33.334 45.2263 32.3207 45.8663C31.2273 46.5863 27.0407 48.9863 27.6807 44.3197C28.2407 41.0397 28.9873 38.213 29.574 35.9997C30.6673 31.9997 31.1473 30.1863 28.694 31.7597C27.7073 32.3463 27.1207 32.7197 26.774 32.9597C26.4807 33.173 26.454 33.173 26.2673 32.8263L26.1873 32.6663L26.054 32.453C25.8673 32.1863 25.8673 32.1597 26.134 31.9197Z" fill="#5AB9E7"/>
          </svg>
        </div>

        <div class="home-about__notice-text">
          <?= $arParams["~NOTICE_TEXT"] ?>
        </div>

        <button type="button" class="home-about__button btn btn--outline">
          <?= $arParams["BUTTON_TEXT"] ?>
        </button>
      </div>
    <? endif; ?>
  </div>
<? endif; ?>
