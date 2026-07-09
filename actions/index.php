<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @global CMain $APPLICATION
 */

$APPLICATION->SetTitle("Акции и спецпредложения");
?><p class="text-16">
	 Сезонные предложения, подарки именинникам и специальные поводы для визита.
</p>
 <br>
<?$APPLICATION->IncludeComponent(
	"web-comp:offers.list",
	"main",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "main",
		"ELEMENT_IDS" => [0=>"632",],
		"IBLOCK_ID" => "18",
		"IBLOCK_TYPE" => "content"
	)
);?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>