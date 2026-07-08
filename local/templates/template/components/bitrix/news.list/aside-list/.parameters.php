<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arSections = array(
	"" => GetMessage("T_IBLOCK_DESC_PARENT_SECTION_EMPTY"),
);

if (
	isset($arCurrentValues["IBLOCK_ID"])
	&& (int)$arCurrentValues["IBLOCK_ID"] > 0
	&& CModule::IncludeModule("iblock")
) {
	$rsSections = CIBlockSection::GetList(
		array("LEFT_MARGIN" => "ASC"),
		array(
			"IBLOCK_ID" => (int)$arCurrentValues["IBLOCK_ID"],
			"ACTIVE" => "Y",
		),
		false,
		array("ID", "NAME", "DEPTH_LEVEL")
	);

	while ($arSection = $rsSections->Fetch()) {
		$arSections[$arSection["ID"]] = str_repeat(". ", max(0, (int)$arSection["DEPTH_LEVEL"] - 1)) . $arSection["NAME"];
	}
}

$arTemplateParameters = array(
	"PARENT_SECTION" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("T_IBLOCK_DESC_PARENT_SECTION"),
		"TYPE" => "LIST",
		"VALUES" => $arSections,
		"DEFAULT" => "",
		"ADDITIONAL_VALUES" => "N",
	),
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_NAME" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
);
