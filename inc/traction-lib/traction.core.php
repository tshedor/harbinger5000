<?php
/**
* Traction library - holding down WordPress so you don't have to
* @version 1.0
* @author Tim Shedor
* @package Traction
*/
class Traction {

	/**
	* Retrieve post count for use in a popularity metric
	* @uses $post The current post's DB object
	* @param int $postID if $post is unavailable
	*/
	static function getPostViews($postID = NULL){
		if(empty($postID)){
			global $post;
			$postID = $post;
		}
		$count = get_post_meta($postID, 'post_views_count', true);
		return $count.' '.__('Views', 'trwp');
	}

	/**
	* Set post count for use in a popularity metric
	* @uses $post The current post's DB object
	* @param int $postID if $post is unavailable
	*/
	static function setPostViews($postID = NULL) {
		if(empty($postID)){
			global $post;
			$postID = $post;
		}
		$count = get_post_meta($postID, 'post_views_count', true);
		$count++;
		update_post_meta($postID, 'post_views_count', $count);
	}

	/**
	* WP_Query for popular posts
	* @uses WP_Query
	* @param int $post_count how many posts to return
	*/
	static function queryPopular($post_count = 5) {
		$popular_query = array(
			'meta_key' => 'post_views_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'showposts' => $post_count,
			'w' => date('W'),
			'year' => date('Y')
		);
		$query = new WP_Query($popular_query);

		// If the week is zero, we get the month
		if($query->post_count == 0) {
			unset($popular_query['w']);
			$popular_query['monthnum'] = date('n');

			$query = new WP_Query($popular_query);

			// If month is zero, let's get the whole year then
			if ($query->post_count == 0) {
				unset($popular_query['monthnum']);
				$query = new WP_Query($popular_query);

				// If there's nothing this year, show the most recent requested count
				if($query->post_count == 0) {
					$query = new WP_Query(array('showposts' => $post_count));
				}
			}
		}

		return $query;
	}

	/**
	* Determine if current view (usually archive) is paged
	* @uses $_GET['paged']
	* @return string current paginated page
	*/
	static function if_paged(){
		if(isset($_GET['paged']) && !empty($_GET['paged'])) {
			return $_GET['paged'];
		} else {
			return false;
		}
	}

	/**
	* Get image ID from image URL
	* @param string $image_url The image url
	* @return int The image ID
	*/
	//http://themeforest.net/forums/thread/get-attachment-id-by-image-url/36381
	static function get_image_id($image_url) {
    	global $wpdb;
	    $prefix = $wpdb->prefix;
    	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='" . $image_url . "';"));
    	return $attachment[0];
	}

	/**
	* Get post image
	* @uses $post global object
	* @uses Get the Image plugin if available
	* @param string $size the image's size
	* @param string $class an HTML class to apply to the image
	* @param array $attr additional attributes to apply as either get_the_image options or otherwise
	* @param bool $just_url only return the URL of the image
	* @param int $id if $post is unavailable or to retrieve specific image
	*/
	static function get_image($size = 'medium', $args = array()){
		global $post, $a;

		$defaults = array(
			'id' => $post->ID,
			'attr' => NULL,
			'just_url' => false,
			'class' => '',
			'link_to_post' => true,
			'image_present' => false
		);

		$args = wp_parse_args( $args, $defaults );

		//Use get_the_image if available
		if(function_exists('get_the_image')){

			//Set get the image settings
			$gti_settings = array('image_scan' => true, 'meta_key_save' => true, 'post_id' => $args['id'], 'size' => $size, 'image_class' => $args['class']);

			$gti_settings = array_merge($gti_settings, array('link_to_post' => $args['link_to_post']));

			//Apply attributes array if available
			if(!empty($args['attr']))
				$gti_settings = array_merge($gti_settings, $args['attr']);

			if($args['image_present']) {
				$gti = get_the_image(array('format' => 'array'));
				return isset($gti['src']);
			}

			if($args['just_url']){

				$gti_settings = array_merge($gti_settings, array('format' => 'array'));
				$gti = get_the_image($gti_settings);
				return $gti['src'];

			} else {

				get_the_image($gti_settings);
				return;

			}

		//See if the post has a thumbnail
		} elseif(has_post_thumbnail($args['id'])) {

			//Only get the URL
			if($args['just_url']){

				$image = wp_get_attachment_image_src(get_post_thumbnail_id($args['id']), $size);
				return $image[0];

			} else {

				//Echo out the result
				if($args['link_to_post']) echo '<a href="' . get_permalink($args['id']) . '" title="' . esc_attr(get_post_field('post_title', $args['id'])) . '">';
					the_post_thumbnail($size, array('class' => $class, $args['attr']));
				if($args['link_to_post']) echo '</a>';
				return;
			}

		//If post thumbnail isn't attached, but user has get_first_image turned on
		} elseif($a['get_first_image']) {

			//http://wpforce.com/automatically-set-the-featured-image-in-wordpress/
			$attached_image = get_children( "post_parent=".$args['id']."&post_type=attachment&post_mime_type=image&numberposts=1" );

			//If there is an image available
			if($attached_image){
				foreach ($attached_image as $attachment_id => $attachment) { //This only returns one image, but it's hard to just get the first result
					set_post_thumbnail($args['id'], $attachment_id);
				}

				//End if we're just getting the URL
				if($args['just_url']){
					$image = wp_get_attachment_image_src(get_post_thumbnail_id($args['id']), $size);
					return $image[0];
				}

				//Echo out the result
				if($args['link_to_post']) echo '<a href="' . get_permalink($args['id']) . '" title="' . esc_attr(get_post_field('post_title', $args['id'])) . '">';
					the_post_thumbnail($size, array('class' => $class, $args['attr']));
				if($args['link_to_post']) echo '</a>';
				return;

			//Search post content for an inserted image
			} else {
				$first_img = NULL;
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
				$first_img = $matches[1][0];
				if(empty($first_img)){
					return false;
				} else {

					if($args['just_url']){
						return $first_img;
					}

					//Echo out the result
					if($args['link_to_post']) echo '<a href="' . get_permalink($args['id']) . '" title="' . esc_attr(get_post_field('post_title', $args['id'])) . '">';
						echo '<img src="' . $first_img . '"';
						if(!empty($args['attr']) && $args['attr']['alt']) echo ' alt="' . $args['attr']['alt'] . '"';
						echo '/>';
					if($args['link_to_post']) echo '</a>';
					return;

				}
			}
		}
	}

	/**
	* Clean a string to be useful as an ID or GET query
	* @param string $text the URL to convert
	* @return string converted URL
	*/
	static function parameterize($text){
		$param = $text;
		$param = strtolower($param);
		$replace_with_underscores = array(' ', '-', '/', ':', ';');
		$remove_entirely = array("'", '"', '?', '\\', '#', '@', '!', '$', '%', '^', '&', '*', '(', ')', '{', '}', '[', ']', '+', '=', '~', '`', '|', '<', ',', '.', '>', '‘', '’', '”', '“', '–');
		$param = str_replace($replace_with_underscores, '_', $param);
		$param = str_replace($remove_entirely, '', $param);
		return $param;
	}

	/**
	* Display social media share icons on single posts
	* @uses $post global post object
	* @uses array $a admin options
	* @param bool $showNames display platform names next to icons
	* @param int $postID submit a custom post ID
	* @echo icons wrapped in links
	*/
	static function social_single($showNames = true, $postID = NULL){
		global $a;
		if(empty($postID)){
			global $post;
			$postID = $post;
		}
		if($a['show_social']){
			$share_array = array('facebook', 'twitter', 'linkedin', 'pinterest', 'google-plus', 'stumbleupon');
			foreach($share_array as $s){
				$content = '<i class="social-ico-'.$s.'"></i>';
				if($showNames)
					$content .= ' '.$s;

				//See if the option is in the array
				if($a[$s]){
					$shareinfo = new TractionShare(get_permalink(),get_the_title(),$content,$a['twitter_profile']);
					$shareme = $shareinfo->$s();
					echo '<li class="social-list-item">'.$shareme.'</li>';
				}
			}
			if($a['fblike']) {
				$shareinfo = new TractionShare(get_permalink(),get_the_title(),'',$a['twitter_profile']);
				$shareme = $shareinfo->fblike();
				echo '<li class="social-list-item">' . $shareme . '</li>';
			}
		}
	}

	/**
	* Display icons to social profiles
	* @uses $a global options
	* @echo icons for networks wrapped in links to profiles
	*/
	static function social_header(){
		global $a;
		$social = array('facebook', 'twitter', 'linkedin', 'youtube', 'pinterest', 'instagram', 'vimeo', 'google-plus', 'github', 'foursquare', 'dribbble', 'flickr', 'feed', 'mail');
		foreach($social as $sicon) :
			$op = $a[$sicon.'_profile'];
			if(!empty($op)){
				if($sicon == 'twitter')
					$op = 'http://twitter.com/'.$op;
				if($sicon == 'feed')
					$op = get_bloginfo('rss2_url');
				if($sicon == 'mail')
					$op = 'mailto:'.get_bloginfo('admin_email');
				echo '<a href="'.$op.'" title="'.ucfirst($sicon).'"><i class="social-ico-'.$sicon.'"></i></a>';
			}
		endforeach;
	}

	/**
	* Render logo or site name
	* @uses $a global options
	* @echo logo or text site name
	*/
	static function logo(){
		global $a;
		echo '<a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">';
			if(empty($a['logo']))
				echo '<h2 class="head-logo">'.get_bloginfo('name').'</h2>';
			else
				echo '<img src="'.$a['logo'].'" alt="'.get_bloginfo('name').'" />';
		echo '</a>';
	}

	/**
	* Display related posts
	* @uses $post global DB object
	* @uses Yet Another Related Posts Plugin if available
	* @param int $post_count number of related posts to return default 5
	*/
	static function related($post_count = 5){
		if(function_exists('related_posts'))
			related_posts();
		else {
			if(function_exists('yarpp_related'))
				yarpp_related();
			else {
				global $post;
				$tags = wp_get_post_tags($post->ID);
				if ($tags){
					$first_tag = $tags[0]->term_id;
					$args = array(
						'tag__in' => array($first_tag),
						'post__not_in' => array($post->ID),
						'showposts'=> $post_count,
					);
					$q82 = new WP_Query($args);
					if( $q82->have_posts() ) :
						echo '<ul class="traction-related">';
						while ($q82->have_posts()) : $q82->the_post();
							echo '<li><a href="'.get_permalink().' title="'.get_the_title().'">'.get_the_title().'</a></li>';
						endwhile;
						echo '</ul>';
					endif; wp_reset_query();
				} else {
					return false;
				}
			}
		}
	}

	/**
	* Display pagination
	* @uses $wp_query global object
	* @uses WP Pagenavi plugin if available
	* @echos paginated list
	*/
	static function pagination(){
		if(function_exists('wp_pagenavi'))
			wp_pagenavi();
		else {
			global $wp_query; $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
			echo paginate_links(array(
				'base'      => @add_query_arg( 'paged', '%#%' ),
				'format'	=>	'',
				'total' 	=>	$wp_query->max_num_pages,
				'prev_text'	=>	'&laquo;&nbsp;'.__('Previous', 'trwp').'&nbsp;',
				'next_text'	=>	'&nbsp;'.__('Next', 'trwp').'&nbsp;&raquo;',
				'end_size'	=>	3,
				'current'	=>	$current
			));
		}
	}

	/**
	* Display breadcrumbs
	* @uses $wp_query global object
	* @uses $a global user options
	* @uses breadcrumb plugin if available
	* @echo breadcrumbs
	*/
	static function breadcrumbs(){
		global $wp_query, $a;
		echo '<div class="row clear breadcrumbs">
		<div class="large-12 columns">';
		if(function_exists('bcn_display'))
			bcn_display();
		elseif($a['breadcrumbs_archive']) {
			if(!is_home()){
				echo '<a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">Home</a>';
				if(is_archive()){
					global $wp_query;
					echo '&nbsp;&raquo;&nbsp;';
					if(is_category()){
						echo get_category_parents(get_query_var('cat'), TRUE, ' &raquo; ');
						echo single_cat_title();
					} elseif(is_tag()) {
						__('Tagged', 'trwp').' ';
						echo single_tag_title();
					} elseif(is_tax()) {
						$tax =  get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						echo get_query_var('taxonomy').': '.$tax->name;
					} elseif(is_day() || is_month() || is_year()) {
						echo 'From '.the_time('F j, Y');
					} elseif(is_author()) {
						echo 'By '.the_author_meta('display_name', get_query_var('author'));
					}
					if_paged();
				}
				if(is_single()){
					global $post;
					echo '&nbsp;&raquo;&nbsp;';
					$post_cats = get_the_category($post->ID);
					if($post_cats[0]){
						echo get_category_parents($post_cats[0]->term_id, TRUE, '&nbsp;&raquo;&nbsp;');
						echo '<a href="'.get_category_link($post_cats[0]->term_id ).'">'.$post_cats[0]->cat_name.'</a> &raquo; ';
					}
					the_title();
				}
				if(is_page()){
					global $post;
					echo '&nbsp;&raquo;&nbsp;';
					$parents = get_post_ancestors($post->ID);
					$top_parent = $parents[count($parents)-1];
					if($post->post_parent){
						$parent = get_page($post->post_parent);
						if($parent->post_parent){
							$higher_parent = get_page($parent->post_parent);
							if($higher_parent->post_parent){
								$highest_parent = get_page($higher_parent->post_parent);
								if($highest_parent->post_parent){
									$ultra_parent = get_page($highest_parent->post_parent);
									if($ultra_parent->post_parent){
										$ultimate_parent = get_page($ultra_parent->post_parent);
										echo '<a href="'.get_permalink($ultimate_parent->ID).'" title="'.$ultimage_parent->post_title.'">'.$ultimate_parent->post_title.'</a>&nbsp;&raquo;&nbsp;';
									}
									echo '<a href="'.get_permalink($ultra_parent->ID).'" title="'.$ultra_parent->post_title.'">'.$ultra_parent->post_title.'</a>&nbsp;&raquo;&nbsp;';
								}
								echo '<a href="'.get_permalink($highest_parent->ID).'" title="'.$highest_parent->post_title.'">'.$highest_parent->post_title.'</a>&nbsp;&raquo;&nbsp;';
							}
							echo '<a href="'.get_permalink($higher_parent->ID).'" title="'.$higher_parent->post_title.'">'.$higher_parent->post_title.'</a>&nbsp;&raquo;&nbsp;';
						}
						echo '<a href="'.get_permalink($parent->ID).'" title="'.$parent->post_title.'">'.$parent->post_title.'</a>&nbsp;&raquo;&nbsp;';
					}
					the_title();
				}
			}
		}
		echo '</div>
		</div>';
	}

	/**
	* Inject copyright/theme credit
	* @uses $a global user options
	* @echo copywright text
	*/
	static function copyright(){
		global $a;
		echo '<div class="copyright">';
		if(!empty($a['copyright_text'])){
			$copytext = $a['copyright_text'];
			$copytext = str_replace('{SITE NAME}', get_bloginfo('name'), $copytext);
			$copytext = str_replace('{CURRENT YEAR}', date('Y'), $copytext);
			echo $copytext;
		} else {
			echo '&copy; '.__('Copyright', 'trwp').' <a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">'.get_bloginfo('name').'</a> '.date('Y');
		}
		if($a['footer_credit'])
			echo __('Code and Design by', 'trwp').' <a href="http://timshedor.com" title="Tim Shedor">Tim Shedor</a>';
		else
			echo ' | <div id="creditsDisplay"><div><a href="'.get_template_directory_uri().'/humans.txt" title="Credits">Credits</a></div><div>'.__('Code and Design by', 'trwp').' <a href="http://timshedor.com" title="Tim Shedor">Tim Shedor</a></div></div> <a href="#" title="Tim Shedor" class="cred" id="footerCredits"><i class="icon-asterisk"></i></a>';
		echo '</div>';
	}

	/**
	* Retrieve an array key from its value
	* @param array $array the haystack
	* @param object $arrayValue
	* @return $key array key || false on failure
	*/
	//http://stackoverflow.com/questions/8102221/php-multidimensional-array-searching-find-key-by-specific-value
	public function arrayKey($array, $arrayValue) {
		foreach($array as $key => $item) {
			if($item['id'] === $arrayValue)
				return $key;
		}
		return false;
	}

}

/**
* Add share buttons with urls and title parameters
* @package Traction
* @subpackage TractionShare
*/
class TractionShare {
	private $url;
	private $title;
	private $content;
	private $account;

	public function __construct($url,$title,$content,$account) {
		$this->url = $url;
		$this->title = $title;
		$this->content = $content;
		$this->account = $account;
	}

	public function twitter() {
		return '<a href="https://twitter.com/intent/tweet?text='.$this->title.'&url='.$this->url.'&related='.$this->account.'&via='.$this->account.'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	public function facebook() {
		return '<a href="https://www.facebook.com/sharer/sharer.php?u='.$this->url.'&t='.$this->title.'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	public function fblike() {
		return '<iframe src="http://www.facebook.com/plugins/like.php?href='.$this->url.'&layout=button_count&show_faces=false&width=90&action=like&colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:90px; height:20px;"></iframe>';
	}
	public function pinterest() {
		return '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script><a data-pin-config="none" href="//pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" >'.$this->content.'</a>';
	}
	public function googleplus() {
		return '<a href="https://plus.google.com/share?url='.$this->url.'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	public function stumbleupon() {
		return '<a href="http://stumbleupon.com/submit?url='.$this->url.'&title='.$this->title.'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	public function reddit() {
		return '<a href="http://reddit.com/submit?url='.$this->url.'&title='.$this->title.'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	public function linkedin() {
		return '<a href="http://www.linkedin.com/cws/share?url='.$this->url.'&isFramed=false&ts='.time().'" title="'.$this->title.'">'.$this->content.'</a>';
	}
	//Not a network, but for print functionality
	public function htmlprint(){
		return '<a href="' . $this->url .'" title="' . $this->title . '" onClick="window.print()">'. $this->content .'</a>';
	}

}

/**
* Display admin fields for user options or custom post meta
* @package Traction
* @subpackage TractionInput
*
* Arrays constructed for each field in this way:
* @param string 'name' the displayed title of the field
* @param string 'desc' helper description beneath title of the field
* @param string 'id' the custom field unique identifier
* @param string 'std' often the placeholder
* @param string 'type' the input type (text | textarea | hidden | checkbox | select | radio |
* password | textareacode | repeatable | number | range | date | week | month |
* datetime | url | email | color)
* as well as WP-specific helpers to convert into inputs: (tinymce | media | posts | pages |
* categories | users)
* traction specific: (icons | socialcheckbox | map)
* HTML helpers: (separate | customnotice | clearfix | endarray)
* @param string|boolean 'def' the default text or value of the field
* @param array 'options' to be applied for radio or select fields. Constructed in this way:
*** @param array for each option
****** @param string 'name' the front-facing label for the option
****** @param string 'id' the unique identifier
****** @param string 'image' absolute path of image to display above radio option (not applicable to select fields)
*/
class TractionInput {

	/**
	* Field values
	* @param array $meta accepts specifics of meta field
	*/
	private $meta;

	/**
	* Retrieve pre-set values
	* @param object $value accepts values of meta field as int, string, boolean or array
	*/
	private $value;

	/**
	* Establish preceding HTML
	* @param string $initial HTML values before rendering of adminfield
	*/
	private $initial;

	/**
	* Establish antecedent HTML
	* @param string $finish HTML values after rendering of adminfield
	*/
	private $finish;

	public function __construct($meta,$value) {
		$this->meta = $meta;
		$this->value = $value;
		$this->initial = '<div class="option '.$this->value['type'].' ';
		if(isset($this->value['class']))
			$this->initial .= $this->value['class'];
		$this->initial .= '">
			<div class="label">';
		if(!empty($this->value['name'])) $this->initial .= $this->value['name'];
		if(!empty($this->value['desc'])) $this->initial .= '<span class="desc">'.$this->value['desc'].'</span>';
		$this->initial .= '</div>
			<div class="cell">';
		$this->finish = '</div></div>';
	}

	/**
	* Show a text field
	*/
	public function text() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="text" id="'.$this->value['id'].'" value="';
		if(!empty($this->meta))
			$html .= htmlspecialchars($this->meta);
		$html .= '"';
		if(!empty($this->value['std']))
			$html .= ' placeholder="'.htmlspecialchars($this->value['std']).'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a textarea
	*/
	public function textarea(){
		$html = $this->initial;
		$html .= '<textarea name="'.$this->value['id'].'" type="'.$this->value['type'].'" cols="18" rows="5"';
		if(isset($this->value['required']))
			$html .= ' required ';
		if ($this->meta == "")
			$html .= 'placeholder="'.htmlspecialchars(stripslashes($this->value['std'])).'"';
		$html .= '>';
		if ($this->meta != "")
			$html .= htmlspecialchars(stripslashes($this->meta));
		$html .= '</textarea>';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Render a hidden field
	*/
	public function hidden() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="hidden" id="'.$this->value['id'].'" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a checkbox field
	*/
	public function checkbox(){
		if(isset($this->value['class']))
			$class = $this->value['class'];
		else
			$class = '';
		$html = '<div class="option checkbox '.$class.'">
		<div class="cell">
			<label class="label">
				<input type="checkbox" name="'.$this->value['id'].'"';
			if($this->meta)
				$html .= ' checked ';
			$html .= ' />'.$this->value['name'].'<span class="desc">'.$this->value['desc'].'</span>
			</label>
		</div>
		</div>';
		echo $html;
	}

	/**
	* Display a select box
	*/
	public function select(){
		$html = $this->initial;
		$html .= '<select name="' . $this->value['id'] . '">';
		foreach ($this->value['options'] as $opt) {
			$html .= '<option value="'.$opt['id'].'">'.$opt['name'].'</option>';
		}
		$html .= '</select>';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a radio select box
	*/
	public function radio(){
		$html = $this->initial;
		foreach ($this->value['options'] as $opt) {
			if(isset($opt['image']))
				$html .= '<div class="radio-image">';
			$html .= '<label>';
			if(isset($opt['image']))
				$html .= '<img src="'.$opt['image'].'" />';
			$html .= '<input name="'.$this->value['id'].'" type="radio" value="'.$opt['id'].'"';

			//If meta has it as checked or the opt
			if(!$this->meta && $opt['id'] == $this->value['def'] xor $this->meta == $opt['id'])
				$html .= 'checked="checked"';
			$html .= '/>';
			$html .= '&nbsp;&nbsp;'.$opt['name'].'</label>&nbsp;&nbsp;';
			if(isset($opt['image']))
				$html .='</div>';
		}
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a password field
	*/
	public function password() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="password" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a textarea explicitly intended for code input (characters are escaped with stripslashes)
	*/
	public function textareacode(){
		$html = $this->initial;
		$html .= '<textarea name="'.$this->value['id'].'" type="'.$this->value['type'].'" cols="18" rows="5"';
		if(isset($this->value['required']))
			$html .= ' required';
		$html .= '>';
			if ($this->meta != "")
				$html .= stripslashes($this->meta);
			else
				$html .= stripslashes($this->value['std']);
			$html .= '</textarea>';
			$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a repeatable field
	*/
	public function repeatable() {
		$html = $this->initial;
		$html .= '<a class="repeatable-add button" href="#">+</a>
		<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
		$i = 0;
		if($this->meta) {
			foreach(unserialize($this->meta) as $row) {
				$html .= '<li class="repeatable-holder">
				<input type="text" name="'.$this->value['id'].'['.$i.']" id="'.$this->field['id'].'" value="'.htmlspecialchars($row).'" /><a class="repeatable-remove button" href="#">-</a></li>';
				$i++;
			}
		} else {
			$html .= '<li class="repeatable-holder">
			<input type="text" name="'.$this->value['id'].'['.$i.']" id="'.$this->field['id'].'" value="'.htmlspecialchars($row).'" /><a class="repeatable-remove button" href="#">-</a></li>';
		}
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a number field
	*/
	public function number() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="number" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native range input field
	*/
	public function range() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="range" min="'.$this->value['min'].'" max="'.$this->value['max'].'" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native date field
	*/
	public function date() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="date" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native week picker field
	*/
	public function week() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="week" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native month field
	*/
	public function month() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="month" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native datetime field
	*/
	public function datetime() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="datetime" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native URL field
	*/
	public function url() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="url" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native telephone field
	*/
	public function tel() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="tel" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native email field
	*/
	public function email() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="email" data-type="email" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		$html .= '" placeholder="'.$this->value['std'].'" ';
		if(isset($this->value['required']))
			$html .= 'required data-required="true"';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a native color picker field
	*/
	public function color() {
		$html = $this->initial;
		$html .= '<input name="'.$this->value['id'].'" type="count" value="';
		if ($this->meta != "")
			$html .= $this->meta;
		else
			$html .= $this->value['std'];
		$html .= '"';
		if(isset($this->value['required']))
			$html .= ' required';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Show a rich editor field
	*/
	public function tinymce(){
		echo $this->initial;
			if ($this->meta != "")
				$val = html_entity_decode(stripcslashes($this->meta));
			else
				$val = html_entity_decode(stripcslashes($this->value['std']));
		wp_editor( $val, $this->value['id'], array( 'textarea_name' => $this->value['id'], 'media_buttons' => true, 'textarea_rows' => 12, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_adv' ) ) );
		echo $this->finish;
	}

	/**
	* Display a media upload button that hooks into the WordPress media library/rich uploader
	*/
	public function media(){
		$html = $this->initial;
		$html .= '<input type="button" class="custom_media_upload button button-large" value="Add Media" />
			<img class="custom_media_image" src="';
			if($this->meta)
				$html .= $this->meta;
			else
				$html .= $this->value['std'] ? $this->value['std'] : '';
			$html .= '" />
		<input class="custom_media_url" type="text" name="'.$this->value['id'].'" value="';
		$html .= $this->meta ? $this->meta : $this->value['std'];
		$html .= '" placeholder="http://example.com/media.png"';
		if(isset($this->value['required']))
			$html .= ' required ';
		$html .= ' />';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a select field with populated with a list of posts
	*/
	public function posts(){
		$html = $this->initial;
		$html .= '<select name="'.$this->value['id'].'"';
		if(isset($this->value['required']))
			$html .= ' required ';
		$html .= '>
			<option value="">'.__('Select One', 'trwp').'</option>';
			$rps = wp_get_recent_posts();
			foreach ($rps as $recent) {
				$html .= '<option value="'.$recent["ID"].'"';
					if($this->meta == $recent["ID"])
						$html .= 'selected="selected"';
					$html .= '>'.$recent["post_title"].'
				</option>';
			}
		$html .= '</select>';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a select field with populated with a list of pages
	*/
	public function pages(){
		$html = $this->initial;
		$html .= wp_dropdown_pages(array('echo' => 0, 'name' => $this->value['id'], 'selected' => $this->meta, 'show_option_none' => __('Select One', 'trwp')));
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a select field with populated with a list of categories
	*/
	public function categories(){
		$html = $this->initial;
		$query = array('hide_empty' => 0, 'echo' => 0, 'name' => $this->value['id'], 'selected' => $this->meta, 'hierarchical' => true, 'show_option_none' => __('Select One', 'trwp'));
		if(isset($this->value['taxonomy']))
			$query['taxonomy'] = $this->value['taxonomy'];
		if(isset($this->value['tax']))
			$query['taxonomy'] = $this->value['tax'];
		$html .= wp_dropdown_categories($query);
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a select field with populated with a list of users
	*/
	public function users(){
		$html = $this->initial;
		$html .= wp_dropdown_users(array('echo' => 0, 'name' => $this->value['id'], 'selected' => $this->meta, 'hierarchical' => true, 'show_option_none' => __('Select One', 'trwp')));
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display an icon picker select menu
	*/
	public function icons(){
		$icons = array('home', 'heart', 'heart-empty', 'refresh', 'repeat', 'print', 'cog', 'comments', 'check', 'ok', 'remove', 'microphone', 'reorder', 'support', 'phone', 'alert', 'code', 'tie', 'presentation', 'paperclip', 'file', 'loop', 'pencil', 'pencil2', 'calendar', 'link', 'film', 'quotes-left', 'map', 'envelope', 'play2', 'image', 'tags', 'tag', 'greenhosting', 'lightbulb', 'plus', 'minus', 'location', 'bell', 'users', 'user', 'export', 'share', 'clock', 'sound', 'arrow-left', 'mobile', 'folder', 'star', 'star2', 'thumbs-up', 'thumbs-down', 'shuffle', 'pictures', 'camera', 'music', 'info', 'help', 'archive', 'aid', 'automobile', 'law', 'factory', 'food', 'arrow-up', 'copyright', 'foodtray', 'office', 'building', 'library', 'wrench', 'wrench2', 'cart', 'globe', 'users', 'chair', 'dollar', 'dollar2', 'pig', 'retail', 'parts', 'money', 'handshake', 'handshake1');
		$html = $this->initial;
		$html .= '<div class="list-icon-wrapper">';
		$html .= '<div class="preview-icons">';
		if($this->meta)
			$html .= '<i class="icon-'.$this->meta.'"></i> '.ucfirst(str_replace('-', ' ', $this->meta));
		else
			$html .= __('Select One', 'trwp');
		$html .= ' <i class="icon-angle-down"></i></div>';
		$html .= '<ul class="list-icons" data-name="'.$this->meta.'"';
		if(isset($this->value['required']))
			$html .= ' required ';
		$html .= '>';
			foreach ($icons as $icon) {
				$html .= '<li data-value="'.$icon.'"';
				if($this->meta == $icon)
					$html .= ' data-selected="selected"';
				$html .= '><i class="icon-'.$icon.'"></i> ' . ucfirst(str_replace('-', ' ', $icon)) . '</li>';
			}
		$html .= '</ul>';
		$html .= '<input class="hidden-icons" type="hidden" name="'.$this->value['id'].'" value="'.$this->meta.'" />';
		$html .= '</div>';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Display a checkbox with social icons (for admin options page only)
	*/
	public function socialcheckbox(){
		if(isset($this->value['class']))
			$class = $this->value['class'];
		else
			$class = '';
		$html = '<div class="option checkbox socialbox '.$class.'">
			<div class="cell">
				<label class="label">
					<input type="checkbox" name="'.$this->value['id'].'"';
					if($this->meta)
						$html .= ' checked ';
					$html .= ' />
					<i class="social-ico-'.$this->value['desc'].'" style="font-size:22px"></i> '.$this->value['name'].'
				</label>
			</div>
		</div>';
		echo $html;
	}

	/**
	* Display a Google map and store lat/lng coordinates
	*/
	public function map(){
		$html = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places,geometry"></script>';
		$html .= $this->initial;
		$html .= '<script type="text/javascript">jQuery(document).ready(function(){';
		if($this->meta)
			$html .= 'mapsload('.$this->meta.',"#'.$this->value['id'].'");';
		else
			$html .= 'mapsload();';
		$html .= '});</script><input type="hidden" name="'.$this->value['id'].'" id="'.$this->value['id'].'" value="'.$this->meta.'" />
			<div id="residence_map" style="width:500px; height:200px;"></div>';
		$html .= $this->finish;
		echo $html;
	}

	/**
	* Administrative: Start a new meta box set
	*/
	public function separate(){
		$html = '<div class="postbox clearfix clear">
			<h3 class="hndle">'.$this->value['name'].'</h3>
			<div class="inside">';
		echo $html;
	}

	/**
	* Administrative: Inject custom HTML
	*/
	public function customnotice(){
		$html = $this->value['std'];
		echo $html;
	}

	/**
	* Administrative: Reset columned fields (i.e. after four <div class="one-fourth"> fields)
	*/
	public function clearfix(){
		$html = '<input type="hidden" name="clearfix" /><div class="clearfix"></div>';
		echo $html;
	}

	/**
	* Administrative: Round out the end of the postbox class
	*/
	public function endarray(){
		$html = '</div>
		</div>';
		echo $html;
	}
}

/**
* Add custom meta to posts and pages
* @package Traction
* @subpackage TractionMetaBoxes
*/
class TractionMetaBoxes {

	/**
	* Load in meta fields
	* @param array $meta_fields
	*/
	private $meta_fields;

	/**
	* Meta box display information, like title
	* @param array $meta_information
	* @param string $meta_information['title'] Post box title
	* @param string $meta_information['post_type'] accepts registered custom post type
	* @param string $meta_information['priority'] ranking = high | low | normal
	* @param string $meta_information['display'] meta box positioning = high | low | normal
	*/
	private $meta_information;

	public function __construct($meta_fields, $meta_information) {
		$this->meta_fields = $meta_fields;
		$this->meta_information = $meta_information;
		add_action('add_meta_boxes', array($this, '_add_traction_meta_box') );
		add_action('save_post', array($this, '_save_traction_box_meta') );
	}

	/**
	* Call the actual meta box WP function
	*/
	public function _add_traction_meta_box() {
		if(!empty($this->meta_information)){
			$this->meta_information['title'] = empty($this->meta_information['title']) ? 'Traction Meta Box' : $this->meta_information['title'];
			$this->meta_information['post_type'] = empty($this->meta_information['post_type']) ? 'post' : $this->meta_information['post_type'];
			$this->meta_information['priority'] = empty($this->meta_information['priority']) ? 'high' : $this->meta_information['priority'];
			$this->meta_information['display'] = empty($this->meta_information['display']) ? 'normal' : $this->meta_information['display'];
		}

		add_meta_box(
			Traction::parameterize($this->meta_information['title']),
			$this->meta_information['title'],
			array($this, '_display_traction_meta_box'),
			$this->meta_information['post_type'],
			$this->meta_information['display'],
			$this->meta_information['priority']
		);
	}

	/**
	* Add content to the meta box, like admin fields
	*/
	public function _display_traction_meta_box() {

		global $post;
		wp_nonce_field( 'traction_nonce_check', 'traction_meta_box_nonce' );
		echo '<div class="wrap trao clear clearfix" id="poststuff">';
		$globalMeta = get_post_custom($post->ID);
			foreach ($this->meta_fields as $value) {
				if(isset($globalMeta[$value['id']][0]))
					$meta = $globalMeta[$value['id']][0];
				else
					$meta = false;
				$fieldType = $value['type'];
				$newField = new TractionInput($meta,$value);
				$newField->$fieldType();
			}
		echo '</div>';

	}

	/**
	* Save custom meta box content
	*/
	public function _save_traction_box_meta($post_id) {
		if ( !isset( $_POST['traction_meta_box_nonce'] )  || !wp_verify_nonce($_POST['traction_meta_box_nonce'], 'traction_nonce_check'))
			return $post_id;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}

		foreach ($this->meta_fields as $field) {
			if($field['type'] == 'tax_select') continue;
			$old = get_post_meta($post_id, $field['id'], true);
			if(isset($_POST[$field['id']])){
				$new = $_POST[$field['id']];;
			}
			if($field['type'] == 'checkbox' && !isset($_POST[$field['id']])){
				$new = '';
			}
			if (isset($new) && $new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ($old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
	}

}

include_once(dirname(__FILE__) . '/traction.hooks-callbacks.php');
include_once(dirname(__FILE__) . '/traction.widgets.php');
include_once(dirname(__FILE__) . '/traction.shortcodes.php');
include_once(dirname(__FILE__) . '/traction.globals.php');
include_once(dirname(__FILE__) . '/post-meta/traction.layout.php');
if(is_user_logged_in()){
	include_once(dirname(__FILE__) . '/tinymce/functions.php');
	include_once(dirname(__FILE__) . '/traction-admin-options.php');
}

?>