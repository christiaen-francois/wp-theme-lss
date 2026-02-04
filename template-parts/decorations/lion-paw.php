<?php
/**
 * Lion Paw Decoration Partial
 *
 * Renders lion paw SVG decorations with parallax effect
 *
 * @param array $args {
 *     @type bool   $enabled        Whether paws are enabled
 *     @type string $image_position Position of the image (left/right) for auto calculation
 *     @type array  $paw_1          Paw 1 configuration (position, taille, couleur)
 *     @type bool   $paw_2_enabled  Whether to show a second paw
 *     @type array  $paw_2          Paw 2 configuration (position, taille, couleur)
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enabled        = $args['enabled'] ?? false;
$image_position = $args['image_position'] ?? 'right';
$paw_1          = $args['paw_1'] ?? [];
$paw_2_enabled  = $args['paw_2_enabled'] ?? false;
$paw_2          = $args['paw_2'] ?? [];

if ( ! $enabled ) {
	return;
}

/**
 * Get position classes for a paw
 */
function lunivers_get_paw_position_classes( string $position, string $image_position ): string {
	// Auto position: place paw on opposite side of image
	if ( $position === 'auto' ) {
		$position = $image_position === 'left' ? 'top-right' : 'top-left';
	}

	$position_map = [
		'top-left'     => 'top-4 md:top-8 left-4 md:left-8 -rotate-12',
		'top-right'    => 'top-4 md:top-8 right-4 md:right-8 rotate-12',
		'bottom-left'  => 'bottom-4 md:bottom-8 left-4 md:left-8 rotate-12',
		'bottom-right' => 'bottom-4 md:bottom-8 right-4 md:right-8 -rotate-12',
	];

	return $position_map[ $position ] ?? $position_map['top-left'];
}

/**
 * Get size classes for a paw
 */
function lunivers_get_paw_size_classes( string $size ): string {
	$size_map = [
		'sm' => 'w-12 h-14 md:w-16 md:h-20',
		'md' => 'w-16 h-20 md:w-24 md:h-28',
		'lg' => 'w-24 h-28 md:w-32 md:h-40',
	];

	return $size_map[ $size ] ?? $size_map['md'];
}

/**
 * Get color classes for a paw
 */
function lunivers_get_paw_color_classes( string $color ): string {
	$color_map = [
		'primary' => 'text-primary-500/20',
		'neutral' => 'text-neutral-400/15',
	];

	return $color_map[ $color ] ?? $color_map['primary'];
}

/**
 * Render a single paw
 */
function lunivers_render_paw( array $paw_config, string $image_position, int $index ): void {
	$position = $paw_config['position'] ?? 'auto';
	$size     = $paw_config['taille'] ?? 'md';
	$color    = $paw_config['couleur'] ?? 'primary';

	$position_classes = lunivers_get_paw_position_classes( $position, $image_position );
	$size_classes     = lunivers_get_paw_size_classes( $size );
	$color_classes    = lunivers_get_paw_color_classes( $color );
	?>
	<div
		class="lion-paw-decoration absolute pointer-events-none z-0 <?php echo esc_attr( $position_classes ); ?> <?php echo esc_attr( $size_classes ); ?> <?php echo esc_attr( $color_classes ); ?>"
		data-paw-parallax
		data-paw-index="<?php echo esc_attr( $index ); ?>"
		aria-hidden="true"
	>
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120" fill="currentColor" class="w-full h-full">
			<path d="M50 45c-18 0-32 14-32 32 0 22 14 38 32 38s32-16 32-38c0-18-14-32-32-32z"/>
			<ellipse cx="20" cy="22" rx="12" ry="15" transform="rotate(-15 20 22)"/>
			<ellipse cx="38" cy="12" rx="11" ry="14" transform="rotate(-5 38 12)"/>
			<ellipse cx="62" cy="12" rx="11" ry="14" transform="rotate(5 62 12)"/>
			<ellipse cx="80" cy="22" rx="12" ry="15" transform="rotate(15 80 22)"/>
		</svg>
	</div>
	<?php
}

// Render paw 1
if ( ! empty( $paw_1 ) ) {
	lunivers_render_paw( $paw_1, $image_position, 1 );
}

// Render paw 2 if enabled
if ( $paw_2_enabled && ! empty( $paw_2 ) ) {
	lunivers_render_paw( $paw_2, $image_position, 2 );
}
