<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

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
class NewsWidget extends CBitrixComponent
{
	private $iblockCode = 'news';
	private $typeBlockCode = 'first_bit';

	public function onPrepareComponentParams($arParams)
	{
		$iblockId = CIBlock::GetList(
			[],
			[
				"CODE" => $this->iblockCode,
				"TYPE" => "$this->typeBlockCode",
				"CHECK_PERMISSIONS" => "N"
			]
		)->fetch();
		$result = array(
			"NEWS_COUNT" => intval($arParams["NEWS_COUNT"]) > 0 ? intval($arParams["NEWS_COUNT"]) : 3,
			"URL_COMPONENT_NEWS" => $arParams["URL_COMPONENT_NEWS"],
			"IBLOCK_ID" => $iblockId['ID'],
		);
		return $result;
	}


	public function executeComponent()
	{
		if (!Loader::includeModule("iblock"))
		{
			ShowError(Loc::getMessage('BIT_NEWS_WIDGET_IBLOCK_NOT_INSTALL'));
			return;
		}
		if ($this->startResultCache())
		{
			if (defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->RegisterTag("iblock_id_{$this->arParams["IBLOCK_ID"]}");
			}
			$news = $this->processSample($this->getNews());
			$this->arResult["NEWS"] = $news;
			$this->arResult["URL_COMPONENT_NEWS"] = $this->arParams['URL_COMPONENT_NEWS'];
			if (!empty($this->arResult))
			{
				$this->IncludeComponentTemplate();
			} else
			{
				$this->AbortResultCache();
				\Bitrix\Iblock\Component\Tools::process404(
					Loc::getMessage('BIT_NEWS_SECTION_ELEMENT_NOT_FOUND'),
					true,
					true
				);
			}
		}
	}

	protected function createFilterNews()
	{
		$filter = [
			'=ACTIVE' => 'Y',
			'LOGIC' => 'AND',
			[
				'LOGIC' => 'OR',
				'>=ACTIVE_TO' => new \Bitrix\Main\Type\DateTime(),
				'ACTIVE_TO' => null,
			],
			[
				'LOGIC' => 'OR',
				'<=ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(),
				'ACTIVE_FROM' => null,
			],
		];
		$filter['IN_SECTIONS'] = 'Y';

		return $filter;
	}

	protected function getNews()
	{
		$query = new \Bitrix\Main\Entity\Query(\Bitrix\Iblock\Elements\ElementNewsTable::getEntity());
		$query
			->registerRuntimeField(
				null,
				new \Bitrix\Main\Entity\ExpressionField(
					'SORT_ACTIVE_FROM',
					'CASE WHEN %s IS NULL THEN %s ELSE %s END',
					['ACTIVE_FROM', 'DATE_CREATE', 'ACTIVE_FROM']
				)
			)
			->setOrder(['SORT_ACTIVE_FROM' => 'DESC'])
			->setFilter($this->createFilterNews())
			->setSelect(
				array('NAME',
					  'CODE',
					  'ID',
					  'ACTIVE_FROM',
					  "PREVIEW_TEXT",
					  "PREVIEW_PICTURE",
					  'DATE_CREATE',
					  'SECTIONS',
					  'SHOW_COUNTER',
				))
			->setLimit($this->arParams['NEWS_COUNT']);
		return $query;
	}

	protected function processSample($items)
	{
		$elementsCollection = \Bitrix\Main\ORM\Query\QueryHelper::decompose($items, false);
		$dateFormat = CSite::GetDateFormat();
		foreach ($elementsCollection as $properties)
		{
			$id = $properties->Get('ID');
			$elements[$id]['ID'] = $id;
			$elements[$id]['NAME'] = $properties->Get('NAME');
			$elements[$id]['CODE'] = $properties->Get('CODE');
			$elements[$id]['PREVIEW_TEXT'] = $properties->Get('PREVIEW_TEXT');
			$elements[$id]['PREVIEW_PICTURE'] = $properties->Get('PREVIEW_PICTURE');
			$datePublish = $properties->Get('ACTIVE_FROM') ?? $properties->Get('DATE_CREATE');
			$date = CIBlockFormatProperties::DateFormat(
				'd.m.Y',
				MakeTimeStamp($datePublish,
					$dateFormat)
			);
			$elements[$id]['DATE_CREATE'] = $date;
			foreach ($properties->getSections()->getAll() as $section)
			{
				$elements[$id]['SECTION_CODE'][] = $section->Get('CODE');
				break;
			}
			$elements[$id]['SHOW_COUNTER'] = $properties->Get('SHOW_COUNTER');
		}
		return $elements;
	}


}
