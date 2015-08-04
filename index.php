<?php get_header(); global $a; global $query_string; ?>

<?php if(is_singular()) {
  if(have_posts()) : while(have_posts()) : the_post();
    get_template_part('loop', 'single');
  endwhile; else :
    get_template_part('shared/loop', 'error');
  endif;
} else {
  if(have_posts()) :
    get_template_part('shared/archive-title');
    $featured = ''; ?>

    <section>
      <div class="row clearfix">
        <?php query_posts($query_string . '&showposts=4'); ?>
          <div class="large-6 columns">
            <?php $i = 0; while(have_posts()) : the_post();
              switch($i) {
                case 0:
                case 3:
                  Harbinger::template('slide-over', array('image_size' => 'archive_hero') );
                break;
                case 1:
                  Harbinger::template('slide-over', array('image_size' => 'skinny_hero') );
                  echo '</div><div class="large-6 columns">';
                break;
                case 2:
                  Harbinger::template('slide-over', array('image_size' => 'skinny_hero') );
                break;
              }
            $i++;
            $featured .= get_the_ID(); endwhile; wp_reset_query(); ?>
          </div>
      </div>
    </section>
    <?php query_posts($query_string . '&offset=4');
    global $wp_query; ?>

    <section class="row clearfix">
      <div class="large-8 columns">

        <?php if($wp_query->found_posts > 0) : ?>
          <h2>Recent</h2>
          <?php while(have_posts()) : the_post();
            get_template_part('loop', 'tease');
          endwhile; ?>
          <div class="clearfix page-navigation">
            <?php Traction::pagination(); ?>
          </div>
        <?php endif; ?>
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
