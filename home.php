<?php
get_header();
global $a;
?>

<section class="row clearfix hero-wrapper">

  <div class="large-8 columns">
    <?php $q1 = new WP_Query(array('showposts' => 1, 'cat' => $a['home_hero'])); if($q1->have_posts()) : while($q1->have_posts()) : $q1->the_post();
      Harbinger::template('static-title',
        array('image_size' => 'hero')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="large-4 columns">
    <?php $q15 = new WP_Query(array('showposts' => 3, 'cat' => $a['home_hero'], 'offset' => 1)); if($q15->have_posts()) : while($q15->have_posts()) : $q15->the_post();
      Harbinger::template('slide-over',
        array('wrapper' => 'featured--sidekick', 'image_size' => 'hero_sidekick')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

</section>

<section class="row clearfix">
  <div class="large-4 columns">
    <h2>Popular</h2>
    <aside class="large-first">
      <?php $q2 = Traction::queryPopular(5); if($q2->have_posts()) : while($q2->have_posts()) : $q2->the_post(); ?>
        <h4>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h4>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </aside>
  </div>

  <div class="large-8 columns">
    <?php $supplement_cat = get_category($a['home_supplement_category']); ?>
    <h2><?php echo $supplement_cat->name; ?></h2>
    <?php $q3 = new WP_Query(array('showposts' => 1, 'cat' => $supplement_cat->cat_ID)); if($q3->have_posts()) : while($q3->have_posts()) : $q3->the_post();

      Harbinger::template('static-title',
        array('image_size' => 'hero')
      );

    endwhile; endif; wp_reset_postdata();

    $q35 = new WP_Query(array('showposts' => 5, 'cat' => $supplement_cat->cat_ID, 'offset' => 1)); if($q35->have_posts()) : while($q35->have_posts()) : $q35->the_post();

      Harbinger::template('slide-over',
        array('wrapper' => '-micro',  'image_size' => 'thumbnail')
      );

    endwhile; endif; wp_reset_postdata(); ?>
  </div>

<?php
get_footer(); ?>
