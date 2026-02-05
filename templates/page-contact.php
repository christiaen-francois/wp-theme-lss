<?php
/**
 * Template Name: Contact
 * The template for displaying the contact page
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

		// Champs ACF pour le hero
		$hero_image      = function_exists( 'get_field' ) && get_field( 'background_image' ) ? get_field( 'background_image' )['sizes']['large'] : '';
		$sur_titre       = function_exists( 'get_field' ) ? get_field( 'sur_titre' ) : '';
		$titre       	= function_exists( 'get_field' ) ? get_field( 'title' ) : get_the_title();
		$extrait         = function_exists( 'get_field' ) ? get_field( 'extrait' ) : '';
		$extrait_display = ! empty( $extrait ) ? $extrait : ( has_excerpt() ? get_the_excerpt() : '' );

		// Fallback sur l'image à la une si pas d'image ACF
		if ( empty( $hero_image ) && has_post_thumbnail() ) {
			$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		}
		?>

		<!-- Hero Section -->
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
						<?php echo $titre; ?>
					</h1>

					<?php if ( $extrait_display ) : ?>
						<div class="text-lg md:text-xl mb-4 <?php echo $hero_image ? 'text-cream-200' : 'text-brown-700'; ?>">
							<?php echo wp_kses_post( $extrait_display ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
			// Section Informations de contact
			if ( function_exists( 'get_field' ) ) {
				$contact_site_1 = get_field( 'contact_site_1' );
				$contact_site_2 = get_field( 'contact_site_2' );
				
				if ( $contact_site_1 || $contact_site_2 ) {
					?>
					<section class="contact-info py-16 md:py-24 xl:py-32 bg-white">
						<div class="container mx-auto px-4">
							<div class="max-w-6xl mx-auto">
								<h2 class="text-3xl md:text-4xl text-brown-950 mb-8 md:mb-12 text-center">
									<?php esc_html_e( 'Nos coordonnées', 'lunivers-theme' ); ?>
								</h2>
								
								<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
									<?php if ( $contact_site_1 ) : ?>
										<?php
										$name_1   = $contact_site_1['name'] ?? '';
										$street_1 = $contact_site_1['street'] ?? '';
										$city_1   = $contact_site_1['city'] ?? '';
										$zip_1    = $contact_site_1['zip'] ?? '';
										$phone_1  = $contact_site_1['phone'] ?? '';
										$mail_1   = $contact_site_1['mail'] ?? '';
										?>
										<div class="bg-cream-50 rounded-lg p-6 md:p-8">
											<?php if ( $name_1 ) : ?>
												<h3 class="text-xl md:text-2xl text-brown-950 mb-4">
													<?php echo esc_html( $name_1 ); ?>
												</h3>
											<?php endif; ?>
											
											<div class="space-y-3 text-brown-700">
												<?php if ( $street_1 || $city_1 || $zip_1 ) : ?>
													<div class="flex items-start gap-3">
														<svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
														</svg>
														<div>
															<?php if ( $street_1 ) : ?>
																<div><?php echo esc_html( $street_1 ); ?></div>
															<?php endif; ?>
															<?php if ( $zip_1 || $city_1 ) : ?>
																<div>
																	<?php if ( $zip_1 ) : ?>
																		<?php echo esc_html( $zip_1 ); ?>
																	<?php endif; ?>
																	<?php if ( $zip_1 && $city_1 ) : ?>
																		&nbsp;
																	<?php endif; ?>
																	<?php if ( $city_1 ) : ?>
																		<?php echo esc_html( $city_1 ); ?>
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														</div>
													</div>
												<?php endif; ?>
												
												<?php if ( $phone_1 ) : ?>
													<div class="flex items-center gap-3">
														<svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
														</svg>
														<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_1 ) ); ?>" class="hover:text-primary-500 transition-colors">
															<?php echo esc_html( $phone_1 ); ?>
														</a>
													</div>
												<?php endif; ?>
												
												<?php if ( $mail_1 ) : ?>
													<div class="flex items-center gap-3">
														<svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
														</svg>
														<a href="mailto:<?php echo esc_attr( $mail_1 ); ?>" class="hover:text-primary-500 transition-colors">
															<?php echo esc_html( $mail_1 ); ?>
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
									
									<?php if ( $contact_site_2 ) : ?>
										<?php
										$name_2   = $contact_site_2['name'] ?? '';
										$street_2 = $contact_site_2['street'] ?? '';
										$city_2   = $contact_site_2['city'] ?? '';
										$zip_2    = $contact_site_2['zip'] ?? '';
										$phone_2  = $contact_site_2['phone'] ?? '';
										$mail_2   = $contact_site_2['mail'] ?? '';
										?>
										<div class="bg-cream-50 rounded-lg p-6 md:p-8">
											<?php if ( $name_2 ) : ?>
												<h3 class="text-xl md:text-2xl text-brown-950 mb-4">
													<?php echo esc_html( $name_2 ); ?>
												</h3>
											<?php endif; ?>
											
											<div class="space-y-3 text-brown-700">
												<?php if ( $street_2 || $city_2 || $zip_2 ) : ?>
													<div class="flex items-start gap-3">
														<svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
														</svg>
														<div>
															<?php if ( $street_2 ) : ?>
																<div><?php echo esc_html( $street_2 ); ?></div>
															<?php endif; ?>
															<?php if ( $zip_2 || $city_2 ) : ?>
																<div>
																	<?php if ( $zip_2 ) : ?>
																		<?php echo esc_html( $zip_2 ); ?>
																	<?php endif; ?>
																	<?php if ( $zip_2 && $city_2 ) : ?>
																		&nbsp;
																	<?php endif; ?>
																	<?php if ( $city_2 ) : ?>
																		<?php echo esc_html( $city_2 ); ?>
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														</div>
													</div>
												<?php endif; ?>
												
												<?php if ( $phone_2 ) : ?>
													<div class="flex items-center gap-3">
														<svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
														</svg>
														<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_2 ) ); ?>" class="hover:text-primary-500 transition-colors">
															<?php echo esc_html( $phone_2 ); ?>
														</a>
													</div>
												<?php endif; ?>
												
												<?php if ( $mail_2 ) : ?>
													<div class="flex items-center gap-3">
														<svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
														</svg>
														<a href="mailto:<?php echo esc_attr( $mail_2 ); ?>" class="hover:text-primary-500 transition-colors">
															<?php echo esc_html( $mail_2 ); ?>
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</section>
					<?php
				}
			}
			?>

			<?php
			// Formulaire de contact avec informations à droite
			$contact_email = nl_get_contact_email();
			$contact_address = nl_get_contact_address();
			$contact_people = nl_get_contact_people();
			$social_networks = nl_get_social_networks();
			?>
			<section class="contact-form py-16 md:py-24 bg-white">
				<div class="container mx-auto px-4">
					<div class="">
						<!-- <h2 class="text-3xl md:text-4xl text-brown-950 mb-6 text-center">
							<?php esc_html_e( 'Envoyez-nous un message', 'lunivers-theme' ); ?>
						</h2>
						
						<p class="text-brown-700 mb-8 text-center">
							<?php esc_html_e( 'Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.', 'lunivers-theme' ); ?>
						</p> -->

						<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
							<!-- Formulaire (2 colonnes sur 3) -->
							<div class="lg:col-span-2">
								<!-- Messages de succès/erreur -->
								<div 
									data-success-message
									class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg"
									role="alert"
								></div>
								
								<div 
									data-error-message
									class="hidden mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"
									role="alert"
								></div>

								<form 
									data-contact-form
									method="post"
									class="space-y-6"
									novalidate
								>
									<?php wp_nonce_field( 'nl_contact_form_nonce', 'nonce' ); ?>
									
									<!-- Honeypot pour la protection anti-spam -->
									<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="sr-only" aria-hidden="true">

									<!-- Type de demande -->
									<div data-request-type-selector>
										<fieldset>
											<legend class="block text-sm font-semibold text-brown-950 mb-3">
												<?php esc_html_e( 'Type de demande', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</legend>
											<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
												<label class="relative flex items-center p-4 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-primary-300 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
													<input
														type="radio"
														name="request_type"
														value="devis"
														checked
														class="w-4 h-4 text-primary-500 border-neutral-300 focus:ring-primary-500"
														data-request-type
													>
													<span class="ml-3">
														<span class="block font-semibold text-brown-950"><?php esc_html_e( 'Demander un devis', 'lunivers-theme' ); ?></span>
														<span class="block text-sm text-brown-600"><?php esc_html_e( 'Safari sur mesure', 'lunivers-theme' ); ?></span>
													</span>
												</label>
												<label class="relative flex items-center p-4 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-primary-300 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
													<input
														type="radio"
														name="request_type"
														value="question"
														class="w-4 h-4 text-primary-500 border-neutral-300 focus:ring-primary-500"
														data-request-type
													>
													<span class="ml-3">
														<span class="block font-semibold text-brown-950"><?php esc_html_e( 'Poser une question', 'lunivers-theme' ); ?></span>
														<span class="block text-sm text-brown-600"><?php esc_html_e( 'Renseignements généraux', 'lunivers-theme' ); ?></span>
													</span>
												</label>
											</div>
										</fieldset>
									</div>

									<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
										<!-- Prénom -->
										<div>
											<label for="first_name" class="block text-sm font-semibold text-brown-950 mb-2">
												<?php esc_html_e( 'Prénom', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</label>
											<input
												type="text"
												id="first_name"
												name="first_name"
												required
												class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
												aria-required="true"
											>
										</div>

										<!-- Nom -->
										<div>
											<label for="last_name" class="block text-sm font-semibold text-brown-950 mb-2">
												<?php esc_html_e( 'Nom', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</label>
											<input
												type="text"
												id="last_name"
												name="last_name"
												required
												class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
												aria-required="true"
											>
										</div>
									</div>

									<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
										<!-- Email -->
										<div>
											<label for="email" class="block text-sm font-semibold text-brown-950 mb-2">
												<?php esc_html_e( 'Email', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</label>
											<input
												type="email"
												id="email"
												name="email"
												required
												class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
												aria-required="true"
											>
										</div>

										<!-- Téléphone -->
										<div>
											<label for="phone" class="block text-sm font-semibold text-brown-950 mb-2">
												<?php esc_html_e( 'Téléphone', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</label>
											<input
												type="tel"
												id="phone"
												name="phone"
												required
												class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
												aria-required="true"
											>
										</div>
									</div>

									<!-- Champs pour question (masqués par défaut) -->
									<div data-question-fields class="hidden space-y-6">
										<!-- Objet -->
										<div>
											<label for="subject" class="block text-sm font-semibold text-brown-950 mb-2">
												<?php esc_html_e( 'Objet de votre question', 'lunivers-theme' ); ?>
												<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
											</label>
											<input
												type="text"
												id="subject"
												name="subject"
												class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
											>
										</div>
									</div>

									<!-- Champs pour devis (visibles par défaut) -->
									<div data-devis-fields class="space-y-6">
										<!-- Détails du séjour -->
								<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
									<!-- Nombre de nuits -->
									<div>
										<label for="nights" class="block text-sm font-semibold text-brown-950 mb-2">
											<?php esc_html_e( 'Nombre de nuits', 'lunivers-theme' ); ?>
										</label>
										<select
											id="nights"
											name="nights"
											class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white"
										>
											<option value=""><?php esc_html_e( 'Sélectionner', 'lunivers-theme' ); ?></option>
											<option value="3-5"><?php esc_html_e( '3-5 nuits', 'lunivers-theme' ); ?></option>
											<option value="6-8"><?php esc_html_e( '6-8 nuits', 'lunivers-theme' ); ?></option>
											<option value="9-12"><?php esc_html_e( '9-12 nuits', 'lunivers-theme' ); ?></option>
											<option value="12+"><?php esc_html_e( 'Plus de 12 nuits', 'lunivers-theme' ); ?></option>
										</select>
									</div>

									<!-- Nombre d'adultes -->
									<div>
										<label for="adults" class="block text-sm font-semibold text-brown-950 mb-2">
											<?php esc_html_e( 'Adultes', 'lunivers-theme' ); ?>
										</label>
										<select
											id="adults"
											name="adults"
											class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white"
										>
											<option value=""><?php esc_html_e( 'Sélectionner', 'lunivers-theme' ); ?></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6+">6+</option>
										</select>
									</div>

									<!-- Nombre d'enfants -->
									<div>
										<label for="children" class="block text-sm font-semibold text-brown-950 mb-2">
											<?php esc_html_e( 'Enfants (0-12 ans)', 'lunivers-theme' ); ?>
										</label>
										<select
											id="children"
											name="children"
											class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white"
										>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4+">4+</option>
										</select>
									</div>
								</div>

								<!-- Zanzibar -->
								<div>
									<fieldset>
										<legend class="block text-sm font-semibold text-brown-950 mb-3">
											<?php esc_html_e( 'Souhaitez-vous un séjour balnéaire à Zanzibar ?', 'lunivers-theme' ); ?>
										</legend>
										<div class="flex flex-wrap gap-4">
											<label class="flex items-center gap-2 cursor-pointer">
												<input
													type="radio"
													name="zanzibar"
													value="yes"
													class="w-4 h-4 text-primary-500 border-neutral-300 focus:ring-primary-500"
												>
												<span class="text-brown-700"><?php esc_html_e( 'Oui', 'lunivers-theme' ); ?></span>
											</label>
											<label class="flex items-center gap-2 cursor-pointer">
												<input
													type="radio"
													name="zanzibar"
													value="no"
													class="w-4 h-4 text-primary-500 border-neutral-300 focus:ring-primary-500"
												>
												<span class="text-brown-700"><?php esc_html_e( 'Non', 'lunivers-theme' ); ?></span>
											</label>
											<label class="flex items-center gap-2 cursor-pointer">
												<input
													type="radio"
													name="zanzibar"
													value="maybe"
													checked
													class="w-4 h-4 text-primary-500 border-neutral-300 focus:ring-primary-500"
												>
												<span class="text-brown-700"><?php esc_html_e( 'Je ne sais pas encore', 'lunivers-theme' ); ?></span>
											</label>
										</div>
									</fieldset>
								</div>
									</div>
									<!-- Fin des champs devis -->

									<!-- Message -->
									<div>
										<label for="message" class="block text-sm font-semibold text-brown-950 mb-2">
											<?php esc_html_e( 'Message', 'lunivers-theme' ); ?>
											<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
										</label>
										<textarea
											id="message"
											name="message"
											rows="6"
											required
											class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-y"
											aria-required="true"
										></textarea>
									</div>

									<!-- RGPD -->
									<div class="flex items-start gap-3">
										<input
											type="checkbox"
											id="rgpd"
											name="rgpd"
											value="1"
											required
											class="mt-1 w-4 h-4 text-primary-500 border-neutral-300 rounded focus:ring-primary-500"
											aria-required="true"
										>
										<label for="rgpd" class="text-sm text-brown-700">
											<?php
											printf(
												/* translators: %s: Privacy policy link */
												esc_html__( 'J\'accepte que mes données soient utilisées pour me contacter. %s', 'lunivers-theme' ),
												'<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank" class="text-primary-500 hover:text-primary-600 underline">' . esc_html__( 'Politique de confidentialité', 'lunivers-theme' ) . '</a>'
											);
											?>
											<span class="text-red-500" aria-label="<?php esc_attr_e( 'requis', 'lunivers-theme' ); ?>">*</span>
										</label>
									</div>

									<!-- Newsletter opt-in -->
									<div class="flex items-start gap-3">
										<input
											type="checkbox"
											id="newsletter_optin"
											name="newsletter_optin"
											value="1"
											class="mt-1 w-4 h-4 text-primary-500 border-neutral-300 rounded focus:ring-primary-500"
										>
										<label for="newsletter_optin" class="text-sm text-brown-700">
											<?php esc_html_e( 'Je souhaite recevoir les inspirations safari, conseils de voyage et nouveautés de Lion Select Safaris.', 'lunivers-theme' ); ?>
										</label>
									</div>

									<!-- Bouton d'envoi -->
									<div class="text-center">
										<button
											type="submit"
											data-submit-button
											class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white font-heading font-semibold tracking-wide rounded-lg hover:bg-primary-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 group"
										>
											<?php esc_html_e( 'Envoyer', 'lunivers-theme' ); ?>
											<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
											</svg>
										</button>
									</div>
								</form>
							</div>

							<!-- Informations de contact (1 colonne sur 3) -->
							<div class="lg:col-span-1">
								<div class="bg-white rounded-lg shadow-md border border-neutral-200 p-6 md:p-8 sticky top-8">
									<h3 class="text-xl md:text-2xl text-brown-950 mb-6">
										<?php esc_html_e( 'Nos coordonnées', 'lunivers-theme' ); ?>
									</h3>

									<div class="space-y-6">
										<?php if ( $contact_email ) : ?>
											<div>
												<h4 class="text-sm text-brown-700 mb-2 uppercase tracking-wide">
													<?php esc_html_e( 'Email', 'lunivers-theme' ); ?>
												</h4>
												<div class="flex items-start gap-3">
													<svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
													</svg>
													<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="text-brown-700 hover:text-primary-500 transition-colors break-all">
														<?php echo esc_html( $contact_email ); ?>
													</a>
												</div>
											</div>
										<?php endif; ?>

										<?php if ( ! empty( $contact_address ) && ( $contact_address['street'] || $contact_address['city'] || $contact_address['zip'] ) ) : ?>
											<div>
												<h4 class="text-sm text-brown-700 mb-2 uppercase tracking-wide">
													<?php esc_html_e( 'Adresse', 'lunivers-theme' ); ?>
												</h4>
												<div class="flex items-start gap-3">
													<svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
													</svg>
													<address class="text-brown-700 not-italic">
														<?php if ( $contact_address['street'] ) : ?>
															<div><?php echo esc_html( $contact_address['street'] ); ?></div>
														<?php endif; ?>
														<?php if ( $contact_address['zip'] || $contact_address['city'] ) : ?>
															<div>
																<?php if ( $contact_address['zip'] ) : ?>
																	<?php echo esc_html( $contact_address['zip'] ); ?>
																<?php endif; ?>
																<?php if ( $contact_address['zip'] && $contact_address['city'] ) : ?>
																	&nbsp;
																<?php endif; ?>
																<?php if ( $contact_address['city'] ) : ?>
																	<?php echo esc_html( $contact_address['city'] ); ?>
																<?php endif; ?>
															</div>
														<?php endif; ?>
														<?php if ( $contact_address['country'] ) : ?>
															<div><?php echo esc_html( $contact_address['country'] ); ?></div>
														<?php endif; ?>
													</address>
												</div>
											</div>
										<?php endif; ?>

										<?php if ( ! empty( $contact_people ) ) : ?>
											<div>
												<h4 class="text-sm text-brown-700 mb-3 uppercase tracking-wide">
													<?php esc_html_e( 'Équipe', 'lunivers-theme' ); ?>
												</h4>
												<div class="space-y-4">
													<?php foreach ( $contact_people as $person ) : ?>
														<?php
														$name = $person['name'] ?? '';
														$phone = $person['phone'] ?? '';
														?>
														<?php if ( $name || $phone ) : ?>
															<div>
																<?php if ( $name ) : ?>
																	<div class="font-semibold text-brown-950 mb-1">
																		<?php echo esc_html( $name ); ?>
																	</div>
																<?php endif; ?>
																<?php if ( $phone ) : ?>
																	<div class="flex items-center gap-2">
																		<svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
																			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
																		</svg>
																		<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="text-brown-700 hover:text-primary-500 transition-colors">
																			<?php echo esc_html( $phone ); ?>
																		</a>
																	</div>
																<?php endif; ?>
															</div>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>

										<?php if ( ! empty( $social_networks ) ) : ?>
											<div>
												<h4 class="text-sm text-brown-700 mb-3 uppercase tracking-wide">
													<?php esc_html_e( 'Suivez-nous', 'lunivers-theme' ); ?>
												</h4>
												<div class="flex flex-wrap gap-3">
													<?php foreach ( $social_networks as $network ) : ?>
														<?php
														$type = $network['type'] ?? '';
														$url = $network['url'] ?? '';
														?>
														<?php if ( $type && $url ) : ?>
															<a
																href="<?php echo esc_url( $url ); ?>"
																target="_blank"
																rel="noopener noreferrer"
																class="w-10 h-10 flex items-center justify-center bg-cream-50 rounded-lg hover:bg-primary-500 hover:text-white transition-colors text-brown-700"
																aria-label="<?php echo esc_attr( nl_get_social_network_name( $type ) ); ?>"
															>
																<?php
																// Icônes SVG pour les réseaux sociaux
																$icon_path = '';
																switch ( $type ) {
																	case 'facebook':
																		$icon_path = 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z';
																		break;
																	case 'twitter':
																		$icon_path = 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z';
																		break;
																	case 'instagram':
																		$icon_path = 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z';
																		break;
																	case 'linkedin':
																		$icon_path = 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z';
																		break;
																	case 'youtube':
																		$icon_path = 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z';
																		break;
																	case 'whatsapp':
																		$icon_path = 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.149.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z';
																		break;
																	default:
																		$icon_path = 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z';
																}
																?>
																<?php if ( $icon_path ) : ?>
																	<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
																		<path d="<?php echo esc_attr( $icon_path ); ?>"></path>
																	</svg>
																<?php else : ?>
																	<span class="text-xs font-semibold"><?php echo esc_html( substr( nl_get_social_network_name( $type ), 0, 1 ) ); ?></span>
																<?php endif; ?>
															</a>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<?php
			// Flexible Content Section (après le formulaire)
			if ( function_exists( 'get_field' ) ) {
				$flexible_content = get_field( 'flexible_content' );
				
				if ( $flexible_content ) {
					lunivers_render_flexible_content( 'flexible_content' );
				} elseif ( get_the_content() ) {
					?>
					<section class="contact-content py-16 md:py-24 bg-white border-t border-neutral-200">
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
				?>
				<section class="contact-content py-16 md:py-24 bg-white border-t border-neutral-200">
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
		</article>
		<?php
	}
	?>
</div>

<?php
get_footer();

