<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="bg-cream-50 min-h-screen">
	<section class="error-404 py-20 md:py-32">
		<div class="container mx-auto px-4">
			<div class="max-w-4xl mx-auto text-center">
				<header class="page-header mb-12">
					<h1 class="text-8xl md:text-9xl text-primary-500 mb-6 leading-none">404</h1>
					<p class="text-2xl md:text-3xl text-brown-950 font-semibold mb-8">
						<?php esc_html_e( 'Oops! Cette page est introuvable.', 'lunivers-theme' ); ?>
					</p>
				</header>

				<div class="page-content">
					<p class="text-lg md:text-xl text-brown-700 mb-12 max-w-2xl mx-auto">
						<?php esc_html_e( 'Il semble que rien n\'ait été trouvé à cet emplacement. Essayez peut-être une recherche ?', 'lunivers-theme' ); ?>
					</p>

					<div class="max-w-md mx-auto mb-12">
						<?php get_search_form(); ?>
					</div>

					<div>
						<a
							href="<?php echo esc_url( home_url( '/' ) ); ?>"
							class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white font-heading font-semibold tracking-wide rounded-lg shadow-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 group"
						>
							<?php esc_html_e( 'Retour à l\'accueil', 'lunivers-theme' ); ?>
							<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
							</svg>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();

