<?php
  $pc = get_post_custom();
  if(isset($pc['post_layout'])) :
    switch ($pc['post_layout'][0]) {
      case 'left-sidebar':
        get_template_part('shared/single/left-sidebar');

      break;
      case 'right-sidebar' :
        get_template_part('shared/single/right-sidebar');

      break;
      case 'full-width' :
        get_template_part('shared/single/full-width');

      break;
      default :
        get_template_part('shared/single/basic-right');

        break;
    }

  else :
    get_template_part('shared/single/basic-right');

  endif;

  Traction::setPostViews(get_the_ID());
