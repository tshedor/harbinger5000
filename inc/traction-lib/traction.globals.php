<?php
$a = get_option('traction_admin_options');

/**
* Inject custom HTML into WordPress header and footer
* @package Traction
* @subpackage TractionHTMLHooks
*/
class TractionHTMLHooks {
	public function __construct(){
		add_filter( 'wp_footer' , array($this, 'custom_footer') );
		add_filter( 'wp_head' , array($this, 'custom_header') );
	}

	public function custom_footer() {
		global $a;
		echo '<div class="ie-warning warning notice">
		<div class="row clearfix">
			<div class="large-12 columns">
				<i class="icon-alert"></i> <strong>'.__('Internet Explorer User', 'trwp').'</strong>: '.__('You are using an outdated and insecure browser. In order to enjoy all the features of ', 'trwp').get_bloginfo('name').', please <a href="http://browsehappy.com/" title="'.__('Use an alternative browser', 'trwp').'">'.__('use an alternative browser', 'trwp').'</a>, '.__('install', 'trwp').' <a href="http://www.google.com/chromeframe/eula.html?prefersystemlevel=true" title="'.__('Google Chrome Frame', 'trwp').'">'.__('Google Chrome Frame', 'trwp').'</a>'.__(', or learn why ', 'trwp').'<a href="http://www.smashingmagazine.com/2012/07/10/dear-web-user-please-upgrade-your-browser/" title="'.__('Dear Web User: Please Upgrade Your Browser', 'trwp').'">'.__('using an updated browser is important', 'trwp').'</a>.
			</div>
		</div>
		<noscript>
			<div class="warning alert">
				<div class="row">
					<div class="large-12 columns">
						<i class="icon-alert"></i> <strong>'.__('Javascript Disabled', 'trwp').'</strong>'.__(': Your Javascript is disabled. In order to enjoy all the features of ', 'trwp').get_bloginfo('url').', <a href="http://enable-javascript.com/" title="'.__('Enable Javascript', 'trwp').'">'.__('please enable Javascript', 'trwp').'</a> '.__('in your browser\'s settings', 'trwp').'.
					</div>
				</div>
			</div>
		</noscript>
		<!--[if lt IE 9]>
			<script src="'.get_template_directory_uri().'/inc/traction-lib/libs/compatibility/html5.js"></script>
			<script type="text/javascript" src="'.get_template_directory_uri().'/inc/traction-lib/libs/compatibility/respond.min.js"></script>
			<script src="'.get_template_directory_uri().'/inc/traction-lib/libs/compatibility/IE9.js"></script>
			<script src="'.get_template_directory_uri().'/inc/traction-lib/libs/compatibility/jquery.1.9.1.min.js" type="text/javascript"></script>
		<![endif]-->';
	}

	public function custom_header() {
		global $a;
		if(is_singular()){
			echo '<meta property="og:title" content="'.get_the_title().'" />';
			echo '<meta property="og:url" content="'.get_permalink().'" />';
			echo '<meta property="og:description" content="'.get_the_excerpt().'" />';
			echo '<meta property="og:type" content="article" />';
			if(get_option('thread_comments') && comments_open())
				wp_enqueue_script( 'comment-reply' );
		} elseif((is_home()) || (is_archive())){
			echo '<meta property="og:title" content="'.get_bloginfo('name').'" />';
			echo '<meta property="og:url" content="'.get_bloginfo('url').'" />';
			echo '<meta property="og:description" content="'.get_bloginfo('description').'" />';
			echo '<meta property="og:type" content="website" />';
		}
		echo '<meta property="og:site_name" content="'.get_bloginfo('name').'" />';
		echo '<meta name="copyright" content="'.date('Y').' '.get_bloginfo('name').'"><link type="text/plain" rel="author" href="'.get_template_directory_uri().'	/humans.txt" />';
		echo '<meta http-equiv="Content-Type" content="'.get_bloginfo('html_type').'; charset='.get_bloginfo('charset').'" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><meta name="description" content="'.get_bloginfo('description').'" /><meta name="robots" content="index,follow" />

		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="'.get_bloginfo('rss2_url').'" /><link rel="alternate" type="text/xml" title="RSS .92" href="'.get_bloginfo('rss_url').'" /><link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="'.get_bloginfo('atom_url').'" /><link rel="pingback" href="'.get_bloginfo('pingback_url').'" />';
		if(!empty($a['favicon']))
			echo '<link rel="icon" type="image/x-icon" href="'.$a['favicon'].'" />';

		echo '<style type="text/css">'.stripslashes($a['custom_css']).'</style>';
		if($a['do_not_track_users']){
			if(!is_user_logged_in())
				echo stripslashes($a['analytics_code']);
		} else {
			echo stripslashes($a['analytics_code']);
		}
		if($a['maintenance_mode'] && !is_user_logged_in()) echo '<style type="text/css">div,hide-for-small,.home .header {display:none!important; }</style><article class="traction-maintenance-mode gradient">'.get_bloginfo('name').'<p class="coming-soon">'.__('Coming Soon', 'trwp').'</p></article>';
	}
}