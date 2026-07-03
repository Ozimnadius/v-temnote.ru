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
  <div class="home-media">
    <? if (!empty($arParams["HEADER_TEXT"])): ?>
      <h2 class="home-media__title"><?= $arParams["HEADER_TEXT"] ?></h2>
    <? endif; ?>

    <div class="home-media__list">
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        ?>
        <article class="home-media__item"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
        >
          <? if (!empty($arItem["ICON"]["SRC"])): ?>
            <div class="home-media__icon">
              <img
                class="home-media__image"
                src="<?= htmlspecialcharsbx($arItem["ICON"]["SRC"]) ?>"
                <? if ($arItem["ICON"]["WIDTH"]): ?>width="<?= $arItem["ICON"]["WIDTH"] ?>"<? endif; ?>
                <? if ($arItem["ICON"]["HEIGHT"]): ?>height="<?= $arItem["ICON"]["HEIGHT"] ?>"<? endif; ?>
                alt="<?= $arItem["NAME"] ?>"
                title="<?= $arItem["NAME"] ?>"
                loading="lazy"
              >
            </div>
          <? endif; ?>

          <div class="home-media__content">
            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
              <h3 class="home-media__name"><?= $arItem["NAME"] ?></h3>
            <? endif; ?>

            <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
              <div class="home-media__text"><?= $arItem["PREVIEW_TEXT"] ?></div>
            <? endif; ?>

            <? if ($arItem["LINK"]): ?>
              <a class="home-media__link"
                 href="<?= htmlspecialcharsbx($arItem["LINK"]) ?>"
              ><?= GetMessage("HOME_MEDIA_LINK_TEXT") ?></a>
            <? endif; ?>
          </div>
        </article>
      <? endforeach; ?>
    </div>
  </div>
<? endif; ?>
