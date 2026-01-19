<?php
/**
 * Itineraires Custom Post Type
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Itineraires {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Register Itineraires Custom Post Type
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'Itinéraires', 'Post type general name', 'lunivers-theme' ),
			'singular_name'         => _x( 'Itinéraire', 'Post type singular name', 'lunivers-theme' ),
			'menu_name'             => _x( 'Itinéraires', 'Admin Menu text', 'lunivers-theme' ),
			'name_admin_bar'        => _x( 'Itinéraire', 'Add New on Toolbar', 'lunivers-theme' ),
			'add_new'               => __( 'Ajouter', 'lunivers-theme' ),
			'add_new_item'          => __( 'Ajouter un itinéraire', 'lunivers-theme' ),
			'new_item'              => __( 'Nouvel itinéraire', 'lunivers-theme' ),
			'edit_item'             => __( 'Modifier l\'itinéraire', 'lunivers-theme' ),
			'view_item'             => __( 'Voir l\'itinéraire', 'lunivers-theme' ),
			'all_items'             => __( 'Tous les itinéraires', 'lunivers-theme' ),
			'search_items'          => __( 'Rechercher des itinéraires', 'lunivers-theme' ),
			'parent_item_colon'     => __( 'Itinéraires parents:', 'lunivers-theme' ),
			'not_found'             => __( 'Aucun itinéraire trouvé.', 'lunivers-theme' ),
			'not_found_in_trash'    => __( 'Aucun itinéraire trouvé dans la corbeille.', 'lunivers-theme' ),
			'featured_image'        => _x( 'Image de l\'itinéraire', 'Overrides the "Featured Image" phrase', 'lunivers-theme' ),
			'set_featured_image'    => _x( 'Définir l\'image', 'Overrides the "Set featured image" phrase', 'lunivers-theme' ),
			'remove_featured_image' => _x( 'Retirer l\'image', 'Overrides the "Remove featured image" phrase', 'lunivers-theme' ),
			'use_featured_image'    => _x( 'Utiliser comme image', 'Overrides the "Use as featured image" phrase', 'lunivers-theme' ),
			'archives'              => _x( 'Archives des itinéraires', 'The post type archive label used in nav menus', 'lunivers-theme' ),
			'insert_into_item'      => _x( 'Insérer dans l\'itinéraire', 'Overrides the "Insert into post"/"Insert into page" phrase', 'lunivers-theme' ),
			'uploaded_to_this_item' => _x( 'Téléversé sur cet itinéraire', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'lunivers-theme' ),
			'filter_items_list'     => _x( 'Filtrer la liste des itinéraires', 'Screen reader text for the filter links heading', 'lunivers-theme' ),
			'items_list_navigation' => _x( 'Navigation de la liste des itinéraires', 'Screen reader text for the pagination heading', 'lunivers-theme' ),
			'items_list'            => _x( 'Liste des itinéraires', 'Screen reader text for the items list heading', 'lunivers-theme' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-location-alt',
			'supports'           => [ 'title', 'page-attributes' ],
			'show_in_rest'       => false,
		];

		register_post_type( 'itineraire', $args );
	}
}
