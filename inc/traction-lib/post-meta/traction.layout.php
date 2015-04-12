<?php
$layout_meta_fields = array(
array(
	'name'	=> '',
	'desc'	=> '',
	'id'	=> 'post_layout',
	'std'	=> 'default',
	'type'	=> 'radio',
	'options' => array(
		array(
			'label' => __('Right Sidebar', 'trwp'),
			'value' => 'default',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/right-sidebar.png'
		),
		array(
			'label' => __('Left Sidebar', 'trwp'),
			'value' => 'left-sidebar',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/left-sidebar.png'
		),
		array(
			'label' => __('Full Width', 'trwp'),
			'value' => 'full-width',
			'image'	=>	get_template_directory_uri().'/inc/traction-lib/post-meta/full-width.png'
		),
	)
),
);

$layout_meta_information = array(
	'title' => 'Layouts'
);

new TractionMetaBoxes($layout_meta_fields, $layout_meta_information); ?>