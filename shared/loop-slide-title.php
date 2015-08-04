<div class="js-slide-title slide slide-title <?php echo $args['wrapper']; ?>">
  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
    <?php $image_present = Traction::get_image($args['image_size'], array('image_present' => true));
    if($image_present) : ?>
      <?php Traction::get_image($args['image_size'], array('link_to_post' => false)); ?>
      <div class="slide-cover">
        <?php the_title(); ?>
      </div>
    <?php else : ?>
      <h2><?php the_title(); ?></h2>
    <?php endif; ?>
  </a>
</div>
