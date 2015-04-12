<?php
include_once(get_template_directory().'/inc/traction-lib/traction.core-options.php');
function example_customizer( $wp_customize ) {
	global $traction_options;
	foreach($traction_options as $ov){
		$wp_customize->add_section(
			$ov['id'],
			array(
				'title'			=>	$ov['name'],
				'description'	=>	$ov['desc'],
				'priority'		=>	$ov['priority'],
			)
		);
		foreach($ov['settings'] as $os){
			if(isset($os['std']))
				$std = $os['std'];
			else
				$std = '';
			$wp_customize->add_setting(
				$os['id'],
				array(
					'default' => $std,
				)
			);
			if($os['type'] == 'image'){
				$wp_customize->add_control(
					new WP_Customize_Image_Control($wp_customize, $os['id'],
						array(
							'label'		=>	$os['name'],
							'section'	=>	$ov['id'],
							'type'		=>	$os['type'],
						)
					)
				);
			} elseif($os['type'] == 'color'){
				$wp_customize->add_control(
					new WP_Customize_Color_Control($wp_customize, $os['id'],
						array(
							'label'		=>	$os['name'],
							'section'	=>	$ov['id'],
							'type'		=>	$os['type'],
						)
					)
				);
			} else {
				$wp_customize->add_control(
					$os['id'],
					array(
						'label'		=>	$os['name'],
						'section'	=>	$ov['id'],
						'type'		=>	$os['type'],
					)
				);
			}
		}
	}
}
add_action( 'customize_register', 'example_customizer' );