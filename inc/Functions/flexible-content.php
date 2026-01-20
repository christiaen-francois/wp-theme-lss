<?php
/**
 * Flexible Content Helper Functions
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get aspect ratio class based on format choice
 *
 * @param string $format Format choice: 'paysage', 'carre', 'portrait'
 * @return string CSS class for aspect ratio
 */
function lunivers_get_aspect_ratio_class( $format = 'paysage' ) {
	$formats = [
		'paysage'  => 'aspect-[4/3]',      // 4/3
		'carre'    => 'aspect-square',     // 1/1
		'portrait' => 'aspect-[3/4]',      // 3/4
		'large'    => 'aspect-video',      // 16/9
	];

	return $formats[ $format ] ?? $formats['paysage'];
}

/**
 * Get spacing classes based on spacing choice
 *
 * @param string $espacement Spacing choice: 'top', 'bottom', 'both', 'none'
 * @return string CSS classes for padding
 */
function lunivers_get_spacing_classes( $espacement = 'top' ) {
	$spacing = [
		'top'    => 'pt-16 md:pt-24 xl:pt-32',
		'bottom' => 'pb-16 md:pb-24 xl:pb-32',
		'both'   => 'py-16 md:py-24 xl:py-32',
		'none'   => '',
	];

	return $spacing[ $espacement ] ?? $spacing['top'];
}

/**
 * Get background color classes and text color classes based on background choice
 *
 * @param string $bg_color Background color choice: 'dark', 'light', 'white', 'primary'
 * @return array Array with 'bg_class' and 'text_classes' keys
 */
function lunivers_get_layout_colors( $bg_color = 'white' ) {
	$colors = [
		'dark'    => [
			'bg'   => 'bg-brown-950',
			'text' => 'text-cream-100',
			'text_secondary' => 'text-cream-200',
			'text_muted' => 'text-cream-300',
			'border' => 'border-brown-800',
		],
		'light'   => [
			'bg'   => 'bg-cream-50',
			'text' => 'text-brown-950',
			'text_secondary' => 'text-brown-700',
			'text_muted' => 'text-neutral-600',
			'border' => 'border-cream-200',
		],
		'white'   => [
			'bg'   => 'bg-white',
			'text' => 'text-brown-950',
			'text_secondary' => 'text-brown-700',
			'text_muted' => 'text-neutral-600',
			'border' => 'border-neutral-200',
		],
		'primary' => [
			'bg'   => 'bg-primary-500',
			'text' => 'text-white',
			'text_secondary' => 'text-cream-100',
			'text_muted' => 'text-cream-200',
			'border' => 'border-primary-400',
		],
	];

	return $colors[ $bg_color ] ?? $colors['white'];
}

/**
 * Render flexible content layouts
 *
 * @param string $field_name The ACF field name (default: 'flexible_content')
 * @return void
 */
function lunivers_render_flexible_content( $field_name = 'flexible_content' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$flexible_content = get_field( $field_name );

	if ( ! $flexible_content || ! is_array( $flexible_content ) ) {
		return;
	}

	foreach ( $flexible_content as $layout ) {
		$layout_name = $layout['acf_fc_layout'] ?? '';

		switch ( $layout_name ) {
			case 'texte_image':
				lunivers_render_layout_texte_image( $layout );
				break;

			case 'separateur':
				lunivers_render_layout_separateur( $layout );
				break;

			case 'newsletter':
				lunivers_render_layout_newsletter( $layout );
				break;

			case 'galerie':
				lunivers_render_layout_galerie( $layout );
				break;

			case 'hero':
				lunivers_render_layout_hero( $layout );
				break;

			case 'temoignages':
				lunivers_render_layout_temoignages( $layout );
				break;

			case 'services':
				lunivers_render_layout_services( $layout );
				break;

			case 'faq':
				lunivers_render_layout_faq( $layout );
				break;

			case 'separateur_lion':
				lunivers_render_layout_separateur_lion( $layout );
				break;

			case 'cta':
				lunivers_render_layout_cta( $layout );
				break;

			case 'equipe':
				lunivers_render_layout_equipe( $layout );
				break;

			case 'guide_pratique':
				lunivers_render_layout_guide_pratique( $layout );
				break;
		}
	}
}

/**
 * Render Texte et Image layout
 * Layout unifié (fusion de texte_image et presentation)
 * Style aéré avec support position "none" pour layout sans image
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_texte_image( $layout ) {
	$sous_titre   = $layout['sous_titre'] ?? '';
	$titre        = $layout['titre'] ?? '';
	$contenu      = $layout['contenu'] ?? '';
	$lien         = $layout['lien'] ?? '';
	$image        = $layout['image'] ?? '';
	$position     = $layout['position_image'] ?? 'right';
	$bg_color     = $layout['couleur_fond'] ?? 'light';
	$format_image = $layout['format_image'] ?? 'paysage';
	$espacement   = $layout['espacement'] ?? 'top';

	if ( ! $contenu ) {
		return;
	}

	$colors         = lunivers_get_layout_colors( $bg_color );
	$aspect_class   = lunivers_get_aspect_ratio_class( $format_image );
	$spacing_class  = lunivers_get_spacing_classes( $espacement );

	// Déterminer si on affiche l'image
	$has_image     = $image && $position !== 'none';
	$image_classes = $position === 'right' ? 'md:order-2' : 'md:order-1';
	$text_classes  = $position === 'right' ? 'md:order-1' : 'md:order-2';
	?>
	<section class="layout-texte-image <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<?php if ( $has_image ) : ?>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 lg:gap-20 items-center">
					<!-- Contenu texte -->
					<div class="<?php echo esc_attr( $text_classes ); ?>">
						<?php if ( $sous_titre || $titre ) : ?>
							<header class="mb-8 md:mb-12">
								<?php if ( $sous_titre ) : ?>
									<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
										<?php echo esc_html( $sous_titre ); ?>
									</span>
								<?php endif; ?>
								<?php if ( $titre ) : ?>
									<h2 class="text-4xl md:text-5xl <?php echo esc_attr( $colors['text'] ); ?> leading-tight">
										<?php echo esc_html( $titre ); ?>
									</h2>
								<?php endif; ?>
							</header>
						<?php endif; ?>

						<div class="max-w-none">
							<div class="<?php echo esc_attr( $colors['text_secondary'] ); ?> leading-relaxed">
								<?php echo wp_kses_post( $contenu ); ?>
							</div>
						</div>

						<?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
							<div class="mt-8">
								<a
									href="<?php echo esc_url( $lien['url'] ); ?>"
									target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
									class="btn-texte-image inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-500 text-primary-500 font-heading font-semibold tracking-wide rounded-lg transition-all duration-300 hover:bg-primary-500 hover:text-white group"
								>
									<?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
									<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
									</svg>
								</a>
							</div>
						<?php endif; ?>
					</div>

					<!-- Image -->
					<div class="<?php echo esc_attr( $image_classes ); ?>">
						<?php
						$image_url    = $image['url'] ?? '';
						$image_alt    = $image['alt'] ?? '';
						$image_width  = $image['width'] ?? '';
						$image_height = $image['height'] ?? '';
						$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
						?>
						<div class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-500">
							<div class="<?php echo esc_attr( $aspect_class ); ?> bg-neutral-100">
								<img
									data-src="<?php echo esc_url( $image_url ); ?>"
									src="<?php echo esc_attr( $placeholder ); ?>"
									alt="<?php echo esc_attr( $image_alt ); ?>"
									width="<?php echo esc_attr( $image_width ); ?>"
									height="<?php echo esc_attr( $image_height ); ?>"
									class="lazy w-full h-full object-cover"
									loading="lazy"
								>
							</div>
						</div>
					</div>
				</div>
			<?php else : ?>
				<!-- Contenu sans image (centré) -->
				<div class="max-w-4xl mx-auto">
					<div class="max-w-none">
						<?php if ( $sous_titre || $titre ) : ?>
							<header class="text-center mb-12 md:mb-16">
								<?php if ( $sous_titre ) : ?>
									<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
										<?php echo esc_html( $sous_titre ); ?>
									</span>
								<?php endif; ?>
								<?php if ( $titre ) : ?>
									<h2 class="text-4xl md:text-5xl lg:text-6xl <?php echo esc_attr( $colors['text'] ); ?> leading-tight">
										<?php echo esc_html( $titre ); ?>
									</h2>
								<?php endif; ?>
							</header>
						<?php endif; ?>

						<div class="<?php echo esc_attr( $colors['text_secondary'] ); ?> leading-relaxed text-lg md:text-xl space-y-6">
							<?php echo wp_kses_post( $contenu ); ?>
						</div>

						<?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
							<div class="mt-8 text-center">
								<a
									href="<?php echo esc_url( $lien['url'] ); ?>"
									target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
									class="btn-texte-image inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-500 text-primary-500 font-heading font-semibold tracking-wide rounded-lg transition-all duration-300 hover:bg-primary-500 hover:text-white group"
								>
									<?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
									<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
									</svg>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render Séparateur Hero layout
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_separateur( $layout ) {
	$image     = $layout['image_fond'] ?? '';
	$bg_color  = $layout['couleur_fond'] ?? 'light';
	$espacement = $layout['espacement'] ?? 'top';

	if ( ! $image ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	$image_url     = $image['url'] ?? '';
	$image_alt     = $image['alt'] ?? '';
	?>
	<section class="layout-separateur relative overflow-hidden <?php echo esc_attr( $spacing_class ); ?>">
		<div class="parallax-bg absolute inset-0 lazy bg-cover bg-center bg-no-repeat" data-bg="<?php echo esc_url( $image_url ); ?>"></div>
		<div class="absolute inset-0 bg-brown-950/40 pointer-events-none"></div>
		<div class="relative z-10 container mx-auto px-4 ">
			<!-- Espace pour le séparateur -->
		</div>
	</section>
	<?php
}

/**
 * Render Newsletter layout
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_newsletter( $layout ) {
	$titre       = $layout['titre'] ?? 'Recevez nos idées de safaris en Tanzanie';
	$description = $layout['description'] ?? 'Inspirez votre prochain voyage : itinéraires sur mesure, meilleures périodes, conseils terrain et nouveautés depuis la Tanzanie. Une newsletter courte, utile, et sans spam.';
	$bg_color    = $layout['couleur_fond'] ?? 'primary';
	$espacement  = $layout['espacement'] ?? 'top';

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-newsletter <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<div class="max-w-6xl mx-auto text-center">
				<?php if ( $titre ) : ?>
					<h2 class="text-3xl md:text-4xl <?php echo esc_attr( $colors['text'] ); ?> mb-6">
						<?php echo esc_html( $titre ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="<?php echo esc_attr( $colors['text_secondary'] ); ?> mb-8 text-lg">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>

				<!-- Messages de feedback -->
				<div data-success-message class="hidden mb-6 p-4 bg-green-100 text-green-800 rounded-lg" role="alert"></div>
				<div data-error-message class="hidden mb-6 p-4 bg-red-100 text-red-800 rounded-lg" role="alert"></div>

				<form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto" method="post" data-newsletter-form>
					<?php wp_nonce_field( 'nl_newsletter_form_nonce', 'nonce' ); ?>
					<!-- Honeypot anti-spam -->
					<input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

					<div class="flex-1">
						<input
							type="email"
							name="email"
							placeholder="<?php esc_attr_e( 'Votre adresse email', 'lunivers-theme' ); ?>"
							required
							aria-label="<?php esc_attr_e( 'Adresse email', 'lunivers-theme' ); ?>"
							class="w-full px-4 py-3 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-cream-200"
						>
					</div>
					<button
						type="submit"
						data-submit-button
						class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brown-950 text-white font-heading font-semibold tracking-wide rounded-lg hover:bg-brown-900 transition-colors whitespace-nowrap group"
					>
						<span data-button-text><?php esc_html_e( 'S\'inscrire', 'lunivers-theme' ); ?></span>
						<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
						</svg>
					</button>
				</form>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Galerie layout
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_galerie( $layout ) {
	$photos       = $layout['photos'] ?? '';
	$bg_color     = $layout['couleur_fond'] ?? 'light';
	$format_image = $layout['format_image'] ?? 'carre';
	$espacement   = $layout['espacement'] ?? 'top';

	if ( ! $photos || ! is_array( $photos ) || empty( $photos ) ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$aspect_class  = lunivers_get_aspect_ratio_class( $format_image );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-galerie <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<div class="flex flex-wrap justify-center gap-4">
				<?php foreach ( $photos as $photo ) : ?>
					<?php
					$photo_url    = $photo['url'] ?? '';
					$photo_alt    = $photo['alt'] ?? '';
					$photo_width  = $photo['width'] ?? '';
					$photo_height = $photo['height'] ?? '';
					$thumbnail_url = $photo['sizes']['medium'] ?? $photo_url;
					$placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $photo_width ) . ' ' . esc_attr( $photo_height ) . '"%3E%3C/svg%3E';
					?>
					<a
						href="<?php echo esc_url( $photo_url ); ?>"
						class="group relative <?php echo esc_attr( $aspect_class ); ?> w-[calc(50%-0.5rem)] md:w-[calc(33.333%-0.67rem)] lg:w-[calc(25%-0.75rem)] overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300"
						data-lightbox="galerie"
						data-glightbox="type: image"
					>
						<img
							data-src="<?php echo esc_url( $thumbnail_url ); ?>"
							src="<?php echo esc_attr( $placeholder ); ?>"
							alt="<?php echo esc_attr( $photo_alt ); ?>"
							width="<?php echo esc_attr( $photo_width ); ?>"
							height="<?php echo esc_attr( $photo_height ); ?>"
							class="lazy w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
						>
						<div class="absolute inset-0 bg-brown-950/0 group-hover:bg-brown-950/20 transition-colors duration-300"></div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Hero layout avec image de fond
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_hero( $layout ) {
	$titre        = $layout['titre'] ?? '';
	$sous_titre   = $layout['sous_titre'] ?? '';
	$description  = $layout['description'] ?? '';
	$image        = $layout['image_fond'] ?? '';
	$lien         = $layout['lien'] ?? '';
	$hauteur      = $layout['hauteur'] ?? '80vh';
	$overlay      = $layout['overlay'] ?? '40';
	$bg_color     = $layout['couleur_fond'] ?? 'dark';
	$espacement   = $layout['espacement'] ?? 'top';

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-hero relative flex items-center justify-center overflow-hidden <?php echo esc_attr( $spacing_class ); ?>" style="min-height: <?php echo esc_attr( $hauteur ); ?>;">
		<?php if ( $image ) : ?>
			<?php
			$image_url = $image['url'] ?? '';
			$image_alt = $image['alt'] ?? '';
			?>
			<div class="parallax-bg absolute inset-0 lazy bg-cover bg-center bg-no-repeat" data-bg="<?php echo esc_url( $image_url ); ?>"></div>
			<div class="absolute inset-0 bg-brown-950/<?php echo esc_attr( $overlay ); ?> pointer-events-none"></div>
		<?php else : ?>
			<div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-700"></div>
			<div class="absolute inset-0 bg-brown-950/<?php echo esc_attr( $overlay ); ?> pointer-events-none"></div>
		<?php endif; ?>

		<div class="relative z-10 container mx-auto px-4 text-center py-16 md:py-24 xl:py-32">
			<div class="max-w-4xl mx-auto">
				<?php if ( $sous_titre ) : ?>
					<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 6">
						<?php echo esc_html( $sous_titre ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $titre ) : ?>
					<h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl <?php echo esc_attr( $colors['text'] ); ?> mb-6 leading-tight drop-shadow-lg">
						<?php echo esc_html( $titre ); ?>
					</h1>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="prose prose-lg max-w-none mb-8 <?php echo esc_attr( $colors['text_secondary'] ); ?>">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
					<a
						href="<?php echo esc_url( $lien['url'] ); ?>"
						target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
						class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white font-heading font-semibold tracking-wide rounded-lg shadow-xl hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 group"
					>
						<?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
						<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Témoignages layout
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_temoignages( $layout ) {
	$titre      = $layout['titre'] ?? 'Témoignages';
	$sous_titre = $layout['sous_titre'] ?? '';
	$limit      = $layout['nombre_temoignages'] ?? -1;
	$bg_color   = $layout['couleur_fond'] ?? 'light';
	$espacement = $layout['espacement'] ?? 'top';

	// WP_Query pour récupérer les témoignages
	$args = [
		'post_type'      => 'testimonial',
		'posts_per_page' => $limit > 0 ? $limit : -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	];

	$testimonials_query = new WP_Query( $args );

	if ( ! $testimonials_query->have_posts() ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-temoignages <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?> overflow-hidden">
		<div class="container mx-auto px-4">
			<div class="max-w-6xl mx-auto">
				<?php if ( $titre || $sous_titre ) : ?>
					<header class="text-center mb-12 md:mb-16">
						<?php if ( $sous_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
								<?php echo esc_html( $sous_titre ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $titre ) : ?>
							<h2 class="text-3xl md:text-4xl lg:text-5xl <?php echo esc_attr( $colors['text'] ); ?>">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<!-- Swiper Slider -->
				<div class="testimonials-slider swiper" data-swiper="testimonials">
					<div class="swiper-wrapper">
						<?php
						while ( $testimonials_query->have_posts() ) :
							$testimonials_query->the_post();

							$nom      = get_field( 'nom' ) ?? '';
							$role     = get_field( 'role' ) ?? '';
							$photo    = get_field( 'photo' ) ?? '';
							$citation = get_field( 'citation' ) ?? '';
							$note     = get_field( 'note' ) ?? 5;
							?>
							<div class="swiper-slide">
								<article class="bg-white rounded-lg shadow-md p-6 md:p-8 transition-shadow h-full flex flex-col">
									<?php if ( $note ) : ?>
										<div class="flex gap-1 mb-4">
											<?php
											$note_int = (int) $note;
											for ( $i = 1; $i <= 5; $i++ ) :
												?>
												<svg 
													class="w-5 h-5 <?php echo $i <= $note_int ? 'text-yellow-400' : 'text-neutral-300'; ?>" 
													fill="currentColor" 
													viewBox="0 0 20 20"
													aria-hidden="true"
												>
													<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
												</svg>
												<?php
											endfor;
											?>
										</div>
									<?php endif; ?>

									<?php if ( $citation ) : ?>
										<blockquote class="text-neutral-700 mb-6 italic text-lg leading-relaxed flex-grow">
											"<?php echo esc_html( $citation ); ?>"
										</blockquote>
									<?php endif; ?>

									<footer class="flex items-center gap-4 mt-auto">
										<?php if ( $photo ) : ?>
											<?php
											$photo_url    = $photo['url'] ?? '';
											$photo_alt    = $photo['alt'] ?? $nom;
											$photo_width  = $photo['width'] ?? 60;
											$photo_height = $photo['height'] ?? 60;
											$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $photo_width ) . ' ' . esc_attr( $photo_height ) . '"%3E%3C/svg%3E';
											?>
											<img 
												data-src="<?php echo esc_url( $photo['sizes']['thumbnail'] ?? $photo_url ); ?>" 
												src="<?php echo esc_attr( $placeholder ); ?>"
												alt="<?php echo esc_attr( $photo_alt ); ?>"
												width="<?php echo esc_attr( $photo_width ); ?>"
												height="<?php echo esc_attr( $photo_height ); ?>"
												class="lazy w-12 h-12 md:w-16 md:h-16 rounded-full object-cover flex-shrink-0"
											>
										<?php endif; ?>
										<div>
											<?php if ( $nom ) : ?>
												<p class="font-semibold text-brown-950"><?php echo esc_html( $nom ); ?></p>
											<?php endif; ?>
											<?php if ( $role ) : ?>
												<p class="text-sm text-neutral-600"><?php echo esc_html( $role ); ?></p>
											<?php endif; ?>
										</div>
									</footer>
								</article>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Services layout (Cards)
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_services( $layout ) {
	$titre      = $layout['titre'] ?? 'Nos Services';
	$sous_titre = $layout['sous_titre'] ?? '';
	$services   = $layout['services'] ?? [];
	$bg_color   = $layout['couleur_fond'] ?? 'white';
	$espacement = $layout['espacement'] ?? 'top';

	if ( empty( $services ) || ! is_array( $services ) ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-services <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<div class="">
				<?php if ( $titre || $sous_titre ) : ?>
					<header class="text-center mb-12 md:mb-16">
						<?php if ( $sous_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
								<?php echo esc_html( $sous_titre ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $titre ) : ?>
							<h2 class="text-3xl md:text-4xl lg:text-5xl <?php echo esc_attr( $colors['text'] ); ?>">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
					<?php foreach ( $services as $service ) : ?>
						<?php
						$titre_service = $service['titre'] ?? '';
						$description   = $service['description'] ?? '';
						$icone         = $service['icone'] ?? '';
						$image         = $service['image'] ?? '';
						$lien          = $service['lien'] ?? '';
						$format_image  = $service['format_image'] ?? 'paysage';
						$aspect_class  = lunivers_get_aspect_ratio_class( $format_image );
						?>
						<article class="group bg-white rounded-lg shadow-md border border-neutral-200 overflow-hidden">
							<?php if ( $image ) : ?>
								<?php
								$image_url    = $image['url'] ?? '';
								$image_alt    = $image['alt'] ?? $titre_service;
								$image_width  = $image['width'] ?? '';
								$image_height = $image['height'] ?? '';
								$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
								?>
								<div class="relative <?php echo esc_attr( $aspect_class ); ?> overflow-hidden bg-neutral-200">
									<img
										data-src="<?php echo esc_url( $image['sizes']['large'] ?? $image_url ); ?>"
										src="<?php echo esc_attr( $placeholder ); ?>"
										alt="<?php echo esc_attr( $image_alt ); ?>"
										width="<?php echo esc_attr( $image_width ); ?>"
										height="<?php echo esc_attr( $image_height ); ?>"
										class="lazy w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
									>
								</div>
							<?php elseif ( $icone ) : ?>
								<div class="relative <?php echo esc_attr( $aspect_class ); ?> flex items-center justify-center bg-primary-500">
									<div class="text-6xl text-white"><?php echo wp_kses_post( $icone ); ?></div>
								</div>
							<?php endif; ?>

							<div class="p-6 md:p-8">
								<?php if ( $titre_service ) : ?>
									<h3 class="text-xl md:text-2xl text-brown-950 mb-4">
										<?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
											<a 
												href="<?php echo esc_url( $lien['url'] ); ?>"
												target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
												class="hover:text-primary-500 transition-colors"
											>
												<?php echo esc_html( $titre_service ); ?>
											</a>
										<?php else : ?>
											<?php echo esc_html( $titre_service ); ?>
										<?php endif; ?>
									</h3>
								<?php endif; ?>

								<?php if ( $description ) : ?>
									<div class="prose prose-sm max-w-none text-neutral-700 mb-4">
										<?php echo wp_kses_post( $description ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
									<a 
										href="<?php echo esc_url( $lien['url'] ); ?>"
										target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
										class="inline-flex items-center text-primary-500 font-semibold hover:text-primary-600 transition-colors group/link"
									>
										<?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
										<svg class="w-4 h-4 ml-2 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render FAQ layout (Accordéon)
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_faq( $layout ) {
	$titre      = $layout['titre'] ?? 'Questions fréquentes';
	$sous_titre = $layout['sous_titre'] ?? '';
	$questions  = $layout['questions'] ?? [];
	$bg_color   = $layout['couleur_fond'] ?? 'light';
	$espacement = $layout['espacement'] ?? 'top';

	if ( empty( $questions ) || ! is_array( $questions ) ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );

	// Convertir les IDs en array si ce n'est pas déjà le cas
	$question_ids = is_array( $questions ) ? $questions : [ $questions ];
	?>
	<section class="layout-faq <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<div class="max-w-4xl mx-auto">
				<?php if ( $titre || $sous_titre ) : ?>
					<header class="text-center mb-12 md:mb-16">
						<?php if ( $sous_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
								<?php echo esc_html( $sous_titre ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $titre ) : ?>
							<h2 class="text-3xl md:text-4xl lg:text-5xl <?php echo esc_attr( $colors['text'] ); ?>">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<div class="space-y-4" role="list">
					<?php
					foreach ( $question_ids as $index => $question_id ) :
						$question_post = get_post( $question_id );
						
						if ( ! $question_post || $question_post->post_type !== 'faq' ) {
							continue;
						}

						$question_text = get_the_title( $question_post );
						$reponse       = get_field( 'reponse', $question_id ) ?? '';
						$faq_id        = 'faq-' . $question_id . '-' . $index;
						?>
						<details 
							class="group bg-white rounded-lg shadow-md overflow-hidden"
							role="listitem"
							id="<?php echo esc_attr( $faq_id ); ?>"
						>
							<summary 
								class="flex items-center justify-between p-6 cursor-pointer list-none hover:bg-brown-950 hover:text-white transition-colors"
								role="button"
								aria-expanded="false"
							>
								<span class="font-semibold text-lg text-brown-950 group-hover:text-white pr-4 transition-colors">
									<?php echo esc_html( $question_text ); ?>
								</span>
								<svg 
									class="w-6 h-6 text-primary-500 group-hover:text-white flex-shrink-0 transform transition-all group-open:rotate-180" 
									fill="none" 
									stroke="currentColor" 
									viewBox="0 0 24 24"
									aria-hidden="true"
								>
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
								</svg>
							</summary>
							<div class="px-6 py-6">
								<div class="prose prose-sm max-w-none text-neutral-700">
									<?php echo wp_kses_post( $reponse ); ?>
								</div>
							</div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render CTA (Call to Action) layout
 * Design adaptatif : centré avec effets décoratifs si pas d'image, ou texte + image côte à côte
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_cta( $layout ) {
	$sur_titre       = $layout['sur_titre'] ?? '';
	$titre           = $layout['titre'] ?? 'Créons ensemble votre safari de rêve';
	$description     = $layout['description'] ?? 'Chaque itinéraire est personnalisable. Parlez-nous de vos envies et nous créerons une aventure unique, parfaitement adaptée à vos attentes.';
	$lien_principal  = $layout['lien_principal'] ?? '';
	$lien_secondaire = $layout['lien_secondaire'] ?? '';
	$image           = $layout['image'] ?? '';
	$position        = $layout['position_image'] ?? 'right';
	$bg_color        = $layout['couleur_fond'] ?? 'dark';
	$format_image    = $layout['format_image'] ?? 'paysage';
	$espacement      = $layout['espacement'] ?? 'both';

	$colors        = lunivers_get_layout_colors( $bg_color );
	$aspect_class  = lunivers_get_aspect_ratio_class( $format_image );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	$has_image     = ! empty( $image );

	// Si pas d'image : design centré avec effets décoratifs
	if ( ! $has_image ) :
		?>
		<section class="layout-cta relative <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?> overflow-hidden">
			<!-- Background décoratif -->
			<?php if ( $bg_color === 'dark' ) : ?>
				<div class="absolute inset-0">
					<div class="absolute inset-0 bg-gradient-to-br from-brown-950 via-brown-900 to-brown-950"></div>
					<div class="absolute top-0 right-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
					<div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl"></div>
				</div>
			<?php elseif ( $bg_color === 'primary' ) : ?>
				<div class="absolute inset-0">
					<div class="absolute inset-0 bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700"></div>
					<div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
					<div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
				</div>
			<?php endif; ?>

			<div class="relative z-10 container mx-auto px-4">
				<div class="max-w-6xl mx-auto text-center">
					<?php if ( $sur_titre ) : ?>
						<span class="inline-block px-4 py-2 mb-6 text-sm font-semibold tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20">
							<?php echo esc_html( $sur_titre ); ?>
						</span>
					<?php endif; ?>

					<?php if ( $titre ) : ?>
						<h2 class="text-3xl sm:text-4xl md:text-5xl <?php echo esc_attr( $colors['text'] ); ?> mb-6 leading-tight">
							<?php echo esc_html( $titre ); ?>
						</h2>
					<?php endif; ?>

					<?php if ( $description ) : ?>
						<div class="text-base md:text-lg <?php echo esc_attr( $colors['text_secondary'] ); ?> mb-10 max-w-2xl mx-auto leading-relaxed">
							<?php echo wp_kses_post( $description ); ?>
						</div>
					<?php endif; ?>

					<div class="flex flex-col sm:flex-row gap-4 justify-center">
						<?php if ( $lien_principal && ! empty( $lien_principal['url'] ) ) : ?>
							<a
								href="<?php echo esc_url( $lien_principal['url'] ); ?>"
								target="<?php echo esc_attr( $lien_principal['target'] ?? '_self' ); ?>"
								class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary-500 hover:bg-primary-400 text-white font-heading font-semibold tracking-wide rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
							>
								<span><?php echo esc_html( $lien_principal['title'] ?? 'Demander un devis gratuit' ); ?></span>
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( $lien_secondaire && ! empty( $lien_secondaire['url'] ) ) : ?>
							<a
								href="<?php echo esc_url( $lien_secondaire['url'] ); ?>"
								target="<?php echo esc_attr( $lien_secondaire['target'] ?? '_self' ); ?>"
								class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 <?php echo esc_attr( $colors['text'] ); ?> font-heading font-semibold tracking-wide rounded-xl border-2 border-white/20 hover:border-white/40 transition-all duration-300"
							>
								<?php
								// Icône téléphone si le lien contient "tel:"
								$is_phone = strpos( $lien_secondaire['url'] ?? '', 'tel:' ) !== false;
								if ( $is_phone ) :
									?>
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
									</svg>
								<?php endif; ?>
								<span><?php echo esc_html( $lien_secondaire['title'] ?? 'Nous appeler' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	else :
		// Design avec image (texte + image côte à côte)
		?>
		<section class="layout-cta <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
			<div class="container mx-auto px-4">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
					<!-- Contenu -->
					<div class="<?php echo $position === 'right' ? 'md:order-1' : 'md:order-2'; ?> text-center md:text-left">
						<?php if ( $sur_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-4 text-sm font-semibold tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20">
								<?php echo esc_html( $sur_titre ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $titre ) : ?>
							<h2 class="text-3xl md:text-4xl lg:text-5xl <?php echo esc_attr( $colors['text'] ); ?> mb-6">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>

						<?php if ( $description ) : ?>
							<div class="prose prose-lg max-w-none mb-8 <?php echo esc_attr( $colors['text_secondary'] ); ?>">
								<?php echo wp_kses_post( $description ); ?>
							</div>
						<?php endif; ?>

						<div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
							<?php if ( $lien_principal && ! empty( $lien_principal['url'] ) ) : ?>
								<a
									href="<?php echo esc_url( $lien_principal['url'] ); ?>"
									target="<?php echo esc_attr( $lien_principal['target'] ?? '_self' ); ?>"
									class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary-500 hover:bg-primary-400 text-white font-heading font-semibold tracking-wide rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
								>
									<span><?php echo esc_html( $lien_principal['title'] ?? 'En savoir plus' ); ?></span>
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
									</svg>
								</a>
							<?php endif; ?>

							<?php if ( $lien_secondaire && ! empty( $lien_secondaire['url'] ) ) : ?>
								<a
									href="<?php echo esc_url( $lien_secondaire['url'] ); ?>"
									target="<?php echo esc_attr( $lien_secondaire['target'] ?? '_self' ); ?>"
									class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 <?php echo esc_attr( $colors['text'] ); ?> font-heading font-semibold tracking-wide rounded-xl border-2 <?php echo esc_attr( $colors['border'] ); ?> hover:border-white/40 transition-all duration-300"
								>
									<span><?php echo esc_html( $lien_secondaire['title'] ?? 'Contactez-nous' ); ?></span>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<!-- Image -->
					<div class="<?php echo $position === 'right' ? 'md:order-2' : 'md:order-1'; ?>">
						<?php
						$image_url    = $image['url'] ?? '';
						$image_alt    = $image['alt'] ?? '';
						$image_width  = $image['width'] ?? '';
						$image_height = $image['height'] ?? '';
						$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
						?>
						<div class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-500">
							<div class="<?php echo esc_attr( $aspect_class ); ?> bg-neutral-100">
								<img
									data-src="<?php echo esc_url( $image_url ); ?>"
									src="<?php echo esc_attr( $placeholder ); ?>"
									alt="<?php echo esc_attr( $image_alt ); ?>"
									width="<?php echo esc_attr( $image_width ); ?>"
									height="<?php echo esc_attr( $image_height ); ?>"
									class="lazy w-full h-full object-cover"
								>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	endif;
}

/**
 * Render Équipe layout
 * Layout élégant pour présenter les membres de l'équipe
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_equipe( $layout ) {
	$titre      = $layout['titre'] ?? 'Notre équipe';
	$sous_titre = $layout['sous_titre'] ?? '';
	$membres    = $layout['membres'] ?? [];
	$bg_color   = $layout['couleur_fond'] ?? 'light';
	$espacement = $layout['espacement'] ?? 'top';

	if ( empty( $membres ) || ! is_array( $membres ) ) {
		return;
	}

	// Convertir les IDs en array si ce n'est pas déjà le cas
	$membre_ids = is_array( $membres ) ? $membres : [ $membres ];

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-equipe <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>">
		<div class="container mx-auto px-4">
			<div class="">
				<?php if ( $sous_titre || $titre ) : ?>
					<header class="text-center mb-16 md:mb-20">
						<?php if ( $sous_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 6">
								<?php echo esc_html( $sous_titre ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $titre ) : ?>
							<h2 class="text-4xl md:text-5xl lg:text-6xl <?php echo esc_attr( $colors['text'] ); ?> leading-tight">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 md:gap-10 xl:gap-12">
					<?php foreach ( $membre_ids as $membre_id ) : ?>
						<?php
						$membre_post = get_post( $membre_id );
						
						if ( ! $membre_post || $membre_post->post_type !== 'team' ) {
							continue;
						}

						$nom         = get_the_title( $membre_post );
						$image       = get_field( 'image', $membre_id ) ?? '';
						$description = get_field( 'description', $membre_id ) ?? '';
						?>
						
						<article class="group bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 flex flex-col border border-neutral-200">
							<?php if ( $image ) : ?>
								<?php
								$image_url    = $image['url'] ?? '';
								$image_alt    = $image['alt'] ?? $nom;
								$image_width  = $image['width'] ?? '';
								$image_height = $image['height'] ?? '';
								$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
								?>
								<div class="relative overflow-hidden bg-neutral-100 aspect-[4/3]">
									<img 
										data-src="<?php echo esc_url( $image_url ); ?>" 
										src="<?php echo esc_attr( $placeholder ); ?>"
										alt="<?php echo esc_attr( $image_alt ); ?>"
										width="<?php echo esc_attr( $image_width ); ?>"
										height="<?php echo esc_attr( $image_height ); ?>"
										class="lazy w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
										loading="lazy"
									>
									<div class="absolute inset-0 bg-gradient-to-t from-brown-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
								</div>
							<?php endif; ?>

							<div class="p-8 md:p-10 flex-grow flex flex-col">
								<?php if ( $nom ) : ?>
									<h3 class="text-2xl md:text-3xl text-brown-950 mb-4 group-hover:text-primary-500 transition-colors duration-300">
										<?php echo esc_html( $nom ); ?>
									</h3>
								<?php endif; ?>

								<?php if ( $description ) : ?>
									<div class="prose prose-sm md:prose-base prose-brown max-w-none text-neutral-700 leading-relaxed flex-grow">
										<?php echo wp_kses_post( $description ); ?>
									</div>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Séparateur Lion layout
 * Layout avec le symbole du lion du logo
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_separateur_lion( $layout ) {
	$couleur    = $layout['couleur'] ?? 'dark';
	$espacement = $layout['espacement'] ?? 'top';

	// Déterminer la couleur du lion selon le choix
	$lion_colors = [
		'dark'    => '#fcbb46', // Jaune/orange pour fond sombre
		'primary' => '#fcbb46', // Jaune/orange pour fond primaire
		'light'   => '#fcbb46', // Jaune/orange pour fond clair
	];

	$lion_color    = $lion_colors[ $couleur ] ?? '#fcbb46';
	$spacing_class = lunivers_get_spacing_classes( $espacement );
	?>
	<section class="layout-separateur-lion bg-white <?php echo esc_attr( $spacing_class ); ?>">
		<div class="container mx-auto px-4">
			<div class="flex justify-center items-center">
				<svg class="w-24 md:w-32 h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
					<g id="lion">
						<path d="M191.36,85.95c-3.42-11.45-10.98-23.02-21.72-28.81,2.36-1.53,4.13-3.42,5.55-5.9,2.48-4.37,4.13-10.86,2.72-15.82-3.31-11.81-20.31-15.47-31.05-15.82-2.13,0-6.49.94-8.26,0,3.31-4.01,13.11-3.19,17.83-4.37-3.66-2.48-7.2-3.78-11.57-4.72-8.38-1.77-18.65-2.95-26.68.83-2.72,1.3-5.19,3.31-7.67,4.84.94-1.77,2.01-3.31,3.31-4.72,6.49-6.85,14.76-8.15,23.73-8.26-3.07-.94-6.38-1.65-9.45-2.36-7.44-1.53-15.47-1.18-22.08,3.19-7.44,4.96-7.32,12.51-8.97,20.42-.94-2.48-2.01-4.96-3.42-7.2-4.84-7.67-12.28-11.22-20.78-13.11,5.55,7.67,8.03,11.92,9.33,21.49-.94-1.65-1.89-3.31-2.83-5.08-2.83-5.08-8.62-13.11-14.52-14.64-1.18-.35-2.01-.12-3.07.47,3.78,3.78,6.73,8.15,8.5,13.11-8.15-4.25-12.04-6.14-21.61-5.19,4.25,2.24,10.98,5.08,12.51,10.04v.83c-.94.47-1.53.47-2.6.12-2.13-.59-4.01-1.53-6.02-2.24-6.49-2.01-14.17-2.13-20.19,1.18-3.78,2.01-6.49,5.08-8.38,8.74-1.89,5.19-1.89,10.27.47,15.23,1.53,3.19,3.9,5.79,6.26,8.38l.59.24c.35-.12.71-.24,1.06-.47h0c-.24.24-.71.35-1.06.47l-.59-.24c-2.48,2.48-5.55,4.37-8.15,6.85-7.91,7.44-13.34,17.71-16.06,28.22,1.42-2.24,2.6-4.6,4.01-6.85,4.25-6.61,9.68-12.99,15.7-18.06-2.24,4.6-5.31,8.85-7.79,13.46-6.49,11.92-8.03,25.86-7.08,39.32,1.53-3.07,2.24-7.32,3.42-10.74,4.84-12.99,9.45-17.83,19.13-27.39-2.13,5.43-5.43,10.27-8.26,15.35-7.44,13.46-9.56,26.56-5.79,41.44,2.95-11.57,6.14-23.26,16.29-30.7-4.49,7.56-7.79,16.17-8.5,24.91-.71,9.56,2.13,19.48,8.38,26.8-3.54-17.83-1.3-27.15,8.38-42.27-3.19,12.75-4.25,27.27.94,39.67,4.01,9.68,10.63,18.18,20.54,22.2-2.24-2.6-5.08-4.72-7.32-7.32-4.72-5.67-5.43-13.34-4.72-20.42,1.3,3.66,2.01,7.56,3.78,11.22,3.42,6.97,14.64,17.71,21.37,21.61-4.13-9.56-11.81-18.06-14.64-28.22-2.24-7.79,6.49-15.58,5.19-24.32-.59-3.66-4.96-1.65-7.32-3.42-3.66-2.72,2.6-13.46.47-19.13-1.65-4.25-4.37-8.26-6.61-12.16-3.54-6.14-8.26-15.82-7.79-23.02.24-4.25,4.84-9.21,6.85-12.87,1.06-1.89,1.65-3.78,2.36-5.9.35-1.3.94-2.6.71-4.01-.24-.47-.59-.83-1.06-1.06-3.54-2.13-13.22-1.06-16.77.59-2.24,1.06-4.6,3.54-6.97,4.13h-.24c-3.42-3.66-5.43-8.5-5.08-13.58.12-3.19,1.42-6.38,3.78-8.5,2.24-2.01,5.31-2.83,8.26-2.72,4.01.24,6.49,2.13,9.45,4.49-3.54,2.24-10.86,6.61-11.92,10.86v1.18l.47.71c1.42.24,9.21-3.07,11.33-3.54,7.91-2.13,16.88-4.01,25.15-3.9-2.83,2.72-5.55,5.55-8.03,8.62-3.66,4.72-13.58,20.42-13.81,25.97-.24,8.26,12.75,18.42,14.52,24.2.94,2.95.24,6.61.24,9.68l.47.35c1.18-1.06,2.83-12.28,2.95-14.64,0-1.06,0-2.13.12-3.19,2.36,3.78,2.6,6.61,1.53,10.98-1.53,6.02-4.01,11.81-5.08,17.95,2.83-.59,5.67-1.06,8.5-1.3-1.65,2.24-3.31,4.6-4.96,6.85,4.13,1.06,8.5,1.42,12.51,2.95-2.83,1.3-6.02,2.13-8.85,3.54.83,2.36,1.65,5.79,4.13,6.85,3.78,1.65,8.85.24,12.4-1.18,2.01-.83,4.6-1.89,5.79-3.66.24-1.06,0-2.01,0-3.07-.35-1.06-.35-2.36-.47-3.42-.47-4.72-1.89-6.61-5.19-9.92-1.18-.59-2.48-1.77-3.66-2.48-2.13-1.3-7.79-3.54-8.5-5.79-.12-.47,0-.59.24-1.06,1.18-.24,2.36-.12,3.54,0,3.42.59,13.81,2.6,16.53,3.78.71.24,1.06.83,1.53,1.42h0c2.01,0,12.4-7.56,17.12-5.31.47.59.83.83.94,1.65-2.95,4.25-18.54,3.42-20.31,19.01.24,2.36,0,4.25,2.13,6.02,3.54,2.95,11.1,5.55,15.82,5.08,1.3-.12,2.72-.59,3.54-1.65s1.18-2.83,1.06-4.13c-.24-1.65-1.53-2.48-2.83-3.42,4.49-3.66,10.27-5.43,14.17-9.8.59-9.68-2.95-18.89-3.19-28.45,3.42-2.13,5.43-6.97,7.67-10.39-.94,4.72-4.49,8.97-4.37,13.93,0,1.3.71,3.54,1.65,4.37.24.24.59.12.94.12,1.42-1.06,2.48-2.83,3.31-4.37,1.3-2.36,8.38-17.71,8.62-19.6.35-2.36-.35-4.6-1.06-6.85-5.19-12.28-14.05-23.73-26.45-29.16,11.45-.47,20.19,2.13,30.7,6.49,3.19,1.53,6.38,3.31,9.56,4.6.83-.83,1.3-1.3,1.3-2.48.24-4.84-8.62-10.86-7.91-14.4.12-.83.83-1.53,1.3-2.13,1.77-1.77,4.25-2.72,6.73-2.6h1.06c.35,0,.71,0,1.06.24.35,0,.71.12,1.06.24.35,0,.71.24,1.06.35.35.12.71.35.94.47.35.12.59.35.94.59.24.24.59.47.83.71s.59.47.83.71c2.6,2.72,2.83,6.49,2.83,10.04,0,3.19-.83,7.91-3.31,10.15-1.06.94-2.24,1.3-3.66,1.18-1.89-.12-3.78-.71-5.67-1.06-3.19-1.18-6.38-2.72-9.45-4.25-2.36.24-5.31,2.24-7.32,3.54,1.77,5.19,3.66,10.51,5.79,15.58,1.42,4.25,4.37,10.04,3.78,14.52-.24,2.13-1.06,4.25-1.53,6.38-.59,2.83-.83,5.79-2.13,8.5-2.36,4.96-7.91,7.32-10.39,11.45-.59,1.18-.59,2.6-.47,3.78.83,10.86-2.24,12.4-10.27,18.65-.12,4.25-1.77,8.62-1.53,12.75.24,3.31,1.53,6.49,2.24,9.8,1.3,7.2,1.65,16.17-.47,23.14-.94,3.07-2.36,5.9-3.42,8.85,8.26-8.5,10.51-16.06,10.27-27.63,0-4.25-.59-8.38-1.53-12.51-.71-2.95-3.54-7.08-3.31-10.04,7.79,15.7,10.86,27.51,6.14,44.86,8.26-4.49,14.17-10.86,19.24-18.65,5.08-7.56,6.85-15.7,5.08-24.68,0-.59-.24-1.18-.35-1.77-.12-.59-.35-1.18-.47-1.77s-.35-1.18-.59-1.65c-.24-.59-.47-1.06-.59-1.65-.24-.59-.47-1.06-.71-1.65s-.47-1.06-.83-1.65c-.24-.47-.59-1.06-.83-1.53-.35-.47-.59-1.06-.94-1.53s-.71-.94-1.06-1.42c-.35-.47-.71-.94-1.06-1.42-.35-.47-.71-.94-1.18-1.3-.35-.47-.83-.83-1.18-1.3-.47-.47-.83-.83-1.3-1.18-.47-.35-.94-.83-1.3-1.18-.47-.35-.94-.71-1.42-1.06s-.94-.71-1.42-1.06c1.06.24,2.24.59,3.31.94,6.49,2.24,11.81,9.33,14.88,15.47,5.9,11.92,4.37,19.24.35,31.29,2.6-2.83,4.84-6.26,6.38-9.8,6.38-14.17,4.37-26.68-4.49-39.2-1.65-2.36-4.6-4.84-5.55-7.32l.35-.35c.35,0,.83.24,1.18.35,2.48,1.06,4.96,2.95,7.08,4.72,8.15,6.85,10.04,13.46,13.81,22.9.83-2.13,1.53-4.25,1.77-6.49,1.65-17.36-14.88-29.52-24.68-41.44,11.22,4.84,21.37,13.34,26.92,24.32,1.18,2.48,2.72,7.44,4.96,9.09.24-2.24,0-4.84-.12-7.08-1.53-18.3-14.29-31.64-24.44-45.93,7.08,5.55,13.93,9.56,19.72,16.77,2.48,3.07,4.01,6.61,6.49,9.68h.47c.59-1.53-.71-5.08-1.18-6.61l-.83.71ZM32.69,55.84v.24-.24ZM32.57,56.2v.24-.24ZM32.45,56.43s0,.12-.12.24c0,0,.12-.12.12-.24ZM83.57,73.79c-5.79,8.62-2.13,10.15-3.78,18.89-.24,1.3-.94,3.54-2.01,4.25v-.94c.59-1.77.94-3.9.83-5.67-.24-3.07-1.06-6.14-2.01-9.09-4.13-12.04-10.51-6.49-15.11-10.74-.35-.35-.35-.47-.35-.94,1.42-1.89,5.79-1.18,7.91-1.89,1.89-.59,3.9-1.89,5.67-2.72.35.12,1.18.47,1.42.71.59.59,3.9,6.49,5.19,8.26.59-1.06,1.06-2.36,1.77-3.42.47-.83,1.06-1.18,1.89-1.42l.59.35c0,1.53-1.06,3.07-1.89,4.25l-.12.12ZM103.76,66.12c0-.12,0-.24-.12-.35-1.42-4.25-2.36-16.53-.35-20.54l.71.47c0,.35.24.71.24.94.35,2.72.59,18.06-.47,19.48ZM125.72,121.25c-2.36,2.24-6.14,2.6-9.21,2.48-.35,0-.59-.35-.83-.47,0-1.42,1.3-2.83,2.01-4.01,2.72-.71,5.55-1.06,8.38-1.77.47,0,.59,0,.94.24v.71c0,1.06-.71,2.13-1.42,2.83h.12ZM127.73,68.95c1.06.12,2.36-.47,3.42-.59.71-.83,1.06-1.06,1.3-2.13-.71-2.24-2.6-3.78-3.66-5.9,2.01,1.18,5.79,2.95,6.38,5.31.12.59.12,1.18-.12,1.65-.35.59-1.18,1.18-1.77,1.65-3.66,2.72-8.26,2.83-11.57,5.31-2.13,1.53-2.95,7.79-6.26,11.33.71,2.13,1.3,4.25,2.13,6.26,1.65,3.66,7.67,16.65,6.49,20.07-.71.59-.83.71-1.65.83-1.06-1.18-.59-4.13-.94-5.67-1.06-4.49-4.25-10.04-4.96-14.17-.47-1.65-1.06-3.31-1.42-4.96-.71-3.31.47-5.31,2.24-8.03-.47-3.66-.94-8.03,1.53-11.1,2.83-3.54,5.9-.35,8.97,0l-.12.12Z" fill="<?php echo esc_attr( $lion_color ); ?>"/>
						<path d="M97.27,139.79c-6.02,2.6-14.17,5.67-18.89,10.27,0,4.49,0,8.74,1.53,12.99,1.06,3.19,2.83,6.02,4.84,8.85,7.2,10.63,9.8,14.99,6.38,28.1,4.37-4.84,6.61-10.15,6.26-16.77,0-2.01-.59-3.9-1.06-5.9l-.24-.83c.94.24,2.95,3.9,3.54,4.96,2.13,3.54,2.36,6.73,1.65,10.74,9.68-6.26,17.71-12.04,20.31-24.08,1.3-6.02,1.3-15.11-1.18-20.9-2.6-.94-21.61-7.32-23.14-7.32v-.12Z" fill="<?php echo esc_attr( $lion_color ); ?>"/>
					</g>
				</svg>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render Guide Pratique layout
 * Layout avec table des matières et sections avec ancres pour navigation
 *
 * @param array $layout Layout data
 * @return void
 */
function lunivers_render_layout_guide_pratique( $layout ) {
	$titre      = $layout['titre'] ?? '';
	$sous_titre = $layout['sous_titre'] ?? '';
	$sections   = $layout['sections'] ?? [];
	$bg_color   = $layout['couleur_fond'] ?? 'white';
	$show_toc   = $layout['afficher_table_matiere'] ?? true;
	$espacement = $layout['espacement'] ?? 'top';

	if ( empty( $sections ) || ! is_array( $sections ) ) {
		return;
	}

	$colors        = lunivers_get_layout_colors( $bg_color );
	$spacing_class = lunivers_get_spacing_classes( $espacement );

	// Générer les IDs uniques pour chaque section
	$sections_with_ids = [];
	foreach ( $sections as $index => $section ) {
		$titre_section = $section['titre'] ?? '';
		$section_id     = sanitize_title( $titre_section );

		// Si l'ID est vide ou existe déjà, utiliser l'index
		if ( empty( $section_id ) ) {
			$section_id = 'section-' . ( $index + 1 );
		} else {
			// Vérifier si l'ID existe déjà
			$original_id = $section_id;
			$counter     = 1;
			foreach ( $sections_with_ids as $existing ) {
				if ( $existing['id'] === $section_id ) {
					$section_id = $original_id . '-' . $counter;
					$counter++;
				}
			}
		}

		$sections_with_ids[] = array_merge( $section, [ 'id' => $section_id ] );
	}
	?>
	<section class="layout-guide-pratique <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>" data-guide-pratique>
		<div class="container mx-auto px-4">
			<div class="">
				<?php if ( $sous_titre || $titre ) : ?>
					<header class="text-center mb-12 md:mb-16">
						<?php if ( $sous_titre ) : ?>
							<span class="inline-block px-4 py-2 mb-2 text-xs tracking-wider text-primary-400 uppercase bg-primary-500/10 rounded-full border border-primary-500/20 mb-4">
								<?php echo esc_html( $sous_titre ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $titre ) : ?>
							<h2 class="text-3xl md:text-4xl lg:text-5xl <?php echo esc_attr( $colors['text'] ); ?>">
								<?php echo esc_html( $titre ); ?>
							</h2>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
					<?php if ( $show_toc && count( $sections_with_ids ) > 1 ) : ?>
						<!-- Table des matières (sticky sur desktop) -->
						<aside class="lg:col-span-3 order-2 lg:order-1">
							<nav 
								class="sticky top-24 bg-white rounded-lg shadow-md p-6 border border-neutral-200"
								aria-label="<?php esc_attr_e( 'Table des matières', 'lunivers-theme' ); ?>"
								data-toc-nav
							>
								<h3 class="text-lg <?php echo esc_attr( $colors['text'] ); ?> mb-4">
									<?php esc_html_e( 'Sommaire', 'lunivers-theme' ); ?>
								</h3>
								<ul class="space-y-2">
									<?php foreach ( $sections_with_ids as $section ) : ?>
										<?php
										$titre_section = $section['titre'] ?? '';
										$section_id   = $section['id'] ?? '';
										?>
										<?php if ( $titre_section && $section_id ) : ?>
											<li>
												<a 
													href="#<?php echo esc_attr( $section_id ); ?>"
													class="block text-sm <?php echo esc_attr( $colors['text_secondary'] ); ?> hover:text-primary-500 transition-colors py-1"
													data-toc-link
												>
													<?php echo esc_html( $titre_section ); ?>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</nav>
						</aside>
					<?php endif; ?>

					<!-- Contenu des sections -->
					<div class="<?php echo $show_toc && count( $sections_with_ids ) > 1 ? 'lg:col-span-9' : 'lg:col-span-12'; ?> order-1 lg:order-2">
						<div class="space-y-12 md:space-y-16">
							<?php foreach ( $sections_with_ids as $index => $section ) : ?>
								<?php
								$titre_section      = $section['titre'] ?? '';
								$contenu            = $section['contenu'] ?? '';
								$image              = $section['image'] ?? '';
								$icone              = $section['icone'] ?? '';
								$section_id         = $section['id'] ?? '';
								$position           = $section['position_image'] ?? 'top'; // top, left, right, none
								$section_format     = $section['format_image'] ?? 'paysage';
								$section_aspect     = lunivers_get_aspect_ratio_class( $section_format );
								?>
								
								<article 
									id="<?php echo esc_attr( $section_id ); ?>"
									class="scroll-mt-24 md:scroll-mt-32"
									data-section
								>
									<?php if ( $titre_section ) : ?>
										<header class="mb-6 md:mb-8">
											<?php if ( $icone ) : ?>
												<div class="flex items-center gap-4 mb-4">
													<div class="text-3xl text-primary-500 flex-shrink-0">
														<?php echo wp_kses_post( $icone ); ?>
													</div>
													<h3 class="text-2xl md:text-3xl lg:text-4xl <?php echo esc_attr( $colors['text'] ); ?>">
														<?php echo esc_html( $titre_section ); ?>
													</h3>
												</div>
											<?php else : ?>
												<h3 class="text-2xl md:text-3xl lg:text-4xl <?php echo esc_attr( $colors['text'] ); ?> mb-4">
													<?php echo esc_html( $titre_section ); ?>
												</h3>
											<?php endif; ?>
										</header>
									<?php endif; ?>

									<?php if ( $contenu ) : ?>
										<?php if ( $image && $position !== 'none' ) : ?>
											<?php
											$image_url    = $image['url'] ?? '';
											$image_alt    = $image['alt'] ?? $titre_section;
											$image_width  = $image['width'] ?? '';
											$image_height = $image['height'] ?? '';
											$placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
											?>

											<?php if ( $position === 'top' ) : ?>
												<!-- Image en haut -->
												<div class="mb-6 md:mb-8">
													<div class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-500">
														<div class="<?php echo esc_attr( $section_aspect ); ?> bg-neutral-100">
															<img
																data-src="<?php echo esc_url( $image_url ); ?>"
																src="<?php echo esc_attr( $placeholder ); ?>"
																alt="<?php echo esc_attr( $image_alt ); ?>"
																width="<?php echo esc_attr( $image_width ); ?>"
																height="<?php echo esc_attr( $image_height ); ?>"
																class="lazy w-full h-full object-cover"
																loading="lazy"
															>
														</div>
													</div>
												</div>
												<div class="max-w-none <?php echo esc_attr( $colors['text_secondary'] ); ?>">
													<?php echo wp_kses_post( $contenu ); ?>
												</div>
											<?php elseif ( $position === 'left' || $position === 'right' ) : ?>
												<!-- Image à gauche ou droite -->
												<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-start">
													<?php if ( $position === 'left' ) : ?>
														<div class="order-1">
															<div class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-500">
																<div class="<?php echo esc_attr( $section_aspect ); ?> bg-neutral-100">
																	<img
																		data-src="<?php echo esc_url( $image_url ); ?>"
																		src="<?php echo esc_attr( $placeholder ); ?>"
																		alt="<?php echo esc_attr( $image_alt ); ?>"
																		width="<?php echo esc_attr( $image_width ); ?>"
																		height="<?php echo esc_attr( $image_height ); ?>"
																		class="lazy w-full h-full object-cover"
																		loading="lazy"
																	>
																</div>
															</div>
														</div>
														<div class="order-2 <?php echo esc_attr( $colors['text_secondary'] ); ?>">
															<?php echo wp_kses_post( $contenu ); ?>
														</div>
													<?php else : ?>
														<div class="order-2 md:order-1 <?php echo esc_attr( $colors['text_secondary'] ); ?>">
															<?php echo wp_kses_post( $contenu ); ?>
														</div>
														<div class="order-1 md:order-2">
															<div class="overflow-hidden rounded-lg shadow-lg">
																<div class="<?php echo esc_attr( $section_aspect ); ?> bg-neutral-100">
																	<img
																		data-src="<?php echo esc_url( $image_url ); ?>"
																		src="<?php echo esc_attr( $placeholder ); ?>"
																		alt="<?php echo esc_attr( $image_alt ); ?>"
																		width="<?php echo esc_attr( $image_width ); ?>"
																		height="<?php echo esc_attr( $image_height ); ?>"
																		class="lazy w-full h-full object-cover"
																		loading="lazy"
																	>
																</div>
															</div>
														</div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										<?php else : ?>
											<!-- Pas d'image -->
											<div class="<?php echo esc_attr( $colors['text_secondary'] ); ?>">
												<?php echo wp_kses_post( $contenu ); ?>
											</div>
										<?php endif; ?>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

