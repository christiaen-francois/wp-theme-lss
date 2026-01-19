<?php
/**
 * FAQ Custom Post Type
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class FAQ {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Register FAQ Custom Post Type
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'FAQ', 'Post type general name', 'lunivers-theme' ),
			'singular_name'         => _x( 'Question', 'Post type singular name', 'lunivers-theme' ),
			'menu_name'             => _x( 'FAQ', 'Admin Menu text', 'lunivers-theme' ),
			'name_admin_bar'        => _x( 'Question', 'Add New on Toolbar', 'lunivers-theme' ),
			'add_new'               => __( 'Ajouter', 'lunivers-theme' ),
			'add_new_item'          => __( 'Ajouter une question', 'lunivers-theme' ),
			'new_item'              => __( 'Nouvelle question', 'lunivers-theme' ),
			'edit_item'             => __( 'Modifier la question', 'lunivers-theme' ),
			'view_item'             => __( 'Voir la question', 'lunivers-theme' ),
			'all_items'             => __( 'Toutes les questions', 'lunivers-theme' ),
			'search_items'          => __( 'Rechercher des questions', 'lunivers-theme' ),
			'parent_item_colon'      => __( 'Questions parents:', 'lunivers-theme' ),
			'not_found'              => __( 'Aucune question trouvée.', 'lunivers-theme' ),
			'not_found_in_trash'     => __( 'Aucune question trouvée dans la corbeille.', 'lunivers-theme' ),
			'archives'              => _x( 'Archives des questions', 'The post type archive label used in nav menus', 'lunivers-theme' ),
			'insert_into_item'      => _x( 'Insérer dans la question', 'Overrides the "Insert into post"/"Insert into page" phrase', 'lunivers-theme' ),
			'uploaded_to_this_item' => _x( 'Téléversé sur cette question', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'lunivers-theme' ),
			'filter_items_list'     => _x( 'Filtrer la liste des questions', 'Screen reader text for the filter links heading', 'lunivers-theme' ),
			'items_list_navigation'  => _x( 'Navigation de la liste des questions', 'Screen reader text for the pagination heading', 'lunivers-theme' ),
			'items_list'            => _x( 'Liste des questions', 'Screen reader text for the items list heading', 'lunivers-theme' ),
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
			'menu_icon'          => 'dashicons-editor-help',
			'supports'           => [ 'title' ], // Uniquement le titre
			'show_in_rest'       => false,
		];

		register_post_type( 'faq', $args );
	}
}

