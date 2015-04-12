<?php
/*
Template Name: Sitemap
*/
get_header();
$cp = get_post_custom();
if(have_posts()) : while(have_posts()) : the_post();

get_template_part('loop-page', 'header'); ?>

<div class="row clearfix">
	<div class="large-10 large-centered columns">
		<div <?php post_class('page-emphasis entry clearfix'); ?>>
			<?php the_content(); ?>
			<?php echo do_shortcode('[sitemap pages="true" categories="true" tags="true" /]'); ?>
		</div>
	</div>
</div>

<?php endwhile; else :
	get_template_part('inc/loop', 'error');
 endif; get_footer(); ?>