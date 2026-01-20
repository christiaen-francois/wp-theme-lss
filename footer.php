<?php
/**
 * The footer template
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Récupération des données
$contact_email   = nl_get_contact_email();
$contact_address = nl_get_contact_address();
$contact_people  = nl_get_contact_people();
$social_networks = nl_get_social_networks();
$footer_cta      = nl_get_footer_cta();
?>

	</main><!-- #main-content -->

	<footer id="colophon" class="site-footer mt-auto bg-brown-950 text-cream-100" role="contentinfo">
		<div class="container mx-auto px-4 py-12 md:py-16 lg:py-20">


			<div class="mb-8 text-center">
				
			</div>

			<!-- Structure principale en 2 colonnes -->
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

				<!-- COLONNE 1 : Infos de contact en grille 2x2 -->
				<div class="order-2 lg:order-1 grid grid-cols-1 sm:grid-cols-2 gap-8">

					<!-- Logo et slogan -->
					<div class="sm:col-span-2">

						<img src="<?php echo LUNIVERS_THEME_URI; ?>/assets/images/logo-inverse.svg" class="w-[12rem] md:w-[20rem] h-auto mb-5" width="320" height="80" alt="<?php bloginfo( 'name' ); ?>">
						
						<p class="text-sm text-cream-200 mt-3">
							<?php esc_html_e( 'Safaris d\'exception en Afrique', 'lunivers-theme' ); ?>
						</p>
					</div>

					<!-- Contact -->
					<div>
						<h3 class="text-lg text-cream-100 mb-4">
							<?php esc_html_e( 'Contact', 'lunivers-theme' ); ?>
						</h3>
						<div class="space-y-3 text-sm text-cream-200">
							<?php if ( $contact_email ) : ?>
								<div class="flex items-start gap-3">
									<svg class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
									</svg>
									<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="hover:text-primary-400 transition-colors">
										<?php echo esc_html( $contact_email ); ?>
									</a>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $contact_address ) && ( $contact_address['street'] || $contact_address['city'] || $contact_address['zip'] ) ) : ?>
								<div class="flex items-start gap-3">
									<svg class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
									</svg>
									<address class="not-italic">
										<?php if ( $contact_address['street'] ) : ?>
											<div><?php echo esc_html( $contact_address['street'] ); ?></div>
										<?php endif; ?>
										<?php if ( $contact_address['zip'] || $contact_address['city'] ) : ?>
											<div>
												<?php echo esc_html( trim( $contact_address['zip'] . ' ' . $contact_address['city'] ) ); ?>
											</div>
										<?php endif; ?>
										<?php if ( $contact_address['country'] ) : ?>
											<div><?php echo esc_html( $contact_address['country'] ); ?></div>
										<?php endif; ?>
									</address>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Équipe / Personnes de contact -->
					<?php if ( ! empty( $contact_people ) ) : ?>
						<div>
							<h3 class="text-lg text-cream-100 mb-4">
								<?php esc_html_e( 'Équipe', 'lunivers-theme' ); ?>
							</h3>
							<div class="space-y-3 text-sm text-cream-200">
								<?php foreach ( $contact_people as $person ) : ?>
									<?php
									$name  = $person['name'] ?? '';
									$phone = $person['phone'] ?? '';
									?>
									<?php if ( $name || $phone ) : ?>
										<div>
											<?php if ( $name ) : ?>
												<div class="font-semibold text-cream-100 mb-1">
													<?php echo esc_html( $name ); ?>
												</div>
											<?php endif; ?>
											<?php if ( $phone ) : ?>
												<div class="flex items-center gap-2">
													<svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
													</svg>
													<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="hover:text-primary-400 transition-colors">
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
						<div class="">

							<div class="flex flex-wrap gap-3">
								<?php foreach ( $social_networks as $network ) : ?>
									<?php
									$type = $network['type'] ?? '';
									$url  = $network['url'] ?? '';
									?>
									<?php if ( $type && $url ) : ?>
										<a
											href="<?php echo esc_url( $url ); ?>"
											target="_blank"
											rel="noopener noreferrer"
											class="w-10 h-10 flex items-center justify-center bg-brown-900 rounded-lg hover:bg-primary-500 transition-colors text-cream-100"
											aria-label="<?php echo esc_attr( nl_get_social_network_name( $type ) ); ?>"
										>
											<?php
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
											<?php endif; ?>
										</a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>
					

				</div>

				<!-- COLONNE 2 : CTA -->
				<div class="order-1 lg:order-2">
					
					<div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-500 bg-white/5 border border-white/10">
						<?php if ( ! empty( $footer_cta['image'] ) ) : ?>
							<div class="absolute inset-0">
								<img
									src="<?php echo esc_url( $footer_cta['image']['sizes']['large'] ?? $footer_cta['image']['url'] ); ?>"
									alt=""
									class="w-full h-full object-cover opacity-15"
									loading="lazy"
								>
							</div>
						<?php endif; ?>
	
						<div class="relative p-8 md:p-10 lg:p-12">
							<?php if ( ! empty( $footer_cta['sur_titre'] ) ) : ?>
								<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
									<?php echo esc_html( $footer_cta['sur_titre'] ); ?>
								</span>
							<?php endif; ?>
	
							<h3 class="text-2xl text-cream-100 mb-3">
								<?php
								if ( ! empty( $footer_cta['titre'] ) ) {
									echo esc_html( $footer_cta['titre'] );
								} else {
									esc_html_e( 'Créons ensemble votre safari de rêve', 'lunivers-theme' );
								}
								?>
							</h3>
	
							<?php if ( ! empty( $footer_cta['description'] ) ) : ?>
								<p class="text-cream-200 mb-8 max-w-md">
									<?php echo wp_kses_post( $footer_cta['description'] ); ?>
								</p>
							<?php endif; ?>
	
							<div class="flex flex-col sm:flex-row gap-4">
								<?php if ( ! empty( $footer_cta['lien'] ) ) : ?>
									<a
										href="<?php echo esc_url( $footer_cta['lien']['url'] ); ?>"
										<?php echo ! empty( $footer_cta['lien']['target'] ) ? 'target="' . esc_attr( $footer_cta['lien']['target'] ) . '"' : ''; ?>
										class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-500 text-white font-heading font-semibold tracking-wide rounded-lg hover:bg-primary-600 transition-colors group"
									>
										<?php
										if ( ! empty( $footer_cta['lien']['title'] ) ) {
											echo esc_html( $footer_cta['lien']['title'] );
										} else {
											esc_html_e( 'Demander un devis gratuit', 'lunivers-theme' );
										}
										?>
										<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
										</svg>
									</a>
								<?php endif; ?>
	
								<?php if ( ! empty( $footer_cta['telephone'] ) ) : ?>
									<a
										href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $footer_cta['telephone'] ) ); ?>"
										class="inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-cream-100/30 text-cream-100 font-heading font-semibold tracking-wide rounded-lg hover:bg-white/5 transition-colors group"
									>
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
										</svg>
										<?php echo esc_html( $footer_cta['telephone'] ); ?>
										<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

			</div>

			<!-- Barre de copyright -->
			<div class="border-t border-brown-800 mt-12 pt-6 md:pt-8">
				<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
					<!-- Copyright -->
					<p class="text-xs text-cream-100/70">
						&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary-400 transition-colors">
							<?php bloginfo( 'name' ); ?>
						</a>
						<?php esc_html_e( '- Tous droits réservés', 'lunivers-theme' ); ?>
					</p>

					<!-- Menu footer (CGV, Politique de confidentialité, etc.) -->
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<nav aria-label="<?php esc_attr_e( 'Menu légal', 'lunivers-theme' ); ?>">
							<?php
							wp_nav_menu(
								[
									'theme_location' => 'footer',
									'container'      => false,
									'menu_class'     => 'flex flex-wrap gap-x-6 gap-y-2 text-xs text-cream-100/70',
									'depth'          => 1,
									'fallback_cb'    => false,
									'link_before'    => '',
									'link_after'     => '',
								]
							);
							?>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
