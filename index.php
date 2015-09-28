<?php get_header();
global $a;
global $wp_query; ?>

<?php if(is_singular()) {
  if(have_posts()) : while(have_posts()) : the_post();
    get_template_part('loop', 'single');
  endwhile; else :
    get_template_part('shared/loop', 'error');
  endif;
} else {
  if(have_posts()) :
    get_template_part('shared/archive-title');
    if( $wp_query->post_count > 4 ) {
      $i = 0;
      while(have_posts()) : the_post();
        switch($i) {
          case 0:
            echo '<section><div class="row clearfix"><div class="large-6 columns">'; // create the hero section. this is really jank i know, but query_posts with an offset and pagination is such a rabbit hole the codex has a dedicated page to it and this is a better solution.
            Harbinger::template('slide-over', array('image_size' => 'archive_hero') );
          break;
          case 1:
            Harbinger::template('slide-over', array('image_size' => 'skinny_hero') );
            echo '</div><div class="large-6 columns">';
          break;
          case 2:
            Harbinger::template('slide-over', array('image_size' => 'skinny_hero') );
          break;
          case 3:
            Harbinger::template('slide-over', array('image_size' => 'archive_hero') );
            echo '</div></div></section>'; // end the section created in 0
            echo '<section class="row clearfix"><div class="large-8 columns"><h2>Recent</h2>';
          default :
            get_template_part('loop', 'tease');
          break;
        }
        $i++;
      endwhile;
    } else {
      echo '<section class="row clearfix"><div class="large-8 columns"><h2>Recent</h2>';
      while(have_posts()) : the_post();
        get_template_part('loop', 'tease');
      endwhile;
    } ?>
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
    get_template_part('shared/loop', 'error');
  endif;
} // if it's not singular
get_footer(); ?>
