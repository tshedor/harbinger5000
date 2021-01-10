<?php
/**
 * Plugin Name: Get The Image
 * Plugin URI:  https://themehybrid.com/plugins/get-the-image
 * Description: This is a highly intuitive script that can grab an image by custom field, featured image, post attachment, or extracting it from the post's content.
 * Version:     1.1.0
 * Author:      Justin Tadlock
 * Author URI:  https://themehybrid.com
 */

/**
 * Get the Image - An advanced post image script for WordPress.
 *
 * Get the Image was created to be a highly-intuitive image script that displays post-specific images (an
 * image-based representation of a post).  The script handles old-style post images via custom fields for
 * backwards compatibility.  It also supports WordPress' built-in featured image functionality.  On top of
 * those things, it can automatically set attachment images as the post image or scan the post content for
 * the first image element used.  It can also fall back to a given default image.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   GetTheImage
 * @version   1.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2017, Justin Tadlock
 * @link      https://themehybrid.com/plugins/get-the-image
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Adds theme support for WordPress 'featured images'.
add_theme_support( 'post-thumbnails' );

# Delete the cache when a post or post metadata is updated.
add_action( 'save_post',         'get_the_image_delete_cache_by_post'        );
add_action( 'deleted_post_meta', 'get_the_image_delete_cache_by_meta', 10, 2 );
add_action( 'updated_post_meta', 'get_the_image_delete_cache_by_meta', 10, 2 );
add_action( 'added_post_meta',   'get_the_image_delete_cache_by_meta', 10, 2 );

/**
 * The main image function for displaying an image.  This is a wrapper for the Get_The_Image class. Use this
 * function in themes rather than the class.
 *
 * @since  0.1.0
 * @access public
 * @param  array        $args  Arguments for how to load and display the image.
 * @return string|array        The HTML for the image. | Image attributes in an array.
 */
function get_the_image( $args = array() ) {

	$image = new Get_The_Image( $args );

	return $image->get_image();
}


/* === Internal Plugin Code: Don't use the below unless you know what you're doing. Expect breakage. === */


/**
 * Class for getting images related to a post.  Only use this class in your projects if you absolutely know
 * what you're doing and expect your code to break in future versions.  Use the the `get_the_image()`
 * wrapper function instead.  That's the reason it exists.
 *
 * @since  1.0.0
 * @access private
 */
final class Get_The_Image {

	/**
	 * Array of arguments passed in by the user and merged with the defaults.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $args  = array();

	/**
	 * Image arguments array filled by the class.  This is used to store data about the image (src,
	 * width, height, etc.).  In some scenarios, it may not be set, particularly when getting the
	 * raw image HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $image_args  = array();

	/**
	 * The image HTML to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $image = '';

	/**
	 * Original image HTML.  This is set when splitting an image from the content.  By default, this
	 * is only used when 'scan_raw' is set.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $original_image = '';

	/**
	 * Holds an array of srcset sources and descriptors.
	 *
	 * @since  1.1.0
	 * @access public
	 * @var    array
	 */
	public $srcsets = array();

	/**
	 * Constructor method.  This sets up and runs the show.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args
	 * @return void
	 */
	public function __construct( $args = array() ) {
		global $wp_embed;

		// Use WP's embed functionality to handle the [embed] shortcode and autoembeds. */
		add_filter( 'get_the_image_post_content', array( $wp_embed, 'run_shortcode' ) );
		add_filter( 'get_the_image_post_content', array( $wp_embed, 'autoembed'     ) );

		// Set the default arguments.
		$defaults = array(

			// Post the image is associated with.
			'post_id'            => get_the_ID(),

			// Method order (see methods below).
			'order'              => array( 'meta_key', 'featured', 'attachment', 'scan', 'scan_raw', 'callback', 'default' ),

			// Methods of getting an image (in order).
			'meta_key'           => array( 'Thumbnail', 'thumbnail' ), // array|string
			'featured'           => true,
			'attachment'         => true,
			'scan'               => false,
			'scan_raw'           => false, // Note: don't use the array format option with this.
			'callback'           => null,
			'default'            => false,

			// Split image from post content (by default, only used with the 'scan_raw' option).
			'split_content'      => false,

			// Attachment-specific arguments.
			'size'               => has_image_size( 'post-thumbnail' ) ? 'post-thumbnail' : 'thumbnail',

			// Key (image size) / Value ( width or px-density descriptor) pairs (e.g., 'large' => '2x' )
			'srcset_sizes'       => array(),

			// Format/display of image.
			'link'               => 'post', // string|bool - 'post' (true), 'file', 'attachment', false
			'link_class'         => '',
			'image_class'        => false,
			'image_attr'         => array(),
			'width'              => false,
			'height'             => false,
			'before'             => '',
			'after'              => '',

			// Minimum allowed sizes.
			'min_width'          => 0,
			'min_height'         => 0,

			// Captions.
			'caption'            => false, // Default WP [caption] requires a width.

			// Saving the image.
			'meta_key_save'      => false, // Save as metadata (string).
			'thumbnail_id_save'  => false, // Set 'featured image'.
			'cache'              => true,  // Cache the image.

			// Return/echo image.
			'format'             => 'img',
			'echo'               => true,

			// Deprecated arguments.
			'custom_key'         => null, // @deprecated 0.6.0 Use 'meta_key'.
			'default_size'       => null, // @deprecated 0.5.0 Use 'size'.
			'the_post_thumbnail' => null, // @deprecated 1.0.0 Use 'featured'.
			'image_scan'         => null, // @deprecated 1.0.0 Use 'scan' or 'scan_raw'.
			'default_image'      => null, // @deprecated 1.0.0 Use 'default'.
			'order_of_image'     => null, // @deprecated 1.0.0 No replacement.
			'link_to_post'       => null, // @deprecated 1.1.0 Use 'link'.
		);

		// Allow plugins/themes to filter the arguments.
		$this->args = apply_filters(
			'get_the_image_args',
			wp_parse_args( $args, $defaults )
		);

		// If no post ID, return.
		if ( empty( $this->args['post_id'] ) )
			return false;

		/* === Handle deprecated arguments. === */

		// If $default_size is given, overwrite $size.
		if ( !is_null( $this->args['default_size'] ) )
			$this->args['size'] = $this->args['default_size'];

		// If $custom_key is set, overwrite $meta_key.
		if ( !is_null( $this->args['custom_key'] ) )
			$this->args['meta_key'] = $this->args['custom_key'];

		// If 'the_post_thumbnail' is set, overwrite 'featured'.
		if ( !is_null( $this->args['the_post_thumbnail'] ) )
			$this->args['featured'] = $this->args['the_post_thumbnail'];

		// If 'image_scan' is set, overwrite 'scan'.
		if ( !is_null( $this->args['image_scan'] ) )
			$this->args['scan'] = $this->args['image_scan'];

		// If 'default_image' is set, overwrite 'default'.
		if ( !is_null( $this->args['default_image'] ) )
			$this->args['default'] = $this->args['default_image'];

		// If 'link_to_post' is set, overwrite 'link'.
		if ( !is_null( $this->args['link_to_post'] ) )
			$this->args['link'] = true === $this->args['link_to_post'] ? 'post' : false;

		/* === End deprecated arguments. === */

		// If $format is set to 'array', don't link to the post.
		if ( 'array' == $this->args['format'] )
			$this->args['link'] = false;

		// Find images.
		$this->find();

		// Only used if $original_image is set.
		if ( true === $this->args['split_content'] && !empty( $this->original_image ) )
			add_filter( 'the_content', array( $this, 'split_content' ), 9 );
	}

	/**
	 * Returns the image HTML or image array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_image() {

		// Allow plugins/theme to override the final output.
		$image_html = apply_filters( 'get_the_image', $this->image );

		// If $format is set to 'array', return an array of image attributes.
		if ( 'array' === $this->args['format'] ) {

			// Set up a default empty array.
			$out = array();

			// Get the image attributes.
			$atts = wp_kses_hair( $image_html, array( 'http', 'https' ) );

			// Loop through the image attributes and add them in key/value pairs for the return array.
			foreach ( $atts as $att )
				$out[ $att['name'] ] = $att['value'];

			// Return the array of attributes.
			return $out;
		}

		// Or, if $echo is set to false, return the formatted image.
		elseif ( false === $this->args['echo'] ) {
			return !empty( $image_html ) ? $this->args['before'] . $image_html . $this->args['after'] : $image_html;
		}

		// If there is a $post_thumbnail_id, do the actions associated with get_the_post_thumbnail().
		if ( isset( $this->image_args['post_thumbnail_id'] ) )
			do_action( 'begin_fetch_post_thumbnail_html', $this->args['post_id'], $this->image_args['post_thumbnail_id'], $this->args['size'] );

		// Display the image if we get to this point.
		echo !empty( $image_html ) ? $this->args['before'] . $image_html . $this->args['after'] : $image_html;

		// If there is a $post_thumbnail_id, do the actions associated with get_the_post_thumbnail().
		if ( isset( $this->image_args['post_thumbnail_id'] ) )
			do_action( 'end_fetch_post_thumbnail_html', $this->args['post_id'], $this->image_args['post_thumbnail_id'], $this->args['size'] );
	}

	/**
	 * Figures out if we have an image related to the post. Runs through the various methods of getting
	 * an image.  If there's a cached image, we'll just use that.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function find() {

		// Get cache key based on $this->args.
		$key = md5( serialize( compact( array_keys( $this->args ) ) ) );

		// Check for a cached image.
		$image_cache = wp_cache_get( $this->args['post_id'], 'get_the_image' );

		if ( !is_array( $image_cache ) )
			$image_cache = array();

		// If there is no cached image, let's see if one exists.
		if ( !isset( $image_cache[ $key ] ) || empty( $cache ) ) {

			foreach ( $this->args['order'] as $method ) {

				if ( !empty( $this->image ) || !empty( $this->image_args ) )
					break;

				if ( 'meta_key' === $method && !empty( $this->args['meta_key'] ) )
					$this->get_meta_key_image();

				elseif ( 'featured' === $method && true === $this->args['featured'] )
					$this->get_featured_image();

				elseif ( 'attachment' === $method && true === $this->args['attachment'] )
					$this->get_attachment_image();

				elseif ( 'scan' === $method && true === $this->args['scan'] )
					$this->get_scan_image();

				elseif ( 'scan_raw' === $method && true === $this->args['scan_raw'])
					$this->get_scan_raw_image();

				elseif ( 'callback' === $method && !is_null( $this->args['callback'] ) )
					$this->get_callback_image();

				elseif ( 'default' === $method && !empty( $this->args['default'] ) )
					$this->get_default_image();
			}

			// Format the image HTML.
			if ( empty( $this->image ) && !empty( $this->image_args ) )
				$this->format_image();

			// If we have image HTML.
			if ( !empty( $this->image ) ) {

				// Save the image as metadata.
				if ( !empty( $this->args['meta_key_save'] ) )
					$this->meta_key_save();

				// Set the image cache for the specific post.
				$image_cache[ $key ] = $this->image;
				wp_cache_set( $this->args['post_id'], $image_cache, 'get_the_image' );
			}
		}

		// If an image was already cached for the post and arguments, use it.
		else {
			$this->image = $image_cache[ $key ];
		}
	}

	/**
	 * Gets a image by post meta key.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_meta_key_image() {

		// If $meta_key is not an array.
		if ( !is_array( $this->args['meta_key'] ) )
			$this->args['meta_key'] = array( $this->args['meta_key'] );

		// Loop through each of the given meta keys.
		foreach ( $this->args['meta_key'] as $meta_key ) {

			// Get the image URL by the current meta key in the loop.
			$image = get_post_meta( $this->args['post_id'], $meta_key, true );

			// If an image was found, break out of the loop.
			if ( !empty( $image ) )
				break;
		}

		// If there's an image and it is numeric, assume it is an attachment ID.
		if ( !empty( $image ) && is_numeric( $image ) )
			$this->_get_image_attachment( absint( $image ) );

		// Else, assume the image is a file URL.
		elseif ( !empty( $image ) )
			$this->image_args = array( 'src' => $image );
	}

	/**
	 * Gets the featured image (i.e., WP's post thumbnail).
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_featured_image() {

		// Check for a post image ID (set by WP as a custom field).
		$post_thumbnail_id = get_post_thumbnail_id( $this->args['post_id'] );

		// If no post image ID is found, return.
		if ( empty( $post_thumbnail_id ) )
			return;

		// Apply filters on post_thumbnail_size because this is a default WP filter used with its image feature.
		$this->args['size'] = apply_filters( 'post_thumbnail_size', $this->args['size'] );

		// Set the image args.
		$this->_get_image_attachment( $post_thumbnail_id );

		// Add the post thumbnail ID.
		if ( $this->image_args )
			$this->image_args['post_thumbnail_id'] = $post_thumbnail_id;
	}

	/**
	 * Gets the first image attached to the post.  If the post itself is an attachment image, that will
	 * be the image used.  This method also works with sub-attachments (images for audio/video attachments
	 * are a good example).
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_attachment_image() {

		// Check if the post itself is an image attachment.
		if ( wp_attachment_is_image( $this->args['post_id'] ) ) {
			$attachment_id = $this->args['post_id'];
		}

		// If the post is not an image attachment, check if it has any image attachments.
		else {

			// Get attachments for the inputted $post_id.
			$attachments = get_children(
				array(
					'numberposts'      => 1,
					'post_parent'      => $this->args['post_id'],
					'post_status'      => 'inherit',
					'post_type'        => 'attachment',
					'post_mime_type'   => 'image',
					'order'            => 'ASC',
					'orderby'          => 'menu_order ID',
					'fields'           => 'ids'
				)
			);

			// Check if any attachments were found.
			if ( !empty( $attachments ) )
				$attachment_id = array_shift( $attachments );
		}

		if ( !empty( $attachment_id ) )
			$this->_get_image_attachment( $attachment_id );
	}

	/**
	 * Scans the post content for an image.  It first scans and checks for an image with the
	 * "wp-image-xxx" ID.  If that exists, it'll grab the actual image attachment.  If not, it looks
	 * for the image source.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_scan_image() {

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->args['post_id'] );

		// Apply filters to content.
		$post_content = apply_filters( 'get_the_image_post_content', $post_content );

		// Check the content for `id="wp-image-%d"`.
		preg_match( '/id=[\'"]wp-image-([\d]*)[\'"]/i', $post_content, $image_ids );

		// Loop through any found image IDs.
		if ( is_array( $image_ids ) ) {

			foreach ( $image_ids as $image_id ) {
				$this->_get_image_attachment( $image_id );

				if ( !empty( $this->image_args ) )
					return;
			}
		}

		// Search the post's content for the <img /> tag and get its URL.
		preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches );

		// If there is a match for the image, set the image args.
		if ( isset( $matches ) && !empty( $matches[1][0] ) )
			$this->image_args = array( 'src' => $matches[1][0] );
	}

	/**
	 * Scans the post content for a complete image.  This method will attempt to grab the complete
	 * HTML for an image.  If an image is found, pretty much all arguments passed in may be ignored
	 * in favor of getting the actual image used in the post content.  It works with both captions
	 * and linked images.  However, it can't account for all possible HTML wrappers for images used
	 * in all setups.
	 *
	 * This method was created for use with the WordPress "image" post format where theme authors
	 * might want to pull the whole image from the content as the user added it.  It's also meant
	 * to be used (not required) with the `split_content` option.
	 *
	 * Note: This option should not be used if returning the image as an array.  If that's desired,
	 * use the `scan` option instead.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_scan_raw_image() {

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->args['post_id'] );

		// Apply filters to content.
		$post_content = apply_filters( 'get_the_image_post_content', $post_content );

		// Finds matches for shortcodes in the content.
		preg_match_all( '/' . get_shortcode_regex() . '/s', $post_content, $matches, PREG_SET_ORDER );

		if ( !empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				if ( in_array( $shortcode[2], array( 'caption', 'wp_caption' ) ) ) {

					preg_match( '#id=[\'"]attachment_([\d]*)[\'"]|class=[\'"].*?wp-image-([\d]*).*?[\'"]#i', $shortcode[0], $matches );

					if ( !empty( $matches ) && isset( $matches[1] ) || isset( $matches[2] ) ) {

						$attachment_id = !empty( $matches[1] ) ? absint( $matches[1] ) : absint( $matches[2] );

						$image_src = wp_get_attachment_image_src( $attachment_id, $this->args['size'] );

						if ( !empty( $image_src ) ) {

							// Old-style captions.
							if ( preg_match( '#.*?[\s]caption=[\'"](.+?)[\'"]#i', $shortcode[0], $caption_matches ) )
								$image_caption = trim( $caption_matches[1] );

							$caption_args = array(
								'width'   => $image_src[1],
								'align'   => 'center'
							);

							if ( !empty( $image_caption ) )
								$caption_args['caption'] = $image_caption;

							// Set up the patterns for the 'src', 'width', and 'height' attributes.
							$patterns = array(
								'/(src=[\'"]).+?([\'"])/i',
								'/(width=[\'"]).+?([\'"])/i',
								'/(height=[\'"]).+?([\'"])/i',
							);

							// Set up the replacements for the 'src', 'width', and 'height' attributes.
							$replacements = array(
								'${1}' . $image_src[0] . '${2}',
								'${1}' . $image_src[1] . '${2}',
								'${1}' . $image_src[2] . '${2}',
							);

							// Filter the image attributes.
							$shortcode_content = preg_replace( $patterns, $replacements, $shortcode[5] );

							$this->image          = img_caption_shortcode( $caption_args, $shortcode_content );
							$this->original_image = $shortcode[0];
							return;
						}
						else {
							$this->image          = do_shortcode( $shortcode[0] );
							$this->original_image = $shortcode[0];
							return;
						}
					}
				}
			}
		}

		// Pull a raw HTML image + link if it exists.
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)#is', $post_content, $matches ) )
			$this->image = $this->original_image = $matches[0];
	}

	/**
	 * Allows developers to create a custom callback function.  If the `callback` argument is set, theme
	 * developers are expected to **always** return an array.  Even if nothing is found, return an empty
	 * array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_callback_image() {
		$this->image_args = call_user_func( $this->args['callback'], $this->args );
	}

	/**
	 * Sets the default image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_default_image() {
		$this->image_args = array( 'src' => $this->args['default'] );
	}

	/**
	 * Handles an image attachment.  Other methods rely on this method for getting the image data since
	 * most images are actually attachments.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $attachment_id
	 * @return void
	 */
	public function _get_image_attachment( $attachment_id ) {

		// Get the attachment image.
		$image = wp_get_attachment_image_src( $attachment_id, $this->args['size'] );

		// Get the attachment alt text.
		$alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );

		// Get the attachment caption.
		$caption = get_post_field( 'post_excerpt', $attachment_id );

		// Only use the image if we have an image and it meets the size requirements.
		if ( ! $image || ! $this->have_required_dimensions( $image[1], $image[2] ) )
			return;

		// Save the attachment as the 'featured image'.
		if ( true === $this->args['thumbnail_id_save'] )
			$this->thumbnail_id_save( $attachment_id );

		// Set the image args.
		$this->image_args = array(
			'id'      => $attachment_id,
			'src'     => $image[0],
			'width'   => $image[1],
			'height'  => $image[2],
			'alt'     => $alt,
			'caption' => $caption
		);

		// Get the image srcset sizes.
		$this->get_srcset( $attachment_id );
	}

	/**
	 * Adds array of srcset image sources and descriptors based on the `srcset_sizes` argument
	 * provided by the developer.
	 *
	 * @since  1.1.0
	 * @access public
	 * @param  int     $attachment_id
	 * @return void
	 */
	public function get_srcset( $attachment_id ) {

		// Bail if no sizes set.
		if ( empty( $this->args['srcset_sizes'] ) )
			return;

		foreach ( $this->args['srcset_sizes'] as $size => $descriptor ) {

			$image = wp_get_attachment_image_src( $attachment_id, $size );

			// Make sure image doesn't match the image used for the `src` attribute.
			// This will happen often if the particular image size doesn't exist.
			if ( $this->image_args['src'] !== $image[0] )
				$this->srcsets[] = sprintf( "%s %s", esc_url( $image[0] ), esc_attr( $descriptor ) );
		}
	}

	/**
	 * Formats the image HTML.  This method is only called if the `$image` property isn't set.  It uses
	 * the `$image_args` property to set up the image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function format_image() {

		// If there is no image URL, return false.
		if ( empty( $this->image_args['src'] ) )
			return;

		// Check against min. width and height. If the image is too small return.
		if ( isset( $this->image_args['width'] ) || isset( $this->image_args['height'] ) ) {

			$_w = isset( $this->image_args['width'] )  ? $this->image_args['width']  : false;
			$_h = isset( $this->image_args['height'] ) ? $this->image_args['height'] : false;

			if ( ! $this->have_required_dimensions( $_w, $_h ) )
				return;
		}

		// Set up a variable for the image attributes.
		$img_attr = '';

		// Loop through the image attributes and format them for display.
		foreach ( $this->get_image_attr() as $name => $value )
			$img_attr .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );

		// Add the image attributes to the <img /> element.
		$html = sprintf( '<img %s />', $img_attr );

		// If $link is set to true, link the image to its post.
		if ( false !== $this->args['link'] ) {

			if ( 'post' === $this->args['link'] || true === $this->args['link'] )
				$url = get_permalink( $this->args['post_id'] );

			elseif ( 'file' === $this->args['link'] )
				$url = $this->image_args['src'];

			elseif ( 'attachment' === $this->args['link'] && isset( $this->image_args['id'] ) )
				$url = get_permalink( $this->image_args['id'] );

			if ( ! empty( $url ) ) {

				$link_class = $this->args['link_class'] ? sprintf( ' class="%s"', esc_attr( $this->args['link_class'] ) ) : '';

				$html = sprintf( '<a href="%s"%s>%s</a>', esc_url( $url ), $link_class, $html );
			}
		}

		// If there is a $post_thumbnail_id, apply the WP filters normally associated with get_the_post_thumbnail().
		if ( ! empty( $this->image_args['post_thumbnail_id'] ) )
			$html = apply_filters( 'post_thumbnail_html', $html, $this->args['post_id'], $this->image_args['post_thumbnail_id'], $this->args['size'], '' );

		// If we're showing a caption.
		if ( true === $this->args['caption'] && ! empty( $this->image_args['caption'] ) )
			$html = img_caption_shortcode( array( 'caption' => $this->image_args['caption'], 'width' => $this->args['width'] ), $html );

		$this->image = $html;
	}

	/**
	 * Sets up and returns an array of attributes for the final `<img>` element.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return array
	 */
	public function get_image_attr() {

		$attr = array();

		// Add the image class.
		$attr['class'] = join( ' ', $this->get_image_class() );

		// If there's a width/height for the image.
		if ( isset( $this->image_args['width'] ) && isset( $this->image_args['height'] ) ) {

			// If an explicit width/height is not set, use the info from the image.
			if ( ! $this->args['width'] && ! $this->args['height'] ) {

				$this->args['width']  = $this->image_args['width'];
				$this->args['height'] = $this->image_args['height'];
			}
		}

		// If there is a width or height, set them.
		if ( $this->args['width'] )
			$attr['width'] = $this->args['width'];

		if ( $this->args['height'] )
			$attr['height'] = $this->args['height'];

		// If there is alt text, set it.  Otherwise, default to the post title.
		$attr['alt'] = ! empty( $this->image_args['alt'] ) ? $this->image_args['alt'] : get_post_field( 'post_title', $this->args['post_id'] );

		// Add the itemprop attribute.
		$attr['itemprop'] = 'image';

		// Parse the args with the user inputted args.
		$attr = wp_parse_args( $this->args['image_attr'], $attr );

		// Allow devs to filter the image attributes.
		$attr = apply_filters( 'get_the_image_attr', $attr, $this );

		// Add the image source after the filter so that it can't be overwritten.
		$attr['src'] = $this->image_args['src'];

		// Return attributes.
		return $attr;
	}

	/**
	 * Sets up and returns an array of classes for the `<img>` element.
	 *
	 * @since  1.1.0
	 * @access public
	 * @global int     $content_width
	 * @return array
	 */
	public function get_image_class() {
		global $content_width;

		$classes = array();

		// Get true image height and width.
		$width  = isset( $this->image_args['width'] )  ? $this->image_args['width']  : false;
		$height = isset( $this->image_args['height'] ) ? $this->image_args['height'] : false;

		// If there's a width/height for the image.
		if ( $width && $height ) {

			// Set a class based on the orientation.
			$classes[] = $height > $width ? 'portrait' : 'landscape';

			// Set class based on the content width (defined by theme).
			if ( 0 < $content_width ) {

				if ( $content_width == $width )
					$classes[] = 'cw-equal';

				elseif ( $content_width <= $width )
					$classes[] = 'cw-lesser';

				elseif ( $content_width >= $width )
					$classes[] = 'cw-greater';
			}
		}

		// Add the meta key(s) to the classes array.
		if ( ! empty( $this->args['meta_key'] ) )
			$classes = array_merge( $classes, (array)$this->args['meta_key'] );

		// Add the $size to the class.
		$classes[] = $this->args['size'];

		// Get the custom image class.
		if ( ! empty( $this->args['image_class'] ) ) {

			if ( ! is_array( $this->args['image_class'] ) )
				$this->args['image_class'] = preg_split( '#\s+#', $this->args['image_class'] );

			$classes = array_merge( $classes, $this->args['image_class'] );
		}

		return apply_filters( 'get_the_image_class', $this->sanitize_class( $classes ), $this );
	}

	/**
	 * Saves the image source as metadata.  Saving the image as meta is actually quite a bit quicker
	 * if the user doesn't have a persistent caching plugin available.  However, it doesn't play as
	 * nicely with custom image sizes used across multiple themes where one might want to resize images.
	 * This option should be reserved for advanced users only.  Don't use in publicly-distributed
	 * themes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_key_save() {

		// If the $meta_key_save argument is empty or there is no image $url given, return.
		if ( empty( $this->args['meta_key_save'] ) || empty( $this->image_args['src'] ) )
			return;

		// Get the current value of the meta key.
		$meta = get_post_meta( $this->args['post_id'], $this->args['meta_key_save'], true );

		// If there is no value for the meta key, set a new value with the image $url.
		if ( empty( $meta ) )
			add_post_meta( $this->args['post_id'], $this->args['meta_key_save'], $this->image_args['src'] );

		// If the current value doesn't match the image $url, update it.
		elseif ( $meta !== $this->image_args['src'] )
			update_post_meta( $this->args['post_id'], $this->args['meta_key_save'], $this->image_args['src'], $meta );
	}

	/**
	 * Saves the image attachment as the WordPress featured image.  This is useful for setting the
	 * featured image for the post in the case that the user forgot to (win for client work!).  It
	 * should not be used in publicly-distributed themes where you don't know how the user will be
	 * setting up their site.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function thumbnail_id_save( $attachment_id ) {

		// Save the attachment as the 'featured image'.
		if ( true === $this->args['thumbnail_id_save'] )
			set_post_thumbnail( $this->args['post_id'], $attachment_id );
	}

	/**
	 * Sanitizes the image class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $classes
	 * @return array
	 */
	public function sanitize_class( $classes ) {

		$classes = array_map( 'strtolower',          $classes );
		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}

	/**
	 * Splits the original image HTML from the post content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $content
	 * @return string
	 */
	public function split_content( $content ) {

		remove_filter( 'the_content', array( $this, 'split_content' ), 9 );

		return str_replace( $this->original_image, '', $content );
	}

	/**
	 * Checks if the image meets the minimum size requirements.
	 *
	 * @since  1.1.0
	 * @access public
	 * @param  int|bool  $width
	 * @param  int|bool  $height
	 * @return bool
	 */
	public function have_required_dimensions( $width = false, $height = false ) {

		// Check against min. width. If the image width is too small return.
		if ( 0 < $this->args['min_width'] && $width && $width < $this->args['min_width'] )
			return false;

		// Check against min. height. If the image height is too small return.
		if ( 0 < $this->args['min_height'] && $height && $height < $this->args['min_height'] )
			return false;

		return true;
	}
}

/**
 * Deletes the image cache for the specific post when the 'save_post' hook is fired.
 *
 * @since  0.7.0
 * @access private
 * @param  int      $post_id  The ID of the post to delete the cache for.
 * @return void
 */
function get_the_image_delete_cache_by_post( $post_id ) {
	wp_cache_delete( $post_id, 'get_the_image' );
}

/**
 * Deletes the image cache for a specific post when the 'added_post_meta', 'deleted_post_meta',
 * or 'updated_post_meta' hooks are called.
 *
 * @since  0.7.0
 * @access private
 * @param  int      $meta_id  The ID of the metadata being updated.
 * @param  int      $post_id  The ID of the post to delete the cache for.
 * @return void
 */
function get_the_image_delete_cache_by_meta( $meta_id, $post_id ) {
	wp_cache_delete( $post_id, 'get_the_image' );
}


/* === Deprecated functions === */


/**
 * @since      0.1.0
 * @deprecated 0.3.0
 * @access     public
 */
function get_the_image_link() {
	_deprecated_function( __FUNCTION__, '0.3.0', 'get_the_image' );
	get_the_image( array( 'link_to_post' => true ) );
}

/**
 * @since      0.3.0
 * @deprecated 0.7.0
 * @access     private
 */
function image_by_custom_field() {}

/**
 * @since      0.4.0
 * @deprecated 0.7.0
 * @access     private
 */
function image_by_the_post_thumbnail() {}

/**
 * @since      0.3.0
 * @deprecated 0.7.0
 * @access     private
 */
function image_by_attachment() {}

/**
 * @since      0.3.0
 * @deprecated 0.7.0
 * @access     private
 */
function image_by_scan() {}

/**
 * @since      0.3.0
 * @deprecated 0.7.0
 * @access     private
 */
function image_by_default() {}

/**
 * @since      0.1.0
 * @deprecated 0.7.0
 * @access     private
 */
function display_the_image() {}

/**
 * @since      0.5.0
 * @deprecated 0.7.0
 * @access     private
 */
function get_the_image_delete_cache() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_by_meta_key() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_by_post_thumbnail() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_by_attachment() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_by_scan() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_by_default() {}

/**
 * @since      0.7.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_format() {}

/**
 * @since      0.6.0
 * @deprecated 1.0.0
 * @access     private
 */
function get_the_image_meta_key_save() {}
?>

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
	* @print string current paginated page
	*/
	static function if_paged(){
		$paged = get_query_var('paged', 0);
		if($paged !== 0) {
			echo " ($paged)";
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