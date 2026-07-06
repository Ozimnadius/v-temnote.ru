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

if (!$arResult["NavShowAlways"]) {
  if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) {
    return;
  }
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");

$currentPage = (int)$arResult["NavPageNomer"];
$pageCount = (int)$arResult["NavPageCount"];
$navNum = (int)$arResult["NavNum"];

$getPageUrl = static function (int $page) use ($arResult, $strNavQueryString, $strNavQueryStringFull, $navNum): string {
  if ($page <= 1 && !$arResult["bSavePage"]) {
    return $arResult["sUrlPath"] . $strNavQueryStringFull;
  }

  return $arResult["sUrlPath"] . "?" . $strNavQueryString . "PAGEN_" . $navNum . "=" . $page;
};

$formatPageNumber = static function (int $page): string {
  return str_pad((string)$page, 2, "0", STR_PAD_LEFT);
};

$pageItems = [];

if ($pageCount <= 7) {
  for ($page = 1; $page <= $pageCount; $page++) {
    $pageItems[] = $page;
  }
} else {
  $startPage = max(2, $currentPage - 2);
  $endPage = min($pageCount - 1, $currentPage + 2);

  if ($startPage <= 3) {
    $startPage = 2;
  }

  if ($endPage >= $pageCount - 2) {
    $endPage = $pageCount - 1;
  }

  $pageItems[] = 1;

  if ($startPage > 2) {
    $pageItems[] = [
      "type" => "gap",
      "page" => $startPage - 1,
    ];
  }

  for ($page = $startPage; $page <= $endPage; $page++) {
    $pageItems[] = $page;
  }

  if ($endPage < $pageCount - 1) {
    $pageItems[] = [
      "type" => "gap",
      "page" => $endPage + 1,
    ];
  }

  $pageItems[] = $pageCount;
}

$prevPage = max(1, $currentPage - 1);
$nextPage = min($pageCount, $currentPage + 1);
$navLabel = $arResult["NavTitle"] ?: "Пагинация";
?>

<nav class="pagination" aria-label="<?= htmlspecialcharsbx($navLabel) ?>">
  <? if ($currentPage > 1): ?>
    <a class="pagination__prev"
       href="<?= $getPageUrl($prevPage) ?>"
       rel="prev"
       aria-label="Предыдущая страница">
      <svg class="pagination__prev-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M15 6L9 12L15 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span class="pagination__prev-text">Назад</span>
    </a>
  <? else: ?>
    <span class="pagination__arrow pagination__arrow--prev pagination__arrow--disabled" aria-hidden="true">
      <svg class="pagination__arrow-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9 6L15 12L9 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  <? endif; ?>

  <ul class="pagination__list">
    <? foreach ($pageItems as $pageItem): ?>
      <? if (is_array($pageItem) && $pageItem["type"] === "gap"): ?>
        <? $gapPage = (int)$pageItem["page"]; ?>
        <li class="pagination__item">
          <a class="pagination__link pagination__ellipsis"
             href="<?= $getPageUrl($gapPage) ?>"
             aria-label="Перейти к странице <?= $formatPageNumber($gapPage) ?>">...</a>
        </li>
      <? else: ?>
        <? $page = (int)$pageItem; ?>
        <li class="pagination__item">
          <? if ($page === $currentPage): ?>
            <span class="pagination__link pagination__link--current" aria-current="page">
              <?= $formatPageNumber($page) ?>
            </span>
          <? else: ?>
            <a class="pagination__link" href="<?= $getPageUrl($page) ?>">
              <?= $formatPageNumber($page) ?>
            </a>
          <? endif; ?>
        </li>
      <? endif; ?>
    <? endforeach; ?>
  </ul>

  <? if ($currentPage < $pageCount): ?>
    <a class="pagination__next" href="<?= $getPageUrl($nextPage) ?>" rel="next">
      <span class="pagination__next-text">Дальше</span>
      <svg class="pagination__next-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9 6L15 12L9 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>
  <? else: ?>
    <span class="pagination__arrow pagination__arrow--disabled" aria-hidden="true">
      <svg class="pagination__arrow-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9 6L15 12L9 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  <? endif; ?>

  <? if ($arResult["bShowAll"]): ?>
    <noindex>
      <? if ($arResult["NavShowAll"]): ?>
        <a class="pagination__all" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $navNum ?>=0" rel="nofollow">
          <?= GetMessage("nav_paged") ?>
        </a>
      <? else: ?>
        <a class="pagination__all" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $navNum ?>=1" rel="nofollow">
          <?= GetMessage("nav_all") ?>
        </a>
      <? endif; ?>
    </noindex>
  <? endif; ?>
</nav>
