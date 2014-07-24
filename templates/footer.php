<footer role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="span3">
				<h5>About Us</h5>
				<p>Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis. Morbi in sem quis dui placerat ornare.</p>
			</div>
			<div class="span3">
				<h5>Useful Links</h5>
				<p>Lorem ipsum dolor sit amet Aliquam tincidunt mauris Vestibulum auctor Nunc dignissim risus Cras ornare tristique elit.</p>
			</div>
			<div class="span3">
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
			<div class="span3">
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
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

<?php
/*
<footer class="content-info container" role="contentinfo">
  <div class="row">
    <div class="col-lg-12">
      <?php dynamic_sidebar('sidebar-footer'); ?>
      <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
*/
?>
