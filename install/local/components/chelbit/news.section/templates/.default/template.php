<?
use Bitrix\Main\Localization\Loc;
use \ChelBit\Main\ResourceManager;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

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
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$nav = $arResult['ELEMENTS_DATA']['NAVIGATION_ELEMENTS'];
if(!empty($arResult['ELEMENTS_DATA']['ELEMENTS'])):
?>
	<div class="content__news-comp-news">
		<div class="aside__tags-comp-news">
			<?php foreach ($arResult['SECTIONS'] as $key => $section) : ?>
				<div class="wrapper__tag-comp-news">
					<a href="<?= $section["SEF_FOLDER"] . $section["CODE"] . "/"; ?>">
						<div class="inner__tag-comp-news"><?= $section['NAME']; ?></div>
					</a>
				</div>
				<?php if (array_key_last($arResult['SECTIONS']) == $key): ?>
					<div class="wrapper__tag-comp-news">
						<a href="<?= $section["SEF_FOLDER"] ?>">
							<div class="inner__tag-comp-news"><?= Loc::getMessage('BIT_NEWS_SECTION_ALL_NEWS') ?></div>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="container__news-comp-news">
			<?php foreach ($arResult['ELEMENTS_DATA']['ELEMENTS'] as $element): ?>
				<div class="wrapper__item-comp-news">
					<div class="wrapper__picture-comp-news">
						<a href="<?= $element["SEF_FOLDER"] . $element['SECTION_DATA'][0]['SECTION_CODE'] . "/" . $element["CODE"] . "/"; ?>">
							<div class="inner__picture-comp-news">
								<?php
								$dataImage = CFile::GetFileArray($element['PREVIEW_PICTURE']);
								$resImage = CFile::ResizeImageGet(
									$dataImage,
									array("width" => 400, "height" => 200),
									BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
									false,
									false,
									false,
									100
								);
								$src = $image['src'] ?? $dataImage['SRC'] ?? $templateFolder . '/images-template/no-photo.jpg';
								?>
								<img src="<?= $src ?>" alt="news_picture">
							</div>
						</a>
					</div>
					<div class="wrapper__description-comp-news">
						<a href="<?= $element["SEF_FOLDER"] . $element['SECTION_DATA'][0]['SECTION_CODE'] . "/" . $element["CODE"] . "/"; ?>">
							<div class="inner__description-comp-news">
								<div class="title__news-header-comp-news"><?= $element['NAME'] ?></div>
								<div class="wrapper__date-comp-news">
									<div class="block__date-comp-news">
										<div class="text__date-comp-news"><?= $element['DATE_CREATE'] ?></div>
									</div>
									<div class="wrapper__counter-comp-news">
										<div class="image__counter-comp-news">
											<svg width="14" height="10" viewBox="0 0 14 10" fill="none"
											     xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd"
												      d="M7.10995 0.797682C7.13879 0.798062 7.16762 0.798591 7.19641 0.799269C7.23926 0.797678 7.28231 0.796875 7.32555 0.796875C7.5279 0.796875 7.72614 0.814455 7.91883 0.848171C8.91994 0.960822 9.88331 1.25891 10.7908 1.7385C11.9178 2.33413 12.9203 3.20397 13.6936 4.25347C14.1021 4.80791 14.102 5.57649 13.6936 6.13094C12.9203 7.18047 11.9178 8.05028 10.7908 8.64591C9.64123 9.25344 8.40194 9.56968 7.10995 9.58673C7.06373 9.5878 6.93617 9.5878 6.88996 9.58673C5.59794 9.56971 4.35868 9.25343 3.20917 8.64591C2.08216 8.05028 1.07963 7.18044 0.306381 6.13095C-0.102126 5.57652 -0.102126 4.80789 0.306377 4.25347C1.07966 3.20395 2.08216 2.33413 3.20917 1.7385C4.35869 1.13097 5.59798 0.814727 6.88996 0.797682C6.93617 0.796606 7.06373 0.796606 7.10995 0.797682ZM3.66556 2.60206C3.95843 2.44728 4.25748 2.31407 4.56212 2.20255C4.15011 2.76721 3.90696 3.46294 3.90696 4.21547C3.90696 6.1035 5.43752 7.63406 7.32555 7.63406C9.21359 7.63406 10.7441 6.1035 10.7441 4.21547C10.7441 3.63373 10.5988 3.08594 10.3425 2.60639C11.3326 3.1313 12.2206 3.9009 12.9072 4.83285C13.0618 5.04271 13.0619 5.34161 12.9072 5.55157C12.2186 6.48611 11.3277 7.25735 10.3344 7.78235C9.3203 8.31829 8.23203 8.59545 7.09437 8.61004C7.03409 8.61179 6.97371 8.61062 6.91342 8.61021C5.78096 8.59567 4.66692 8.31158 3.66556 7.78235C2.67218 7.25735 1.78127 6.48608 1.09272 5.55156C0.93808 5.34168 0.938085 5.04272 1.09273 4.83284C1.7813 3.89829 2.67218 3.12706 3.66556 2.60206ZM7.3499 5.71573C8.17005 5.71573 8.83492 5.05086 8.83492 4.23071C8.83492 3.41055 8.17005 2.74568 7.3499 2.74568C6.52974 2.74568 5.86488 3.41055 5.86488 4.23071C5.86488 5.05086 6.52974 5.71573 7.3499 5.71573Z"
												      fill="#7E8284"/>
											</svg>
										</div>
										<div class="text__counter-comp-news">
											<?= $element['SHOW_COUNTER'] ?>
										</div>
									</div>
								</div>
								<div class="text__news-header-comp-news"><?= $element['PREVIEW_TEXT'] ?></div>
							</div>
						</a>
					</div>
					<div class="aside__additional-property-element-comp-news">
						<div class="aside__tags_without-padding-comp-news">
							<?php foreach ($element['SECTION_DATA'] as $sectionData): ?>
								<div class="wrapper__tag_reverse-margin-comp-news">
									<a href="<?= $element["SEF_FOLDER"] . $sectionData['SECTION_CODE'] . "/"; ?>">
										<div class="inner__tag-comp-news"><?= $sectionData['SECTION_NAME']; ?></div>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
						</a>
						<div class="block__filler-comp-news">
							<a href="<?= $element["SEF_FOLDER"] . $element['SECTION_DATA'][0]['SECTION_CODE'] . "/" . $element["CODE"] . "/"; ?>">
							</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?
$APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation",
	"",
	array(
		"NAV_OBJECT" => $nav,
		"SEF_MODE" => "N",
		"SHOW_ALWAYS" => "Y",
	),
	false
);
else:
echo ResourceManager::formPlug();
endif;?>
