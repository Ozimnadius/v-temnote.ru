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
	"INTRO_TEXT" => Array(
		"NAME" => GetMessage("HOME_ABOUT_INTRO_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("HOME_ABOUT_INTRO_TEXT_DEFAULT"),
		"ROWS" => 10,
		"COLS" => 60,
	),
	"NOTICE_TEXT" => Array(
		"NAME" => GetMessage("HOME_ABOUT_NOTICE_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("HOME_ABOUT_NOTICE_TEXT_DEFAULT"),
		"ROWS" => 10,
		"COLS" => 60,
	),
	"BUTTON_TEXT" => Array(
		"NAME" => GetMessage("HOME_ABOUT_BUTTON_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("HOME_ABOUT_BUTTON_TEXT_DEFAULT"),
	),
);
