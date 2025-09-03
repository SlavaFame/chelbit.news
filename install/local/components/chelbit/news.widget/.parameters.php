<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('BIT_NEWS_WIDGET_QUANTITY'),
			"TYPE" => "STRING",
			"DEFAULT" => '3',
		),
		"URL_COMPONENT_NEWS" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('BIT_NEWS_WIDGET_URL_SECTION'),
			"TYPE" => "STRING",
			"DEFAULT" => '/news/',
		),
		"CACHE_TIME" => array("DEFAULT" => 36000),
		),
);
