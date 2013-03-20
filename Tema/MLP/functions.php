<?php

function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
}

function custom_excerpt_more($more) {
	global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Läs hela...</a>';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

if ( !defined( 'BP_DTHEME_DISABLE_CUSTOM_HEADER' ) ) {
	define( 'HEADER_TEXTCOLOR', '222' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to bp_dtheme_header_image_width and bp_dtheme_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH',  apply_filters( 'bp_dtheme_header_image_width',  1060 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'bp_dtheme_header_image_height', 500  ) );

	// We'll be using post thumbnails for custom header images on posts and pages. We want them to be 1250 pixels wide by 133 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add a way for the custom header to be styled in the admin panel that controls custom headers.
	$custom_header_args = array(
		'wp-head-callback' => 'bp_dtheme_header_style',
		'admin-head-callback' => 'bp_dtheme_admin_header_style'
	);
	add_theme_support( 'custom-header', $custom_header_args );
}

// Gör så att buddypress är kompatibelt för nyare versioner av WP än 3.3 då Buddypress kräver att man använder jQuery 1.7 och inte nyare versioner än 1.8.
wp_deregister_script('jquery');