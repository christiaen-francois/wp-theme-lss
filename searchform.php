<?php
/**
 * Template for displaying search form
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="search-field">
		<span><?php esc_html_e( 'Rechercher pour:', 'lunivers-theme' ); ?></span>
	</label>
	<div class="flex gap-2">
		<input
			type="search"
			id="search-field"
			class="search-field flex-1 px-4 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
			placeholder="<?php esc_attr_e( 'Rechercher...', 'lunivers-theme' ); ?>"
			value="<?php echo get_search_query(); ?>"
			name="s"
		/>
		<button
			type="submit"
			class="search-submit px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors"
		>
			<?php esc_html_e( 'Rechercher', 'lunivers-theme' ); ?>
		</button>
	</div>
</form>

