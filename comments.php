<?php
/**
 * The template for displaying comments
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return early if password protected and the password hasn't been entered yet.
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area max-w-4xl mx-auto mt-12">
	<?php
	if ( have_comments() ) {
		?>
		<h2 class="comments-title text-2xl mb-6">
			<?php
			$comment_count = get_comments_number();
			if ( '1' === $comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'Un commentaire sur &ldquo;%1$s&rdquo;', 'lunivers-theme' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s commentaire sur &ldquo;%2$s&rdquo;', '%1$s commentaires sur &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'lunivers-theme' ) ),
					number_format_i18n( $comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list list-none space-y-6">
			<?php
			wp_list_comments( [
				'style'      => 'ol',
				'short_ping' => true,
				'callback'   => function( $comment, $args, $depth ) {
					?>
					<li <?php comment_class( 'comment bg-neutral-50 p-6 rounded-lg' ); ?> id="comment-<?php comment_ID(); ?>">
						<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
							<footer class="comment-meta mb-4">
								<div class="comment-author vcard flex items-center gap-4">
									<?php
									if ( 0 != $args['avatar_size'] ) {
										echo get_avatar( $comment, $args['avatar_size'], '', '', [ 'class' => 'rounded-full' ] );
									}
									?>
									<div>
										<b class="fn"><?php comment_author_link(); ?></b>
										<time datetime="<?php comment_time( 'c' ); ?>" class="text-sm text-neutral-600 block">
											<?php
											printf(
												/* translators: 1: date, 2: time */
												esc_html__( '%1$s à %2$s', 'lunivers-theme' ),
												get_comment_date(),
												get_comment_time()
											);
											?>
										</time>
									</div>
								</div>
							</footer>

							<div class="comment-content prose max-w-none">
								<?php comment_text(); ?>
							</div>

							<div class="comment-reply mt-4">
								<?php
								comment_reply_link(
									array_merge(
										$args,
										[
											'depth'     => $depth,
											'max_depth' => $args['max_depth'],
											'class'     => 'text-primary-500 hover:text-primary-600 transition-colors',
										]
									)
								);
								?>
							</div>
						</article>
					</li>
					<?php
				},
			] );
			?>
		</ol>

		<?php
		the_comments_pagination( [
			'prev_text' => esc_html__( '&laquo; Précédent', 'lunivers-theme' ),
			'next_text' => esc_html__( 'Suivant &raquo;', 'lunivers-theme' ),
		] );
	}

	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>
		<p class="no-comments text-neutral-600"><?php esc_html_e( 'Les commentaires sont fermés.', 'lunivers-theme' ); ?></p>
		<?php
	}

	$commenter     = wp_get_current_commenter();
	$req           = get_option( 'require_name_email' );
	$aria_req      = ( $req ? " aria-required='true'" : '' );

	comment_form( [
		'title_reply'        => esc_html__( 'Laisser un commentaire', 'lunivers-theme' ),
		'title_reply_to'     => esc_html__( 'Laisser une réponse à %s', 'lunivers-theme' ),
		'cancel_reply_link'  => esc_html__( 'Annuler la réponse', 'lunivers-theme' ),
		'label_submit'       => esc_html__( 'Publier le commentaire', 'lunivers-theme' ),
		'class_submit'       => 'px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors',
		'comment_field'      => '<p class="comment-form-comment mb-4"><label for="comment" class="block mb-2 font-semibold">' . esc_html__( 'Commentaire', 'lunivers-theme' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea></p>',
		'fields'             => [
			'author' => '<p class="comment-form-author mb-4"><label for="author" class="block mb-2 font-semibold">' . esc_html__( 'Nom', 'lunivers-theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . ( $req ? ' required' : '' ) . ' class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" /></p>',
			'email'  => '<p class="comment-form-email mb-4"><label for="email" class="block mb-2 font-semibold">' . esc_html__( 'Email', 'lunivers-theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . ( $req ? ' required' : '' ) . ' class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" /></p>',
			'url'     => '<p class="comment-form-url mb-4"><label for="url" class="block mb-2 font-semibold">' . esc_html__( 'Site web', 'lunivers-theme' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" /></p>',
		],
	] );
	?>
</div>

