<?php
$layout_meta_fields = array(
array(
	'name'	=> '',
	'desc'	=> '',
	'id'	=> 'post_layout',
	'std'	=> 'default',
	'def'	=> 'full-width',
	'type'	=> 'radio',
	'options' => array(
		array(
			'name' => __('Basic Right', 'trwp'),
			'id' => 'basic-right',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/basic-right.jpg'
		),
		array(
			'name' => __('Right Sidebar', 'trwp'),
			'id' => 'right-sidebar',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/right-sidebar.jpg'
		),
		array(
			'name' => __('Left Sidebar', 'trwp'),
			'id' => 'left-sidebar',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/left-sidebar.jpg'
		),
		array(
			'name' => __('Full Width', 'trwp'),
			'id' => 'full-width',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/full-width.jpg'
		),
	)
),
);

$layout_meta_information = array(
	'title' => 'Layouts'
);

new TractionMetaBoxes($layout_meta_fields, $layout_meta_information); ?>