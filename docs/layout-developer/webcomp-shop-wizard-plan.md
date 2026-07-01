# План реализации визарда WebComp Shop

> **Для агентских исполнителей:** обязательный под-навык при реализации: `superpowers:subagent-driven-development` (рекомендуется) или `superpowers:executing-plans`. Выполнять план пошагово. Для отслеживания статуса используются чекбоксы (`- [ ]`).

**Цель:** создать новый мастер установки Bitrix `webcomp:shop` для модуля `webcomp.shop`.

**Архитектура:** использовать кастомный режим `wizard.php` + `wizard_sol`. Визард сам управляет видимой цепочкой шагов, а установочные действия передает в скрипты `site/services/*`, которые выполняются стандартным шагом `CDataInstallWizardStep`.

**Технологии:** Bitrix Framework, PHP, Bitrix wizard API, `wizard_sol`, модули `main`, `iblock`, `catalog`, `sale`.

---

## Обязательные Источники

Перед реализацией и при спорных местах использовать:

- официальную документацию Bitrix по визардам, ссылки на которую были переданы в задаче;
- официальные уроки Bitrix по типовым сайтам:
  - [Решения типовых сайтов](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=101&LESSON_ID=3225&LESSON_PATH=8781.4793.3225)
  - [Частые ошибки](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=101&LESSON_ID=3224&LESSON_PATH=8781.4793.3224)
- `docs/agents/bitrix-wizard.md` как локальную базу знаний по системе мастеров Bitrix;
- `bitrix/modules/aspro.max` как референс реализации похожего мастера установки.

Код из `aspro.max` не копировать механически. Его можно использовать, чтобы понять структуру шагов,
паттерны `wizard_sol`, обработку sale-настроек, services и импорт демо-данных.

## Область Работ

Первая версия устанавливает одно магазинное решение с одним шаблоном и одним инфоблоком демо-данных:

- имя визарда: `webcomp:shop`;
- ID шаблона: `template`;
- ID модуля: `webcomp.shop`;
- опция мегаменю: `webcomp.shop:megamenu_iblock_id`;
- исходный шаблон из бэкапа: `bitrix/modules/webcomp.shop/install/wizards/~webcomp/shop/site/templates/template`;
- XML мегаменю из бэкапа: `bitrix/modules/webcomp.shop/install/wizards/~webcomp/shop/site/services/iblock/xml/ru/menu/megamenu.xml`.

Папку `~webcomp` не использовать как рабочее namespace визарда. Это только бэкап.

## Целевые Шаги Визарда

1. `Выбор сайта`
2. `Выбор шаблона`
3. `Информация о сайте`
4. `Типы плательщиков`
5. `Оплата и доставка`
6. `Установка решения`
7. `Завершение настройки`

Будущие шаги, которые не входят в первую реализацию:

- `Выбор тематики`
- `Выбор конфигурации`

## Целевая Файловая Структура

Создать:

```text
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/.description.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/wizard.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/.description.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/wizard.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/.services.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/template/index.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/settings/index.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/locations.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/person_types.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/order_props.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/pay_systems.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/deliveries.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/locations/
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/iblock/index.php
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/iblock/xml/ru/menu/megamenu.xml
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/templates/template/
```

Использовать позже, когда появится файл превью:

```text
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/templates/template/templateThumb.webp
bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/templates/template/template.webp
```

Перед реализацией прочитать:

```text
bitrix/modules/webcomp.shop/install/index.php
bitrix/modules/webcomp.shop/default_option.php
bitrix/modules/webcomp.shop/lib/Models/Resources.php
bitrix/modules/webcomp.shop/lib/Admin/Config.php
bitrix/modules/webcomp.shop/lib/Models/CssGenerator.php
bitrix/templates/template
```

## Решение по `css/generated`

Генерируемые CSS-файлы не хранить в дистрибутиве визарда.

Целевой runtime-путь генерации:

```text
/bitrix/templates/template/css/generated/
```

Причины:

- существующий модуль уже строит путь генерации через `WebComp\Shop\Admin\Config`;
- `Config` берет `template_name` из опций `webcomp.shop` и пишет в `/bitrix/templates/{template_name}/css/generated`;
- `Resources::resetAll()` использует `CssGenerator` и создает `colors.css`, `brakepoints.css`, `styles.css` и `themes/theme-*.css`;
- `PrologCssVars` подключает CSS с сайта, а не из папки визарда;
- папка визарда `site/templates/template/css/generated/` была бы сборочным артефактом и не должна быть источником правды.

Правило для визарда:

- после копирования шаблона в `/bitrix/templates/template` сохранить `template_name = template` в опции модуля;
- затем вызвать существующую генерацию технических ресурсов через `WebComp\Shop\Models\Resources`;
- перед использованием `Resources` в визарде разделить технические ресурсы и данные конкретного сайта: генерация CSS и настройки темы относятся к техническим ресурсам, а логотипы, телефоны, email, адрес, название компании и meta-настройки должны сохраняться только через визард или страницу настроек;
- `Resources::resetAll()` не должен перезаписывать бизнес-данные сайта. Если текущая реализация вызывает `resetPhones()` или `resetLogos()`, это поведение нужно убрать из общего сброса или заменить отдельными явно названными методами, которые не используются при установке решения.

## Решение по Бизнес-Данным До Запуска Визарда

Принятое правило: модуль при установке подготавливает инфраструктуру, а визард персонализирует конкретный сайт.

До прохождения визарда бизнес-поля в `b_option` должны оставаться пустыми или технически нейтральными:

- телефоны;
- логотипы `logo_light` и `logo_dark`;
- название сайта или компании;
- подпись сайта;
- email;
- адрес;
- режим работы;
- meta description;
- meta keywords.

Форма визарда может показывать стартовые значения для удобства заполнения, но эти значения становятся настройками сайта только после явного прохождения визарда и выполнения сервиса `settings`.

Запрещено использовать `Resources::DEFAULT_PHONES` как источник реальных контактных данных сайта. Контактные данные относятся к настройкам сайта, а не к ресурсам модуля.

Запрещено использовать `Resources::DEFAULT_LOGOS` как источник уже установленных логотипов сайта до прохождения визарда. Логотипы относятся к данным конкретного сайта. Если нужны дефолтные файлы логотипов, их должен устанавливать визард или страница настроек после явного действия пользователя.

Страница настроек модуля должна учитывать это правило:

- если визард еще не был завершен, вместо обычных вкладок настроек показывать заглушку по аналогии с `aspro.max`;
- текст заглушки: `Данные решения WebComp Shop еще не установлены. Сначала выполните установку данных.`;
- кнопка заглушки: `Установить данные`;
- кнопка должна вести на стандартный запуск визарда `webcomp:shop` через `/bitrix/admin/wizard_install.php`;
- факт завершения визарда фиксировать отдельной опцией модуля, например `webcomp.shop:wizard_installed = Y`, только после успешного завершения всех ключевых установочных сервисов;
- дополнительно сохранить сайт установки, например `webcomp.shop:wizard_site_id = WIZARD_SITE_ID`, чтобы админка могла понимать, для какого сайта были установлены данные.

Минимальный набор ключевых данных для установки флага `wizard_installed = Y`:

- выбран и назначен шаблон сайта;
- сгенерированы технические CSS-ресурсы темы;
- сохранены настройки из шага `Информация о сайте`;
- установлены или явно оставлены пустыми логотипы `logo_light` и `logo_dark` согласно составу текущей версии визарда;
- настроены выбранные типы плательщиков, оплата и доставка;
- импортирован инфоблок мегаменю и сохранен `webcomp.shop:megamenu_iblock_id`;
- сервисы установки завершились без критической ошибки.

## Решение по Единому Источнику Опций

`default_option.php` не удалять и не игнорировать. Это штатный fallback-механизм Bitrix для `Bitrix\Main\Config\Option::get()`, если значение еще не сохранено в `b_option`.

В `default_option.php` нельзя завязываться на D7-классы модуля из `lib/`. Проверка показала: при минимальном bootstrap через `bitrix/modules/main/include.php` `Option::getDefaults('webcomp.shop')` уже может подключать `default_option.php`, но классы `WebComp\Shop\...` еще недоступны без `Loader::includeModule('webcomp.shop')`.

Не использовать `bitrix/modules/webcomp.shop/options.php` как общий конфиг опций. В Bitrix это привычное имя для страницы настроек модуля, поэтому такой файл будет путать назначение.

Принятое направление:

- создать простой PHP-конфиг без классов, например `bitrix/modules/webcomp.shop/config/module_options.php`;
- хранить в нем единый список описаний опций, где ключ массива является ключом `b_option`;
- для каждой опции указывать `default`, группу, переменную визарда (`wizardVar`) и обработчик (`handler`), если значение требует специальной подготовки;
- `default_option.php` должен подключать этот конфиг и собирать из него только массив дефолтов `$webcomp_shop_default_option`;
- будущий `WebComp\Shop\Models\OptionRegistry` должен быть D7-оберткой над тем же конфигом, чтобы визард, админка и модели не писали строковые ключи вручную.

Пример формы описания:

```php
return [
    'options' => [
        'site_name' => [
            'default' => '',
            'group' => 'site_info',
            'wizardVar' => 'siteName',
        ],
        'site_phones' => [
            'default' => '[]',
            'group' => 'site_info',
            'wizardVar' => 'sitePhone',
            'handler' => 'phones',
        ],
    ],
];
```

Так `site_name` или `site_phones` пишутся один раз как ключи опций, а остальные места получают эти ключи из общего описания.

## Описание Визарда

`.description.php` должен описывать визард как установочный мастер на базе `wizard_sol`.

Обязательное поведение:

- определить константы для идентификации модуля и визарда;
- установить `PARENT` в `wizard_sol`;
- установить `TEMPLATES` в `wizard_sol`;
- указать зависимости от `main`, `iblock`, `catalog`, `sale`;
- определить `STEPS` строго в целевом порядке;
- если известен `WIZARD_DEFAULT_SITE_ID`, оставить поведение выбора сайта согласованным с итоговой реализацией `SelectSiteStep`.

Ожидаемые классы шагов:

```php
'STEPS' => [
    'SelectSiteStep',
    'SelectTemplateStep',
    'SiteInfoStep',
    'PersonTypeStep',
    'PayDeliveryStep',
    'DataInstallStep',
    'FinishStep',
],
```

## Поведение Шагов

### Общее правило кнопок навигации

Во всех кастомных шагах визарда явно задавать подписи кнопок через `SetPrevCaption()` и `SetNextCaption()`.

Не полагаться на дефолты `CWizardStep`, потому что стандартные подписи ядра Bitrix содержат стрелки: `< Назад` и `Далее >`.

Для совпадения с поведением АСПРО использовать подписи без стрелок:

```php
$this->SetPrevCaption(Loc::getMessage('WEBCOMP_SHOP_WIZARD_BUTTON_PREVIOUS'));
$this->SetNextCaption(Loc::getMessage('WEBCOMP_SHOP_WIZARD_BUTTON_NEXT'));
```

Ключи должны быть объявлены в `lang/ru/wizard.php`:

```php
$MESS['WEBCOMP_SHOP_WIZARD_BUTTON_PREVIOUS'] = 'Назад';
$MESS['WEBCOMP_SHOP_WIZARD_BUTTON_NEXT'] = 'Далее';
```

### Общее правило HTML-разметки шагов

Если шаг выводит больше одного простого поля или содержит вложенную HTML-разметку, формировать `$this->content` через `ob_start()` и `ob_get_clean()`.

Не собирать сложный HTML длинной цепочкой строк вида `$this->content .= '...'`, потому что такой код быстро становится нечитаемым и плохо расширяется.

Пример:

```php
$radioField = $this->ShowRadioField('templateID', 'template', [
    'id' => 'webcomp-template-template',
]);

$templateName = htmlspecialcharsbx((string) Loc::getMessage('WEBCOMP_SHOP_TEMPLATE_NAME'));
$templateDescription = htmlspecialcharsbx((string) Loc::getMessage('WEBCOMP_SHOP_TEMPLATE_DESCRIPTION'));

ob_start();
?>
<label class="webcomp-wizard-template">
    <?= $radioField ?>
    <span>
        <strong><?= $templateName ?></strong><br>
        <span><?= $templateDescription ?></span>
    </span>
</label>
<?php
$this->content .= ob_get_clean();
```

Правила безопасности:

- тексты из lang-файлов и пользовательских переменных экранировать до вывода через `htmlspecialcharsbx`;
- HTML, который вернули методы визарда вроде `ShowRadioField()`, не экранировать повторно;
- использовать такой подход для читаемости разметки, а не для смешивания бизнес-логики с HTML.

### Правило UI-классов шагов визарда

Для кастомных шагов использовать стандартные CSS-классы `wizard_sol` и сверять похожие шаги с `bitrix/modules/aspro.max`.

Правила:

- выбор сайта оставлять на базовом `CSelectSiteWizardStep`, потому что он уже выводит стандартные `solution-item`, `solution-radio`, `solution-inner-item`;
- выбор шаблона оформлять через `inst-template-list-block`, `inst-template-description`, `inst-template-list-inp`, `inst-template-list-img`, `inst-template-list-label`;
- обычные поля настроек оформлять через `wizard-input-form`, `wizard-input-form-block`, `wizard-input-title`, `wizard-field`;
- группы чекбоксов и радиокнопок оформлять через `wizard-input-form-field wizard-input-form-field-checkbox`;
- каждую строку чекбокса/радио и описательный текст внутри таких групп оформлять через `wizard-catalog-form-item`;
- заголовки групп в шагах оплаты, доставки и похожих блоках оформлять через `wizard-catalog-title`;
- не использовать произвольные `h4`, `p`, `small`, `data-table` и inline-flex как основной UI, если для этого уже есть класс визарда;
- подсказки под обычными полями допускается выводить как inline `span` по паттерну АСПРО.

### SelectSiteStep

Назначение:

- дать пользователю выбрать целевой сайт;
- в первую очередь поддержать установку на существующий сайт;
- создание нового сайта поддерживать только если наследуемое поведение `wizard_sol` сохранено и проверено.

Навигация:

```php
$this->SetStepID('select_site');
$this->SetNextStep('select_template');
```

Не оставлять переход на `select_template`, если реального шага `SelectTemplateStep` нет.

### SelectTemplateStep

Назначение:

- показать один вариант шаблона по аналогии с АСПРО;
- сохранить структуру radio-карточки, чтобы позже можно было добавить другие шаблоны.
- использовать простой подход АСПРО: хранить выбор в переменной `templateID`;
- не вводить `wizTemplateID`, потому что он нужен стандартному eShop только как дополнительная UI-прослойка для нескольких шаблонов.

Навигация:

```php
$this->SetStepID('select_template');
$this->SetPrevStep('select_site');
$this->SetNextStep('site_info');
$wizard->SetDefaultVars(['templateID' => 'template']);
```

Фиксированное значение:

```text
templateID = template
```

Содержимое экрана:

- список шаблонов получать через `WizardServices::GetTemplatesPath($wizard->GetPath() . '/site')` и `WizardServices::GetTemplates($templatesPath)`;
- показывать только шаблон с ID `template`;
- radio выбран по умолчанию;
- изображение превью выводить по механике АСПРО: `templateThumb.webp` как маленькая картинка и `template.webp` как увеличенная картинка по клику;
- заголовок: `WebComp Shop`;
- описание: `Шаблон интернет-магазина WebComp. Цветовые параметры и расположение блоков настраиваются в параметрах шаблона/модуля.`

Валидация:

- шаг 19.1: на нажатие `Далее` проверить, что `templateID === 'template'`;
- шаг 19.2: дополнительно проверить, что шаблон `template` есть в результате `WizardServices::GetTemplates($templatesPath)`;
- если шаблон отсутствует, показать ошибку `Шаблон template не найден в дистрибутиве визарда`.

Превью шаблона:

- шаг 19.3: добавить файлы `site/templates/template/templateThumb.webp` и `site/templates/template/template.webp`;
- `WizardServices::GetTemplates()` автоматически подхватывает только стандартные `preview.gif` и `screen.gif`, поэтому для `.webp`-имен пути нужно формировать явно от `$wizard->GetPath()`;
- если есть оба файла, выводить изображение через `CFile::Show2Images($templateThumbPath, $templatePreviewPath, 150, 150, ' class="inst-template-list-img"')`;
- если есть только `template.webp`, использовать `CFile::ShowImage($templatePreviewPath, 150, 150, ' class="inst-template-list-img"', '', true)`;
- не использовать путь `images/ru/template.png` для карточки выбора шаблона.

Правило установки:

- скопировать исходный шаблон поверх `/bitrix/templates/template`;
- не удалять `/bitrix/templates/template`;
- существующие файлы с совпадающими путями перезаписывать;
- лишние старые файлы оставлять.
- исходный шаблон брать из `site/templates/template` внутри нового визарда.
- в сервисах установки использовать константы `WIZARD_TEMPLATE_ID` и `WIZARD_TEMPLATE_ABSOLUTE_PATH`, которые формируются из выбранного `templateID`.

### SiteInfoStep

Назначение:

- собрать информацию о сайте и SEO-метаданные.

Навигация:

```php
$this->SetStepID('site_info');
$this->SetPrevStep('select_template');
$this->SetNextStep('person_type');
```

Поля:

| Поле | Переменная | Тип | Обязательность | Валидация |
|---|---|---|---|---|
| Название сайта или компании | `siteName` | text | обязательно | не пустое после `trim()` |
| Подпись сайта | `siteSlogan` | textarea | необязательно | нет |
| Телефон для обратной связи | `sitePhone` | text | обязательно | не пустое после `trim()`, строгую маску не вводить |
| Ваш E-mail | `siteEmail` | text | обязательно | `check_email()` |
| Ваш адрес | `siteAddress` | textarea | необязательно | нет |
| Режим работы | `siteSchedule` | textarea | необязательно | нет |
| Описание сайта | `siteMetaDescription` | textarea | необязательно | нет |
| Ключевые слова | `siteMetaKeywords` | text | необязательно | нет |

Значения по умолчанию:

| Переменная | Источник |
|---|---|
| `siteName` | `CSite::GetByID($siteID)`: сначала `SITE_NAME`, затем `NAME`, затем `WebComp Shop` |
| `siteSlogan` | `Option::get('webcomp.shop', 'site_slogan', 'Интернет-магазин')` |
| `sitePhone` | первый `display` из `webcomp.shop:site_phones`, затем `+7 100 000-00-00` |
| `siteEmail` | `CSite::GetByID($siteID)['EMAIL']`, затем `Option::get('main', 'email_from')`, затем `info@{SERVER_NAME}` |
| `siteAddress` | `Option::get('webcomp.shop', 'site_address', '')` |
| `siteSchedule` | `Option::get('webcomp.shop', 'site_schedule', '')` |
| `siteMetaDescription` | корневой `.section.php` `$arDirProperties['description']`, затем `Интернет-магазин` |
| `siteMetaKeywords` | корневой `.section.php` `$arDirProperties['keywords']`, затем `интернет-магазин, заказать, купить` |

Сообщения об ошибках:

```text
Необходимо указать название сайта или компании
Необходимо указать телефон для обратной связи
Необходимо указать корректный E-mail
```

Поле логотипа в первую версию не входит.

Значения сохранять в переменные визарда, а записывать в систему во время сервиса `settings`.

Имена переменных:

```text
siteName
siteSlogan
sitePhone
siteEmail
siteAddress
siteSchedule
siteMetaDescription
siteMetaKeywords
```

Правила:

- `siteSlogan`, `siteAddress`, `siteSchedule` сохранять в `webcomp.shop` как подготовленные настройки, даже если текущий шаблон пока не читает их напрямую;
- `sitePhone` сохранять через сервис `settings` в `webcomp.shop:site_phones` JSON в формате `display`, `href`, `label`;
- конфликт чтения телефонов из `webcomp.core` не решать в этом шаге, он вынесен в `docs/TODO.MD`;
- при чтении meta из `.section.php` сохранять существующий `robots` и другие свойства, менять только `description` и `keywords` в сервисе `settings`.

### PersonTypeStep

Назначение:

- дать пользователю выбрать типы плательщиков для магазина.

Навигация:

```php
$this->SetStepID('person_type');
$this->SetPrevStep('site_info');
$this->SetNextStep('pay_delivery');
```

Значения по умолчанию:

```php
$wizard->SetDefaultVars([
    'personType' => [
        'fiz' => 'Y',
        'ur' => 'Y',
    ],
]);
```

Чекбоксы:

```text
personType[fiz] = Y: Физическое лицо, checked by default
personType[ur] = Y: Юридическое лицо, checked by default
```

Валидация:

```text
Должен быть выбран хотя бы один тип плательщика.
Проверять только при нажатии кнопки Далее.
Условие ошибки: empty($personType['fiz']) && empty($personType['ur']).
```

Сообщение об ошибке:

```text
Необходимо выбрать хотя бы один тип плательщика
```

Не добавлять:

```text
personType[fiz_ua]
```

### PayDeliveryStep

Назначение:

- настроить способы оплаты, доставки и вариант импорта местоположений для первой версии.

Навигация:

```php
$this->SetStepID('pay_delivery');
$this->SetPrevStep('person_type');
$this->SetNextStep('data_install');
```

Значения по умолчанию:

```php
$wizard->SetDefaultVars([
    'paysystem' => [
        'cash' => 'Y',
    ],
    'delivery' => [
        'courier' => 'Y',
        'self' => 'Y',
        'rus_post_first' => 'N',
    ],
    'locations_csv' => 'loc_ussr.csv',
]);
```

Чекбоксы оплаты:

```text
paysystem[cash] = Y: Наличные (при доставке курьером и самовывозе), checked by default
```

Чекбоксы доставки:

```text
delivery[courier] = Y: Курьер, checked by default
delivery[self] = Y: Самовывоз, checked by default
delivery[rus_post_first] = Y: Отправления 1 класса, unchecked by default
```

Radio-значения местоположений:

```text
locations_csv = loc_ussr.csv: Россия и СНГ (страны и города), checked by default
locations_csv = loc_kz.csv: Казахстан
locations_csv = loc_usa.csv: США (города)
locations_csv = loc_cntr.csv: Мир (страны)
locations_csv = "": Не загружать
```

Валидация:

```text
Должен быть выбран хотя бы один способ оплаты.
Должен быть выбран хотя бы один способ доставки.
Значение locations_csv должно быть одним из: loc_ussr.csv, loc_kz.csv, loc_usa.csv, loc_cntr.csv, "".
Пустое значение locations_csv валидно, потому что это вариант Не загружать.
Проверять только при нажатии кнопки Далее.
```

Сообщения об ошибках:

```text
Необходимо выбрать хотя бы один способ оплаты
Необходимо выбрать хотя бы один способ доставки
Необходимо выбрать корректный вариант местоположений
```

Отличие от АСПРО:

- АСПРО валидирует только оплату, но в нашем визарде доставка тоже обязательна;
- не добавлять платежные системы `sber`, `bill`, `collect`, `paypal`, `oshad`;
- не добавлять доставки `ruspost`, `rus_post`, `ua_post`, `kaz_post`, `dhl`, `ups`;
- не добавлять radio-пункт `Украина`.

### DataInstallStep

Назначение:

- запустить сервисы из `site/services/.services.php` через стандартный AJAX-цикл `CDataInstallWizardStep`.
- использовать стандартный `InstallService()` из `CDataInstallWizardStep`;
- не переопределять `InstallService()` без отдельной причины, потому что стандартная реализация уже задает `WIZARD_TEMPLATE_ABSOLUTE_PATH`, `WIZARD_SERVICE_ABSOLUTE_PATH` и подключает stage-файлы.

Инициализация:

```php
$this->SetStepID('data_install');
$this->SetTitle(GetMessage('WIZ_DATA_INSTALL_TITLE'));
$this->SetSubTitle(GetMessage('WIZ_DATA_INSTALL_SUBTITLE'));
```

Сервисы должны выполняться в стабильном порядке:

```text
template
settings
sale
iblock
```

### FinishStep

Назначение:

- показать статус завершения;
- дать ссылку на установленный сайт и полезные административные страницы, если они доступны.

## Services

### `.services.php`

Использовать `$arServices`, а не `$arWizardServices`, потому что этот визард работает в режиме `wizard.php` + `CDataInstallWizardStep`.

Ожидаемая структура:

```php
$arServices = [
    'template' => [
        'MODULE_ID' => 'webcomp.shop',
        'NAME' => GetMessage('SERVICE_TEMPLATE_NAME'),
        'STAGES' => ['index.php'],
    ],
    'settings' => [
        'MODULE_ID' => 'webcomp.shop',
        'NAME' => GetMessage('SERVICE_SETTINGS_NAME'),
        'STAGES' => ['index.php'],
    ],
    'sale' => [
        'MODULE_ID' => 'webcomp.shop',
        'NAME' => GetMessage('SERVICE_SALE_NAME'),
        'STAGES' => [
            'locations.php',
            'person_types.php',
            'order_props.php',
            'pay_systems.php',
            'deliveries.php',
        ],
    ],
    'iblock' => [
        'MODULE_ID' => 'iblock',
        'NAME' => GetMessage('SERVICE_IBLOCK_NAME'),
        'STAGES' => ['index.php'],
    ],
];
```

Почему `MODULE_ID` обязателен:

- `WizardServices::GetServices()` по умолчанию проверяет установленный модуль с ID, равным ключу сервиса;
- без `MODULE_ID` сервисы `template` и `settings` будут проверяться как модули `template` и `settings`;
- таких модулей нет, поэтому Bitrix отфильтрует эти сервисы и они не выполнятся;
- для `sale` намеренно использовать `MODULE_ID => 'webcomp.shop'`, а не `MODULE_ID => 'sale'`, чтобы Bitrix не отфильтровал сервис до запуска stage-файлов;
- доступность модуля `sale` проверять в первом sale-stage через `Loader::includeModule('sale')` и останавливать установку с понятной ошибкой, если модуль недоступен.

Языковой файл:

- создать `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/site/services/.services.php`;
- определить сообщения `SERVICE_TEMPLATE_NAME`, `SERVICE_SETTINGS_NAME`, `SERVICE_SALE_NAME`, `SERVICE_IBLOCK_NAME`.

### Сервис `template`

Ответственность:

- скопировать шаблон сайта `template` из `WIZARD_TEMPLATE_ABSOLUTE_PATH` в `/bitrix/templates/template`;
- перезаписать совпадающие файлы;
- не удалять папку назначения;
- назначить шаблон `template` выбранному сайту;
- сохранить `main:wizard_template_id = template` для выбранного сайта;
- сохранить `webcomp.shop:template_name = template`;
- сгенерировать CSS в `/bitrix/templates/template/css/generated/` через существующий `Resources::resetAll()`.

Заметки по реализации:

- проверить наличие `B_PROLOG_INCLUDED`, `WIZARD_SITE_ID`, `WIZARD_TEMPLATE_ID` и `WIZARD_TEMPLATE_ABSOLUTE_PATH`;
- не пересчитывать путь к шаблону вручную, если доступен `WIZARD_TEMPLATE_ABSOLUTE_PATH`;
- копировать через `CopyDirFiles(WIZARD_TEMPLATE_ABSOLUTE_PATH, $targetDir, true, true, false)`;
- назначать шаблон сайту по паттерну АСПРО/eShop: прочитать `CSite::GetTemplateList(WIZARD_SITE_ID)`, заменить первую запись с пустым `CONDITION`, если она есть, иначе добавить `['CONDITION' => '', 'SORT' => 150, 'TEMPLATE' => WIZARD_TEMPLATE_ID]`;
- сохранить `webcomp.shop:template_name` до вызова `Resources::resetAll()`, чтобы `Config` генерировал CSS в правильный шаблон;
- вызвать генерацию технических ресурсов после копирования шаблона и сохранения `template_name`;
- не полагаться на порядок `template` -> `settings` для защиты телефонов: техническая генерация ресурсов не должна перезаписывать `webcomp.shop:site_phones`;
- перед кодом изучить `bitrix/modules/webcomp.shop/install/index.php`, чтобы использовать существующие паттерны копирования;
- перед кодом изучить `bitrix/modules/webcomp.shop/lib/Models/Resources.php` и `bitrix/modules/webcomp.shop/lib/Admin/Config.php`, чтобы не дублировать генератор CSS;
- не удалять пользовательские файлы, добавленные в `/bitrix/templates/template`;
- не создавать и не хранить `site/templates/template/css/generated/` в источнике визарда;
- явно сообщать об ошибке, если исходный шаблон не найден.

### Сервис `settings`

Ответственность:

- сохранить значения из `SiteInfoStep`;
- обновить поля сайта Bitrix там, где это уместно;
- сохранить опции модуля, если их ожидает шаблон;
- сохранить meta description и keywords.

Перед кодом изучить:

```text
bitrix/modules/webcomp.shop/default_option.php
bitrix/modules/webcomp.shop/lib/Admin/Controllers/Tabs/GeneralController.php
bitrix/templates/template
.section.php
```

Карта сохранения:

| Поле визарда | Переменная визарда | Куда сохранять |
|---|---|---|
| Название сайта или компании | `siteName` | `CSite::Update`: `SITE_NAME`, `NAME`; `Option::set('main', 'site_name', ..., WIZARD_SITE_ID)`; `Option::set('webcomp.shop', 'site_name', ...)` |
| Подпись сайта | `siteSlogan` | `Option::set('webcomp.shop', 'site_slogan', ...)` |
| Телефон для обратной связи | `sitePhone` | `Option::set('webcomp.shop', 'site_phones', $phonesJson)` |
| Ваш E-mail | `siteEmail` | `CSite::Update`: `EMAIL`; `Option::set('main', 'email_from', ...)`; `Option::set('sale', 'order_email', ...)`; `Option::set('webcomp.shop', 'site_email', ...)` |
| Ваш адрес | `siteAddress` | `Option::set('webcomp.shop', 'site_address', ...)` |
| Режим работы | `siteSchedule` | `Option::set('webcomp.shop', 'site_schedule', ...)` |
| Описание сайта | `siteMetaDescription` | корневой `.section.php`: `$arDirProperties['description']`; `Option::set('webcomp.shop', 'site_meta_description', ...)` |
| Ключевые слова | `siteMetaKeywords` | корневой `.section.php`: `$arDirProperties['keywords']`; `Option::set('webcomp.shop', 'site_meta_keywords', ...)` |

Новые значения по умолчанию в `bitrix/modules/webcomp.shop/default_option.php`:

```php
'wizard_installed'      => 'N',
'wizard_site_id'        => '',
'site_name'             => '',
'site_slogan'           => '',
'site_phones'           => '[]',
'site_email'            => '',
'site_address'          => '',
'site_schedule'         => '',
'site_meta_description' => '',
'site_meta_keywords'    => '',
```

Телефон сохранять в формате, совместимом с текущей админкой модуля:

```php
[
    [
        'display' => $sitePhone,
        'href'    => 'tel:' . $normalizedPhone,
        'label'   => '',
    ],
]
```

Нормализация телефона для `href`:

- взять `trim($sitePhone)`;
- удалить все символы кроме цифр и `+`;
- если `+` не первый символ, удалить его;
- если `+` встречается несколько раз, оставить только первый;
- итоговое значение использовать только для `href`, поле `display` оставлять таким, как ввел пользователь.

`sale:order_email`:

- сохранять только если установлен или подключается модуль `sale`;
- если `sale` недоступен, не останавливать сервис `settings`, потому что сам `sale` обрабатывается отдельным сервисом.

Обновление корневого `.section.php`:

- файл находится по `WIZARD_SITE_PATH . '.section.php'`;
- если файл существует, подключить его в изолированной функции и получить текущие `$sSectionName` и `$arDirProperties`;
- если файла нет, использовать `$sSectionName = ''` и пустой `$arDirProperties`;
- заменить только `$arDirProperties['description']` и `$arDirProperties['keywords']`;
- остальные ключи, например `robots`, сохранить без изменений;
- записать файл заново через структурную генерацию PHP-массива, например `var_export($arDirProperties, true)`;
- не использовать простую замену строк по макросам `SITE_DESCRIPTION` и `SITE_KEYWORDS`, потому что в текущем проекте `.section.php` уже живой файл без этих макросов.

Правила:

- не вызывать `Resources::resetAll()` в сервисе `settings`, потому что сервис настроек отвечает только за бизнес-данные сайта;
- обновлять корневой `.section.php` аккуратно, сохраняя существующие свойства, например `robots`;
- новые ключи `wizard_installed`, `wizard_site_id`, `site_name`, `site_slogan`, `site_phones`, `site_email`, `site_address`, `site_schedule`, `site_meta_description`, `site_meta_keywords` добавить в `default_option.php` при реализации;
- не сохранять дублирующие значения в `webcomp.core` в рамках этой задачи;
- текущие компоненты `webcomp:phones`, `webcomp:logo`, `webcomp:header` пока читают часть настроек из `webcomp.core`; это не решать в рамках сервиса `settings`, потому что конфликт вынесен в `docs/TODO.MD`.

### Сервис `sale`

Ответственность:

- требовать модуль `sale`;
- выполнять настройку не одним `index.php`, а отдельными stages, как в стандартном `bitrix:eshop` и `aspro:max`;
- импортировать выбранные местоположения, если не выбран вариант `Не загружать`;
- создать или обновить типы плательщиков, выбранные на `PersonTypeStep`;
- создать или обновить базовые группы и свойства заказа для выбранных типов плательщиков;
- создать или обновить выбранные платежные системы;
- создать или обновить выбранные службы доставки.

Stages:

```text
sale/locations.php
sale/person_types.php
sale/order_props.php
sale/pay_systems.php
sale/deliveries.php
```

Переменные визарда:

| Назначение | Переменная |
|---|---|
| Физическое лицо | `personType[fiz]` |
| Юридическое лицо | `personType[ur]` |
| Наличные | `paysystem[cash]` |
| Курьер | `delivery[courier]` |
| Самовывоз | `delivery[self]` |
| Отправления 1 класса | `delivery[rus_post_first]` |
| Файл местоположений | `locations_csv` |

`locations.php`:

- использовать подход стандартного Bitrix eShop: `\Bitrix\Sale\Location\Import\ImportProcess::importFile()` для нового механизма местоположений и старую CSV-ветку только если проектная версия Bitrix ее требует;
- поддерживать только значения `loc_ussr.csv`, `loc_kz.csv`, `loc_usa.csv`, `loc_cntr.csv` и пустое значение для варианта `Не загружать`;
- если `locations_csv` пустой, ничего не импортировать;
- если `locations_csv` не входит в whitelist, не подставлять значение в путь к файлу и остановить установку с понятной ошибкой;
- если импорт местоположений не завершен за один проход, выставлять `$this->repeatCurrentService = true`;
- хранить нужные файлы местоположений внутри `site/services/sale/locations/`, чтобы визард не зависел от установленного `bitrix:eshop` или `aspro:max`;
- папку `site/services/sale/locations/` копировать из стандартного Bitrix eShop: `bitrix/wizards/bitrix/eshop/site/services/sale/locations/` или из такого же источника в модуле `bitrix.eshop`;
- копировать только поддерживаемые наборы и служебные файлы, которые нужны стандартному импорту местоположений;
- не добавлять дополнительные файлы местоположений из АСПРО и не добавлять Украину в первой версии.

`person_types.php`:

- создать или обновить `CSalePersonType` для `Физическое лицо` с `SORT = 100`;
- создать или обновить `CSalePersonType` для `Юридическое лицо` с `SORT = 150`;
- искать существующие типы по `LID = WIZARD_SITE_ID` и имени;
- выбранные типы делать активными;
- не создавать и не учитывать `fiz_ua`;
- невыбранные типы в MVP не деактивировать, если нет явного маркера, что они были созданы или управляются нашим визардом.

`order_props.php`:

- создать группы свойств заказа для каждого выбранного типа плательщика;
- для физического лица создать минимум `FIO`, `EMAIL`, `PHONE`, `LOCATION`, `ADDRESS`;
- для юридического лица создать минимум `COMPANY`, `INN`, `CONTACT_PERSON`, `EMAIL`, `PHONE`, `LOCATION`, `ADDRESS`;
- выставить системные флаги свойств там, где они нужны checkout-компоненту: `IS_PAYER`, `IS_EMAIL`, `IS_PHONE`, `IS_LOCATION`, `IS_ADDRESS`, `IS_PROFILE_NAME`;
- группы искать или создавать по `PERSON_TYPE_ID + NAME`;
- свойства искать или создавать по `PERSON_TYPE_ID + CODE`, чтобы повторный запуск визарда не создавал дубликаты;
- для `FIO` использовать `TYPE = TEXT`, `REQUIED = Y`, `USER_PROPS = Y`, `IS_PROFILE_NAME = Y`, `IS_PAYER = Y`;
- для `EMAIL` использовать `TYPE = TEXT`, `REQUIED = Y`, `IS_EMAIL = Y`;
- для `PHONE` использовать `TYPE = TEXT`, `REQUIED = Y`, `IS_PHONE = Y`;
- для `LOCATION` использовать `TYPE = LOCATION`, `REQUIED = Y`, `IS_LOCATION = Y`;
- для `ADDRESS` использовать `TYPE = TEXTAREA`, `REQUIED = Y`, `IS_ADDRESS = Y`;
- для `COMPANY` использовать `TYPE = TEXT`, `REQUIED = Y`, `IS_PROFILE_NAME = Y`;
- для `INN` использовать `TYPE = TEXT`, `REQUIED = N`;
- для `CONTACT_PERSON` использовать `TYPE = TEXT`, `REQUIED = Y`, `IS_PAYER = Y`;
- business value mappings не переносить в первой версии, пока не появится платежная система, которой они реально нужны.

`pay_systems.php`:

- создать или обновить платежную систему `Наличные`;
- использовать `\Bitrix\Sale\Internals\PaySystemActionTable` и ограничения `\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType`;
- задать стабильный идентификатор `XML_ID = webcomp_cash`;
- искать существующую платежную систему сначала по `XML_ID = webcomp_cash`, затем fallback по `ACTION_FILE = cash` и имени;
- использовать `ACTION_FILE = cash`, `IS_CASH = Y`, `HAVE_PAYMENT = Y`, `HAVE_ACTION = N`, `ENTITY_REGISTRY_TYPE = \Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER`;
- привязать оплату к выбранным типам плательщиков;
- повторный запуск должен обновлять существующую платежную систему, а не создавать дубль.

`deliveries.php`:

- создать или обновить доставку `Курьер` через `\Bitrix\Sale\Delivery\Services\Configurable`;
- создать или обновить доставку `Самовывоз` через `\Bitrix\Sale\Delivery\Services\Configurable`;
- для `Курьер` использовать стабильный `CODE = webcomp_courier`;
- для `Самовывоз` использовать стабильный `CODE = webcomp_self`;
- существующие доставки искать по `CODE` и обновлять, а не создавать дубль;
- если выбран `delivery[rus_post_first]`, не создавать legacy-доставку вручную; только активировать уже зарегистрированный сервис/обработчик с `CODE` или `SID = rus_post_first`, если он есть в текущей установке;
- если `rus_post_first` выбран, но обработчик не найден, не падать на MVP-этапе: оставить его неактивным и зафиксировать это как ограничение первой версии;
- для доставок добавлять ограничения по сайту `WIZARD_SITE_ID`;
- для `Самовывоз` привязать активный склад, если модуль `catalog` и склад уже доступны;
- валюту для конфигурируемых доставок брать из `\Bitrix\Sale\Internals\SiteCurrencyTable` для `WIZARD_SITE_ID`, а если ее нет, использовать `RUB` и создать/обновить запись для сайта.

Правила:

- `sale` обязателен;
- не пропускать настройку sale молча, если модуль отсутствует;
- каждый stage должен заново находить нужные ID по базе или через сохраненные wizard vars; не полагаться на локальные переменные предыдущего stage;
- не копировать большой eShop `step1.php` целиком: не создавать демо-заказы, статусы, группы пользователей и лишние платежные системы в MVP;
- если не выбран ни один тип плательщика, этот сервис не должен запускаться, потому что валидация должна остановить визард раньше;
- если не выбран ни один способ оплаты или доставки, сервис не должен запускаться, потому что валидация должна остановить визард раньше;
- `sale`-service не должен зависеть от `aspro.max`; код АСПРО использовать только как референс поведения.

### Сервис `iblock`

Ответственность:

- требовать модуль `iblock`;
- удалить старый инфоблок мегаменю перед импортом;
- импортировать `site/services/iblock/xml/ru/menu/megamenu.xml`;
- сохранить ID нового инфоблока в `webcomp.shop:megamenu_iblock_id`.
- не копировать старую логику `~webcomp/shop/site/services/iblock/megamenu.php` без изменений: там существующий инфоблок переиспользуется, а в новой реализации его нужно удалять перед импортом.

Правило удаления:

```text
1. Прочитать Option::get('webcomp.shop', 'megamenu_iblock_id').
2. Если в опции есть ID существующего инфоблока, удалить этот инфоблок.
3. Если опция пустая, дополнительно проверить наличие инфоблока мегаменю по стабильным идентификаторам перед импортом.
4. Импортировать XML.
5. Определить ID нового инфоблока.
6. Сохранить его через Option::set('webcomp.shop', 'megamenu_iblock_id', $iblockId).
```

Известные факты по XML:

```text
Файл: bitrix/modules/webcomp.shop/install/wizards/~webcomp/shop/site/services/iblock/xml/ru/menu/megamenu.xml
Название: Мега Меню
ID классификатора: 4
Разделов: 19
Элементов: 0
Верхние разделы: Верхнее меню, Каталог, Разделы
Поля разделов: UF_LINK:url, UF_ICON:file
```

Перед реализацией импорта скопировать этот XML в новый путь визарда без `~`.

## Список Задач

### Задача 1: Изучить Текущую Структуру Модуля

**Файлы:**

- Прочитать: `bitrix/modules/webcomp.shop/install/index.php`
- Прочитать: `bitrix/modules/webcomp.shop/default_option.php`
- Прочитать: `bitrix/templates/template`
- Прочитать: `bitrix/modules/webcomp.shop/install/wizards/~webcomp/shop/site/services/iblock/xml/ru/menu/megamenu.xml`

- [ ] Проверить, как текущий `InstallFiles()` копирует файлы визарда.
- [ ] Подтвердить, что `webcomp.shop` является ID модуля.
- [ ] Подтвердить, что `megamenu_iblock_id` есть в `default_option.php`.
- [ ] Подтвердить, откуда должна браться исходная папка шаблона `template`.
- [ ] Проверить, существует ли развернутая копия `/bitrix/wizards/webcomp/shop` и нужно ли обновлять ее при локальном тестировании.

### Задача 2: Создать Скелет Визарда

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/.description.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/wizard.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/.description.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/wizard.php`

- [ ] Добавить `.description.php` с `PARENT`, `TEMPLATES`, зависимостями и `STEPS`.
- [ ] Добавить языковые сообщения для названия визарда, описания и заголовков всех шагов.
- [ ] Добавить `wizard.php` с семью целевыми классами шагов.
- [ ] Убедиться, что каждый шаг явно задает `SetStepID()`, `SetNextStep()` и `SetPrevStep()`.

### Задача 3: Реализовать Формы Шагов

**Файлы:**

- Изменить: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/wizard.php`
- Изменить: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/wizard.php`

- [ ] Добавить общие lang-ключи кнопок `WEBCOMP_SHOP_WIZARD_BUTTON_PREVIOUS` и `WEBCOMP_SHOP_WIZARD_BUTTON_NEXT`.
- [ ] Во всех шагах с `SetPrevStep()` явно вызывать `SetPrevCaption(...)`.
- [ ] Во всех шагах с `SetNextStep()` явно вызывать `SetNextCaption(...)`.
- [ ] Для вложенной HTML-разметки шагов использовать `ob_start()` / `ob_get_clean()`, а не длинные цепочки `$this->content .= '...'`.
- [ ] Реализовать `SelectTemplateStep` как одну выбранную radio-карточку для `template`.
- [ ] В `SelectTemplateStep` использовать переменную `templateID`, не `wizTemplateID`.
- [ ] Шаг 19.1: добавить валидацию, что `templateID === 'template'`, и проверить ее временной подменой значения radio.
- [ ] Шаг 19.2: добавить валидацию, что шаблон найден через `WizardServices::GetTemplates()`, и проверить ее временным удалением/переименованием папки `site/templates/template`.
- [ ] Шаг 19.3: добавить `templateThumb.webp` и `template.webp` для карточки шаблона и вывести их через `CFile::Show2Images()` с увеличением по клику.
- [ ] Реализовать поля и значения по умолчанию для `SiteInfoStep`.
- [ ] Реализовать чекбоксы и валидацию для `PersonTypeStep`.
- [ ] Реализовать элементы управления оплатой, доставкой и местоположениями для `PayDeliveryStep`.
- [ ] Добавить точные сообщения валидации из этого плана.

### Задача 4: Добавить Реестр Services

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/.services.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/lang/ru/site/services/.services.php`

- [ ] Определить `$arServices`.
- [ ] Зарегистрировать сервисы в порядке: `template`, `settings`, `sale`, `iblock`.
- [ ] Для `template` и `settings` явно указать `MODULE_ID => 'webcomp.shop'`.
- [ ] Для `sale` явно указать `MODULE_ID => 'webcomp.shop'`, чтобы сервис не был отфильтрован до проверки модуля `sale` внутри stage-файлов.
- [ ] Для `iblock` явно указать `MODULE_ID => 'iblock'`.
- [ ] Для `template`, `settings` и `iblock` использовать `STAGES => ['index.php']`.
- [ ] Для `sale` использовать stages: `locations.php`, `person_types.php`, `order_props.php`, `pay_systems.php`, `deliveries.php`.
- [ ] Добавить языковые сообщения `SERVICE_TEMPLATE_NAME`, `SERVICE_SETTINGS_NAME`, `SERVICE_SALE_NAME`, `SERVICE_IBLOCK_NAME`.

### Задача 5: Реализовать Сервис Шаблона

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/template/index.php`

- [ ] Проверить `WIZARD_TEMPLATE_ID === 'template'`.
- [ ] Использовать `WIZARD_TEMPLATE_ABSOLUTE_PATH` как исходную папку шаблона.
- [ ] Скопировать файлы в `/bitrix/templates/template` с включенной перезаписью.
- [ ] Не удалять `/bitrix/templates/template`.
- [ ] Назначить шаблон `template` сайту `WIZARD_SITE_ID`.
- [ ] Сохранить `main:wizard_template_id = template` для выбранного сайта.
- [ ] Сохранить `webcomp.shop:template_name = template`.
- [ ] Вызвать существующую генерацию ресурсов так, чтобы CSS появился в `/bitrix/templates/template/css/generated/`.
- [ ] Перед вызовом генерации убедиться, что она не перезаписывает бизнес-данные сайта, включая `webcomp.shop:site_phones`, `webcomp.shop:logo_light` и `webcomp.shop:logo_dark`.
- [ ] Не создавать `css/generated` в `site/templates/template` внутри визарда.
- [ ] Выдать понятную ошибку визарда, если исходный шаблон отсутствует.

### Задача 6: Реализовать Сервис Настроек

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/settings/index.php`
- Изменить: `bitrix/modules/webcomp.shop/default_option.php`

- [ ] Прочитать переменные визарда из `SiteInfoStep`.
- [ ] Обновить сайт `WIZARD_SITE_ID` через `CSite::Update`.
- [ ] Сохранить `siteName`, `siteSlogan`, `siteEmail`, `siteAddress`, `siteSchedule`, `siteMetaDescription`, `siteMetaKeywords` в опции `webcomp.shop`.
- [ ] Сохранить `sitePhone` в `webcomp.shop:site_phones` JSON в формате `display`, `href`, `label`.
- [ ] Нормализовать телефон для `href`, оставив пользовательское значение в `display`.
- [ ] Сохранить `main:site_name` и `main:email_from`.
- [ ] Сохранить `sale:order_email` только если модуль `sale` установлен или подключается.
- [ ] Обновить корневой `.section.php`, сохранив существующие свойства и заменив только `description` и `keywords`.
- [ ] Добавить новые ключи настроек сайта в `default_option.php` с дефолтами из этого плана.
- [ ] После успешной установки данных сохранить `webcomp.shop:wizard_installed = Y`.
- [ ] После успешной установки данных сохранить `webcomp.shop:wizard_site_id = WIZARD_SITE_ID`.
- [ ] Не сохранять дублирующие значения в `webcomp.core`.
- [ ] Не вызывать `Resources::resetAll()` в этом сервисе.

### Задача 6.1: Добавить Заглушку Страницы Настроек До Запуска Визарда

**Файлы:**

- Изменить: `bitrix/modules/webcomp.shop/lib/Admin/Controllers/SettingsController.php`
- Возможно изменить: `bitrix/modules/webcomp.shop/lib/Admin/Views/layout.php`
- Возможно изменить: `bitrix/modules/webcomp.shop/install/admin/webcomp_shop_settings.php`
- Изменить: lang-файлы админки модуля

- [ ] При открытии `/bitrix/admin/webcomp_shop_settings.php` проверить `Option::get('webcomp.shop', 'wizard_installed', 'N')`.
- [ ] Если значение не `Y`, не показывать обычные вкладки настроек.
- [ ] Показать информационную заглушку: `Данные решения WebComp Shop еще не установлены. Сначала выполните установку данных.`
- [ ] Показать кнопку `Установить данные`.
- [ ] Кнопка должна вести на `/bitrix/admin/wizard_install.php?lang=ru&wizardName=webcomp%3Ashop&sessid=...`.
- [ ] Ссылку формировать через `bitrix_sessid()` и стандартные Bitrix-хелперы, без жестко прописанного sessid.
- [ ] Использовать D7 `Bitrix\Main\Config\Option` для проверки флага.
- [ ] Не запускать визард автоматически при открытии страницы настроек.
- [ ] После прохождения визарда и установки `wizard_installed = Y` страница настроек должна показывать обычный интерфейс.

### Задача 7: Реализовать Сервис Sale

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/locations.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/person_types.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/order_props.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/pay_systems.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/deliveries.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/sale/locations/`

- [ ] Подключить и проверить модуль `sale`.
- [ ] Скопировать `locations/` из стандартного Bitrix eShop только с поддерживаемыми наборами.
- [ ] Реализовать `locations.php`: импортировать местоположения согласно выбранному radio-значению и поддержать повтор этапа через `$this->repeatCurrentService`.
- [ ] Реализовать `person_types.php`: создать или обновить выбранные типы плательщиков, без `fiz_ua` и без деактивации чужих существующих типов.
- [ ] Реализовать `order_props.php`: создать группы и базовые свойства заказа для выбранных типов плательщиков, и искать свойства по `PERSON_TYPE_ID + CODE`.
- [ ] Реализовать `pay_systems.php`: создать или обновить `Наличные` с `XML_ID = webcomp_cash` и ограничениями по выбранным типам плательщиков.
- [ ] Реализовать `deliveries.php`: создать или обновить `Курьер` с `CODE = webcomp_courier`, `Самовывоз` с `CODE = webcomp_self`, а `Отправления 1 класса` только активировать при наличии готового обработчика.
- [ ] Остановиться с понятной ошибкой, если `sale` недоступен.

### Задача 8: Реализовать Сервис Инфоблока Мегаменю

**Файлы:**

- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/iblock/index.php`
- Создать: `bitrix/modules/webcomp.shop/install/wizards/webcomp/shop/site/services/iblock/xml/ru/menu/megamenu.xml`

- [ ] Скопировать `megamenu.xml` из бэкап-папки в новый путь визарда.
- [ ] Подключить и проверить модуль `iblock`.
- [ ] Прочитать `webcomp.shop:megamenu_iblock_id`.
- [ ] Удалить старый инфоблок, если он существует.
- [ ] Импортировать XML.
- [ ] Определить ID нового инфоблока.
- [ ] Сохранить новый ID в `webcomp.shop:megamenu_iblock_id`.

### Задача 9: Проверить Визард Вручную

**URL:**

```text
/bitrix/admin/wizard_install.php?wizardName=webcomp:shop
```

- [ ] Открыть визард в административной части.
- [ ] Проверить, что слева отображается ровно семь MVP-шагов.
- [ ] Проверить, что `PersonTypeStep` не дает перейти дальше, если оба чекбокса выключены.
- [ ] Проверить, что `PayDeliveryStep` не дает перейти дальше, если не выбран способ оплаты.
- [ ] Проверить, что `PayDeliveryStep` не дает перейти дальше, если не выбран способ доставки.
- [ ] Запустить установку.
- [ ] Подтвердить, что `/bitrix/templates/template` получил скопированные файлы.
- [ ] Подтвердить, что выбранный сайт использует шаблон `template`.
- [ ] Подтвердить, что `/bitrix/templates/template/css/generated/colors.css` создан.
- [ ] Подтвердить, что `/bitrix/templates/template/css/generated/brakepoints.css` создан.
- [ ] Подтвердить, что `/bitrix/templates/template/css/generated/styles.css` создан.
- [ ] Подтвердить, что `/bitrix/templates/template/css/generated/themes/` содержит файлы `theme-*.css`.
- [ ] Подтвердить, что типы плательщиков, оплата и доставка созданы в sale.
- [ ] Подтвердить, что инфоблок мегаменю существует.
- [ ] Подтвердить, что `webcomp.shop:megamenu_iblock_id` содержит ID нового инфоблока.

### Задача 10: Проверить Повторную Установку

- [ ] Запустить визард второй раз.
- [ ] Подтвердить, что файлы шаблона перезаписаны без удаления папки шаблона.
- [ ] Подтвердить, что старый инфоблок мегаменю удален.
- [ ] Подтвердить, что новый инфоблок мегаменю импортирован.
- [ ] Подтвердить, что `megamenu_iblock_id` изменился на актуальный ID инфоблока.
- [ ] Подтвердить, что дубли инфоблоков мегаменю не остались.

## Открытые Решения Перед Кодом

Перед началом реализации решить:

1. Точный исходный путь папки `template` внутри модуля или дистрибутива визарда.
2. Точные имена опций для информации о сайте после изучения `default_option.php` и использования в шаблоне.
3. Точный способ импорта CML2 XML: `WizardServices::ImportIBlockFromXML()` или проектная обертка импорта.

## Правила Реализации

- Перед кодом прочитать `AGENTS.md`, `docs/agents/README.md` и `docs/agents/bitrix-wizard.md`.
- При неясностях сверяться с официальной документацией Bitrix по визардам и с реализацией в `bitrix/modules/aspro.max`.
- Не использовать бэкап-namespace `~webcomp`.
- Не создавать `docs/plans` или `PLANNING.md`.
- Не редактировать ядро Bitrix.
- Перед изменением файлов описывать, что именно будет изменено, и ждать подтверждения.
- При ручном тестировании держать исходную и развернутую копии визарда синхронизированными или явно указывать, какая копия тестируется.
