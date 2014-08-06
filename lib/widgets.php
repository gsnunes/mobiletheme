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
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'name'          => __('Footer', 'roots'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s"><div class="widget-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));

  // Widgets
  register_widget('Roots_Vcard_Widget');
  register_widget('Mobiletheme_Contact_Widget');
  register_widget('Mobiletheme_Social_Widget');
}

add_action('widgets_init', 'roots_widgets_init');



class Mobiletheme_Social_Widget extends WP_Widget {

  /**
  * Sets up the widgets name etc
  */
  public function __construct() {
    $widget_ops = array('classname' => 'widget_mobiletheme_social', 'description' => __('Use this widget to add a social icons', 'roots'));

    $this->WP_Widget('widget_mobiletheme_social', __('Mobiletheme: social', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_mobiletheme_social';
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
        <h5>Social Media</h5>
        <ul class="sprite-icons">
          <?php
            $social_icons_options = get_option("mobiletheme_social_icons");

            foreach ($social_icons_options as $key => $value) {
              if (!empty($value)) {
                echo '<li><a class="' . $key . '" href="' . $value . '" target="_blank"></a></li>';
              }
            }
          ?>
        </ul>
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
  }

  /**
  * Processing widget options on save
  *
  * @param array $new_instance The new options
  * @param array $old_instance The previous options
  */
  public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved
  }
  
}




class Mobiletheme_Contact_Widget extends WP_Widget {

  /**
  * Sets up the widgets name etc
  */
  public function __construct() {
    $widget_ops = array('classname' => 'mobiletheme-contact', 'description' => __('Use this widget to add a contact form', 'roots'));
    $this->WP_Widget('mobiletheme-contact', __('Contact form', 'roots'), $widget_ops);
  }

  /**
  * Outputs the content of the widget
  *
  * @param array $args
  * @param array $instance
  */
  public function widget( $args, $instance ) {
    $email = apply_filters( 'widget_email', $instance['email'] );
    echo $email;
    ?>
    <div class="widget">
        <h5>Contact</h5>

        <form id="contact-form" action="<?php echo admin_url('admin-ajax.php'); ?>">
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
      $email = __( 'New email', 'text_domain' );
    }

    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'email' ); ?>">Contact email:</label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_id( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
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
    // processes widget options to be saved
    $instance = array();
    $instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : get_settings('admin_email');

    return $instance;
  }
  
}




/**
 * Example vCard widget
 */
class Roots_Vcard_Widget extends WP_Widget {
  private $fields = array(
    'title'          => 'Title (optional)',
    'street_address' => 'Street Address',
    'locality'       => 'City/Locality',
    'region'         => 'State/Region',
    'postal_code'    => 'Zipcode/Postal Code',
    'tel'            => 'Telephone',
    'email'          => 'Email'
  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_roots_vcard', 'description' => __('Use this widget to add a vCard', 'roots'));

    $this->WP_Widget('widget_roots_vcard', __('Roots: vCard', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_roots_vcard';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_roots_vcard', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

    $title = apply_filters('widget_title', empty($instance['title']) ? __('vCard', 'roots') : $instance['title'], $instance, $this->id_base);

    foreach($this->fields as $name => $label) {
      if (!isset($instance[$name])) { $instance[$name] = ''; }
    }

    echo $before_widget;

    if ($title) {
      echo $before_title, $title, $after_title;
    }
  ?>
    <p class="vcard">
      <a class="fn org url" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a><br>
      <span class="adr">
        <span class="street-address"><?php echo $instance['street_address']; ?></span><br>
        <span class="locality"><?php echo $instance['locality']; ?></span>,
        <span class="region"><?php echo $instance['region']; ?></span>
        <span class="postal-code"><?php echo $instance['postal_code']; ?></span><br>
      </span>
      <span class="tel"><span class="value"><?php echo $instance['tel']; ?></span></span><br>
      <a class="email" href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a>
    </p>
  <?php
    echo $after_widget;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_roots_vcard', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_roots_vcard'])) {
      delete_option('widget_roots_vcard');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_roots_vcard', 'widget');
  }

  function form($instance) {
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'roots'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
  }
}
