<?php
function register_custom_menu() {
  register_nav_menu('footer_menu', __('Footer Links'));
  register_nav_menu('primary_menu', __('Main Navigation'));
  register_nav_menu('mobile_menu', __('Mobile Navigation'));
}
add_action( 'init', 'register_custom_menu' );

register_sidebar(array(
  'name'      =>  __( 'Sidebar' ),
  'id'      => 'main-sidebar',
  'description' =>  __( 'The right sidebar appearing on single and archive pages' ),
  'before_widget' =>  '<div class="widget %2$s" id="%1$s">',
  'after_widget'  =>  '</div></div>',
  'before_title'  =>  '<h3><span>',
  'after_title' =>  '</span></h3><div class="widget-content">'
));

if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : endif;
