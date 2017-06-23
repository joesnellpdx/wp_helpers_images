<?php

/**
 * Adds custom image sizes to WP based on the needs of the theme.
 *
 * @return void
 */
function custom_image_sizes() {
	// custom image sizes
	add_image_size( 'rwd-six-five', 330, 275 );
	add_image_size( 'rwd-tiny', 200, 100 );

	// general responsive image sizes (2:1) -- fit semantics for image functions
	add_image_size( 'rwd-pri-sm', 400, 200 );
	add_image_size( 'rwd-pri-md', 800, 400 );
	add_image_size( 'rwd-pri-lg', 1200, 600 );
	add_image_size( 'rwd-pri-xlg', 1600, 800 );
	add_image_size( 'rwd-pri-xxlg', 2000, 1000 );
	add_image_size( 'rwd-pri-xxlg', 2400, 1200 );
	add_image_size( 'rwd-pri-xxxl', 3200, 1200 );
	add_image_size( 'rwd-pri-xxxxlg', 4000, 2000 );

	// square image sizes -- cropped
	add_image_size( 'rwd-sq-sm', 400, 400, true );
	add_image_size( 'rwd-sq-md', 800, 800, true );
	add_image_size( 'rwd-sq-lg', 1200, 1300, true );
	add_image_size( 'rwd-sq-xlg', 1600, 1600, true );
	add_image_size( 'rwd-sq-xxlg', 2400, 2400, true );
	add_image_size( 'rwd-sq-xxxlg', 3200, 3200, true );

	// rectangle image sizes -- cropped (16:9)
	add_image_size( 'rwd-rect-sm', 400, 225, true );
	add_image_size( 'rwd-rect-md', 800, 450, true );
	add_image_size( 'rwd-rect-lg', 1200, 675, true );
	add_image_size( 'rwd-rect-xlg', 1600, 900, true );
	add_image_size( 'rwd-rect-xxlg', 2400, 1350, true );
	add_image_size( 'rwd-rect-xxxlg', 3200, 1800, true );

	// portrait image sizes -- cropped (9:16)
	add_image_size( 'rwd-port-sm', 400, 711, true );
	add_image_size( 'rwd-port-md', 800, 1422, true );
	add_image_size( 'rwd-port-lg', 1200, 23133, true );
	add_image_size( 'rwd-port-xlg', 1600, 2844, true );
	add_image_size( 'rwd-port-xxlg', 2400, 4267, true );
	add_image_size( 'rwd-port-xxxlg', 3200, 5689, true );
}

/**
 * Outputs a Responsive Image tag with optional link.
 *
 * @param  int     $image_id The image attachment ID.
 * @param  bool    $echo     Whether to echo (true) or return (false) Default
 *                           true.
 * @param  string  $sizes    The image size.
 * @param  boolean $use_link Link to attachment when true.
 * @param  string  $fallback The fallback image size.
 * @param  string  $image_size The image size (i.e. rwd-pri-sm).
 * @return void
 */
function the_rwd_image( $image_id, $echo = true, $sizes = '100vw', $use_link = false, $fallback = 'rwd-pri-xxlg', $image_size = 'full' ) {
	$img_src      = wp_get_attachment_image_url( $image_id, 'rwd-pri-sm' );
	$img_fallback = wp_get_attachment_image_url( $image_id, $fallback );
	$srcset_value = get_srcset( $image_id, $image_size )[0];
	$srcset       = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
	$alt          = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

	if ( ! $img_src ) {
		return;
	}

	$output = '';

	if ( $use_link === true ) {
		$output = sprintf( '<a href="%s" rel="bookmark" %s>', esc_url( get_permalink() ) );
	}

	$output .= sprintf(
		'<img src="%s" %s sizes="%s" alt="%s" data-fallback-img="%s">',
		esc_url( $img_src ),
		$srcset,
		esc_attr( $sizes ),
		esc_attr( $alt ),
		esc_url( $img_fallback )
	);


	if ( true === $use_link ) {
		$output .= '</a>';
	}

	// Depending on echo param, echo or return.
	if ( true === $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Outputs a Responsive Image fit figure with optional fallback.
 *
 * @param  int     $image_id    The image attachment ID.
 * @param  string  $class       The image class attribute.
 * @param  boolean $echo        Whether to echo (true) or return (false)
 *                              Default true.
 * @param  string  $sizes       The image size.
 * @param  boolean $use_link    Link to attachment when true.
 * @param  boolean $use_caption The image caption.
 * @param  string  $fallback    The fallback image size.
 * @return void
 */
function the_img_fit_figure( $image_id, $class, $sizes = '100vw', $fallback = 'rwd-pri-xxlg', $echo = true, $use_link = false, $use_caption = false ) {

	if ( empty( $image_id ) ) {
		return;
	}

	$img_src = wp_get_attachment_image_url( $image_id, 'rwd-pri-sm' );

	if ( empty( $img_src ) ) {
		return;
	}

	$img_post = get_post( $image_id );

	if ( ! empty( $img_post ) ) {
		$image_caption = $img_post->post_excerpt;
	}

	if ( ! empty( $image_caption ) && ( true === $use_caption ) ) {
		$class .= ' wp-caption';
	}

	$figcaption = ( ! empty( $image_caption ) && ( true === $use_caption ) ) ?
		sprintf( '<figcaption class="wp-caption-text">%1$s</figcaption>', esc_html( $image_caption ) ) :
		'';

	$output = sprintf(
		'<figure class="%1$s img-fit" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
			%2$s
			%3$s
		</figure>',
		esc_attr( $class ),
		the_rwd_image( $image_id, false, $sizes, $use_link, $fallback ),
		$figcaption
	);

	// Depending on echo param, echo or return.
	if ( true === $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Output Art Directed <picuture> element
 * @param   $img_start_id
 * @param   $img_end_id
 *
 * @return  string|void
 */
function picture_art_direct( $img_start_id, $img_end_id, $type = 'wide' ) {

	if ( empty ( $img_start_id ) || empty( $img_end_id ) ) {
		return;
	}

	$full_size = 'rwd-rect-xl';
	$full_fallback = 'rwd-rect-xl';
	$full_sizes = '50vw';
	$start_size = 'rwd-sq-lg';
	$start_fallback = 'rwd-sq-lg';
	$start_sizes = '100vw';
	$breakpoint = 768;

	$alt = get_post_meta( $img_end_id, '_wp_attachment_image_alt', true);
	$img_full_srcset = get_srcset($img_end_id, $full_size )[0];
	$img_cropped_srcset = get_srcset( $img_start_id, $start_size )[0];
	$img_full_src = wp_get_attachment_image_url( $img_end_id, get_srcset($img_end_id, $full_fallback )[1] );
	$img_start_src = wp_get_attachment_image_url( $img_start_id, get_srcset($img_start_id, $start_fallback )[1] );

	if ( $img_full_srcset && $img_cropped_srcset ) {
		$output = sprintf(
			'<picture class="ms-card__img">
						<source
							media="(min-width: %1$spx)"
							srcset="%2$s"
							sizes="%3$s" />
						<img srcset="%4$s"
						     alt="%5$s"
						     sizes="%6$s" data-fallback-img="%7$s" data-fallback-img-sm="%8$s"/>
					</picture>',
			esc_attr( $breakpoint),
			esc_attr( $img_full_srcset ),
			esc_attr( $full_sizes ),
			esc_attr( $img_cropped_srcset ),
			esc_attr( $alt ),
			esc_attr( $start_sizes ),
			esc_attr( $img_full_src ),
			esc_attr( $img_start_src )
		);
	} else {
		$output = sprintf(
			'<picture class="ms-card__img">%1$s</picture>',
			the_rwd_image( $img_end_id, false )
		);
	}

	return $output;
}

/**
 * Get available image srcset
 *
 * @param $image_id             The image ID
 * @param $desired_img_size     Desired image size (i.e. rwd-sq-md)
 *
 * @return bool|string
 */
function get_srcset( $image_id, $desired_img_size ) {

	$img_src = wp_get_attachment_image_src(  $image_id, $desired_img_size );
	$image_meta = wp_get_attachment_metadata( $image_id );

	preg_match('~-(.*?)-~', $desired_img_size, $output);

	if( empty ( $output[1] ) ) {
		$image_type = 'pri';
	} else {
		$image_type = $output[1];
	}

	// override falling back to 'pri' image ratios if specific size doesn't exist
	if( 'sq' === $image_type ) {
		$size_array = array(
			400,
			400
		);
	} elseif( 'rect' === $image_type ) {
		$size_array = array(
			400,
			225
		);
	} elseif( 'port' === $image_type ) {
		$size_array = array(
			400,
			711
		);
	} else {
		$size_array = array(
			absint( $img_src[1] ),
			absint( $img_src[2] )
		);
	}

	$img_srcset = wp_calculate_image_srcset( $size_array, $img_src, $image_meta, $image_id );

	// Get largest existing image size by type
	$img_srcset_raw = wp_get_attachment_image_srcset( $image_id, $desired_img_size );
	if( empty ( $img_srcset_raw ) ) {
		$image_size_array = array(
			'xxxlg', 'xxlg', 'xlg', 'lg', 'md', 'sm'
		);

		foreach ( $image_size_array as $size ) {
			$desired_img_size = 'rwd-' . $image_type . '-' . $size;
			$img_srcset_raw  = wp_get_attachment_image_srcset( $image_id, $desired_img_size );
			if( ! empty( $img_srcset_raw  ) ){
				break;
			}
		}

		if ( empty ( $img_srcset_raw  ) ) {
			$desired_img_size = 'full';
			$img_srcset_raw  = wp_get_attachment_image_srcset( $image_id, $desired_img_size );
		}
	}

	if( empty ($img_srcset ) ) {
		$img_srcset = $img_srcset_raw;
	}
	return array( $img_srcset, $desired_img_size );
}