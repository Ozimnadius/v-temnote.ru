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
$widePage = $APPLICATION->GetProperty("wide_page") === 'Y'; // страница без .container; включить: 'Y'

// Путь запроса глубже каталога страницы = детальная страница SEF-компонента (список: /news/, деталь: /news/kod-novosti/)
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (substr($requestPath, -9) === 'index.php') $requestPath = substr($requestPath, 0, -9);
$requestPath = rtrim($requestPath, '/') . '/';
$isSefDetail = $requestPath !== $CurDir;

// Боковая колонка: на всём разделе ('show_aside' = Y) или только на детальных SEF-страницах ('show_aside_detail' = Y)
$showAside = $APPLICATION->GetProperty("show_aside") === 'Y'
    || ($APPLICATION->GetProperty("show_aside_detail") === 'Y' && $isSefDetail);
