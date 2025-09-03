<?php

namespace ChelBit\News\Events;

use Bitrix\Iblock\ElementTable;
use Bitrix\Im\V2\Message;
use Bitrix\Main\Loader;
use Bitrix\Seo\Engine\Bitrix;
use CAgent;
use CGroup;
use CIBlock;
use CIMNotify;
use CUser;
use DateTime;

class Notify
{
	static protected $codeGroup = 'EMPLOYEES_s1';

	static function getEmployees()
	{
		$groupData = CGroup::GetList(
			$by = 'c_sort',
			$order = 'asc',
			['STRING_ID' => self::$codeGroup]
		);
		$group = $groupData->Fetch();

		if ($group)
		{
			// Получаем пользователей, принадлежащих к данной группе
			$rsUsers = CUser::GetList(
				$by = 'ID',
				$order = 'ASC',
				['GROUPS_ID' => $group['ID']],
				['FIELDS' => ['ID']]
			);

			// Массив для хранения ID пользователей
			$userIds = [];

			while ($user = $rsUsers->Fetch())
			{
				$userIds[] = $user['ID'];
			}
		} else
		{
			return '';
		}
		return $userIds;
	}

	private static function getUrlElement($iblockId, $elementId)
	{
		$urlElement = ElementTable::getList(
			[
				'filter' => ['IBLOCK_ID' => $iblockId, 'ID' => $elementId],
				'select' => ['IBLOCK_SECTION_ID', 'CODE', 'IBLOCK_ID', 'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL']
			]
		)->fetch();
		$href = CIBlock::ReplaceDetailUrl($urlElement ['DETAIL_PAGE_URL'], $urlElement, false, 'E');
		return $href;
	}
		static function notifyNewsAgent()
	{
		Loader::includeModule('iblock');
		Loader::includeModule('im');
		$currentDate = date("d.m.Y H:i");

		$iblockCode = 'news';
		$iblock = \CIBlock::GetList([], ['CODE' => $iblockCode])->Fetch();
		if (!$iblock)
		{
			return '';
		}
		$iblockId = $iblock['ID'];

		$elements = ElementTable::getList([
			'filter' => [
				'IBLOCK_ID' => $iblockId,
				'=ACTIVE' => 'Y',
				'ACTIVE_FROM' => $currentDate,
			],
			'select' => [
				'ID',
				'NAME',
				'CREATED_BY',
				'ACTIVE_FROM',
				'IBLOCK_SECTION_ID',
				'CODE',
				'IBLOCK_ID',
				'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'
			]
		]);

		$elementsData = [];
		while ($element = $elements->fetch())
		{
			$href = CIBlock::ReplaceDetailUrl($element ['DETAIL_PAGE_URL'], $element, false, 'E');;
			$elementsData[] = [
				'NAME' => $element['NAME'],
				'URL' => $href,
				'CREATED_BY' => $element['CREATED_BY'],
			];
		}
		$employeesID = self::getEmployees();
		foreach ($elementsData as $element)
		{
			// Отправка уведомлений сотрудникам
			foreach ($employeesID as $id)
			{
				$aMessageParams = [
					"TO_USER_ID" => $id,
					"FROM_USER_ID" => $element['CREATED_BY'],
					"NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
					"NOTIFY_MODULE" => "im",
					"NOTIFY_MESSAGE" => '[URL=' . $element['URL'] . ']Новая новость[/URL] ' . $element['NAME'],
				];
				\CIMNotify::Add($aMessageParams);
			}
		}
		return __METHOD__ . "();";
	}
}