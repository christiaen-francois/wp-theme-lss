# Documentation Technique - Système de Flexible Content ACF

Ce document fournit toutes les informations nécessaires pour mettre en place un système de **Flexible Content ACF** complet dans un thème WordPress, avec prévisualisation visuelle des layouts dans l'admin.

---

## Table des matières

1. [Architecture du système](#1-architecture-du-système)
2. [Structure des fichiers](#2-structure-des-fichiers)
3. [Fonctions helpers essentielles](#3-fonctions-helpers-essentielles)
4. [Fonction de rendu principale](#4-fonction-de-rendu-principale)
5. [Structure d'un layout type](#5-structure-dun-layout-type)
6. [Système de prévisualisation SVG dans l'admin](#6-système-de-prévisualisation-svg-dans-ladmin)
7. [Guide de création des icônes SVG](#7-guide-de-création-des-icônes-svg)
8. [Configuration ACF JSON](#8-configuration-acf-json)
9. [Layouts génériques recommandés](#9-layouts-génériques-recommandés)
10. [Checklist d'implémentation](#10-checklist-dimplémentation)

---

## 1. Architecture du système

Le système de Flexible Content repose sur 4 composants principaux :

```
┌─────────────────────────────────────────────────────────────────┐
│                        FLEXIBLE CONTENT                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐       │
│  │ ACF Fields   │───▶│ PHP Render   │───▶│ HTML Output  │       │
│  │ (JSON sync)  │    │ Function     │    │ (Tailwind)   │       │
│  └──────────────┘    └──────────────┘    └──────────────┘       │
│         │                                                        │
│         ▼                                                        │
│  ┌──────────────┐    ┌──────────────┐                           │
│  │ SVG Icons    │───▶│ Admin UX     │                           │
│  │ (Preview)    │    │ (Modal)      │                           │
│  └──────────────┘    └──────────────┘                           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Flux de données

1. **ACF Field Group** → Définit la structure des layouts (JSON sync)
2. **Fonction de rendu** → Route vers les fonctions de layout appropriées
3. **Fonctions helpers** → Fournissent les classes CSS réutilisables
4. **SVG Icons** → Affiché dans le modal de sélection ACF

---

## 2. Structure des fichiers

```
theme/
├── inc/
│   ├── Classes/
│   │   └── Acf.php                    # Classe pour l'intégration ACF et les SVG admin
│   └── Functions/
│       └── flexible-content.php       # Fonction de rendu principale + helpers
├── acf-fields/
│   └── group_xxxxxx.json              # Export JSON du groupe de champs ACF
├── assets/
│   └── images/
│       └── flexible-content/          # Icônes SVG de prévisualisation
│           ├── hero.svg
│           ├── texte_image.svg
│           ├── services.svg
│           └── ...
└── template-parts/
    └── flexible-content/              # ⭐ Un fichier PHP par layout
        ├── hero.php
        ├── texte_image.php
        ├── services.php
        ├── temoignages.php
        ├── faq.php
        ├── galerie.php
        ├── newsletter.php
        ├── cta.php
        ├── equipe.php
        └── separateur.php
```

> **Note** : L'approche "un fichier par layout" est recommandée pour la maintenabilité. Pour ajouter un nouveau layout, il suffit de créer le fichier PHP correspondant - aucune modification de la fonction principale n'est nécessaire.

---

## 3. Fonctions helpers essentielles

Ces fonctions sont réutilisées dans tous les layouts pour garantir la cohérence.

### 3.1 Gestion des espacements

```php
<?php
/**
 * Get spacing classes based on spacing choice
 *
 * @param string $espacement Spacing choice: 'top', 'bottom', 'both', 'none'
 * @return string CSS classes for padding
 */
function prefix_get_spacing_classes( $espacement = 'top' ) {
    $spacing = [
        'top'    => 'pt-16 md:pt-24 xl:pt-32',
        'bottom' => 'pb-16 md:pb-24 xl:pb-32',
        'both'   => 'py-16 md:py-24 xl:py-32',
        'none'   => '',
    ];

    return $spacing[ $espacement ] ?? $spacing['top'];
}
```

### 3.2 Gestion des couleurs de fond

```php
<?php
/**
 * Get background and text color classes based on background choice
 *
 * @param string $bg_color Background color choice: 'dark', 'light', 'white', 'primary'
 * @return array Array with 'bg', 'text', 'text_secondary', 'text_muted', 'border' keys
 */
function prefix_get_layout_colors( $bg_color = 'white' ) {
    $colors = [
        'dark'    => [
            'bg'             => 'bg-gray-900',
            'text'           => 'text-white',
            'text_secondary' => 'text-gray-200',
            'text_muted'     => 'text-gray-400',
            'border'         => 'border-gray-700',
        ],
        'light'   => [
            'bg'             => 'bg-gray-50',
            'text'           => 'text-gray-900',
            'text_secondary' => 'text-gray-700',
            'text_muted'     => 'text-gray-500',
            'border'         => 'border-gray-200',
        ],
        'white'   => [
            'bg'             => 'bg-white',
            'text'           => 'text-gray-900',
            'text_secondary' => 'text-gray-700',
            'text_muted'     => 'text-gray-500',
            'border'         => 'border-gray-200',
        ],
        'primary' => [
            'bg'             => 'bg-primary-500',
            'text'           => 'text-white',
            'text_secondary' => 'text-primary-100',
            'text_muted'     => 'text-primary-200',
            'border'         => 'border-primary-400',
        ],
    ];

    return $colors[ $bg_color ] ?? $colors['white'];
}
```

### 3.3 Gestion des formats d'image

```php
<?php
/**
 * Get aspect ratio class based on format choice
 *
 * @param string $format Format choice: 'paysage', 'carre', 'portrait', 'large'
 * @return string CSS class for aspect ratio
 */
function prefix_get_aspect_ratio_class( $format = 'paysage' ) {
    $formats = [
        'paysage'  => 'aspect-[4/3]',      // 4:3
        'carre'    => 'aspect-square',     // 1:1
        'portrait' => 'aspect-[3/4]',      // 3:4
        'large'    => 'aspect-video',      // 16:9
    ];

    return $formats[ $format ] ?? $formats['paysage'];
}
```

---

## 4. Fonction de rendu principale

Cette fonction est appelée dans les templates de page et charge automatiquement les fichiers template correspondant à chaque layout.

### 4.1 Approche recommandée : Template Parts (modulaire)

Cette approche utilise des fichiers séparés pour chaque layout, ce qui offre plusieurs avantages :
- **Maintenabilité** : Un fichier par layout, facile à modifier
- **Pas de switch/case** : Ajout automatique de nouveaux layouts
- **Collaboration** : Moins de conflits Git
- **Debug** : Message clair si un template est manquant
- **Index** : Numéro de section disponible pour animations/styling

```php
<?php
/**
 * Render flexible content layouts using template parts
 *
 * @param string $field_name The ACF field name (default: 'flexible_content')
 * @param string $template_path Path to template parts (default: 'template-parts/flexible-content/')
 * @return void
 */
function prefix_render_flexible_content( $field_name = 'flexible_content', $template_path = 'template-parts/flexible-content/' ) {
    if ( ! function_exists( 'have_rows' ) ) {
        return;
    }

    if ( ! have_rows( $field_name ) ) {
        return;
    }

    $index = 1;

    while ( have_rows( $field_name ) ) : the_row();

        $layout_name = get_row_layout();
        $template_file = $template_path . $layout_name . '.php';
        $located = locate_template( $template_file );

        if ( ! empty( $located ) ) {
            // Le template existe, on le charge avec les données
            get_template_part(
                $template_path . $layout_name,
                null,
                [
                    'layout' => $layout_name,
                    'index'  => $index,
                    'data'   => get_row( true ), // Toutes les données du layout
                ]
            );
        } else {
            // Template non trouvé - afficher un message en environnement de développement
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                ?>
                <div class="py-12 bg-red-50 border-2 border-red-200">
                    <div class="container mx-auto px-4 text-center">
                        <p class="text-red-600">
                            ⚠️ Template manquant : <strong><?php echo esc_html( $template_file ); ?></strong>
                        </p>
                        <p class="text-red-500 text-sm mt-2">
                            Créez le fichier <code><?php echo esc_html( $layout_name ); ?>.php</code> dans <code><?php echo esc_html( $template_path ); ?></code>
                        </p>
                    </div>
                </div>
                <?php
            }
        }

        $index++;
    endwhile;
}
```

### 4.2 Structure des fichiers template

Avec cette approche, créez un fichier par layout :

```
theme/
└── template-parts/
    └── flexible-content/
        ├── hero.php
        ├── texte_image.php
        ├── services.php
        ├── temoignages.php
        ├── faq.php
        ├── galerie.php
        ├── newsletter.php
        ├── cta.php
        ├── equipe.php
        └── separateur.php
```

### 4.3 Exemple de fichier template (hero.php)

```php
<?php
/**
 * Flexible Content Layout: Hero
 *
 * @package theme-name
 *
 * Variables disponibles :
 * @var array  $args['data']   - Données ACF du layout
 * @var string $args['layout'] - Nom du layout ('hero')
 * @var int    $args['index']  - Index de la section (1, 2, 3...)
 */

// Extraction des données
$data        = $args['data'] ?? [];
$index       = $args['index'] ?? 1;
$titre       = $data['titre'] ?? '';
$sous_titre  = $data['sous_titre'] ?? '';
$description = $data['description'] ?? '';
$image_fond  = $data['image_fond'] ?? '';
$lien        = $data['lien'] ?? '';
$bg_color    = $data['couleur_fond'] ?? 'dark';
$espacement  = $data['espacement'] ?? 'both';

// Helpers
$colors        = prefix_get_layout_colors( $bg_color );
$spacing_class = prefix_get_spacing_classes( $espacement );

// Validation
if ( ! $titre && ! $image_fond ) {
    return;
}
?>

<section
    class="layout-hero relative overflow-hidden <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>"
    data-section-index="<?php echo esc_attr( $index ); ?>"
>
    <?php if ( $image_fond ) : ?>
        <div class="absolute inset-0 parallax-bg lazy bg-cover bg-center" data-bg="<?php echo esc_url( $image_fond['url'] ); ?>"></div>
        <div class="absolute inset-0 bg-brown-950/40"></div>
    <?php endif; ?>

    <div class="relative z-10 container mx-auto px-4 text-center">
        <?php if ( $sous_titre ) : ?>
            <span class="inline-block px-4 py-2 text-xs tracking-wider uppercase bg-primary-500/20 text-primary-400 rounded-full mb-4">
                <?php echo esc_html( $sous_titre ); ?>
            </span>
        <?php endif; ?>

        <?php if ( $titre ) : ?>
            <h1 class="text-4xl md:text-5xl lg:text-6xl <?php echo esc_attr( $colors['text'] ); ?> mb-6">
                <?php echo esc_html( $titre ); ?>
            </h1>
        <?php endif; ?>

        <?php if ( $description ) : ?>
            <div class="max-w-2xl mx-auto <?php echo esc_attr( $colors['text_secondary'] ); ?> text-lg mb-8">
                <?php echo wp_kses_post( $description ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
            <a
                href="<?php echo esc_url( $lien['url'] ); ?>"
                target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
                class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-all"
            >
                <?php echo esc_html( $lien['title'] ?? 'Découvrir' ); ?>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>
</section>
```

### 4.4 Approche alternative : Switch/Case (monolithique)

> ⚠️ **Non recommandée** pour les gros projets. Utilisée uniquement si vous préférez tout centraliser dans un seul fichier.

<details>
<summary>Voir l'approche switch/case (cliquer pour dérouler)</summary>

```php
<?php
/**
 * Render flexible content layouts (approche switch/case)
 *
 * @deprecated Préférer l'approche template parts pour une meilleure maintenabilité
 */
function prefix_render_flexible_content_legacy( $field_name = 'flexible_content' ) {
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
            case 'hero':
                prefix_render_layout_hero( $layout );
                break;

            case 'texte_image':
                prefix_render_layout_texte_image( $layout );
                break;

            case 'services':
                prefix_render_layout_services( $layout );
                break;

            // ... autres layouts
        }
    }
}
```

</details>

### 4.5 Utilisation dans les templates

```php
<?php
// Dans page.php, single.php ou un template personnalisé
get_header();
?>

<main id="main-content">
    <?php prefix_render_flexible_content( 'flexible_content' ); ?>
</main>

<?php get_footer(); ?>

<?php
// Ou avec un chemin de template personnalisé :
prefix_render_flexible_content( 'flexible_content', 'tpl-parts/flexibles/' );
?>
```

---

## 5. Structure d'un fichier template de layout

Chaque layout est un fichier PHP séparé dans `template-parts/flexible-content/`. Voici un exemple complet pour le layout "Texte + Image" :

### Fichier : `template-parts/flexible-content/texte_image.php`

```php
<?php
/**
 * Flexible Content Layout: Texte + Image
 *
 * @package theme-name
 *
 * Variables disponibles via $args :
 * @var array  $args['data']   - Données ACF du layout (tous les champs)
 * @var string $args['layout'] - Nom du layout ('texte_image')
 * @var int    $args['index']  - Index de la section (1, 2, 3...)
 */

// 1. Extraction des données
$data         = $args['data'] ?? [];
$index        = $args['index'] ?? 1;
$sous_titre   = $data['sous_titre'] ?? '';
$titre        = $data['titre'] ?? '';
$contenu      = $data['contenu'] ?? '';
$lien         = $data['lien'] ?? '';
$image        = $data['image'] ?? '';
$position     = $data['position_image'] ?? 'right';
$bg_color     = $data['couleur_fond'] ?? 'white';
$format_image = $data['format_image'] ?? 'paysage';
$espacement   = $data['espacement'] ?? 'both';

// 2. Validation (retour anticipé si données manquantes)
if ( ! $contenu ) {
    return;
}

// 3. Récupération des classes via helpers
$colors        = prefix_get_layout_colors( $bg_color );
$aspect_class  = prefix_get_aspect_ratio_class( $format_image );
$spacing_class = prefix_get_spacing_classes( $espacement );

// 4. Logique de positionnement
$has_image     = $image && $position !== 'none';
$image_classes = $position === 'right' ? 'md:order-2' : 'md:order-1';
$text_classes  = $position === 'right' ? 'md:order-1' : 'md:order-2';
?>

<section
    class="layout-texte-image <?php echo esc_attr( $spacing_class ); ?> <?php echo esc_attr( $colors['bg'] ); ?>"
    data-section-index="<?php echo esc_attr( $index ); ?>"
    data-layout="<?php echo esc_attr( $args['layout'] ); ?>"
>
    <div class="container mx-auto px-4">
        <?php if ( $has_image ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Contenu texte -->
                <div class="<?php echo esc_attr( $text_classes ); ?>">
                    <?php if ( $sous_titre || $titre ) : ?>
                        <header class="mb-8">
                            <?php if ( $sous_titre ) : ?>
                                <span class="inline-block px-4 py-2 text-xs tracking-wider uppercase bg-primary-500/10 text-primary-500 rounded-full mb-4">
                                    <?php echo esc_html( $sous_titre ); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ( $titre ) : ?>
                                <h2 class="text-4xl md:text-5xl <?php echo esc_attr( $colors['text'] ); ?>">
                                    <?php echo esc_html( $titre ); ?>
                                </h2>
                            <?php endif; ?>
                        </header>
                    <?php endif; ?>

                    <div class="<?php echo esc_attr( $colors['text_secondary'] ); ?> leading-relaxed">
                        <?php echo wp_kses_post( $contenu ); ?>
                    </div>

                    <?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
                        <div class="mt-8">
                            <a
                                href="<?php echo esc_url( $lien['url'] ); ?>"
                                target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
                                class="inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-500 text-primary-500 rounded-lg hover:bg-primary-500 hover:text-white transition-all"
                            >
                                <?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    // Placeholder SVG pour le lazy loading
                    $placeholder  = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . esc_attr( $image_width ) . ' ' . esc_attr( $image_height ) . '"%3E%3C/svg%3E';
                    ?>
                    <div class="overflow-hidden rounded-lg">
                        <div class="<?php echo esc_attr( $aspect_class ); ?> bg-gray-100">
                            <img
                                data-src="<?php echo esc_url( $image_url ); ?>"
                                src="<?php echo esc_attr( $placeholder ); ?>"
                                alt="<?php echo esc_attr( $image_alt ); ?>"
                                width="<?php echo esc_attr( $image_width ); ?>"
                                height="<?php echo esc_attr( $image_height ); ?>"
                                class="lazy w-full h-full object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Contenu sans image (centré) -->
            <div class="max-w-4xl mx-auto text-center">
                <?php if ( $sous_titre || $titre ) : ?>
                    <header class="mb-12">
                        <?php if ( $sous_titre ) : ?>
                            <span class="inline-block px-4 py-2 text-xs tracking-wider uppercase bg-primary-500/10 text-primary-500 rounded-full mb-4">
                                <?php echo esc_html( $sous_titre ); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ( $titre ) : ?>
                            <h2 class="text-4xl md:text-5xl lg:text-6xl <?php echo esc_attr( $colors['text'] ); ?>">
                                <?php echo esc_html( $titre ); ?>
                            </h2>
                        <?php endif; ?>
                    </header>
                <?php endif; ?>

                <div class="<?php echo esc_attr( $colors['text_secondary'] ); ?> leading-relaxed text-lg">
                    <?php echo wp_kses_post( $contenu ); ?>
                </div>

                <?php if ( $lien && ! empty( $lien['url'] ) ) : ?>
                    <div class="mt-8">
                        <a
                            href="<?php echo esc_url( $lien['url'] ); ?>"
                            target="<?php echo esc_attr( $lien['target'] ?? '_self' ); ?>"
                            class="inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-500 text-primary-500 rounded-lg hover:bg-primary-500 hover:text-white transition-all"
                        >
                            <?php echo esc_html( $lien['title'] ?? 'En savoir plus' ); ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
/**
 * Notes sur la structure :
 *
 * 1. Chaque layout est un fichier séparé = facile à maintenir
 * 2. L'index permet de cibler les sections paires/impaires en CSS ou JS
 * 3. Les data-attributes facilitent le debug et les animations GSAP
 * 4. Pour ajouter un nouveau layout, créer simplement le fichier .php correspondant
 */
```

---

## 6. Système de prévisualisation SVG dans l'admin

### 6.1 Classe PHP pour l'intégration ACF

Ajouter ce code dans votre classe ACF (ou dans `functions.php`) :

```php
<?php
/**
 * ACF Flexible Content - Visual Layout Picker
 * Injecte des icônes SVG de prévisualisation dans le modal de sélection de layouts
 */

// Hook pour ajouter le JS/CSS dans l'admin ACF
add_action( 'acf/input/admin_footer', 'prefix_flexible_content_layout_popup' );

function prefix_flexible_content_layout_popup() {
    // Définir l'URL de base des icônes SVG
    $icons_url = get_template_directory_uri() . '/assets/images/flexible-content/';
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){

        try {
            // Modifier la popup ACF pour ajouter une classe personnalisée
            var flexible_content_position = acf.fields.flexible_content.position_popup;
            acf.fields.flexible_content.position_popup = function($popup, $bouton){
                // Ajouter la classe modale si c'est notre flexible content
                if(this.$field.attr('data-name') == 'flexible_content'){
                    $popup.addClass('acf-fc-popup-modal');
                }
                return flexible_content_position.apply(this, arguments);
            }
        } catch(e) {}

        // Ajouter les icônes SVG aux options de layout
        $('a[data-name=add-layout]').click(function(){
            waitForEl('.acf-tooltip li', function() {
                document.querySelectorAll('.acf-tooltip li a').forEach((item) => {
                    var image = item.querySelector("img");
                    var layoutName = item.getAttribute("data-layout");

                    if(!image && layoutName){
                        $(item).append('<div class="imagePreview"><img src="<?php echo esc_url( $icons_url ); ?>' + layoutName + '.svg" alt=""></div>');
                    }
                });
            });
        });

        // Helper : attendre qu'un élément existe dans le DOM
        var waitForEl = function(selector, callback) {
            if (jQuery(selector).length) {
                callback();
            } else {
                setTimeout(function() {
                    waitForEl(selector, callback);
                }, 100);
            }
        };
    });
    </script>

    <style>
    /* Container de prévisualisation */
    .imagePreview {
        position: relative;
        overflow: hidden;
        margin: 15px -15px -15px -15px;
        background: white;
    }
    .imagePreview:after {
        content: "";
        display: block;
        padding-bottom: 50%; /* Ratio 2:1 */
    }
    .imagePreview img {
        position: absolute !important;
        width: 100% !important;
        height: 100% !important;
        left: 0;
        object-fit: cover;
    }

    /* Modal personnalisée */
    .acf-fc-popup {
        width: 80%;
        max-width: 1200px;
        max-height: 90dvh;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%);
        border-radius: 0;
        position: fixed !important;
        background: none !important;
        padding: 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }
    .acf-fc-popup * {
        box-sizing: border-box;
    }
    .acf-fc-popup::before,
    .acf-fc-popup::after {
        border-color: transparent !important;
    }

    /* Overlay sombre derrière la modal */
    .acf-fc-popup:before {
        top: 50% !important;
        right: 0 !important;
        bottom: 0 !important;
        left: 50% !important;
        position: fixed;
        background: rgba(0,0,0,0.8);
        height: 100dvh;
        width: 110vw;
        margin: 0 !important;
        padding: 0;
        content: "";
        transform: translate(-50%, -50%);
    }

    /* Liste des layouts */
    .acf-fc-popup ul {
        position: relative;
        height: 100%;
        width: 100%;
        overflow-y: auto;
        background: #f4f4f4;
        border: #ccc;
        padding: 15px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
        padding-top: 93px !important;
        flex-direction: row;
        flex-wrap: wrap;
    }

    /* Titre de la modal */
    .acf-fc-popup ul:before {
        position: absolute;
        top: 31px;
        content: "Ajouter une section";
        font-size: 23px;
        font-weight: 400;
        color: #333;
        left: 30px;
    }
    .acf-fc-popup ul:after {
        position: absolute;
        top: 62px;
        content: "Sélectionnez une section à ajouter sur la page.";
        font-size: 13px;
        color: #666;
        left: 30px;
        font-style: italic;
    }

    /* Items de layout - Grille responsive */
    .acf-fc-popup ul li {
        width: 33.33333%;
        padding: 1%;
        float: left;
        height: auto;
    }
    @media only screen and (max-width: 960px) {
        .acf-fc-popup ul li {
            width: 50%;
        }
    }
    @media only screen and (max-width: 782px) {
        .acf-fc-popup {
            width: 95%;
            height: 88%;
        }
        .acf-fc-popup ul li {
            width: 100%;
        }
    }

    /* Style des cartes de layout */
    .acf-fc-popup ul li a {
        border: 1px solid #ddd;
        background: #fff;
        padding: 15px;
        color: #333;
    }
    .acf-fc-popup ul li a:hover {
        background: #1e293b; /* Couleur de hover - adapter selon votre thème */
        border: 1px solid #1e293b;
        color: #fff;
    }
    </style>
    <?php
}
```

### 6.2 Auto-ajout si un seul layout disponible

Bonus : si un seul layout est disponible, l'ajouter automatiquement sans ouvrir la popup :

```php
<?php
add_action( 'acf/input/admin_footer', 'prefix_flexible_content_no_popup' );

function prefix_flexible_content_no_popup() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        try {
            var flexible_content_open = acf.fields.flexible_content._open;
            acf.fields.flexible_content._open = function(e){
                var $popup = $(this.$el.children('.tmpl-popup').html());
                // Si un seul layout, l'ajouter directement
                if($popup.find('a').length == 1){
                    acf.fields.flexible_content.add($popup.find('a').attr('data-layout'));
                    return false;
                }
                return flexible_content_open.apply(this, arguments);
            }
        } catch(e) {}
    });
    </script>
    <?php
}
```

---

## 7. Guide de création des icônes SVG

### 7.1 Spécifications techniques

| Propriété | Valeur |
|-----------|--------|
| **Viewbox** | `0 0 400 200` (ratio 2:1) |
| **Fond** | `#f5f5f4` (gris clair neutre) |
| **Police** | `system-ui, sans-serif` |
| **Taille du label** | `11px` |
| **Couleur du label** | `#78716c` |
| **Position du label** | Centré en bas (`y="185"`) |

### 7.2 Palette de couleurs recommandée

```
Fond de carte :      #f5f5f4 (stone-100)
Fond image :         #e7e5e4 (stone-200)
Placeholder image :  #d6d3d1 (stone-300)
Texte titre :        #292524 (stone-800)
Texte paragraphe :   #78716c (stone-500)
Bordures :           #e7e5e4 (stone-200)
Accent primaire :    #fcbb46 (golden/primary)
Accent opacity :     rgba(252, 187, 70, 0.2)
```

### 7.3 Template de base SVG

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <!-- Fond -->
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Contenu du layout (à personnaliser) -->
  <g transform="translate(20, 20)">
    <!-- Éléments de prévisualisation ici -->
  </g>

  <!-- Label du layout -->
  <text x="200" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">Nom du Layout</text>
</svg>
```

### 7.4 Composants SVG réutilisables

#### Badge/Sur-titre

```svg
<!-- Badge sur-titre -->
<rect x="0" y="0" width="80" height="14" rx="7" fill="#fcbb46" opacity="0.2"/>
<rect x="10" y="4" width="60" height="6" rx="2" fill="#fcbb46"/>
```

#### Titre

```svg
<!-- Lignes de titre -->
<rect x="0" y="22" width="140" height="14" rx="2" fill="#292524"/>
<rect x="0" y="40" width="100" height="14" rx="2" fill="#292524"/>
```

#### Paragraphe de texte

```svg
<!-- Lignes de texte -->
<rect x="0" y="65" width="160" height="6" rx="1" fill="#78716c"/>
<rect x="0" y="77" width="150" height="6" rx="1" fill="#78716c"/>
<rect x="0" y="89" width="155" height="6" rx="1" fill="#78716c"/>
```

#### Bouton CTA

```svg
<!-- Bouton avec bordure -->
<rect x="0" y="120" width="90" height="28" rx="6" fill="none" stroke="#fcbb46" stroke-width="2"/>
<rect x="12" y="131" width="50" height="6" rx="1" fill="#fcbb46"/>
<path d="M72 134 L78 134 M76 131 L79 134 L76 137" stroke="#fcbb46" stroke-width="1.5" stroke-linecap="round"/>

<!-- Bouton plein -->
<rect x="0" y="98" width="90" height="28" rx="6" fill="#fcbb46"/>
<rect x="12" y="109" width="45" height="6" rx="1" fill="#ffffff"/>
```

#### Placeholder d'image

```svg
<!-- Conteneur image avec paysage stylisé -->
<rect x="0" y="0" width="170" height="127" rx="8" fill="#e7e5e4"/>
<circle cx="40" cy="35" r="15" fill="#d6d3d1"/>
<path d="M15 100 L50 70 L85 95 L120 55 L155 90 L155 115 L15 115 Z" fill="#d6d3d1"/>
```

#### Icône image

```svg
<!-- Icône représentant une image -->
<rect x="70" y="50" width="30" height="25" rx="2" stroke="#a8a29e" stroke-width="2" fill="none"/>
<circle cx="78" cy="58" r="3" fill="#a8a29e"/>
<path d="M72 70 L80 62 L88 70 L95 65 L98 70" stroke="#a8a29e" stroke-width="1.5" fill="none"/>
```

#### Chevron (FAQ/Accordion)

```svg
<!-- Chevron vers le bas -->
<path d="M255 75 L260 81 L265 75" stroke="#fcbb46" stroke-width="2" stroke-linecap="round" fill="none"/>

<!-- Chevron vers le haut -->
<path d="M255 20 L260 14 L265 20" stroke="#fcbb46" stroke-width="2" stroke-linecap="round" fill="none"/>
```

### 7.5 Exemples complets de SVG

#### Hero

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Full hero section -->
  <g transform="translate(20, 20)">
    <!-- Background image -->
    <rect x="0" y="0" width="360" height="155" rx="6" fill="#44403c"/>

    <!-- Overlay -->
    <rect x="0" y="0" width="360" height="155" rx="6" fill="#292524" opacity="0.4"/>

    <!-- Content centered -->
    <g transform="translate(80, 30)">
      <!-- Sur-titre badge -->
      <rect x="60" y="0" width="80" height="14" rx="7" fill="#fcbb46" opacity="0.2"/>
      <rect x="70" y="4" width="60" height="6" rx="2" fill="#fcbb46"/>

      <!-- Title -->
      <rect x="20" y="25" width="160" height="14" rx="2" fill="#ffffff"/>
      <rect x="50" y="45" width="100" height="14" rx="2" fill="#ffffff"/>

      <!-- Description -->
      <rect x="30" y="70" width="140" height="5" rx="1" fill="#ffffff" opacity="0.7"/>
      <rect x="45" y="80" width="110" height="5" rx="1" fill="#ffffff" opacity="0.7"/>

      <!-- CTA Button -->
      <rect x="55" y="98" width="90" height="28" rx="6" fill="#fcbb46"/>
      <rect x="70" y="109" width="45" height="6" rx="1" fill="#ffffff"/>
    </g>
  </g>

  <!-- Label -->
  <text x="200" y="190" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">Hero</text>
</svg>
```

#### Texte + Image

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Left: Text content -->
  <g transform="translate(20, 30)">
    <!-- Sur-titre badge -->
    <rect x="0" y="0" width="60" height="12" rx="6" fill="#fcbb46" opacity="0.3"/>
    <rect x="5" y="4" width="50" height="4" rx="2" fill="#fcbb46"/>

    <!-- Title -->
    <rect x="0" y="22" width="140" height="14" rx="2" fill="#292524"/>
    <rect x="0" y="40" width="100" height="14" rx="2" fill="#292524"/>

    <!-- Text lines -->
    <rect x="0" y="65" width="160" height="6" rx="1" fill="#78716c"/>
    <rect x="0" y="77" width="150" height="6" rx="1" fill="#78716c"/>
    <rect x="0" y="89" width="155" height="6" rx="1" fill="#78716c"/>

    <!-- Button -->
    <rect x="0" y="120" width="90" height="28" rx="6" fill="none" stroke="#fcbb46" stroke-width="2"/>
    <rect x="12" y="131" width="50" height="6" rx="1" fill="#fcbb46"/>
  </g>

  <!-- Right: Image -->
  <g transform="translate(210, 20)">
    <rect x="0" y="0" width="170" height="127" rx="8" fill="#e7e5e4"/>
    <circle cx="40" cy="35" r="15" fill="#d6d3d1"/>
    <path d="M15 100 L50 70 L85 95 L120 55 L155 90 L155 115 L15 115 Z" fill="#d6d3d1"/>
  </g>

  <!-- Label -->
  <text x="200" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">Texte + Image</text>
</svg>
```

#### Services (Grille de cartes)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Header -->
  <g transform="translate(120, 12)">
    <rect x="40" y="0" width="80" height="12" rx="6" fill="#fcbb46" opacity="0.2"/>
    <rect x="50" y="3" width="60" height="6" rx="2" fill="#fcbb46"/>
    <rect x="30" y="18" width="100" height="10" rx="2" fill="#292524"/>
  </g>

  <!-- Service cards grid (3 columns) -->
  <g transform="translate(20, 45)">
    <!-- Card 1 -->
    <rect x="0" y="0" width="115" height="120" rx="8" fill="#faf9f8" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="0" y="0" width="115" height="55" rx="8" fill="#e7e5e4"/>
    <circle cx="25" cy="20" r="8" fill="#d6d3d1"/>
    <path d="M10 45 L35 30 L60 42 L85 25 L105 38 L105 47 L10 47 Z" fill="#d6d3d1"/>
    <rect x="10" y="62" width="90" height="8" rx="1" fill="#292524"/>
    <rect x="10" y="76" width="95" height="4" rx="1" fill="#78716c"/>
    <rect x="10" y="84" width="85" height="4" rx="1" fill="#78716c"/>
    <rect x="10" y="100" width="55" height="5" rx="1" fill="#fcbb46"/>

    <!-- Card 2 -->
    <rect x="122" y="0" width="115" height="120" rx="8" fill="#faf9f8" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="122" y="0" width="115" height="55" rx="8" fill="#e7e5e4"/>
    <circle cx="147" cy="20" r="8" fill="#d6d3d1"/>
    <path d="M132 45 L157 30 L182 42 L207 25 L227 38 L227 47 L132 47 Z" fill="#d6d3d1"/>
    <rect x="132" y="62" width="90" height="8" rx="1" fill="#292524"/>
    <rect x="132" y="76" width="95" height="4" rx="1" fill="#78716c"/>
    <rect x="132" y="84" width="85" height="4" rx="1" fill="#78716c"/>
    <rect x="132" y="100" width="55" height="5" rx="1" fill="#fcbb46"/>

    <!-- Card 3 -->
    <rect x="244" y="0" width="115" height="120" rx="8" fill="#faf9f8" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="244" y="0" width="115" height="55" rx="8" fill="#e7e5e4"/>
    <circle cx="269" cy="20" r="8" fill="#d6d3d1"/>
    <path d="M254 45 L279 30 L304 42 L329 25 L349 38 L349 47 L254 47 Z" fill="#d6d3d1"/>
    <rect x="254" y="62" width="90" height="8" rx="1" fill="#292524"/>
    <rect x="254" y="76" width="95" height="4" rx="1" fill="#78716c"/>
    <rect x="254" y="84" width="85" height="4" rx="1" fill="#78716c"/>
    <rect x="254" y="100" width="55" height="5" rx="1" fill="#fcbb46"/>
  </g>

  <!-- Label -->
  <text x="200" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">Services</text>
</svg>
```

#### FAQ (Accordion)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Header -->
  <g transform="translate(120, 15)">
    <rect x="40" y="0" width="80" height="12" rx="6" fill="#fcbb46" opacity="0.2"/>
    <rect x="50" y="3" width="60" height="6" rx="2" fill="#fcbb46"/>
    <rect x="10" y="18" width="140" height="10" rx="2" fill="#292524"/>
  </g>

  <!-- FAQ Accordion items -->
  <g transform="translate(60, 50)">
    <!-- Item 1 (expanded) -->
    <rect x="0" y="0" width="280" height="55" rx="6" fill="#ffffff" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="15" y="12" width="180" height="7" rx="1" fill="#292524"/>
    <path d="M255 20 L260 14 L265 20" stroke="#fcbb46" stroke-width="2" stroke-linecap="round" fill="none"/>
    <rect x="15" y="30" width="250" height="4" rx="1" fill="#78716c"/>
    <rect x="15" y="38" width="230" height="4" rx="1" fill="#78716c"/>

    <!-- Item 2 (collapsed) -->
    <rect x="0" y="62" width="280" height="35" rx="6" fill="#ffffff" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="15" y="76" width="200" height="7" rx="1" fill="#292524"/>
    <path d="M255 75 L260 81 L265 75" stroke="#fcbb46" stroke-width="2" stroke-linecap="round" fill="none"/>

    <!-- Item 3 (collapsed) -->
    <rect x="0" y="104" width="280" height="35" rx="6" fill="#ffffff" stroke="#e7e5e4" stroke-width="1"/>
    <rect x="15" y="118" width="170" height="7" rx="1" fill="#292524"/>
    <path d="M255 117 L260 123 L265 117" stroke="#fcbb46" stroke-width="2" stroke-linecap="round" fill="none"/>
  </g>

  <!-- Label -->
  <text x="200" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">FAQ (Accordion)</text>
</svg>
```

#### Galerie

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" fill="none">
  <rect width="400" height="200" fill="#f5f5f4"/>

  <!-- Gallery grid 4 columns -->
  <g transform="translate(20, 25)">
    <!-- Row 1 -->
    <rect x="0" y="0" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="20" cy="18" r="8" fill="#d6d3d1"/>
    <path d="M5 50 L25 35 L45 48 L65 30 L80 45 L80 58 L5 58 Z" fill="#d6d3d1"/>

    <rect x="92" y="0" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="112" cy="18" r="8" fill="#d6d3d1"/>
    <path d="M97 50 L117 35 L137 48 L157 30 L172 45 L172 58 L97 58 Z" fill="#d6d3d1"/>

    <rect x="184" y="0" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="204" cy="18" r="8" fill="#d6d3d1"/>
    <path d="M189 50 L209 35 L229 48 L249 30 L264 45 L264 58 L189 58 Z" fill="#d6d3d1"/>

    <rect x="276" y="0" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="296" cy="18" r="8" fill="#d6d3d1"/>
    <path d="M281 50 L301 35 L321 48 L341 30 L356 45 L356 58 L281 58 Z" fill="#d6d3d1"/>

    <!-- Row 2 -->
    <rect x="0" y="72" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="20" cy="90" r="8" fill="#d6d3d1"/>
    <path d="M5 122 L25 107 L45 120 L65 102 L80 117 L80 130 L5 130 Z" fill="#d6d3d1"/>

    <rect x="92" y="72" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="112" cy="90" r="8" fill="#d6d3d1"/>
    <path d="M97 122 L117 107 L137 120 L157 102 L172 117 L172 130 L97 130 Z" fill="#d6d3d1"/>

    <rect x="184" y="72" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="204" cy="90" r="8" fill="#d6d3d1"/>
    <path d="M189 122 L209 107 L229 120 L249 102 L264 117 L264 130 L189 130 Z" fill="#d6d3d1"/>

    <rect x="276" y="72" width="85" height="64" rx="6" fill="#e7e5e4"/>
    <circle cx="296" cy="90" r="8" fill="#d6d3d1"/>
    <path d="M281 122 L301 107 L321 120 L341 102 L356 117 L356 130 L281 130 Z" fill="#d6d3d1"/>
  </g>

  <!-- Label -->
  <text x="200" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="11" fill="#78716c">Galerie</text>
</svg>
```

---

## 8. Configuration ACF JSON

### 8.1 Structure du champ Flexible Content

Créer un groupe de champs ACF avec :

- **Type** : Flexible Content
- **Nom du champ** : `flexible_content`
- **Affectation** : Pages (ou selon vos besoins)

### 8.2 Champs communs à cloner

Créer un groupe de champs séparé pour les options communes, puis les cloner dans chaque layout :

| Nom du champ | Type | Options |
|--------------|------|---------|
| `couleur_fond` | Select | dark, light, white, primary |
| `espacement` | Select | top, bottom, both, none |
| `format_image` | Radio | paysage, carre, portrait, large |
| `position_image` | Radio | left, right, none |

### 8.3 Exemple de configuration JSON (à adapter)

```json
{
  "key": "group_flexible_content",
  "title": "Contenu flexible",
  "fields": [
    {
      "key": "field_flexible_content",
      "label": "Sections",
      "name": "flexible_content",
      "type": "flexible_content",
      "layouts": {
        "layout_hero": {
          "key": "layout_hero",
          "name": "hero",
          "label": "Hero",
          "sub_fields": [...]
        },
        "layout_texte_image": {
          "key": "layout_texte_image",
          "name": "texte_image",
          "label": "Texte + Image",
          "sub_fields": [...]
        }
      }
    }
  ],
  "location": [
    [
      {
        "param": "page_type",
        "operator": "==",
        "value": "front_page"
      }
    ]
  ]
}
```

---

## 9. Layouts génériques recommandés

Voici une liste de layouts génériques qui couvrent la majorité des besoins :

| Layout | Description | Cas d'usage |
|--------|-------------|-------------|
| **Hero** | Section pleine hauteur avec image de fond | Page d'accueil, landing pages |
| **Texte + Image** | Texte à gauche ou droite avec image | Présentation, À propos |
| **Services** | Grille de cartes | Services, Fonctionnalités |
| **Témoignages** | Carousel de témoignages | Preuves sociales |
| **FAQ** | Accordion de questions/réponses | Support, Documentation |
| **Galerie** | Grille d'images avec lightbox | Portfolio, Photos |
| **Newsletter** | Formulaire d'inscription | Capture d'emails |
| **CTA** | Appel à l'action centré ou avec image | Conversion |
| **Équipe** | Grille de membres d'équipe | À propos |
| **Séparateur** | Image pleine largeur avec parallax | Transition entre sections |
| **Guide pratique** | Sections avec table des matières | Documentation, Guides |
| **Chiffres clés** | Statistiques avec compteurs | Impact, Résultats |
| **Logos partenaires** | Grille/carousel de logos | Confiance |
| **Timeline** | Frise chronologique | Histoire, Processus |
| **Comparatif** | Tableau de comparaison | Offres, Tarifs |
| **Vidéo** | Lecteur vidéo intégré | Présentation, Tutoriels |

---

## 10. Checklist d'implémentation

### Étape 1 : Structure de base
- [ ] Créer le dossier `assets/images/flexible-content/`
- [ ] Créer le dossier `template-parts/flexible-content/`
- [ ] Créer le fichier `inc/Functions/flexible-content.php`
- [ ] Ajouter les fonctions helpers (couleurs, espacement, aspect ratio)
- [ ] Ajouter la fonction de rendu principale (approche template parts)
- [ ] Inclure le fichier dans `functions.php`

### Étape 2 : Configuration ACF
- [ ] Créer le groupe de champs ACF "Contenu flexible"
- [ ] Créer les champs communs à cloner
- [ ] Activer le JSON sync dans `acf-fields/`
- [ ] Configurer les règles d'affichage

### Étape 3 : Système de prévisualisation
- [ ] Ajouter le code JS/CSS pour la popup personnalisée (dans classe ACF ou functions.php)
- [ ] Créer les icônes SVG pour chaque layout (viewbox 400x200)
- [ ] Nommer les fichiers SVG avec le même nom que les layouts ACF (ex: `hero.svg` pour le layout `hero`)

### Étape 4 : Layouts (un fichier par layout)
- [ ] Définir les layouts nécessaires selon le projet
- [ ] Créer un fichier PHP par layout dans `template-parts/flexible-content/`
- [ ] Créer l'icône SVG correspondante dans `assets/images/flexible-content/`
- [ ] Tester chaque layout avec les différentes options (couleurs, espacements, positions)

### Étape 5 : Intégration
- [ ] Appeler `prefix_render_flexible_content()` dans les templates de page
- [ ] Configurer le lazy loading (vanilla-lazyload ou autre)
- [ ] Tester les layouts sur mobile, tablette et desktop
- [ ] Valider l'accessibilité (contrastes, navigation clavier, focus states)

### Ajouter un nouveau layout (après mise en place)
1. Créer `template-parts/flexible-content/nouveau_layout.php`
2. Créer `assets/images/flexible-content/nouveau_layout.svg`
3. Ajouter le layout dans ACF (admin ou JSON)
4. C'est tout ! La fonction de rendu le détecte automatiquement

---

## Prochaine étape : Définition des layouts

> **Important** : Avant de créer les layouts, je dois analyser le projet cible pour :
>
> 1. Scanner la structure existante du projet
> 2. Identifier les besoins spécifiques en termes de contenu
> 3. Proposer une liste de layouts adaptés au contexte
>
> **Interrogez-moi pour que je puisse scanner votre projet et vous proposer les layouts les plus pertinents !**

---

## Ressources

- [ACF Flexible Content](https://www.advancedcustomfields.com/resources/flexible-content/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [vanilla-lazyload](https://github.com/verlok/vanilla-lazyload)
- [GSAP ScrollTrigger](https://greensock.com/scrolltrigger/)

---

*Document généré à partir du thème Lion Select Safaris - Go Lunivers*
