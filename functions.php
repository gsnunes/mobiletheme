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