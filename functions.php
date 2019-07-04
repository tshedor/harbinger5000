<?php
include_once(get_template_directory().'/inc/traction-lib/traction.core-options.php');

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
  add_image_size( 'hero1', 930, 523.125, true );
  add_image_size( 'archive_hero', 650, 450, true );
  add_image_size( 'skinny_hero1', 650, 240, true );
  add_image_size( 'hero_side', 360, 202.5, true );
}

function custom_scripts(){
  wp_enqueue_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.js', array('jquery') );
	wp_enqueue_script('harbinger', get_template_directory_uri() . '/js/harbinger.js', array('jquery', 'bxslider') );
}

// If Coauthors Plus is installed, use that
function harbinger_authors() {
  if( function_exists('coauthors_posts_links') ) {
    coauthors_posts_links();
  } else {
    the_author_posts_link();
  }
}


add_action( 'wp_enqueue_scripts', 'custom_scripts', 0);

$themename = "Harbinger 5001";

$breaking_opts = array(
  array(
    'name'  =>  'Header Stuff',
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
    'id'    => 'clearfix',
    'type'  => 'clearfix'
  ),
  array(
    'name'  => 'Masthead Graphic',
    'desc'  => 'Nestled next to the logo',
    'id'    => 'masthead_graphic',
    'std'   => get_template_directory_uri() . '/img/masthead-graphic.png',
    'type'  => 'media',
  ),

  array(
    'name'  => 'Masthead Link',
    'desc'  => 'The URL for the masthead graphic',
    'id'    => 'masthead_link',
    'std'   => 'http://smeharbinger.net/category/live-broadcasts/',
    'type'  => 'text',
  ),
  array(
    'type'  => 'endarray',
    'id'    => 'endarray'
  )
);

$homeopts = array(
  array(
    'name'  =>  'Homepage',
    'id'    =>  'separate',
    'type'  =>  'separate',
  ),
  array(
    'name'  => 'Hero',
    'desc'  => 'First box on the home page. Displays 3 of the most recent posts',
    'id'    => 'home_hero',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 1, Box 1',
    'desc'  => 'The first box in the first column',
    'id'    => 'home_supplement_category',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 1, Box 2',
    'desc'  => 'The second box in the first column',
    'id'    => 'home_col_1_box_2',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 1, Box 4',
    'desc'  => 'The fourth box in the first column',
    'id'    => 'home_below_fold_category',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 2, Box 2',
    'desc'  => 'Raw embed code that appears beneath "Popular"',
    'id'    => 'home_subpopular_embed',
    'type'  => 'textareacode',
  ),
  array(
    'name'  => 'Column 2, Box 3',
    'desc'  => 'The third box in the second column',
    'id'    => 'home_col_2_box_3',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 2, Box 4',
    'desc'  => 'The fourth box in the second column',
    'id'    => 'home_col_2_box_4',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Column 2, Box 5',
    'desc'  => 'The fifth box in the second column',
    'id'    => 'home_col_2_box_5',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Sponsor Count',
    'desc'  => 'Number of sponsors to display',
    'id'    => 'home_sponsor_count',
    'std'   => 3,
    'type'  => 'number',
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
    'name'  => 'Photos',
    'id'    => 'photos_category',
    'type'  => 'categories',
  ),
  array(
    'type'  => 'endarray',
    'id'    => 'endarray'
  )
);

$custom = array_merge($breaking_opts, $homeopts, $site_categories);
$traction_options = array_merge($custom, $traction_options);

include_once(get_template_directory() . '/inc/traction-lib/traction.core.php');
include_once(get_template_directory() . '/functions/harbinger.php');
include_once(get_template_directory() . '/functions/menus_sidebars.php');
include_once(get_template_directory() . '/functions/sponsors-pt.php');
include_once(get_template_directory() . '/functions/sponsors-meta.php');
include_once(get_template_directory() . '/functions/taxonomies.php');
