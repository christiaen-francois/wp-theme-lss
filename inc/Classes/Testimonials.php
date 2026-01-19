<?php
/**
 * Testimonials Custom Post Type
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Testimonials {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Register Testimonials Custom Post Type
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'Témoignages', 'Post type general name', 'lunivers-theme' ),
			'singular_name'         => _x( 'Témoignage', 'Post type singular name', 'lunivers-theme' ),
			'menu_name'             => _x( 'Témoignages', 'Admin Menu text', 'lunivers-theme' ),
			'name_admin_bar'        => _x( 'Témoignage', 'Add New on Toolbar', 'lunivers-theme' ),
			'add_new'               => __( 'Ajouter', 'lunivers-theme' ),
			'add_new_item'          => __( 'Ajouter un témoignage', 'lunivers-theme' ),
			'new_item'              => __( 'Nouveau témoignage', 'lunivers-theme' ),
			'edit_item'             => __( 'Modifier le témoignage', 'lunivers-theme' ),
			'view_item'             => __( 'Voir le témoignage', 'lunivers-theme' ),
			'all_items'             => __( 'Tous les témoignages', 'lunivers-theme' ),
			'search_items'          => __( 'Rechercher des témoignages', 'lunivers-theme' ),
			'parent_item_colon'      => __( 'Témoignages parents:', 'lunivers-theme' ),
			'not_found'              => __( 'Aucun témoignage trouvé.', 'lunivers-theme' ),
			'not_found_in_trash'     => __( 'Aucun témoignage trouvé dans la corbeille.', 'lunivers-theme' ),
			'featured_image'        => _x( 'Photo du témoignage', 'Overrides the "Featured Image" phrase', 'lunivers-theme' ),
			'set_featured_image'    => _x( 'Définir la photo', 'Overrides the "Set featured image" phrase', 'lunivers-theme' ),
			'remove_featured_image' => _x( 'Retirer la photo', 'Overrides the "Remove featured image" phrase', 'lunivers-theme' ),
			'use_featured_image'    => _x( 'Utiliser comme photo', 'Overrides the "Use as featured image" phrase', 'lunivers-theme' ),
			'archives'              => _x( 'Archives des témoignages', 'The post type archive label used in nav menus', 'lunivers-theme' ),
			'insert_into_item'      => _x( 'Insérer dans le témoignage', 'Overrides the "Insert into post"/"Insert into page" phrase', 'lunivers-theme' ),
			'uploaded_to_this_item' => _x( 'Téléversé sur ce témoignage', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'lunivers-theme' ),
			'filter_items_list'     => _x( 'Filtrer la liste des témoignages', 'Screen reader text for the filter links heading', 'lunivers-theme' ),
			'items_list_navigation'  => _x( 'Navigation de la liste des témoignages', 'Screen reader text for the pagination heading', 'lunivers-theme' ),
			'items_list'            => _x( 'Liste des témoignages', 'Screen reader text for the items list heading', 'lunivers-theme' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false, // Non public
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-quote',
			'supports'           => [ 'title' ], // Uniquement le titre
			'show_in_rest'       => false,
		];

		register_post_type( 'testimonial', $args );
	}
}

