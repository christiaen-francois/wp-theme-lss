<?php
/**
 * WPML Helper Functions
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get translated page ID (WPML compatible)
 *
 * @param int $page_id The page ID to translate
 * @return int The translated page ID or original ID if WPML is not active
 */
if ( ! function_exists( 'nl_get_translated_page_id' ) ) {
	function nl_get_translated_page_id( $page_id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $page_id, 'page', true );
		}
		return $page_id;
	}
}

/**
 * Get ID Products page (WPML compatible)
 * Example function as mentioned in user requirements
 *
 * @return int The page ID
 */
if ( ! function_exists( 'get_ID_Products' ) ) {
	function get_ID_Products() {
		$page_ID = 80;

		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $page_ID, 'page', true );
		}

		return $page_ID;
	}
}

