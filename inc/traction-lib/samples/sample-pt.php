<?php

function sample_register() {
	$labels = array(
		'name'			=>	_x('Samples', 'post type general name'),
		'singular_name'	=>	_x('Sample', 'post type singular name'),
		'search_items'	=>	__('Search Samples', 'trwp'),
		'not_found'		=>	__('Nothing found', 'trwp'),
		'parent_item_colon'	=>	''
	);

	$args = array(
		'labels'				=>	$labels,
		'public'				=>	true,
		'publicly_queryable'	=>	false,
		'show_ui'				=>	true,
		'query_var'				=>	true,
		'rewrite'				=>	true,
		'capability_type'		=>	'post',
		'hierarchical'			=>	false,
		'supports'				=>	array('title', 'editor', 'revisions', 'thumbnail', 'author')
	);

	//Custom post type columns
	function sample_edit_columns($columns){
		$columns = array(
			"cb"			=>	"<input type=\"checkbox\" />",
			"client"		=>	"Supervisor/Corporation",
			"date_begun"	=>	"Date Begun",
			"date_completed"=>	"Date Completed",
			"position"		=>	"Position Held",
			"description"	=>	"Description",
		);
		return $columns;
	}
	add_filter("manage_edit-sample_columns", "sample_edit_columns");

	//Information to pull for the post type columns
	function sample_custom_columns($column){
		global $post;
		$custom = get_post_custom();
		switch ($column) :
			case "client":
				echo '<a href="'.admin_url().'post.php?post='.get_the_ID().'&action=edit" title="'.get_the_title().'">';
				the_title();
				echo '</a>';
				break;
			case "description":
				echo get_the_content($post->ID);
				break;
			case "date_begun":
				echo $custom["date_begun"][0];
				break;
			case "date_completed":
				if(!empty($custom['date_completed'][0]))
					echo $custom["date_completed"][0];
				break;
			case "position":
				echo $custom["position_held"][0];
				break;
		endswitch;
	};
	add_action("manage_posts_custom_column",	"sample_custom_columns");

	//Pull it together
	register_post_type( 'sample' , $args );
	flush_rewrite_rules();
}
add_action('init', 'sample_register');

register_taxonomy(
	"sample_categories",
	array("sample"),
	array(
		"hierarchical"		=> true,
		"label"				=> "Sample Categories",
		"singular_label"	=> "Sample Category",
		"rewrite"			=> true
	)
);

$sample_meta_fields = array(
array(
	'name'	=> 'Just Text',
	'desc'	=> 'Some text',
	'id'	=> 'enter_text',
	'std'	=> 'Enter text here',
	'type'	=> 'text',
),
array(
	'name'	=> 'Email',
	'id'	=> 'enter_email',
	'type'	=> 'email'
),
array(
	'name'	=> 'Date Completed',
	'desc'	=> 'Required',
	'id'	=> 'date_completed',
	'std'	=> 'August 2012',
	'type'	=> 'text'
),
array(
	'name'	=> 'Appearance',
	'desc'	=> 'General Appearance',
	'id'	=> 'resume_style',
	'type'	=> 'radio',
	'def'	=>	'third_chunk',
	'options' => array(
		array(
			'name' => 'Normal',
			'id' => 'standard_meta',
		),
		array(
			'name' => 'Chunk (1/3)',
			'id' => 'third_chunk'
		),
		array(
			'name' => 'Chunk (1/4)',
			'id' => 'fourth_chunk'
		),
		array(
			'name' => 'Just Text',
			'id' => 'just_text'
		),
	)
),

);

$resume_meta_info = array(
	'title' => 'Sample Box',
	'post_type' => 'sample'
);

new TractionMetaBoxes($sample_meta_fields, $sample_meta_info);

?>