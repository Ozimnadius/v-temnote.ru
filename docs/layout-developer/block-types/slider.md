# Тип Блока: Slider

## Назначение

Эта спецификация описывает дефолтную структуру слайдеров проекта.

Если Figma-блок выглядит как слайдер, карусель или имеет slides, pagination, точки, стрелки либо другое carousel-поведение, использовать Swiper.

Официальная документация:

- `https://swiperjs.com/get-started/`
- `https://swiperjs.com/swiper-api/`

## Базовая Структура

В примере `{block}` означает имя конкретного BEM-блока.

```html
<div class="{block}__slider">
  <div class="{block}__swiper swiper">
    <div class="{block}__wrapper swiper-wrapper">
      <div class="{block}__slide swiper-slide">
        ...
      </div>
    </div>
  </div>

  <div class="{block}__pagination"></div>

  <div class="{block}__nav">
    <button class="{block}__arrow {block}__arrow--prev" type="button"></button>
    <button class="{block}__arrow {block}__arrow--next" type="button"></button>
  </div>
</div>
```

## Правила Разметки

- Не писать `{block}__slider swiper`.
- Класс `swiper` ставить на `{block}__swiper`.
- Класс `swiper-wrapper` ставить на `{block}__wrapper`.
- Класс `swiper-slide` ставить на каждый `{block}__slide`.
- Pagination лежит внутри `{block}__slider`, но вне `{block}__swiper`.
- `swiper-pagination` не использовать, если проект управляет pagination через BEM-класс блока.
- Arrows всегда лежат внутри `{block}__nav`.
- Для стрелок использовать BEM-классы `{block}__arrow`, `{block}__arrow--prev`, `{block}__arrow--next`; `swiper-button-prev` и `swiper-button-next` не обязательны.
- Для Swiper JS использовать BEM-селекторы проекта.

## Количество И Контент Слайдов

- Если Figma показывает конкретное количество точек pagination, это количество считать количеством слайдов, если пользователь не уточнил другое.
- Если Figma раскрывает только один слайд, остальные слайды заполнять той же структурой и тем же доступным контентом.
- Не оставлять пустые placeholder-слайды, если доступен контент первого слайда.
- Если для разных слайдов есть отдельный контент в Figma, переносить его в соответствующие слайды.

## JS

Минимальная инициализация должна использовать BEM-селекторы:

```js
const slider = document.querySelector('.{block}__slider');
const swiper = slider.querySelector('.{block}__swiper');

new Swiper(swiper, {
  slidesPerView: 1,
  loop: true,
  pagination: {
    el: slider.querySelector('.{block}__pagination'),
    clickable: true,
  },
  navigation: {
    prevEl: slider.querySelector('.{block}__arrow--prev'),
    nextEl: slider.querySelector('.{block}__arrow--next'),
  },
});
```

Если Swiper не подключен, агент должен предложить точный способ подключения перед изменением файлов.

## Чеклист

- `{block}__slider` не содержит класс `swiper`.
- `{block}__swiper` содержит класс `swiper`.
- `{block}__wrapper` содержит класс `swiper-wrapper`.
- Каждый `{block}__slide` содержит класс `swiper-slide`.
- Pagination находится вне `{block}__swiper`.
- Pagination не использует `swiper-pagination`, если проект управляет ей через BEM.
- Arrows находятся внутри `{block}__nav`.
- Количество слайдов соответствует Figma pagination.
- Слайды не являются пустыми placeholders, если доступен контент.
