<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 */
?>

<? if (!empty($arResult)): ?>
  <ul class="footer-menu">

    <?
    foreach ($arResult as $arItem):
      if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
        continue;
      ?>
      <? if ($arItem["SELECTED"]): ?>
      <li class="footer-menu__item">
        <a href="<?= $arItem["LINK"] ?>"
           class="footer-menu__link selected"
        ><?= $arItem["TEXT"] ?></a>
      </li>
    <? else: ?>
      <li class="footer-menu__item">
        <a href="<?= $arItem["LINK"] ?>"
           class="footer-menu__link"
        ><?= $arItem["TEXT"] ?></a>
      </li>
    <? endif ?>

    <? endforeach ?>

  </ul>
<? endif ?>