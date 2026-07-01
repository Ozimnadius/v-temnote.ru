<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="header">

  <div class="header__top">
    <div class="header__info">
      <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
          "AREA_FILE_SHOW" => "file",
          "AREA_FILE_SUFFIX" => "inc",
          "EDIT_TEMPLATE" => "",
          "COMPONENT_TEMPLATE" => ".default",
          "PATH" => "/include/header/info.php"
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
          "PATH" => "/include/phone.php"
        ],
        false
      ); ?>
    </div>
  </div>

  <div class="container">
    <div class="header__bottom">

      <a class="header__logo"
         href="/"
         aria-label="В темноте?!"
      ></a>

      <div class="header__menu">
        <? $APPLICATION->IncludeComponent(
          "bitrix:menu",
          "main",
          [
            "ROOT_MENU_TYPE" => "main",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => "",
            "MAX_LEVEL" => "2",
            "CHILD_MENU_TYPE" => "section",
            "USE_EXT" => "N",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            "COMPONENT_TEMPLATE" => "vertical_multilevel"
          ],
          false
        ); ?>
      </div>
      <? if (!empty($isLightTheme)): ?>
        <a class="header__cart"
           href="/order/"
           aria-label="Корзина"
        >
          <img class="header__cart-icon"
               src="/upload/images/cart.svg"
               width="42"
               height="42"
               alt=""
          >
        </a>
      <? endif; ?>
    </div>
  </div>
</div>
