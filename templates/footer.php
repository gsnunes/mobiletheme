<footer role="contentinfo">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar('sidebar-footer'); ?>
		</div>
	</div>
</footer>

<script>
	jQuery(document).ready(function () {
		var len = jQuery('footer .widget').length,
			columnClass = 'span' + (12 / len);
			
		jQuery('footer .widget').each(function (i, elem) {
			if (jQuery(this).hasClass('widget_text')) {
				var div = jQuery('<div class="' + columnClass + '"></div>');

				div.append('<h5>' + jQuery(this).find('h3').html() + '</h5>');
				div.append('<p>' + jQuery(this).find('.textwidget').html() + '</p>');

				jQuery(this).replaceWith(div);
			}
			else {
				jQuery(this).addClass(columnClass);
			}
		});
	});
</script>


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
