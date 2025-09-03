<?php
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;
$APPLICATION->SetTitle(Loc::getMessage('BIT_SECTION_NEWS_TITLE_PAGE'));
global $USER;
$group='';
if ($USER->IsAdmin() || $group=="news_admin")
{
$arButtons = CIBlock::GetPanelButtons(
	$arParams['IBLOCK_ID'],
	0,
	(int)$arResult["SECTION_ID"],
	array(
		"SESSID" => false
	)
);
\Bitrix\Main\Loader::includeModule('ui');

$linkButton = new \Bitrix\UI\Buttons\Button([
	"link" => $arButtons['submenu']['element_list']['ACTION_URL'],
	"text" => Loc::getMessage('BIT_NEWS_SECTION_BUTTON_LIST')
]);
\Bitrix\UI\Toolbar\Facade\Toolbar::addButton($linkButton);
}

if($arResult["FILTER"]){
	\Bitrix\UI\Toolbar\Facade\Toolbar::addFilter([
		'FILTER_ID' => $arResult['FILTER']['ID'],
		'GRID_ID' => $arResult["GRID"]["ID"],
		'FILTER' => $arResult['FILTER']['FILTER_PARAMS'] ?: [],
		'FILTER_PRESETS' => $arResult['FILTER']['FILTER_PRESETS'] ?: [],
		'ENABLE_LABEL' => true ,
		'RESET_TO_DEFAULT_MODE' => true,
	]);
}