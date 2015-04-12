<?php
get_header();
global $a;
?>

<section class="row clearfix hero-wrapper">

  <div class="large-8 columns">
    <?php $q1 = new WP_Query(array('showposts' => 1, 'cat' => $a['home_hero'])); if($q1->have_posts()) : while($q1->have_posts()) : $q1->the_post(); ?>

      <div class="js-slide-title slide-title featured">
        <?php Traction::get_image('hero'); ?>
        <div class="slide-cover">
          <?php the_title(); ?>
        </div>
      </div>

    <?php endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="large-4 columns">
    <?php $q15 = new WP_Query(array('showposts' => 3, 'cat' => $a['home_hero'], 'offset' => 1)); if($q15->have_posts()) : while($q15->have_posts()) : $q15->the_post(); ?>

      <div class="js-slide-title slide-title featured--sidekick">
        <?php Traction::get_image('hero_sidekick'); ?>
        <div class="slide-cover">
          <?php the_title(); ?>
        </div>
      </div>

    <?php endwhile; endif; wp_reset_postdata(); ?>
  </div>

</section>

<?php
get_footer(); ?>
