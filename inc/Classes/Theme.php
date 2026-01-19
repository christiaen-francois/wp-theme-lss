<?php
namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Theme {
    use Singleton;

    protected function __construct() {
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
        add_action( 'init', [ $this, 'create_auth_pages' ] );
        add_filter( 'theme_page_templates', [ $this, 'register_page_templates' ] );
        add_filter( 'page_template', [ $this, 'load_custom_page_template' ] );
        add_filter( 'use_block_editor_for_post_type', [ $this, 'disable_gutenberg_for_pages' ], 10, 2 );
    }

    public function setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'editor-styles' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );
    }

    /**
     * Disable Gutenberg editor for pages
     *
     * @param bool   $use_block_editor Whether to use the block editor.
     * @param string $post_type         The post type.
     * @return bool
     */
    public function disable_gutenberg_for_pages( $use_block_editor, $post_type ) {
        if ( 'page' === $post_type ) {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Create auth pages on theme activation
     * 
     * Note: This function can be extended to create custom auth pages.
     * Create a function in inc/Functions/ with namespace LUNIVERS_THEME\Inc\Functions
     * and uncomment the call below.
     */
    public function create_auth_pages(): void {
        // Only run once
        if ( get_option( 'lunivers_auth_pages_created' ) ) {
            return;
        }

        // Uncomment if you have a custom auth pages function
        // if ( function_exists( 'LUNIVERS_THEME\Inc\Functions\nl_create_auth_pages' ) ) {
        //     \LUNIVERS_THEME\Inc\Functions\nl_create_auth_pages();
        //     update_option( 'lunivers_auth_pages_created', true );
        // }
    }

    /**
     * Register custom page templates
     *
     * @param array $templates Existing templates
     * @return array
     */
    public function register_page_templates( array $templates ): array {
        $custom_templates = [
            'templates/dashboard/dashboard.php'        => __( 'Dashboard', 'lunivers-theme' ),
            'templates/dashboard/edit-profile.php'     => __( 'Edit Profile', 'lunivers-theme' ),
            'templates/dashboard/clubs.php'            => __( 'Clubs', 'lunivers-theme' ),
            'templates/dashboard/add-establishment.php' => __( 'Add Establishment', 'lunivers-theme' ),
            'templates/dashboard/events.php'           => __( 'Events', 'lunivers-theme' ),
            'templates/dashboard/add-event.php'        => __( 'Add Event', 'lunivers-theme' ),
            'templates/dashboard/comments.php'         => __( 'Comments', 'lunivers-theme' ),
        ];

        return array_merge( $templates, $custom_templates );
    }

    /**
     * Load custom page templates
     */
    public function load_custom_page_template( string $template ): string {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        $page_template = get_post_meta( $post->ID, '_wp_page_template', true );

        if ( ! $page_template ) {
            return $template;
        }

        // Vérifier si le template existe
        $template_path = get_template_directory() . '/' . $page_template;
        
        if ( file_exists( $template_path ) ) {
            return $template_path;
        }

        return $template;
    }
}