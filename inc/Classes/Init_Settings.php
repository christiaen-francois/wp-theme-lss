<?php
/**
 * Init Settings
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Init_Settings {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/**
		 * Actions.
		 */
		add_action( 'after_setup_theme', [ $this, 'clean_default' ], 10 );
	}

	/**
	 * Display success notice on theme reactivation
	 */
	public function admin_notice__success() {
		$msg = 'Le thème ' . get_option( 'current_theme' ) . ' a été réactivé avec succès.';
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo esc_html( $msg ); ?></p>
		</div>
		<?php
	}

	/**
	 * Display error notice on first theme activation
	 */
	public function admin_notice__error() {
		$msg = 'Le thème ' . get_option( 'current_theme' ) . ' a changé des <a href="' . esc_url( admin_url( 'options-general.php' ) ) . '" title="See Settings">options</a> par défaut de Wordpress et effacé les posts & pages par défaut.';
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo wp_kses_post( $msg ); ?></p>
		</div>
		<?php
	}
    
	/**
	 * Check PHP version compatibility
	 */
	public function check_php_version() {
		$version = phpversion();
		$major   = explode( '.', $version );
		
		if ( $major[0] == 8 ) {
			wp_die( 'Ce thème n\'est pas compatible avec la version de PHP installée sur le cet hébergement. La version 8 de PHP n\'est pas encore compatible avec la plus part des plugins ni le coeur de Wordpress. <br/> Merci de revenir à une version 7.4 au antérieure.' );
		}
	}

	/**
	 * Clean default WordPress settings and content on first activation
	 */
	public function clean_default() {
		// First we check to see if our default theme settings have been applied.
		$the_theme_status = get_option( 'theme_setup_status' );
		
		// If the theme has not yet been used we want to run our default settings.
		if ( $the_theme_status !== '1' ) {
			// Setup Default WordPress settings
			$core_settings = [
				'avatar_default'                => 'mystery', // Comment Avatars should be using mystery by default
				'avatar_rating'                 => 'G', // Avatar rating
				'comment_max_links'             => 0, // Désactiver les liens dans les commentaires
				'comments_per_page'             => 20, // 20 commentaires par page
				'default_comment_status'        => 'closed', // Désactiver les commentaires
				'uploads_use_yearmonth_folders' => 0, // Désactiver "Organiser mes fichiers envoyés dans des dossiers mensuels et annuels"
				'theme_setup_status'            => '1', // Une fois fait, on enregistre les règlages pour ne pas les refaire à la prochaine réactivation.
				'WPLANG'                        => 'fr_FR', // Mettre le FR comme langue par défaut de WP
				'blogdescription'               => 'Une nouvelle planète de Lunivers',
				'timezone_string'               => 'Europe/Brussels',
			];
			
			foreach ( $core_settings as $k => $v ) {
				update_option( $k, $v );
			}
			
			// Supprimer les pages, postes et commentaires par défaut
			wp_delete_post( 1, true );
			wp_delete_post( 2, true );
			wp_delete_comment( 1, true );
			
			add_action( 'admin_notices', [ $this, 'admin_notice__error' ] );

			// Send activation email (optional - comment out if not needed)
			// $to      = 'go@lunivers.lu';
			// $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
			// $subject = '[podpeche] Activation notice';
			// $body    = '<p>Activation url: <a href="' . esc_url( get_site_url() ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></p>';
			// wp_mail( $to, $subject, $body, $headers );
		}
		// Else if we are re-activing the theme
		elseif ( $the_theme_status === '1' && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice__success' ] );

			// Send reactivation email (optional - comment out if not needed)
			// $to      = 'go@lunivers.lu';
			// $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
			// $subject = '[podpeche] Reactivation notice';
			// $body    = '<p>Reactivation url: <a href="' . esc_url( get_site_url() ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></p>';
			// wp_mail( $to, $subject, $body, $headers );
		}
	}
}

