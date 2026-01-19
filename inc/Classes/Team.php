<?php
/**
 * Team Custom Post Type
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Team {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'wp_head', [ $this, 'add_noindex_meta' ], 1 );
	}

	/**
	 * Register Team Custom Post Type
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'Équipe', 'Post type general name', 'lunivers-theme' ),
			'singular_name'         => _x( 'Membre', 'Post type singular name', 'lunivers-theme' ),
			'menu_name'             => _x( 'Équipe', 'Admin Menu text', 'lunivers-theme' ),
			'name_admin_bar'        => _x( 'Membre', 'Add New on Toolbar', 'lunivers-theme' ),
			'add_new'               => __( 'Ajouter', 'lunivers-theme' ),
			'add_new_item'          => __( 'Ajouter un membre', 'lunivers-theme' ),
			'new_item'              => __( 'Nouveau membre', 'lunivers-theme' ),
			'edit_item'             => __( 'Modifier le membre', 'lunivers-theme' ),
			'view_item'             => __( 'Voir le membre', 'lunivers-theme' ),
			'all_items'             => __( 'Tous les membres', 'lunivers-theme' ),
			'search_items'          => __( 'Rechercher des membres', 'lunivers-theme' ),
			'parent_item_colon'      => __( 'Membres parents:', 'lunivers-theme' ),
			'not_found'              => __( 'Aucun membre trouvé.', 'lunivers-theme' ),
			'not_found_in_trash'     => __( 'Aucun membre trouvé dans la corbeille.', 'lunivers-theme' ),
			'archives'              => _x( 'Archives des membres', 'The post type archive label used in nav menus', 'lunivers-theme' ),
			'insert_into_item'      => _x( 'Insérer dans le membre', 'Overrides the "Insert into post"/"Insert into page" phrase', 'lunivers-theme' ),
			'uploaded_to_this_item' => _x( 'Téléversé sur ce membre', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'lunivers-theme' ),
			'filter_items_list'     => _x( 'Filtrer la liste des membres', 'Screen reader text for the filter links heading', 'lunivers-theme' ),
			'items_list_navigation'  => _x( 'Navigation de la liste des membres', 'Screen reader text for the pagination heading', 'lunivers-theme' ),
			'items_list'            => _x( 'Liste des membres', 'Screen reader text for the items list heading', 'lunivers-theme' ),
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
			'has_archive'        => false, // Pas d'archive
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => [ 'title' ], // Uniquement le titre
			'show_in_rest'       => false,
		];

		register_post_type( 'team', $args );
	}

	/**
	 * Add noindex meta tag for team post type
	 */
	public function add_noindex_meta() {
		if ( is_singular( 'team' ) || is_post_type_archive( 'team' ) ) {
			echo '<meta name="robots" content="noindex, nofollow">' . "\n";
		}
	}
}
