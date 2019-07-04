<?php
get_header();
global $a;
?>

<section class="row clearfix hero-wrapper">

  <div class="large-8 medium-8 columns">
    <?php
    $q1 = new WP_Query(array('showposts' => 1, 'cat' => $a['home_hero']));
    if($q1->have_posts()) : while($q1->have_posts()) : $q1->the_post();
      Harbinger::template('static-title',
        array('image_size' => 'hero1')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="large-4 medium-4 columns">
    <?php
    $q15 = new WP_Query(array('showposts' => 2, 'cat' => $a['home_hero'], 'offset' => 1));
    if($q15->have_posts()) : while($q15->have_posts()) : $q15->the_post();
      Harbinger::template('slide-over',
        array('wrapper' => 'featured--sidekick', 'image_size' => 'hero_side')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

</section>

<section class="row clearfix">
  <div class="large-7 medium-6 columns">
    <?php $supplement_cat = get_category($a['home_supplement_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($supplement_cat->cat_ID); ?>" title="<?php echo $supplement_cat->name; ?>">
        <span><?php echo $supplement_cat->name; ?></span>
      </a>
    </h2>

    <div class="row clearfix">
      <div class="large-8 columns">
        <?php
        $q5 = new WP_Query(array('showposts' => 1, 'cat' => $a['home_supplement_category']));
        if($q5->have_posts()) : while($q5->have_posts()) : $q5->the_post();
          Harbinger::template('static-title',
            array('image_size' => 'hero')
          );
        endwhile; endif; wp_reset_postdata(); ?>
      </div>
      <div class="large-4 columns spaced-slides">
        <?php
        $q55 = new WP_Query(array('showposts' => 2, 'cat' => $a['home_supplement_category'], 'offset' => 1));
        if($q55->have_posts()) : while($q55->have_posts()) : $q55->the_post();
          Harbinger::template('slide-over',
            array('image_size' => 'medium')
          );
        endwhile; endif; wp_reset_postdata(); ?>
      </div>
    </div>

    <?php $col_1_box_2 = get_category($a['home_col_1_box_2']); ?>
    <h2>
      <a href="<?php echo get_category_link($col_1_box_2->cat_ID); ?>" title="<?php echo $col_1_box_2->name; ?>">
        <span><?php echo $col_1_box_2->name; ?></span>
      </a>
    </h2>

    <div class="four-up">
      <?php
      $c1b2 = new WP_Query(array('showposts' => 4, 'cat' => $a['home_col_1_box_2']));
      if($c1b2->have_posts()) : while($c1b2->have_posts()) : $c1b2->the_post();
        Harbinger::template('slide-over',
          array('image_size' => 'medium')
        );
      endwhile; endif; wp_reset_postdata(); ?>
    </div>

    <?php $photos_cat = get_category($a['photos_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($photos_cat->cat_ID); ?>" title="<?php echo $photos_cat->name; ?>">
        <span><?php echo $photos_cat->name; ?></span>
      </a>
    </h2>
    <ul class="simple-slider bx-slider">
      <?php $q7 = new WP_Query(array('showposts' => 3, 'cat' => $photos_cat->cat_ID));
      if($q7->have_posts()) : while($q7->have_posts()) : $q7->the_post(); ?>
        <li>
          <?php Harbinger::template('slide-over', array('wrapper' => '-micro', 'image_size' => 'large')); ?>
        </li>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </ul>
  </div>

  <div class="large-5 medium-6 columns">
    <h2><span>Popular</span></h2>
    <aside class="large-first">
      <?php
      $q2 = Traction::queryPopular(4);
      if($q2->have_posts()) : while($q2->have_posts()) : $q2->the_post(); ?>
        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h5>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </aside>

    <h2><span>Scores</span></h2>
    <?php echo stripslashes( $a['home_subpopular_embed'] ); ?>

    <?php $col_2_box_3 = get_category($a['home_col_2_box_3']); ?>
    <h2>
      <a href="<?php echo get_category_link($col_2_box_3->cat_ID); ?>" title="<?php echo $col_2_box_3->name; ?>">
        <span><?php echo $col_2_box_3->name; ?></span>
      </a>
    </h2>
    <div class="four-up">
      <?php
      $c2b3 = new WP_Query(array('showposts' => 2, 'cat' => $col_2_box_3->cat_ID));
      if($c2b3->have_posts()) : while($c2b3->have_posts()) : $c2b3->the_post();
        Harbinger::template('slide-over',
          array('image_size' => 'thumbnail')
        );
      endwhile; endif; wp_reset_postdata(); ?>
    </div>

    <?php $col_2_box_4 = get_category($a['home_col_2_box_4']); ?>
    <h2>
      <a href="<?php echo get_category_link($col_2_box_4->cat_ID); ?>" title="<?php echo $col_2_box_4->name; ?>">
        <span><?php echo $col_2_box_4->name; ?></span>
      </a>
    </h2>
    <div class="large-first">
      <?php
      $c2b4 = new WP_Query(array('showposts' => 3, 'cat' => $a['home_col_2_box_4']));
      if($c2b4->have_posts()) : while($c2b4->have_posts()) : $c2b4->the_post(); ?>
        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php Traction::get_image('thumbnail', array('link_to_post' => false)); ?>
            <?php the_title(); ?>
          </a>
        </h5>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<section class="row clearfix">
  <div class="large-8 medium-6 columns">
    <?php $fold_cat = get_category($a['home_below_fold_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($fold_cat->cat_ID); ?>" title="<?php echo $fold_cat->name; ?>">
        <span><?php echo $fold_cat->name; ?></span>
      </a>
    </h2>
    <?php
    $q4 = new WP_Query(array('showposts' => 1, 'cat' => $fold_cat->cat_ID));
    if($q4->have_posts()) : while($q4->have_posts()) : $q4->the_post();
      Harbinger::template('static-title',
        array('image_size' => 'hero')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="large-4 medium-6 columns spaced-slides">
    <?php $col_2_box_5 = get_category($a['home_col_2_box_5']); ?>
    <h2>
      <a href="<?php echo get_category_link($col_2_box_5->cat_ID); ?>" title="<?php echo $col_2_box_5->name; ?>">
        <span><?php echo $col_2_box_5->name; ?></span>
      </a>
    </h2>

    <?php
    $c2b5 = new WP_Query(array('showposts' => 2, 'cat' => $col_2_box_5->cat_ID));
    if($c2b5->have_posts()) : while($c2b5->have_posts()) : $c2b5->the_post();
      Harbinger::template('slide-over',
        array('image_size' => 'hero')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>
</section>

<section class="row clearfix">
  <div class="large-12 columns">
    <h2><span>Sponsors</span></h2>
    <ul class="evenly-spaced-list">
    <?php
    $q6 = new WP_Query(array('showposts' => $a['home_sponsor_count'], 'post_type' => 'sponsor', 'offset' => 1));
    if($q6->have_posts()) : while($q6->have_posts()) : $q6->the_post(); ?>

      <li><a href="<?php echo get_post_meta($post->ID, 'sponsor_url', true); ?>" title="<?php the_title(); ?>" target="_blank">
        <?php Traction::get_image('large', array('link_to_post' => false)); ?>
      </a></li>

    <?php endwhile; endif; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>

<?php
get_footer(); ?>
