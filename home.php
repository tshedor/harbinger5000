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
        array('image_size' => 'hero')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="large-4 medium-4 columns">
    <?php
    $q15 = new WP_Query(array('showposts' => 3, 'cat' => $a['home_hero'], 'offset' => 1));
    if($q15->have_posts()) : while($q15->have_posts()) : $q15->the_post();
      Harbinger::template('slide-over',
        array('wrapper' => 'featured--sidekick', 'image_size' => 'hero_sidekick')
      );
    endwhile; endif; wp_reset_postdata(); ?>
  </div>

</section>

<section class="row clearfix">
  <div class="large-4 medium-6 columns">
    <h2>Popular</h2>
    <aside class="large-first">
      <?php
      $q2 = Traction::queryPopular(5);
      if($q2->have_posts()) : while($q2->have_posts()) : $q2->the_post(); ?>
        <h4>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h4>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </aside>
  </div>

  <div class="large-8 medium-6 columns">
    <?php $supplement_cat = get_category($a['home_supplement_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($supplement_cat->cat_ID); ?>" title="<?php echo $supplement_cat->name; ?>">
        <?php echo $supplement_cat->name; ?>
      </a>
    </h2>
    <?php
    $q3 = new WP_Query(array('showposts' => 1, 'cat' => $supplement_cat->cat_ID));
    if($q3->have_posts()) : while($q3->have_posts()) : $q3->the_post();

      Harbinger::template('static-title',
        array('image_size' => 'hero')
      );

    endwhile; endif; wp_reset_postdata(); ?>

    <?php $q35 = new WP_Query(array('showposts' => 5, 'cat' => $supplement_cat->cat_ID, 'offset' => 1)); if($q35->have_posts()) : ?>
      <div class="featured-strip">
        <?php while($q35->have_posts()) : $q35->the_post();
          Harbinger::template('slide-over',
            array('wrapper' => '-micro',  'image_size' => 'thumbnail')
          );
        endwhile; ?>
      </div>
    <?php endif; wp_reset_postdata(); ?>
  </div>
</section>

<section class="row clearfix">
  <div class="large-6 medium-6 columns">
    <?php $fold_cat = get_category($a['home_below_fold_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($fold_cat->cat_ID); ?>" title="<?php echo $fold_cat->name; ?>">
        <?php echo $fold_cat->name; ?>
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
  <div class="large-6 medium-6 columns">
    <h2>Latest Issue</h2>
    <?php echo $a['home_latest_issue']; ?>
  </div>
</section>

<section class="row clearfix">
  <div class="large-8 medium-8 columns">
    <?php $broadcast_cat = get_category($a['broadcast_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($broadcast_cat->cat_ID); ?>" title="<?php echo $broadcast_cat->name; ?>">
        <?php echo $broadcast_cat->name; ?>
      </a>
    </h2>
    <div class="row clearfix">
      <div class="large-9 columns">
        <?php
        $q5 = new WP_Query(array('showposts' => 1, 'cat' => $a['broadcast_category']));
        if($q5->have_posts()) : while($q5->have_posts()) : $q5->the_post();
          Harbinger::template('slide-over',
            array('image_size' => 'hero')
          );
        endwhile; endif; wp_reset_postdata(); ?>
      </div>
      <div class="large-3 columns">
        <?php
        $q55 = new WP_Query(array('showposts' => 3, 'cat' => $a['broadcast_category'], 'offset' => 1));
        if($q55->have_posts()) : while($q55->have_posts()) : $q55->the_post();
          Harbinger::template('static-title',
            array('image_size' => 'thumbnail')
          );
        endwhile; endif; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
  <div class="large-4 medium-4 columns">
    <h2>Sponsors</h2>
    <?php
    $q6 = new WP_Query(array('showposts' => 1, 'post_type' => 'sponsor'));
    if($q6->have_posts()) : while($q6->have_posts()) : $q6->the_post(); ?>

      <a href="<?php echo get_post_meta($post->ID, 'sponsor_url', true); ?>" title="<?php the_title(); ?>">
        <?php Traction::get_image('large', array('link_to_post' => false)); ?>
      </a>

    <?php endwhile; endif; wp_reset_postdata(); ?>
  </div>
</section>

<section class="row clearfix">
  <div class="large-12 columns">
    <?php $photos_cat = get_category($a['photos_category']); ?>
    <h2>
      <a href="<?php echo get_category_link($photos_cat->cat_ID); ?>" title="<?php echo $photos_cat->name; ?>">
        <?php echo $photos_cat->name; ?>
      </a>
    </h2>
    <aside>
      <ul class="bx-slider">
        <?php $q7 = new WP_Query(array('showposts' => 15, 'cat' => $photos_cat->cat_ID));
        if($q7->have_posts()) : while($q7->have_posts()) : $q7->the_post(); ?>
          <li>
            <?php Harbinger::template('slide-over', array('wrapper' => '-micro', 'image_size' => 'thumbnail')); ?>
          </li>
        <?php endwhile; endif; wp_reset_postdata(); ?>
      </ul>
    </aside>
  </div>
</section>

<?php
get_footer(); ?>
