<?php

/**
 * Handle fetchpriority="high" on images.
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_get_loading_optimization_attributes/
 *
 * @param string    $tag_name   The tag name (e.g., 'img').
 * @param array     $attr       The attributes for the image element.
 * @param string    $context    The context in which the image is being loaded.
 * @return array    Modified attributes for the image element.
 */
function digivon_control_fetchpriority( $tag_name, $attr, $context ) {
    // Ensure $attr is an array
    if ( ! is_array( $attr ) ) {
        $attr = [];
    }
    
    // Ensure no conflicts between loading="lazy" and fetchpriority="high"
    if ( isset( $attr['loading'] ) && $attr['loading'] === 'lazy' ) {
        unset( $attr['fetchpriority'] );
    }

    // Apply eager loading and keep fetchpriority="high" for post thumbnails on singular posts
    if ( is_singular( 'post' ) && $context === 'post_thumbnail' ) {
        $attr['loading'] = 'eager';
        $attr['fetchpriority'] = 'high';
        return $attr;
    }

    // Remove fetchpriority="high" for all other contexts
    unset( $attr['fetchpriority'] );

    // Lazy load images by default if not a post thumbnail
    $attr['loading'] = 'lazy';

    return $attr;
}
add_filter( 'wp_get_loading_optimization_attributes', 'digivon_control_fetchpriority', 10, 3 );
