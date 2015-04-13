
<article class="row clearfix collapse">
  <div class="tease clearfix">
    <div class="large-4 medium-4 columns">
      <?php Traction::get_image('medium'); ?>
    </div>

    <div class="large-8 medium-8 columns">
      <div class="tease-content">
        <div class="meta">
          <?php the_category(); ?>
        </div>
        <div class="byline"><?php the_author_link(); ?></div>
        <h4>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h4>
      </div>
    </div>
  </div>

</article>
