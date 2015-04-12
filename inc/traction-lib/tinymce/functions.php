<?php

/**
* Add buttons and blocks to the editor for quick shortcode implementation
* @package Traction
* @subpackage TractionTinyMCE
*/
class TractionTinyMCE {

	public function __construct(){

		// init process for button control
		add_filter( 'tiny_mce_version', array($this, 'refresh_mce') );
		add_action('init', array($this, 'add_tiny_buttons') );
		//add_filter( 'mce_buttons_2', array($this, 'remove_fontsize_buttons') );
		add_filter( 'tiny_mce_before_init', array($this, 'style_blocks') );

	}

	public function add_tiny_buttons() {

		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

	 	// Add only in Rich Editor mode
	 	if ( get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'add_tiny_plugin') );
			add_filter('mce_buttons', array($this, 'register_tiny_buttons') );
	 	}
	}

	//Register the buttons
	public function register_tiny_buttons($buttons) {

		array_push($buttons, '', 'text');
		array_push($buttons, '', 'tabs');
		array_push($buttons, '', 'sibling');
		return $buttons;

	}

	// Load the TinyMCE plugin
	public function add_tiny_plugin($plugin_array) {

		$plugin_array['text'] = get_template_directory_uri().'/inc/traction-lib/tinymce/editor_plugin.js';
		$plugin_array['tabs'] = get_template_directory_uri().'/inc/traction-lib/tinymce/editor_plugin.js';
		$plugin_array['sibling'] = get_template_directory_uri().'/inc/traction-lib/tinymce/editor_plugin.js';
		return $plugin_array;

	}

	//Update tinymce version
	public function refresh_mce($ver) {

		$ver += 3;
		return $ver;

	}

	//Remove font size buttons - must uncomment the add_filter for this to work
	public function remove_fontsize_buttons( $buttons ) {

		array_unshift($buttons, 'fontsizeselect');
		array_unshift( $buttons, 'styleselect' );
		return $buttons;

	}

	//Add block headings to the style dropdown menu
	public function style_blocks( $settings ) {

		$settings['theme_advanced_blockformats'] = 'p,h1,h2,h3';
		$style_formats = array(
			array( 'title' => __('Heading', 'trwp'),		'block'		=>	'h1',	'classes' => '' ),
			array( 'title' => __('Subhead', 'trwp'),		'block'		=>	'h2',	'classes' => '' ),
			array( 'title' => __('Minor Head', 'trwp'),		'block'		=>	'h3',	'classes' => '' ),
			array( 'title' => __('Highlights', 'trwp') ),
			array( 'title' => __('Button', 'trwp'),			'inline'	=> 'span',	'classes' => 'button' ),
			array( 'title' => __('Aside Box', 'trwp'),		'block'		=> 'aside',	'classes' => 'inline-text' ),
			array( 'title' => __('Columns', 'trwp') ),
			array( 'title' => __('New Row', 'trwp'),			'block'	=> 'div',	'classes' => 'row clearfix' ),
			array( 'title' => '&frac12; '.__('Column', 'trwp'),	'block'	=> 'div',	'classes' => 'small-6 large-6 columns' ),
			array( 'title' => '&frac13; '.__('Column', 'trwp'),	'block'	=> 'div',	'classes' => 'large-4 columns' ),
			array( 'title' => '&frac14; '.__('Column', 'trwp'),	'block'	=> 'div',	'classes' => 'large-3 columns' ),
			array( 'title' => '&frac16; '.__('Column', 'trwp'),	'block'	=> 'div',	'classes' => 'large-2 columns' ),
		);
		$settings['style_formats'] = json_encode( $style_formats );
		return $settings;

	}
}

new TractionTinyMCE;
?>
