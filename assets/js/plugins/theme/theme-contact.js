$(document).ready(function () {
	$('#contact-form').submit(function (ev) {
		ev.preventDefault();

		var data = {
			action: 'my_action',
			data: $(this).serialize()
		};

		jQuery.post($(this).attr('action'), data, function (response) {
			var div = $('#contact-form .response');

			div.html('');

			response = jQuery.parseJSON(response);

			if (response.success) {
				div.append(response.success);
				$('#contact-form :input').val('');
			}

			if (response.error) {
				div.append(response.error);
			}
		});
	});
});