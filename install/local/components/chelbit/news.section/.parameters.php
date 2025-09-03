<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}
$arComponentParameters = [
	'PARAMETERS' => [
		'CACHE_TIME' => [
            'DEFAULT' => 3600
		],
	],
];
