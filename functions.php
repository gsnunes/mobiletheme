<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/custom.php');          // Custom functions


add_action('wp_ajax_nopriv_my_action', 'my_action_callback');
add_action('wp_ajax_my_action', 'my_action_callback');

function my_action_callback () {
	$params = array();
	parse_str($_POST['data'], $params);

	$name = trim($params['name']);
	$email = $params['email'];
	$message = $params['message'];

	$subject = get_bloginfo('name') . ' - Contact';
	$site_owners_email = get_settings('admin_email');

	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';


	if ($name == "") {
		$error['error'] .= "Please enter your name. <br />";
	}

	if (!preg_match($regex, $email)) {
		$error['error'] .= "Please enter a valid email address. <br />";
	}

	if ($message == "") {
		$error['error'] .= "Please leave a comment.";
	}


	if (!$error) {
		$mail = mail($site_owners_email, $subject, $message,
			"From: ".$name." <".$email.">rn"
			."Reply-To: ".$email."rn"
			."X-Mailer: PHP/" . phpversion());

		$success['success'] = "We've received your email. We'll be in touch with you as soon as possible!";
		echo json_encode($success);
	}
	else {
		echo json_encode($error);
	}

	die();
}



add_action('admin_enqueue_scripts', 'my_admin_scripts');

function my_admin_scripts () {
	wp_enqueue_style('farbtastic');
	wp_enqueue_script('farbtastic');
}



add_action("admin_menu", "setup_theme_admin_menus");

function setup_theme_admin_menus () {
	add_submenu_page('themes.php', 'Mobile Theme Setup', 'Mobile Theme', 'manage_options', 'front-page-elements', 'theme_front_page_settings');
}



function theme_front_page_settings () {
	// Check that the user is allowed to update options
	if (!current_user_can('manage_options')) {
		wp_die('You do not have sufficient permissions to access this page.');
	}

	$social_icons = array('twitter', 'vimeo', 'flickr', 'facebook', 'skype', 'youtube', 'googleplus');

	$social_icons_options = get_option("mobiletheme_social_icons");

	$color_options = get_option("mobiletheme_color");
	$color_options = empty($color_options) ? '#bd1f43' : $color_options;

	if (isset($_POST["mobiletheme_settings"])) {
		$social_icons_options = array();

		for ($i = 0; $i < sizeof($social_icons); $i++) {
			$field_name = 'social_' . $social_icons[$i];

			if (isset($_POST[$field_name])) {
				$social_icons_options[$social_icons[$i]] = esc_attr($_POST[$field_name]);
			}
			else {
				$social_icons_options[$social_icons[$i]] = '';
			}

			update_option("mobiletheme_social_icons", $social_icons_options);
		}


		$color_options = $_POST['color'];
		update_option("mobiletheme_color", esc_attr($color_options));

		require "lib/vendor/lessc.inc.php";
		$less = new lessc;

		$less->setVariables(array(
			"baseColor" => $color_options
		));

		$less->compileFile(get_template_directory() . "/assets/less/app.less", get_template_directory() . "/assets/css/main.min.css");


		?>
		<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>
		<?php
	}

	?>
	<div class="wrap">
		<h2>Mobile Theme Setup</h2>

		<form method="POST">
			<input type="hidden" name="mobiletheme_settings" />

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="num_elements">Social icons</label>
					</th>
					<td>
						<fieldset class="social-icons">
							<legend class="screen-reader-text"><span>Social settings</span></legend>

							<?php foreach ($social_icons_options as $key => $value) { ?>
							<label for="social_<?php echo $key ?>_check">
								<input name="social_<?php echo $key ?>_check" type="checkbox" id="social_<?php echo $key ?>_check" value="1" <?php echo (empty($value) ? '' : 'checked');?> >
								<input type="text" name="social_<?php echo $key ?>" value="<?php echo $value;?>" <?php echo (empty($value) ? 'disabled' : '');?> />
								<?php echo $key ?>
							</label>
							<br>
							<?php } ?>
						</fieldset>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="num_elements">Theme color</label>
					</th>
					<td>
						<fieldset class="social-icons">
							<legend class="screen-reader-text"><span>Theme color settings</span></legend>
							<input type="text" id="color" name="color" value="<?php echo $color_options ?>" />
							<div id="colorpicker"></div>
						</fieldset>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</form>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('.social-icons').find('input[type="checkbox"]').on('change', function () {
					if (jQuery(this).is(':checked')) {
						jQuery('label[for="' + jQuery(this).attr('id') + '"]').find('input[type="text"]').attr('disabled', false);
					}
					else {
						jQuery('label[for="' + jQuery(this).attr('id') + '"]').find('input[type="text"]').attr('disabled', true);
					}
				});

				jQuery('#colorpicker').farbtastic('#color');
			});
		</script>
	</div>
	<?php
}