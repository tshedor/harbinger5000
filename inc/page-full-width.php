<?php
/*
Template Name: Full Width
*/
get_header();
$cp = get_post_custom();
if(have_posts()) : while(have_posts()) : the_post();

get_template_part('loop-page', 'header'); ?>

<div class="row clearfix">
	<div class="large-12 large-centered columns">
		<div <?php post_class('page-emphasis entry clearfix'); ?>>
			<?php the_content(); ?>
		</div>
	</div>
</div>

<?php endwhile; else :
	get_template_part('shared/loop', 'error');
 endif; get_footer(); ?>