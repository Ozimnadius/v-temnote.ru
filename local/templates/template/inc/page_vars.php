<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * Переменные страницы для каркаса шаблона (header.php, footer.php, page_blocks).
 * Подключается в начале header.php; благодаря include видны и в footer.php.
 */

$CurDir = $APPLICATION->GetCurDir();

$isMain = $CurDir === '/'; // главная страница
$isLightTheme = strpos($CurDir, '/catalog/') === 0 || strpos($CurDir, '/order/') === 0; // светлая тема в каталоге и корзине

// Флаги из свойств страницы ($APPLICATION->SetPageProperty('...', ...))
$showTitle = $APPLICATION->GetProperty("show_title") !== 'N'; // заголовок страницы; скрыть: 'N'
$showBreadcrumbs = $APPLICATION->GetProperty("show_breadcrumbs") !== 'N'; // хлебные крошки; скрыть: 'N'
$showAside = $APPLICATION->GetProperty("show_aside") === 'Y'; // боковая колонка; включить: 'Y'
$widePage = $APPLICATION->GetProperty("wide_page") === 'Y'; // страница без .container; включить: 'Y'
