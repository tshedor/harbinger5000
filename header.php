<!DOCTYPE html>
<html <?php language_attributes() ?>>
<!--[if IE 8]>         <html class="ie ie8 lt-ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="ie ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<head>
  <title><?php if(!is_home()) { wp_title(''); echo " | "; } bloginfo('name'); if(is_home()) { echo " | "; bloginfo('description'); } ?></title>
  <?php wp_head(); ?>
  <link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
</head>
<body <?php body_class(); ?>>
  <?php global $a; ?>
  <?php $broadcast_query = new WP_Query( array('showposts' => 1, 'cat' => $a['broadcast_category'], 'year' => date('Y'), 'monthnum' => date('m'), 'day' => date('d') ) );

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
  endif; wp_reset_postdata();

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

  <div class="row clearfix">
    <div class="large-12 columns">
      <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); echo ' | '; bloginfo('description'); ?>" class="logo-wrapper">
        <img src="<?php echo get_template_directory_uri(); ?>/img/masthead-graphic.png" alt="<?php bloginfo('name'); ?>" class="masthead-graphic" />
        <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="<?php bloginfo('name'); ?>" class="logo" />
      </a>
    </div>
  </div>
  <div class="row clearfix">
    <div class="large-12 columns">

      <div class="header-menu">
        <div class="row clearfix header-menu collapse">
          <div class="large-11 columns">
            <?php wp_nav_menu(array( 'theme_location' => 'primary_menu', 'container' => '', 'items_wrap' => '<ul class="link-list sf-menu menu">%3$s</ul>', )); ?>
          </div>
          <div class="large-1 columns text-right">
            <i class="icon-search search-trigger js-search-trigger"></i>
          </div>
          <?php get_search_form(); ?>
        </div>
      </div>
    </div>
  </div>
