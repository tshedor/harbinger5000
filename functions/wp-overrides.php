<?php
function override_search(){
  update_option('override_custom_search_template', true);
}
add_action('after_setup_theme', 'override_search');

function fix_adminbar_placement() {
  remove_action('wp_head', '_admin_bar_bump_cb');
}
//add_action('get_header', 'fix_adminbar_placement');
