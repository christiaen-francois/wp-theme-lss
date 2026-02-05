<?php
/**
 * Contact Form Handler
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Contact {
	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'wp_ajax_nl_contact_form', [ $this, 'handle_contact_form' ] );
		add_action( 'wp_ajax_nopriv_nl_contact_form', [ $this, 'handle_contact_form' ] );
	}

	/**
	 * Handle contact form submission via AJAX
	 *
	 * @return void
	 */
	public function handle_contact_form() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'nl_contact_form_nonce' ) ) {
			wp_send_json_error( [
				'message' => __( 'Erreur de sécurité. Veuillez rafraîchir la page et réessayer.', 'lunivers-theme' ),
			] );
		}

		// Sanitize and validate input
		$first_name   = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
		$last_name    = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
		$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$request_type = isset( $_POST['request_type'] ) ? sanitize_text_field( wp_unslash( $_POST['request_type'] ) ) : 'devis';
		$subject      = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
		$nights       = isset( $_POST['nights'] ) ? sanitize_text_field( wp_unslash( $_POST['nights'] ) ) : '';
		$adults       = isset( $_POST['adults'] ) ? sanitize_text_field( wp_unslash( $_POST['adults'] ) ) : '';
		$children     = isset( $_POST['children'] ) ? sanitize_text_field( wp_unslash( $_POST['children'] ) ) : '';
		$zanzibar     = isset( $_POST['zanzibar'] ) ? sanitize_text_field( wp_unslash( $_POST['zanzibar'] ) ) : '';
		$message          = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
		$rgpd             = isset( $_POST['rgpd'] ) && '1' === $_POST['rgpd'];
		$newsletter_optin = isset( $_POST['newsletter_optin'] ) && '1' === $_POST['newsletter_optin'];

		// Définir le sujet par défaut pour les demandes de devis
		if ( 'devis' === $request_type && empty( $subject ) ) {
			$subject = __( 'Demande de devis safari', 'lunivers-theme' );
		}

		// Validation
		$errors = [];

		if ( empty( $first_name ) ) {
			$errors['first_name'] = __( 'Le prénom est requis.', 'lunivers-theme' );
		}

		if ( empty( $last_name ) ) {
			$errors['last_name'] = __( 'Le nom est requis.', 'lunivers-theme' );
		}

		if ( empty( $email ) || ! is_email( $email ) ) {
			$errors['email'] = __( 'Une adresse email valide est requise.', 'lunivers-theme' );
		}

		if ( empty( $phone ) ) {
			$errors['phone'] = __( 'Le numéro de téléphone est requis.', 'lunivers-theme' );
		}

		// L'objet n'est obligatoire que pour les questions
		if ( 'question' === $request_type && empty( $subject ) ) {
			$errors['subject'] = __( 'L\'objet est requis.', 'lunivers-theme' );
		}

		if ( empty( $message ) ) {
			$errors['message'] = __( 'Le message est requis.', 'lunivers-theme' );
		}

		if ( ! $rgpd ) {
			$errors['rgpd'] = __( 'Vous devez accepter la politique de confidentialité.', 'lunivers-theme' );
		}

		// Return errors if any
		if ( ! empty( $errors ) ) {
			wp_send_json_error( [
				'message' => __( 'Veuillez corriger les erreurs dans le formulaire.', 'lunivers-theme' ),
				'errors'  => $errors,
			] );
		}

		// Honeypot check (basic spam protection)
		if ( isset( $_POST['website'] ) && ! empty( $_POST['website'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Erreur de validation.', 'lunivers-theme' ),
			] );
		}

		// Préparer les données du séjour pour l'email
		$trip_data = [
			'request_type' => $request_type,
			'nights'       => $nights,
			'adults'       => $adults,
			'children'     => $children,
			'zanzibar'     => $zanzibar,
		];

		// Send emails
		$email_sent = $this->send_notification_email( $first_name, $last_name, $email, $phone, $subject, $message, $trip_data );
		$thank_you_sent = $this->send_thank_you_email( $first_name, $last_name, $email, $request_type );

		if ( ! $email_sent || ! $thank_you_sent ) {
			wp_send_json_error( [
				'message' => __( 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer plus tard.', 'lunivers-theme' ),
			] );
		}

		// Add to Brevo newsletter list if opted in
		if ( $newsletter_optin ) {
			Newsletter::add_to_brevo( $email, [
				'FIRSTNAME' => $first_name,
				'LASTNAME'  => $last_name,
			] );
		}

		// Success response
		wp_send_json_success( [
			'message' => __( 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.', 'lunivers-theme' ),
		] );
	}

	/**
	 * Send notification email to admin
	 *
	 * @param string $first_name First name
	 * @param string $last_name  Last name
	 * @param string $email      Email address
	 * @param string $phone      Phone number
	 * @param string $subject    Subject
	 * @param string $message    Message
	 * @param array  $trip_data  Trip data (request_type, nights, adults, children, zanzibar)
	 * @return bool
	 */
	private function send_notification_email( string $first_name, string $last_name, string $email, string $phone, string $subject, string $message, array $trip_data = [] ): bool {
		$to      = 'info@lionselectsafaris.com';

		// Adapter le sujet de l'email selon le type de demande
		$request_type = $trip_data['request_type'] ?? 'devis';
		if ( 'devis' === $request_type ) {
			$subject_email = sprintf(
				/* translators: %s: Site name */
				__( '[%s] Nouvelle demande de devis', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
			$email_title = __( 'Nouvelle demande de devis', 'lunivers-theme' );
			$email_intro = sprintf(
				/* translators: %s: Site name */
				__( 'Vous avez reçu une nouvelle demande de devis depuis le site %s.', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
		} else {
			$subject_email = sprintf(
				/* translators: %s: Site name */
				__( '[%s] Nouvelle question', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
			$email_title = __( 'Nouvelle question', 'lunivers-theme' );
			$email_intro = sprintf(
				/* translators: %s: Site name */
				__( 'Vous avez reçu une nouvelle question depuis le site %s.', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
		}

		// Convertir les retours à la ligne en <br> pour le HTML
		$message_html = nl2br( esc_html( $message ) );

		// Construire les champs de l'email
		$fields = [
			[
				'label' => __( 'Type de demande', 'lunivers-theme' ),
				'value' => 'devis' === $request_type
					? '<strong style="color: #fcbb46;">' . esc_html__( 'Demande de devis', 'lunivers-theme' ) . '</strong>'
					: esc_html__( 'Question', 'lunivers-theme' ),
			],
			[
				'label' => __( 'Nom', 'lunivers-theme' ),
				'value' => esc_html( $first_name . ' ' . $last_name ),
			],
			[
				'label' => __( 'Email', 'lunivers-theme' ),
				'value' => sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) ),
			],
			[
				'label' => __( 'Téléphone', 'lunivers-theme' ),
				'value' => sprintf( '<a href="tel:%1$s">%2$s</a>', esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ), esc_html( $phone ) ),
			],
			[
				'label' => __( 'Objet', 'lunivers-theme' ),
				'value' => esc_html( $subject ),
			],
		];

		// Ajouter les champs spécifiques aux demandes de devis
		if ( 'devis' === $request_type ) {
			if ( ! empty( $trip_data['nights'] ) ) {
				$nights_labels = [
					'3-5'  => __( '3-5 nuits', 'lunivers-theme' ),
					'6-8'  => __( '6-8 nuits', 'lunivers-theme' ),
					'9-12' => __( '9-12 nuits', 'lunivers-theme' ),
					'12+'  => __( 'Plus de 12 nuits', 'lunivers-theme' ),
				];
				$fields[] = [
					'label' => __( 'Durée souhaitée', 'lunivers-theme' ),
					'value' => esc_html( $nights_labels[ $trip_data['nights'] ] ?? $trip_data['nights'] ),
				];
			}

			$travelers = [];
			if ( ! empty( $trip_data['adults'] ) ) {
				$travelers[] = sprintf(
					/* translators: %s: number of adults */
					_n( '%s adulte', '%s adultes', (int) $trip_data['adults'], 'lunivers-theme' ),
					$trip_data['adults']
				);
			}
			if ( ! empty( $trip_data['children'] ) && '0' !== $trip_data['children'] ) {
				$travelers[] = sprintf(
					/* translators: %s: number of children */
					_n( '%s enfant', '%s enfants', (int) $trip_data['children'], 'lunivers-theme' ),
					$trip_data['children']
				);
			}
			if ( ! empty( $travelers ) ) {
				$fields[] = [
					'label' => __( 'Voyageurs', 'lunivers-theme' ),
					'value' => esc_html( implode( ', ', $travelers ) ),
				];
			}

			if ( ! empty( $trip_data['zanzibar'] ) ) {
				$zanzibar_labels = [
					'yes'   => __( 'Oui', 'lunivers-theme' ),
					'no'    => __( 'Non', 'lunivers-theme' ),
					'maybe' => __( 'Indécis', 'lunivers-theme' ),
				];
				$fields[] = [
					'label' => __( 'Séjour Zanzibar', 'lunivers-theme' ),
					'value' => esc_html( $zanzibar_labels[ $trip_data['zanzibar'] ] ?? $trip_data['zanzibar'] ),
				];
			}
		}

		// Ajouter le message à la fin
		$fields[] = [
			'label' => __( 'Message', 'lunivers-theme' ),
			'value' => $message_html,
		];

		$body = $this->get_email_template(
			$email_title,
			$email_intro,
			$fields
		);

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_bloginfo( 'name' ) . ' <info@lionselectsafaris.com>',
			'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>',
		];

		return wp_mail( $to, $subject_email, $body, $headers );
	}

	/**
	 * Send thank you email to user
	 *
	 * @param string $first_name   First name
	 * @param string $last_name    Last name
	 * @param string $email        Email address
	 * @param string $request_type Request type (devis or question)
	 * @return bool
	 */
	private function send_thank_you_email( string $first_name, string $last_name, string $email, string $request_type = 'devis' ): bool {
		$to = $email;

		if ( 'devis' === $request_type ) {
			$subject_email = sprintf(
				/* translators: %s: Site name */
				__( 'Votre demande de devis - %s', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
			$email_title = __( 'Merci pour votre demande de devis', 'lunivers-theme' );
			$email_content = __(
				'Nous avons bien reçu votre demande de devis et vous remercions de votre intérêt pour nos safaris.<br><br>' .
				'Notre équipe va étudier votre projet avec attention et vous contactera très prochainement pour vous proposer un voyage sur mesure adapté à vos envies.',
				'lunivers-theme'
			);
		} else {
			$subject_email = sprintf(
				/* translators: %s: Site name */
				__( 'Merci pour votre message - %s', 'lunivers-theme' ),
				get_bloginfo( 'name' )
			);
			$email_title = __( 'Merci pour votre message', 'lunivers-theme' );
			$email_content = __(
				'Nous avons bien reçu votre message et vous remercions de nous avoir contactés.<br><br>' .
				'Notre équipe va examiner votre demande et vous répondra dans les plus brefs délais.',
				'lunivers-theme'
			);
		}

		$body = $this->get_email_template(
			$email_title,
			sprintf(
				/* translators: %1$s: First name, %2$s: Last name */
				__( 'Bonjour %1$s %2$s,', 'lunivers-theme' ),
				esc_html( $first_name ),
				esc_html( $last_name )
			),
			[],
			$email_content,
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

		return wp_mail( $to, $subject_email, $body, $headers );
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
		$site_name = get_bloginfo( 'name' );
		$site_url  = home_url( '/' );
		$primary_color = '#fcbb46'; // Primary color from theme
		$text_color = '#1a1a1a'; // Brown-950
		$bg_color = '#faf9f6'; // Cream-50
		$border_color = '#e5e5e5'; // Neutral-200

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

