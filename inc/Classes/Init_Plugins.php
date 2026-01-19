<?php
/**
 * Init Plugins
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Init_Plugins {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
		}
	}
    
	/**
	 * Register required plugins via TGMPA
	 */
	public function register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = [
			// This is an example of how to include a plugin bundled with a theme.
			[
				'name'               => 'Advanced Custom Fields Pro', // The plugin name.
				'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
				'source'             => 'https://updates.lunivers.lu/wpup/?action=download&slug=advanced-custom-fields-pro', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => 'https://wordpress.org/plugins/advanced-custom-fields/', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			],

			// This is an example of how to include a plugin bundled with a theme.
			[
				'name'               => 'WPML Multilingual CMS', // The plugin name.
				'slug'               => 'sitepress-multilingual-cms', // The plugin slug (typically the folder name).
				'source'             => 'https://updates.lunivers.lu/wpup/?action=download&slug=sitepress-multilingual-cms', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => 'https://wpml.org/', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			],
			
			[
				'name'               => 'Lunivers - Admin', // The plugin name.
				'slug'               => 'lunivers-admin', // The plugin slug (typically the folder name).
				'source'             => 'https://updates.lunivers.lu/wpup/?action=download&slug=lunivers-admin', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.0.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => 'https://www.lunivers.lu', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			],
			
			[
				'name'               => 'Lunivers - Législation sur les cookies & RGPD - tarteaucitron.js', // The plugin name.
				'slug'               => 'lunivers-tarteaucitronjs', // The plugin slug (typically the folder name).
				'source'             => 'https://updates.lunivers.lu/wpup/?action=download&slug=lunivers-tarteaucitronjs', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => 'https://www.lunivers.lu', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			],

			// This is an example of the use of 'is_callable' functionality. A user could - for instance -
			// have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
			// 'wordpress-seo-premium'.
			// By setting 'is_callable' to either a function from that plugin or a class method
			// `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
			// recognize the plugin as being installed.
			[
				'name'            => 'WordPress SEO by Yoast',
				'slug'            => 'wordpress-seo',
				'required'        => true,
				'force_activation' => false,
				'is_callable'     => 'wpseo_init',
			],
			[
				'name'            => 'Enable Media Replace',
				'slug'            => 'enable-media-replace',
				'required'        => false,
				'force_activation' => false,
				'is_callable'     => '',
			],
			[
				'name'            => 'Mail logging',
				'slug'            => 'wp-mail-logging',
				'required'        => false,
				'force_activation' => false,
				'is_callable'     => '',
			],
		];
    
		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = [
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => LUNIVERS_THEME_PATH . '/inc/helpers/tgm-plugin-activation/plugins/',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

			'strings'      => [
				'page_title'                      => __( 'Installez les plug-ins requis', 'lunivers-theme' ),
				'menu_title'                      => __( 'Installer les plugins', 'lunivers-theme' ),
				/* translators: %s: plugin name. */
				'installing'                      => __( 'Installation du plugin: %s', 'lunivers-theme' ),
				/* translators: %s: plugin name. */
				'updating'                        => __( 'Mise à jour du Plugin: %s', 'lunivers-theme' ),
				'oops'                            => __( 'Une erreur s\'est produite avec l\'API du plugin.', 'lunivers-theme' ),
				'notice_can_install_required'     => _n_noop(
					/* translators: 1: plugin name(s). */
					'Ce thème nécessite le plugin suivant: %1$s.',
					'Ce thème nécessite les plugins suivants: %1$s.',
					'lunivers-theme'
				),
				'notice_can_install_recommended'  => _n_noop(
					/* translators: 1: plugin name(s). */
					'Ce thème recommande le plugin suivant: %1$s.',
					'Ce thème recommande les plugins suivants: %1$s.',
					'lunivers-theme'
				),
				'notice_ask_to_update'            => _n_noop(
					/* translators: 1: plugin name(s). */
					'Le plugin suivant doit être mis à jour à sa dernière version pour assurer une compatibilité maximale avec ce thème.: %1$s.',
					'Les plugins suivants doivent être mis à jour vers leur dernière version pour assurer une compatibilité maximale avec ce thème.: %1$s.',
					'lunivers-theme'
				),
				'notice_ask_to_update_maybe'      => _n_noop(
					/* translators: 1: plugin name(s). */
					'Une mise à jour est disponible pour: %1$s.',
					'Des mises à jour sont disponibles pour les plugins suivants: %1$s.',
					'lunivers-theme'
				),
				'notice_can_activate_required'    => _n_noop(
					/* translators: 1: plugin name(s). */
					'Le plugin requis suivant est actuellement inactif: %1$s.',
					'Les plugins requis suivants sont actuellement inactifs: %1$s.',
					'lunivers-theme'
				),
				'notice_can_activate_recommended' => _n_noop(
					/* translators: 1: plugin name(s). */
					'Le plugin recommandé suivant est actuellement inactif: %1$s.',
					'Les plugins recommandés suivants sont actuellement inactifs: %1$s.',
					'lunivers-theme'
				),
				'install_link'                    => _n_noop(
					'Commencer l\'installation du plugin',
					'Commencer à installer des plugins',
					'lunivers-theme'
				),
				'update_link' 					  => _n_noop(
					'Commencer la mise à jour du plugin',
					'Commencer à mettre à jour les plugins',
					'lunivers-theme'
				),
				'activate_link'                   => _n_noop(
					'Commencez à activer le plugin',
					'Commencez à activer les plugins',
					'lunivers-theme'
				),
				'return'                          => __( 'Retourner à l\'installateur de plugins requis', 'lunivers-theme' ),
				'plugin_activated'                => __( 'Plugin activé avec succès.', 'lunivers-theme' ),
				'activated_successfully'          => __( 'Le plugin suivant a été activé avec succès:', 'lunivers-theme' ),
				/* translators: 1: plugin name. */
				'plugin_already_active'           => __( 'Pas d\'action prise. Le plugin %1$ s était déjà actif.', 'lunivers-theme' ),
				/* translators: 1: plugin name. */
				'plugin_needs_higher_version'     => __( 'Plugin non activé. Une version supérieure de %s est nécessaire pour ce thème. Veuillez mettre à jour le plugin.', 'lunivers-theme' ),
				/* translators: 1: dashboard link. */
				'complete'                        => __( 'Tous les plugins installés et activés avec succès. %1$s', 'lunivers-theme' ),
				'dismiss'                         => __( 'Ne tenez pas compte de cet avertissement', 'lunivers-theme' ),
				'notice_cannot_install_activate'  => __( 'Il existe un ou plusieurs plug-ins obligatoires ou recommandés à installer, mettre à jour ou activer.', 'lunivers-theme' ),
				'contact_admin'                   => __( 'Veuillez contacter l\'administrateur de ce site pour obtenir de l\'aide.', 'lunivers-theme' ),

				'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
			],
		];
        
		tgmpa( $plugins, $config );
	}
}

