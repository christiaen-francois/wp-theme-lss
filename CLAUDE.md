# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Philosophie générale

- **Performance first** : Chaque décision doit être évaluée sous l'angle performance
- **Code maintenable** : Structure claire, conventions cohérentes, documentation
- **Accessibilité** : WCAG 2.1 AA minimum
- **Mobile-first** : Design et développement mobile-first, desktop en enhancement
- **Progressive enhancement** : Le site fonctionne sans JS, JS améliore l'expérience

---

## Project Overview

Custom WordPress theme for Lion Select Safaris, built with modern frontend tooling (Vite, Tailwind CSS, GSAP, Lenis). Designed for high-performance and Awwwards-level quality.

## Development Commands

```bash
# Start Vite dev server (port 5174)
npm run dev

# Build for production (outputs to /dist)
npm run build

# Preview production build
npm run preview

# Install PHP autoloader
composer dump-autoload
```

**Important**: The Vite dev server must be running for local development. WordPress detects the environment via `wp_get_environment_type()` and loads assets from either Vite (local/development) or the `/dist` folder (production).

---

## Architecture

### Structure des fichiers

```
theme/
├── inc/
│   ├── Classes/          # Classes PHP (PSR-4)
│   ├── Traits/           # Singleton trait
│   ├── Functions/        # Helper functions (flexible-content.php, image-helpers.php, etc.)
│   └── helpers/          # Third-party helpers (TGM Plugin Activation)
├── parts/                 # Composants réutilisables PHP
├── templates/             # Templates personnalisés
├── blocks/                # Gutenberg blocks (si utilisé)
├── acf-fields/            # ACF JSON sync
└── assets/
    ├── js/
    │   ├── main.js        # Point d'entrée
    │   ├── modules/       # Modules JS séparés
    │   └── utils/         # Utilitaires JS
    └── css/
        └── main.css       # Point d'entrée Tailwind
```

### PHP Structure (PSR-4 Autoloading)

- **Namespace**: `LUNIVERS_THEME\Inc\`
- **Singleton Pattern**: All main classes use the `Singleton` trait
- **Entry point**: `functions.php` initializes all classes

Key classes:
- `Theme` - Theme setup, page templates, Gutenberg disabled for pages
- `Assets` - Vite integration, manifest loading, font preloading
- `Acf` - ACF field groups (JSON sync in `/acf-fields`)
- Custom Post Types: `Testimonials`, `FAQ`, `Team`, `Contact`

### JavaScript Architecture

Entry point: `assets/js/main.js`

```javascript
// Initialization order:
1. initPreloader()      // Preloader animation
2. initLenis()          // Smooth scroll (synced with ScrollTrigger)
3. initLazyLoad()       // vanilla-lazyload
4. initScrollAnimations() // GSAP ScrollTrigger animations
5. initNavigation()     // Mobile menu
6. initGlightbox()      // Lightbox
7. initSwiper()         // Carousels
```

Libraries used: GSAP (ScrollTrigger), Lenis, Swiper, GLightbox, vanilla-lazyload

### ACF Flexible Content

The theme uses ACF Flexible Content for page building. Layouts are rendered via `lunivers_render_flexible_content()` in `inc/Functions/flexible-content.php`.

Available layouts: `hero`, `texte_image`, `presentation`, `services`, `temoignages`, `faq`, `galerie`, `cta`, `newsletter`, `equipe`, `guide_pratique`, `separateur`, `separateur_lion`

---

## Conventions de nommage

### PHP

- **Classes** : PascalCase, namespace `LUNIVERS_THEME\Inc\Classes`
- **Fonctions** : snake_case avec préfixe `lunivers_` (ex: `lunivers_get_image_src()`)
- **Constantes** : UPPER_SNAKE_CASE avec préfixe `LUNIVERS_THEME_`
- **Variables** : camelCase pour les variables, snake_case pour les fonctions WordPress
- **Fichiers** : PascalCase pour les classes, kebab-case pour les templates

```php
// BON
class Theme_Setup {
    const MAX_IMAGE_SIZE = 1920;

    public function enqueue_assets() {
        $asset_url = lunivers_get_asset_url('main.js');
    }
}

// MAUVAIS
class theme_setup {
    const maxImageSize = 1920;

    public function EnqueueAssets() {
        $assetUrl = getAssetUrl('main.js');
    }
}
```

### Patterns WordPress

- **Singleton** : Pour les classes principales (Theme, Assets, etc.)
- **Hooks** : Utiliser les hooks WordPress, éviter les modifications directes du core
- **Transients** : Pour le cache des requêtes coûteuses
- **Nonces** : Pour toutes les actions utilisateur
- **Sanitization** : Toujours sanitizer les entrées utilisateur
- **Escaping** : Toujours échapper les sorties

```php
// BON - Utilisation des hooks
add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
add_filter('excerpt_length', [$this, 'custom_excerpt_length']);

// BON - Sanitization et escaping
$user_input = sanitize_text_field($_POST['name']);
echo esc_html($user_input);
echo esc_url($link);
echo esc_attr($attribute);
```

---

## Tailwind CSS - Conventions

### Structure des classes

**Ordre recommandé** (pour lisibilité) :

1. Layout (display, position, top/right/bottom/left, z-index)
2. Flexbox/Grid (flex, grid, gap)
3. Spacing (margin, padding)
4. Sizing (width, height, max-width, etc.)
5. Typography (font, text, line-height, letter-spacing)
6. Visual (background, border, shadow, opacity)
7. Misc (cursor, pointer-events, user-select)

```html
<!-- BON - Ordre logique -->
<div class="relative z-10 flex items-center gap-4 p-6 w-full max-w-5xl text-lg font-semibold bg-neutral-900 border border-neutral-800 rounded-lg shadow-xl">

<!-- MAUVAIS - Désordonné -->
<div class="bg-neutral-900 flex p-6 relative text-lg gap-4 z-10 items-center">
```

### Design tokens personnalisés

**Toujours étendre le thème Tailwind** plutôt que d'utiliser des valeurs arbitraires.

Custom color palette in `tailwind.config.cjs`:
- `primary` - Golden/orange (lion theme)
- `brown` - Dark browns
- `cream` - Light backgrounds
- `neutral` - Grays

Font: Geist (variable font loaded via Google Fonts)

```html
<!-- BON - Utiliser les tokens -->
<div class="bg-primary-500 text-neutral-50 font-display">

<!-- MAUVAIS - Valeurs arbitraires -->
<div class="bg-[#ff6b6b] text-[#fafafa] font-['Space_Grotesk']">
```

### Responsive design

**Mobile-first obligatoire** :

```html
<!-- BON - Mobile-first -->
<div class="text-sm md:text-base lg:text-lg xl:text-xl">
  <div class="p-4 md:p-6 lg:p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

<!-- MAUVAIS - Desktop-first -->
<div class="text-xl lg:text-lg md:text-base text-sm">
```

### Component classes

- Préférer les classes utilitaires sauf pour les patterns répétitifs
- Component classes defined in `@layer components` in `main.css`

---

## Performance - Règles strictes

### Images

- **Toujours utiliser `wp_get_attachment_image()`** avec les bonnes tailles
- **Lazy loading natif** : `loading="lazy"` (sauf above-the-fold)
- **Formats modernes** : WebP avec fallback, AVIF si supporté
- **Responsive images** : `srcset` et `sizes` automatiques
- **Aspect ratio** : Utiliser `aspect-ratio` CSS pour éviter le layout shift
- Use `data-src` for lazy loading with vanilla-lazyload
- Include width/height attributes to prevent layout shift

```php
// BON
echo wp_get_attachment_image(
    $image_id,
    'large',
    false,
    [
        'class' => 'w-full h-auto',
        'loading' => 'lazy',
        'decoding' => 'async',
    ]
);
```

### JavaScript

- **Code splitting** : Séparer les modules par fonctionnalité
- **Lazy loading** : Charger les modules non-critiques à la demande
- **Debounce/Throttle** : Pour les événements scroll/resize
- **RequestAnimationFrame** : Pour les animations fluides
- **Pas de jQuery** : Utiliser le vanilla JS moderne
- ES6 modules only
- Async/await for promises
- Clean up ScrollTrigger instances on page transitions

```javascript
// BON - Code splitting
const initHeavyFeature = async () => {
  const { HeavyModule } = await import("./modules/HeavyModule");
  HeavyModule.init();
};

// BON - Debounce pour scroll
import { debounce } from "./utils/debounce";
window.addEventListener("scroll", debounce(handleScroll, 100));
```

### CSS

- **PurgeCSS** : Configuré correctement dans Tailwind
- **Critical CSS** : Inline pour above-the-fold
- **Pas de CSS inline** : Sauf pour les valeurs dynamiques critiques
- **Utiliser les custom properties** : Pour les valeurs dynamiques

### Fonts

- **Variable fonts** : Préférer les fonts variables
- **Preload** : Pour les fonts critiques
- **font-display: swap** : Toujours
- **Subset** : Charger uniquement les caractères nécessaires

```php
// BON - Preload des fonts critiques
add_action('wp_head', function() {
    echo '<link rel="preload" href="' . get_theme_file_uri('assets/fonts/inter.woff2') . '" as="font" type="font/woff2" crossorigin>';
}, 1);
```

### Performance cibles (Awwwards)

- **Lighthouse Performance** : > 95
- **First Contentful Paint** : < 1.5s
- **Largest Contentful Paint** : < 2.5s
- **Time to Interactive** : < 3.5s
- **Cumulative Layout Shift** : < 0.1
- **Total Bundle Size** : < 200KB (gzipped)
- All animations at 60fps

---

## Animations et transitions

### GSAP - Bonnes pratiques

- **ScrollTrigger** : Utiliser pour les animations au scroll
- **Performance** : Préférer `transform` et `opacity` (GPU-accelerated)
- **Will-change** : Utiliser avec parcimonie, retirer après animation
- **Cleanup** : Toujours nettoyer les ScrollTriggers au unmount

```javascript
// BON - Animation performante
gsap.from(element, {
  autoAlpha: 0, // opacity + visibility
  y: 40, // transform (GPU)
  duration: 1,
  ease: "power3.out",
  scrollTrigger: {
    trigger: element,
    start: "top 80%",
    toggleActions: "play none none reverse",
  },
  onComplete: () => {
    element.style.willChange = "auto"; // Cleanup
  },
});
```

### Lenis (Smooth scroll)

- **Toujours synchroniser avec ScrollTrigger** : `lenis.on('scroll', ScrollTrigger.update)`
- **Pause pendant les transitions** : `lenis.stop()` / `lenis.start()`
- **Configuration** : Ajuster `lerp` selon le projet (0.1-0.15 généralement)

```javascript
// BON - Synchronisation Lenis/ScrollTrigger
lenis.on("scroll", ScrollTrigger.update);

// BON - Pause pendant transition Barba
barba.hooks.beforeLeave(() => {
  lenis.stop();
});

barba.hooks.afterEnter(() => {
  lenis.start();
  window.scrollTo(0, 0);
});
```

### Barba.js (Page transitions)

- **Namespace** : Toujours définir un namespace unique par page
- **Cleanup** : Nettoyer les event listeners et animations au leave
- **Scroll position** : Réinitialiser après transition
- **Performance** : Précharger les pages au hover (optionnel)

### Micro-interactions

- **Feedback immédiat** : Tous les éléments interactifs doivent avoir un feedback
- **Transitions fluides** : Utiliser des easing functions naturelles
- **Hover states** : Toujours définis pour desktop
- **Respect prefers-reduced-motion** : Toujours vérifier

```css
/* BON - Respect de prefers-reduced-motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## Accessibilité

### HTML sémantique

```html
<!-- BON - Structure sémantique -->
<header role="banner">
  <nav aria-label="Navigation principale">
    <ul>
      <li><a href="/">Accueil</a></li>
    </ul>
  </nav>
</header>

<main role="main">
  <article>
    <h1>Titre de l'article</h1>
  </article>
</main>

<footer role="contentinfo"></footer>
```

### ARIA et navigation clavier

- **Skip links** : Toujours ajouter un lien "Aller au contenu"
- **Focus visible** : Styles de focus clairs et visibles
- **Landmarks** : Utiliser les rôles ARIA appropriés
- **Labels** : Toujours labeliser les éléments interactifs

```html
<!-- BON - Skip link -->
<a
  href="#main-content"
  class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-white"
>
  Aller au contenu
</a>

<!-- BON - Focus visible -->
button:focus-visible { @apply outline-2 outline-offset-2 outline-primary; }
```

### Contraste et couleurs

- **WCAG AA** : Minimum 4.5:1 pour le texte normal, 3:1 pour le texte large
- **Pas de couleur seule** : Ne pas utiliser uniquement la couleur pour transmettre l'information
- **Focus states** : Toujours visibles

---

## Code quality

### PHP

- **PSR-12** : Standards de codage PHP
- **Type hints** : Utiliser les type hints partout
- **Docblocks** : Documenter les fonctions publiques
- **DRY** : Don't Repeat Yourself
- **Single Responsibility** : Une classe = une responsabilité

```php
// BON - Type hints et docblocks
/**
 * Enqueue les assets du thème
 *
 * @param string $handle Le handle de l'asset
 * @param string $src    L'URL source
 * @param array  $deps   Les dépendances
 * @return void
 */
public function enqueue_asset(string $handle, string $src, array $deps = []): void {
    wp_enqueue_script($handle, $src, $deps, LUNIVERS_THEME_VERSION, true);
}
```

### JavaScript

- **ES6+** : Utiliser les syntaxes modernes
- **Modules** : Tout en modules ES6
- **Const/Let** : Jamais de `var`
- **Arrow functions** : Pour les callbacks courts
- **Async/Await** : Préférer à Promise.then()
- **Error handling** : Toujours gérer les erreurs

```javascript
// BON - Code moderne
const fetchData = async (url) => {
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error("Network error");
    return await response.json();
  } catch (error) {
    console.error("Error fetching data:", error);
    return null;
  }
};
```

### Commentaires

- **Pourquoi, pas quoi** : Expliquer la raison, pas l'action
- **TODOs** : Marquer les TODOs avec contexte et priorité
- **Complexité** : Commenter les algorithmes complexes

---

## Anti-patterns à éviter

### PHP

```php
// MAUVAIS - Requête dans la boucle
while (have_posts()) {
    the_post();
    $meta = get_post_meta(get_the_ID(), 'custom_field', true); // N+1 queries
}

// BON - Requête unique
$posts = get_posts(['meta_key' => 'custom_field']);
foreach ($posts as $post) {
    // ...
}
```

### JavaScript

```javascript
// MAUVAIS - Manipulation DOM excessive
for (let i = 0; i < 1000; i++) {
  element.innerHTML += "<div>" + i + "</div>";
}

// BON - Batch DOM updates
const fragment = document.createDocumentFragment();
for (let i = 0; i < 1000; i++) {
  const div = document.createElement("div");
  div.textContent = i;
  fragment.appendChild(div);
}
element.appendChild(fragment);
```

### CSS

```html
<!-- MAUVAIS - Styles inline (sauf valeurs dynamiques) -->
<div style="color: red; padding: 20px;">

<!-- MAUVAIS - !important (sauf cas très spécifiques) -->
.text { color: red !important; }
```

---

## Vite Configuration

- Local domain: `lion-select-safaris.lunivers` (configured in `vite.config.mjs`)
- Port: 5174
- Manifest path: `/dist/.vite/manifest.json`

---

## Checklist de déploiement

- [ ] Tous les assets compilés (`npm run build`)
- [ ] Manifest Vite généré
- [ ] Images optimisées
- [ ] Fonts préchargées
- [ ] Cache configuré
- [ ] 404 et erreurs gérées
- [ ] Mobile testé
- [ ] Cross-browser testé (Chrome, Firefox, Safari)
- [ ] Lighthouse score > 90
- [ ] axe DevTools, aucun warning

---

## Règle d'or

> **"Si tu doutes, choisis la solution la plus performante et la plus maintenable, pas la plus rapide à coder."**
