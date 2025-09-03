<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => Loc::getMessage('BIT_NEWS_WIDGET_NAME_COMPONENT'),
	"DESCRIPTION" => Loc::getMessage('BIT_NEWS_WIDGET_DESCRIPTION_COMPONENT'),
	'PATH' => array(
		'ID' => 'news_components',
		'NAME' => Loc::getMessage('BIT_NEWS_WIDGET_FIRST_LEVEL'),
		'CHILD' => array(
			'ID' => 'news_iblock',
			'NAME' => Loc::getMessage('BIT_NEWS_WIDGET_SECOND_LEVEL')
		)
	)
);
