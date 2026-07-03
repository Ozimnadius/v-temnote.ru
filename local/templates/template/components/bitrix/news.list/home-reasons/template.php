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
  <div class="home-reasons">
    <? if (!empty($arParams["HEADER_TEXT"])): ?>
      <h2 class="home-reasons__title"><?= $arParams["HEADER_TEXT"] ?></h2>
    <? endif; ?>

    <div class="home-reasons__list">
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        ?>
        <? if ($arItem["LINK"]): ?>
          <a class="home-reasons__item"
             href="<?= htmlspecialcharsbx($arItem["LINK"]) ?>"
             id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
          >
            <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
              <img
                class="home-reasons__image"
                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                loading="lazy"
              >
            <? endif; ?>

            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
              <span class="home-reasons__name"><?= $arItem["NAME"] ?></span>
            <? endif; ?>
          </a>
        <? else: ?>
          <div class="home-reasons__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
              <img
                class="home-reasons__image"
                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                loading="lazy"
              >
            <? endif; ?>

            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
              <span class="home-reasons__name"><?= $arItem["NAME"] ?></span>
            <? endif; ?>
          </div>
        <? endif; ?>
      <? endforeach; ?>
    </div>
  </div>
<? endif; ?>
