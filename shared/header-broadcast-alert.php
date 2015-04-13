<?php
$broadcast_query = new WP_Query( array('showposts' => 1, 'cat' => $a['broadcast_category'], 'year' => date('Y'), 'monthnum' => date('m'), 'day' => date('d') ) );

if($broadcast_query->have_posts()) :
  while($broadcast_query->have_posts()) :
    $pc = get_post_custom(); ?>

    <div class="alert info">
      <div class="row clearfix">
        <div class="large-12 columns">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php if( isset($pc['start_hour']) && isset($pc['end_hour']) && (intval($pc['start_hour']) >= date('G')) && (intval($pc['end_hour']) <= date('G'))) { ?>
              LIVE - <?php the_title(); ?>
            <?php } else { ?>
              Broadcasting today - <?php the_title(); ?>
            <?php } ?>
          </a>
        </div>
      </div>
    </div>

  <?php endwhile;
endif; wp_reset_postdata(); ?>
