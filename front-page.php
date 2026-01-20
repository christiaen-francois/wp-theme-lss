<?php
/**
 * The front page template
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
	// Hero Section
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			
			// ACF Fields
			$acf_surtitle   = function_exists( 'get_field' ) ? get_field( 'surtitle' ) : '';
			$acf_title      = function_exists( 'get_field' ) ? get_field( 'title' ) : '';
			$acf_description = function_exists( 'get_field' ) ? get_field( 'description' ) : '';
			$acf_alignment  = function_exists( 'get_field' ) ? get_field( 'content_alignment' ) : 'center';
			$acf_links      = function_exists( 'get_field' ) ? get_field( 'links' ) : [];
			$acf_slider     = function_exists( 'get_field' ) ? get_field( 'slider' ) : '';

			// Use ACF title if available, otherwise use post title
			$hero_title = $acf_title ? $acf_title : get_the_title();

			// Alignment classes
			$alignment_classes = $acf_alignment === 'left' ? 'text-left' : 'text-center';
			$container_classes = $acf_alignment === 'left' ? '' : 'mx-auto';
			?>
			<section class="hero-section relative overflow-hidden">
				<?php
				// Slider/Carousel
				if ( $acf_slider && is_array( $acf_slider ) && ! empty( $acf_slider ) ) {
					?>
					<div class="hero-slider swiper relative h-[60vh] md:h-[80vh]">
						<div class="swiper-wrapper">
							<?php foreach ( $acf_slider as $slide ) : ?>
								<?php
								$slide_url = $slide['url'] ?? '';
								$slide_alt = $slide['alt'] ?? '';
								?>
								<div class="swiper-slide relative overflow-hidden">
									<img
										src="<?php echo esc_url( $slide_url ); ?>"
										alt="<?php echo esc_attr( $slide_alt ); ?>"
										class="parallax-bg w-full h-full object-cover"
										loading="lazy"
									>
									<div class="absolute inset-0 bg-brown-950/30 pointer-events-none"></div>
									<div class="absolute inset-0 z-10 container mx-auto px-4 flex items-center <?php echo $acf_alignment === 'left' ? 'justify-start' : 'justify-center'; ?>">
										<div class="max-w-6xl <?php echo esc_attr( $alignment_classes ); ?> <?php echo esc_attr( $container_classes ); ?> text-white">
											<?php if ( $acf_surtitle ) : ?>
												<p class="inline-block px-4 py-2 mb-4 text-sm tracking-wider text-white uppercase bg-white/15 backdrop-blur-sm rounded-full border border-white/30 drop-shadow-md">
													<?php echo esc_html( $acf_surtitle ); ?>
												</p>
											<?php endif; ?>

											<h1 class="text-5xl md:text-6xl lg:text-7xl mb-6 leading-tight drop-shadow-lg">
												<?php echo esc_html( $hero_title ); ?>
											</h1>

											<?php if ( $acf_description ) : ?>
												<div class="mb-8 max-w-3xl <?php echo $acf_alignment === 'center' ? 'mx-auto' : ''; ?> leading-relaxed drop-shadow-md">
													<?php echo wp_kses_post( $acf_description ); ?>
												</div>
											<?php endif; ?>

											<?php if ( $acf_links && is_array( $acf_links ) && ! empty( $acf_links ) ) : ?>
												<div class="mt-10 flex flex-wrap gap-4 <?php echo $acf_alignment === 'center' ? 'justify-center' : ''; ?>">
													<?php foreach ( $acf_links as $link_item ) :
														$link = $link_item['link'] ?? [];
														$style = $link_item['style'] ?? 'primary';
														if ( empty( $link['url'] ) ) continue;

														$btn_classes = $style === 'primary'
															? 'bg-primary-500 text-white hover:bg-primary-600 shadow-lg'
															: 'bg-transparent border-2 border-white text-white hover:bg-white hover:text-brown-950';
													?>
														<a
															href="<?php echo esc_url( $link['url'] ); ?>"
															target="<?php echo esc_attr( $link['target'] ?? '_self' ); ?>"
															class="inline-flex items-center gap-2 px-8 py-4 font-heading font-semibold tracking-wide rounded-lg transition-all duration-300 transform group <?php echo esc_attr( $btn_classes ); ?>"
														>
															<?php echo esc_html( $link['title'] ?? 'En savoir plus' ); ?>
															<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
															</svg>
														</a>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="swiper-pagination"></div>
						<div class="swiper-button-next text-white"></div>
						<div class="swiper-button-prev text-white"></div>
					</div>
					<?php
				} else {
					// Hero without slider
					?>
					<div class="container mx-auto px-4 py-24 md:py-32">
						<div class="max-w-4xl <?php echo esc_attr( $alignment_classes ); ?> <?php echo esc_attr( $container_classes ); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								?>
								<div class="mb-12 <?php echo $acf_alignment === 'center' ? '' : ''; ?>">
									<?php the_post_thumbnail( 'large', [ 'class' => ( $acf_alignment === 'center' ? 'mx-auto ' : '' ) . 'rounded-lg shadow-2xl max-w-2xl' ] ); ?>
								</div>
								<?php
							}
							?>

							<?php if ( $acf_surtitle ) : ?>
								<p class="text-lg md:text-xl font-medium uppercase tracking-widest text-primary-500 mb-4">
									<?php echo esc_html( $acf_surtitle ); ?>
								</p>
							<?php endif; ?>

							<h1 class="text-5xl md:text-6xl lg:text-7xl text-brown-950 mb-6 leading-tight">
								<?php echo esc_html( $hero_title ); ?>
							</h1>

							<?php if ( $acf_description ) : ?>
								<div class="text-xl md:text-2xl text-brown-700 mb-8 max-w-3xl <?php echo $acf_alignment === 'center' ? 'mx-auto' : ''; ?> leading-relaxed">
									<?php echo wp_kses_post( $acf_description ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $acf_links && is_array( $acf_links ) && ! empty( $acf_links ) ) : ?>
								<div class="mt-10 flex flex-wrap gap-4 <?php echo $acf_alignment === 'center' ? 'justify-center' : ''; ?>">
									<?php foreach ( $acf_links as $link_item ) :
										$link = $link_item['link'] ?? [];
										$style = $link_item['style'] ?? 'primary';
										if ( empty( $link['url'] ) ) continue;

										$btn_classes = $style === 'primary'
											? 'bg-primary-500 text-white hover:bg-primary-600 shadow-lg'
											: 'bg-transparent border-2 border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white';
									?>
										<a
											href="<?php echo esc_url( $link['url'] ); ?>"
											target="<?php echo esc_attr( $link['target'] ?? '_self' ); ?>"
											class="inline-flex items-center gap-2 px-8 py-4 font-heading font-semibold tracking-wide rounded-lg transition-all duration-300 transform group <?php echo esc_attr( $btn_classes ); ?>"
										>
											<?php echo esc_html( $link['title'] ?? 'En savoir plus' ); ?>
											<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
											</svg>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php
				}
				?>
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
					<section class="content-section py-16 md:py-24">
						<div class="container mx-auto px-4">
							<div class="max-w-4xl mx-auto">
								<div class="prose prose-lg prose-brown max-w-none">
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					</section>
					<?php
				}
			} elseif ( get_the_content() ) {
				// Fallback if ACF is not active
				?>
				<section class="content-section py-16 md:py-24">
					<div class="container mx-auto px-4">
						<div class="max-w-4xl mx-auto">
							<div class="prose prose-lg prose-brown max-w-none">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
				</section>
				<?php
			}
		}
	} else {
		// Default content if no page is set as front page
		?>
		<section class="hero-section relative overflow-hidden">
			<div class="container mx-auto px-4 py-24 md:py-32">
				<div class="max-w-4xl mx-auto text-center">
					<h1 class="text-5xl md:text-6xl lg:text-7xl text-brown-950 mb-6 leading-tight">
						<?php esc_html_e( 'Bienvenue chez Lion Select Safaris', 'lunivers-theme' ); ?>
					</h1>
					
					<p class="text-xl md:text-2xl text-brown-700 mb-8 max-w-3xl mx-auto leading-relaxed">
						<?php esc_html_e( 'Découvrez l\'aventure africaine avec nos safaris d\'exception', 'lunivers-theme' ); ?>
					</p>

					<div class="mt-10">
						<a
							href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: '#' ); ?>"
							class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white font-heading font-semibold tracking-wide rounded-lg shadow-lg hover:bg-primary-600 transition-all duration-300 transform group"
						>
							<?php esc_html_e( 'Contactez-nous', 'lunivers-theme' ); ?>
							<svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
							</svg>
						</a>
					</div>
				</div>
			</div>
		</section>

		<?php
		// Features Section
		?>
		<section class="features-section py-16 md:py-24 bg-white/50">
			<div class="container mx-auto px-4">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
					<div class="text-center">
						<div class="w-16 h-16 bg-primary-400 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<h3 class="text-2xl text-brown-950 mb-3">
							<?php esc_html_e( 'Safaris Authentiques', 'lunivers-theme' ); ?>
						</h3>
						<p class="text-brown-700">
							<?php esc_html_e( 'Vivez une expérience unique au cœur de la nature africaine', 'lunivers-theme' ); ?>
						</p>
					</div>

					<div class="text-center">
						<div class="w-16 h-16 bg-primary-400 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<h3 class="text-2xl text-brown-950 mb-3">
							<?php esc_html_e( 'Expertise Locale', 'lunivers-theme' ); ?>
						</h3>
						<p class="text-brown-700">
							<?php esc_html_e( 'Des guides expérimentés pour une aventure en toute sécurité', 'lunivers-theme' ); ?>
						</p>
					</div>

					<div class="text-center">
						<div class="w-16 h-16 bg-primary-400 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
							</svg>
						</div>
						<h3 class="text-2xl text-brown-950 mb-3">
							<?php esc_html_e( 'Luxe & Confort', 'lunivers-theme' ); ?>
						</h3>
						<p class="text-brown-700">
							<?php esc_html_e( 'Des hébergements de qualité pour un séjour mémorable', 'lunivers-theme' ); ?>
						</p>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	?>
</div>

<?php
get_footer();

