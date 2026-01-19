<?php
/**
 * The template for displaying search results pages
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="bg-cream-50 min-h-screen">
	<?php
	if ( have_posts() ) {
		?>
		<section class="search-hero py-20 md:py-32 bg-white border-b border-neutral-200">
			<div class="container mx-auto px-4">
				<div class="max-w-4xl mx-auto text-center">
					<h1 class="text-4xl md:text-5xl lg:text-6xl text-brown-950 mb-6 leading-tight">
						<?php
						printf(
							/* translators: %s: search query. */
							esc_html__( 'Résultats de recherche pour : %s', 'lunivers-theme' ),
							'<span class="text-primary-500">' . esc_html( get_search_query() ) . '</span>'
						);
						?>
					</h1>
				</div>
			</div>
		</section>

		<section class="search-content py-16 md:py-24 bg-cream-50">
			<div class="container mx-auto px-4">
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 lg:gap-12">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/content', 'search' );
					}
					?>
				</div>

				<?php
				the_posts_pagination( [
					'prev_text' => '<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> ' . esc_html__( 'Précédent', 'lunivers-theme' ) . '</span>',
					'next_text' => '<span class="flex items-center gap-2">' . esc_html__( 'Suivant', 'lunivers-theme' ) . ' <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></span>',
					'class'     => 'mt-12 flex justify-center',
				] );
				?>
			</div>
		</section>
		<?php
	} else {
		?>
		<section class="search-empty py-20 md:py-32">
			<div class="container mx-auto px-4">
				<div class="max-w-4xl mx-auto text-center">
					<h1 class="text-4xl md:text-5xl lg:text-6xl text-brown-950 mb-6 leading-tight">
						<?php
						printf(
							/* translators: %s: search query. */
							esc_html__( 'Aucun résultat pour : %s', 'lunivers-theme' ),
							'<span class="text-primary-500">' . esc_html( get_search_query() ) . '</span>'
						);
						?>
					</h1>
					<p class="text-lg md:text-xl text-brown-700 mb-8 max-w-2xl mx-auto">
						<?php esc_html_e( 'Désolé, mais rien ne correspond à vos critères de recherche. Veuillez réessayer avec d\'autres mots-clés.', 'lunivers-theme' ); ?>
					</p>
					<div class="max-w-md mx-auto">
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	?>
</div>

<?php
get_footer();

