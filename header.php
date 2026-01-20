<?php
/**
 * The header template
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LUNIVERS_THEME\Inc\Classes\Menus;
use LUNIVERS_THEME\Inc\Classes\Nav_Walker;

$menus_class = Menus::get_instance();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased' ); ?>>
<?php wp_body_open(); ?>

<!-- Preloader -->
<div id="preloader" class="fixed inset-0 z-[9999] bg-brown-950 flex flex-col items-center justify-center">
	<div class="preloader-logo mb-8 w-64 md:w-80 max-w-full">
		<?php
		$logo_path = 'logo-inverse.svg';
		if ( lunivers_image_exists( $logo_path ) ) {
			$logo_file_path = lunivers_get_image_path( $logo_path );
			$svg_content    = file_get_contents( $logo_file_path );
			
			if ( $svg_content ) {
				// Nettoyer le SVG pour enlever les attributs width/height et garder seulement viewBox
				$svg_content = preg_replace( '/<svg[^>]*>/', '<svg id="preloader-logo-svg" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 431.37 274.39" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">', $svg_content, 1 );
				
				// Autoriser tous les éléments SVG nécessaires pour l'animation
				$allowed_svg = [
					'svg'  => [
						'id'          => [],
						'class'       => [],
						'xmlns'       => [],
						'viewbox'     => [],
						'viewBox'     => [],
						'width'       => [],
						'height'      => [],
						'version'     => [],
						'aria-label'  => [],
						'data-name'   => [],
					],
					'defs' => [],
					'style' => [
						'type' => [],
					],
					'g'    => [
						'id'        => [],
						'data-name' => [],
					],
					'path' => [
						'd'     => [],
						'fill'  => [],
						'class' => [],
					],
					'rect' => [
						'x'      => [],
						'y'      => [],
						'width'  => [],
						'height' => [],
						'fill'   => [],
						'class'  => [],
					],
				];
				
				// Utiliser wp_kses avec les attributs autorisés
				echo wp_kses( $svg_content, $allowed_svg );
			}
		} else {
			?>
			<h1 class="text-3xl md:text-4xl text-cream-50">
				<?php bloginfo( 'name' ); ?>
			</h1>
			<?php
		}
		?>
	</div>
</div>

<div id="page" class="min-h-screen flex flex-col">
	<a class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-500 focus:text-white focus:rounded" href="#main-content">
		<?php esc_html_e( 'Aller au contenu', 'lunivers-theme' ); ?>
	</a>

	<header id="masthead" class="site-header fixed top-0 left-0 right-0 z-40 bg-brown-950 text-cream-100 transition-transform duration-300 ease-out" role="banner" data-smart-header>
		<div class="container mx-auto px-4">
			<nav class="flex items-center justify-between py-4 md:py-6 " aria-label="<?php esc_attr_e( 'Navigation principale', 'lunivers-theme' ); ?>">
				<div class="site-branding">
					<?php
					$logo_path = 'logo-h.svg';
					if ( lunivers_image_exists( $logo_path ) ) {
						$logo_file_path = lunivers_get_image_path( $logo_path );
						$svg_content    = file_get_contents( $logo_file_path );
						
						if ( $svg_content ) {
							// Nettoyer le SVG pour enlever les attributs width/height et garder seulement viewBox
							$svg_content = preg_replace( '/<svg[^>]*>/', '<svg class="h-8 md:h-10 w-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 548.37 105.56" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">', $svg_content, 1 );
							
							// Autoriser tous les éléments SVG nécessaires
							$allowed_svg = [
								'svg'  => [
									'id'          => [],
									'class'       => [],
									'xmlns'       => [],
									'viewbox'     => [],
									'viewBox'     => [],
									'width'       => [],
									'height'      => [],
									'version'     => [],
									'aria-label'  => [],
									'data-name'   => [],
								],
								'g'    => [
									'id'        => [],
									'data-name' => [],
								],
								'path' => [
									'd'     => [],
									'fill'  => [],
									'class' => [],
								],
								'rect' => [
									'x'      => [],
									'y'      => [],
									'width'  => [],
									'height' => [],
									'fill'   => [],
									'class'  => [],
								],
							];
							
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
								<?php echo wp_kses( $svg_content, $allowed_svg ); ?>
							</a>
							<?php
						}
					} elseif ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-bold text-cream-100 hover:text-primary-400 transition-colors" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
						<?php
					}
					?>
				</div>

				<?php
				$menu_id = $menus_class->get_menu_id( 'primary' );
				
				if ( $menu_id ) {
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'menu_class'      => 'hidden lg:flex items-center gap-6',
						'walker'          => new Nav_Walker(),
						'fallback_cb'     => false,
					] );
				}
				?>

				<button
					data-menu-toggle
					class="lg:hidden p-2 text-cream-100 hover:text-primary-400 transition-colors"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'lunivers-theme' ); ?>"
					aria-controls="primary-menu"
				>
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
					</svg>
				</button>
			</nav>

			</div>
	</header>

	<?php
	// Menu mobile (en dehors du header pour couvrir toute la hauteur)
	if ( $menu_id ) {
		?>
		<nav
			data-menu
			class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-brown-950 text-cream-100 shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden"
			role="navigation"
			aria-label="<?php esc_attr_e( 'Menu mobile', 'lunivers-theme' ); ?>"
		>
			<div class="flex items-center justify-between p-4 md:p-6 border-b border-brown-800">
				<?php
					$logo_path = 'logo-h.svg';
					if ( lunivers_image_exists( $logo_path ) ) {
						$logo_file_path = lunivers_get_image_path( $logo_path );
						$svg_content    = file_get_contents( $logo_file_path );
						
						if ( $svg_content ) {
							// Nettoyer le SVG pour enlever les attributs width/height et garder seulement viewBox
							$svg_content = preg_replace( '/<svg[^>]*>/', '<svg class="h-8 md:h-10 w-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 548.37 105.56" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">', $svg_content, 1 );
							
							// Autoriser tous les éléments SVG nécessaires
							$allowed_svg = [
								'svg'  => [
									'id'          => [],
									'class'       => [],
									'xmlns'       => [],
									'viewbox'     => [],
									'viewBox'     => [],
									'width'       => [],
									'height'      => [],
									'version'     => [],
									'aria-label'  => [],
									'data-name'   => [],
								],
								'g'    => [
									'id'        => [],
									'data-name' => [],
								],
								'path' => [
									'd'     => [],
									'fill'  => [],
									'class' => [],
								],
								'rect' => [
									'x'      => [],
									'y'      => [],
									'width'  => [],
									'height' => [],
									'fill'   => [],
									'class'  => [],
								],
							];
							
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
								<?php echo wp_kses( $svg_content, $allowed_svg ); ?>
							</a>
							<?php
						}
					} elseif ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-bold text-cream-100 hover:text-primary-400 transition-colors" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
						<?php
					}
					?>
				<button
					data-menu-close
					class="p-2 text-cream-100 hover:text-primary-400 transition-colors"
					aria-label="<?php esc_attr_e( 'Fermer le menu', 'lunivers-theme' ); ?>"
				>
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</button>
			</div>
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_id'        => 'mobile-menu',
				'container'      => false,
				'menu_class'      => 'flex flex-col gap-6 p-6',
				'walker'          => new Nav_Walker(),
				'fallback_cb'     => false,
			] );
			?>
		</nav>
		<?php
	}
	?>

	<!-- Spacer pour compenser le header fixed -->
	<div id="header-spacer" class="h-[72px] md:h-[88px]"></div>

	<main id="main-content" class="flex-grow" role="main">

