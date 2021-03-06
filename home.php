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
  <div class="large-6 medium-6 columns">
    <h2><span>Popular</span></h2>
    <div class="large-first">
      <?php
      $q2 = Traction::queryPopular(4);
      if($q2->have_posts()) : while($q2->have_posts()) : $q2->the_post(); ?>
        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h5>
        <div class="byline">By <?php harbinger_authors(); ?></div>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </div>
  </div>
  <div class="large-6 medium-6 columns">
    <a href="https://smeharbinger.net/category/video/"><h2><span>Video</span></h2></a>
    <?php echo stripslashes( $a['home_latest_video'] ); ?>
  </div>
</section>

<section class="row clearfix">
  <div class="large-6 medium-6 columns">
    <?php $r3c1c = get_category($a['home_row_3_column_1']); ?>
    <h2>
      <a href="<?php echo get_category_link($r3c1c->cat_ID); ?>" title="<?php echo $r3c1c->name; ?>">
        <span><?php echo $r3c1c->name; ?></span>
      </a>
    </h2>
    <ul class="simple-slider bx-slider">
      <?php $r3c1q = new WP_Query(array('showposts' => 3, 'cat' => $r3c1c->cat_ID));
      if($r3c1q->have_posts()) : while($r3c1q->have_posts()) : $r3c1q->the_post(); ?>
        <li>
          <?php Harbinger::template('slide-over', array('wrapper' => '-micro', 'image_size' => 'large')); ?>
        </li>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </ul>
  </div>
  <div class="large-6 medium-6 columns">
    <?php if ( is_active_sidebar( 'home-row-2-sidebar' ) ) :
      dynamic_sidebar('home-row-2-sidebar');
    endif; ?>
  </div>
</section>

<section class="row clearfix">
  <?php for($c = 1; $c < 4; $c++) : ?>
    <div class="large-4 medium-4 columns">
      <?php $r4c = get_category($a["home_row_4_column_$c"]); ?>
      <h2>
        <a href="<?php echo get_category_link($r4c->cat_ID); ?>" title="<?php echo $r4c->name; ?>">
          <span><?php echo $r4c->name; ?></span>
        </a>
      </h2>
      <?php
      $r4q1 = new WP_Query(array('showposts' => 1, 'cat' => $r4c->cat_ID));
      if($r4q1->have_posts()) : while($r4q1->have_posts()) : $r4q1->the_post();
        Harbinger::template('static-title',
          array('image_size' => 'medium')
        );
      endwhile; endif; wp_reset_postdata(); ?>
      <?php
      $r4q2 = new WP_Query(array('showposts' => 3, 'cat' => $r4c->cat_ID));
      if($r4q2->have_posts()) : while($r4q2->have_posts()) : $r4q2->the_post(); ?>
        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h5>
        <div class="byline">By <?php harbinger_authors(); ?></div>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </div>
  <?php endfor; ?>
</section>

<section class="row clearfix">
  <div class="large-6 medium-6 columns">
    <?php $r5c1c = get_category($a['home_row_5_column_1']); ?>
    <h2>
      <a href="<?php echo get_category_link($r5c1c->cat_ID); ?>" title="<?php echo $r5c1c->name; ?>">
        <span><?php echo $r5c1c->name; ?></span>
      </a>
    </h2>
    <div class="four-up">
      <?php
      $r5c1q = new WP_Query(array('showposts' => 4, 'cat' => $r5c1c->cat_ID));
      if($r5c1q->have_posts()) : while($r5c1q->have_posts()) : $r5c1q->the_post();
        Harbinger::template('slide-over',
          array('image_size' => 'medium')
        );
      endwhile; endif; wp_reset_postdata(); ?>
    </div>
  </div>

  <div class="large-6 medium-6 columns">
    <h2><span>Scorestream</span></h2>
    <?php echo stripslashes( $a['home_scorestream'] ); ?>
  </div>
</section>

<div class="row clearfix">
  <div class="large-12 columns">
    <?php $r6c = get_category($a['home_row_6']); ?>
    <h2>
      <a href="<?php echo get_category_link($r6c->cat_ID); ?>" title="<?php echo $r6c->name; ?>">
        <span><?php echo $r6c->name; ?></span>
      </a>
    </h2>
  </div>
</div>
<section class="row clearfix">
  <?php $r6q = new WP_Query(array('showposts' => 4, 'cat' => $r6c->cat_ID));
    if($r6q->have_posts()) : while($r6q->have_posts()) : $r6q->the_post(); ?>
    <div class="large-3 medium-6 columns">
      <?php Traction::get_image('medium'); ?>
      <h5>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php the_title(); ?>
        </a>
      </h5>
    </div>
  <?php endwhile; endif; wp_reset_postdata(); ?>
</section>


<div class="row clearfix">
  <div class="large-12 columns">
    <?php $r7c = get_category($a['home_row_7']); ?>
    <h2>
      <a href="<?php echo get_category_link($r7c->cat_ID); ?>" title="<?php echo $r7c->name; ?>">
        <span><?php echo $r7c->name; ?></span>
      </a>
    </h2>
  </div>
</div>
<section class="row clearfix">
  <?php $r7q = new WP_Query(array('showposts' => 4, 'cat' => $r7c->cat_ID));
    if($r7q->have_posts()) : while($r7q->have_posts()) : $r7q->the_post(); ?>
    <div class="large-3 medium-6 columns">
      <?php Traction::get_image('medium'); ?>
      <h5>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php the_title(); ?>
        </a>
      </h5>
    </div>
  <?php endwhile; endif; wp_reset_postdata(); ?>
</section>

<section class="row clearfix">
  <div class="large-6 medium-6 columns">
    <h2><span>Sponsors</span></h2>
    <ul class="simple-slider bx-slider">
      <?php $q6 = new WP_Query(array('showposts' => $a['home_sponsor_count'], 'post_type' => 'sponsor', 'offset' => 1));
      if($q6->have_posts()) : while($q6->have_posts()) : $q6->the_post(); ?>
        <li>
          <?php Harbinger::template('slide-over', array('wrapper' => '-micro', 'image_size' => 'large')); ?>
        </li>
      <?php endwhile; endif; wp_reset_postdata(); ?>
    </ul>
  </div>

  <div class="large-6 medium-6 columns">
    <a href="https://issuu.com/smeharbinger"><h2><span>Our Latest Issue</span></h2></a>
    <?php echo stripslashes( $a['home_latest_issue'] ); ?>
  </div>
</section>

<?php
get_footer(); ?>
