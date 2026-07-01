<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="footer">
  <div class="container">
    <div class="footer__top">

      <a class="footer__logo header__logo"
         href="/"
         aria-label="В темноте?!"
      ></a>

      <div class="footer__menu">
        <? $APPLICATION->IncludeComponent(
          "bitrix:menu",
          "bottom",
          [
            "ROOT_MENU_TYPE" => "bottom",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => "",
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "left",
            "USE_EXT" => "N",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            "COMPONENT_TEMPLATE" => ".default"
          ],
          false
        ); ?>
      </div>

    </div>
    <div class="footer__bottom">
      <div class="footer__left">
        <div class="footer__contacts">

          <div class="footer__contact">
            <div class="footer__contact-icon">
              <img src="/upload/images/icons/email.svg"
                   alt="email"
              >
            </div>
            <div class="footer__contact-link">
              <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                [
                  "AREA_FILE_SHOW" => "file",
                  "AREA_FILE_SUFFIX" => "inc",
                  "EDIT_TEMPLATE" => "",
                  "COMPONENT_TEMPLATE" => ".default",
                  "PATH" => "/include/email.php"
                ],
                false
              ); ?>
            </div>
          </div>

          <div class="footer__contact">
            <div class="footer__contact-icon">
              <img src="/upload/images/icons/cart.svg"
                   alt="cart"
              >
            </div>
            <div class="footer__contact-link">
              <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                [
                  "AREA_FILE_SHOW" => "file",
                  "AREA_FILE_SUFFIX" => "inc",
                  "EDIT_TEMPLATE" => "",
                  "COMPONENT_TEMPLATE" => ".default",
                  "PATH" => "/include/footer/order.php"
                ],
                false
              ); ?>
            </div>
          </div>

          <div class="footer__contact">
            <div class="footer__contact-icon">
              <img src="/upload/images/icons/phone-in-talk.svg"
                   alt="email"
              >
            </div>
            <div class="footer__contact-link">
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

          <div class="footer__contact">
            <div class="footer__contact-icon">
              <img src="/upload/images/icons/information.svg"
                   alt="email"
              >
            </div>
            <div class="footer__contact-link">
              <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                [
                  "AREA_FILE_SHOW" => "file",
                  "AREA_FILE_SUFFIX" => "inc",
                  "EDIT_TEMPLATE" => "",
                  "COMPONENT_TEMPLATE" => ".default",
                  "PATH" => "/include/policy.php"
                ],
                false
              ); ?>
            </div>
          </div>

        </div>
      </div>
      <div class="footer__center">
        <div class="footer__info">
          <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            [
              "AREA_FILE_SHOW" => "file",
              "AREA_FILE_SUFFIX" => "inc",
              "EDIT_TEMPLATE" => "",
              "COMPONENT_TEMPLATE" => ".default",
              "PATH" => "/include/footer/info.php"
            ],
            false
          ); ?>
        </div>
      </div>
      <div class="footer__right">
        <div class="footer__socials">
          <? $APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"footer-socials", 
	[
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "4",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => [
			0 => "",
			1 => "",
		],
		"PROPERTY_CODE" => [
			0 => "",
			1 => "",
		],
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"COMPONENT_TEMPLATE" => "footer-socials",
		"SET_LAST_MODIFIED" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	],
	false
); ?>
        </div>
        <div class="footer__vendor">
          Сайт разработал:
          <a href="https://web-komp.ru/">Web-Comp</a>
        </div>
      </div>
    </div>
  </div>
</div>

