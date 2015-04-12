<?php

//Set up the Traction default options on theme change
function traction_initialize_options(){
	global $traction_options;
	$a = get_option('traction_admin_options');
	$admin_options = array();
	foreach ($traction_options as $value) {
		if(!isset($a[$value['id']])){
			if(isset($value['def']))
				$admin_options[$value['id']] = $value['def'];
			else
				$admin_options[$value['id']] = false;
		} elseif(!$a[$value['id']]){
			$admin_options[$value['id']] = false;
		} else {
			$admin_options[$value['id']] = $a[$value['id']];
		}
	}
	update_option('traction_admin_options', $admin_options);
}
add_action('after_switch_theme', 'traction_initialize_options');

//Load scripts for the media upload TractionInput function
function traction_admin_meta_scripts(){
	if(function_exists( 'wp_enqueue_media' )){
		wp_enqueue_media();
	} else {

		//For legacy versions of WP
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}
}

//Only need these scripts on the custom page; post and page edit screens have these by default
if (isset($_GET['page']) && $_GET['page'] == 'traction-admin-options.php') {
	add_action('admin_enqueue_scripts', 'traction_admin_meta_scripts');
}


//Link to the options page on the WP bar
function traction_add_option_bar() {
	global $wp_admin_bar, $themename;
	$wp_admin_bar->add_menu( array(
		'parent' => false,
		'id' => 'traction_options',
		'title' => $themename.' '.__('Options', 'trwp'),
		'href' => admin_url( 'themes.php?page=traction-admin-options.php'),
		'meta' => false
	));
}
add_action( 'wp_before_admin_bar_render', 'traction_add_option_bar' );

function traction_admin_area_favicon() {
	global $a;
	echo '<link rel="icon" type="image/x-icon" href="'.$a['favicon'].'" />';
}
add_action('admin_head', 'traction_admin_area_favicon');

function traction_add_admin() {
	global $themename, $traction_options;
	$admin_options = array();
	foreach ($traction_options as $value) {
		$admin_options[$value['id']] = isset($_POST[$value['id']]) ? $_POST[$value['id']] : false;
	}
	$admin_options['has_saved'] = true;
	update_option('traction_admin_options', $admin_options);
	echo '<div id="message" class="updated" style="width:800px; margin-left:0px; padding-right:0px;margin-top:20px"><p><strong>'.__('Settings saved.', 'trwp').'</strong></p></div>';
}

function traction_admin() {
	global $themename, $traction_options;
	if ( isset($_POST['update_themeoptions']) && $_POST['update_themeoptions'] == 'true' ) { traction_add_admin(); }
	if(isset($_REQUEST['action']) && 'reset' == $_REQUEST['action']) {
		$admin_options = array();
		foreach ($traction_options as $value) {
			if(isset($value['def']))
				$admin_options[$value['id']] = $value['def'];
			else
				$admin_options[$value['id']] = false;
		}
		update_option('traction_admin_options', $admin_options);
		echo '<div id="message" class="updated" style="width:800px; margin-left:0px; padding-right:0px;margin-top:20px"><p><strong>'.__('Settings reset', 'trwp').'</strong></p></div>';
	}
	echo '<div class="wrap trao clear clearfix" id="poststuff">
		<h2>'.$themename.' '.__('settings', 'trwp').'</h2>
		<form method="post" novalidate data-validate="parsley">
		<input type="hidden" name="update_themeoptions" value="true" />';
	$globalMeta = get_option('traction_admin_options');

	foreach ($traction_options as $value) {
		$meta = $globalMeta[$value['id']];
		$fieldType = $value['type'];
		$newField = new TractionInput($meta,$value);
		$newField->$fieldType();
	}
		echo '
</div>
<p class="submit">
	<input name="save" type="submit" class="button button-primary button-large" value="'.__('Save changes', 'trwp').'" />
	<input type="hidden" name="action" value="save" />
</p>
</form>
<script type="text/javascript">
function reallyreset(){
var agree=confirm("'.__('Are you sure you want to reset all options?', 'trwp').'");
if (agree)
	return true ;
else
	return false ;
}
</script>
<form method="post">
	<p class="submit"> 	<input name="save" type="submit" class="button" value="'.__('Reset', 'trwp').'" onclick="return reallyreset()" />
<input type="hidden" name="action" value="reset" /></form> </p>
';
}

function traction_themeoptions_admin_menu()  {
	global $themename;
	add_theme_page($themename.' '.__('Options', 'trwp'), $themename.' '.__('Options', 'trwp'), 'edit_themes', basename(__FILE__), 'traction_admin');
}
add_action('admin_menu', 'traction_themeoptions_admin_menu');