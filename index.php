<?php
/**
 * The main template file
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="container mx-auto px-4 py-12">
	
	<?php
	if ( have_posts() ) {
		?>
		<header class="page-header mb-12">
			<?php
			if ( is_home() && ! is_front_page() ) {
				?>
				<h1 class="text-4xl mb-4"><?php single_post_title(); ?></h1>
				<?php
			} elseif ( is_search() ) {
				?>
				<h1 class="text-4xl mb-4">
					<?php
					printf(
						/* translators: %s: search query. */
						esc_html__( 'Résultats de recherche pour : %s', 'lunivers-theme' ),
						'<span>' . get_search_query() . '</span>'
					);
					?>
				</h1>
				<?php
			} elseif ( is_archive() ) {
				the_archive_title( '<h1 class="text-4xl mb-4">', '</h1>' );
				the_archive_description( '<div class="text-neutral-600 mt-4">', '</div>' );
			}
			?>
		</header>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			}
			?>
		</div>

		<?php
		the_posts_pagination( [
			'prev_text' => esc_html__( '&laquo; Précédent', 'lunivers-theme' ),
			'next_text' => esc_html__( 'Suivant &raquo;', 'lunivers-theme' ),
		] );
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}
	?>
</div>

<?php
get_footer();

