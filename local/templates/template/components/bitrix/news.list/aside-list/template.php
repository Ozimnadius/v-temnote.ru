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
  <div class="aside-list">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
      <div class="aside-list__pager aside-list__pager--top">
        <?= $arResult["NAV_STRING"] ?>
      </div>
    <? endif; ?>

    <div class="aside-list__items">
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage("CT_BNL_ELEMENT_DELETE_CONFIRM")));

        $card = $arItem["ASIDE_CARD"];
        ?>

        <article class="<?= $card["CLASS"] ?>"
                 id="<?= $this->GetEditAreaId($arItem["ID"]); ?>"
        >
          <? if ($card["HAS_NAME"]): ?>
            <h3 class="aside-list__title"><?= $card["NAME"] ?></h3>
          <? endif; ?>

          <? if ($card["HAS_PREVIEW_TEXT"]): ?>
            <div class="aside-list__text">
              <?= $card["PREVIEW_TEXT"] ?>
            </div>
          <? endif; ?>

          <? if ($card["HAS_PICTURE"]): ?>
            <div class="aside-list__picture">
              <img
                class="aside-list__image"
                src="<?= $card["PICTURE"]["SRC"] ?>"
                width="<?= $card["PICTURE"]["WIDTH"] ?>"
                height="<?= $card["PICTURE"]["HEIGHT"] ?>"
                alt="<?= $card["PICTURE"]["ALT"] ?>"
                title="<?= $card["PICTURE"]["TITLE"] ?>"
                loading="lazy"
              >
            </div>
          <? endif; ?>

          <? if ($card["HAS_DETAIL_TEXT"]): ?>
            <div class="aside-list__detail">
              <?= $card["DETAIL_TEXT"] ?>
            </div>
          <? endif; ?>

          <? if ($card["HAS_LINK"]): ?>
            <a class="aside-list__link" href="<?= htmlspecialcharsbx($card["LINK"]) ?>">
              <?= htmlspecialcharsbx($card["LINK_TEXT"]) ?>
            </a>
          <? endif; ?>
        </article>
      <? endforeach; ?>
    </div>

    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
      <div class="aside-list__pager">
        <?= $arResult["NAV_STRING"] ?>
      </div>
    <? endif; ?>
  </div>
<? endif; ?>
