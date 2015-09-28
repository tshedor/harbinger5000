jQuery(document).ready(function ($) {

	if ($('.gallery').length) {

		$(".gallery").magnificPopup({
			mainClass: "mfp-zoom-in",
			removalDelay: 300,
			delegate: "a",
			type: "image",
			tLoading: "Loading image %curr%...",
			gallery: {
				enabled:true,
				navigateByImgClick:true,
				preload: [0, 1]
			},
			image: {
				tError: '<a href="%url%">The image %curr%</a> could not be loaded.',
			}
		});

	} else {

		$('.entry a[href*=".jpg"], .entry a[href*=".png"], .entry a[href*=".jpeg"]').magnificPopup({
			type: "image",
			closeOnContentClick:true,
			mainClass: "mfp-zoom-in",
			removalDelay: 300,
			image: {
				tError: '<a href="%url%">The image %curr%</a> could not be loaded.',
			}
		});

	}
});