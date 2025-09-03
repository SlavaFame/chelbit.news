<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!CModule::IncludeModule('iblock'))
{
	return;
}
Loader::includeModule('iblock');
$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arInfoBlocks = array();
$arFilterInfoBlocks = array('ACTIVE' => 'Y');
$arOrderInfoBlocks = array('SORT' => 'ASC');
if (!empty($arCurrentValues['IBLOCK_TYPE']))
{
	$arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);
while ($obIBlock = $rsIBlock->Fetch())
{
	$arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}
$arComponentParameters = [
	"PARAMETERS" => [
		"VARIABLE_ALIASES" => [
			"ELEMENT_CODE" => [
				"NAME" => Loc::getMessage('BIT_NEWS_ELEMENT_CODE'),
			],
			"SECTION_CODE" => [
				"NAME" => Loc::getMessage('BIT_NEWS_SECTION_CODE'),
			],
			"CATALOG_URL" => [
				"NAME" => Loc::getMessage('BIT_NEWS_CATALOG_URL'),
			]
		],
		"SEF_MODE" => [
			"section" => [
				"NAME" => Loc::getMessage('BIT_NEWS_SEF_MODE'),
				"DEFAULT" => "#SECTION_CODE#/",
				"VARIABLES" => [
					"SECTION_ID",
					"SECTION_CODE",
					"SECTION_CODE_PATH",
				],
			],
			"element" => [
				"NAME" => Loc::getMessage('BIT_NEWS_SEF_MODE_DETAIL_PAGE'),
				"DEFAULT" => "#SECTION_CODE#/#ELEMENT_CODE#/",
				"VARIABLES" => [
					"ELEMENT_ID",
					"ELEMENT_CODE",
					"SECTION_ID",
					"SECTION_CODE",
					"SECTION_CODE_PATH",
				]
			]
		],
		"NEWS_COUNT" => [
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('BIT_NEWS_NUMBER_NEWS'),
			"TYPE" => "STRING",
			"DEFAULT" => "3",
		],

	]
];
