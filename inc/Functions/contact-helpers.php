<?php
/**
 * Contact Helpers
 *
 * Fonctions utilitaires pour récupérer les options de contact
 *
 * @package lunivers-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LUNIVERS_THEME\Inc\Classes\Acf;

/**
 * Récupère l'email de contact général
 *
 * @return string Email de contact ou chaîne vide
 */
function nl_get_contact_email(): string {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}

	$acf = Acf::get_instance();
	return (string) $acf->get_acf_field_option( 'contact_email' );
}

/**
 * Récupère l'adresse postale complète
 *
 * @return array Tableau avec les clés : street, zip, city, country
 */
function nl_get_contact_address(): array {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$acf = Acf::get_instance();
	$address = $acf->get_acf_field_option( 'contact_address' );

	if ( ! is_array( $address ) ) {
		return [];
	}

	return [
		'street'  => $address['street'] ?? '',
		'zip'     => $address['zip'] ?? '',
		'city'    => $address['city'] ?? '',
		'country' => $address['country'] ?? '',
	];
}

/**
 * Récupère l'adresse postale formatée sur une seule ligne
 *
 * @return string Adresse formatée
 */
function nl_get_contact_address_formatted(): string {
	$address = nl_get_contact_address();

	if ( empty( $address ) ) {
		return '';
	}

	$parts = array_filter( [
		$address['street'] ?? '',
		trim( ( $address['zip'] ?? '' ) . ' ' . ( $address['city'] ?? '' ) ),
		$address['country'] ?? '',
	] );

	return implode( ', ', $parts );
}

/**
 * Récupère la liste des personnes de contact
 *
 * @return array Tableau de tableaux avec les clés : name, phone
 */
function nl_get_contact_people(): array {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$acf = Acf::get_instance();
	$people = $acf->get_acf_field_option( 'contact_people' );

	if ( ! is_array( $people ) ) {
		return [];
	}

	return $people;
}

/**
 * Récupère la liste des réseaux sociaux
 *
 * @return array Tableau de tableaux avec les clés : type, url
 */
function nl_get_social_networks(): array {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$acf = Acf::get_instance();
	$networks = $acf->get_acf_field_option( 'social_networks' );

	if ( ! is_array( $networks ) ) {
		return [];
	}

	return $networks;
}

/**
 * Récupère l'URL d'un réseau social spécifique
 *
 * @param string $type Type de réseau social (facebook, instagram, etc.)
 * @return string URL du réseau social ou chaîne vide
 */
function nl_get_social_network_url( string $type ): string {
	$networks = nl_get_social_networks();

	foreach ( $networks as $network ) {
		if ( isset( $network['type'] ) && $network['type'] === $type && isset( $network['url'] ) ) {
			return esc_url( $network['url'] );
		}
	}

	return '';
}

/**
 * Récupère le nom du réseau social pour l'affichage
 *
 * @param string $type Type de réseau social
 * @return string Nom du réseau social
 */
function nl_get_social_network_name( string $type ): string {
	$names = [
		'facebook'  => 'Facebook',
		'twitter'   => 'Twitter',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'Youtube',
		'vimeo'     => 'Vimeo',
		'behance'   => 'Behance',
		'whatsapp'  => 'WhatsApp',
		'telegram'  => 'Telegram',
		'envelope'  => 'Email',
	];

	return $names[ $type ] ?? ucfirst( $type );
}

/**
 * Récupère les données du CTA du footer
 *
 * @return array Tableau avec les données du CTA
 */
function nl_get_footer_cta(): array {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$acf = Acf::get_instance();

	return [
		'sur_titre'   => (string) $acf->get_acf_field_option( 'footer_cta_sur_titre' ),
		'titre'       => (string) $acf->get_acf_field_option( 'footer_cta_titre' ),
		'description' => (string) $acf->get_acf_field_option( 'footer_cta_description' ),
		'lien'        => $acf->get_acf_field_option( 'footer_cta_lien' ),
		'telephone'   => (string) $acf->get_acf_field_option( 'footer_cta_telephone' ),
		'image'       => $acf->get_acf_field_option( 'footer_cta_image' ),
	];
}

