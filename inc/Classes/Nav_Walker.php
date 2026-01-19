<?php
/**
 * Walker personnalisé pour la navigation WordPress avec support des sous-menus
 */
namespace LUNIVERS_THEME\Inc\Classes;

class Nav_Walker extends \Walker_Nav_Menu {

    /**
     * Début d'une liste de sous-menus
     *
     * @param string   $output Output string
     * @param int      $depth  Depth of menu item
     * @param stdClass $args   An object of wp_nav_menu() arguments
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    /**
     * Fin d'une liste de sous-menus
     *
     * @param string   $output Output string
     * @param int      $depth  Depth of menu item
     * @param stdClass $args   An object of wp_nav_menu() arguments
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }

    /**
     * Début d'un élément de menu
     *
     * @param string   $output Output string
     * @param \WP_Post $item   Menu item object
     * @param int      $depth  Depth of menu item
     * @param array    $args   An array of arguments
     * @param int      $id     Current item ID
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Ajouter la classe pour les items avec sous-menus
        if ( in_array( 'menu-item-has-children', $classes, true ) ) {
            $classes[] = 'has-submenu';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

        // Détecter si c'est le menu mobile
        $is_mobile = isset( $args->menu_id ) && 'mobile-menu' === $args->menu_id;

        // Classes pour le lien selon la profondeur et le contexte
        if ( $is_mobile ) {
            // Menu mobile : texte plus grand
            $link_classes = 'font-heading text-2xl tracking-wide text-cream-100 hover:text-primary-400 transition-colors';
            if ( $depth > 0 ) {
                $link_classes = 'block py-2 font-heading text-xl text-cream-200 hover:text-primary-400 transition-colors';
            }
        } else {
            // Menu desktop
            $link_classes = 'font-heading text-base tracking-wide text-cream-100 hover:text-primary-400 transition-colors';
            if ( $depth > 0 ) {
                $link_classes = 'block py-2 font-heading text-cream-200 hover:text-primary-400 transition-colors';
            }
        }

        $item_output  = isset( $args->before ) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="' . esc_attr( $link_classes ) . '">';
        $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
        $item_output .= '</a>';

        // Ajouter un bouton toggle pour les sous-menus (mobile)
        if ( in_array( 'menu-item-has-children', $classes, true ) ) {
            $item_output .= '<button class="submenu-toggle md:hidden ml-2 p-1 hover:text-primary-500 transition-colors" aria-expanded="false" aria-label="' . esc_attr__( 'Ouvrir le sous-menu', 'lunivers-theme' ) . '">';
            $item_output .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">';
            $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
            $item_output .= '</svg>';
            $item_output .= '</button>';
        }

        $item_output .= isset( $args->after ) ? $args->after : '';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Fin d'un élément de menu
     *
     * @param string   $output Output string
     * @param \WP_Post $item   Page data object
     * @param int      $depth  Depth of page
     * @param array    $args   An array of arguments
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}

