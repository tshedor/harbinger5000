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
    'name'  => 'Logo',
    'desc'  => 'The big masthead image',
    'id'    => 'logo',
    'std'   => get_template_directory_uri() . '/img/masthead-graphic.png',
    'type'  => 'media',
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
    'name'  => 'Latest Video',
    'desc'  => 'Raw embed code from video host site',
    'id'    => 'home_latest_video',
    'type'  => 'textareacode',
  ),
  array(
    'name'  => 'Scorestream',
    'desc'  => 'Raw embed code from Scorestream',
    'id'    => 'home_scorestream',
    'type'  => 'textareacode',
  ),
  array(
    'name'  => 'Row 3, Column 1',
    'desc'  => 'The first column in the third row',
    'id'    => 'home_row_3_column_1',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 4, Column 1',
    'desc'  => 'The first column in the fourth row',
    'id'    => 'home_row_4_column_1',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 4, Column 2',
    'desc'  => 'The second column in the fourth row',
    'id'    => 'home_row_4_column_2',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 4, Column 3',
    'desc'  => 'The third column in the fourth row',
    'id'    => 'home_row_4_column_3',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 5, Column 1',
    'desc'  => 'The first column in the fifth row',
    'id'    => 'home_row_5_column_1',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 6',
    'desc'  => 'The sixth row',
    'id'    => 'home_row_6',
    'type'  => 'categories',
  ),
  array(
    'name'  => 'Row 7',
    'desc'  => 'The seventh row',
    'id'    => 'home_row_7',
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
    'name'  => 'Latest Issue Embed Code',
    'desc'  => 'Raw embed code from Issuu',
    'id'    => 'home_latest_issue',
    'type'  => 'textareacode',
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
