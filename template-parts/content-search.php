<?php
/**
 * Template part for displaying search results
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow' ); ?>>
	<?php
	if ( has_post_thumbnail() ) {
		?>
		<a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden">
			<?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-300' ] ); ?>
		</a>
		<?php
	}
	?>

	<div class="p-6">
		<header class="entry-header mb-4">
			<?php
			the_title( '<h2 class="entry-title text-2xl mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-500 transition-colors">', '</a></h2>' );

			if ( 'post' === get_post_type() ) {
				?>
				<div class="entry-meta text-sm text-neutral-600">
					<span class="posted-on">
						<?php echo esc_html( get_the_date() ); ?>
					</span>
					<?php
					if ( get_the_author() ) {
						?>
						<span class="byline ml-2">
							<?php esc_html_e( 'par', 'lunivers-theme' ); ?> 
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="hover:text-primary-500 transition-colors">
								<?php echo esc_html( get_the_author() ); ?>
							</a>
						</span>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</header>

		<div class="entry-summary text-neutral-700">
			<?php the_excerpt(); ?>
		</div>

		<footer class="entry-footer mt-4">
			<a href="<?php the_permalink(); ?>" class="inline-block mt-4 text-primary-500 hover:text-primary-600 font-semibold transition-colors">
				<?php esc_html_e( 'Lire la suite &rarr;', 'lunivers-theme' ); ?>
			</a>
		</footer>
	</div>
</article>

