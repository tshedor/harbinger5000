
<article class="row clearfix collapse">
  <div class="tease clearfix">
    <div class="large-4 medium-4 columns">
      <?php Traction::get_image('medium'); ?>
    </div>

    <div class="large-8 medium-8 columns">
      <div class="tease-content">
        <div class="meta">
          <?php the_date('M j, Y'); ?>
        </div>
        <div class="byline">By <?php harbinger_authors(); ?></div>
        <h4>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h4>
      </div>
    </div>
  </div>

</article>
