<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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

// проверка на ЧПУ
if (empty($arParams['VARIABLE_ALIASES']))
{
	$URL_CHPU = true;
} else
{
	$URL_CHPU = false;
}

// подключаем компонент
$APPLICATION->IncludeComponent(
	"chelbit:news.detail",
	"",
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CACHE_GROUPS" => "N",
		"URL_CHPU" => $URL_CHPU,
		"IBLOCK_ID" => $arResult["IBLOCK_ID"],
		"SEF_FOLDER" => $arParams["SEF_FOLDER"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CODE_SECTION" => $arResult["VARIABLES"]["CODE_SECTION"],
	),
	$component
);
