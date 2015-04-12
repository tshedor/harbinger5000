<?php

/**
* Shortcodes for the Traction library
* @package Traction
* @subpackage TractionShortcodes
*/
class TractionShortcodes {

	public function __construct() {
		add_shortcode( 'inline_text', array($this, 'inline_text') );
		add_shortcode( 'sibling', array($this, 'sibling') );
		add_shortcode( 'tabbed', array($this, 'tabbed') );
		add_shortcode( 'tab', array($this, 'tab') );
		add_shortcode( 'contact', array($this, 'contact') );
		add_shortcode( 'sitemap', array($this, 'sitemap') );
		add_shortcode( 'example', array($this, 'example') );
		add_shortcode( 'label', array($this, 'label') );
		add_shortcode( 'alert', array($this, 'alert') );
	}

	/**
	* Render an in-post sidebar sort of thing
	* @param string title The title of it
	* @param string alignment (left | center | right)
	* @param string text_content the actual copy
	*/
	public function inline_text( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => __('More', 'trwp'),
			'align' => 'left',
			'text_content' => '',
		), $atts ) );
		if(empty($text_content)) $text_content = $content;
		return '
			<aside class="inline-text align'.esc_attr($align).'">
				<h3 class="inline-title"><i class="icon-pencil-2"></i> '.esc_attr($title).'</h3>'
				.$text_content.
			'</aside>';
	}

	/**
	* Show a related post
	* @param int post the post to fetch's ID
	* @param string description any additional info from the retrieved post
	* @param string media if the retrieved post is a media-focused one (video | audio | gallery); autopopulates if post type available
	* @param string alignment (left | center | right)
	*/
	public function sibling( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'post' => '',
			'description' => '',
			'media' => '',
			'align' => 'left',
		), $atts ) );
		if(empty($post)) return;
		if(empty($media)) $media = get_post_format($post);
		switch($media):
			case 'video' :
				$media_title = '<i class="icon-play"></i> Video';
			break; case 'audio' :
				$media_title = '<i class="icon-sound"></i> Audio';
			break; case 'gallery' :
				$media_title = '<i class="icon-pictures"></i> Gallery';
			break; case 'image' :
				$media_title = '<i class="icon-pictures"></i> Image';
			break; default :
				$media_title = '';
		endswitch;
		$p = get_post($post);
		$ct = empty($description) ? $p->post_excerpt : $description;

		return '
			<aside class="sibling align'.esc_attr($align).' bump'.esc_attr($align).'">
				<h3 class="inline-title">'.$media_title.'</h3><a href="'.get_permalink($post).'" title="'.$p->post_title.'"><h3 class="inline-title">'.$p->post_title.'</h3></a>'.$ct.'</aside>';
	}

	/**
	* A tabbed system
	* @param string type (vertical | horizontal); defaults vertical
	*/
	public function tabbed( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => 'vertical',
		), $atts ) );
		$tabContainer = '<div class="tab-container '.esc_attr($type).' clearfix">
			<ul class="tab-navigation"></ul>
			'.do_shortcode($content).'
		</div>';
		return $tabContainer;
	}

	/**
	* The actual tab in the system
	* @param string name the tab title
	* @subpackage tabbed shortcode
	*/
	public function tab( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'name' => '',
		), $atts ) );
		$clean_name = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $name), '_'));
		$tab = '<div class="tab" id="'.$clean_name.'" title="'.str_replace('"', "'", $name).'">'.$content.'</div>';
		return $tab;
	}

	/**
	* Contact form
	* @param string email email to send it to
	* @param string label the title above the form
	*/
	public function contact( $atts, $content = null ) {
		global $a;
		extract( shortcode_atts( array(
			'email' =>	'',
			'label'	=>	__('Get in touch', 'trwp'),
		), $atts ) );
		$contactme = '';

		if(!empty($email))
			$email = empty($a['contact_form_email']) ? get_option('admin_email') : $a['contact_form_email'];

		if(isset($_POST['traction_contact'])){
			wp_mail($email, __('Contact Form from ', 'trwp').$_POST['traction_name'], esc_attr(strip_tags($_POST['traction_message'])));
			$contactme .= '<div class="notice success">Thank you for your message</div>';
		}
		$contactme .= '<h3>'.$label.'</h3>';
		$contactme .= '<form method="post" class="traction-contact">
			<input type="text" placeholder="'.__('Name', 'trwp').'" name="traction_name"/>
			<input type="text" placeholder="'.__('Email', 'trwp').'" name="traction_email"/>
			<textarea placeholder="'.__('Your message', 'trwp').'" name="traction_message"></textarea>
			<input type="hidden" name="traction_contact"/>
			<input type="submit" class="button" value="'.__('Contact', 'trwp').'" />
		</form>';
		return $contactme;
	}

	/**
	* Display a sitemap
	* @param boolean pages show pages in the sitemap; default true
	* @param boolean categories show categories in the sitemap; default true
	* @param boolean tags show tags in the sitemap; default true
	*/
	public function sitemap( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'pages' 		=>	'true',
			'categories'	=>	'true',
			'tags'			=>	'true',
		), $atts ) );
		$smarr = array();
		$sitemap = '';
		$pages = filter_var($pages, FILTER_VALIDATE_BOOLEAN);
		$categories = filter_var($categories, FILTER_VALIDATE_BOOLEAN);
		$tags = filter_var($tags, FILTER_VALIDATE_BOOLEAN);
		if($pages)
			array_push($smarr, 'pages');
		if($categories)
			array_push($smarr, 'categories');
		if($tags)
			array_push($smarr, 'tags');
		if(count($smarr) == 3)
			$divclass = '4';
		elseif(count($smarr) == 2)
			$divclass = '6';
		else
			$divclass = '12';
		if($pages){
			$page = get_pages();
			$sitemap .= '<ul class="traction-sitemap no-list large-'.$divclass.' columns"><li>'.__('Pages', 'trwp').'</li>';
			foreach($page as $p){
				$sitemap .= '<li><a href="'.get_page_link($p->ID).'" title="'.$p->post_title.'">'.$p->post_title.'</a></li>';
			}
			$sitemap .= '</ul>';
		}
		if($categories){
			$cat = get_categories();
			$sitemap .= '<ul class="traction-sitemap no-list large-'.$divclass.' columns"><li>'.__('Categories', 'trwp').'</li>';
			foreach($cat as $c){
				$sitemap .= '<li><a href="'.get_category_link($c->term_id).'" title="'.$c->name.'">'.$c->name.'</a></li>';
			}
			$sitemap .= '</ul>';
		}
		if($tags){
			$tag = get_tags();
			$sitemap .= '<ul class="traction-sitemap no-list large-'.$divclass.' columns"><li>'.__('Tags', 'trwp').'</li>';
			foreach($tag as $t){
				$sitemap .= '<li><a href="'.get_tag_link($t->term_id).'" title="'.$t->name.'">'.$t->name.'</a></li>';
			}
			$sitemap .= '</ul>';
		}
		return '<div class="row clear">'.$sitemap.'</div>';
	}

	/**
	* Render a code sample
	* @param boolean just_code only show code; default false
	* @param string lang the coding language (markup | CSS | js | PHP); defaults to markup
	*/
	public function example( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'just_code' => 'false',
			'lang' => 'markup',
		), $atts ) );
		$code = strip_tags($content, '<!doctype><a><p><img><div><body><html><head><script><style><h1><h2><h3><h4><h5><h6><hr><video><audio><ul><ol><li><table><	tbody><tr><td><th><link><strong><em><code><pre><span><embed><b><i><dd><dt><dl><blockquote><header><footer><article><aside><form><input><textarea><	button><section><address><cite><embed><object>');
		$code = str_replace('<', '&lt;', $code);
		$code = '<pre><code class="language-'.$lang.'">'.$code.'</code></pre>';
		$code_and_example = '<div class="large-6 columns code-block">'.$content.'</div><div class="large-6 columns">'.$code.'</div>';
		$just_code = filter_var($just_code, FILTER_VALIDATE_BOOLEAN);
		if($just_code){
			return '<div class="row clearfix md-margin full-code-row">
				<div class="span12 full-code">'.$code.'</div>
			</div>';
		} else {
			return '<div class="row example-row clearfix md-margin">'.$code_and_example.'</div>';
		}
	}

	/**
	* Pill label
	* @param string name the label text; defaults to Heads Up
	* @param string type the label color/priority (tip/info | bonus/success | danger/error);
	*/
	public function label( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'name' => __('Heads Up', 'trwp'),
			'type' => '',
		), $atts ) );
		$wsc = '<span class="label '.esc_attr($type).'">'.esc_attr($name).'</span>';
		if(empty($content))
			return $wsc;
		else
			return '<p>'.$wsc.' '.$content.'</p>';
	}

	/**
	* Alert bar
	* @param string type the label color/priority (tip/info | bonus/success | danger/error);
	*/
	public function alert( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => 'important'
		), $atts ) );
		return '<div class="alert '.esc_attr($type).'">'.esc_attr($content).'</div>';
	}

}

new TractionShortcodes;