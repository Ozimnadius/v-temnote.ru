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

$photos = $arResult["DISPLAY_PROPERTIES"]["PHOTOS"]["FILE_VALUE"] ?? [];
if (is_array($photos) && !empty($photos["SRC"])) {
  $photos = [$photos];
}

if (!empty($photos)) {
  \Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/@fancyapps/ui/dist/fancybox/fancybox.min.css');
  \Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/@fancyapps/ui/dist/fancybox/fancybox.umd.min.js');
}
?>

<? if (!empty($photos)): ?>
  <div class="gallery-detail">
    <? foreach ($photos as $photoId => $arPhoto): ?>
      <?
      if (empty($arPhoto["SRC"]) || empty($arPhoto["ID"])) {
        continue;
      }

      $preview = CFile::ResizeImageGet(
        $arPhoto["ID"],
        ["width" => 327, "height" => 229],
        BX_RESIZE_IMAGE_EXACT,
        true
      );
      $previewSrc = $preview["src"] ?? $arPhoto["SRC"];
      $previewWidth = (int)($preview["width"] ?? 327);
      $previewHeight = (int)($preview["height"] ?? 229);
      $caption = $arPhoto["DESCRIPTION"] ?: ($arPhoto["ORIGINAL_NAME"] ?? $arResult["NAME"]);
      $alt = $caption ?: $arResult["NAME"];
      ?>
      <a
        class="gallery-detail__item"
        href="<?= htmlspecialcharsbx($arPhoto["SRC"]) ?>"
        data-fancybox="gallery-detail"
        data-caption="<?= htmlspecialcharsbx($caption) ?>"
        data-thumb-src="<?= htmlspecialcharsbx($previewSrc) ?>"
        <? if (!empty($arPhoto["WIDTH"]) && !empty($arPhoto["HEIGHT"])): ?>
          data-width="<?= (int)$arPhoto["WIDTH"] ?>"
          data-height="<?= (int)$arPhoto["HEIGHT"] ?>"
        <? endif; ?>
      >
        <img
          class="gallery-detail__image"
          src="<?= htmlspecialcharsbx($previewSrc) ?>"
          width="<?= $previewWidth ?>"
          height="<?= $previewHeight ?>"
          alt="<?= htmlspecialcharsbx($alt) ?>"
          loading="lazy"
          decoding="async"
        >
      </a>
    <? endforeach; ?>
  </div>

<? endif; ?>
