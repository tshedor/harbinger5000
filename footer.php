<?php wp_footer(); ?>
<div class="footer">
	<div class="row clearfix">
		<div class="large-12 columns text-center">
			<div class="social-icons">
				<?php Traction::social_header(); ?>
			</div>
			<div class="copyright">
				&copy; Copyright <a href="<?php bloginfo('url') ?>" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a> <?php echo date('Y'); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>