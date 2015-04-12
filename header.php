<!DOCTYPE html>
<html <?php language_attributes() ?>>
<!--[if IE 8]>         <html class="ie ie8 lt-ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="ie ie9 lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<head>
	<title><?php if(!is_home()) { wp_title(''); echo " | "; } bloginfo('name'); if(is_home()) { echo " | "; bloginfo('description'); } ?></title>
	<?php wp_head(); ?>
	<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
</head>
<body <?php body_class(); ?>>
	<div class="row clearfix xlg-padding">
		<div class="large-9 large-centered columns">
			<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); echo ' | '; bloginfo('description'); ?>">
				<?php bloginfo('title'); ?>
			</a>
		</div>
	</div>
