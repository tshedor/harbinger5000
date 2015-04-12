<?php
class Traction_FBLike extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'traction_fblike',
			__('TS Facebook Like Box', 'trwp'),
			array( 'description' => __( 'Show your Facebook Page or Profile feed', 'trwp' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$fblike_profile = $instance['fblike_profile'];
		$fblike_height = $instance['fblike_height'];

		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
			$html = '<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$fblike_profile.'&amp;width=292&amp;height='.$fblike_height.'&amp;show_faces=';
			$html .= $instance['fblike_show_faces'] ? 'true' : 'false';
			$html .= '&amp;colorscheme='.$instance['fblike_color_scheme'].'&amp;stream=';

			$html .= $instance['fblike_show_stream'] ? 'true' : 'false';

			$html .= '&amp;border_color=%23';
			$html .= $instance['fblike_color_scheme'] == 'light' ? 'fff' : '000';
			$html .= '&amp;header=';
			$html .= $instance['fblike_show_header'] ? 'true' : 'false';

			$html .= '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:'.$fblike_height.'px;" allowTransparency="true"></iframe>';
			echo $html;
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fblike_profile'] = strip_tags( $new_instance['fblike_profile'] );
		$instance['fblike_height'] = strip_tags( $new_instance['fblike_height'] );
		$instance['fblike_show_faces'] = strip_tags( $new_instance['fblike_show_faces'] );
		$instance['fblike_color_scheme'] = strip_tags( $new_instance['fblike_color_scheme'] );
		$instance['fblike_show_stream'] = strip_tags( $new_instance['fblike_show_stream'] );
		$instance['fblike_show_header'] = strip_tags( $new_instance['fblike_show_header'] );

		return $instance;
	}

	public function form( $instance ) {
		$title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'Latest from Facebook', 'trwp' );
		$fblike_profile = isset($instance['fblike_profile']) ? $instance['fblike_profile'] : 'platform';
		if(isset($instance['fblike_show_faces']))
			$fblike_show_faces = $instance['fblike_show_faces'];
		$fblike_show_stream = isset($instance['fblike_show_stream']) ? $instance['fblike_show_stream'] : 'on';

		$fblike_height = isset($instance['fblike_height']) ? $instance['fblike_height'] : '350';
		$fblike_color_scheme = isset($instance['fblike_color_scheme']) ? $instance['fblike_color_scheme'] : 'light';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_profile' ); ?>"><?php _e('Facebook Profile', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'fblike_profile' ); ?>" name="<?php echo $this->get_field_name( 'fblike_profile' ); ?>" type="text" value="<?php echo esc_attr( $fblike_profile ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_show_stream' ); ?>">
				<input id="<?php echo $this->get_field_id( 'fblike_show_stream' ); ?>" name="<?php echo $this->get_field_name( 'fblike_show_stream' ); ?>" type="checkbox" <?php if($fblike_show_stream) { echo ' checked '; } ?> />
				<?php _e('Show Stream', 'trwp') ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_show_faces' ); ?>">
				<input id="<?php echo $this->get_field_id( 'fblike_show_faces' ); ?>" name="<?php echo $this->get_field_name( 'fblike_show_faces' ); ?>" type="checkbox" <?php if($fblike_show_faces) { echo ' checked '; } ?> />
				<?php _e('Show Faces', 'trwp') ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_show_header' ); ?>">
				<input id="<?php echo $this->get_field_id( 'fblike_show_header' ); ?>" name="<?php echo $this->get_field_name( 'fblike_show_header' ); ?>" type="checkbox" <?php if($fblike_show_header) { echo ' checked '; } ?> />
				<?php _e('Show Header', 'trwp') ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_height' ); ?>"><?php _e('Box height', 'trwp') ?></label>
			<input id="<?php echo $this->get_field_id( 'fblike_height' ); ?>" name="<?php echo $this->get_field_name( 'fblike_height' ); ?>" type="number" value="<?php echo esc_attr( $fblike_height ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fblike_color_scheme' ); ?>"><?php _e('Facebook Color Scheme', 'trwp') ?><br /></label>
			<label for="colorLight">
				<input id="colorLight" name="<?php echo $this->get_field_name( 'fblike_color_scheme' ); ?>" type="radio" <?php if($fblike_color_scheme == 'light') { echo ' checked '; } ?> value="light" />
			&nbsp;<?php _e('Light', 'trwp') ?>&nbsp;
			</label>
			<label for="colorDark">
				<input id="colorDark" name="<?php echo $this->get_field_name( 'fblike_color_scheme' ); ?>" type="radio" <?php if($fblike_color_scheme == 'dark') { echo ' checked '; } ?> value="dark" />
			&nbsp;<?php _e('Dark', 'trwp') ?>&nbsp;
			</label>
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "traction_fblike" );' ) );

class Traction_Related extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'traction_related',
			__('TS Related Posts', 'trwp'),
			array( 'description' => __( 'Display related posts', 'trwp' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$post_count = $instance['post_count'];

		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
			Traction::related($post_count);
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_count'] = strip_tags( $new_instance['post_count'] );

		return $instance;
	}

	public function form( $instance ) {
		if(isset($instance[ 'title' ]))
			$title = $instance[ 'title' ];
		else
			$title = __( 'Related Posts', 'trwp' );
		if(isset($instance['post_count']))
			$post_count = $instance['post_count'];
		else
			$post_count = '5';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e('Post Count', 'trwp') ?></label>
			<input id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" value="<?php echo esc_attr( $post_count ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "traction_related" );' ) );

class Traction_Popular extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'traction_popular',
			__('TS Popular Posts', 'trwp'),
			array( 'description' => __( 'Display most popular posts', 'trwp' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$post_count = $instance['post_count'];

		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
			echo '<ul class="ts-popular">';
			$q1 = new WP_Query('meta_key=post_views_count&orderby=meta_value_num&order=DESC&showposts='.$post_count.'&w='.date('W')); while ($q1->have_posts()) : $q1->the_post();
				echo '<li><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></li>';
			endwhile;
			echo '</ul>';
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['post_count'] = strip_tags( $new_instance['post_count'] );

		return $instance;
	}

	public function form( $instance ) {
		if(isset($instance[ 'title' ]))
			$title = $instance[ 'title' ];
		else
			$title = __( 'Popular Posts', 'trwp' );
		if(isset($instance['post_count']))
			$post_count = $instance['post_count'];
		else
			$post_count = '5';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e('Post Count', 'trwp') ?></label>
			<input id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" value="<?php echo esc_attr( $post_count ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "traction_popular" );' ) );
class Traction_Contact extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'traction_contact',
			__('TS Contact', 'trwp'),
			array( 'description' => __( 'Display a contact form', 'trwp' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$email_please = $instance['contact_email'];
		$a = get_option('traction_admin_options');
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		if(!isset($email_please)){
			if(!$email_please){
				if(!empty($a['contact_form_email']))
					$email_please = $a['contact_form_email'];
				else
					$email_please = get_option('admin_email');
			}
		}
		$contact_form = '<form method="post" class="ts-contact">
			<input type="text" placeholder="'.__('Name', 'trwp').'" name="traction_name"/>
			<input type="text" placeholder="'.__('Email', 'trwp').'" name="traction_email"/>
			<textarea placeholder="'.__('Your message', 'trwp').'" name="traction_message"></textarea>
			<input type="hidden" name="traction_contact"/>
			<input type="submit" class="button" value="'.__('Contact', 'trwp').'" />
		</form>';
		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
		if(isset($_POST['traction_contact'])){
			wp_mail($email, __('Contact Form from', 'trwp').' '.$_POST['traction_name'], esc_attr(strip_tags($_POST['traction_message'])));
			echo '<div class="notice success">'.__('Thank you for your message', 'trwp').'</div>';
		}
		echo $contact_form;
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['contact_email'] = strip_tags( $new_instance['contact_email'] );

		return $instance;
	}

	public function form( $instance ) {
		if(isset($instance[ 'title' ]))
			$title = $instance[ 'title' ];
		else
			$title = __( 'Contact', 'trwp' );
		if(isset($instance['contact_email']))
			$contact_email = $instance['contact_email'];
		else
			$contact_email = __('name@example.com', 'trwp');
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'contact_email' ); ?>"><?php _e('Send to Email', 'trwp') ?></label>
			<input id="<?php echo $this->get_field_id( 'contact_email' ); ?>" name="<?php echo $this->get_field_name( 'contact_email' ); ?>" type="text" value="<?php echo esc_attr( $contact_email ); ?>" placeholder="<?php _e('name@example.com', 'trwp') ?>" class="widefat" />
		</p>
		<?php
	}

}

add_action( 'widgets_init', create_function( '', 'register_widget( "traction_contact" );' ) );

class Traction_Mailchimp extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'traction_mailchimp',
			__('TS Mailchimp', 'trwp'),
			array( 'description' => __( 'Display a Mailchimp subscribe form', 'trwp' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		include_once(get_template_directory().'/inc/traction-lib/mailchimp/MCAPI.class.php');
		$title = apply_filters( 'widget_title', $instance['title'] );
		$mc_api_key = $instance['mc_api_key'];
		$mc_list_id = $instance['mc_list_id'];
		if($instance['double_optin'])
			$double_optin = true;
		else
			$double_optin = false;
		if($instance['send_welcome'])
			$send_welcome = true;
		else
			$send_welcome = false;
		$get_name = $instance['get_name'];
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
		if(isset($_POST['traction_mailchimp'])){
			$merges = array();
			if($get_name)
				$merges = array('FNAME' => $_POST['fname'], 'LNAME'=>$_POST['lname'],);
			$api = new MCAPI($mc_api_key);
			$retval = $api->listSubscribe( $mc_list_id, $_POST['email'], $merges, 'html', $double_optin, false, true, $send_welcome );
			if ($api->errorCode){
				echo '<div class="notice error">'.$api->errorMessage.'\n</div>';
			} else {
				echo '<div class="notice success">';
				if($double_optin)
					_e('Success. Look for the confirmation message in your inbox.', 'trwp');
				else
					_e('Success. Stay tuned.', 'trwp');
				echo '</div>';
			}
		} else {
			echo '<form method="post" class="ts-contact ts-mailchimp">
			<input type="text" name="email" placeholder="'.__('Email', 'trwp').'" />';
			if($get_name)
				echo '<input type="text" name="fname" placeholder="'.__('First Name', 'trwp').'" />
				<input type="text" name="lname" placeholder="'.__('Last Name', 'trwp').'" />';
			echo '<input type="hidden" name="traction_mailchimp" />
			<input type="submit" class="button" value="'.__('Subscribe', 'trwp').'" />
			</form>';
		}
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['mc_api_key'] = strip_tags( $new_instance['mc_api_key'] );
		$instance['mc_list_id'] = strip_tags( $new_instance['mc_list_id'] );
		$instance['double_optin'] = strip_tags( $new_instance['double_optin'] );
		$instance['send_welcome'] = strip_tags( $new_instance['send_welcome'] );
		$instance['get_name'] = strip_tags( $new_instance['get_name'] );

		return $instance;
	}

	public function form( $instance ) {
		if(isset($instance[ 'title' ]))
			$title = $instance[ 'title' ];
		else
			$title = __( 'Subscribe', 'trwp' );
		if(isset($instance['mc_api_key']))
			$mc_api_key = $instance['mc_api_key'];
		else
			$mc_api_key = __('YOUR API KEY', 'trwp');
		if(isset($instance['mc_list_id']))
			$mc_list_id = $instance['mc_list_id'];
		else
			$mc_list_id = false;
		if(isset($instance['double_optin']))
			$double_optin = $instance['double_optin'];
		else
			$double_optin = 'on';
		if(isset($instance['send_welcome']))
			$send_welcome = $instance['send_welcome'];
		else
			$send_welcome = false;
		if(isset($instance['get_name']))
			$get_name = $instance['get_name'];
		else
			$get_name = false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'trwp') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'mc_api_key' ); ?>"><?php _e('API Key', 'trwp') ?> <a href="http://admin.mailchimp.com/account/api" target="_blank"><?php _e('Get an API Key', 'trwp') ?></a>:</label>
			<input id="<?php echo $this->get_field_id( 'mc_api_key' ); ?>" name="<?php echo $this->get_field_name( 'mc_api_key' ); ?>" type="text" value="<?php echo esc_attr( $mc_api_key ); ?>" placeholder="<?php _e('YOUR API KEY', 'trwp') ?>" class="widefat" />
		</p>
		<?php if($mc_api_key != 'YOUR API KEY'){ ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'mc_list_id' ); ?>"><?php _e('Your lists (please add API key, save and refresh)', 'trwp') ?></label>
			<?php
			include_once(get_template_directory().'/inc/ts-stds/libs/mailchimp/MCAPI.class.php');
			$api = new MCAPI($mc_api_key);
			$getlists = $api->lists();
			if ($api->errorCode){
				_e('No lists available', 'trwp');
			} else { ?>
			<select id="<?php echo $this->get_field_id( 'mc_list_id' ); ?>" name="<?php echo $this->get_field_name( 'mc_list_id' ); ?>" placeholder="<?php _e('YOUR API KEY', 'trwp') ?>" class="widefat">
				<option><?php _e('Select One', 'trwp') ?></option>
				<?php foreach ($getlists['data'] as $list) {
					echo '<option value="'.$list['id'].'" ',$mc_list_id == $list['id'] ? 'selected' : '','>'.$list['name'].'</option>';
				} ?>
			</select>
			<?php }
		echo '</p>';
		} ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'double_optin' ); ?>">
				<input id="<?php echo $this->get_field_id( 'double_optin' ); ?>" name="<?php echo $this->get_field_name( 'double_optin' ); ?>" type="checkbox" <?php if($double_optin) { echo ' checked '; } ?> />
				<?php _e('Send a confirmation email', 'trwp') ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'send_welcome' ); ?>">
				<input id="<?php echo $this->get_field_id( 'send_welcome' ); ?>" name="<?php echo $this->get_field_name( 'send_welcome' ); ?>" type="checkbox" <?php if($send_welcome) { echo ' checked '; } ?> />
				<?php _e('Send welcome email (no effect if confirmation email is checked)', 'trwp') ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'get_name' ); ?>">
				<input id="<?php echo $this->get_field_id( 'get_name' ); ?>" name="<?php echo $this->get_field_name( 'get_name' ); ?>" type="checkbox" <?php if($get_name) { echo ' checked '; } ?> />
				<?php _e('Get first and last name', 'trwp') ?>
			</label>
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "traction_mailchimp" );' ) );