<?php

/**
* Harbinger functions
* @version 1.0
* @author Tim Shedor
*/
class Harbinger {

  /**
  * Use a template file with variables
  * @param string $template_name name of file (loop- is prepended)
  * @param array $args optional variables to pass to the template
  *** @param string wrapper assigned to the parent element
  *** @param image_size applied to image if available
  **/
  static function template($template_name, $args = array()) {

    $defaults = array(
      'wrapper' => '',
      'image_size'    => 'medium'
    );

    $args = wp_parse_args( $args, $defaults );

    include( get_template_directory() . '/shared/loop-' . $template_name . '.php' );
  }

}
