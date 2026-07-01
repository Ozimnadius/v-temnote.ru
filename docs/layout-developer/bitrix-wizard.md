# Bitrix Wizard — База знаний для агентов

Справочник по системе визардов Bitrix Framework. Написан на основе официальной документации
и практики работы с `webcomp.core`. Читать перед любой задачей, связанной с мастерами установки.

---

## 1. Как работает система (архитектура)

### Два типа визардов

**Простой визард** — произвольный пошаговый UI. Шаги и навигацию описывает разработчик вручную.
Запускается через `CWizardBase` или `CWizard`.

**Сайтовый визард** (`wizard_sol`) — специализированный для установки решений. Автоматически
добавляет шаги выбора сайта, шаблонов, сервисов на основе описательных файлов
(`.sites.php`, `.templates.php`, `.services.php`). Именно этот тип используется в `webcomp.core`.

### Два режима мастера создания сайта

У мастера создания сайта есть два разных режима, и их нельзя смешивать:

1. **Автоматический режим без `wizard.php`.** Если в папке мастера нет `wizard.php`,
   Bitrix генерирует цепочку шагов по файлам описания: `.sites.php`, `.templates.php`,
   `.services.php`, `license.php`. В этом режиме `.services.php` описывает массив
   `$arWizardServices`.
2. **Кастомный режим с `wizard.php` и `wizard_sol`.** Если `wizard.php` есть, цепочка
   шагов берётся из классов, указанных в `STEPS`, а стандартные шаги можно наследовать
   из `/bitrix/modules/main/install/wizard_sol/wizard.php`. В этом режиме стандартный
   `CDataInstallWizardStep` читает `/site/services/.services.php`, где должен быть
   массив `$arServices` со `STAGES`.

В `webcomp.core` используется второй режим: `wizard.php` + классы шагов + `site/services/.services.php`.

### Жизненный цикл

```
DoInstall() в install/index.php
  └─ InstallFiles()  — копирует install/wizards/ → /bitrix/wizards/
  └─ CWizard::Install()  — запускает визард (или вызывается из admin-страницы)

При открытии визарда:
  1. .description.php  — загружается, формирует $arWizardDescription
  2. wizard.php        — загружается, определяет классы шагов
  3. Для каждого шага: InitStep() → ShowStep()
  4. При клике «Далее»: OnPostForm() → если нет ошибок → переход на nextStep

При установке сервисов (DataInstallStep):
  5. AJAX-цикл: каждый файл из services/ включается через include()
  6. Внутри файла доступны константы WIZARD_SITE_ID, WIZARD_INSTALL_DEMO_DATA и др.
```

### Как шаги связаны — ВАЖНО

Шаги связаны **по StepID** (строковый идентификатор), а **не** по порядку в массиве `STEPS`.

```php
// В InitStep() каждого шага:
$this->SetStepID('select_site');    // ID этого шага
$this->SetNextStep('data_install'); // ID следующего шага
$this->SetPrevStep('select_site');  // ID предыдущего шага
```

Когда пользователь нажимает «Далее», визард ищет шаг с `StepID == nextStep`. Если такого
шага нет в списке — страница остаётся на месте (без ошибки!).

---

## 2. Файловая структура

### Расположение файлов

```
/bitrix/wizards/                     ← репозиторий всех визардов
    <namespace>/                     ← пространство имён (webcomp, bitrix, aspro...)
        <name>/                      ← папка визарда (core, demo, site...)
            .description.php         ← ОБЯЗАТЕЛЬНО: описание визарда
            wizard.php               ← классы шагов (обязателен для простого/кастомного режима)
            .sites.php               ← типы сайта (автоматический режим, опционально)
            .templates.php           ← группы и шаблоны (автоматический режим, опционально)
            .services.php            ← сервисы (автоматический режим, опционально)
            license.php              ← лицензионное соглашение (опционально)
            scripts/                 ← дополнительные классы шагов и шаблоны мастера
            lang/
                ru/
                    .description.php ← языковые файлы (та же структура)
                    wizard.php
                    site/
                        services/
                            .services.php
            site/
                public/
                    ru/              ← публичная часть решения
                services/
                    .services.php    ← сервисы для wizard.php + CDataInstallWizardStep
                    <service_id>/
                        index.php    ← скрипт установки сервиса
                templates/           ← шаблоны сайта
            images/
                ru/
                    solution.png     ← скриншот для списка визардов
```

### Структура модуля webcomp.core

```
bitrix/modules/webcomp.core/
    install/
        index.php                    ← DoInstall() / DoUninstall()
        wizards/                     ← ИСТОЧНИК (копируется при установке модуля)
            webcomp/
                core/
                    .description.php
                    wizard.php
                    lang/ru/
                        .description.php
                    site/
                        services/
                            .services.php
                            iblock/
                                megamenu.php

/bitrix/wizards/webcomp/core/        ← РАЗВЁРНУТАЯ КОПИЯ (работает из неё!)
    .description.php
    wizard.php
    ...
```

**Критично:** при редактировании файлов в `install/wizards/` — изменения не применяются
автоматически. Нужно либо переустановить модуль (DoInstall → InstallFiles), либо
отредактировать развёрнутую копию напрямую.

### InstallFiles() в install/index.php

```php
public function InstallFiles(): bool
{
    CopyDirFiles(
        __DIR__ . '/wizards',                         // источник
        $_SERVER['DOCUMENT_ROOT'] . '/bitrix/wizards', // назначение
        true,   // перезаписывать
        true    // рекурсивно
    );
    return true;
}
```

---

## 3. ⚠️ Навигация шагов — критические правила

### Стандартные StepID из wizard_sol

Базовые классы из `/bitrix/modules/main/install/wizard_sol/wizard.php` задают жёстко
прописанные StepID и nextStep:

| Класс                          | SetStepID()         | SetNextStep()       |
|-------------------------------|---------------------|---------------------|
| `CSelectSiteWizardStep`       | `"select_site"`     | `"select_template"` |
| `CSelectTemplateWizardStep`   | `"select_template"` | `"select_theme"`    |
| `CSelectThemeWizardStep`      | `"select_theme"`    | `"site_settings"`   |
| `CSiteSettingsWizardStep`     | `"site_settings"`   | `"data_install"`    |
| `CDataInstallWizardStep`      | `"data_install"`    | _(через AJAX)_      |
| `CFinishWizardStep`           | `"finish"`          | `"finish"`          |

### Типичная ошибка при кастомном wizard_sol

Если ваш визард использует `SelectSiteStep → DataInstallStep → FinishStep` (без шагов
выбора шаблона), то `CSelectSiteWizardStep::InitStep()` по умолчанию указывает
`SetNextStep("select_template")` — а такого шага у вас нет. Кнопка «Далее» нажимается,
форма отправляется (POST 200), но страница остаётся на шаге 1.

**Исправление:** переопределить `SetNextStep` в вашем `SelectSiteStep`:

```php
class SelectSiteStep extends CSelectSiteWizardStep
{
    public function InitStep(): void
    {
        parent::InitStep();
        $wizard =& $this->GetWizard();
        $wizard->solutionName = WEBCOMP_MODULE_NAME_SHORT;
        $this->SetNextStep('data_install'); // ← переопределяем "select_template"
    }
}
```

### Как отлаживать навигацию

1. Проверить `SetStepID()` в каждом шаге — они должны образовывать цепочку.
2. Проверить `OnPostForm()` родительского класса — ищите `return;` без `SetError()` как признак
   того, что шаг считает переход успешным.
3. Убедиться, что `SetError()` нигде не вызывается ненужно.
4. Убедиться, что развёрнутая копия соответствует исходникам.

---

## 4. .description.php — справочник параметров

### Полная структура

```php
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Константы доступны из .description.php и далее во всём визарде
define('WEBCOMP_PARTNER_NAME',      'webcomp');
define('WEBCOMP_MODULE_NAME_SHORT', 'core');
define('WEBCOMP_MODULE_NAME',       WEBCOMP_PARTNER_NAME . '.' . WEBCOMP_MODULE_NAME_SHORT);

// Если модуль передал siteID через URL — определяем константу
if (!defined('WIZARD_DEFAULT_SITE_ID') && !empty($_REQUEST['wizardSiteID'])) {
    define('WIZARD_DEFAULT_SITE_ID', $_REQUEST['wizardSiteID']);
}

$arWizardDescription = [
    'NAME'         => GetMessage('WEBCOMP_CORE_WIZARD_NAME'),
    'DESCRIPTION'  => GetMessage('WEBCOMP_CORE_WIZARD_DESC'),
    'VERSION'      => '1.0.0',
    'START_TYPE'   => 'WINDOW',   // 'WINDOW' — открыть в popup
    'WIZARD_TYPE'  => 'INSTALL',  // 'INSTALL' — тип установки
    'IMAGE'        => '/images/' . LANGUAGE_ID . '/solution.png',
    'PARENT'       => 'wizard_sol', // родительский визард (наследует шаблон)
    'TEMPLATES'    => [['SCRIPT' => 'wizard_sol']], // использовать шаблон wizard_sol
    'STEPS'        => [
        'SelectSiteStep',
        'DataInstallStep',
        'FinishStep',
    ],
    'DEPENDENCIES' => [
        'main' => '22.0.0',
        'iblock' => '22.0.0',
    ],
];

// Если siteID уже известен — убираем шаг выбора сайта
if (defined('WIZARD_DEFAULT_SITE_ID')) {
    unset($arWizardDescription['STEPS'][
        array_search('SelectSiteStep', $arWizardDescription['STEPS'])
    ]);
}
```

### Ключи $arWizardDescription

| Ключ            | Обязателен | Описание |
|-----------------|-----------|----------|
| `NAME`          | да        | Название визарда в списке |
| `DESCRIPTION`   | нет       | Описание |
| `ICON`          | нет       | Иконка мастера, путь относительно папки мастера |
| `VERSION`       | нет       | Версия (строка) |
| `CHARSET`       | нет       | Кодировка файлов мастера при сборке/конвертации дистрибутива |
| `START_TYPE`    | нет       | Для `wizard_sol`: `'WINDOW'` — открыть popup |
| `WIZARD_TYPE`   | нет       | Для `wizard_sol`: `'INSTALL'` / `'CONFIG'` |
| `IMAGE`         | нет       | Для `wizard_sol`: скриншот решения, путь от папки мастера |
| `PARENT`        | нет       | Для `wizard_sol`: родительский визард, например `wizard_sol` |
| `TEMPLATES`     | нет       | Массив шаблонов `[['SCRIPT'=>..., 'CLASS'=>...]]` |
| `STEPS`         | да*       | Массив имён классов шагов |
| `DEPENDENCIES`  | нет       | Зависимости от модулей `['module_id' => 'version']` |
| `STEPS_SETTINGS`| нет       | Кастомизация системных шагов wizard_sol |

\* Обязателен для простого визарда; в сайтовом может отсутствовать, если шаги генерируются
из `.templates.php` / `.services.php`.

### TEMPLATES — подключение шаблона

```php
// Использовать стандартный шаблон wizard_sol (WizardTemplate):
'TEMPLATES' => [['SCRIPT' => 'wizard_sol']],

// Использовать кастомный шаблон:
'TEMPLATES' => [
    ['SCRIPT' => 'scripts/template.php', 'CLASS' => 'MyTemplate'],
    // STEP — опционально, если нет — применяется ко всем шагам
    ['SCRIPT' => 'scripts/template.php', 'CLASS' => 'SpecialTemplate', 'STEP' => 'data_install'],
],
```

---

## 5. wizard.php — классы шагов

### Подключение базовых классов

Первая строка в `wizard.php` — всегда:

```php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php');
```

Без этого классы `CSelectSiteWizardStep`, `CDataInstallWizardStep`, `CFinishWizardStep`
будут не определены → fatal error.

### Минимальная структура для wizard_sol

```php
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php');

class SelectSiteStep extends CSelectSiteWizardStep
{
    public function InitStep(): void
    {
        parent::InitStep();
        $wizard =& $this->GetWizard();
        $wizard->solutionName = WEBCOMP_MODULE_NAME_SHORT;
        $this->SetNextStep('data_install'); // ВАЖНО: переопределить default "select_template"
    }
}

class DataInstallStep extends CDataInstallWizardStep
{
    // Стандартная реализация достаточна для большинства случаев.
    // Override CorrectServices() если нужно динамически изменить список сервисов.
}

class FinishStep extends CFinishWizardStep
{
}
```

### Методы CWizardStep (шаг)

```php
// В InitStep():
$this->SetStepID('my_step');             // StepID — строковый ключ навигации
$this->SetTitle('Заголовок');
$this->SetSubTitle('Подзаголовок');
$this->SetNextStep('next_step_id');
$this->SetPrevStep('prev_step_id');
$this->SetNextCaption('Установить');     // текст кнопки «Далее»
$this->SetPrevCaption('Назад');
$this->SetCancelStep('cancel');
$this->SetAutoSubmit();                  // автоматически отправить форму при показе шага

// В ShowStep() — контент шага:
$this->content .= '<p>Текст</p>';
$this->content .= $this->ShowInputField('text', 'var_name', ['size' => 25]);
$this->content .= $this->ShowCheckBoxField('install_data', 'Y');
$this->content .= $this->ShowRadioField('mode', 'new', ['id' => 'mode_new']);
$this->content .= $this->ShowSelectField('site_id', $arOptions);
$this->content .= $this->ShowHiddenField('service', 'iblock');
$this->content .= $this->ShowFileField('photo', ['max_file_size' => 50*1024]);

// В OnPostForm() — обработка:
$wizard =& $this->GetWizard();
if ($wizard->IsNextButtonClick()) { /* ... */ }
if ($wizard->IsCancelButtonClick()) { return; }
$value = $wizard->GetVar('var_name');   // получить значение из формы/сессии
$wizard->SetVar('result_id', $id);      // сохранить значение для следующих шагов
$this->SetError('Ошибка', 'field_name'); // остановить переход, показать ошибку
```

### Методы CWizardBase (визард)

```php
$wizard =& $this->GetWizard();

$wizard->GetVar('key');           // значение переменной (из сессии/POST)
$wizard->SetVar('key', $value);   // сохранить переменную
$wizard->SetDefaultVar('key', $default); // сохранить только если переменная не задана
$wizard->GetFormName();           // имя HTML-формы (для JS)
$wizard->GetNextButtonID();       // ID кнопки «Далее» (для JS onclick)
$wizard->GetRealName('var');      // реальное имя поля в форме (с namespace)
$wizard->GetPath();               // путь к папке визарда от корня
$wizard->IsNextButtonClick();     // пользователь нажал «Далее»
$wizard->IsCancelButtonClick();   // пользователь нажал «Отмена»
$wizard->SetCurrentStep('finish');// принудительно перейти на шаг
$wizard->solutionName;            // имя решения (используется CFinishWizardStep)
```

---

## 6. Services (.services.php)

### Назначение

Services — скрипты, которые выполняются на шаге `DataInstallStep` через AJAX-цикл.
Каждый service = папка с `index.php` (или несколькими файлами — stages).

### Два формата .services.php

В официальной документации встречаются два массива, потому что это два разных режима:

- `$arWizardServices` — для автоматического мастера создания сайта без `wizard.php`.
  Такой файл `.services.php` лежит в корне папки мастера и описывает сервисы, шаг
  выбора сервисов, файлы для копирования и дополнительные шаги.
- `$arServices` — для кастомного `wizard_sol` с `wizard.php`, когда используется
  стандартный `CDataInstallWizardStep`. Такой файл лежит в `site/services/.services.php`
  и описывает только install-stages, которые будут подключены через AJAX-цикл.

### site/services/.services.php для wizard.php

Лежит в `site/services/.services.php`. Определяет переменную `$arServices`:

```php
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arServices = [
    'iblock' => [
        'NAME'   => GetMessage('SERVICE_IBLOCK_NAME'),
        'STAGES' => ['index.php'], // список файлов-этапов
    ],
    // Многоэтапный сервис:
    'megamenu' => [
        'NAME'   => GetMessage('SERVICE_MEGAMENU_NAME'),
        'STAGES' => ['step1.php', 'step2.php'],
    ],
];
```

### Как CDataInstallWizardStep читает services

```php
// В wizard_sol/wizard.php, метод GetServices():
WizardServices::GetServices(
    $_SERVER['DOCUMENT_ROOT'] . $wizard->GetPath(),
    '/site/services/'
);
// Ищет файл /site/services/.services.php и возвращает $arServices
```

### Пример сервиса: создание инфоблока

```php
// site/services/iblock/megamenu.php
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option;

// Гейт: устанавливать только если пользователь выбрал «установить демо-данные»
if (!WIZARD_INSTALL_DEMO_DATA) {
    return;
}

$xmlId = 'webcomp_core_megamenu_' . WIZARD_SITE_ID;

// Проверить: инфоблок уже существует?
$existing = CIBlock::GetList([], ['XML_ID' => $xmlId])->Fetch();
if ($existing) {
    Option::set(WEBCOMP_MODULE_NAME, 'megamenu_iblock_id', $existing['ID']);
    return;
}

// Создать инфоблок
$ib = new CIBlock();
$iblockId = $ib->Add([
    'ACTIVE'      => 'Y',
    'NAME'        => 'Megamenu',
    'CODE'        => 'megamenu_' . WIZARD_SITE_ID,
    'XML_ID'      => $xmlId,
    'IBLOCK_TYPE_ID' => 'content',
    'SITE_ID'     => [WIZARD_SITE_ID],
]);

if ($iblockId) {
    Option::set(WEBCOMP_MODULE_NAME, 'megamenu_iblock_id', $iblockId);
}
```

### WizardServices::ImportIBlockFromXML()

Для импорта инфоблока из CML2 XML:

```php
// Метод доступен через require wizard_sol/wizard.php
$iblockId = WizardServices::ImportIBlockFromXML(
    WIZARD_SERVICE_RELATIVE_PATH . '/xml/' . LANGUAGE_ID . '/feedback.xml',
    'feedback',
    'content',
    WIZARD_SITE_ID,
    [
        '1' => 'X',
        '2' => 'R',
    ]
);
```

Сигнатура метода: `ImportIBlockFromXML($xmlFile, $iblockCode, $iblockType, $siteID, $permissions = [])`.
Перед вызовом нужно убедиться, что модуль `iblock` подключен и нужный тип инфоблока уже существует
или создаётся отдельным шагом.

---

## 7. Константы во время установки сервисов

Все константы определяются в `CDataInstallWizardStep::InstallService()` непосредственно
перед `include()` файла сервиса.

| Константа                    | Значение |
|------------------------------|----------|
| `WIZARD_SITE_ID`             | ID сайта (например `'s1'`) |
| `WIZARD_SITE_DIR`            | Директория сайта (например `'/'`) |
| `WIZARD_SITE_ROOT_PATH`      | Абсолютный путь к корню сайта |
| `WIZARD_SITE_PATH`           | = `WIZARD_SITE_ROOT_PATH + WIZARD_SITE_DIR` |
| `WIZARD_RELATIVE_PATH`       | Путь к папке визарда от корня (например `/bitrix/wizards/webcomp/core`) |
| `WIZARD_ABSOLUTE_PATH`       | = `$_SERVER['DOCUMENT_ROOT'] + WIZARD_RELATIVE_PATH` |
| `WIZARD_TEMPLATE_ID`         | ID выбранного шаблона |
| `WIZARD_TEMPLATE_RELATIVE_PATH` | Путь к шаблону от корня |
| `WIZARD_TEMPLATE_ABSOLUTE_PATH` | Абсолютный путь к шаблону |
| `WIZARD_THEME_ID`            | ID выбранной темы |
| `WIZARD_THEME_RELATIVE_PATH` | Путь к теме |
| `WIZARD_THEME_ABSOLUTE_PATH` | Абсолютный путь к теме |
| `WIZARD_SERVICE_RELATIVE_PATH` | Путь к текущему сервису от корня сайта |
| `WIZARD_SERVICE_ABSOLUTE_PATH` | Абсолютный путь к текущему сервису |
| `WIZARD_IS_RERUN`            | Признак повторного запуска мастера |
| `WIZARD_INSTALL_DEMO_DATA`   | `true` если пользователь выбрал демо-данные |
| `WIZARD_FIRST_INSTAL`        | `'Y'` если уже устанавливалось ранее |
| `WIZARD_SITE_LOGO`           | ID файла логотипа |

Другие константы могут появляться в конкретной реализации мастера, но перед использованием их нужно
проверять в текущем `wizard_sol/wizard.php` или в проектном коде.

---

## 8. Запуск визарда из модуля

### Из страницы установки модуля (admin)

После `DoInstall()` Bitrix сам предлагает запустить визард, если он зарегистрирован.
Запуск происходит через `/bitrix/admin/wizard_install.php?wizardName=webcomp:core`.

### Программный запуск

```php
// Из публичной части или admin-страницы:
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/wizard.php');
$wizard = new CWizard('webcomp:core');
$wizard->Install();

// Вне репозитория /bitrix/wizards/ (для встроенных мастеров):
$wizard = new CWizardBase('Мой мастер', null);
$wizard->AddStep(new Step1);
$wizard->AddStep(new Step2);
$wizard->Display();
```

### Идентификатор визарда

```
<namespace>:<name>     →   webcomp:core
```

Или без namespace если визард лежит прямо в `/bitrix/wizards/<name>/`.

### Дополнительные официальные уроки по типовым сайтам

Полезные страницы официального курса:

- [Решения типовых сайтов](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=101&LESSON_ID=3225&LESSON_PATH=8781.4793.3225)
- [Частые ошибки](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=101&LESSON_ID=3224&LESSON_PATH=8781.4793.3224)

Важные выводы:

- `CWizardUtil::ReplaceMacros()` и `CWizardUtil::ReplaceMacrosRecursive()` использовать для подстановки макросов в уже скопированных файлах публичной части или шаблона.
- Замена макросов не является механизмом хранения настроек. Для данных, которые должны жить после установки и редактироваться в админке, использовать `Option`, сущности Bitrix или файлы публичной части по назначению.
- При повторной установке заранее разделять сущности, которые можно перезаписать, и сущности, которые надо сохранить или обновить без удаления пользовательских данных.
- В публичной части мастера файл главной страницы обычно хранится как `_index.php`, чтобы при копировании не конфликтовать с системной обработкой `index.php`.
- Кодировкозависимые строки держать в `lang/`, а в PHP-файлах оставлять только логику и вызовы локализации.
- Для мастера установки решения на базе `wizard_sol` в `.description.php` явно задавать `START_TYPE => 'WINDOW'` и `WIZARD_TYPE => 'INSTALL'`.

---

## 9. Подводные камни (из практики)

### ❌ RestartBuffer() внутри wizard.php

```php
// НЕЛЬЗЯ:
$GLOBALS['APPLICATION']->RestartBuffer(); // убивает HTML-вывод шаблона визарда
```

`RestartBuffer()` очищает буфер вывода и сбрасывает заголовки. Внутри визарда HTML
шаблона (WizardTemplate) уже начал отрисовываться — после вызова RestartBuffer()
страница выдаёт пустой ответ или обрывается. Wizard.php — не компонент, не страница,
здесь этот вызов запрещён.

### ❌ SetNextStep не переопределён после parent::InitStep()

```php
// ПРОБЛЕМА: parent задаёт nextStep = "select_template", которого нет в визарде
class SelectSiteStep extends CSelectSiteWizardStep
{
    public function InitStep(): void
    {
        parent::InitStep(); // ← устанавливает SetNextStep("select_template")
        $wizard =& $this->GetWizard();
        $wizard->solutionName = WEBCOMP_MODULE_NAME_SHORT;
        // Забыли переопределить! Кнопка «Далее» не работает.
    }
}

// ИСПРАВЛЕНИЕ:
$this->SetNextStep('data_install');
```

Симптом: форма отправляется (POST 200), но страница остаётся на шаге 1.

### ❌ Редактирование source, но не deployed copy

```
install/wizards/webcomp/core/wizard.php   ← редактируем здесь
/bitrix/wizards/webcomp/core/wizard.php   ← выполняется этот файл
```

После редактирования source-файла нужно либо переустановить модуль
(`DoInstall()` → `InstallFiles()`), либо применить изменение к обоим файлам.

### ❌ Файлы с точкой (.description.php) на Windows

Windows скрывает файлы с точкой в начале имени. Если `.description.php`
или `.services.php` не видны — снять атрибут «Скрытый» с корневой папки рекурсивно.

### ❌ require_once пропущен в wizard.php

```php
// Без этой строки — fatal error "Class CSelectSiteWizardStep not found":
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php');
```

### ❌ Неверный путь в InstallFiles()

```php
// БЫЛО (ошибка — папка называлась ~wizards):
CopyDirFiles(__DIR__ . '/~wizards', ...);

// ПРАВИЛЬНО:
CopyDirFiles(__DIR__ . '/wizards', ...);
```

### ✅ Чеклист перед тестом визарда

1. Оба файла актуальны (source и deployed copy).
2. `wizard.php` начинается с `require_once` wizard_sol.
3. Нет `RestartBuffer()` в `wizard.php`.
4. `SelectSiteStep::InitStep()` вызывает `$this->SetNextStep('data_install')`.
5. Для режима с `wizard.php` в `site/services/.services.php` есть `$arServices` (не `$arWizardServices`).
6. Языковые файлы лежат в `lang/<lang_id>/` с той же структурой.
