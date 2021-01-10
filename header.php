<!DOCTYPE html>
<html <?php language_attributes() ?>>
<!--[if IE 8]>         <html class="ie ie8 lt-ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="ie ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<head>
  <title><?php if(!is_home()) { wp_title(''); echo " | "; } bloginfo('name'); if(is_home()) { echo " | "; bloginfo('description'); } ?></title>
  <?php wp_head(); ?>
  <link href='//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic|Roboto+Condensed:400,700|Noto+Serif:400,400italic' rel='stylesheet' type='text/css'>
  <?php if(!is_user_logged_in()) { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12879550-1', 'auto');
  ga('send', 'pageview');

</script>
  <?php } ?>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=10.0, user-scalable=1" name="viewport" />
<meta http-equiv="expires" content="14400">
</head>
<body <?php body_class(); ?> onunload="">
  <?php
    global $a;
    include_once(get_template_directory() . '/shared/header-broadcast-alert.php');
    include_once(get_template_directory() . '/shared/header-breaking-news-alert.php');  ?>

<div class="row clearfix header-logo-row">
	<div class="large-10 small-9 columns">
    <a href="http://smeharbinger.net" title="Shawnee Mission East" class="logo-wrapper" target="_self">
      <img src="<?php echo $a['logo']; ?>" alt="<?php bloginfo('name'); ?>" class="logo" />
    </a>
  </div>
  <div class="large-2 small-3 columns">
	  <div class="social-icons">
		 <?php Traction::social_header(); ?>
	  </div>
  </div>
</div>
<div class="row clearfix sticky-nav">
  <div class="large-12 columns">
    <div class="header-menu">
      <div class="row clearfix desktop-menu collapse">
        <div class="large-11 hide-for-small columns">
          <?php wp_nav_menu(array( 'theme_location' => 'primary_menu', 'container' => '', 'items_wrap' => '<ul class="link-list sf-menu menu">%3$s</ul>', )); ?>
        </div>
        <div class="large-1 columns text-right hide-for-small">
          <i class="icon-search search-trigger js-search-trigger"></i>
        </div>
        <?php get_search_form(); ?>
      </div>

      <div class="show-for-small clearfix mobile-menu sm-padding">
        <div class="mobile-search">
          <i class="icon-search search-trigger js-search-trigger"></i>
        </div>
        <a href="#" id="activateMobile"><i class="icon-reorder"></i></a>
        <?php wp_nav_menu(array( 'theme_location' => 'mobile_menu', 'container' => '', 'items_wrap' => '<ul id="mobileMenu" class="clearfix link-list">%3$s</ul>', )); ?>
      </div>
    </div>
  </div>
</div>
