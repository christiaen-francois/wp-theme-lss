<?php
/**
 * Register Menus
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Menus {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'register_menus' ] );
		
		// Flush transient
		add_action( 'wp_update_nav_menu', [ $this, 'delete_transients_nav_menu' ] );
		add_action( 'wp_delete_nav_menu', [ $this, 'delete_transients_nav_menu' ] );

		/**
		 * Filters.
		 */
		// Mettre en cache le menu
		// https://wabeo.fr/hook-nav-menus/#mettre-en-cache-les-menus-grace-aux-transients
		
		// Set Menu transient
		add_filter( 'wp_nav_menu', [ $this, 'set_transient_nav_menu' ], 10, 2 );
		// Get Menu transient
		add_filter( 'pre_wp_nav_menu', [ $this, 'get_transient_nav_menu' ], 10, 2 );
	}

	/**
	 * Register navigation menus
	 */
	public function register_menus() {
		register_nav_menus( [
			'primary' => esc_html__( 'Principal', 'lunivers-theme' ),
			// 'primary_right' => esc_html__( 'Principal droite', 'lunivers-theme' ),
			// 'mobile' => esc_html__( 'Mobile', 'lunivers-theme' ),
			'footer' => esc_html__( 'Menu pied de page', 'lunivers-theme' ),
			// 'footer_more' => esc_html__( 'Menu pied de page plus', 'lunivers-theme' ),
			// 'copyright' => esc_html__( 'Copyright', 'lunivers-theme' ),
		] );
	}

	/**
	 * Get the menu id by menu location.
	 *
	 * @param string $location Menu location slug
	 * @return integer|string Menu ID or empty string
	 */
	public function get_menu_id( $location ) {
		// Get all locations
		$locations = get_nav_menu_locations();

		// Get object id by location.
		$menu_id = isset( $locations[ $location ] ) ? $locations[ $location ] : '';

		return ! empty( $menu_id ) ? $menu_id : '';
	}

	/**
	 * Get all child menus that has given parent menu id.
	 *
	 * @param array   $menu_array Menu array.
	 * @param integer $parent_id Parent menu id.
	 * @return array Child menu array.
	 */
	public function get_child_menu_items( $menu_array, $parent_id ) {
		$child_menus = [];

		if ( ! empty( $menu_array ) && is_array( $menu_array ) ) {
			foreach ( $menu_array as $menu ) {
				if ( intval( $menu->menu_item_parent ) === $parent_id ) {
					array_push( $child_menus, $menu );
				}
			}
		}

		return $child_menus;
	}

	/**
	 * Set Menu transient for caching
	 *
	 * @param string $nav_menu The HTML content for the navigation menu.
	 * @param object $args An object containing wp_nav_menu() arguments.
	 * @return string
	 */
	public function set_transient_nav_menu( $nav_menu, $args ) {
		// La taille d'un md5 est de 32 caractères
		// Celle du nom d'un transient ne peut dépasser 45
		if ( ! is_404() ) {
			$transient_name = 'nav_' . md5( serialize( $args ) . ( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) );
			set_transient( $transient_name, $nav_menu, HOUR_IN_SECONDS );
		}
		return $nav_menu;
	}
    
	/**
	 * Get Menu transient from cache
	 *
	 * @param string|null $pre Value to return instead. Default null to continue retrieving the menu.
	 * @param object      $args An object containing wp_nav_menu() arguments.
	 * @return string|null
	 */
	public function get_transient_nav_menu( $pre, $args ) {
		if ( ! is_404() ) {
			$transient_name = 'nav_' . md5( serialize( $args ) . ( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) );
			$cached_menu   = get_transient( $transient_name );
			
			if ( false !== $cached_menu ) {
				return $cached_menu;
			}
		}
		return $pre;
	}

	/**
	 * Delete all menu transients when menu is updated
	 */
	public function delete_transients_nav_menu() {
		// On supprime tous les transients dès qu'un menu est mis à jour
		// Pas moyen de faire dans la dentèle...
		// ... car difficilement possible de connaitre l'ID du menu au moment du hook wp_nav_menu
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_nav\_%' OR option_name LIKE '\_transient\_timeout\_nav\_%'" );
	}
	
	/**
	 * WPML :: Get language name by code
	 *
	 * @param string $code Language code
	 * @return string Language name
	 */
	public function get_language_name( $code = '' ) {
		if ( ! function_exists( 'icl_object_id' ) ) {
			return '';
		}

		global $sitepress;
		
		if ( ! isset( $sitepress ) ) {
			return '';
		}

		$details       = $sitepress->get_language_details( $code );
		$language_name = isset( $details['native_name'] ) ? $details['native_name'] : '';
		
		return $language_name;
	}

	/**
	 * WPML :: Get active language class
	 *
	 * @param string $code Language code
	 * @return string Class attribute or empty string
	 */
	public function get_active_language_class( $code = '' ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return '';
		}

		if ( ICL_LANGUAGE_CODE === $code ) {
			return "class='hidden'";
		}
		
		return '';
	}
}

