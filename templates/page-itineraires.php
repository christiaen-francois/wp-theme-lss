<?php
/**
 * Template Name: Itinéraires de Safari
 *
 * Template pour afficher les itinéraires de safari
 * Design mobile-first avec navigation sticky entre itinéraires
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// URL de la page contact
$contact_page_url = get_permalink( get_page_by_path( 'contact' ) ) ?: '#';
?>

<div class="bg-cream-50 min-h-screen">
	<?php
	while ( have_posts() ) {
		the_post();

		// Champs ACF pour le hero
		$hero_image      = get_field( 'background_image' ) ? get_field( 'background_image' )['sizes']['large'] : '';
		$sur_titre       = get_field( 'sur_titre' ) ?? '';
		$extrait         = get_field( 'extrait' ) ?? '';
		$extrait_display = ! empty( $extrait ) ? $extrait : ( has_excerpt() ? get_the_excerpt() : '' );

		// Statistiques du header
		$stat_duree           = get_field( 'stat_duree' ) ?: '7-12';
		$stat_personnalisation = get_field( 'stat_personnalisation' ) ?: '100%';

		// Récupérer les itinéraires sélectionnés
		$itineraires_ids = get_field( 'itineraires_selection' ) ?: [];

		// Construire le tableau des itinéraires
		$itineraires = [];
		if ( ! empty( $itineraires_ids ) ) {
			foreach ( $itineraires_ids as $itineraire_id ) {
				$itineraire_post = get_post( $itineraire_id );
				if ( ! $itineraire_post || $itineraire_post->post_type !== 'itineraire' ) {
					continue;
				}

				$jours_data = get_field( 'jours', $itineraire_id ) ?: [];
				$days       = [];
				foreach ( $jours_data as $jour ) {
					$days[] = [
						'label'       => $jour['label'] ?? '',
						'title'       => $jour['titre'] ?? '',
						'location'    => $jour['lieu'] ?? '',
						'description' => $jour['description'] ?? '',
					];
				}

				$itineraires[] = [
					'id'        => sanitize_title( $itineraire_post->post_title ),
					'post_id'   => $itineraire_id,
					'title'     => get_the_title( $itineraire_post ),
					'subtitle'  => get_field( 'sous_titre', $itineraire_id ) ?: '',
					'days'      => $days,
					'duration'  => get_field( 'duree', $itineraire_id ) ?: '',
					'highlight' => get_field( 'highlight', $itineraire_id ) ?: '',
					'galerie'   => get_field( 'galerie', $itineraire_id ) ?: [],
				];
			}
		}
		?>

		<!-- Hero Section -->
		<section class="page-hero relative overflow-hidden <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : 'py-20 md:py-32'; ?>">
			<?php if ( $hero_image ) : ?>
				<div class="absolute inset-0 lazy bg-cover bg-center bg-no-repeat" data-bg="<?php echo esc_url( $hero_image ); ?>">
					<div class="absolute inset-0 bg-brown-950/40"></div>
				</div>
			<?php endif; ?>

			<div class="relative z-10 container mx-auto px-4 flex items-center <?php echo $hero_image ? 'min-h-[50vh] md:min-h-[60vh]' : ''; ?>">
				<div class="max-w-6xl mx-auto text-center <?php echo $hero_image ? 'text-white' : 'text-brown-950'; ?>">
					<?php if ( $sur_titre ) : ?>
						<p class="inline-block px-4 py-2 mb-2 text-sm tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20">
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

					<!-- Stats rapides -->
					<?php if ( ! empty( $itineraires ) ) : ?>
						<div class="flex flex-wrap justify-center gap-6 md:gap-12 pt-8 mt-4 border-t <?php echo $hero_image ? 'border-white/10' : 'border-brown-200'; ?>">
							<div class="text-center">
								<div class="text-2xl md:text-3xl font-bold text-primary-400"><?php echo count( $itineraires ); ?></div>
								<div class="text-sm <?php echo $hero_image ? 'text-cream-300/60' : 'text-brown-500'; ?> uppercase tracking-wide">Itinéraires</div>
							</div>
							<div class="text-center">
								<div class="text-2xl md:text-3xl font-bold text-primary-400"><?php echo esc_html( $stat_duree ); ?></div>
								<div class="text-sm <?php echo $hero_image ? 'text-cream-300/60' : 'text-brown-500'; ?> uppercase tracking-wide">Jours</div>
							</div>
							<div class="text-center">
								<div class="text-2xl md:text-3xl font-bold text-primary-400"><?php echo esc_html( $stat_personnalisation ); ?></div>
								<div class="text-sm <?php echo $hero_image ? 'text-cream-300/60' : 'text-brown-500'; ?> uppercase tracking-wide">Sur mesure</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $itineraires ) ) : ?>
			<!-- Navigation sticky des itinéraires (se positionne sous le header principal) -->
			<nav
				class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-brown-200 shadow-sm transition-all duration-300"
				id="itinerary-nav"
				aria-label="Navigation des itinéraires"
			>
				<div class="container mx-auto px-4">
					<div class="relative">
						<!-- Mobile: Scroll horizontal -->
						<div class="flex overflow-x-auto py-3 lg:py-5 gap-2 scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0 md:justify-center md:flex-wrap md:gap-3">
							<?php foreach ( $itineraires as $index => $itineraire ) : ?>
								<a
									href="#<?php echo esc_attr( $itineraire['id'] ); ?>"
									class="itinerary-nav-link flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 whitespace-nowrap
										<?php echo 0 === $index ? 'bg-primary-500 text-white shadow-md' : 'bg-brown-100 text-brown-700 hover:bg-brown-200'; ?>"
									data-target="<?php echo esc_attr( $itineraire['id'] ); ?>"
								>
									<span class="w-6 h-6 flex items-center justify-center rounded-full bg-white/20 text-xs font-bold">
										<?php echo esc_html( $index + 1 ); ?>
									</span>
									<span class="hidden sm:inline"><?php echo esc_html( $itineraire['title'] ); ?></span>
									<span class="sm:hidden"><?php echo esc_html( $itineraire['duration'] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</nav>

			<!-- Itinéraires -->
			<section class="py-12 md:py-20 lg:py-24">
				<div class="container mx-auto px-4">
					<div class="space-y-20 md:space-y-32">
						<?php foreach ( $itineraires as $index => $itineraire ) :
							$contact_url = add_query_arg(
								'subject',
								urlencode( 'Demande de devis : ' . $itineraire['title'] ),
								$contact_page_url
							);
							$galerie = $itineraire['galerie'];
						?>
							<article
								id="<?php echo esc_attr( $itineraire['id'] ); ?>"
								class="itinerary-section scroll-mt-16"
								data-itinerary-index="<?php echo esc_attr( $index ); ?>"
							>
								<!-- Header de l'itinéraire -->
								<header class="mb-8 md:mb-12">
									<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
										<div>
											<?php if ( $itineraire['highlight'] ) : ?>
												<span class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 mb-2">
													<span class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-100 text-primary-700">
														<?php echo esc_html( $index + 1 ); ?>
													</span>
													<span class="uppercase tracking-wider"><?php echo esc_html( $itineraire['highlight'] ); ?></span>
												</span>
											<?php endif; ?>
											<h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-brown-900 leading-tight">
												<?php echo esc_html( $itineraire['title'] ); ?>
											</h2>
											<?php if ( $itineraire['subtitle'] ) : ?>
												<p class="mt-2 text-base md:text-lg text-brown-600">
													<?php echo esc_html( $itineraire['subtitle'] ); ?>
												</p>
											<?php endif; ?>
										</div>
										<?php if ( $itineraire['duration'] ) : ?>
											<div class="flex items-center gap-4">
												<div class="text-center px-5 py-3 bg-primary-500 rounded-xl shadow-lg">
													<div class="text-2xl md:text-3xl font-bold text-white">
														<?php echo esc_html( $itineraire['duration'] ); ?>
													</div>
													<div class="text-xs uppercase tracking-wide text-primary-100">Durée</div>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</header>

								<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
									<!-- Galerie d'images -->
									<div class="lg:col-span-5 order-1 lg:order-2">
										<div class="lg:sticky lg:top-32">
											<!-- Galerie Swiper -->
											<?php if ( ! empty( $galerie ) ) : ?>
												<div class="itinerary-gallery swiper rounded-2xl overflow-hidden shadow-2xl" data-itinerary-gallery="<?php echo esc_attr( $index ); ?>">
													<div class="swiper-wrapper">
														<?php
														$total_images = count( $galerie );
														foreach ( $galerie as $img_index => $image ) :
															$image_url    = $image['sizes']['large'] ?? $image['url'];
															$image_alt    = $image['alt'] ?? $itineraire['title'] . ' - Photo ' . ( $img_index + 1 );
															$image_width  = $image['width'] ?? 800;
															$image_height = $image['height'] ?? 600;
														?>
															<div class="swiper-slide">
																<div class="relative aspect-[4/3]">
																	<?php if ( $img_index === 0 ) : ?>
																		<img
																			src="<?php echo esc_url( $image_url ); ?>"
																			alt="<?php echo esc_attr( $image_alt ); ?>"
																			class="w-full h-full object-cover"
																			width="<?php echo esc_attr( $image_width ); ?>"
																			height="<?php echo esc_attr( $image_height ); ?>"
																			loading="eager"
																		>
																	<?php else : ?>
																		<img
																			data-src="<?php echo esc_url( $image_url ); ?>"
																			src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 <?php echo esc_attr( $image_width ); ?> <?php echo esc_attr( $image_height ); ?>'%3E%3C/svg%3E"
																			alt="<?php echo esc_attr( $image_alt ); ?>"
																			class="lazy w-full h-full object-cover"
																			width="<?php echo esc_attr( $image_width ); ?>"
																			height="<?php echo esc_attr( $image_height ); ?>"
																			loading="lazy"
																		>
																	<?php endif; ?>
																	<!-- Overlay gradient -->
																	<div class="absolute inset-0 bg-gradient-to-t from-brown-950/60 via-transparent to-transparent"></div>
																	<!-- Numéro de photo -->
																	<div class="absolute bottom-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-sm font-medium text-brown-800">
																		<?php echo esc_html( ( $img_index + 1 ) . '/' . $total_images ); ?>
																	</div>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
													<!-- Pagination -->
													<div class="swiper-pagination"></div>
													<!-- Navigation -->
													<button class="swiper-button-prev !w-10 !h-10 !bg-white/90 !rounded-full after:!text-sm after:!text-brown-800 after:!font-bold" aria-label="Image précédente"></button>
													<button class="swiper-button-next !w-10 !h-10 !bg-white/90 !rounded-full after:!text-sm after:!text-brown-800 after:!font-bold" aria-label="Image suivante"></button>
												</div>
											<?php else : ?>
												<div class="aspect-[4/3] bg-brown-200 rounded-2xl flex items-center justify-center">
													<span class="text-brown-500">Aucune image</span>
												</div>
											<?php endif; ?>

											<!-- CTA Mobile (visible uniquement sur mobile) -->
											<div class="mt-6 lg:hidden">
												<a
													href="<?php echo esc_url( $contact_url ); ?>"
													class="flex items-center justify-center gap-2 w-full px-6 py-4 bg-primary-500 hover:bg-primary-600 text-white font-heading font-semibold tracking-wide rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl"
												>
													<span>Demander un devis personnalisé</span>
													<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
													</svg>
												</a>
											</div>
										</div>
									</div>

									<!-- Timeline / Roadmap -->
									<div class="lg:col-span-7 order-2 lg:order-1">
										<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-500 overflow-hidden">
											<!-- Header de la timeline -->
											<div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white">
												<h3 class="text-lg flex items-center gap-2">
													<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
													</svg>
													Votre itinéraire jour par jour
												</h3>
											</div>

											<!-- Timeline -->
											<?php if ( ! empty( $itineraire['days'] ) ) : ?>
												<div class="p-4 md:p-6">
													<ol class="relative space-y-0">
														<?php
														$total_days = count( $itineraire['days'] );
														foreach ( $itineraire['days'] as $day_index => $day ) :
															$is_last = ( $day_index === $total_days - 1 );
														?>
															<li class="relative pl-8 pb-8 <?php echo $is_last ? 'pb-0' : ''; ?>">
																<!-- Ligne verticale -->
																<?php if ( ! $is_last ) : ?>
																	<div class="absolute left-[11px] top-8 bottom-0 w-0.5 bg-gradient-to-b from-primary-300 to-primary-100" aria-hidden="true"></div>
																<?php endif; ?>

																<!-- Point de la timeline -->
																<div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-primary-500 border-4 border-white shadow-md flex items-center justify-center" aria-hidden="true">
																	<div class="w-2 h-2 rounded-full bg-white"></div>
																</div>

																<!-- Contenu -->
																<div class="bg-cream-50/50 rounded-xl p-4 hover:bg-cream-100/50 transition-colors duration-200 border border-transparent hover:border-brown-200">
																	<div class="flex flex-wrap items-start justify-between gap-2 mb-2">
																		<span class="inline-block px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-wider rounded">
																			<?php echo esc_html( $day['label'] ); ?>
																		</span>
																		<?php if ( $day['location'] ) : ?>
																			<span class="text-xs text-brown-500 font-medium">
																				<?php echo esc_html( $day['location'] ); ?>
																			</span>
																		<?php endif; ?>
																	</div>
																	<h4 class="text-base md:text-lg text-brown-900 mb-1">
																		<?php echo esc_html( $day['title'] ); ?>
																	</h4>
																	<p class="text-sm text-brown-600 leading-relaxed">
																		<?php echo esc_html( $day['description'] ); ?>
																	</p>
																</div>
															</li>
														<?php endforeach; ?>
													</ol>
												</div>
											<?php endif; ?>

											<!-- Footer avec CTA (desktop) -->
											<div class="hidden lg:block px-6 py-4 bg-brown-950 border-t border-brown-100">
												<div class="flex items-center justify-between gap-4">
													<p class="text-sm text-brown-600">
														Cet itinéraire est entièrement personnalisable selon vos envies.
													</p>
													<a
														href="<?php echo esc_url( $contact_url ); ?>"
														class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-heading font-semibold tracking-wide rounded-xl transition-all duration-200 shadow-md hover:shadow-lg whitespace-nowrap"
													>
														<span>Demander un devis</span>
														<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
														</svg>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php else : ?>
			<!-- Message si aucun itinéraire -->
			<section class="py-20 md:py-32">
				<div class="container mx-auto px-4 text-center">
					<p class="text-lg text-brown-600">Aucun itinéraire n'a encore été ajouté.</p>
				</div>
			</section>
		<?php endif; ?>

		<?php
		// Render flexible content if available
		if ( function_exists( 'lunivers_render_flexible_content' ) ) {
			lunivers_render_flexible_content();
		}
	}
	?>
</div>

<!-- Script pour la navigation sticky -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	const nav = document.getElementById('itinerary-nav');
	const navLinks = document.querySelectorAll('.itinerary-nav-link');
	const sections = document.querySelectorAll('.itinerary-section');

	if (!nav || navLinks.length === 0 || sections.length === 0) {
		return;
	}

	// Intersection Observer pour mettre à jour la navigation active
	const observerOptions = {
		root: null,
		rootMargin: '-20% 0px -60% 0px',
		threshold: 0
	};

	const observer = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				const targetId = entry.target.id;

				// Mettre à jour les classes des liens
				navLinks.forEach(link => {
					const isActive = link.dataset.target === targetId;
					link.classList.toggle('bg-primary-500', isActive);
					link.classList.toggle('text-white', isActive);
					link.classList.toggle('shadow-md', isActive);
					link.classList.toggle('bg-brown-100', !isActive);
					link.classList.toggle('text-brown-700', !isActive);
					link.classList.toggle('hover:bg-brown-200', !isActive);
				});

				// Scroll horizontal pour afficher le lien actif sur mobile
				const activeLink = document.querySelector(`.itinerary-nav-link[data-target="${targetId}"]`);
				if (activeLink && window.innerWidth < 768) {
					activeLink.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
				}
			}
		});
	}, observerOptions);

	sections.forEach(section => observer.observe(section));

	// Smooth scroll pour les liens de navigation
	navLinks.forEach(link => {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			const targetId = this.dataset.target;
			const targetSection = document.getElementById(targetId);
			if (targetSection) {
				targetSection.scrollIntoView({ behavior: 'smooth' });
			}
		});
	});
});
</script>

<style>
/* Cache la scrollbar horizontale sur la navigation mobile */
.scrollbar-hide {
	-ms-overflow-style: none;
	scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
	display: none;
}

/* Amélioration des boutons Swiper */
.swiper-button-prev,
.swiper-button-next {
	transition: all 0.2s ease;
}
.swiper-button-prev:hover,
.swiper-button-next:hover {
	transform: scale(1.1);
}
.swiper-button-prev.swiper-button-disabled,
.swiper-button-next.swiper-button-disabled {
	opacity: 0.3;
}

/* Pagination Swiper personnalisée - position absolute */
.itinerary-gallery {
	position: relative;
}
.itinerary-gallery .swiper-pagination {
	position: absolute;
	bottom: 1rem;
	left: 50%;
	transform: translateX(-50%);
	z-index: 10;
}
.itinerary-gallery .swiper-pagination-bullet {
	background: white;
	opacity: 0.5;
	width: 8px;
	height: 8px;
	margin: 0 4px;
}
.itinerary-gallery .swiper-pagination-bullet-active {
	opacity: 1;
	background: white;
}
</style>

<?php
get_footer();
