<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/**
 * @global CMain $APPLICATION
 */

$APPLICATION->SetTitle("UI Styleguide");
$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/css/pages/styleguide.css");

$colors = [
  ["White", "--Colors-White", "#ffffff"],
  ["BG Black", "--Colors-BG-Black", "#101010"],
  ["BG Black 2", "--Colors-BG-Black-2", "#1b1b1b"],
  ["Gray 1", "--Colors-Gray-1", "#525252"],
  ["Gray 2", "--Colors-Gray-2", "#424242"],
  ["Gray 3", "--Colors-Gray-3", "#bfbfbf"],
  ["Gray 4", "--Colors-Gray-4", "#eeeeee"],
  ["Blue Accident", "--Colors-Blue-Accident", "#5ab9e7"],
  ["Blue Accident 2", "--Colors-Blue-Accident-2", "#259fda"],
];

$tokens = [
  ["--bg", "фон страницы"],
  ["--bg-surface", "поверхность / карточка"],
  ["--text", "текст"],
  ["--text-muted", "приглушённый текст"],
  ["--border", "рамка"],
  ["--accent", "акцент"],
  ["--accent-hover", "акцент · hover"],
];

$type = [
  ["h1", "Heading 1 · Poiret One 70"],
  ["h1-display", "Heading 1 display · 54"],
  ["h2", "Heading 2 · 50"],
  ["h2-upper", "Heading 2 upper · 40"],
  ["h3", "Heading 3 · 27"],
  ["h4", "Heading 4 · 24"],
  ["text-accent", "Text accent (Poiret) · 24"],
  ["text-36", "Text 36 · uppercase"],
  ["text-24", "Text 24"],
  ["text-18", "Text 18"],
  ["text-16-medium", "Text 16 · medium"],
  ["text-16-regular", "Text 16 · regular"],
  ["text-16", "Text 16 · light"],
  ["text-15", "Text 15"],
  ["text-14", "Text 14"],
  ["menu-18", "Menu 18"],
  ["btn-16", "Btn 16"],
  ["menu-15", "Menu 15"],
];

$sample = "Съешь же ещё этих мягких французских булок да выпей чаю";

// стрелка-шеврон для card-кнопки (как в offers.list)
$arrow = '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.52856 5.43274C6.60465 5.50883 6.6427 5.59633 6.6427 5.69525C6.6427 5.79417 6.60465 5.88167 6.52856 5.95776L1.20984 11.2765C1.13375 11.3526 1.04624 11.3906 0.947327 11.3906C0.848409 11.3906 0.760905 11.3526 0.684814 11.2765L0.114136 10.7058C0.0380452 10.6297 0 10.5422 0 10.4433C0 10.3444 0.0380452 10.2569 0.114136 10.1808L4.59967 5.69525L0.114136 1.20972C0.0380452 1.13363 0 1.04612 0 0.947205C0 0.848287 0.0380452 0.760783 0.114136 0.684692L0.684814 0.114014C0.760905 0.0379229 0.848409 0 0.947327 0C1.04624 0 1.13375 0.0379229 1.20984 0.114014L6.52856 5.43274Z" fill="currentColor"/></svg>';
?>
<div class="styleguide">

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Цвета</h2>
    <div class="styleguide__swatches">
      <? foreach ($colors as [$name, $var, $hex]): ?>
        <div class="styleguide__swatch">
          <div class="styleguide__chip" style="background: var(<?= $var ?>)"></div>
          <div class="styleguide__meta">
            <b><?= $name ?></b>
            <span class="styleguide__label"><?= $hex ?></span>
          </div>
        </div>
      <? endforeach; ?>
    </div>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Семантические токены (тёмная тема)</h2>
    <div class="styleguide__swatches">
      <? foreach ($tokens as [$var, $label]): ?>
        <div class="styleguide__swatch">
          <div class="styleguide__chip" style="background: var(<?= $var ?>)"></div>
          <div class="styleguide__meta">
            <b><?= $var ?></b>
            <span class="styleguide__label"><?= $label ?></span>
          </div>
        </div>
      <? endforeach; ?>
    </div>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Типографика</h2>
    <? foreach ($type as [$cls, $label]): ?>
      <div class="styleguide__type">
        <span class="styleguide__label"><?= $label ?> — .<?= $cls ?></span>
        <p class="<?= $cls ?>"><?= $sample ?></p>
      </div>
    <? endforeach; ?>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Кнопки</h2>
    <div class="styleguide__row">
      <a class="btn btn--primary">В корзину</a>
      <a class="btn btn--outline">Забронировать столик</a>
      <a class="btn btn--white">Заказать</a>
      <a class="btn btn--card">Заказать места <?= $arrow ?></a>
      <a class="btn btn--inline">Сделать заказ</a>
      <a class="btn btn--outline btn--arrow">Все новости</a>
      <a class="btn btn--primary btn--upper">Оформить заказ</a>
    </div>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Ссылки</h2>
    <div class="styleguide__row">
      <a class="link-15" href="#">Ссылка · link-15</a>
      <a class="link-14" href="#">Ссылка · link-14</a>
    </div>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Светлая тема (/catalog/)</h2>
    <div class="styleguide__theme" data-theme="light">
      <div class="styleguide__row">
        <a class="btn btn--primary">В корзину</a>
        <a class="btn btn--outline">Забронировать</a>
      </div>
      <span class="styleguide__label">btn--white и btn--card — варианты для тёмных карточек (белый текст), на светлой поверхности не используются.</span>
      <p class="h3">Заголовок H3 на светлой теме (Inter)</p>
      <p class="text-16"><?= $sample ?></p>
    </div>
  </section>

  <section class="styleguide__block">
    <h2 class="styleguide__title h3">Контентная разметка</h2>
    <span class="styleguide__label">
      Применяется классом .content или свойством раздела «Контентная страница» (content_layout = Y).
    </span>

    <div class="styleguide__content content">
      <p>
        Базовый текст контентной страницы. Внутри него могут использоваться
        <strong>сильное выделение</strong>, <em>смысловой акцент</em> и
        <a href="#">обычная ссылка</a>.
      </p>
      <p>
        Соседние абзацы разделяются одним базовым шагом, а разные смысловые элементы — увеличенным интервалом.
      </p>

      <h2>Заголовок второго уровня</h2>
      <p>Используется для основных разделов длинной публикации или информационной страницы.</p>

      <h3>Заголовок третьего уровня</h3>
      <p>Подходит для подразделов внутри крупной смысловой части.</p>

      <h4>Заголовок четвёртого уровня</h4>
      <p>Используется для небольших вложенных разделов.</p>

      <h5>Заголовок пятого уровня</h5>
      <p>Дополнительный уровень структурирования контента.</p>

      <h6>Заголовок шестого уровня</h6>
      <p>Минимальный заголовок внутри текстовой страницы.</p>

      <ul>
        <li>Первый пункт маркированного списка</li>
        <li>Второй пункт маркированного списка</li>
        <li>Третий пункт маркированного списка</li>
      </ul>

      <ol>
        <li>Первый шаг нумерованного списка</li>
        <li>Второй шаг нумерованного списка</li>
        <li>Третий шаг нумерованного списка</li>
      </ol>

      <figure>
        <img src="/upload/images/menu/bar.jpg"
             width="1379"
             height="489"
             alt="Интерьер бара ресторана">
        <figcaption>Пример изображения с подписью.</figcaption>
      </figure>

      <blockquote>
        <p>Информационная цитата или важное замечание внутри контентной страницы.</p>
      </blockquote>

      <hr>

      <table>
        <thead>
          <tr>
            <th>Элемент</th>
            <th>Назначение</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Абзац</td>
            <td>Основной текст страницы</td>
          </tr>
          <tr>
            <td>Цитата</td>
            <td>Акцентная информация</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

</div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
