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

	$color_options = get_option("mobiletheme_color");
	$color_options = empty($color_options) ? '#bd1f43' : $color_options;

	if (isset($_POST["mobiletheme_settings"])) {
		$color_options = $_POST['color'];
		update_option("mobiletheme_color", esc_attr($color_options));

		require "lib/vendor/lessc.inc.php";
		$less = new lessc;

		$less->unsetVariable("baseColor");

		$less->setVariables(array(
			"baseColor" => $color_options
		));

		chmod("/assets/css/main.min.css", 0777);

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