<?php

/**
* Register sidebars and widgets
*/
function roots_widgets_init() {

	// Sidebars
	register_sidebar(array(
		'name'          => __('Primary', 'roots'),
		'id'            => 'sidebar-primary',
		'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	));

	register_sidebar(array(
		'name'          => __('Footer', 'roots'),
		'id'            => 'sidebar-footer',
		'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	));

	register_sidebar(array(
		'name'          => __('Header', 'roots'),
		'id'            => 'sidebar-header'
	));


	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if (is_plugin_active('mailpress/MailPress.php')) {
		register_sidebar(array(
			'name'          => __('Newsletter', 'roots'),
			'id'            => 'sidebar-newsletter',
			'before_widget' => '<div class="widget %1$s %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<label class="title">',
			'after_title'   => '</label>'
		));
	}



	// Widgets
	register_widget('Mobiletheme_Contact_Widget');
	register_widget('Mobiletheme_Social_Widget');

	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Search');
	//unregister_widget('WP_Widget_Text');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');

}

add_action('widgets_init', 'roots_widgets_init');



class Mobiletheme_Social_Widget extends WP_Widget {

	/**
	* Sets up the widgets name etc
	*/
	public function __construct() {
		$widget_ops = array('classname' => 'mobiletheme-social', 'description' => __('Use this widget to add a social icons', 'roots'));
		$this->WP_Widget('mobiletheme-social', __('Social icons', 'roots'), $widget_ops);
	}



	/**
	* Outputs the content of the widget
	*
	* @param array $args
	* @param array $instance
	*/
	public function widget( $args, $instance ) {
		extract( $args );

		?>
		<div class="widget">
			<h5>Social Media</h5>
			<ul class="sprite-icons">
				<?php
				foreach ($instance as $key => $value) {
					if (!empty($value)) {
						echo '<li><a class="' . $key . '" href="' . $value . '" target="_blank"></a></li>';
					}
				}
				?>
			</ul>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('header').find('.sprite-icons').parent().find('h5').remove();
				jQuery('header').find('.sprite-icons').addClass('colored');
			});
		</script>
		<?php
	}



	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
	public function form( $instance ) {
		$defaults = array( 'twitter' => '', 'vimeo' => '', 'flickr' => '', 'facebook' => '', 'skype' => '', 'youtube' => '', 'googleplus' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset class="social-icons">
						<legend class="screen-reader-text"><span>Social settings</span></legend>

						<?php foreach ($instance as $key => $value) { ?>
						<label for="<?php echo $this->get_field_id( $key ); ?>">
							<input type="checkbox" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" value="1" <?php echo (empty($value) ? '' : 'checked');?> />
							<input type="text" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" value="<?php echo $value;?>" <?php echo (empty($value) ? 'disabled' : '');?> />
							<?php echo $key ?>
						</label>
						<br>
						<?php } ?>
					</fieldset>
				</td>
			</tr>
		</table>

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
			});
		</script>
		<?php
	}



	/**
	* Processing widget options on save
	*
	* @param array $new_instance The new options
	* @param array $old_instance The previous options
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['twitter'] = strip_tags( $new_instance['twitter'] );
		$instance['vimeo'] = strip_tags( $new_instance['vimeo'] );
		$instance['flickr'] = strip_tags( $new_instance['flickr'] );
		$instance['facebook'] = strip_tags( $new_instance['facebook'] );
		$instance['skype'] = strip_tags( $new_instance['skype'] );
		$instance['youtube'] = strip_tags( $new_instance['youtube'] );
		$instance['googleplus'] = strip_tags( $new_instance['googleplus'] );

		return $instance;
	}

}



class Mobiletheme_Contact_Widget extends WP_Widget {

	/**
	* Sets up the widgets name etc
	*/
	public function __construct() {
		$widget_ops = array('classname' => 'mobiletheme-contact', 'description' => __('Use this widget to add a contact form', 'roots'));
		$this->WP_Widget('mobiletheme-contact', __('Contact form', 'roots'), $widget_ops);

		add_action('wp_ajax_nopriv_my_action', 'my_action_callback');
		add_action('wp_ajax_my_action', 'my_action_callback');
	}



	/**
	* Outputs the content of the widget
	*
	* @param array $args
	* @param array $instance
	*/
	public function widget( $args, $instance ) {
		?>
		<div class="widget">
			<h5>Contact</h5>

			<form id="contact-form" action="<?php echo admin_url('admin-ajax.php'); ?>">
				<input type="hidden" name="contactEmail" value="<?php echo $instance['email']; ?>" />

				<label>Name</label>
				<input type="text" class="input-large" name="name">

				<label>E-mail</label>
				<input type="text" class="input-large" name="email">

				<label>Message</label>
				<textarea rows="3" class="input-large" name="message"></textarea>

				<button type="submit" class="btn btn-small">SUBMIT</button>

				<div class="response"></div>
			</form>
		</div>
		<?php
	}



	/**
	* Outputs the options form on admin
	*
	* @param array $instance The widget options
	*/
	public function form( $instance ) {
		// outputs the options form on admin
		if ( isset( $instance[ 'email' ] ) ) {
			$email = $instance[ 'email' ];
		}
		else {
			$email = get_settings('admin_email');
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>">Contact email:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<?php
	}



	/**
	* Processing widget options on save
	*
	* @param array $new_instance The new options
	* @param array $old_instance The previous options
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : get_settings('admin_email');

		return $instance;
	}

}



function my_action_callback () {
	$params = array();
	parse_str($_POST['data'], $params);

	$name = trim($params['name']);
	$email = $params['email'];
	$message = $params['message'];

	$subject = get_bloginfo('name') . ' - Contact';
	$site_owners_email = $params['contactEmail'];

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



add_action('admin_print_styles-widgets.php', 'print_widget_hint_style');

function print_widget_hint_style()
{
	?>
	<style type="text/css">
		/* Less specific rule for all widgets */
		div.widgets-sortables[id*=-header] div.widget .widget-title,
		div.widgets-sortables[id*=-newsletter] div.widget .widget-title,
		div.widgets-sortables[id*=-primary] div.widget .widget-title,
		div.widgets-sortables[id*=-footer] div.widget .widget-title
		{
			color: red;
			text-decoration: line-through;
		}

		/* More specific rule for correct widgets */
		div.widgets-sortables[id*=-header] div.widget[id*=-social-] .widget-title,
		div.widgets-sortables[id*=-newsletter] div.widget[id*=_mailpress-] .widget-title,
		div.widgets-sortables[id*=-primary] div.widget[id*=_text-] .widget-title,
		div.widgets-sortables[id*=-footer] div.widget[id*=_text-] .widget-title,
		div.widgets-sortables[id*=-footer] div.widget[id*=-contact-] .widget-title,
		div.widgets-sortables[id*=-footer] div.widget[id*=-social-] .widget-title
		{
			color: green;
			text-decoration: none;
		}
	</style>
	<?php
}