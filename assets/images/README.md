# Dossier Images du Thème

Ce dossier contient les images statiques du thème (logos, icônes, placeholders, etc.).

## Structure recommandée

```
assets/images/
├── logos/          # Logos du site
├── icons/          # Icônes SVG
├── placeholders/   # Images placeholder
└── flexible-content/ # Images pour les previews de layouts ACF (optionnel)
```

## Utilisation dans le code

### En PHP
```php
// Utiliser get_template_directory_uri() pour obtenir l'URL
$image_url = get_template_directory_uri() . '/assets/images/logos/logo.svg';
echo '<img src="' . esc_url( $image_url ) . '" alt="Logo">';
```

### En JavaScript
```javascript
// Utiliser une variable globale ou importer directement
const imageUrl = themeData.imagesUrl + '/logos/logo.svg';
```

### En CSS
```css
/* Utiliser url() avec le chemin relatif */
.logo {
  background-image: url('../images/logos/logo.svg');
}
```

## Note importante

Les images uploadées par les utilisateurs via WordPress (médias) sont stockées dans `/wp-content/uploads/` et gérées par WordPress.

Ce dossier est uniquement pour les images statiques du thème qui ne changent pas.

