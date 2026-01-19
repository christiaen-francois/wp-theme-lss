<?php
/**
 * Init Pages
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Init_Content {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		if ( isset( $_GET['activated'] ) && is_admin() ) {
			add_action( 'init', [ $this, 'create_initial_pages' ] );
		}
	}
    
	/**
	 * Create initial pages on theme activation
	 * Supports WPML for multilingual sites
	 */
	public function create_initial_pages() {
		$pages = [
			'Accueil' => [
				'post_type'    => 'page',
				'post_content' => '',
				'post_author'  => 1,
				'menu_order'   => 1,
			],
			'Contact' => [
				'post_type'     => 'page',
				'post_content'  => '',
				'post_author'   => 1,
				'page_template' => 'templates/page-contact.php',
				'menu_order'    => 6,
			],
		];

		foreach ( $pages as $page_url_title => $page_meta ) {
			$id = get_page_by_title( $page_url_title );
			
			$page = [
				'post_type'    => $page_meta['post_type'],
				'post_title'   => $page_url_title,
				'post_name'    => sanitize_title( $page_url_title ),
				'post_status'  => 'publish',
				'post_content' => $page_meta['post_content'],
				'post_author'  => $page_meta['post_author'],
				'post_parent'  => 0,
				'menu_order'   => $page_meta['menu_order'],
			];

			if ( ! isset( $id->ID ) ) {
				$new_page_id = wp_insert_post( $page );
				
				if ( ! empty( $page_meta['page_template'] ) ) {
					update_post_meta( $new_page_id, '_wp_page_template', $page_meta['page_template'] );
				}

				// WPML: Register page for translation
				if ( function_exists( 'icl_object_id' ) && function_exists( 'do_action' ) ) {
					do_action( 'wpml_set_element_language_details', [
						'element_id'   => $new_page_id,
						'element_type' => 'post_page',
						'trid'         => false,
						'language_code' => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : 'fr',
					] );
				}
			}
		}
    
		// Set homepage and posts page
		$homepage = get_page_by_title( 'Accueil' );
		$newspage = get_page_by_title( 'Journal' );
		
		if ( $homepage || $newspage ) {
			update_option( 'show_on_front', 'page' );
			
			if ( $homepage ) {
				// WPML: Get translated page ID if WPML is active
				$homepage_id = $homepage->ID;
				if ( function_exists( 'icl_object_id' ) ) {
					$homepage_id = icl_object_id( $homepage_id, 'page', true );
				}
				update_option( 'page_on_front', $homepage_id );
			}
			
			if ( $newspage ) {
				// WPML: Get translated page ID if WPML is active
				$newspage_id = $newspage->ID;
				if ( function_exists( 'icl_object_id' ) ) {
					$newspage_id = icl_object_id( $newspage_id, 'page', true );
				}
				update_option( 'page_for_posts', $newspage_id );
			}
		}
	}
}

