# Lion Select Safaris - Thème WordPress

Thème WordPress sur mesure pour Lion Select Safaris, développé avec des outils frontend modernes et optimisé pour la performance.

## Stack technique

- **WordPress** - CMS
- **Vite** - Bundler & serveur de développement
- **Tailwind CSS** - Framework CSS utility-first
- **GSAP** - Animations & ScrollTrigger
- **Lenis** - Smooth scrolling
- **Swiper** - Carrousels
- **GLightbox** - Lightbox pour galeries
- **ACF Pro** - Champs personnalisés & Flexible Content

## Prérequis

- PHP 8.0+
- Node.js 18+
- Composer
- WordPress 6.0+
- Plugin ACF Pro

## Installation

1. Cloner le dépôt dans le répertoire des thèmes WordPress :

```bash
cd wp-content/themes/
git clone https://github.com/your-username/lion-select-safaris.git
```

2. Installer les dépendances :

```bash
npm install
composer install
```

3. Activer le thème dans l'administration WordPress.

## Développement

Lancer le serveur de développement Vite :

```bash
npm run dev
```

Le serveur tourne sur le port **5174**. WordPress détecte automatiquement l'environnement et charge les assets depuis Vite en mode développement.

## Build de production

Compiler les assets optimisés pour la production :

```bash
npm run build
```

Les fichiers CSS/JS minifiés sont générés dans le dossier `/dist` avec des hash pour le cache-busting.

## Déploiement

Le thème utilise GitHub Actions pour le CI/CD. À chaque push sur `main` ou `master` :

1. Les assets sont compilés avec Vite
2. Les dépendances Composer sont installées (production uniquement)
3. Le thème est déployé via FTP

### Secrets requis

À configurer dans les paramètres du dépôt GitHub :

| Secret | Description |
|--------|-------------|
| `FTP_SERVER` | Adresse du serveur FTP |
| `FTP_USERNAME` | Nom d'utilisateur FTP |
| `FTP_PASSWORD` | Mot de passe FTP |
| `FTP_SERVER_DIR` | Chemin du thème sur le serveur |

## Structure du projet

```
theme/
├── assets/
│   ├── css/          # Point d'entrée Tailwind
│   └── js/           # Modules JavaScript
├── inc/
│   ├── Classes/      # Classes PHP (PSR-4)
│   ├── Functions/    # Fonctions utilitaires
│   └── Traits/       # Trait Singleton
├── parts/            # Composants réutilisables
├── templates/        # Templates de pages
├── acf-fields/       # Synchronisation JSON ACF
└── dist/             # Build de production
```

## Objectifs de performance

- Lighthouse Performance : > 95
- First Contentful Paint : < 1.5s
- Largest Contentful Paint : < 2.5s
- Cumulative Layout Shift : < 0.1

## Licence

Privé - Tous droits réservés.
# wp-theme-lss
