<?php

function sponsor_register() {
  $labels = array(
    'name'              =>  _x('Sponsors', 'post type general name'),
    'singular_name'     =>  _x('Sponsor', 'post type singular name'),
    'search_items'      =>  __('Search Sponsors', 'trwp'),
    'not_found'         =>  __('Nothing found', 'trwp'),
    'parent_item_colon' =>  ''
  );

  $args = array(
    'labels'              =>  $labels,
    'public'              =>  true,
    'publicly_queryable'  =>  false,
    'show_ui'             =>  true,
    'query_var'           =>  true,
    'rewrite'             =>  true,
    'capability_type'     =>  'post',
    'hierarchical'        =>  false,
    'supports'            =>  array('title', 'revisions', 'thumbnail', 'author')
  );

  //Pull it together
  register_post_type( 'sponsor' , $args );
  flush_rewrite_rules();
}

add_action('init', 'sponsor_register');

?>
