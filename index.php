<?php get_header(); $a = get_option('traction_admin_options'); ?>

<div class="row clearfix">
	<div class="large-12 columns">
		<div class="breadcrumbs">
			<?php Traction::breadcrumbs(); ?>
		</div>
	</div>
</div>
<?php if(is_singular()) {
	if(have_posts()) : while(have_posts()) : the_post();
		get_template_part('loop', 'single');
	endwhile; else :
		get_template_part('inc/loop', 'error');
	endif;
} else { ?>
<div class="row clearfix lg-margin">
	<div class="large-12 columns archive-head text-center">
		<?php if(have_posts()) : if(is_category()) { ?>
			<h1><?php single_cat_title(); Traction::if_paged(); if($a['show_rss_on_archive']){ ?><span class="pull-right"><a href="<?php echo get_category_link(get_query_var('cat')).'feed'; ?>" title="RSS Feed for <?php single_cat_title(); ?>"><i class="icon-feed"></i></a></span><?php } ?></h1>
		<?php } elseif(is_tag()) { ?>
			<h1>Tagged <?php single_tag_title(); Traction::if_paged(); if($a['show_rss_on_archive']){ ?> <span class="pull-right"><a href="<?php echo get_tag_link(get_query_var('tag')).'feed'; ?>" title="Subscribe to <?php single_tag_title(); ?>" class="feed-link"><i class="icon-feed"></i></a></span><?php } ?></h1>
		<?php } elseif(is_tax()){ $tax =  get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) ?>
			<h1><?php echo $tax->name; Traction::if_paged(); if($a['show_rss_on_archive']){ ?> <span class="pull-right"><a href="<?php echo get_term_link(get_query_var('taxonomy')).'feed'; ?>" title="Subscribe to <?php echo $tax->name; ?>" class="feed-link"><i class="icon-feed"></i></a></span><?php } ?></h1>
		<?php } elseif(is_day() || is_month() || is_year()) { ?>
			<h1>From <?php the_time('F j, Y'); Traction::if_paged(); ?></h1>
		<?php } elseif(is_author()) { ?>
			<h1>By <?php the_author_meta('display_name', get_query_var('author')); Traction::if_paged(); if($a['show_rss_on_archive']){ ?> <span class="pull-right"><a href="<?php echo get_author_postraction_url(get_query_var('author')).'feed'; ?>" title="Subscribe to <?php the_author_meta('display_name', get_query_var('author')); ?>" class="feed-link"><i class="icon-feed"></i></a></span> <?php } ?></h1>
		<?php } ?>
	</div>
</div>
<div class="row clearfix">
	<div class="large-8 large-centered columns archive-list">
		<?php while(have_posts()) : the_post();
			get_template_part('loop', 'tease');
		endwhile; ?>
		<div class="clearfix page-navigation">
			<?php Traction::pagination(); ?>
		</div>
	</div>
</div>
<?php else :
	get_template_part('inc/loop', 'error');
endif; ?>
<?php } get_footer(); ?>