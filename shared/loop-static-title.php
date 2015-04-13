<div class="slide slide-title active <?php echo $args['wrapper']; ?>">
  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
    <?php Traction::get_image($args['image_size'], array('link_to_post' => false)); ?>
    <div class="slide-cover">
      <?php the_title(); ?>
    </div>
  </a>
</div>
