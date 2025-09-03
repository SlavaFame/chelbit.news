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
} ?>

<div id="newsListContainer">
	<?php
	$request = \Bitrix\Main\Application::getInstance();
	if ($request->getContext()->getRequest()->isAjaxRequest())
	{
		$APPLICATION->RestartBuffer();
	}

	// подключаем компонент
	$APPLICATION->IncludeComponent(
		"chelbit:news.section",
		"",
		array(
			"CACHE_TIME" => "3600",
			"CACHE_GROUPS" => "N",
			"CACHE_TYPE" => "A",
			"URL_CHPU" => $URL_CHPU,
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"SEF_FOLDER" => $arParams["SEF_FOLDER"],
			"NEWS_COUNT" => $arParams["NEWS_COUNT"],
			"PAGE_NUMBER" => $_GET['nav-more-news'],
		),
		$component
	);

	if ($request->getContext()->getRequest()->isAjaxRequest())
	{
		die();
	}
	?>
