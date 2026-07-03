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
            $isFirstSlide = ($index === 0);
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
                <? if (!empty($arItem["PHOTO_MOBILE"])): ?>
                  <picture class="home-hero__picture">
                    <source media="(max-width: 743.98px)" srcset="<?= $arItem["PHOTO_MOBILE"]["SRC"] ?>">
                    <img
                      class="home-hero__image"
                      src="<?= $image["SRC"] ?>"
                      width="<?= $image["WIDTH"] ?>"
                      height="<?= $image["HEIGHT"] ?>"
                      alt="<?= $image["ALT"] ?>"
                      title="<?= $image["TITLE"] ?>"
                      <?= $isFirstSlide ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                    >
                  </picture>
                <? else: ?>
                  <div class="home-hero__picture">
                    <img
                      class="home-hero__image"
                      src="<?= $image["SRC"] ?>"
                      width="<?= $image["WIDTH"] ?>"
                      height="<?= $image["HEIGHT"] ?>"
                      alt="<?= $image["ALT"] ?>"
                      title="<?= $image["TITLE"] ?>"
                      <?= $isFirstSlide ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                    >
                  </div>
                <? endif; ?>
              <? endif; ?>

            </div>
          <? endforeach; ?>
        </div>
      </div>
      <div class="home-hero__controls">
        <button class="home-hero__arrow home-hero__arrow--prev"
                type="button"
                aria-label="Предыдущий слайд"
        >
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 3.75C19.8713 3.75 21.7242 4.11883 23.4531 4.83496C25.182 5.55109 26.7529 6.60059 28.0762 7.92383C29.3994 9.24706 30.4489 10.818 31.165 12.5469C31.8812 14.2758 32.25 16.1287 32.25 18C32.25 21.7793 30.7486 25.4038 28.0762 28.0762C25.4038 30.7486 21.7793 32.25 18 32.25C16.1287 32.25 14.2758 31.8812 12.5469 31.165C10.818 30.4489 9.24706 29.3994 7.92383 28.0762C5.25143 25.4038 3.75 21.7793 3.75 18C3.75 14.2207 5.25143 10.5962 7.92383 7.92383C10.5962 5.25143 14.2207 3.75 18 3.75ZM18 5.25C14.6185 5.25 11.3755 6.59329 8.98438 8.98438C6.59329 11.3755 5.25 14.6185 5.25 18C5.25 21.3815 6.59329 24.6245 8.98438 27.0156C11.3755 29.4067 14.6185 30.75 18 30.75C21.3815 30.75 24.6245 29.4067 27.0156 27.0156C29.4067 24.6245 30.75 21.3815 30.75 18C30.75 14.6185 29.4067 11.3755 27.0156 8.98438C24.6245 6.59329 21.3815 5.25 18 5.25ZM22.0391 11.0996L15.6699 17.4697L15.1396 18L22.0391 24.8994L21 25.9395L13.0605 18L21 10.0605L22.0391 11.0996Z" fill="currentColor" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>
        <div class="home-hero__pagination"></div>
        <button class="home-hero__arrow home-hero__arrow--next"
                type="button"
                aria-label="Следующий слайд"
        >
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 3.75C19.8713 3.75 21.7242 4.11883 23.4531 4.83496C25.182 5.55109 26.7529 6.60059 28.0762 7.92383C29.3994 9.24706 30.4489 10.818 31.165 12.5469C31.8812 14.2758 32.25 16.1287 32.25 18C32.25 21.7793 30.7486 25.4038 28.0762 28.0762C25.4038 30.7486 21.7793 32.25 18 32.25C16.1287 32.25 14.2758 31.8812 12.5469 31.165C10.818 30.4489 9.24706 29.3994 7.92383 28.0762C5.25143 25.4038 3.75 21.7793 3.75 18C3.75 14.2207 5.25143 10.5962 7.92383 7.92383C10.5962 5.25143 14.2207 3.75 18 3.75ZM18 5.25C14.6185 5.25 11.3755 6.59329 8.98438 8.98438C6.59329 11.3755 5.25 14.6185 5.25 18C5.25 21.3815 6.59329 24.6245 8.98438 27.0156C11.3755 29.4067 14.6185 30.75 18 30.75C21.3815 30.75 24.6245 29.4067 27.0156 27.0156C29.4067 24.6245 30.75 21.3815 30.75 18C30.75 14.6185 29.4067 11.3755 27.0156 8.98438C24.6245 6.59329 21.3815 5.25 18 5.25ZM22.9395 18L15 25.9395L13.96 24.8994L20.3301 18.5303L20.8604 18L13.96 11.0996L15 10.0605L22.9395 18Z" fill="currentColor" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
<? endif; ?>
