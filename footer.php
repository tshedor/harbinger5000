<footer class="footer">
	<div class="row clearfix">
		<div class="large-12 columns">
			 <?php wp_nav_menu(array( 'theme_location' => 'footer_menu', 'container' => '', 'items_wrap' => '<ul class="link-list">%3$s</ul>', )); ?>
		</div>
	</div>
	<div class="row clearfix">
		<div class="large-6 columns">
			<div class="copyright">
				&copy; Copyright <a href="<?php bloginfo('url') ?>" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a> <?php echo date('Y'); ?>
			</div>
		</div>
		<div class="large-6 columns text-right">
			<div class="social-icons">
				<?php Traction::social_header(); ?>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>