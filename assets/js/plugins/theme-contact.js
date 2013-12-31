$(document).ready(function () {
	$('#contact-form').submit(function (ev) {
		ev.preventDefault();

		var data = {
			action: 'my_action'
		};

        jQuery.post($(this).attr('action'), data, function (response) {
            console.log(response);
        });
	});
});