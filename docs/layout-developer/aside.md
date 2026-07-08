# Боковая Колонка (aside)

## Назначение

Этот документ описывает архитектуру боковой колонки страниц (`page__aside`): как она включается, откуда берет содержимое и по каким правилам наполняется.

Эталон реализации — раздел `restoran/about/`.

## Как Это Работает

Цепочка вывода:

1. `.section.php` раздела включает колонку свойством `show_aside` или `show_aside_detail`.
2. `local/templates/template/inc/page_vars.php` читает свойства и вычисляет `$showAside`.
3. `local/templates/template/header.php` при `$showAside` строит сетку `page__grid` с `aside.page__aside` и `main.page__main`. В DOM колонка стоит до контента; визуально она справа, на мобильном — под контентом (управляется стилями `_page.scss`).
4. Внутри `page__aside` подключается диспетчер `local/templates/template/page_blocks/aside.php`, который:
   - через `bitrix:main.include` (`AREA_FILE_SHOW => "sect"`, `AREA_FILE_SUFFIX => "aside"`) выводит файл `sect_aside.php` из каталога текущего раздела;
   - выводит отложенный слот `$APPLICATION->ShowViewContent('aside')` — компоненты страницы могут дописать в колонку контент вызовом `$APPLICATION->AddViewContent('aside', $html)`.

При добавлении колонки на новые страницы `header.php`, `footer.php` и `page_blocks/aside.php` не изменяются.

## Включение Колонки На Разделе

1. В `.section.php` раздела добавить свойство:

```php
$arDirProperties = Array(
	"show_aside" => "Y",
);
```

2. Рядом с `index.php` раздела создать файл `sect_aside.php`:

```php
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<!-- содержимое колонки -->
```

Нет свойства — нет колонки и сетки `page__grid`. Нет файла `sect_aside.php` — колонка выведется пустой, поэтому файл обязателен.

## SEF-Разделы: Колонка Только На Детальных Страницах

Для комплексных компонентов с ЧПУ (`news/`, `pressa/`, `photo/` в `urlrewrite.php`) список и детальная страница — один физический `index.php`. Чтобы колонка была только на детальных страницах, вместо `show_aside` используется:

```php
"show_aside_detail" => "Y",
```

`page_vars.php` определяет детальную страницу по URL: путь запроса глубже каталога раздела (`/news/` — список, `/news/kod-novosti/` — детальная). Пагинация (`/news/?PAGEN_1=2`) остается списком.

Динамическое наполнение с детальной страницы (например, «другие новости») добавляется из шаблона компонента через `$APPLICATION->AddViewContent('aside', $html)`.

## Наполнение: Библиотека Карточек

Промо-карточки, повторяющиеся между страницами, живут по одному разу в `include/aside/` — это include-области, редактируемые из админки. `sect_aside.php` раздела перечисляет нужные карточки в нужном порядке:

```php
<? $APPLICATION->IncludeComponent("bitrix:main.include", ".default", [
	"AREA_FILE_SHOW" => "file",
	"AREA_FILE_SUFFIX" => "inc",
	"EDIT_TEMPLATE" => "",
	"COMPONENT_TEMPLATE" => ".default",
	"PATH" => "/include/aside/certificates.php"
], false); ?>
```

Карточка — BEM-блок `aside-card` (элементы `__image`, `__title`, `__text`, `__link`); порядок элементов внутри карточки свободный и следует дизайну.

Блоки, уникальные для одной страницы (расписание, реквизиты и т. п.), верстаются прямо в `sect_aside.php` этой страницы своими BEM-блоками и в библиотеку не выносятся.

## Стили

- Сетка (`page__grid`, `page__main`, `page__aside`) описана в `local/templates/template/css/blocks/_page.scss`: две колонки `1fr 401px` с gap 30px, ниже брейкпоинта `sm` — одна колонка, контент выше колонки.
- Стили карточек и других блоков колонки — обычные BEM-партиалы в `css/blocks/` по правилам `scss-structure.md`.

## Свойства В Админке

`show_aside` и `show_aside_detail` зарегистрированы в настройках модуля «Управление структурой» (список «Типы свойств») — их можно включать из админки в свойствах папки, Битрикс запишет их в `.section.php`. Регистрация хранится в БД: на новой копии сайта ее нужно повторить.

## Запреты

Запрещается:

- выводить боковую колонку в обход механизма (верстать aside вручную в теле страницы);
- менять `header.php`, `footer.php` или `page_blocks/aside.php` ради колонки конкретной страницы;
- создавать `sect_aside.php` без страховки `B_PROLOG_INCLUDED`;
- дублировать разметку повторяющихся карточек по страницам — они живут в `include/aside/`;
- включать `show_aside` и `show_aside_detail` одновременно на одном разделе.
