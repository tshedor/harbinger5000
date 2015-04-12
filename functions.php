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
$custom = array();
$homewide = array();
$homewide = array(
  array(
    'name'  =>  'Homepage',
    'id'  =>  'separate',
    'type'  =>  'separate',
  ),
  array(
    'name'  => 'Hero',
    'id'  => 'home_hero',
    'type'  => 'categories',
  ),
  array(
    'type'  => 'endarray',
    'id'  => 'endarray'
  )
);
$traction_options = array_merge($homewide, $traction_options);

include_once(get_template_directory() . '/inc/traction-lib/traction.core.php');
include_once(get_template_directory() . '/functions/menus_sidebars.php');
include_once(get_template_directory() . '/functions/taxonomies.php');
