<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);
?>
<? if (!empty($arResult["ITEMS"])): ?>
  <div class="offers-list">
    <div class="offers-list__items">
      <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction(
          $arItem["ID"],
          $arItem["EDIT_LINK"],
          CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT")
        );
        $this->AddDeleteAction(
          $arItem["ID"],
          $arItem["DELETE_LINK"],
          CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
          array("CONFIRM" => "Будет удален весь элемент. Продолжить?")
        );

        $card = $arItem["OFFER_CARD"];
        ?>

        <article
          class="<?= $card["CLASS"] ?>"
          id="<?= $this->GetEditAreaId($arItem["ID"]); ?>"
        >
          <? if ($card["HAS_PICTURE"]): ?>
            <img
              class="offers-list__image"
              src="<?= htmlspecialcharsbx($card["PICTURE_SRC"]) ?>"
              width="<?= $card["PICTURE_WIDTH"] ?>"
              height="<?= $card["PICTURE_HEIGHT"] ?>"
              alt="<?= htmlspecialcharsbx($card["PICTURE_ALT"]) ?>"
              loading="lazy"
            >
          <? endif; ?>

          <div class="offers-list__body">
            <? if ($card["HAS_NAME"]): ?>
              <h2 class="offers-list__title"><?= htmlspecialcharsbx($card["NAME"]) ?></h2>
            <? endif; ?>

            <? if ($card["HAS_TEXT"]): ?>
              <div class="offers-list__text">
                <?= $card["TEXT"] ?>
              </div>
            <? endif; ?>

            <? if ($card["HAS_LINK"]): ?>
              <a
                class="offers-list__link btn btn--card"
                href="<?= htmlspecialcharsbx($card["LINK"]) ?>"
              >
                <?= htmlspecialcharsbx($card["LINK_TEXT"]) ?>
                <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6.52856 5.43274C6.60465 5.50883 6.6427 5.59633 6.6427 5.69525C6.6427 5.79417 6.60465 5.88167 6.52856 5.95776L1.20984 11.2765C1.13375 11.3526 1.04624 11.3906 0.947327 11.3906C0.848409 11.3906 0.760905 11.3526 0.684814 11.2765L0.114136 10.7058C0.0380452 10.6297 0 10.5422 0 10.4433C0 10.3444 0.0380452 10.2569 0.114136 10.1808L4.59967 5.69525L0.114136 1.20972C0.0380452 1.13363 0 1.04612 0 0.947205C0 0.848287 0.0380452 0.760783 0.114136 0.684692L0.684814 0.114014C0.760905 0.0379229 0.848409 -0.00012207 0.947327 -0.00012207C1.04624 -0.00012207 1.13375 0.0379229 1.20984 0.114014L6.52856 5.43274Z" fill="currentColor"/>
                </svg>
              </a>
            <? endif; ?>
          </div>
        </article>
      <? endforeach; ?>
    </div>
  </div>
<? endif; ?>
