# Дизайн-система «В темноте?!»

Источник: Figma [v-temnote.ruRef (Copy)](https://www.figma.com/design/zQGpjwHb5y81dTZGOCN8b4/v-temnote.ruRef--Copy-).
Реализация токенов — [`core/_variables.scss`](core/_variables.scss), типографика — [`ui/_typography.scss`](ui/_typography.scss).

Сайт работает в двух темах:
- **тёмная** — по умолчанию (большая часть сайта);
- **светлая** — раздел `/catalog/` (вешается `[data-theme="light"]` на `<body>` в `header.php`).

---

## 🎨 Цвета

### Raw-токены (имена 1:1 с Figma)

| Figma | CSS-переменная | HEX |
|---|---|---|
| Colors/White | `--Colors-White` | `#ffffff` |
| Colors/BG Black | `--Colors-BG-Black` | `#101010` |
| Colors/BG Black 2 | `--Colors-BG-Black-2` | `#1b1b1b` |
| Colors/Gray 1 | `--Colors-Gray-1` | `#525252` |
| Colors/Gray 2 | `--Colors-Gray-2` | `#424242` |
| Colors/Gray 3 | `--Colors-Gray-3` | `#bfbfbf` |
| Colors/Gray 4 | `--Colors-Gray-4` | `#eeeeee` |
| Colors/Blue Accident | `--Colors-Blue-Accident` | `#5ab9e7` |
| Colors/Blue Accident 2 | `--Colors-Blue-Accident-2` | `#259fda` |
| Green 1 | `--Green-1` | `#219653` |

### Семантические токены

Использовать в стилях именно их — они переключаются вместе с темой.

| Токен | Тёмная тема (по умолчанию) | Светлая тема (`/catalog/`) |
|---|---|---|
| `--bg` | `--Colors-BG-Black` (`#101010`) | `--Colors-White` (`#fff`) |
| `--bg-surface` | `--Colors-BG-Black-2` (`#1b1b1b`) | `--Colors-Gray-4` (`#eeeeee`) |
| `--text` | `--Colors-White` (`#fff`) | `--Colors-BG-Black` (`#101010`) |
| `--text-muted` | `--Colors-Gray-3` (`#bfbfbf`) | `--Colors-Gray-1` (`#525252`) |
| `--border` | `--Colors-Gray-2` (`#424242`) | `--Colors-Gray-3` (`#bfbfbf`) |
| `--accent` | `--Colors-Blue-Accident` | `--Colors-Blue-Accident-2` |
| `--accent-hover` | `--Colors-Blue-Accident-2` | `--Colors-Blue-Accident` |
| `--font-heading` | `--font-poiret` (Poiret One) | `--font-inter` (Inter) |
| `--font-text` | `--font-ubuntu` (Ubuntu) | `--font-inter` (Inter) |

---

## 🔤 Шрифты

Подключены локально в [`core/_fonts.scss`](core/_fonts.scss) (woff2 в `fonts/`, `font-display: swap`).

| Семейство | CSS-переменная | Применение | Начертания (font-weight) → файл |
|---|---|---|---|
| **Poiret One** | `--font-poiret` | Заголовки тёмной темы | `400` → `PoiretOne-Regular.woff2` |
| **Ubuntu** | `--font-ubuntu` | Текст, кнопки, меню тёмной темы | `300` → `Ubuntu-Light.woff2`<br>`400` → `Ubuntu-Regular.woff2`<br>`500` → `Ubuntu-Medium.woff2` |
| **Inter** | `--font-inter` | Светлая тема (`/catalog/`) | `400` → `Inter18pt-Regular.woff2`<br>`500` → `Inter18pt-Medium.woff2`<br>`600` → `Inter18pt-SemiBold.woff2` |

### Загрузка по темам

`@font-face` ленивый — браузер качает файл только когда тема реально применяет семейство (всё задаётся через `--font-heading`/`--font-text`, без хардкода).

| Страница | Грузится | НЕ грузится |
|---|---|---|
| Тёмные (весь сайт) | Poiret One, Ubuntu | Inter |
| `/catalog/` (светлая) | Inter | Poiret One, Ubuntu |

> ⚠️ Не добавлять `<link rel="preload">` на эти шрифты — preload форсирует загрузку мимо темы и ломает экономию.

---

## ✍️ Типографика

Тёмная тема. Каждый стиль — миксин в [`ui/_typography.scss`](ui/_typography.scss) + одноимённый класс.
`letter-spacing: 0.06em` соответствует «6%» из макета. Цвет наследуется от `body` (`--text`).

### Заголовки — Poiret One (`--font-heading`)

| Класс / тег | font-weight | size | line-height | letter-spacing | прочее |
|---|---|---|---|---|---|
| `h1` / `.h1` | 400 | 70px | 1 | 0.06em | |
| `.h1-display` | 400 | 54px | 1 | 0.06em | |
| `h2` / `.h2` | 400 | 50px | 1 | ~0 | |
| `.h2-upper` | 400 | 40px | 1.52 | 0.06em | `uppercase` |
| `h3` / `.h3` | 400 | 27px | 1 | 0 | |
| `h4` / `.h4` | 400 | 24px | 1 | ~0 | |
| `.text-accent` | 400 | 24px | 1.32 | 0.06em | Text 24 (Accident) |

### Текст — Ubuntu (`--font-text`)

| Класс | font-weight | size | line-height | letter-spacing | прочее |
|---|---|---|---|---|---|
| `.text-36` | 300 | 36px | 1.52 | 0.06em | `uppercase` |
| `.text-24` | 300 | 24px | 1.32 | 0.06em | |
| `.text-18` | 300 | 18px | 1.62 | 0.06em | |
| `.text-16-medium` | 500 | 16px | 24px | 0.06em | |
| `.text-16-regular` | 400 | 16px | 24px | 0.06em | |
| `.text-16` | 300 | 16px | 1.42 | 0.06em | |
| `.text-15` | 300 | 15px | 20px | 0.06em | |
| `.text-14` | 300 | 14px | 20px | 0.06em | |

### Кнопки / меню / ссылки — Ubuntu (`--font-text`)

| Класс | font-weight | size | line-height | letter-spacing | прочее |
|---|---|---|---|---|---|
| `.menu-18` | 400 | 18px | 24px | 0.06em | |
| `.btn-16` | 400 | 16px | 20px | 0.06em | |
| `.menu-15` | 400 | 15px | 20px | 0.06em | |
| `.link-15` | 400 | 15px | 20px | 0.06em | `underline` |
| `.link-14` | 300 | 14px | 20px | 0.06em | `underline` |

> Светлая тема (`/catalog/`) использует свой набор стилей на Inter (отдельный фрейм Figma) — будет добавлен отдельно.
