<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/inc/page_vars.php';
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head>
    <?
    use Bitrix\Main\Page\Asset;
    use Bitrix\Main\UI\Extension;

    // HEADERS
    $APPLICATION->ShowHead();
    ?>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title><? $APPLICATION->ShowTitle() ?></title>

    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH ?>/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?=SITE_TEMPLATE_PATH ?>/favicon/favicon.svg" />
    <link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH ?>/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH ?>/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="<?=SITE_TEMPLATE_PATH ?>/favicon/site.webmanifest" />

    <?
    // CSS
    /*//Пример подключения CSS
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/swiper/swiper-bundle.css');*/
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/normalize.css/normalize.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/ozimnad-reset/reset.min.css');

    // JS
    /*//Пример подключения JS
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/jquery/dist/jquery.min.js');*/

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/scripts.js');
    ?>
    
</head>
<body data-theme="<?= $isLightTheme ? 'light' : 'dark' ?>">

<!--wrapper-->
<div class="wrapper">

    <div class="wrapper__panel">
        <? $APPLICATION->ShowPanel(); ?>
    </div>

    <div class="wrapper__header">
        <? include_once $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/page_blocks/header.php'; ?>
    </div>

    <!--wrapper__content-->
    <div class="wrapper__content">
    <? if (!$isMain): ?>
      <? if (!$widePage): ?>
        <!--container-->
        <div class="container">
      <? endif; ?>
        <!--page-->
        <div class="page">
          <? if ($showBreadcrumbs): ?>
            <div class="page__breadcrumbs">
                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "main", [
                    "START_FROM" => "0", // номер пункта, с которого строится цепочка
                    "PATH" => "",        // путь, для которого строится цепочка (по умолчанию текущий)
                    "SITE_ID" => SITE_ID,
                ]); ?>
            </div>
          <? endif; ?>

          <? if ($showTitle): ?>
            <h1 class="page__title"><? $APPLICATION->ShowTitle(false); ?></h1>
          <? endif; ?>

          <? if ($showAside): ?>
            <!--page__grid-->
            <div class="page__grid">
              <aside class="page__aside">
                  <? include_once $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/page_blocks/aside.php'; ?>
              </aside>
              <!--page__main-->
              <main class="page__main">
          <? else: ?>
              <!--page__main-->
              <main class="page__main">
          <? endif; ?>
    <? endif; ?>

