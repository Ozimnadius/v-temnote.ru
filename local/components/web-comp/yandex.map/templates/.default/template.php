<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var YandexMapComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
?>
<section class="contacts-map">
  <div class="contacts-map__header">
    <h2 class="contacts-map__title h2"><?= $arParams['TITLE'] ?></h2>

    <? if ($arParams['RATING_WIDGET_URL'] !== ""): ?>
      <div class="contacts-map__rating">
        <iframe class="contacts-map__rating-frame"
                src="<?= htmlspecialcharsbx($arParams['RATING_WIDGET_URL']) ?>"
                title="<?= htmlspecialcharsbx($arParams['RATING_TITLE']) ?>"
                loading="lazy"
        ></iframe>
      </div>
    <? endif; ?>
  </div>

  <div class="contacts-map__widget contacts-map__widget--dark">
    <div class="contacts-map__frame contacts-map__frame--dark"
         data-yandex-map
         data-center-lng="<?= htmlspecialcharsbx($arParams['CENTER_LNG']) ?>"
         data-center-lat="<?= htmlspecialcharsbx($arParams['CENTER_LAT']) ?>"
         data-zoom="<?= htmlspecialcharsbx($arParams['ZOOM']) ?>"
         data-placemark-label="<?= htmlspecialcharsbx($arParams['PLACEMARK_LABEL']) ?>"
         aria-label="<?= htmlspecialcharsbx($arParams['MAP_ARIA_LABEL']) ?>"
    ></div>
  </div>
</section>
