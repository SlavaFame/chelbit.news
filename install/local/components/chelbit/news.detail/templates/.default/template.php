<?php use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
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
/** @global $BX_RESIZE_IMAGE_PROPORTIONAL */
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$nav = $arResult['ELEMENTS_DATA']['NAVIGATION_ELEMENTS'];
?>

<div id="hideBlock" style="display:none;">
	<!-- Здесь будет отображаться изображение по клику -->
</div>

<div class="page__news-detail-comp-news">
	<div class="wrapper__list-news-comp-news">
		<a href="<?= $arResult['ELEMENT']['SEF_FOLDER'] ?>"><?= Loc::getMessage('BIT_NEWS_DETAIL_LIST_NEWS') ?></a>
	</div>
	<div class="aside__tags-comp-news">
		<?php foreach ($arResult['ELEMENT']['SECTIONS'] as $key => $section) : ?>
			<div class="wrapper__tag-comp-news">
				<a href="<?= $section["SEF_FOLDER"] . $section['CODE'] . "/"; ?>">
					<div class="inner__tag-comp-news"><?= $section['NAME']; ?></div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="section__detail-text-comp-news">
		<?= $arResult['ELEMENT']['DETAIL_TEXT']; ?>
	</div>
	<?php if (!empty($arResult['ELEMENT']['GALLERY_ELEMENT'])): ?>
		<div class="aside__media-content">
			<div>МЕДИАКОНТЕНТ</div>
			<div class="container__media-slider-comp-news">
				<div class="swiper swiper-media">
					<div class="swiper-wrapper">
						<?php foreach ($arResult['ELEMENT']['GALLERY_ELEMENT']['VIDEO'] as $mediaFile): ?>
							<div class="swiper-slide">
								<div class="inner__item-media-comp-news">
									<div class="box__media-description-comp-news">
										<div class="wrapper__video-media-comp-news">
											<?php $video = CFile::GetFileArray($mediaFile['ID']); ?>
											<video src="<?= $video['SRC'] ?>" controls></video>
										</div>
									</div>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
						<?php foreach ($arResult['ELEMENT']['GALLERY_ELEMENT']['IMAGE'] as $mediaFile): ?>
							<div class="swiper-slide">
								<div class="inner__item-media-comp-news">
									<div class="box__media-description-comp-news">
										<div class="wrapper__image-media-comp-news">
											<?php
											$dataImage = CFile::GetFileArray($mediaFile['ID']);
											$image = CFile::ResizeImageGet(
												$dataImage,
												['width' => 400, 'height' => 400],
												BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
												false,
												false,
												false,
												100
											);
											$src = $image['src'] ?? $dataImage['SRC'];
											?>
											<img src="<?= $src ?>" data-src="<?= $dataImage['SRC'] ?>" alt="image"/>
										</div>
									</div>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="container__navigation-comp-news">
					<div class="wrapper__navigation-comp-news">
						<button class="swiper-button-prev-media">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M14.1245 7.64893L13.6248 8.27362L10.0246 12.7738L13.6248 17.2741L14.1245 17.8988L12.8751 18.8983L12.3754 18.2736L8.37539 13.2736L7.97559 12.7738L8.37539 12.2741L12.3754 7.27411L12.8751 6.64941L14.1245 7.64893Z"
								      fill="#313538"/>
							</svg>
						</button>
						<div class="swiper-pagination-media"></div>
						<button class="swiper-button-next-media">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="currentColor"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M9.87549 7.64893L10.3752 8.27362L13.9754 12.7738L10.3752 17.2741L9.87549 17.8988L11.1249 18.8983L11.6246 18.2736L15.6246 13.2736L16.0244 12.7738L15.6246 12.2741L11.6246 7.27411L11.1249 6.64941L9.87549 7.64893Z"
								      />
							</svg>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if (!empty($arResult['ELEMENT']['DOCUMENTS_ELEMENT'])): ?>
		<div class="aside__documents">
			<div>ПРИЛОЖЕННЫЕ ФАЙЛЫ</div>
			<div class="container__document-slider-comp-news">
				<div class="swiper swiper-document">
					<div class="swiper-wrapper">
						<?php foreach ($arResult['ELEMENT']['DOCUMENTS_ELEMENT'] as $document): ?>
							<div class="swiper-slide">
								<div class="inner__item-document-comp-news">
									<div class="box__document-description-comp-news">
										<div class="wrapper__document-comp-news">
											<?php $documentPath = CFile::GetFileArray($document['ID']); ?>
											<a href="<?= $documentPath['SRC'] ?>"
											   download>
												<div class="wrapper__image-document-comp-news">
													<img src="<?= $templateFolder . '/img/icons8-document.svg' ?>"
													     alt=""/>
												</div>
												<div class="title__comp-news-document">
													<?= $document['FILE_NAME'] ?>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="container__navigation-comp-news">
					<div class="wrapper__navigation-comp-news">
						<button class="swiper-button-prev-document">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M14.1245 7.64893L13.6248 8.27362L10.0246 12.7738L13.6248 17.2741L14.1245 17.8988L12.8751 18.8983L12.3754 18.2736L8.37539 13.2736L7.97559 12.7738L8.37539 12.2741L12.3754 7.27411L12.8751 6.64941L14.1245 7.64893Z"
								      fill="#313538"/>
							</svg>
						</button>
						<div class="swiper-pagination-document"></div>
						<button class="swiper-button-next-document">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M9.87549 7.64893L10.3752 8.27362L13.9754 12.7738L10.3752 17.2741L9.87549 17.8988L11.1249 18.8983L11.6246 18.2736L15.6246 13.2736L16.0244 12.7738L15.6246 12.2741L11.6246 7.27411L11.1249 6.64941L9.87549 7.64893Z"
								      fill="#313538"/>
							</svg>
						</button>
					</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="aside__date-publish">
		<div class="text__date-comp-news">
			<?= $arResult['ELEMENT']['DATE_CREATE']; ?>
		</div>
		<div>
			<i class="fa fa-calendar-o"></i>
		</div>
	</div>
	<?php if (!empty($arResult['ELEMENT']['RECOMMENDED'])): ?>
		<div class="aside__suggested-news-comp-news">
			<div class="title__suggested-news-comp-news"><?= Loc::getMessage('BIT_NEWS_DETAIL_MASS_MEDIA_ABOUT_US ') ?></div>
			<div class="container__suggested-news-comp-news">
				<?php foreach ($arResult['ELEMENT']['RECOMMENDED'] as $news): ?>
					<a href="<?= $arResult['ELEMENT']['SEF_FOLDER'] . $news['SECTION']['CODE'] . '/' . $news['CODE'] . '/' ?>"
					   class="box__suggested-news-element-comp-news">
						<div class="wrapper__image-suggested-news-comp-news">

							<?php
							$dataImage = CFile::GetFileArray($news['PREVIEW']);
							$image = CFile::ResizeImageGet(
								$dataImage,
								['width' => 200, 'height' => 200],
								BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
								false,
								false,
								false,
								100
							);
							$src = $image['src'] ?? $dataImage['SRC'];
							?>
							<img src="<?= $image['src'] ?>" data-src="<?= $dataImage['SRC'] ?>" alt="image"/>
						</div>
						<div class="wrapper__title-suggested-news-comp-news">
							<?= $news['NAME'] ?>
						</div>
						<div class="wrapper__date-suggested-news-comp-news">
							<i class="fa fa-clock-o" style="color: #F09C63"></i> <?= $news['DATE_PUBLISH'] ?>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
