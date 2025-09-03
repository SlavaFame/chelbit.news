<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException,
	Bitrix\Main\Loader;

class NewsList extends CBitrixComponent
{
	private array $filterData = [];

	public function getFilterParams(): array
	{
		return [
			['id' => 'date', 'name' => Loc::getMessage('BIT_NEWS_SECTION_DATE_CREATE'), 'type' => 'date',
			 'exclude' => [
				 \Bitrix\Main\UI\Filter\DateType::TOMORROW,
				 \Bitrix\Main\UI\Filter\DateType::CURRENT_QUARTER,
				 \Bitrix\Main\UI\Filter\DateType::PREV_DAYS,
				 \Bitrix\Main\UI\Filter\DateType::NEXT_DAYS,
				 \Bitrix\Main\UI\Filter\DateType::NEXT_WEEK,
				 \Bitrix\Main\UI\Filter\DateType::NEXT_MONTH,
			 ]
			],
		];
	}

	protected function createFilter()
	{
		$this->arResult["FILTER"]['FILTER_PARAMS'] = $this->getFilterParams();
		$this->arResult["FILTER"]['ID'] = 'NEWS_FILTER';

	}

	// выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
	public function executeComponent()
	{
		try
		{
			$this->createFilter();
			$this->checkModules();
			$this->getResult();
		} catch (SystemException $e)
		{
			ShowError($e->getMessage());
		}
	}

	protected function checkModules()
	{

		if (!Loader::includeModule('iblock'))

			throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	}

	// обработка массива $arParams (метод подключается автоматически)
	public function onPrepareComponentParams($arParams)
	{
		if (!isset($arParams['CACHE_TIME']))
		{
			$arParams['CACHE_TIME'] = 3600;
		} else
		{
			$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
		}
		return $arParams;
	}

	/**
	 * Получение разделов
	 *
	 * @return array
	 */
	protected function getSections()
	{
		$sections = [];
		$section = \Bitrix\Iblock\SectionTable::getList([
			'filter' => ['IBLOCK_ID' => $this->arParams['IBLOCK_ID']],
			'select' => ['NAME', 'CODE', 'ID'],
		]);
		while ($arItem = $section->fetch())
		{
			$arItem["URL_CHPU"] = $this->arParams["URL_CHPU"];
			$arItem["SEF_FOLDER"] = $this->arParams["SEF_FOLDER"];
			$sections[$arItem['ID']] = $arItem;
		}
		return $sections;
	}

	protected function getSection()
	{
		$section = null;

		$section_code = $this->arParams['SECTION_CODE'];
		if ($section_code)
		{
			$this->arResult['SECTION_ID'] = CIBlockFindTools::GetSectionID(false, $section_code, []);
			$iblockId = $this->arParams['IBLOCK_ID'];
			$section = \Bitrix\Iblock\SectionTable::getByPrimary($this->arResult['SECTION_ID'], [
				'filter' => ['IBLOCK_ID' => $iblockId],
				'select' => ['CODE'],
			])->fetch();

		}

		return $section;
	}

	/**
	 * Получение элементов
	 *
	 * @param array $section
	 * @return array
	 */
	protected function getElements($section = null)
	{
		$nav = new \Bitrix\Main\UI\PageNavigation("nav-more-news");
		$nav->allowAllRecords(true)
			->setPageSize($this->arParams['NEWS_COUNT'])
			->initFromUri();
		\Bitrix\Main\Loader::includeModule('iblock');

		foreach ($this->filterData as $idField => $valueField)
		{
			$filterFields[$idField] = $valueField;
		}

		$filter = ['IBLOCK_ID' => $this->arParams['IBLOCK_ID']];

		if ($filterFields['FIND'])
		{
			$filter[] = [
				"LOGIC" => "OR",
				"NAME" => "%" . $filterFields['FIND'] . "%",
				"PREVIEW_TEXT" => "%" . $filterFields['FIND'] . "%",
				"DETAIL_TEXT" => "%" . $filterFields['FIND'] . "%",
			];
		}
		if ($filterFields["date_from"] && $filterFields["date_to"])
		{
			$filter[] =
				['>=DATE_CREATE' => $filterFields["date_from"],
				 '<=DATE_CREATE' => $filterFields["date_to"]
				];
		}

		if (!empty($section))
		{
			$filter['SECTIONS.CODE'] = $section['CODE'];
		}
		$filter['=ACTIVE'] = 'Y';
		$filter[] = [
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
		$filter['IN_SECTIONS']='Y';
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
			->setOrder([ 'SORT_ACTIVE_FROM'=>'DESC'])
			->setFilter($filter)
			->setSelect(
				array('NAME',
					  'CODE',
					  'ID',
					  'ACTIVE_FROM',
					  "PREVIEW_TEXT",
					  "PREVIEW_PICTURE",
					  'DATE_CREATE',
					  'SHOW_COUNTER',
					  'SECTIONS',
				))
			->setOffset($nav->getOffset())
			->setLimit($nav->getLimit());

		$elements = \Bitrix\Main\ORM\Query\QueryHelper::decompose($query, false);

		$nav->setRecordCount($query->queryCountTotal());

		$dateFormat = CSite::GetDateFormat();
		foreach ($elements as $element)
		{
			$id = $element->getId();
			$elementsItem[$id]["ID"] = $id;
			$elementsItem[$id]["NAME"] = $element->getName();
			$elementsItem[$id]["CODE"] = $element->getCode();
			$elementsItem[$id]["PREVIEW_TEXT"] = $element->getPreviewText();
			$elementsItem[$id]["PREVIEW_PICTURE"] = $element->getPreviewPicture();
			$elementsItem[$id]["SHOW_COUNTER"] = $element->getShowCounter();
			$datePublish = $element->Get('ACTIVE_FROM') ?? $element->Get('DATE_CREATE');
			$date = CIBlockFormatProperties::DateFormat(
				'd.m.Y',
				MakeTimeStamp($datePublish,
					$dateFormat)
			);
			$elementsItem[$id]["DATE_CREATE"] = $date;
			$elementsItem[$id]["URL_CHPU"] = $this->arParams["URL_CHPU"];
			$elementsItem[$id]["SEF_FOLDER"] = $this->arParams["SEF_FOLDER"];
			foreach ($element->getSections()->getAll() as $section)
			{
				$elementsItem[$id]['SECTIONS_ELEMENT'][] = $section->Get('ID');
			}
		}
		return ['ELEMENTS' => $elementsItem, 'NAVIGATION_ELEMENTS' => $nav];
	}

	/**
	 * Подготовка массива $arResult
	 *
	 * @return void
	 */
	protected function getResult()
	{
		$filterOption = new Bitrix\Main\UI\Filter\Options('NEWS_FILTER');
		$this->filterData = $filterOption->getFilter([]);
		$cacheId = md5(serialize($this->filterData));
		$this->arResult['SEF_FOLDER'] =$this->arParams["SEF_FOLDER"];
		// если нет валидного кеша, получаем данные из БД
		if ($this->startResultCache(false, $cacheId))
		{
			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->RegisterTag("iblock_id_{$this->arParams["IBLOCK_ID"]}");
			}
			$this->arResult['SECTIONS'] = $this->getSections();
			$this->arResult['ELEMENTS_DATA'] = $this->getElements($this->getSection());

			foreach ($this->arResult['ELEMENTS_DATA']['ELEMENTS'] as &$element)
			{
				foreach ($element['SECTIONS_ELEMENT'] as $section)
				{
					$element['SECTION_DATA'][] = [
						'SECTION_CODE' => $this->arResult['SECTIONS'][$section]['CODE'],
						'SECTION_NAME' => $this->arResult['SECTIONS'][$section]['NAME']
					];
				}
			}
			unset($element);
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
}
