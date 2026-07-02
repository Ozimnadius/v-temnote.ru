<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
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
	"HEADER_TEXT" => Array(
		"NAME" => GetMessage("NEWS_LIST_HEADER_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"BUTTON_TEXT" => Array(
		"NAME" => GetMessage("NEWS_LIST_BUTTON_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"BUTTON_URL" => Array(
		"NAME" => GetMessage("NEWS_LIST_BUTTON_URL"),
		"TYPE" => "STRING",
		"DEFAULT" => "/news/",
	),
);
