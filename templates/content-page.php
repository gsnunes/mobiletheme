<div id="highlight" class="sprite-devices iphone portrait">
	<div class="opacity"></div>
	<div class="container">
		<!-- TWO COLUMNS -->
		<div class="row two-columns">
			<div class="span8">
				<div class="half-circle previous"><span></span></div>
				<div class="half-circle next"><span></span></div>
				<div class="device"></div>
				<div class="circle"><p>$ 2,99</p></div>
			</div>
			<div class="span4">
				<div class="description">
					<h3>Mauris turpis erat, rhoncus ut tincidunt ut, congue non mi.</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat.</p>
				</div>
				<ul class="sprite-stores">
					<li><a class="android" href="javascript:;"></a></li>
					<li><a class="apple" href="javascript:;"></a></li>
				</ul>
			</div>
		</div>

		<!-- THREE COLUMNS, ONLY VERTICAL IPHONE -->
		<div class="row three-columns">
			<div class="span4">
				<div class="half-circle previous"><span></span></div>
				<div class="half-circle next"><span></span></div>
				<div class="device"></div>
			</div>
			<div class="span4">
				<div class="description">
					<h3>Mauris turpis erat, rhoncus ut tincidunt ut, congue non mi.</h3>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat.</p>
				</div>
				<div class="circle"><p>$ 2,99</p></div>
			</div>
			<div class="span4">
				<ul class="sprite-stores">
					<li><a class="android" href="javascript:;"></a></li>
					<li><a class="apple" href="javascript:;"></a></li>
				</ul>
			</div>
		</div>

		<ul class="slider-images">
			<li data-layout="portrait" data-device="ipad"><a href="javascript:;"><img src="http://placehold.it/291x387/000000" alt=""></a></li>
			<li data-layout="portrait" data-device="ipad"><a href="javascript:;"><img src="http://placehold.it/291x387/ff0000" alt=""></a></li>

			<li data-layout="portrait" data-device="iphone"><a href="javascript:;"><img src="http://placehold.it/173x259/000000" alt=""></a></li>
			<li data-layout="portrait" data-device="iphone"><a href="javascript:;"><img src="http://placehold.it/173x259/ff0000" alt=""></a></li>

			<li data-layout="landscape" data-device="iphone"><a href="javascript:;"><img src="http://placehold.it/259x173/000000" alt=""></a></li>
			<li data-layout="landscape" data-device="iphone"><a href="javascript:;"><img src="http://placehold.it/259x173/ff0000" alt=""></a></li>

			<li data-layout="landscape" data-device="ipad"><a href="javascript:;"><img src="http://placehold.it/387x291/000000" alt=""></a></li>
			<li data-layout="landscape" data-device="ipad"><a href="javascript:;"><img src="http://placehold.it/387x291/ff0000" alt=""></a></li>
		</ul>
	</div>
</div>

<div class="container">
	<div class="sidebar-primary">
		<?php dynamic_sidebar('sidebar-primary'); ?>
	</div>

	<!--
	<div class="row">
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
	</div>

	<div class="row">
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
		<div class="span4">
			<h4>Subheading</h4>
			<p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>
		</div>
	</div>

	<hr />

	-->

	<!--
	<form class="form-inline newsletter">
		<label for="newsletter-email">Signup for our newsletter to get exclusive deals directly to your inbox.</label>
		<input type="text" class="input-large" placeholder="enter your e-mail address" id="newsletter-email">
		<button type="submit" class="btn">SUBMIT</button>
	</form>
	-->

	<?php dynamic_sidebar('sidebar-newsletter'); ?>

</div>

<!--
<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
-->


<script>
	jQuery(document).ready(function () {
		jQuery('.widget_mailpress').find('form').addClass('form-inline newsletter');
		jQuery('.widget_mailpress').find('input[type="submit"]').addClass('btn');

		var title = jQuery('.widget_mailpress').find('label.title');
		jQuery('.widget_mailpress').find('label.title').remove();
		jQuery('.widget_mailpress').find('form').prepend(title);

		jQuery('.widget_mailpress').find('br').remove();



		var oldContent = jQuery('.sidebar-primary .widget'),
			len = oldContent.length,
			columnClass = 'span' + (len <= 4 ? (12 / len) : 3),
			newContent = jQuery('<div class="row"></div>'),
			rows = Math.ceil(len / 4),
			currentRow = 0,
			count = 0;

		jQuery('.sidebar-primary').html("");
			
		oldContent.each(function (i, elem) {
			if (currentRow < rows && count === 4) {
				newContent = jQuery('<div class="row"></div>')
				currentRow++;
			}

			if (jQuery(this).hasClass('widget_text')) {
				var div = jQuery('<div class="' + columnClass + '"></div>'),
					content = jQuery(this).find('.textwidget'),
					header = jQuery(this).find('h3');

				if (header.length) {
					div.append('<h4>' + header.html() + '</h4>');
				}

				if (content) {
					div.append('<p>' + content.html() + '</p>');
				}

				//jQuery(this).replaceWith(div);
				newContent.append(div);
				count++;
			}
			else {
				jQuery(this).addClass(columnClass);
			}

			jQuery('.sidebar-primary').append(newContent);
		});


		if (jQuery('.widget_mailpress').length && jQuery('.sidebar-primary .span3').length) {
			jQuery('.sidebar-primary').append('<hr />');
		}
	});
</script>