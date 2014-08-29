<header class="container">
	<div class="row">
		<div class="span8">
			<h1><?php bloginfo('name'); ?></h1>
			<p><?php bloginfo('description'); ?></p>
		</div>
		<div class="span4">
			<?php dynamic_sidebar('sidebar-header'); ?>
		</div>
	</div>
</header>

<?php
/*
<header class="banner container" role="banner">
<div class="row">
<div class="col-lg-12">
<a class="brand" href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a>
<nav class="nav-main" role="navigation">
<?php
if (has_nav_menu('primary_navigation')) :
wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-pills'));
endif;
?>
</nav>
</div>
</div>
</header>
*/
?>