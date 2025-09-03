<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException,
	Bitrix\Main\Loader;


class NewsDetail extends CBitrixComponent
{
	public function executeComponent()
	{
		try
		{
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

	/**
	 * Подготовка параметров компонента
	 *
	 * @param array $arParams
	 * @return array
	 */
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
	 * Получение элемента из инфоблока
	 *
	 * @return array
	 */
	protected function getElement()
	{
		$dataElement = \Bitrix\Iblock\Elements\ElementNewsTable::getList([
			'select' => [
				"ID",
				"NAME",
				"DETAIL_TEXT",
				'SECTIONS',
				"CODE",
				'DATE_CREATE',
				'MASS_MEDIA_ABOUT_US' => 'MASS_MEDIA_ABOUT_US.ELEMENT',
				'GALLERY_' => 'GALLERY.FILE',
				'DOCUMENTS_' => 'DOCUMENTS.FILE',
			],
			'filter' => [
				'LOGIC' => 'OR',
				[
					'=ID' => $this->arParams['ID']
				],
				[
					'=CODE' => $this->arParams['ELEMENT_CODE']
				],
			],
		])->fetchObject();
		$element['ID'] = $dataElement->Get('ID');
		$element['NAME'] = $dataElement->Get('NAME');
		$element['URL_CHPU'] = $this->arParams["URL_CHPU"];
		$element['SEF_FOLDER'] = $this->arParams["SEF_FOLDER"];
		$element['SECTION_CODE'] = $this->arParams["SECTION_CODE"];
		$element['DETAIL_TEXT'] = $dataElement->Get("DETAIL_TEXT");
		// Получение разделов элемента
		foreach ($dataElement->getSections()->getAll() as $section)
		{
			$id = $section->getId();
			$element['SECTIONS'][$id]['ID'] = $id;
			$element['SECTIONS'][$id]['NAME'] = $section->getName();
			$element['SECTIONS'][$id]['CODE'] = $section->getCode();
			$element['SECTIONS'][$id]['SEF_FOLDER'] = $this->arParams["SEF_FOLDER"];
		}
		// Получение галереи элемента
		foreach ($dataElement->getGallery()->getAll() as $gallery)
		{
			$type = $gallery->getFile()->getContentType();
			if (stristr($type, '/', true) == 'image')
			{
				$element['GALLERY_ELEMENT']['IMAGE'][$gallery->getId()]['ID'] = $gallery->getFile()->getId();
			} elseif (stristr($type, '/', true) == 'video')
			{
				$element['GALLERY_ELEMENT']['VIDEO'][$gallery->getId()]['ID'] = $gallery->getFile()->getId();
			}
		}
		// Получение документов элемента
		foreach ($dataElement->getDocuments()->getAll() as $document)
		{
			$element['DOCUMENTS_ELEMENT'][$document->getId()]['ID'] = $document->getFile()->getId();
			$element['DOCUMENTS_ELEMENT'][$document->getId()]['FILE_NAME'] = $document->getFile()->getFileName();
		}
		// Получение рекомендованных новостей
		foreach ($dataElement->Get('MASS_MEDIA_ABOUT_US')->getAll() as $recommendedNews)
		{
			$id = $recommendedNews->getElement()->getId();
			$element['RECOMMENDED'][$id]['ID'] = $id;
			$element['RECOMMENDED'][$id]['NAME'] = $recommendedNews->getElement()->getName();
			$element['RECOMMENDED'][$id]['PREVIEW'] = $recommendedNews->getElement()->getPreviewPicture();
			$element['RECOMMENDED'][$id]['CODE'] = $recommendedNews->getElement()->getCode();
			$element['RECOMMENDED'][$id]['SECTION'] = CIBlockSection::GetByID($recommendedNews->getElement()->getIblock_section_id())->fetch();
			$datePublish = $recommendedNews->getElement()->getActiveFrom() ?? $recommendedNews->getElement()->getDateCreate();
			$date = CIBlockFormatProperties::DateFormat(
				'j F Y',
				MakeTimeStamp($datePublish,
					CSite::GetDateFormat())
			);
			$element['RECOMMENDED'][$id]['DATE_PUBLISH'] = $date;
		}
		$date = CIBlockFormatProperties::DateFormat(
			'j F Y',
			MakeTimeStamp($dataElement->Get("DATE_CREATE"),
				CSite::GetDateFormat())
		);
		$element['DATE_CREATE'] = $date;
		return $element;
	}

	/**
	 * Подготовка массива $arResult
	 *
	 * @return void
	 */
	protected function getResult()
	{
		if ($this->startResultCache())
		{
			if (defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->RegisterTag("iblock_id_{$this->arParams["IBLOCK_ID"]}");
			}
			$this->arResult['ELEMENT'] = $this->getElement();
			if (!empty($this->arResult))
			{
				$this->IncludeComponentTemplate();
			} else
			{
				$this->AbortResultCache();
				\Bitrix\Iblock\Component\Tools::process404(
					Loc::getMessage('BIT_NEWS_DETAIL_ELEMENT_NOT_FOUND'),
					true,
					true
				);
			}
		}
		//Счетчик посещения страницы
		CIBlockElement::CounterInc($this->arResult['ELEMENT']['ID']);
	}
}
