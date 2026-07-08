# AGENTS.md для webcomp-shop

## Единая Точка Входа

Этот файл обязателен для чтения любым агентом и субагентом перед началом работы в проекте.

Агент не должен начинать задачу с частной спецификации напрямую. Сначала читается `AGENTS.md`, затем индекс агентских правил и только потом нужные специализированные документы.

Порядок чтения:

1. `AGENTS.md`
2. `docs/layout-developer/README.md`
3. Специализированные правила по текущей задаче:
   - `docs/layout-developer/html-structure.md`
   - `docs/layout-developer/scss-structure.md`
   - `docs/layout-developer/figma-to-project.md`

## Правила Работы

- Работать строго шаг за шагом.
- Сначала предложить один конкретный шаг.
- Дождаться результата или явного подтверждения пользователя.
- Только после этого переходить к следующему шагу.
- Не создавать, не редактировать и не удалять файлы без явного согласия пользователя.
- Перед любым изменением файлов описать, что именно будет изменено, и дождаться подтверждения.
- Если задача требует Figma, HTML или SCSS, сначала открыть соответствующую спецификацию из `docs/layout-developer`.

## Карта Проекта

- `index.php` — основная точка входа страницы, подключает Bitrix header и footer.
- `indexContent.php` — содержимое главной страницы в текущем дампе.
- `local/templates/template` — основной шаблон сайта.
- `local/templates/template/page_blocks` — крупные части шаблона, включая header и footer.
- `local/templates/template/css/blocks` — SCSS-файлы BEM-блоков.
- `local/templates/template/css/blocks/_index.scss` — точка подключения SCSS-блоков через `@forward`.

## Маршрутизация Задач

- Для HTML-структуры читать `docs/layout-developer/html-structure.md`.
- Для SCSS читать `docs/layout-developer/scss-structure.md`.
- Для переноса Figma в проект читать `docs/layout-developer/figma-to-project.md`.
- Для новой агентской спецификации сначала обновить `docs/layout-developer/README.md`.

## Общие Запреты

Запрещается без отдельного согласования:

- менять базовый `wrapper`, header или footer;
- менять ядро Bitrix и сторонние файлы, не относящиеся к задаче;
- создавать альтернативные правила вне `docs/layout-developer`;
- создавать `PLANNING.md` или папку `docs/plans`;
- создавать отдельные MD-планы для страниц или внутренних блоков;
- игнорировать существующие HTML и SCSS правила проекта.
