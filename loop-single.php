<div class="row clearfix">
  <div <?php post_class("post entry large-8 large-centered columns") ?>>
    <?php the_content(); ?>
  </div>
</div>
<?php Traction::setPostViews(get_the_ID()); ?>
