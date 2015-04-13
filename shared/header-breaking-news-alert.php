<?php
if(!empty($a['breaking_news_post'])) :
  $breaking_query = new WP_Query( array('p' => $a['breaking_news_post']) ); while($breaking_query->have_posts()) : $breaking_query->the_post(); ?>

    <div class="alert error">
      <div class="row clearfix">
        <div class="large-12 columns">
          <a href="<?php the_permalink(); ?>">
            <?php echo $a['breaking_news_text']; ?> <?php the_title(); ?>
          </a>
        </div>
      </div>
    </div>

  <?php endwhile;
endif; wp_reset_postdata(); ?>
