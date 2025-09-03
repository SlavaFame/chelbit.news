<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock'))
{
	return;
}

use Bitrix\Iblock\Component\Tools,
	Bitrix\Main\Loader,
	\Bitrix\Main\Application;

class NewsMain extends CBitrixComponent
{
	// Переменные, которые нужно получить из GET параметров
	protected array $arComponentVariables = [
		'CODE_SECTION'
	];

	public function executeComponent()
	{
		// Подключение модуля «Информационные блоки»
		Loader::includeModule('iblock');
		// Режим поддержки ЧПУ
		if ($this->arParams["SEF_MODE"] === "Y")
		{
			$componentPage = $this->sefMode();
		}
		// Обычный режим
		if ($this->arParams["SEF_MODE"] != "Y")
		{
			$componentPage = $this->noSefMode();
		}
		// Обработка 404 ошибки
		if (!$componentPage)
		{
			Tools::process404(
				$this->arParams["MESSAGE_404"],
				($this->arParams["SET_STATUS_404"] === "Y"),
				($this->arParams["SET_STATUS_404"] === "Y"),
				($this->arParams["SHOW_404"] === "Y"),
				$this->arParams["FILE_404"]
			);
		}
		$iblockId = \Bitrix\Iblock\IblockTable::getList(
			[
				'filter' => ['CODE' => 'news', 'TYPE.ID' => 'first_bit'],
				'select' => ['ID']
			]
		)->fetch();
		$this->arResult['IBLOCK_ID'] = $iblockId['ID'];
		$this->IncludeComponentTemplate($componentPage);
	}

	/**
	 * Метод обработки режима ЧПУ
	 * Обрабатывает URL-шаблоны и возвращает страницу компонента для режима ЧПУ.
	 *
	 * @return string
	 */
	protected function sefMode()
	{
		$arDefaultVariableAliases404 = [];
		$arDefaultUrlTemplates404 = [
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
		];
		$arVariables = [];
		$engine = new CComponentEngine($this);
		$arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
			$arDefaultUrlTemplates404,
			$this->arParams["SEF_URL_TEMPLATES"]
		);
		$arVariableAliases = CComponentEngine::makeComponentVariableAliases(
			$arDefaultVariableAliases404,
			$this->arParams["VARIABLE_ALIASES"]
		);
		$componentPage = $engine->guessComponentPath(
			$this->arParams["SEF_FOLDER"],
			$arUrlTemplates,
			$arVariables
		);
		if ($componentPage == FALSE)
		{
			$componentPage = 'main';
		}
		CComponentEngine::initComponentVariables(
			$componentPage,
			$this->arComponentVariables,
			$arVariableAliases,
			$arVariables
		);
		$this->arResult = [
			"VARIABLES" => $arVariables,
			"ALIASES" => $arVariableAliases
		];
		return $componentPage;
	}

	/**
	 * Метод обработки обычного режима (не ЧПУ)
	 * Обрабатывает параметры запроса и возвращает страницу компонента для обычного режима.
	 *
	 * @return string
	 */
	protected function noSefMode()
	{
		$componentPage = "";
		$arDefaultVariableAliases = [];
		$arVariableAliases = CComponentEngine::makeComponentVariableAliases(
			$arDefaultVariableAliases,
			$this->arParams["VARIABLE_ALIASES"]
		);
		$arVariables = [];

		CComponentEngine::initComponentVariables(
			false,
			$this->arComponentVariables,
			$arVariableAliases,
			$arVariables
		);


		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();
		$rDir = $request->getRequestedPageDirectory();
		if ($arVariableAliases["CATALOG_URL"] == $rDir)
		{
			$componentPage = "main";
		}
		if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> ''))
		{
			$componentPage = "detail";
		}
		if ((isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) || (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> ''))
		{
			$componentPage = "section";
		}
		$this->arResult = [
			"VARIABLES" => $arVariables,
			"ALIASES" => $arVariableAliases
		];
		return $componentPage;
	}
}
