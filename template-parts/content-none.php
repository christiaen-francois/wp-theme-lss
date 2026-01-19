<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="no-results not-found text-center max-w-2xl mx-auto py-12">
	<header class="page-header mb-8">
		<h1 class="text-4xl md:text-5xl text-brown-950 mb-4"><?php esc_html_e( 'Rien trouvé', 'lunivers-theme' ); ?></h1>
	</header>

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) {
			printf(
				'<p class="text-lg text-brown-700 mb-8">' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Prêt à publier votre premier article ? <a href="%1$s" class="text-primary-500 hover:text-primary-600 font-semibold underline">Commencez ici</a>.', 'lunivers-theme' ),
					[
						'a' => [
							'href' => [],
							'class' => [],
						],
					]
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);
		} elseif ( is_search() ) {
			?>
			<p class="text-lg text-brown-700 mb-8"><?php esc_html_e( 'Désolé, mais rien ne correspond à vos critères de recherche. Veuillez réessayer avec d\'autres mots-clés.', 'lunivers-theme' ); ?></p>
			<div class="max-w-md mx-auto">
				<?php get_search_form(); ?>
			</div>
			<?php
		} else {
			?>
			<p class="text-lg text-brown-700 mb-8"><?php esc_html_e( 'Il semble que nous ne puissions pas trouver ce que vous recherchez. Peut-être qu\'une recherche pourrait aider ?', 'lunivers-theme' ); ?></p>
			<div class="max-w-md mx-auto">
				<?php get_search_form(); ?>
			</div>
			<?php
		}
		?>
	</div>
</section>

