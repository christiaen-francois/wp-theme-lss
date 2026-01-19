<?php
/**
 * Image Helper Functions
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the URL of an image from the theme assets/images directory
 *
 * @param string $path Relative path from assets/images/ (e.g., 'logos/logo.svg')
 * @return string Full URL to the image
 */
function lunivers_get_image_url( $path ) {
	return LUNIVERS_THEME_URI . '/assets/images/' . ltrim( $path, '/' );
}

/**
 * Get the path of an image from the theme assets/images directory
 *
 * @param string $path Relative path from assets/images/ (e.g., 'logos/logo.svg')
 * @return string Full path to the image
 */
function lunivers_get_image_path( $path ) {
	return LUNIVERS_THEME_PATH . '/assets/images/' . ltrim( $path, '/' );
}

/**
 * Check if an image exists in the theme assets/images directory
 *
 * @param string $path Relative path from assets/images/ (e.g., 'logos/logo.svg')
 * @return bool True if image exists, false otherwise
 */
function lunivers_image_exists( $path ) {
	return file_exists( lunivers_get_image_path( $path ) );
}

/**
 * Output an image tag with lazy loading from theme assets
 *
 * @param string $path Relative path from assets/images/
 * @param string $alt Alt text for the image
 * @param string $class CSS classes
 * @param array  $attrs Additional attributes
 * @return void
 */
function lunivers_image( $path, $alt = '', $class = '', $attrs = [] ) {
	$url = lunivers_get_image_url( $path );
	
	if ( ! lunivers_image_exists( $path ) ) {
		return;
	}

	$attributes = [
		'src' => esc_url( $url ),
		'alt' => esc_attr( $alt ),
	];

	if ( ! empty( $class ) ) {
		$attributes['class'] = esc_attr( $class );
	}

	// Merge additional attributes
	$attributes = array_merge( $attributes, $attrs );

	$attr_string = '';
	foreach ( $attributes as $key => $value ) {
		$attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}

	echo '<img' . $attr_string . '>';
}

