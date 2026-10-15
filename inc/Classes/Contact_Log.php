<?php
/**
 * Contact Log — Journal des demandes du formulaire de contact
 *
 * Enregistre chaque demande (devis / question) comme CPT privé afin de
 * conserver un historique consultable dans l'admin : IP, navigateur, email,
 * téléphone, etc. Objectif : repérer les demandes frauduleuses répétées
 * (même IP / même téléphone / même email sous des noms différents).
 *
 * Base légale RGPD : intérêt légitime (prévention de la fraude).
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class Contact_Log {
	use Singleton;

	const POST_TYPE = 'lss_contact_log';

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', [ $this, 'set_columns' ] );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', [ $this, 'render_column' ], 10, 2 );
		add_filter( 'post_row_actions', [ $this, 'remove_row_actions' ], 10, 2 );
		add_action( 'add_meta_boxes', [ $this, 'add_detail_metabox' ] );
	}

	/**
	 * Register the (private, admin-only) CPT.
	 */
	public function register_post_type() {
		$labels = [
			'name'               => _x( 'Demandes de contact', 'Post type general name', 'lunivers-theme' ),
			'singular_name'      => _x( 'Demande', 'Post type singular name', 'lunivers-theme' ),
			'menu_name'          => _x( 'Demandes contact', 'Admin Menu text', 'lunivers-theme' ),
			'all_items'          => __( 'Toutes les demandes', 'lunivers-theme' ),
			'edit_item'          => __( 'Détail de la demande', 'lunivers-theme' ),
			'view_item'          => __( 'Voir la demande', 'lunivers-theme' ),
			'search_items'       => __( 'Rechercher une demande', 'lunivers-theme' ),
			'not_found'          => __( 'Aucune demande trouvée.', 'lunivers-theme' ),
			'not_found_in_trash' => __( 'Aucune demande dans la corbeille.', 'lunivers-theme' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'capabilities'       => [
				'create_posts' => 'do_not_allow', // Pas de création manuelle
			],
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 26,
			'menu_icon'          => 'dashicons-email-alt',
			'supports'           => [ 'title' ],
			'show_in_rest'       => false,
		];

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Store a submission as a log entry.
	 *
	 * @param array $data Clés : first_name, last_name, email, phone, request_type,
	 *                    subject, message, trip (array), newsletter (bool), ip, user_agent.
	 * @return int|\WP_Error Post ID ou erreur.
	 */
	public static function store( array $data ) {
		$first_name   = $data['first_name'] ?? '';
		$last_name    = $data['last_name'] ?? '';
		$request_type = $data['request_type'] ?? 'devis';

		$type_label = 'devis' === $request_type
			? __( 'Devis', 'lunivers-theme' )
			: __( 'Question', 'lunivers-theme' );

		$title = sprintf(
			'%s %s — %s',
			$first_name,
			$last_name,
			$type_label
		);

		$post_id = wp_insert_post( [
			'post_type'   => self::POST_TYPE,
			'post_status' => 'publish',
			'post_title'  => $title,
			// Message en contenu : recherchable dans l'admin.
			'post_content' => $data['message'] ?? '',
		], true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		$meta = [
			'_lss_first_name'   => $first_name,
			'_lss_last_name'    => $last_name,
			'_lss_email'        => $data['email'] ?? '',
			'_lss_phone'        => $data['phone'] ?? '',
			'_lss_request_type' => $request_type,
			'_lss_subject'      => $data['subject'] ?? '',
			'_lss_ip'           => $data['ip'] ?? '',
			'_lss_user_agent'   => $data['user_agent'] ?? '',
			'_lss_newsletter'   => ! empty( $data['newsletter'] ) ? '1' : '0',
			'_lss_trip'         => $data['trip'] ?? [],
		];

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		return $post_id;
	}

	/**
	 * Count how many other log entries match a given IP / phone / email.
	 * Sert à repérer les doublons (même personne, noms différents).
	 *
	 * @param string $meta_key  Clé meta (_lss_ip, _lss_phone, _lss_email).
	 * @param string $value     Valeur à comparer.
	 * @param int    $exclude   Post ID à exclure du décompte.
	 * @return int
	 */
	public static function count_matches( string $meta_key, string $value, int $exclude = 0 ): int {
		if ( '' === $value ) {
			return 0;
		}

		$query = new \WP_Query( [
			'post_type'              => self::POST_TYPE,
			'post_status'            => 'publish',
			'posts_per_page'         => 50,
			'fields'                 => 'ids',
			'post__not_in'           => $exclude ? [ $exclude ] : [],
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => [
				[
					'key'   => $meta_key,
					'value' => $value,
				],
			],
		] );

		return (int) $query->post_count;
	}

	/**
	 * Resolve the real client IP, être prudent avec les headers proxy (spoofables).
	 * Ordre : Cloudflare > X-Forwarded-For (1er) > X-Real-IP > REMOTE_ADDR.
	 *
	 * @return string IP validée, ou '' si introuvable.
	 */
	public static function get_client_ip(): string {
		$candidates = [];

		if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$candidates[] = wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] );
		}
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// XFF peut contenir une liste « client, proxy1, proxy2 » : prendre le 1er.
			$parts        = explode( ',', wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
			$candidates[] = trim( $parts[0] );
		}
		if ( ! empty( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			$candidates[] = wp_unslash( $_SERVER['HTTP_X_REAL_IP'] );
		}
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$candidates[] = wp_unslash( $_SERVER['REMOTE_ADDR'] );
		}

		foreach ( $candidates as $ip ) {
			$ip = trim( $ip );
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}

		return '';
	}

	/**
	 * Admin columns.
	 *
	 * @param array $columns
	 * @return array
	 */
	public function set_columns( array $columns ): array {
		return [
			'cb'            => $columns['cb'] ?? '<input type="checkbox" />',
			'lss_name'      => __( 'Nom', 'lunivers-theme' ),
			'lss_type'      => __( 'Type', 'lunivers-theme' ),
			'lss_email'     => __( 'Email', 'lunivers-theme' ),
			'lss_phone'     => __( 'Téléphone', 'lunivers-theme' ),
			'lss_ip'        => __( 'IP', 'lunivers-theme' ),
			'lss_duplicate' => __( 'Doublons ?', 'lunivers-theme' ),
			'date'          => __( 'Date', 'lunivers-theme' ),
		];
	}

	/**
	 * Render a custom column cell.
	 *
	 * @param string $column
	 * @param int    $post_id
	 */
	public function render_column( string $column, int $post_id ): void {
		switch ( $column ) {
			case 'lss_name':
				$first = get_post_meta( $post_id, '_lss_first_name', true );
				$last  = get_post_meta( $post_id, '_lss_last_name', true );
				echo esc_html( trim( $first . ' ' . $last ) );
				break;

			case 'lss_type':
				$type = get_post_meta( $post_id, '_lss_request_type', true );
				if ( 'devis' === $type ) {
					echo '<span style="display:inline-block;padding:2px 8px;border-radius:4px;background:#fcbb46;color:#1a1a1a;font-weight:600;">' . esc_html__( 'Devis', 'lunivers-theme' ) . '</span>';
				} else {
					echo '<span style="display:inline-block;padding:2px 8px;border-radius:4px;background:#e5e5e5;color:#1a1a1a;">' . esc_html__( 'Question', 'lunivers-theme' ) . '</span>';
				}
				break;

			case 'lss_email':
				$email = get_post_meta( $post_id, '_lss_email', true );
				if ( $email ) {
					printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
				}
				break;

			case 'lss_phone':
				echo esc_html( get_post_meta( $post_id, '_lss_phone', true ) );
				break;

			case 'lss_ip':
				$ip = get_post_meta( $post_id, '_lss_ip', true );
				echo $ip ? '<code>' . esc_html( $ip ) . '</code>' : '—';
				break;

			case 'lss_duplicate':
				$this->render_duplicate_badge( $post_id );
				break;
		}
	}

	/**
	 * Affiche un badge si l'IP, le téléphone ou l'email a déjà été vu ailleurs.
	 *
	 * @param int $post_id
	 */
	private function render_duplicate_badge( int $post_id ): void {
		$ip    = get_post_meta( $post_id, '_lss_ip', true );
		$phone = get_post_meta( $post_id, '_lss_phone', true );
		$email = get_post_meta( $post_id, '_lss_email', true );

		$hits = [];

		$ip_matches = self::count_matches( '_lss_ip', $ip, $post_id );
		if ( $ip_matches > 0 ) {
			/* translators: %d: number of other submissions */
			$hits[] = sprintf( _n( '%d même IP', '%d même IP', $ip_matches, 'lunivers-theme' ), $ip_matches );
		}

		$phone_matches = self::count_matches( '_lss_phone', $phone, $post_id );
		if ( $phone_matches > 0 ) {
			/* translators: %d: number of other submissions */
			$hits[] = sprintf( _n( '%d même tél.', '%d même tél.', $phone_matches, 'lunivers-theme' ), $phone_matches );
		}

		$email_matches = self::count_matches( '_lss_email', $email, $post_id );
		if ( $email_matches > 0 ) {
			/* translators: %d: number of other submissions */
			$hits[] = sprintf( _n( '%d même email', '%d même email', $email_matches, 'lunivers-theme' ), $email_matches );
		}

		if ( empty( $hits ) ) {
			echo '—';
			return;
		}

		echo '<span style="display:inline-block;padding:2px 8px;border-radius:4px;background:#d63638;color:#fff;font-weight:600;" title="' . esc_attr__( 'Correspond à d\'autres demandes', 'lunivers-theme' ) . '">⚠ ' . esc_html( implode( ' · ', $hits ) ) . '</span>';
	}

	/**
	 * Register the read-only detail meta box.
	 */
	public function add_detail_metabox(): void {
		add_meta_box(
			'lss_contact_detail',
			__( 'Détail de la demande', 'lunivers-theme' ),
			[ $this, 'render_detail_metabox' ],
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the read-only detail of a submission.
	 *
	 * @param \WP_Post $post
	 */
	public function render_detail_metabox( $post ): void {
		$post_id = $post->ID;

		$ip    = get_post_meta( $post_id, '_lss_ip', true );
		$phone = get_post_meta( $post_id, '_lss_phone', true );
		$email = get_post_meta( $post_id, '_lss_email', true );

		$rows = [
			__( 'Prénom', 'lunivers-theme' )     => get_post_meta( $post_id, '_lss_first_name', true ),
			__( 'Nom', 'lunivers-theme' )        => get_post_meta( $post_id, '_lss_last_name', true ),
			__( 'Email', 'lunivers-theme' )      => $email,
			__( 'Téléphone', 'lunivers-theme' )  => $phone,
			__( 'Type', 'lunivers-theme' )       => 'devis' === get_post_meta( $post_id, '_lss_request_type', true ) ? __( 'Devis', 'lunivers-theme' ) : __( 'Question', 'lunivers-theme' ),
			__( 'Objet', 'lunivers-theme' )      => get_post_meta( $post_id, '_lss_subject', true ),
			__( 'Newsletter', 'lunivers-theme' ) => '1' === get_post_meta( $post_id, '_lss_newsletter', true ) ? __( 'Oui', 'lunivers-theme' ) : __( 'Non', 'lunivers-theme' ),
			__( 'Adresse IP', 'lunivers-theme' ) => $ip,
			__( 'Navigateur', 'lunivers-theme' ) => get_post_meta( $post_id, '_lss_user_agent', true ),
		];

		$trip = get_post_meta( $post_id, '_lss_trip', true );
		if ( is_array( $trip ) ) {
			if ( ! empty( $trip['nights'] ) ) {
				$rows[ __( 'Durée', 'lunivers-theme' ) ] = $trip['nights'];
			}
			if ( ! empty( $trip['adults'] ) ) {
				$rows[ __( 'Adultes', 'lunivers-theme' ) ] = $trip['adults'];
			}
			if ( ! empty( $trip['children'] ) && '0' !== $trip['children'] ) {
				$rows[ __( 'Enfants', 'lunivers-theme' ) ] = $trip['children'];
			}
			if ( ! empty( $trip['zanzibar'] ) ) {
				$rows[ __( 'Zanzibar', 'lunivers-theme' ) ] = $trip['zanzibar'];
			}
		}

		echo '<table class="widefat striped" style="margin-top:8px;"><tbody>';
		foreach ( $rows as $label => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}
			echo '<tr><th style="width:160px;text-align:left;">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
		}
		echo '</tbody></table>';

		// Message complet.
		echo '<h4 style="margin:16px 0 4px;">' . esc_html__( 'Message', 'lunivers-theme' ) . '</h4>';
		echo '<div style="padding:12px;background:#f6f7f7;border:1px solid #dcdcde;white-space:pre-wrap;">' . esc_html( $post->post_content ) . '</div>';

		// Alerte doublons.
		$ip_matches    = self::count_matches( '_lss_ip', $ip, $post_id );
		$phone_matches = self::count_matches( '_lss_phone', $phone, $post_id );
		$email_matches = self::count_matches( '_lss_email', $email, $post_id );

		if ( $ip_matches || $phone_matches || $email_matches ) {
			echo '<div style="margin-top:16px;padding:12px;background:#fcf0f1;border-left:4px solid #d63638;">';
			echo '<strong>⚠ ' . esc_html__( 'Correspondances avec d\'autres demandes :', 'lunivers-theme' ) . '</strong><ul style="margin:8px 0 0 20px;list-style:disc;">';
			if ( $ip_matches ) {
				/* translators: %d: count */
				echo '<li>' . esc_html( sprintf( _n( '%d autre demande depuis la même IP', '%d autres demandes depuis la même IP', $ip_matches, 'lunivers-theme' ), $ip_matches ) ) . '</li>';
			}
			if ( $phone_matches ) {
				/* translators: %d: count */
				echo '<li>' . esc_html( sprintf( _n( '%d autre demande avec le même téléphone', '%d autres demandes avec le même téléphone', $phone_matches, 'lunivers-theme' ), $phone_matches ) ) . '</li>';
			}
			if ( $email_matches ) {
				/* translators: %d: count */
				echo '<li>' . esc_html( sprintf( _n( '%d autre demande avec le même email', '%d autres demandes avec le même email', $email_matches, 'lunivers-theme' ), $email_matches ) ) . '</li>';
			}
			echo '</ul></div>';
		}
	}

	/**
	 * Remove "Quick Edit" / "Edit" (lecture seule), garder "Trash".
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
	 * @return array
	 */
	public function remove_row_actions( array $actions, $post ): array {
		if ( isset( $post->post_type ) && self::POST_TYPE === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}
}
