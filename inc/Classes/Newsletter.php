<?php
/**
 * Newsletter Form Handler
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Newsletter {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'wp_ajax_nl_newsletter_form', [ $this, 'handle_newsletter_form' ] );
		add_action( 'wp_ajax_nopriv_nl_newsletter_form', [ $this, 'handle_newsletter_form' ] );
	}

	/**
	 * Handle newsletter form submission via AJAX
	 *
	 * @return void
	 */
	public function handle_newsletter_form() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nl_newsletter_form_nonce' ) ) {
			wp_send_json_error( [
				'message' => __( 'Erreur de sécurité. Veuillez rafraîchir la page et réessayer.', 'lunivers-theme' ),
			] );
		}

		// Sanitize and validate input
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		// Validation
		$errors = [];

		if ( empty( $email ) || ! is_email( $email ) ) {
			$errors['email'] = __( 'Une adresse email valide est requise.', 'lunivers-theme' );
		}

		// Return errors if any
		if ( ! empty( $errors ) ) {
			wp_send_json_error( [
				'message' => __( 'Veuillez entrer une adresse email valide.', 'lunivers-theme' ),
				'errors'  => $errors,
			] );
		}

		// Honeypot check (basic spam protection)
		if ( isset( $_POST['website'] ) && ! empty( $_POST['website'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Erreur de validation.', 'lunivers-theme' ),
			] );
		}

		// Send emails
		$notification_sent = $this->send_notification_email( $email );
		$thank_you_sent    = $this->send_thank_you_email( $email );

		if ( ! $notification_sent || ! $thank_you_sent ) {
			wp_send_json_error( [
				'message' => __( 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer plus tard.', 'lunivers-theme' ),
			] );
		}

		// Success response
		wp_send_json_success( [
			'message' => __( 'Merci pour votre inscription ! Vous recevrez bientôt nos actualités.', 'lunivers-theme' ),
		] );
	}

	/**
	 * Send notification email to admin
	 *
	 * @param string $email Email address
	 * @return bool
	 */
	private function send_notification_email( string $email ): bool {
		$to      = 'info@lionselectsafaris.com';
		$subject = sprintf(
			/* translators: %s: Site name */
			__( '[%s] Nouvelle inscription à la newsletter', 'lunivers-theme' ),
			get_bloginfo( 'name' )
		);

		$body = $this->get_email_template(
			__( 'Nouvelle inscription à la newsletter', 'lunivers-theme' ),
			sprintf(
				/* translators: %s: Site name */
				__( 'Une nouvelle personne s\'est inscrite à la newsletter de %s.', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			),
			[
				[
					'label' => __( 'Email', 'lunivers-theme' ),
					'value' => sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) ),
				],
				[
					'label' => __( 'Date', 'lunivers-theme' ),
					'value' => wp_date( 'd/m/Y à H:i' ),
				],
			]
		);

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
			'Reply-To: ' . $email,
		];

		return wp_mail( $to, $subject, $body, $headers );
	}

	/**
	 * Send thank you email to subscriber
	 *
	 * @param string $email Email address
	 * @return bool
	 */
	private function send_thank_you_email( string $email ): bool {
		$to      = $email;
		$subject = sprintf(
			/* translators: %s: Site name */
			__( 'Bienvenue dans la newsletter %s', 'lunivers-theme' ),
			get_bloginfo( 'name' )
		);

		$body = $this->get_email_template(
			__( 'Bienvenue !', 'lunivers-theme' ),
			__( 'Bonjour,', 'lunivers-theme' ),
			[],
			sprintf(
				/* translators: %s: Site name */
				__(
					'Merci de vous être inscrit(e) à notre newsletter !<br><br>' .
					'Vous recevrez désormais nos dernières actualités, nos idées de safaris et nos conseils pour préparer votre voyage en Tanzanie.<br><br>' .
					'À très bientôt pour de nouvelles aventures !',
					'lunivers-theme'
				)
			),
			sprintf(
				/* translators: %s: Site name */
				__( "Cordialement,<br>L'équipe de %s", 'lunivers-theme' ),
				get_bloginfo( 'name' )
			)
		);

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
		];

		return wp_mail( $to, $subject, $body, $headers );
	}

	/**
	 * Get HTML email template
	 *
	 * @param string $title      Email title
	 * @param string $intro      Introduction text
	 * @param array  $fields     Array of fields to display (label => value)
	 * @param string $content    Main content (optional)
	 * @param string $signature  Signature (optional)
	 * @return string
	 */
	private function get_email_template( string $title, string $intro, array $fields = [], string $content = '', string $signature = '' ): string {
		$site_name     = get_bloginfo( 'name' );
		$site_url      = home_url( '/' );
		$primary_color = '#fcbb46'; // Primary color from theme
		$text_color    = '#1a1a1a'; // Brown-950
		$bg_color      = '#faf9f6'; // Cream-50
		$border_color  = '#e5e5e5'; // Neutral-200

		ob_start();
		?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $title ); ?></title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: <?php echo esc_attr( $bg_color ); ?>; line-height: 1.6;">
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: <?php echo esc_attr( $bg_color ); ?>;">
		<tr>
			<td align="center" style="padding: 40px 20px;">
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
					<!-- Header -->
					<tr>
						<td style="background-color: <?php echo esc_attr( $primary_color ); ?>; padding: 30px 40px; text-align: center;">
							<h1 style="margin: 0; color: #1a1a1a; font-size: 24px; font-weight: bold;">
								<?php echo esc_html( $site_name ); ?>
							</h1>
						</td>
					</tr>

					<!-- Content -->
					<tr>
						<td style="padding: 40px;">
							<h2 style="margin: 0 0 20px 0; color: <?php echo esc_attr( $text_color ); ?>; font-size: 22px; font-weight: bold;">
								<?php echo esc_html( $title ); ?>
							</h2>

							<p style="margin: 0 0 30px 0; color: #4a4a4a; font-size: 16px;">
								<?php echo wp_kses_post( $intro ); ?>
							</p>

							<?php if ( ! empty( $fields ) ) : ?>
								<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 30px;">
									<?php foreach ( $fields as $field ) : ?>
										<tr>
											<td style="padding: 12px 0; border-bottom: 1px solid <?php echo esc_attr( $border_color ); ?>;">
												<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
													<tr>
														<td style="width: 140px; padding-right: 20px; vertical-align: top;">
															<strong style="color: <?php echo esc_attr( $text_color ); ?>; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
																<?php echo esc_html( $field['label'] ); ?>
															</strong>
														</td>
														<td style="vertical-align: top;">
															<div style="color: #4a4a4a; font-size: 15px;">
																<?php echo wp_kses_post( $field['value'] ); ?>
															</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									<?php endforeach; ?>
								</table>
							<?php endif; ?>

							<?php if ( ! empty( $content ) ) : ?>
								<div style="margin-bottom: 30px; color: #4a4a4a; font-size: 15px;">
									<?php echo wp_kses_post( $content ); ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $signature ) ) : ?>
								<div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid <?php echo esc_attr( $border_color ); ?>; color: #4a4a4a; font-size: 15px;">
									<?php echo wp_kses_post( $signature ); ?>
								</div>
							<?php endif; ?>
						</td>
					</tr>

					<!-- Footer -->
					<tr>
						<td style="background-color: #f5f5f5; padding: 20px 40px; text-align: center; border-top: 1px solid <?php echo esc_attr( $border_color ); ?>;">
							<p style="margin: 0; color: #666666; font-size: 12px;">
								<?php
								printf(
									/* translators: %1$s: Site name, %2$s: Site URL */
									esc_html__( 'Cet email a été envoyé depuis %1$s (%2$s)', 'lunivers-theme' ),
									esc_html( $site_name ),
									esc_url( $site_url )
								);
								?>
							</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
		<?php
		return ob_get_clean();
	}
}
