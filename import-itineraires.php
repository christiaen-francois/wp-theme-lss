<?php
/**
 * Script d'import des itinéraires Lion Select Safaris
 *
 * Usage: Accéder à ce fichier via l'admin WordPress ou WP-CLI
 * URL: /wp-admin/admin.php?page=import-itineraires
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    // Si accès direct, charger WordPress
    require_once dirname(__FILE__) . '/../../../../wp-load.php';
}

// Vérifier les permissions admin
if (!current_user_can('manage_options')) {
    wp_die('Accès non autorisé');
}

/**
 * Données des itinéraires à importer
 */
function lunivers_get_itineraires_data(): array
{
    return [
        // Itinéraire 1: Les mythiques parcs du nord
        [
            'title' => 'Les mythiques parcs du nord',
            'sous_titre' => 'L\'essence du safari tanzanien',
            'duree' => '7 jours',
            'highlight' => 'Parcs emblématiques',
            'destinations' => 'Tanzanie',
            'jours' => [
                [
                    'label' => 'Jour 1',
                    'titre' => 'Arrivée en Tanzanie',
                    'lieu' => 'Aéroport de Kilimanjaro → Arusha',
                    'description' => 'Accueil à l\'aéroport international du Kilimanjaro par votre guide francophone. Transfert vers votre lodge à Arusha, porte d\'entrée des safaris du nord. Briefing sur votre aventure à venir et première nuit au pied du Mont Meru.',
                ],
                [
                    'label' => 'Jour 2',
                    'titre' => 'Découverte du Tarangire',
                    'lieu' => 'Arusha → Parc National de Tarangire',
                    'description' => 'Départ matinal vers le parc national de Tarangire, célèbre pour ses baobabs millénaires et ses grandes concentrations d\'éléphants. Safari en 4x4 le long de la rivière Tarangire où s\'abreuvent de nombreux animaux. Observation des lions, léopards, girafes et plus de 500 espèces d\'oiseaux.',
                ],
                [
                    'label' => 'Jour 3',
                    'titre' => 'Lac Manyara et Karatu',
                    'lieu' => 'Tarangire → Lac Manyara → Karatu',
                    'description' => 'Safari matinal au parc du lac Manyara, réputé pour ses lions arboricoles et ses flamants roses. L\'après-midi, route vers Karatu, charmante bourgade située sur les hauts plateaux. Installation dans un lodge avec vue sur les montagnes environnantes.',
                ],
                [
                    'label' => 'Jour 4',
                    'titre' => 'Entrée au Serengeti',
                    'lieu' => 'Karatu → Centre Serengeti',
                    'description' => 'Traversée des hauts plateaux du Ngorongoro et descente vers les plaines infinies du Serengeti. Safari en cours de route avec possibilité d\'observer la Grande Migration selon la saison. Installation au cœur du parc pour deux nuits d\'immersion totale.',
                ],
                [
                    'label' => 'Jour 5',
                    'titre' => 'Journée complète au Serengeti',
                    'lieu' => 'Centre Serengeti',
                    'description' => 'Journée entière dédiée à l\'exploration du Serengeti, le plus célèbre parc d\'Afrique. Safaris à l\'aube et au crépuscule pour maximiser les observations. Guettez les Big Five, les guépards en chasse et les crocodiles du Nil dans les rivières Grumeti et Mara.',
                ],
                [
                    'label' => 'Jour 6',
                    'titre' => 'Cratère du Ngorongoro',
                    'lieu' => 'Centre Serengeti → Cratère du Ngorongoro',
                    'description' => 'Safari matinal puis route vers la merveille naturelle du Ngorongoro. Descente dans le cratère, véritable arche de Noé abritant plus de 25 000 animaux. Observation des rhinocéros noirs, espèce menacée, et des lions à crinière noire. Pique-nique au bord du lac Magadi.',
                ],
                [
                    'label' => 'Jour 7',
                    'titre' => 'Retour vers Arusha',
                    'lieu' => 'Karatu → Arusha',
                    'description' => 'Dernière matinée pour profiter des paysages des hauts plateaux. Route vers Arusha avec arrêts dans les villages Iraqw. Transfert à l\'aéroport du Kilimanjaro pour votre vol retour ou continuation vers Zanzibar pour une extension balnéaire.',
                ],
            ],
        ],

        // Itinéraire 2: Big five et farniente à Zanzibar
        [
            'title' => 'Big five et farniente à Zanzibar',
            'sous_titre' => 'Safari et plages paradisiaques',
            'duree' => '12 jours',
            'highlight' => 'Safari + Plage',
            'destinations' => 'Tanzanie, Zanzibar',
            'jours' => [
                [
                    'label' => 'Jour 1',
                    'titre' => 'Bienvenue en Tanzanie',
                    'lieu' => 'Aéroport de Kilimanjaro → Arusha',
                    'description' => 'Arrivée à l\'aéroport international du Kilimanjaro. Accueil chaleureux par votre guide et transfert vers votre lodge à Arusha. Selon l\'heure d\'arrivée, temps libre pour vous reposer ou découvrir les environs.',
                ],
                [
                    'label' => 'Jour 2',
                    'titre' => 'Safari au Tarangire',
                    'lieu' => 'Arusha → Parc National de Tarangire',
                    'description' => 'Direction le parc national de Tarangire et ses paysages de savane ponctués de baobabs géants. Ce parc abrite la plus grande concentration d\'éléphants de Tanzanie. Safari complet avec observation des prédateurs et de nombreuses espèces d\'antilopes.',
                ],
                [
                    'label' => 'Jour 3',
                    'titre' => 'Cratère du Ngorongoro',
                    'lieu' => 'Tarangire → Cratère du Ngorongoro',
                    'description' => 'Journée exceptionnelle au cratère du Ngorongoro, classé au patrimoine mondial de l\'UNESCO. Descente dans cette caldeira de 20 km de diamètre. Excellentes chances d\'observer les Big Five en une seule journée. Remontée en fin d\'après-midi vers Karatu.',
                ],
                [
                    'label' => 'Jour 4',
                    'titre' => 'Les plaines du Serengeti',
                    'lieu' => 'Karatu → Parc National du Serengeti',
                    'description' => 'Traversée de la réserve du Ngorongoro vers le mythique Serengeti. Safari en chemin avec la possibilité d\'observer les gnous et zèbres de la Grande Migration. Installation dans un camp au cœur de l\'écosystème le plus riche d\'Afrique.',
                ],
                [
                    'label' => 'Jour 5',
                    'titre' => 'Immersion au Serengeti',
                    'lieu' => 'Parc National du Serengeti',
                    'description' => 'Journée complète d\'exploration du Serengeti. Safari à l\'aube pour observer les prédateurs en action. Visite des kopjes, ces formations rocheuses où les lions aiment se reposer. En soirée, apéritif bush avec vue sur les plaines infinies.',
                ],
                [
                    'label' => 'Jour 6',
                    'titre' => 'Envol vers Zanzibar',
                    'lieu' => 'Serengeti → Zanzibar',
                    'description' => 'Safari matinal puis transfert vers la piste d\'atterrissage du Serengeti. Vol panoramique vers l\'île aux épices de Zanzibar. Accueil et transfert vers votre hôtel de charme. Première baignade dans les eaux turquoise de l\'océan Indien.',
                ],
                [
                    'label' => 'Jour 7',
                    'titre' => 'Découverte de Stone Town',
                    'lieu' => 'Zanzibar, Stone Town',
                    'description' => 'Visite guidée de Stone Town, la vieille ville classée à l\'UNESCO. Flânerie dans les ruelles labyrinthiques, découverte du marché aux épices et des portes sculptées traditionnelles. Visite de la maison de Freddie Mercury et du palais des sultans.',
                ],
                [
                    'label' => 'Jours 8 à 11',
                    'titre' => 'Détente balnéaire',
                    'lieu' => 'Zanzibar (Nord ou Sud de l\'île)',
                    'description' => 'Séjour libre dans votre resort au choix : plages de Nungwi au nord avec ses eaux cristallines, ou Paje au sud-est réputé pour le kitesurf. Activités optionnelles : plongée sur les récifs coralliens, excursion à Prison Island, croisière en dhow au coucher du soleil, visite d\'une ferme d\'épices.',
                ],
                [
                    'label' => 'Jour 12',
                    'titre' => 'Départ de Zanzibar',
                    'lieu' => 'Zanzibar → Aéroport',
                    'description' => 'Dernières heures sur l\'île selon l\'horaire de votre vol. Transfert à l\'aéroport de Zanzibar. Fin de votre aventure tanzanienne avec des souvenirs inoubliables de safari et de plages paradisiaques.',
                ],
            ],
        ],

        // Itinéraire 3: La Tanzanie hors des sentiers battus
        [
            'title' => 'La Tanzanie hors des sentiers battus',
            'sous_titre' => 'Aventure authentique et nature préservée',
            'duree' => '10 jours',
            'highlight' => 'Hors des sentiers battus',
            'destinations' => 'Tanzanie',
            'jours' => [
                [
                    'label' => 'Jour 1',
                    'titre' => 'Arrivée à Moshi',
                    'lieu' => 'Aéroport de Kilimanjaro → Moshi',
                    'description' => 'Accueil à l\'aéroport du Kilimanjaro et transfert vers Moshi, charmante ville au pied du plus haut sommet d\'Afrique. Installation dans un lodge avec vue imprenable sur le Kilimanjaro. Dîner et briefing sur votre itinéraire hors des sentiers battus.',
                ],
                [
                    'label' => 'Jour 2',
                    'titre' => 'Découverte de Moshi',
                    'lieu' => 'Moshi',
                    'description' => 'Journée d\'immersion dans la culture locale. Visite d\'une coopérative de café où vous découvrirez toutes les étapes de production du célèbre café arabica tanzanien. Balade dans les plantations de bananiers et rencontre avec les communautés Chagga.',
                ],
                [
                    'label' => 'Jour 3',
                    'titre' => 'Route vers les Usambara',
                    'lieu' => 'Moshi → Lushoto',
                    'description' => 'Route panoramique vers les montagnes Usambara, région verdoyante et préservée du tourisme de masse. Ces montagnes abritent une biodiversité exceptionnelle et des villages traditionnels. Installation à Lushoto, ancien poste colonial allemand.',
                ],
                [
                    'label' => 'Jour 4',
                    'titre' => 'Randonnées dans les Usambara',
                    'lieu' => 'Lushoto',
                    'description' => 'Journée de randonnée à travers les forêts primaires et les villages perchés. Rencontre avec les communautés Shambaa, réputées pour leur agriculture en terrasses. Visite du point de vue d\'Irente avec panorama sur les plaines du Masaï Steppe.',
                ],
                [
                    'label' => 'Jour 5',
                    'titre' => 'Parc national de Mkomazi',
                    'lieu' => 'Lushoto → Mkomazi',
                    'description' => 'Route vers le parc national de Mkomazi, l\'un des secrets les mieux gardés de Tanzanie. Ce parc sauvage et peu fréquenté abrite le projet de réintroduction des rhinocéros noirs et des lycaons. Safari d\'observation dans une nature intacte.',
                ],
                [
                    'label' => 'Jour 6',
                    'titre' => 'Safari à Mkomazi',
                    'lieu' => 'Mkomazi',
                    'description' => 'Journée complète de safari dans le parc de Mkomazi. Visite du sanctuaire des rhinocéros noirs et du centre de reproduction des lycaons. Observation des éléphants, girafes, oryx et kudus dans un cadre sauvage avec les monts Usambara en toile de fond.',
                ],
                [
                    'label' => 'Jour 7',
                    'titre' => 'Expérience Maasaï',
                    'lieu' => 'Mkomazi → Maasai Lodge',
                    'description' => 'Route vers un lodge authentique en territoire Maasaï. Installation dans votre hébergement traditionnel avec tout le confort moderne. En fin d\'après-midi, marche avec un guerrier Maasaï et découverte de la vie pastorale.',
                ],
                [
                    'label' => 'Jour 8',
                    'titre' => 'Immersion culturelle Maasaï',
                    'lieu' => 'Maasai Lodge',
                    'description' => 'Journée d\'immersion dans la culture Maasaï. Visite d\'un village traditionnel (boma), démonstration de danses et chants. Apprentissage des techniques de pistage avec les guerriers. En soirée, dîner traditionnel autour du feu sous les étoiles africaines.',
                ],
                [
                    'label' => 'Jour 9',
                    'titre' => 'Parc national d\'Arusha',
                    'lieu' => 'Maasai Lodge → Parc d\'Arusha',
                    'description' => 'Route vers le parc national d\'Arusha, joyau méconnu au pied du Mont Meru. Safari à pied accompagné d\'un ranger armé pour une expérience unique. Observation des colobes noirs et blancs, des buffles et de nombreux oiseaux autour des lacs Momella.',
                ],
                [
                    'label' => 'Jour 10',
                    'titre' => 'Fin de l\'aventure',
                    'lieu' => 'Arusha → Aéroport',
                    'description' => 'Selon l\'horaire de votre vol, temps libre à Arusha pour quelques achats de souvenirs ou dernière visite. Transfert à l\'aéroport du Kilimanjaro pour votre vol retour, ou possibilité d\'extension vers Zanzibar pour quelques jours de détente.',
                ],
            ],
        ],

        // Itinéraire 4: Entre safaris et immersion Maasai
        [
            'title' => 'Entre safaris et immersion Maasai',
            'sous_titre' => 'Rencontre avec le peuple Maasaï',
            'duree' => '8 jours',
            'highlight' => 'Immersion culturelle',
            'destinations' => 'Tanzanie',
            'jours' => [
                [
                    'label' => 'Jour 1',
                    'titre' => 'Bienvenue en Tanzanie',
                    'lieu' => 'Aéroport de Kilimanjaro → Arusha',
                    'description' => 'Arrivée à l\'aéroport international du Kilimanjaro et accueil par votre guide. Transfert vers Arusha pour votre première nuit. Briefing détaillé sur votre aventure mêlant safari et découverte culturelle Maasaï.',
                ],
                [
                    'label' => 'Jour 2',
                    'titre' => 'Les éléphants du Tarangire',
                    'lieu' => 'Arusha → Parc National de Tarangire',
                    'description' => 'Départ vers le parc national de Tarangire, réputé pour ses concentrations d\'éléphants et ses baobabs majestueux. Safari complet dans ce parc aux paysages variés. Observation des familles d\'éléphants, des lions et des léopards.',
                ],
                [
                    'label' => 'Jour 3',
                    'titre' => 'Safari intensif au Tarangire',
                    'lieu' => 'Parc National de Tarangire',
                    'description' => 'Journée complète dédiée à l\'exploration du Tarangire. Safari à l\'aube le long de la rivière où s\'abreuvent les animaux. Exploration des zones marécageuses riches en oiseaux. Retour au camp pour un déjeuner et reprise du safari en fin d\'après-midi.',
                ],
                [
                    'label' => 'Jour 4',
                    'titre' => 'Route vers le lac Natron',
                    'lieu' => 'Tarangire → Lac Natron',
                    'description' => 'Aventure vers le lac Natron, l\'un des lieux les plus spectaculaires et isolés de Tanzanie. Route à travers les territoires Maasaï avec le volcan Ol Doinyo Lengai en toile de fond. Installation dans un camp au bord du lac aux eaux rougeoyantes.',
                ],
                [
                    'label' => 'Jour 5',
                    'titre' => 'Lac Natron et flamants roses',
                    'lieu' => 'Lac Natron',
                    'description' => 'Exploration du lac Natron, unique site de reproduction des flamants roses d\'Afrique de l\'Est. Randonnée vers les gorges et cascades avec baignade possible. Visite d\'un village Maasaï authentique et échanges avec les habitants. Coucher de soleil sur le lac.',
                ],
                [
                    'label' => 'Jour 6',
                    'titre' => 'Traversée vers le Serengeti',
                    'lieu' => 'Lac Natron → Serengeti',
                    'description' => 'Traversée épique des plaines Maasaï vers le nord du Serengeti. Cette route peu empruntée offre des paysages grandioses et la chance d\'observer la Grande Migration selon la saison. Installation dans un camp mobile au cœur de l\'action.',
                ],
                [
                    'label' => 'Jour 7',
                    'titre' => 'Journée au Serengeti',
                    'lieu' => 'Serengeti',
                    'description' => 'Safari complet dans les plaines infinies du Serengeti. Observation des Big Five et des prédateurs en action. Possibilité de safari en montgolfière à l\'aube (en option). Dernière soirée africaine avec dîner bush sous les étoiles.',
                ],
                [
                    'label' => 'Jour 8',
                    'titre' => 'Retour et départ',
                    'lieu' => 'Serengeti → Kilimanjaro',
                    'description' => 'Safari matinal puis transfert vers la piste d\'atterrissage du Serengeti. Vol intérieur vers l\'aéroport du Kilimanjaro pour votre vol international, ou continuation vers Zanzibar pour une extension balnéaire bien méritée.',
                ],
            ],
        ],

        // Itinéraire 5: Les merveilles du sud
        [
            'title' => 'Les merveilles du sud',
            'sous_titre' => 'Safari exclusif dans le sud sauvage',
            'duree' => '10 jours',
            'highlight' => 'Sud sauvage',
            'destinations' => 'Tanzanie',
            'jours' => [
                [
                    'label' => 'Jour 1',
                    'titre' => 'Arrivée à Dar es Salaam',
                    'lieu' => 'Aéroport Dar es Salaam → Dar es Salaam',
                    'description' => 'Arrivée à l\'aéroport international de Dar es Salaam. Selon votre heure d\'arrivée, nuit à Dar es Salaam ou route directe vers la réserve de Selous (4h de route). La capitale économique de Tanzanie vous accueille dans son ambiance swahilie.',
                ],
                [
                    'label' => 'Jour 2',
                    'titre' => 'Direction Selous',
                    'lieu' => 'Dar es Salaam → Réserve de Selous',
                    'description' => 'Route vers la réserve de Selous (aujourd\'hui Nyerere), plus grande zone protégée d\'Afrique. Ce territoire sauvage et peu fréquenté offre une expérience de safari exclusive. Installation dans un lodge en bordure de la rivière Rufiji.',
                ],
                [
                    'label' => 'Jour 3',
                    'titre' => 'Safari à Selous',
                    'lieu' => 'Réserve de Selous',
                    'description' => 'Journée de safari dans l\'immensité de Selous. Activités variées : safari en 4x4, safari en bateau sur la rivière Rufiji peuplée d\'hippopotames et de crocodiles. Observation des lycaons, espèce rare dont Selous abrite l\'une des plus grandes populations.',
                ],
                [
                    'label' => 'Jour 4',
                    'titre' => 'Safari à pied et en bateau',
                    'lieu' => 'Réserve de Selous',
                    'description' => 'Expérience unique de safari à pied accompagné d\'un ranger armé. Découverte de la brousse au plus près : empreintes, traces et comportements animaux. L\'après-midi, croisière au coucher du soleil sur la rivière Rufiji avec apéritif à bord.',
                ],
                [
                    'label' => 'Jour 5',
                    'titre' => 'Les monts Udzungwa',
                    'lieu' => 'Selous → Monts Udzungwa',
                    'description' => 'Route vers le parc national des monts Udzungwa, trésor de biodiversité surnommé les "Galápagos africains". Ces montagnes couvertes de forêt tropicale abritent des espèces endémiques uniques. Installation dans un lodge à l\'orée de la forêt.',
                ],
                [
                    'label' => 'Jour 6',
                    'titre' => 'Randonnée à Udzungwa',
                    'lieu' => 'Monts Udzungwa',
                    'description' => 'Randonnée vers les chutes de Sanje, spectaculaire cascade de 170 mètres en pleine forêt tropicale. Observation des primates endémiques : le colobe rouge d\'Udzungwa et le singe mangabey à crête. Retour par la forêt avec ses orchidées et ses oiseaux rares.',
                ],
                [
                    'label' => 'Jour 7',
                    'titre' => 'Route vers Ruaha',
                    'lieu' => 'Monts Udzungwa → Ruaha',
                    'description' => 'Longue route vers le parc national de Ruaha, le plus grand parc de Tanzanie. Ce territoire sauvage et reculé abrite la plus grande population d\'éléphants d\'Afrique de l\'Est. Installation dans un camp de brousse authentique.',
                ],
                [
                    'label' => 'Jour 8',
                    'titre' => 'Safari à Ruaha',
                    'lieu' => 'Parc National de Ruaha',
                    'description' => 'Journée complète de safari dans le parc de Ruaha. Les paysages de savane et de baobabs centenaires sont le terrain de chasse des lions et léopards. Excellentes observations le long de la Grande Rivière Ruaha où se rassemblent les animaux.',
                ],
                [
                    'label' => 'Jour 9',
                    'titre' => 'Retour à Dar es Salaam',
                    'lieu' => 'Ruaha → Dar es Salaam',
                    'description' => 'Safari matinal puis vol intérieur vers Dar es Salaam. Arrivée en fin de journée et installation dans un hôtel en bord de mer. Dîner de fruits de mer dans un restaurant du front de mer avec vue sur l\'océan Indien.',
                ],
                [
                    'label' => 'Jour 10',
                    'titre' => 'Départ ou extension Zanzibar',
                    'lieu' => 'Dar es Salaam → Aéroport',
                    'description' => 'Selon l\'horaire de votre vol, temps libre pour découvrir le centre historique de Dar es Salaam ou le marché de Kariakoo. Transfert à l\'aéroport pour votre vol retour, ou embarquement sur le ferry vers Zanzibar pour prolonger l\'aventure.',
                ],
            ],
        ],
    ];
}

/**
 * Importer un itinéraire
 *
 * @param array $data Données de l'itinéraire
 * @return int|WP_Error Post ID ou erreur
 */
function lunivers_import_single_itineraire( array $data )
{
    // Vérifier si l'itinéraire existe déjà
    $existing = get_posts([
        'post_type' => 'itineraire',
        'post_status' => 'any',
        'title' => $data['title'],
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return new WP_Error('exists', sprintf('L\'itinéraire "%s" existe déjà.', $data['title']));
    }

    // Créer le post
    $post_id = wp_insert_post([
        'post_type' => 'itineraire',
        'post_title' => $data['title'],
        'post_status' => 'publish',
    ]);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Mettre à jour les champs ACF simples (valeurs + références field keys)
    update_post_meta($post_id, 'sous_titre', $data['sous_titre']);
    update_post_meta($post_id, '_sous_titre', 'field_itineraire_sous_titre');

    update_post_meta($post_id, 'duree', $data['duree']);
    update_post_meta($post_id, '_duree', 'field_itineraire_duree');

    update_post_meta($post_id, 'highlight', $data['highlight']);
    update_post_meta($post_id, '_highlight', 'field_itineraire_highlight');

    update_post_meta($post_id, 'destinations', $data['destinations']);
    update_post_meta($post_id, '_destinations', 'field_itineraire_destinations');

    // Mettre à jour le repeater "jours" ligne par ligne
    // D'abord, on définit le nombre de lignes
    $num_jours = count($data['jours']);
    update_post_meta($post_id, 'jours', $num_jours);
    update_post_meta($post_id, '_jours', 'field_itineraire_jours');

    // Ensuite, on ajoute chaque ligne avec ses sous-champs
    foreach ($data['jours'] as $index => $jour) {
        // Valeurs des champs
        update_post_meta($post_id, 'jours_' . $index . '_label', $jour['label']);
        update_post_meta($post_id, 'jours_' . $index . '_titre', $jour['titre']);
        update_post_meta($post_id, 'jours_' . $index . '_lieu', $jour['lieu']);
        update_post_meta($post_id, 'jours_' . $index . '_description', $jour['description']);

        // Références aux field keys (nécessaire pour ACF)
        update_post_meta($post_id, '_jours_' . $index . '_label', 'field_itineraire_jour_label');
        update_post_meta($post_id, '_jours_' . $index . '_titre', 'field_itineraire_jour_titre');
        update_post_meta($post_id, '_jours_' . $index . '_lieu', 'field_itineraire_jour_lieu');
        update_post_meta($post_id, '_jours_' . $index . '_description', 'field_itineraire_jour_description');
    }

    return $post_id;
}

/**
 * Exécuter l'import
 */
function lunivers_run_itineraires_import(): array
{
    $itineraires = lunivers_get_itineraires_data();
    $results = [
        'success' => [],
        'errors' => [],
    ];

    foreach ($itineraires as $itineraire) {
        $result = lunivers_import_single_itineraire($itineraire);

        if (is_wp_error($result)) {
            $results['errors'][] = [
                'title' => $itineraire['title'],
                'message' => $result->get_error_message(),
            ];
        } else {
            $results['success'][] = [
                'title' => $itineraire['title'],
                'post_id' => $result,
            ];
        }
    }

    return $results;
}

// Interface d'administration
if (isset($_GET['page']) && $_GET['page'] === 'import-itineraires') {
    // Exécuter l'import si demandé
    $import_results = null;
    if (isset($_POST['run_import']) && wp_verify_nonce($_POST['_wpnonce'], 'import_itineraires')) {
        $import_results = lunivers_run_itineraires_import();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Import des Itinéraires - Lion Select Safaris</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background: #f1f1f1;
            }
            .card {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            h1 {
                color: #1d2327;
                margin-top: 0;
            }
            .btn {
                background: #2271b1;
                color: white;
                border: none;
                padding: 12px 24px;
                font-size: 14px;
                cursor: pointer;
                border-radius: 4px;
            }
            .btn:hover {
                background: #135e96;
            }
            .success {
                background: #d1e7dd;
                border: 1px solid #badbcc;
                padding: 15px;
                border-radius: 4px;
                margin: 10px 0;
            }
            .error {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 15px;
                border-radius: 4px;
                margin: 10px 0;
            }
            ul {
                margin: 20px 0;
                padding-left: 20px;
            }
            li {
                margin: 8px 0;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Import des Itinéraires</h1>

            <?php if ($import_results): ?>
                <h2>Résultats de l'import</h2>

                <?php if (!empty($import_results['success'])): ?>
                    <div class="success">
                        <strong><?php echo count($import_results['success']); ?> itinéraire(s) importé(s) avec succès :</strong>
                        <ul>
                            <?php foreach ($import_results['success'] as $item): ?>
                                <li>
                                    <?php echo esc_html($item['title']); ?>
                                    (<a href="<?php echo admin_url('post.php?post=' . $item['post_id'] . '&action=edit'); ?>">Éditer</a>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($import_results['errors'])): ?>
                    <div class="error">
                        <strong><?php echo count($import_results['errors']); ?> erreur(s) :</strong>
                        <ul>
                            <?php foreach ($import_results['errors'] as $item): ?>
                                <li><?php echo esc_html($item['title']); ?>: <?php echo esc_html($item['message']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <p><a href="<?php echo admin_url('edit.php?post_type=itineraire'); ?>">Voir tous les itinéraires</a></p>

            <?php else: ?>
                <p>Ce script va importer les 5 itinéraires suivants :</p>
                <ul>
                    <li><strong>Les mythiques parcs du nord</strong> (7 jours)</li>
                    <li><strong>Big five et farniente à Zanzibar</strong> (12 jours)</li>
                    <li><strong>La Tanzanie hors des sentiers battus</strong> (10 jours)</li>
                    <li><strong>Entre safaris et immersion Maasai</strong> (8 jours)</li>
                    <li><strong>Les merveilles du sud</strong> (10 jours)</li>
                </ul>

                <form method="post">
                    <?php wp_nonce_field('import_itineraires'); ?>
                    <button type="submit" name="run_import" class="btn">Lancer l'import</button>
                </form>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Ajouter le menu admin
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=itineraire',
        'Import des Itinéraires',
        'Importer',
        'manage_options',
        'import-itineraires',
        function() {
            include __FILE__;
        }
    );
});
