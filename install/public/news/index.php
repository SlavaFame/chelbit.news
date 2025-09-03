<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?><?$APPLICATION->IncludeComponent(
	"chelbit:news",
	"",
	Array(
		"NEWS_COUNT" => "3",
		"SEF_FOLDER" => "/news/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("element"=>"#SECTION_CODE#/#ELEMENT_CODE#/","section"=>"#SECTION_CODE#/")
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>