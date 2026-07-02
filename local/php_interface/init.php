<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Push & Pull: сервер очередей не используется (настройки модуля pull → «не использовать»),
// но модуль всё равно инжектит BX.PULL.start(), который падает в консоли с PULL_DISABLED.
// Константа штатно отключает инициализацию клиента (так же поступают модули mobileapp и landing).
// Если когда-нибудь настроите push-сервер — удалите этот define.
if (!defined('BX_PULL_SKIP_INIT'))
{
	define('BX_PULL_SKIP_INIT', true);
}
