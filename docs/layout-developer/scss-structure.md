# SCSS-структура

## Назначение

Этот документ описывает правила создания SCSS-стилей для BEM-блоков проекта.

Цель документа — сохранять единое расположение файлов, предсказуемые подключения, одинаковую вложенность селекторов и единый способ работы с адаптивом и hover-состояниями.

## Один BEM-Блок — Один SCSS-Файл

Каждый самостоятельный BEM-блок должен иметь отдельный SCSS-файл.

Файлы блоков лежат в директории:

```text
local/templates/template/css/blocks
```

Имя SCSS-файла должно соответствовать имени BEM-блока.
Название блока и файла должно быть коротким и смысловым. Длинные составные имена использовать только если короткий вариант делает назначение блока неясным.

Примеры:

```text
.form          -> local/templates/template/css/blocks/_form.scss
.product-card  -> local/templates/template/css/blocks/_product-card.scss
.catalog-nav   -> local/templates/template/css/blocks/_catalog-nav.scss
.main-showcase -> local/templates/template/css/blocks/_main-showcase.scss
```

Не размещать стили новых BEM-блоков в общих файлах, если для этого нет отдельного согласования.

## Структура Файла Блока

Каждый SCSS-файл блока начинается с подключения helpers.

Первая строка всегда:

```scss
@use "../helpers/index" as *;
```

Сразу после подключения helpers идет комментарий с названием BEM-блока.

Пример:

```scss
@use "../helpers/index" as *;

/* product-card */

.product-card {
  ...
}
```

## Подключение Блоков

Каждый SCSS-файл блока должен быть подключен в:

```text
local/templates/template/css/blocks/_index.scss
```

Подключение выполняется через `@forward`.

Пример:

```scss
@forward "form";
@forward "product-card";
@forward "catalog-nav";
```

Для файла:

```text
local/templates/template/css/blocks/_form.scss
```

подключение должно быть:

```scss
@forward "form";
```

Не подключать файлы блоков напрямую в `styles.scss`, если блок относится к общей директории `css/blocks`.

## UI-Примитивы И Семантические Переменные

Перед добавлением или изменением SCSS сначала выполнить UI-preflight:

1. Открыть `local/templates/template/css/ui`.
2. Открыть `local/templates/template/css/core/_variables.scss`.
3. Проверить, есть ли готовый UI-класс, mixin или semantic token для типографики, цвета, фона, радиуса, transition, кнопки или ссылки.
4. Только после этого писать локальные правила блока.

При написании стилей сначала использовать существующие UI-примитивы из:

```text
local/templates/template/css/ui
```

Не дублировать в блочных SCSS-файлах кнопки, типографику и другие примитивы, если они уже есть в `css/ui`.

Типографику не задавать вручную через `font-family`, `font-size`, `font-weight`, `line-height`, `letter-spacing`, если нужный стиль уже есть в `css/ui/_typography.scss`. Использовать UI-классы в HTML (`text-24`, `text-18`, `link-14` и т. п.) или mixins в SCSS (`@include text-24`, `@include text-18`, `@include link-14` и т. п.) по существующему паттерну компонента.

При выборе цветов, фонов, текстов и шрифтов учитывать семантический слой переменных из:

```text
local/templates/template/css/core/_variables.scss
```

Предпочитать `--bg`, `--bg-surface`, `--text`, `--text-muted`, `--border`, `--accent`, `--accent-hover`, `--font-heading`, `--font-text` вместо размножения raw/Figma-токенов по блокам.

Цвета, фон, радиусы и transition не переносить из Figma raw-значениями, если есть semantic token или helper/mixin проекта. Raw/Figma-значения допустимы только для локальной геометрии конкретного блока (`grid`, `gap`, `padding`, `min-height`, `aspect-ratio`), когда подходящего токена в проекте нет.

Для UI-кнопок ориентироваться на Figma hover effects:

```text
https://www.figma.com/design/zQGpjwHb5y81dTZGOCN8b4/v-temnote.ruRef--Copy-?node-id=1186-11108&m=dev
```

## Вложенность Селекторов

Элементы BEM-блока пишутся внутри родительского селектора блока через `&__element`.

Правильно:

```scss
.product-card {
  &__image {
    ...
  }

  &__body {
    ...
  }

  &__title {
    ...
  }
}
```

Неправильно:

```scss
.product-card {
  ...
}

.product-card__image {
  ...
}

.product-card__body {
  ...
}
```

Модификаторы также пишутся внутри родителя.

Пример:

```scss
.product-card {
  &--featured {
    ...
  }

  &__button {
    ...

    &--disabled {
      ...
    }
  }
}
```

## Медиа-Запросы

Медиа-запросы пишутся внутри селектора, к которому они относятся.

Правильно:

```scss
.product-card {
  display: grid;
  grid-template-columns: 120px 1fr;

  @include sm-block {
    grid-template-columns: 1fr;
  }

  &__title {
    font-size: var(--Typography-Size-20);

    @include xs-block {
      font-size: var(--Typography-Size-16);
    }
  }
}
```

Неправильно:

```scss
.product-card {
  display: grid;
  grid-template-columns: 120px 1fr;
}

@media screen and (max-width: 999.98px) {
  .product-card {
    grid-template-columns: 1fr;
  }
}
```

Для адаптива использовать только миксины из:

```text
local/templates/template/css/helpers/_media.scss
```

Доступные миксины:

```scss
@include lg-block { ... }
@include md-block { ... }
@include sm-block { ... }
@include xs-block { ... }
```

Прямые `@media` в файлах блоков запрещены, кроме отдельно согласованных исключений.

## Hover-Состояния

Hover-состояния писать только через миксин `hover()` из:

```text
local/templates/template/css/helpers/_mixins.scss
```

Правильно:

```scss
.product-card {
  &__link {
    color: var(--Base-colors-Black-Text);

    @include hover {
      color: var(--Main-Color);
    }
  }
}
```

Неправильно:

```scss
.product-card__link:hover {
  color: var(--Main-Color);
}
```

Миксин `hover()` учитывает устройства без hover и добавляет `:active` для touch-сценариев.

## Общие Запреты

Запрещается:

- Создавать несколько SCSS-файлов для одного BEM-блока без необходимости.
- Размещать стили BEM-блоков в `styles.scss`, `template_styles.scss` или файлах core.
- Писать элементы блока отдельными корневыми селекторами вместо вложенности через `&__element`.
- Использовать прямые `@media` в файлах блоков.
- Использовать прямые `:hover` вместо `@include hover`.
- Подключать блоки напрямую в `styles.scss`, минуя `css/blocks/_index.scss`.
- Создавать SCSS-файл блока и забывать добавить `@forward` в `css/blocks/_index.scss`.
- Писать стили одного BEM-блока в файле другого BEM-блока.
- Использовать названия файлов, не совпадающие с именем BEM-блока.
- Использовать излишне длинные имена файлов и блоков, если есть короткий понятный вариант.

## Минимальные Требования К Новому SCSS-Блоку

Перед добавлением нового SCSS-блока агент должен проверить:

- HTML содержит самостоятельный BEM-блок.
- Для блока создан один файл в `local/templates/template/css/blocks`.
- Имя файла соответствует имени блока.
- Первая строка файла — `@use "../helpers/index" as *;`.
- После `@use` есть комментарий с названием блока.
- Перед написанием стилей проверены `css/ui` и `css/core/_variables.scss`.
- Типографика взята из UI-классов или mixins, а не задана raw CSS-свойствами.
- Цвета, фон, радиусы и transition взяты из semantic tokens/helpers, если они есть.
- Элементы и модификаторы написаны внутри родителя.
- Адаптив написан через миксины `lg-block`, `md-block`, `sm-block`, `xs-block`.
- Hover-состояния написаны через `@include hover`.
- Файл подключен в `local/templates/template/css/blocks/_index.scss` через `@forward`.
