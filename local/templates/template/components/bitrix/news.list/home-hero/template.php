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
Asset::getInstance()->addCss($templateFolder . '/index.css', true);
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/swiper/swiper-bundle.min.js');
?>
<? if (!empty($arResult["ITEMS"])): ?>
  <?
  $itemsCount = count($arResult["ITEMS"]);
  ?>
  <div class="home-hero">
    <div class="home-hero__slider">
      <div class="home-hero__swiper swiper">
        <div class="home-hero__wrapper swiper-wrapper">
          <? foreach ($arResult["ITEMS"] as $index => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?
            $link = $arItem["DISPLAY_PROPERTIES"]["UF_LINK"]["VALUE"] ?? $arItem["PROPERTIES"]["UF_LINK"]["VALUE"] ?? "";
            $image = is_array($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"] : null;
            ?>
            <div class="home-hero__slide swiper-slide"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
            >

              <div class="home-hero__content">
                <div class="home-hero__counter">
                  <span class="home-hero__counter-current"><?= str_pad($index + 1, 2, "0", STR_PAD_LEFT) ?></span>
                  <span class="home-hero__counter-total"> / <?= str_pad($itemsCount, 2, "0", STR_PAD_LEFT) ?></span>
                </div>

                <div class="home-hero__body">
                  <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                    <h1 class="home-hero__title"><?= $arItem["NAME"] ?></h1>
                  <? endif; ?>

                  <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                    <div class="home-hero__text"><?= $arItem["PREVIEW_TEXT"] ?></div>
                  <? endif; ?>

                  <? if ($link): ?>
                    <a class="home-hero__button btn btn--outline"
                       href="<?= htmlspecialcharsbx($link) ?>"
                    >
                      Подробнее
                    </a>
                  <? endif; ?>
                </div>
              </div>

              <? if ($arParams["DISPLAY_PICTURE"] != "N" && $image): ?>
                <div class="home-hero__picture">
                  <img
                    class="home-hero__image"
                    src="<?= $image["SRC"] ?>"
                    width="<?= $image["WIDTH"] ?>"
                    height="<?= $image["HEIGHT"] ?>"
                    alt="<?= $image["ALT"] ?>"
                    title="<?= $image["TITLE"] ?>"
                  >
                </div>
              <? endif; ?>

            </div>
          <? endforeach; ?>
        </div>
      </div>
      <div class="home-hero__controls">
        <button class="home-hero__arrow home-hero__arrow--prev"
                type="button"
                aria-label="Предыдущий слайд"
        ></button>
        <div class="home-hero__pagination"></div>
        <button class="home-hero__arrow home-hero__arrow--next"
                type="button"
                aria-label="Следующий слайд"
        ></button>
      </div>
    </div>
  </div>
<? endif; ?>
