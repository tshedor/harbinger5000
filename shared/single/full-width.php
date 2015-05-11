<div class="js-body-image body-image" style="background-image: url('<?php Traction::get_image('full', array('just_url' => true)); ?>');">
  <h1><?php the_title(); ?></h1>
  <h3><?php the_author_posts_link(); ?></h3>
</div>
<div class="post-layout--full">
  <div <?php post_class('entry'); ?>>
    <?php the_content(); ?>
  </div>
  <div class="meta">
    <?php the_category(); ?>
  </div>
</div>
