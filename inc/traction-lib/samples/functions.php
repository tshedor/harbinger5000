<?php
//Put this file in the root WP theme directory, like any self-respecting functions.php theme file



//Scripts
function custom_scripts(){
	wp_enqueue_script('isotope', get_template_directory_uri().'/js/jquery.isotope.min.js', array('jquery'));
	wp_enqueue_script('custom-script', get_template_directory_uri().'/js/portfolio.js', array('jquery', 'isotope'));
}
add_action( 'wp_enqueue_scripts', 'custom_scripts', 0);

include_once(get_template_directory().'/inc/traction-lib/traction.core-options.php');
$themename = "Portfolio 2012";
$homewide = array(
array(
	'name'	=>	'Homepage',
	'id'	=>	'separate',
	'type'	=>	'separate',
),
array(
	'name'	=> 'About Image',
	'desc'	=> 'Bottom of the page',
	'std'	=> '',
	'id'	=> 'about_image',
	'type'	=> 'media',
),
array(
	'name'	=> 'About Copy',
	'desc'	=> 'Bottom of the page',
	'std'	=> '',
	'id'	=> 'about_copy',
	'type'	=> 'tinymce',
),
array(
	'name'	=> 'Resume Topic 1',
	'desc'	=> '',
	'std'	=> '',
	'class'	=> 'fourth',
	'id'	=> 'resume_topic_1',
	'type'	=> 'categories',
	'tax'	=> 'resume_categories'
),
array(
	'name'	=> 'Resume Topic 2',
	'desc'	=> '',
	'std'	=> '',
	'class'	=> 'fourth',
	'id'	=> 'resume_topic_2',
	'type'	=> 'categories',
	'tax'	=> 'resume_categories'
),
array(
	'name'	=> 'Resume Topic 3',
	'desc'	=> '',
	'std'	=> '',
	'class'	=> 'fourth',
	'id'	=> 'resume_topic_3',
	'type'	=> 'categories',
	'tax'	=> 'resume_categories'
),
array(
	'name'	=> 'Resume Topic 4',
	'desc'	=> '',
	'std'	=> '',
	'class'	=> 'fourth',
	'id'	=> 'resume_topic_4',
	'type'	=> 'categories',
	'tax'	=> 'resume_categories'
),
array(
	'name'	=> 'Clearfix',
	'id'	=> 'clearfix',
	'type'	=> 'clearfix',
),
array(
	'type'	=> 'endarray',
	'id'	=> 'endarray'
)
);
$traction_options = array_merge($homewide, $traction_options);
function override_search(){
	update_option('override_custom_search_template', true);
}
add_action('after_setup_theme', 'override_search');

//add_action('get_header', 'fix_adminbar_placement');
function fix_adminbar_placement() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}


//Menus
function register_custom_menu() {
	register_nav_menu('footer_menu', __('Footer Links'));
	register_nav_menu('primary_menu', __('Main Navigation'));
	register_nav_menu('mobile_menu', __('Mobile Navigation'));
}
add_action( 'init', 'register_custom_menu' );


//Thumbnails
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'large_thumb', 450, 450, true );
}


//Sidebars
register_sidebar(array(
	'name'			=>	__( 'Sidebar' ),
	'id'			=> 'main_sidebar',
	'description'	=>	__( 'The right sidebar appearing on single and archive pages' ),
	'before_widget'	=>	'<div class="widget %2$s" id="%1$s">',
	'after_widget'	=>	'</div>',
	'before_title'	=>	'<h4 class="text-center">',
	'after_title'	=>	'</h4>'
));
register_sidebar(array(
	'name'			=>	__( 'Left Single Sidebar' ),
	'id'			=> 'single_left',
	'description'	=>	__( 'The left sidebar appearing on single pages' ),
	'before_widget'	=>	'<div class="widget %2$s" id="%1$s">',
	'after_widget'	=>	'</div>',
	'before_title'	=>	'<h4 class="text-center">',
	'after_title'	=>	'</h4>'
));
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : endif;

include_once(get_template_directory().'/inc/traction-lib/traction.core.php');
include_once(get_template_directory().'/functions/resume-pt.php');
include_once(get_template_directory().'/functions/post-meta.php');

function create_taxonomies() {
    register_taxonomy( 'client', 'post', array( 'hierarchical' => false, 'label' => 'Client', 'query_var' => true, 'rewrite' => array('slug' => 'client'), ) );
    register_taxonomy( 'software', 'post', array( 'hierarchical' => false, 'label' => 'Software', 'query_var' => true, 'rewrite' => array('slug' => 'software'), ) );
    register_taxonomy( 'technologies', 'post', array( 'hierarchical' => false, 'label' => 'Technologies', 'query_var' => true, 'rewrite' => array('slug' => 'technologies'), ) );
}
add_action( 'init', 'create_taxonomies', 0 );
