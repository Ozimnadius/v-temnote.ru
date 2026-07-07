<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

/**
 * @global CMain $APPLICATION
 */

$APPLICATION->SetTitle("Контакты");
$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/css/pages/contacts.css");
?>
<div class="contacts">
  <div class="contacts__info">
    <section class="contacts-info"
             aria-label="Контактная информация"
    >
      <div class="contacts-info__content">
        <dl class="contacts-info__list">

          <div class="contacts-info__item">
            <img class="contacts-info__icon"
                 src="/upload/images/icons/map-marker.svg"
                 width="45"
                 height="45"
                 alt=""
                 aria-hidden="true"
            >
            <div class="contacts-info__text">
              <dt class="contacts-info__label text-18">Адрес:</dt>
              <dd class="contacts-info__value text-24">
                <? $APPLICATION->IncludeComponent(
                  "bitrix:main.include",
                  ".default",
                  [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "COMPONENT_TEMPLATE" => ".default",
                    "PATH" => "/include/address.php"
                  ],
                  false
                ); ?>
              </dd>
            </div>
          </div>

          <div class="contacts-info__item">
            <img class="contacts-info__icon"
                 src="/upload/images/icons/phone-in-talk.svg"
                 width="45"
                 height="45"
                 alt=""
                 aria-hidden="true"
            >
            <div class="contacts-info__text">
              <dt class="contacts-info__label text-18">Телефон:</dt>
              <dd class="contacts-info__value text-24">
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
              </dd>
            </div>
          </div>

          <div class="contacts-info__item">
            <img class="contacts-info__icon"
                 src="/upload/images/icons/email.svg"
                 width="45"
                 height="45"
                 alt=""
                 aria-hidden="true"
            >
            <div class="contacts-info__text">
              <dt class="contacts-info__label text-18">E-mail:</dt>
              <dd class="contacts-info__value text-24">
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
              </dd>
            </div>
          </div>

          <div class="contacts-info__item">
            <img class="contacts-info__icon"
                 src="/upload/images/icons/clock-time-four.svg"
                 width="45"
                 height="45"
                 alt=""
                 aria-hidden="true"
            >
            <div class="contacts-info__text">
              <dt class="contacts-info__label text-18">Режим работы:</dt>
              <dd class="contacts-info__value text-24">
                <? $APPLICATION->IncludeComponent(
                  "bitrix:main.include",
                  ".default",
                  [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "COMPONENT_TEMPLATE" => ".default",
                    "PATH" => "/include/worktime.php"
                  ],
                  false
                ); ?>
              </dd>
            </div>
          </div>
        </dl>

        <a class="contacts-info__mail btn btn--outline"
           href="mailto:v-temnote2007@yandex.ru"
        >
          <span class="contacts-info__mail-text">Сообщение на почту</span>
          <svg class="contacts-info__mail-icon"
               width="24"
               height="24"
               viewBox="0 0 20 20"
               aria-hidden="true"
               focusable="false"
          >
            <path d="M16.668 6.66683L10.0013 10.8335L3.33464 6.66683V5.00016L10.0013 9.16683L16.668 5.00016M16.668 3.3335H3.33464C2.40964 3.3335 1.66797 4.07516 1.66797 5.00016V15.0002C1.66797 15.4422 1.84356 15.8661 2.15612 16.1787C2.46868 16.4912 2.89261 16.6668 3.33464 16.6668H16.668C17.11 16.6668 17.5339 16.4912 17.8465 16.1787C18.159 15.8661 18.3346 15.4422 18.3346 15.0002V5.00016C18.3346 4.07516 17.5846 3.3335 16.668 3.3335Z"
                  fill="currentColor"
            />
          </svg>
        </a>

      </div>

      <div class="contacts-info__picture">
        <? $APPLICATION->IncludeComponent(
          "bitrix:main.include",
          ".default",
          [
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => "/include/contacts/photo.php"
          ],
          false
        ); ?>
      </div>

    </section>
  </div>

  <div class="contacts__map">
    <? $APPLICATION->IncludeComponent(
      "web-comp:yandex.map",
      "contacts-map",
      [
        "TITLE" => "Посмотреть на карте",
        "RATING_WIDGET_URL" => "https://yandex.ru/sprav/widget/rating-badge/1325192770?type=award&theme=dark",
        "RATING_TITLE" => "Рейтинг ресторана В темноте?! на Яндекс Картах",
        "CENTER_LAT" => "55.784690",
        "CENTER_LNG" => "37.614140",
        "ZOOM" => "17",
        "PLACEMARK_LABEL" => "Ресторан В ТЕМНОТЕ?!",
        "MAP_ARIA_LABEL" => "Темная карта ресторана В темноте?!"
      ],
      false
    ); ?>
  </div>

  <div class="contacts__reviews">
    <section class="contacts-reviews">
      <h2 class="contacts-reviews__title h2">
        <? $APPLICATION->IncludeComponent(
          "bitrix:main.include",
          ".default",
          [
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => "/include/contacts/reviews-title.php"
          ],
          false
        ); ?>
      </h2>

      <div class="contacts-reviews__widget">
        <? $APPLICATION->IncludeComponent(
          "bitrix:main.include",
          ".default",
          [
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => "/include/contacts/reviews.php"
          ],
          false
        ); ?>
      </div>
    </section>
  </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
