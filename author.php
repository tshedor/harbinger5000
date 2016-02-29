<?php get_header();
global $a;
global $wp_query;

if(have_posts()) :
  get_template_part('shared/archive-title'); ?>

  <section>
    <div class="row">
      <div class="large-8 columns">
        <div class="row hero-wrapper author-hero">
          <div class="large-5 columns">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 512 ); ?>
          </div>
          <div class="large-7 columns">
            <div class="author-bio">
              <?php the_author_meta( 'description' ); ?>
            </div>
          </div>
        </div>

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
    </div>
  </section>

  <?php
  else :
    get_template_part('shared/loop', 'error');
  endif;
get_footer(); ?>
