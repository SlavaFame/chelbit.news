<?

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	'NAME' => Loc::getMessage('BIT_NEWS_DETAIL_NAME_COMPONENT'),
	'DESCRIPTION' => Loc::getMessage('BIT_NEWS_DETAIL_DESCRIPTION'),
	'ICON' => '/images/icon.gif',
	'CACHE_PATH' => 'Y',
	'SORT' => 40,
	'PATH' => array(
		'ID' => 'news_components',
		'NAME' => Loc::getMessage('BIT_NEWS_DETAIL_FIRST_LEVEL_ID'),
		'CHILD' => array(
			'ID' => 'news_iblock',
			'NAME' => Loc::getMessage('BIT_NEWS_DETAIL_SECOND_LEVEL_ID')
		)
	)
);
