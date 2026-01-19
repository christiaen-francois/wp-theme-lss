/**
 * LazyLoad Module
 * Initialise vanilla-lazyload pour optimiser le chargement des images
 *
 * @package lunivers-theme
 */

import LazyLoad from "vanilla-lazyload";

let lazyLoadInstance = null;

/**
 * Initialise LazyLoad avec configuration optimisée
 */
export function initLazyLoad() {
  // Vérifier si LazyLoad est déjà initialisé
  if (lazyLoadInstance) {
    return lazyLoadInstance;
  }

  // Configuration optimisée pour performance
  lazyLoadInstance = new LazyLoad({
    // Sélecteur des éléments à lazy load
    elements_selector: ".lazy",
    
    // Délai avant chargement (en pixels avant l'entrée dans le viewport)
    threshold: 300,
    
    // Utiliser le lazy loading natif si supporté
    use_native: true,
    
    // Charger les images même si le navigateur ne supporte pas le scroll
    // (pour les bots de recherche)
    load_delay: 0,
    
    // Classes CSS
    class_loading: "lazy-loading",
    class_loaded: "lazy-loaded",
    class_error: "lazy-error",
    
    // Callbacks
    callback_loaded: (element) => {
      // Pour les images de fond (data-bg)
      if (element.dataset.bg) {
        // Créer une image pour précharger
        const img = new Image();
        img.onload = () => {
          element.style.backgroundImage = `url(${element.dataset.bg})`;
          element.classList.add("opacity-0");
          requestAnimationFrame(() => {
            element.classList.add("transition-opacity", "duration-500");
            element.classList.remove("opacity-0");
            element.classList.add("opacity-100");
          });
        };
        img.onerror = () => {
          console.warn("LazyLoad: Erreur de chargement du background", element);
        };
        img.src = element.dataset.bg;
        return; // Ne pas continuer pour les backgrounds
      }
      
      // Pour les images normales, s'assurer que les transitions sont préservées
      if (element.tagName === "IMG") {
        // Préserver les classes de transition existantes si elles n'existent pas
        const hasTransform = element.classList.contains("transition-transform") || 
                            element.classList.contains("group-hover:scale-110");
        if (!hasTransform && element.closest(".group")) {
          // Si l'image est dans un groupe avec hover, ajouter les transitions
          element.classList.add("transition-transform", "duration-500", "ease-out");
        }
      }
      
      // Animation fade-in quand l'image est chargée (seulement si pas déjà chargée)
      if (!element.classList.contains("lazy-loaded")) {
        element.classList.add("opacity-0");
        requestAnimationFrame(() => {
          element.classList.add("transition-opacity", "duration-500");
          element.classList.remove("opacity-0");
          element.classList.add("opacity-100");
        });
      }
    },
    
    callback_error: (element) => {
      console.warn("LazyLoad: Erreur de chargement", element);
      // Optionnel: afficher une image placeholder en cas d'erreur
      if (element.dataset.placeholder) {
        element.src = element.dataset.placeholder;
      }
    },
  });

  // Gérer les backgrounds avec data-bg manuellement (vanilla-lazyload ne les détecte pas automatiquement)
  if ("IntersectionObserver" in window) {
    const bgObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && entry.target.dataset.bg) {
            const element = entry.target;
            const bgUrl = element.dataset.bg;
            
            // Vérifier si déjà chargé
            if (element.style.backgroundImage.includes(bgUrl)) {
              return;
            }
            
            // Précharger l'image
            const img = new Image();
            img.onload = () => {
              element.style.backgroundImage = `url(${bgUrl})`;
              element.classList.add("lazy-loaded");
            };
            img.onerror = () => {
              console.warn("LazyLoad: Erreur de chargement du background", element);
              element.classList.add("lazy-error");
            };
            img.src = bgUrl;
            
            bgObserver.unobserve(element);
          }
        });
      },
      {
        rootMargin: "300px", // Charger 300px avant l'entrée dans le viewport
      }
    );

    // Observer tous les éléments avec data-bg
    document.querySelectorAll(".lazy[data-bg]").forEach((el) => {
      bgObserver.observe(el);
    });

    // Observer les nouveaux éléments ajoutés dynamiquement
    const bgMutationObserver = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
          if (node.nodeType === 1) {
            // Element node
            if (node.classList && node.classList.contains("lazy") && node.dataset.bg) {
              bgObserver.observe(node);
            }
            // Vérifier aussi les enfants
            node.querySelectorAll?.(".lazy[data-bg]").forEach((el) => {
              bgObserver.observe(el);
            });
          }
        });
      });
    });

    bgMutationObserver.observe(document.body, {
      childList: true,
      subtree: true,
    });
  }

  // Mettre à jour LazyLoad après les transitions de page (si Barba.js est utilisé)
  if (window.barba) {
    window.barba.hooks.afterEnter(() => {
      lazyLoadInstance.update();
    });
  }

  return lazyLoadInstance;
}

/**
 * Met à jour LazyLoad (utile après injection de contenu dynamique)
 */
export function updateLazyLoad() {
  if (lazyLoadInstance) {
    lazyLoadInstance.update();
  }
}

/**
 * Détruit l'instance LazyLoad (cleanup)
 */
export function destroyLazyLoad() {
  if (lazyLoadInstance) {
    lazyLoadInstance.destroy();
    lazyLoadInstance = null;
  }
}

