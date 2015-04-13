<?php get_header(); global $a; ?>

<?php if(is_singular()) {
  if(have_posts()) : while(have_posts()) : the_post();
    get_template_part('loop', 'single');
  endwhile; else :
    get_template_part('inc/loop', 'error');
  endif;
} else {
  if(have_posts()) :
    include_once(get_template_directory() . '/shared/archive-title.php'); ?>

    <section class="row clearfix">
      <div class="large-8 columns">
        <h2>Recent</h2>
        <?php while(have_posts()) : the_post();
          get_template_part('loop', 'tease');
        endwhile; ?>
        <div class="clearfix page-navigation">
          <?php Traction::pagination(); ?>
        </div>
      </div>

      <div class="large-4 columns">
        <?php get_sidebar('main-sidebar'); ?>
      </div>
    </section>

  <?php
  else :
    get_template_part('inc/loop', 'error');
  endif;
} // if it's not singular
get_footer(); ?>
