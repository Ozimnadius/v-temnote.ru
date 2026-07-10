<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

/**
 * @global CMain $APPLICATION
 */

$APPLICATION->SetTitle("Правила и расписание");

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/libs/@fancyapps/ui/dist/fancybox/fancybox.min.css");
$asset->addCss(SITE_TEMPLATE_PATH . "/css/pages/pravila.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/libs/@fancyapps/ui/dist/fancybox/fancybox.umd.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/pages/pravila.js");
?>
<div class="pravila">
  <div class="pravila__schedule">
    <section class="pravila__schedule-card" aria-labelledby="pravila-schedule-title">
      <div class="pravila__schedule-header">
        <h2 id="pravila-schedule-title">
          <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            [
              "AREA_FILE_SHOW" => "file",
              "AREA_FILE_SUFFIX" => "inc",
              "EDIT_TEMPLATE" => "",
              "COMPONENT_TEMPLATE" => ".default",
              "PATH" => "/include/restoran/pravila/schedule-title.php"
            ],
            false
          ); ?>
        </h2>

        <div class="pravila__video">
          <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            [
              "AREA_FILE_SHOW" => "file",
              "AREA_FILE_SUFFIX" => "inc",
              "EDIT_TEMPLATE" => "",
              "COMPONENT_TEMPLATE" => ".default",
              "PATH" => "/include/restoran/pravila/video.php"
            ],
            false
          ); ?>
        </div>
      </div>

      <ul class="pravila__schedule-list">
        <? $APPLICATION->IncludeComponent(
          "bitrix:main.include",
          ".default",
          [
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => "/include/restoran/pravila/schedule.php"
          ],
          false
        ); ?>
      </ul>
    </section>

    <div class="pravila__notes text-16">
      <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
          "AREA_FILE_SHOW" => "file",
          "AREA_FILE_SUFFIX" => "inc",
          "EDIT_TEMPLATE" => "",
          "COMPONENT_TEMPLATE" => ".default",
          "PATH" => "/include/restoran/pravila/notes.php"
        ],
        false
      ); ?>
    </div>
  </div>

  <div class="pravila__content content">
    <? $APPLICATION->IncludeComponent(
      "bitrix:main.include",
      ".default",
      [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "COMPONENT_TEMPLATE" => ".default",
        "PATH" => "/include/restoran/pravila/intro.php"
      ],
      false
    ); ?>

    <? $APPLICATION->IncludeComponent(
      "bitrix:main.include",
      ".default",
      [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "COMPONENT_TEMPLATE" => ".default",
        "PATH" => "/include/restoran/pravila/rules.php"
      ],
      false
    ); ?>
  </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
