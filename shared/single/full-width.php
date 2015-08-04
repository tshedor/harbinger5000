<div class="js-body-image body-image" style="background-image: url('<?php echo Traction::get_image('full', array('just_url' => true)); ?>');">
  <div class="post-titles">
    <h1><?php the_title(); ?></h1>
    <h3>By <?php the_author_posts_link(); ?></h3>
  </div>
</div>
<div class="post-layout--full">
  <div <?php post_class('entry'); ?>>
    <?php the_content(); ?>
  </div>
  <div class="meta">
    <?php the_category(); ?>
  </div>

  <ul class="social-single">
    <?php Traction::social_single(false); ?>
  </ul>
</div>
