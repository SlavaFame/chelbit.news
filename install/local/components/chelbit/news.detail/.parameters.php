<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

// настройки компонента, формируем массив $arParams
$arComponentParameters = array(
    // основной массив с параметрами
    'PARAMETERS' => array(
        // настройки кэширования
        'CACHE_TIME' => array(
            'DEFAULT' => 3600
        ),
    ),
);
