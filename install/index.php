<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use ChelBit\Main\Installer\Core\CustomInstaller;
use ChelBit\Main\Installer\Core\InstallerException;
use ChelBit\Main\Installer\Core\EventsInstaller;
use ChelBit\Main\Installer\Core\InstallMode;
use ChelBit\Main\Installer\Core\UrlRewriteInstaller;
use ChelBit\Main\Installer\File\FileCopyInstaller;
use ChelBit\Main\Installer\File\FileSymlinkInstaller;
use ChelBit\Main\Installer\Iblock\IblockInstaller;
use ChelBit\Main\Installer\Menu\MenuInstaller;

class_exists('\ChelBit\Main\Module\ModuleInstaller') ?: include($_SERVER["DOCUMENT_ROOT"].'/local/modules/chelbit.main/lib/module/moduleinstaller.php');
class_exists('\ChelBit\Main\Module\ModuleInstaller') ?: include($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/chelbit.main/lib/module/moduleinstaller.php');

if(!class_exists('\ChelBit\Main\Module\ModuleInstaller'))
    return;

class ChelBit_News extends \ChelBit\Main\Module\ModuleInstaller
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
	public $MODULE_NAME = "Первый БИТ. Новости";
	public $MODULE_DESCRIPTION = "";
	public $PARTNER_NAME = "«Первый Бит» (Челябинск)";
	public $PARTNER_URI = "https://chelyabinsk.1cbit.ru/";

	protected string $vendor = 'chelbit';
	protected string $module = 'news';
    protected string $moduleVersionPath = __DIR__.'/version.php';


	/**
	 * @throws InstallerException
	 */
	protected function initInstallers(): void
	{
		$this->installers = [
			new FileSymlinkInstaller(__DIR__ . '/local', $_SERVER["DOCUMENT_ROOT"] . '/local'),
			new FileCopyInstaller(__DIR__ . '/public', $_SERVER["DOCUMENT_ROOT"]),
			new EventsInstaller($this->MODULE_ID, 'ChelBit\\' . ucfirst($this->module), __DIR__ . '/../lib/events'),
			(new IblockInstaller(__DIR__ . '/iblock/news_complex.xml', 'first_bit', 'news', 's1'))
				->then(function () {
					/** @var \ChelBit\Main\Installer\Core\BaseInstaller $this */

					$results = $this->getInstallResults();
					$iblockId = $results[0];
					if (is_int($iblockId) && InstallMode::isInstallMethod($this->getInstallMethod()))
					{
						$group = new CGroup();
						$arFields = [
							"ACTIVE" => "Y",
							"C_SORT" => 100,
							"NAME" => "Администратор новостей",
							"DESCRIPTION" => "",
							"STRING_ID" => "news_admin"
						];
						$groupId = $group->Add($arFields);
						if (is_numeric($groupId) && strlen($group->LAST_ERROR) <= 0)
						{
							$defaultIblockGroupRights = CIBlock::GetGroupPermissions($iblockId);
							$defaultIblockGroupRights[$groupId] = "W";
							CIBlock::SetPermission($iblockId, $defaultIblockGroupRights);
						}
					}
				}),
			new MenuInstaller([
				[
					"ID" => "news-item",
					"TEXT" => "Новости",
					"LINK" => "/news/",
				],
			], 's1'),
            new CustomInstaller(function() {
                $currentDate = date("d.m.Y H:i");
                CAgent::AddAgent(
                    "ChelBit\News\Events\Notify::notifyNewsAgent();",
                    "chelbit.news",
                    "N",
                    60,
                    $currentDate
                );
            }),
		];


		$urlRewrite = [];
		include(__DIR__ . '/urlrewrite.php');

		if (!empty($urlRewrite))
			$this->installers[] = new UrlRewriteInstaller($urlRewrite);
	}
}