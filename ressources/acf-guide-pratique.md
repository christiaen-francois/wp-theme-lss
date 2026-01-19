# Configuration ACF - Layout Guide Pratique

## Structure du layout

Le layout `guide_pratique` permet de créer des guides pratiques avec table des matières et navigation par ancres.

## Champs ACF requis

### Layout principal

**Nom du layout** : `guide_pratique`  
**Label** : Guide Pratique

### Champs du layout

1. **Titre** (Text)
   - Field Name: `titre`
   - Type: Text
   - Label: Titre principal
   - Instructions: Titre principal du guide

2. **Sous-titre** (Text)
   - Field Name: `sous_titre`
   - Type: Text
   - Label: Sous-titre
   - Instructions: Sous-titre optionnel (affiché au-dessus du titre)
   - Required: No

3. **Afficher table des matières** (True/False)
   - Field Name: `afficher_table_matiere`
   - Type: True/False
   - Label: Afficher la table des matières
   - Default Value: Yes
   - Instructions: Affiche la table des matières sticky sur desktop (masquée si moins de 2 sections)

4. **Couleur de fond** (Select)
   - Field Name: `couleur_fond`
   - Type: Select
   - Label: Couleur de fond
   - Choices:
     - `white` : Blanc
     - `light` : Crème clair
     - `dark` : Brun foncé
     - `primary` : Primaire
   - Default Value: `white`

5. **Sections** (Repeater)
   - Field Name: `sections`
   - Type: Repeater
   - Label: Sections
   - Button Label: Ajouter une section
   - Min: 1
   - Layout: Block
   - Sub Fields:
     - **Titre** (Text)
       - Field Name: `titre`
       - Type: Text
       - Label: Titre de la section
       - Required: Yes
       - Instructions: Le titre sera utilisé pour générer l'ancre automatiquement
     
     - **Contenu** (WYSIWYG)
       - Field Name: `contenu`
       - Type: WYSIWYG Editor
       - Label: Contenu
       - Required: Yes
       - Toolbar: Full
       - Media Upload: Yes
     
     - **Image** (Image)
       - Field Name: `image`
       - Type: Image
       - Label: Image
       - Required: No
       - Return Format: Image Array
       - Preview Size: Medium
       - Instructions: Image optionnelle pour illustrer la section
     
     - **Position de l'image** (Select)
       - Field Name: `position_image`
       - Type: Select
       - Label: Position de l'image
       - Choices:
         - `top` : En haut
         - `left` : À gauche
         - `right` : À droite
         - `none` : Aucune image
       - Default Value: `none`
       - Conditional Logic: Afficher si `image` n'est pas vide
     
     - **Icône** (Textarea)
       - Field Name: `icone`
       - Type: Textarea
       - Label: Icône (SVG)
       - Required: No
       - Instructions: Code SVG pour une icône personnalisée (affichée à côté du titre)
       - Rows: 3

## Exemple de configuration

### Section 1 : Les vols
- **Titre** : Les vols
- **Contenu** : 
  ```
  Nous nous occupons de tout pour vous sauf de la réservation des vols internationaux...
  
  - Commencez par consulter un comparateur de vols...
  - Ensuite, rendez-vous directement sur le site...
  ```
- **Image** : (optionnelle)
- **Position** : `top`
- **Icône** : (optionnelle, code SVG)

### Section 2 : Santé
- **Titre** : Santé
- **Contenu** : 
  ```
  La vaccination contre la fièvre jaune...
  ```
- **Image** : (optionnelle)
- **Position** : `none`

## Notes importantes

1. **Génération automatique des ancres** : Les IDs des sections sont générés automatiquement à partir du titre. Si deux sections ont le même titre, un suffixe numérique sera ajouté.

2. **Table des matières** : 
   - Affichée uniquement si `afficher_table_matiere` est activé ET qu'il y a au moins 2 sections
   - Sticky sur desktop (colonne de gauche)
   - Masquée sur mobile (mais les ancres fonctionnent toujours)

3. **Navigation** :
   - Les liens de la table des matières utilisent le scroll smooth (Lenis si disponible)
   - L'état actif est mis à jour automatiquement lors du scroll
   - Les ancres sont ajoutées à l'URL (#section-id)

4. **Responsive** :
   - Sur mobile : table des matières masquée, contenu pleine largeur
   - Sur desktop : table des matières sticky à gauche (3 colonnes), contenu à droite (9 colonnes)

5. **Accessibilité** :
   - Navigation ARIA labelée
   - Ancres avec `scroll-mt-*` pour offset du header
   - Support du clavier complet

## Intégration dans le Flexible Content

Le layout doit être ajouté au groupe de champs Flexible Content :

1. Aller dans ACF > Field Groups
2. Trouver le groupe "Flexible Content" (ou celui utilisé pour les pages)
3. Ajouter un nouveau layout "Guide Pratique"
4. Configurer les champs comme décrit ci-dessus
5. Le layout sera disponible dans l'éditeur de page

## Utilisation

1. Créer ou éditer une page
2. Dans le Flexible Content, ajouter un bloc "Guide Pratique"
3. Remplir le titre et sous-titre (optionnel)
4. Ajouter les sections avec leurs contenus
5. Publier la page

Le layout sera automatiquement rendu avec la table des matières et la navigation fonctionnelle.

