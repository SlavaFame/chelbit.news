BX.ready(function () {
	const swiperMedia = new ChelBit.Ui.Swiper('.swiper-media', {
		speed: 500,
		modules: [ChelBit.Ui.Swiper.Navigation, ChelBit.Ui.Swiper.Pagination],
		navigation: {
			nextEl: '.swiper-button-next-media',
			prevEl: '.swiper-button-prev-media',
		},
		pagination: {
			el: '.swiper-pagination-media',
			type: 'fraction',
		},
		slidesPerView: 1,
	});

	const swiperDocument = new ChelBit.Ui.Swiper('.swiper-document', {
		speed: 500,
		modules: [ChelBit.Ui.Swiper.Navigation, ChelBit.Ui.Swiper.Pagination],
		navigation: {
			nextEl: '.swiper-button-next-document',
			prevEl: '.swiper-button-prev-document',
		},
		pagination: {
			el: '.swiper-pagination-document',
			type: 'fraction',
		},
		slidesPerView: 1,
	});
	window.BXDEBUG = true;
	let oPopup = new BX.PopupWindow('call_feedback', window.body, {
		autoHide: true,
		offsetTop: 1,
		offsetLeft: 0,
		lightShadow: true,
		closeIcon: true,
		closeByEsc: true,
		overlay: {
			backgroundColor: 'black', opacity: '80'
		}
	});
	oPopup.setContent(BX('hideBlock'));

	// Обработчик клика для элементов с тегом img
	BX.bindDelegate(
		document.body, 'click', {tagName: 'img'},
		BX.proxy(function (e) {
			if (!e)
				e = window.event;

			// Получение кликнутого элемента и src из data-атрибута
			let clickedElement = e.target;
			if (clickedElement && clickedElement.tagName.toLowerCase() === 'img') {
				if (clickedElement.closest('.wrapper__image-document-comp-news')) {
					return;
				}
				let hideBlock = BX('hideBlock');
				hideBlock.innerHTML = '';
				let newImage = clickedElement.cloneNode();
				newImage.src = clickedElement.dataset.src || clickedElement.src; // Заменяем src на значение из data-атрибута или текущий src
				hideBlock.appendChild(newImage);
				oPopup.show();
			}

			return BX.PreventDefault(e);
		}, oPopup)
	);
});

