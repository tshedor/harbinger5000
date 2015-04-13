<?php
include_once(get_template_directory().'/inc/traction-lib/traction.core-options.php');

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
  add_image_size( 'hero', 930, 500, true );
  add_image_size( 'hero_sidekick', 360, 125, true );
}

function custom_scripts(){
	wp_enqueue_script('harbinger', get_template_directory_uri() . '/js/harbinger.js', array('jquery') );
}

add_action( 'wp_enqueue_scripts', 'custom_scripts', 0);

$themename = "Harbinger 5000";
$homeopts = array(
  array(
    'name'  =>  'Homepage',
    'id'    =>  'separate',
    'type'  =>  'separate',
  ),
  array(
    'name'  => 'Hero',
    'desc'  => 'First box on the home page. Displays 4 of the most recent posts',
    'id'    => 'home_hero',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Supplement Box',
    'desc'  => 'The box next to popular after the hero. Displays 5 of the most recent posts',
    'id'    => 'home_supplement_category',
    'type'  => 'categories',
  ),
  array(
    'type'  => 'endarray',
    'id'    => 'endarray'
  )
);

$site_categories = array(
  array(
    'name'  =>  'Site Categories',
    'id'    =>  'separate',
    'type'  =>  'separate',
  ),
  array(
    'name'  => 'Broadcasts',
    'id'    => 'broadcast_category',
    'type'  => 'categories',
  ),
  array(
    'type'  => 'endarray',
    'id'    => 'endarray'
  )
);

$breaking_opts = array(
  array(
    'name'  =>  'Breaking News',
    'id'    =>  'separate',
    'type'  =>  'separate',
  ),
  array(
    'name'  => 'Breaking News Text',
    'desc'  => 'Short description before the breaking news post title',
    'std'   => 'BREAKING: ',
    'id'    => 'breaking_news_text',
    'class' => 'half',
    'type'  => 'text',
  ),
  array(
    'name'  => 'Breaking News Post',
    'desc'  => 'If a news story is breaking news, select it from the list. To remove the alert on the site, set this field to "Select One"',
    'id'    => 'breaking_news_post',
    'class' => 'half',
    'type'  => 'posts',
  ),
  array(
    'type'  => 'endarray',
    'id'    => 'endarray'
  )
);

$custom = array_merge($homeopts, $site_categories, $breaking_opts);
$traction_options = array_merge($custom, $traction_options);

include_once(get_template_directory() . '/inc/traction-lib/traction.core.php');
include_once(get_template_directory() . '/functions/harbinger.php');
include_once(get_template_directory() . '/functions/menus_sidebars.php');
include_once(get_template_directory() . '/functions/taxonomies.php');
