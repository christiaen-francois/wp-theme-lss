<?php
/**
 * Theme bootstrap
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define theme constants
define( 'LUNIVERS_THEME_VERSION', '1.0.0' );
define( 'LUNIVERS_THEME_PATH', get_template_directory() );
define( 'LUNIVERS_THEME_URI', get_template_directory_uri() );
// define( 'LUNIVERS_THEME_ENV', "production" ); // 'local', 'development', 'production'...
define( 'LUNIVERS_THEME_ENV', wp_get_environment_type() ); // 'local', 'development', 'production'...


// Composer autoload
if ( file_exists( LUNIVERS_THEME_PATH . '/vendor/autoload.php' ) ) {
    require LUNIVERS_THEME_PATH . '/vendor/autoload.php';
}

// Include utility functions
require_once LUNIVERS_THEME_PATH . '/inc/Functions/wpml-helpers.php';
require_once LUNIVERS_THEME_PATH . '/inc/Functions/flexible-content.php';
require_once LUNIVERS_THEME_PATH . '/inc/Functions/image-helpers.php';
require_once LUNIVERS_THEME_PATH . '/inc/Functions/contact-helpers.php';

// Import script for itineraires (admin only)
if ( is_admin() ) {
    require_once LUNIVERS_THEME_PATH . '/import-itineraires.php';
}

// Include TGMPA
require_once LUNIVERS_THEME_PATH . '/inc/helpers/tgm-plugin-activation/class-tgm-plugin-activation.php';

// Autoload
use LUNIVERS_THEME\Inc\Classes\Theme;
use LUNIVERS_THEME\Inc\Classes\Assets;
use LUNIVERS_THEME\Inc\Classes\Acf;
use LUNIVERS_THEME\Inc\Classes\Menus;
use LUNIVERS_THEME\Inc\Classes\Init_Content;
use LUNIVERS_THEME\Inc\Classes\Init_Settings;
use LUNIVERS_THEME\Inc\Classes\Init_Plugins;
use LUNIVERS_THEME\Inc\Classes\Testimonials;
use LUNIVERS_THEME\Inc\Classes\FAQ;
use LUNIVERS_THEME\Inc\Classes\Contact;
use LUNIVERS_THEME\Inc\Classes\Newsletter;
use LUNIVERS_THEME\Inc\Classes\Team;
use LUNIVERS_THEME\Inc\Classes\Itineraires;

// Initialize core theme classes
Theme::get_instance();
Assets::get_instance();
Menus::get_instance();
Init_Content::get_instance();
Init_Settings::get_instance();
Init_Plugins::get_instance();
Testimonials::get_instance();
FAQ::get_instance();
Contact::get_instance();
Newsletter::get_instance();
Team::get_instance();
Itineraires::get_instance();

// Initialize ACF class (only if ACF is active)
if ( function_exists( 'acf' ) ) {
	Acf::get_instance();
}
