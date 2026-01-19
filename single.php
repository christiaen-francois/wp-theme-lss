<?php
/**
 * The template for displaying single posts
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
		$hero_image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
		?>
		<section class="single-hero relative overflow-hidden <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : 'py-20 md:py-32'; ?>">
			<?php if ( $hero_image ) : ?>
				<div class="absolute inset-0 lazy bg-cover bg-center bg-no-repeat" data-bg="<?php echo esc_url( $hero_image ); ?>">
					<div class="absolute inset-0 bg-brown-950/40"></div>
				</div>
			<?php endif; ?>

			<div class="relative z-10 container mx-auto px-4 flex items-center <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : ''; ?>">
				<div class="max-w-6xl mx-auto text-center <?php echo $hero_image ? 'text-white' : 'text-brown-950'; ?>">
					<?php if ( 'post' === get_post_type() ) : ?>
						<div class="entry-meta text-sm mb-4 <?php echo $hero_image ? 'text-cream-100' : 'text-neutral-600'; ?>">
							<span class="posted-on">
								<?php
								printf(
									/* translators: %s: post date. */
									esc_html_x( 'Publié le %s', 'post date', 'lunivers-theme' ),
									'<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>'
								);
								?>
							</span>
							<?php
							if ( get_the_author() ) {
								?>
								<span class="byline ml-4">
									<?php
									printf(
										/* translators: %s: post author. */
										esc_html_x( 'par %s', 'post author', 'lunivers-theme' ),
										'<span class="author vcard"><a class="url fn n hover:underline" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
									);
									?>
								</span>
								<?php
							}
							?>
						</div>
					<?php endif; ?>

					<h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl mb-6 leading-tight <?php echo $hero_image ? 'drop-shadow-lg' : ''; ?>">
						<?php the_title(); ?>
					</h1>

					<?php if ( has_excerpt() ) : ?>
						<div class="text-lg md:text-xl <?php echo $hero_image ? 'text-cream-100' : 'text-brown-700'; ?> max-w-3xl mx-auto leading-relaxed">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<section class="single-content py-16 md:py-24 bg-white">
				<div class="container mx-auto px-4">
					<div class="max-w-4xl mx-auto">
						<div class="prose prose-lg md:prose-xl prose-brown max-w-none">
							<?php
							the_content(
								sprintf(
									wp_kses(
										/* translators: %s: Name of current post. Only visible to screen readers */
										__( 'Lire la suite<span class="screen-reader-text"> "%s"</span>', 'lunivers-theme' ),
										[
											'span' => [
												'class' => [],
											],
										]
									),
									wp_kses_post( get_the_title() )
								)
							);

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
			// Footer avec catégories et tags
			$categories_list = get_the_category_list( esc_html__( ', ', 'lunivers-theme' ) );
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'lunivers-theme' ) );
			
			if ( $categories_list || $tags_list ) :
				?>
				<footer class="entry-footer py-12 bg-cream-50 border-t border-neutral-200">
					<div class="container mx-auto px-4">
						<div class="max-w-4xl mx-auto">
							<div class="flex flex-wrap gap-4 text-sm text-neutral-600">
								<?php
								if ( $categories_list ) {
									printf(
										/* translators: 1: list of categories. */
										'<span class="cat-links"><span class="font-semibold text-brown-950">' . esc_html__( 'Catégories :', 'lunivers-theme' ) . '</span> %1$s</span>',
										$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									);
								}

								if ( $tags_list ) {
									printf(
										/* translators: 1: list of tags. */
										'<span class="tags-links"><span class="font-semibold text-brown-950">' . esc_html__( 'Tags :', 'lunivers-theme' ) . '</span> %1$s</span>',
										$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									);
								}
								?>
							</div>
						</div>
					</div>
				</footer>
				<?php
			endif;
			?>
		</article>

		<?php
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

