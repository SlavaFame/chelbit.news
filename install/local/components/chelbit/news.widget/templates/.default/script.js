BX.ready(function () {
	const swiper = new ChelBit.Ui.Swiper('.comp-news', {
		slidesPerView: 3,
		slidesPerGroup: 3,
		freeMode: true,
		speed: 500,
		spaceBetween: 20,
		autoplay: {
			delay: 3000,
			stopOnLastSlide: false,
			disableOnInteraction: true,
		},
		breakpoints: {
			1800: {
				slidesPerView: 3
			},
			1600: {
				slidesPerView: 2,
				slidesPerGroup: 2,
			},
			470: {
				slidesPerView: 1,
				slidesPerGroup: 1,
			}
		},
		modules: [ChelBit.Ui.Swiper.Autoplay],
	});
});