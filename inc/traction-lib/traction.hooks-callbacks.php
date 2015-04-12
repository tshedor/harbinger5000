<?php

add_editor_style('style.css');
add_post_type_support( 'page', 'excerpt' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-formats', array('gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'aside') );
add_theme_support( 'post-thumbnails' );

/*
* TODO - add internationalization to app
function traction_theme_localization(){
    load_theme_textdomain('trwp', get_template_directory().'/inc/lang');
}
add_action('after_setup_theme', 'traction_theme_localization');
*/

function traction_enqueue_scripts_and_styles(){
	global $a;
	wp_enqueue_script('jquery');

	if($a['use_traction_plugins']){
		/**
		* Plugins include
		* bxSlider (Slider)
		* jQuery Easing w/ compatibility (friend to many jQuery plugins)
		* SuperFish (dropdown nav helper)
		* hoverIntent (dependency of superfish and friend to many other jQuery plugins)
		* Modernizr (feature detection)
		*/
		wp_register_script('traction-plugins', get_template_directory_uri().'/inc/traction-lib/plugins.min.js', array('jquery'), '', true);
		wp_register_script('traction-lib', get_template_directory_uri().'/inc/traction-lib/traction-lib.min.js', array('jquery', 'traction-plugins'), '', true);

		wp_enqueue_script('traction-plugins');
		wp_enqueue_script('traction-lib');
	}

	//Lightbox
	if($a['magnific_lb']){
		wp_register_script('magnific', get_template_directory_uri().'/inc/traction-lib/libs/magnific/magnific.min.js', array('jquery'), '', true);
		wp_register_script('magnific-traction', get_template_directory_uri().'/inc/traction-lib/libs/magnific/magnific-custom.js', array('jquery', 'magnific'), '', true);

		wp_register_style('magnific-style', get_template_directory_uri().'/inc/traction-lib/libs/magnific/magnific-custom.css', array('theme-style'));

		wp_enqueue_script('magnific');
		wp_enqueue_script('magnific-traction');

		wp_enqueue_style('magnific-style');
	}

	switch($a['share_icon_style']) :
		case 'round':
			$socialStyle = 'Circle';
		break;
		case 'square' :
			$socialStyle = 'Square';
		break;
		default :
			$socialStyle = 'Regular';
	endswitch;

	//Social set
	wp_register_style('traction-social-icons', get_template_directory_uri().'/inc/traction-lib/fonts/CGSocial' . $socialStyle . '/style.css');
	//General icons
	wp_register_style('traction-icons', get_template_directory_uri().'/inc/traction-lib/fonts/CG/style.css');

	//CSS reset and grid
	wp_register_style('foundation', get_template_directory_uri().'/inc/traction-lib/foundation.min.css');
	//Theme stylesheet
	wp_register_style('theme-style', get_template_directory_uri().'/style.css', array('foundation'));

	wp_enqueue_style('traction-icons');
	wp_enqueue_style('traction-social-icons');

	wp_enqueue_style('foundation');
	wp_enqueue_style('theme-style');

}

//For the options pages and some of the TractionInput fields, JS and CSS magic is required
function traction_admin_enqueue_scripts(){
	global $a;
	wp_register_script('traction-meta', get_template_directory_uri().'/inc/traction-lib/traction-meta.js', array('jquery','media-upload','thickbox'));

	wp_register_script('jquery-validate', get_template_directory_uri().'/inc/traction-lib/libs/jquery.validate.js', array('jquery'));

	wp_register_style('traction-admin-style', get_template_directory_uri().'/inc/traction-lib/traction-admin-style.css');

	switch($a['share_icon_style']) :
		case 'round':
			$socialStyle = 'Circle';
		break;
		case 'square' :
			$socialStyle = 'Square';
		break;
		default :
			$socialStyle = 'Regular';
	endswitch;

	//General icons
	wp_register_style('traction-icons', get_template_directory_uri().'/inc/traction-lib/fonts/CG/style.css');

	//Social set
	wp_register_style('traction-social-icons', get_template_directory_uri().'/inc/traction-lib/fonts/CGSocial' . $socialStyle . '/style.css');

	wp_enqueue_style('traction-icons');
	wp_enqueue_style('traction-social-icons');
	wp_enqueue_style('traction-admin-style');

	wp_enqueue_script('traction-meta');
   	wp_enqueue_script('jquery');
   	wp_enqueue_script('jquery-ui-sortable');
   	wp_enqueue_script('jquery-ui-datepicker');

}

//For front-end work on the theme, load in the normal helpers
add_action( 'wp_enqueue_scripts', 'traction_enqueue_scripts_and_styles');
//Admin helpers
add_action( 'admin_enqueue_scripts', 'traction_admin_enqueue_scripts');

if(function_exists('get_the_image')){
	function new_get_the_image_args($args){
		$args['meta_key_save'] = true;
		$args['image_scan'] = true;
		return $args;
	}
//	add_filter('get_the_image', 'new_get_the_image_args');
}

//Comment function callback
if(!function_exists('traction_comment')) :
function traction_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e('Pingback', 'trwp'); ?>: <?php comment_author_link(); ?> <?php edit_comment_link( '(Edit)', '<span class="edit-link">', '</span>' ); ?></p>
	<?php
		break;
		default :
		global $post;
	?>
	<li <?php comment_class('row clearfix'); ?> id="comment-<?php comment_ID(); ?>">
		<div class="large-2 small-4 columns comment-meta comment-author">
 			<?php echo get_avatar($comment, 60 ); ?>
			<a href="<?php the_permalink(); ?>#comment-<?php comment_ID(); ?>" title="<?php _e('Link to this comment', 'trwp'); ?>">
 				<?php echo get_comment_time('M. j, Y'); ?>
 			</a>
			<span class="edit"><?php edit_comment_link( __('Edit', 'trwp')); ?></span>
 		</div>
 		<div class="large-10 small-8 columns">
 			<h4><?php comment_author_link();
 				if($comment->user_id === $post->post_author)
					echo ' <span class="author">'.__('Author','trwp').'</span>';
 			?>
 			<span class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __('Reply','trwp'), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
 			</span></h4>
 			<?php if ('0' == $comment->comment_approved)
			 	echo '<p class="comment-awaiting-moderation">'.__('Comment awaiting moderation', 'trwp').'</p>'; ?>
 			<div class="comment-content">
 				<?php comment_text(); ?>
 			</div>
 		</div>

<?php break; endswitch;
} endif; ?>