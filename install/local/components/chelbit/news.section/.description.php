<?

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
	'NAME' => Loc::getMessage('BIT_NEWS_SECTION_NAME_COMPONENT'),
	'DESCRIPTION' => Loc::getMessage('BIT_NEWS_SECTION_DESCRIPTION'),
	'ICON' => '/images/icon.gif',
	'CACHE_PATH' => 'Y',
	'SORT' => 40,
	'PATH' => [
		'ID' => 'news_components',
		'NAME' => Loc::getMessage('BIT_NEWS_SECTION_FIRST_LEVEL_ID'),
		'CHILD' => [
			'ID' => 'news_iblock',
			'NAME' => Loc::getMessage('BIT_NEWS_SECTION_SECOND_LEVEL_ID')
		]
	]
];
