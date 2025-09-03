<?
namespace ChelBit\News\Events;
use ChelBit\Main\Installer\Core\ModuleEventHandler;
class ChelbitNews extends ModuleEventHandler {

	protected string $targetModuleId = 'chelbit.main';

	static function OnAfterIBlockElementAdd(&$arMenu) {
		\Bitrix\Main\Diag\Debug::dumpToFile('OnAfterB24MenuBuild Event');
	}

    public static function OnConfigureMainPageComponents(\Bitrix\Main\Event $event)
    {
        $results = $event->getResults() ?: null;
        $components = $results ? end($results)->getParameters() : $event->getParameters();

        $components[] = [
            'NAME' => 'Виджет новостей',
            'COMPONENT' => 'chelbit:news.widget',
            'TEMPLATE' => '',
            'PARAMETERS' => [
                "NEWS_COUNT" => 3,
                "URL_COMPONENT_NEWS" => '/news/',
            ],
        ];

        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS,
            $components,
        );
    }

}
