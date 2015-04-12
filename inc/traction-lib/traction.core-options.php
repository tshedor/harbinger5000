<?php

$social = array(
array(
	'name'	=>	__('Social', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'separate',
	'std'	=>	'',
	'type'	=>	'separate'
),
array(
	'name'	=>	__('Twitter', 'trwp'),
	'desc'	=>	__('Twitter Profile. No @ sign.', 'trwp'),
	'id'	=>	'twitter_profile',
	'std'	=>	'tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Facebook Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'facebook_profile',
	'std'	=>	'http://facebook.com/tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Pinterest Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'pinterest_profile',
	'std'	=>	'http://pinterest.com/tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Instagram Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'instagram_profile',
	'std'	=>	'http://instagram.com/tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Flickr Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'flickr_profile',
	'std'	=>	'http://www.flickr.com/photos/tshedor/',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('LinkedIn Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'linkedin_profile',
	'std'	=>	'http://www.linkedin.com/profile/view?id=76301105&trk=hb_tab_pro_top',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('YouTube Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'youtube_profile',
	'std'	=>	'',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Vimeo Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'vimeo_profile',
	'std'	=>	'',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Dribbble Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'dribbble_profile',
	'std'	=>	'http://dribbble.com/tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Foursquare Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'foursquare_profile',
	'std'	=>	'https://foursquare.com/user/18477585',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Google+ Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'google-plus_profile',
	'std'	=>	'https://plus.google.com/u/0/115986805136940069805/posts',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Github Profile', 'trwp'),
	'desc'	=>	__('Include for display in header', 'trwp'),
	'id'	=>	'github_profile',
	'std'	=>	'http://github.com/tshedor',
	'class'	=>	'third',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Show RSS in header', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'feed_profile',
	'std'	=>	'',
	'class'	=>	'third',
	'type'	=>	'checkbox',
	'def'	=>	false,
),
array(
	'name'	=>	__('Show Email in header', 'trwp'),
	'desc'	=>	__('Uses the email set in General settings', 'trwp'),
	'id'	=>	'mail_profile',
	'std'	=>	'',
	'class'	=>	'third',
	'type'	=>	'checkbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('Show RSS on Archive pages', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'show_rss_on_archive',
	'std'	=>	'',
	'class'	=>	'third',
	'type'	=>	'checkbox',
	'def'	=>	false,
),
array(
	'name'	=>	__('Show social buttons on posts', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'show_social',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox',
	'def'	=>	true,
),
array(
	'name'	=> __('Share icon style', 'trwp'),
	'desc'	=> '',
	'id'	=> 'share_icon_style',
	'std'	=> 'plain',
	'class' => 'half',
	'type'	=> 'radio',
	'options' => array(
		array(
			'name' => 'Plain',
			'id' => 'plain',
		),
		array(
			'name' => 'Round',
			'id' => 'round'
		),
		array(
			'name' => 'Square',
			'id' => 'square'
		),
	)
),
array(
	'name'	=>	'Clear',
	'desc'	=>	'',
	'id'	=>	'clear',
	'std'	=>	'<div class="clear"></div>',
	'type'	=>	'customnotice'
),
array(
	'name'	=>	__('Facebook', 'trwp'),
	'desc'	=>	'facebook',
	'id'	=>	'facebook',
	'std'	=>	'',
	'class'	=>	'',
	'type'	=>	'socialcheckbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('FB Like button', 'trwp'),
	'desc'	=>	'facebook',
	'id'	=>	'fblike',
	'std'	=>	'',
	'class'	=>	'',
	'type'	=>	'socialcheckbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('Twitter', 'trwp'),
	'desc'	=>	'twitter',
	'id'	=>	'twitter',
	'std'	=>	'',
	'type'	=>	'socialcheckbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('Pinterest', 'trwp'),
	'desc'	=>	'pinterest',
	'id'	=>	'pinterest',
	'std'	=>	'',
	'type'	=>	'socialcheckbox',
),
array(
	'name'	=>	__('Google+', 'trwp'),
	'desc'	=>	'google-plus',
	'id'	=>	'google-plus',
	'std'	=>	'',
	'type'	=>	'socialcheckbox',
),
array(
	'name'	=>	__('StumbleUpon', 'trwp'),
	'desc'	=>	'stumbleupon',
	'id'	=>	'stumbleupon',
	'std'	=>	'',
	'type'	=>	'socialcheckbox',
),
array(
	'name'	=>	__('LinkedIn', 'trwp'),
	'desc'	=>	'linkedin',
	'id'	=>	'linkedin',
	'std'	=>	'',
	'type'	=>	'socialcheckbox',
),
array(
	'name'	=>	'End array',
	'desc'	=>	'',
	'id'	=>	'endarray',
	'std'	=>	'',
	'type'	=>	'endarray'
),
);
$sitewide = array(
array(
	'name'	=>	__('Sitewide', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'separate',
	'std'	=>	'',
	'type'	=>	'separate'
),
array(
	'name'	=>	__('Logo URL', 'trwp'),
	'desc'	=>	__('The URL to your logo', 'trwp'),
	'id'	=>	'logo',
	'std'	=>	get_template_directory_uri().'/img/logo.png',
	'type'	=>	'media'
),
array(
	'name'	=>	__('Favicon URL', 'trwp'),
	'desc'	=>	__('The URL to your favicon, like http://example.com/favicon.ico. Should be 32x32.', 'trwp'),
	'id'	=>	'favicon',
	'std'	=>	get_template_directory_uri().'/img/favicon.ico',
	'type'	=>	'media'
),
array(
	'name'	=>	__('Landing/Coming Soon/Splash Page Active', 'trwp'),
	'desc'	=>	__('Show a splash page instead of the actual site unless you\'re logged in', 'trwp'),
	'id'	=>	'maintenance_mode',
	'std'	=>	'',
	'type'	=>	'checkbox'
),
array(
	'name'	=>	__('Show dates', 'trwp'),
	'desc'	=>	__('Have dates appear anywhere arcross the site', 'trwp'),
	'id'	=>	'show_dates',
	'std'	=>	'',
	'type'	=>	'checkbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('Get first post image and set it as the featured image', 'trwp'),
	'desc'	=>	__('In case you don\'t want to use a plugin like Get the Image or similar', 'trwp'),
	'id'	=>	'get_first_image',
	'std'	=>	'',
	'type'	=>	'checkbox',
	'def'	=>	true,
),
array(
	'name'	=>	__('Show breadcrumbs on archive pages', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'breadcrumbs_archive',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox'
),
array(
	'name'	=>	__('Show breadcrumbs on single pages', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'breadcrumbs_single',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox'
),
array(
	'name'	=>	__('Show related posts at the bottom of posts', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'show_related_on_single',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox',
	'def'	=>	true
),
array(
	'name'	=>	__('Show comments on pages', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'commentraction_on_pages',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox'
),
array(
	'name'	=>	__('Use all JS libraries included with theme', 'trwp'),
	'desc'	=>	__('This includes Modernizr, PrismJS, Superfish, bxSlider, hoverIntent and jQuery Easing (with compatibility)', 'trwp'),
	'id'	=>	'use_traction_plugins',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox',
	'def'	=>	true
),
array(
	'name'	=>	__('Use builtin Magnific lightbox for single images and galleries', 'trwp'),
	'desc'	=>	__('In case you don\'t use a plugin like NextGEN, or anyother lightbox plugin', 'trwp'),
	'id'	=>	'magnific_lb',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox',
	'def'	=>	true
),
array(
	'name'	=>	__('Author credit in footer', 'trwp'),
	'desc'	=>	__('Please leave this credit in the footer', 'trwp'),
	'id'	=>	'footer_credit',
	'std'	=>	'',
	'class'	=>	'half',
	'type'	=>	'checkbox',
	'def'	=>	true
),
array(
	'name'	=>	__('Copyright Text', 'trwp'),
	'desc'	=>	__('Defaults to &copy; Copyright {SITE NAME} {CURRENT YEAR}', 'trwp'),
	'id'	=>	'copyright_text',
	'std'	=>	'&copy; ' . get_bloginfo('name') . ' ' . date('Y'),
	'class'	=>	'half',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Default Contact Form Email', 'trwp'),
	'desc'	=>	__('Defaults to the admin email (under Settings > General)', 'trwp'),
	'id'	=>	'contact_form_email',
	'std'	=>	get_option('admin_email'),
	'class'	=>	'clear',
	'type'	=>	'text'
),
array(
	'name'	=>	__('Analytics Code', 'trwp'),
	'desc'	=>	__('Paste the code from Google Analytics or a similar analytics service here if you don\'t use a plugin', 'trwp'),
	'id'	=>	'analytics_code',
	'std'	=>	'',
	'class'	=>	'two-thirds',
	'type'	=>	'textareacode'
),
array(
	'name'	=>	__('Do not track logged in users', 'trwp'),
	'desc'	=>	'',
	'id'	=>	'do_not_track_users',
	'class'	=>	'third',
	'type'	=>	'checkbox',
	'def'	=>	true
),
array(
	'name'	=>	__('Custom CSS', 'trwp'),
	'desc'	=>	__('Override styles with your own code', 'trwp'),
	'id'	=>	'custom_css',
	'std'	=>	'',
	'class'	=>	'clear',
	'type'	=>	'textareacode'
),
array(
	'name'	=>	'End array',
	'desc'	=>	'',
	'id'	=>	'endarray',
	'std'	=>	'',
	'type'	=>	'endarray'
),
);
$traction_options = array_merge($social,$sitewide);