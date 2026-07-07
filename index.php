<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/css/pages/home.css");
$APPLICATION->SetTitle("Ресторан в Темноте?! Ужин в ТЕМНОТЕ");
?>
<? include_once 'indexContent.php';?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
