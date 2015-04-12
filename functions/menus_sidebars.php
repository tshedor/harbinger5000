<?php
function register_custom_menu() {
  register_nav_menu('footer_menu', __('Footer Links'));
  register_nav_menu('primary_menu', __('Main Navigation'));
  register_nav_menu('mobile_menu', __('Mobile Navigation'));
}
add_action( 'init', 'register_custom_menu' );

register_sidebar(array(
  'name'      =>  __( 'Sidebar' ),
  'id'      => 'main_sidebar',
  'description' =>  __( 'The right sidebar appearing on single and archive pages' ),
  'before_widget' =>  '<div class="widget %2$s" id="%1$s">',
  'after_widget'  =>  '</div>',
  'before_title'  =>  '<h4 class="text-center">',
  'after_title' =>  '</h4>'
));

register_sidebar(array(
  'name'      =>  __( 'Left Single Sidebar' ),
  'id'      => 'single_left',
  'description' =>  __( 'The left sidebar appearing on single pages' ),
  'before_widget' =>  '<div class="widget %2$s" id="%1$s">',
  'after_widget'  =>  '</div>',
  'before_title'  =>  '<h4 class="text-center">',
  'after_title' =>  '</h4>'
));

if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : endif;
