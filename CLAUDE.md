# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

Важно: `AGENTS.md` и `CLAUDE.md` должны оставаться синхронизированными. При изменении одного файла сразу вноси те же правила в другой; отличаться могут только название файла и строка про конкретный инструмент.

## Что это

Сайт на CMS **1С-Битрикс** (ресторан «В темноте?!»), запускается локально через OSPanel на PHP. Никакого фреймворка поверх Битрикса нет, тестов нет, CLI-сборщика тоже нет — проект редактируется в PhpStorm и работает на локальной установке Битрикса.

Контент и тексты интерфейса — на **русском**. PHP-файлы используют **короткие открывающие теги** (`<? ... ?>`), которые Битрикс требует (`short_open_tag = On`).

## Важно: что можно и что нельзя редактировать

- **`bitrix/`** — ядро Битрикса. Каталог в `.gitignore` и `.claudeignore`. **Никогда не редактируй его** — это вендорный код, перезаписывается при обновлениях платформы. Каждая страница подключает его через `require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php")` и `.../bitrix/footer.php`, но сами эти файлы менять нельзя.
- **`local/`** — здесь живёт весь кастомный код (современное соглашение Битрикса для своих компонентов, модулей и шаблонов). Почти вся работа идёт в `local/templates/template/`.
- **`upload/`** и **`node_modules/`** также игнорируются — генерируемое / вендорное содержимое.

## Сборка (File Watchers PhpStorm — без npm-скриптов)

`package.json` только *объявляет* фронтенд-библиотеки; в нём **нет скриптов сборки**. Компиляция ассетов выполняется через File Watchers PhpStorm (`.idea/watcherTasks.xml`), которые срабатывают при сохранении. Чтобы собрать вне IDE, запусти эквивалентные CLI-инструменты вручную из каталога файла:

| Источник | Инструмент | Команда (запускать в каталоге файла) | Результат |
|----------|------------|--------------------------------------|-----------|
| `*.scss` | Dart Sass | `sass styles.scss:styles.css` | `styles.css` + `styles.css.map` |
| `*.css` | csso | `csso -i styles.css -o styles.min.css` | `styles.min.css` |
| `*.js` | UglifyJS | `uglifyjs scripts.js -o scripts.min.js` | `scripts.min.js` |

То есть `styles.css`, `styles.min.css` и `scripts.min.js` — **генерируемые артефакты**: редактируй исходники `.scss` / `.js`, а не скомпилированный вывод.

Для шаблонов компонентов Битрикса действует то же правило: `local/templates/template/components/.../style.css` и `style.css.map` генерируются автоматически из `style.scss`. Вручную редактируй `style.scss`; `style.css` / `style.css.map` не создавай и не обновляй руками без отдельной просьбы.

npm-зависимости (`swiper`, `@fancyapps/ui`, `normalize.css`, `ozimnad-reset`) **вендорятся** копированием из `node_modules/` в `local/templates/template/libs/`; шаблон подключает их из `libs/`, а не из `node_modules/`.

## Архитектура шаблона (`local/templates/template/`)

Битрикс оборачивает содержимое каждой страницы между `header.php` (верх) и `footer.php` (низ) шаблона:

- `header.php` / `footer.php` — каркас шаблона Битрикса. Здесь регистрируются ассеты через `Bitrix\Main\Page\Asset` (CSS/JS добавляются тут, например `normalize`, `ozimnad-reset`, `scripts.js`) и задаётся вёрстка `.wrapper`. Сама разметка шапки/подвала вынесена в `page_blocks/header.php` и `page_blocks/footer.php`.
- **Переключение темы**: `header.php` проставляет `<body data-theme="...">`, анализируя текущий каталог — `light` внутри `/catalog/`, `dark` во всех остальных местах. Учитывай этот контракт `data-theme` при добавлении стилей.
- **Боковая колонка (aside)**: включается свойством раздела в `.section.php` — `show_aside = Y` (весь раздел) или `show_aside_detail = Y` (только детальные страницы SEF-разделов вроде `news/`). Содержимое — файл `sect_aside.php` в каталоге раздела; повторяющиеся промо-карточки — сниппеты в `include/aside/`. Механика: `inc/page_vars.php` (флаги) + `page_blocks/aside.php` (диспетчер + отложенный слот `ShowViewContent('aside')`). Подробности: `docs/layout-developer/aside.md`; эталон — `restoran/about/`.
- В шаблонах списков, где элементы выводятся карточками, на каждой карточке обязательно сохраняй редактируемую область Битрикса: `AddEditAction`, `AddDeleteAction` и `id="<?= $this->GetEditAreaId($arItem["ID"]); ?>"` на корневом элементе карточки.
- Весь PHP шаблона начинается со страхового условия Битрикса `if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();` — сохраняй его в новых файлах шаблона/инклудов.

### Структура SCSS

Точка входа `styles.scss` подтягивает всё через `@use`:
```
css/core/_variables, _fonts, _adjustment, _globals   → слой ядра
css/ui/    (_index собирает _main, _typography, …)    → UI-примитивы
css/blocks/(_index собирает партиалы по блокам)       → блоки страниц
css/helpers/(_media, _mixins) + favicon, fonts        → вспомогательное
```
Новые стили блока добавляй партиалом в `css/blocks/` и регистрируй его в `css/blocks/_index.scss`; та же схема для `css/ui/_index.scss`. Файлы `_#sample.scss` — это шаблоны-заготовки для копирования.

## Соглашения по страницам / маршрутизации

- Каждый раздел сайта — это каталог (`catalog/`, `news/`, `contacts/`, `restoran/`, `actions/`, …), содержащий `index.php` плюс `.section.php`, который объявляет заголовок раздела и `$arDirProperties` (meta description, keywords, robots).
- Страница — это просто PHP-файл, который `require`-ит `bitrix/header.php`, задаёт `$APPLICATION->SetTitle(...)`, выводит разметку, затем `require`-ит `bitrix/footer.php`. См. `index.php` + `indexContent.php`.
- `include/` хранит редактируемые области-инклуды Битрикса (логотипы, телефон, расписание, копирайт, ссылки на соцсети), переиспользуемые на разных страницах — маленькие PHP-сниппеты, часто редактируемые в админке Битрикса.
- `urlrewrite.php` задаёт правила ЧПУ (личный кабинет, магазин каталога, REST и т. д.); `.htaccess` направляет все запросы не-файлов через `bitrix/urlrewrite.php`.
