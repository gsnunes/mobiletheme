$(function () {

	var slider = $('#highlight'),
		images = $('.slider-images img');


	function setLayoutDevice (elem) {
		var layout = elem.attr('data-layout'),
			device = elem.attr('data-device');

		slider.removeClass('landscape portrait iphone ipad');
		slider.addClass(layout);
		slider.addClass(device);
	}


	// load first image
	setLayoutDevice($('.slider-images li:first'));


	//TODO: Firefox not set the after reload
	setTimeout(function () {
		$('.slider-images li:first').show();
	}, 1000);
	


	// bind events
	$('.next').on('click', function () { changeImage(1); });
	$('.previous').on('click', function () { changeImage(-1); });


	function changeImage (direction) {
		var index = $('.slider-images li:visible').index(),
			imageIndex = index + direction,
			image;

		if (direction > 0) {
			if (imageIndex < images.length) {
				image = $('.slider-images li').eq(imageIndex);
			}
			else {
				image = $('.slider-images li').eq(0);
			}
		}
		else {
			if (imageIndex >= 0) {
				image = $('.slider-images li').eq(imageIndex);
			}
			else {
				image = $('.slider-images li').eq((images.length - 1));
			}
		}

		$('.slider-images li').hide();
		setLayoutDevice(image);
		image.show();
	}

});