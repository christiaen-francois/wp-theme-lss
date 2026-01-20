<?php
/**
 * The template for displaying all pages
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
	while ( have_posts() ) {
		the_post();
		?>

		<?php
		// Hero Section avec image de fond optionnelle
		$hero_image = get_field("background_image") ? get_field("background_image")["sizes"]["large"] : '';
		$has_flexible = function_exists( 'get_field' ) && get_field( 'flexible_content' );
		$sur_titre = get_field( 'sur_titre' ) ?? '';
		$extrait = get_field( 'extrait' ) ?? '';
		// Utiliser l'extrait ACF si disponible, sinon l'extrait WordPress
		$extrait_display = ! empty( $extrait ) ? $extrait : ( has_excerpt() ? get_the_excerpt() : '' );
		?>
		<section class="page-hero relative overflow-hidden <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : 'py-20 md:py-32'; ?>">
			<?php if ( $hero_image ) : ?>
				<div class="parallax-bg absolute inset-0 lazy bg-cover bg-center bg-no-repeat" data-bg="<?php echo esc_url( $hero_image ); ?>"></div>
				<div class="absolute inset-0 bg-brown-950/40 pointer-events-none"></div>
			<?php endif; ?>

			<div class="relative z-10 container mx-auto px-4 flex items-center <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : ''; ?>">
				<div class="max-w-6xl mx-auto text-center <?php echo $hero_image ? 'text-white' : 'text-brown-950'; ?> my-8">
					<?php if ( $sur_titre ) : ?>
						<p class="inline-block px-4 py-2 mb-4 text-xs tracking-wider text-white uppercase bg-white/15 backdrop-blur-sm rounded-full border border-white/30 drop-shadow-md">
							<?php echo esc_html( $sur_titre ); ?>
						</p>
					<?php endif; ?>

					<h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl mb-6 leading-tight <?php echo $hero_image ? 'drop-shadow-lg' : ''; ?>">
						<?php the_title(); ?>
					</h1>

					<?php if ( $extrait_display ) : ?>
						<div class="text-lg md:text-xl mb-4 <?php echo $hero_image ? 'text-cream-200' : 'text-brown-700'; ?>">
							<?php echo wp_kses_post( $extrait_display ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php
		// Flexible Content Section
		if ( function_exists( 'get_field' ) ) {
			$flexible_content = get_field( 'flexible_content' );
			
			if ( $flexible_content ) {
				lunivers_render_flexible_content( 'flexible_content' );
			} elseif ( get_the_content() ) {
				// Fallback to regular content if no flexible content
				?>
				<section class="page-content py-16 md:py-24 bg-white">
					<div class="container mx-auto px-4">
						<div class="max-w-4xl mx-auto">
							<div class="prose prose-lg md:prose-xl prose-brown max-w-none">
								<?php
								the_content();

								wp_link_pages( [
									'before' => '<div class="page-links text-sm text-neutral-600 mt-8 pt-8 border-t border-neutral-200">' . esc_html__( 'Pages:', 'lunivers-theme' ),
									'after'  => '</div>',
								] );
								?>
							</div>
						</div>
					</div>
				</section>
				<?php
			}
		} elseif ( get_the_content() ) {
			// Fallback if ACF is not active
			?>
			<section class="page-content py-16 md:py-24 bg-white">
				<div class="container mx-auto px-4">
					<div class="max-w-4xl mx-auto">
						<div class="prose prose-lg md:prose-xl prose-brown max-w-none">
							<?php
							the_content();

							wp_link_pages( [
								'before' => '<div class="page-links text-sm text-neutral-600 mt-8 pt-8 border-t border-neutral-200">' . esc_html__( 'Pages:', 'lunivers-theme' ),
								'after'  => '</div>',
							] );
							?>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
		?>

		<?php
		// Edit link (only for logged-in users)
		/*
		if ( get_edit_post_link() ) {
			?>
			<section class="py-8 bg-cream-50 border-t border-neutral-200">
				<div class="container mx-auto px-4">
					<div class="max-w-4xl mx-auto text-center">
						<?php
						edit_post_link(
							sprintf(
								wp_kses(
										__( 'Modifier <span class="screen-reader-text">%s</span>', 'lunivers-theme' ),
									[
										'span' => [
											'class' => [],
										],
									]
								),
								get_the_title()
							),
							'<span class="edit-link text-sm text-neutral-600 hover:text-primary-500 transition-colors">',
							'</span>'
						);
						?>
					</div>
				</div>
			</section>
			<?php
		}
		*/

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			?>
			<section class="py-16 md:py-24 bg-white border-t border-neutral-200">
				<div class="container mx-auto px-4">
					<div class="max-w-4xl mx-auto">
						<?php comments_template(); ?>
					</div>
				</div>
			</section>
			<?php
		}
	}
	?>
</div>

<?php
get_footer();

