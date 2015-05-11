<!DOCTYPE html>
<html <?php language_attributes() ?>>
<!--[if IE 8]>         <html class="ie ie8 lt-ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="ie ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<head>
  <title><?php if(!is_home()) { wp_title(''); echo " | "; } bloginfo('name'); if(is_home()) { echo " | "; bloginfo('description'); } ?></title>
  <?php wp_head(); ?>
  <link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic|Roboto+Condensed:400,700|Noto+Serif:400,400italic' rel='stylesheet' type='text/css'>
</head>
<body <?php body_class(); ?>>
  <?php
    global $a;
    include_once(get_template_directory() . '/shared/header-broadcast-alert.php');
    include_once(get_template_directory() . '/shared/header-breaking-news-alert.php');  ?>

  <div class="row clearfix">
    <div class="large-12 columns">
      <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); echo ' | '; bloginfo('description'); ?>" class="logo-wrapper">
        <img src="<?php echo $a['masthead_graphic']; ?>" alt="<?php bloginfo('name'); ?>" class="masthead-graphic" />
        <img src="<?php echo $a['logo']; ?>" alt="<?php bloginfo('name'); ?>" class="logo" />
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
