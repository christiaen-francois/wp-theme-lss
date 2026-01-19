/**
 * Preloader Module
 * Animation d'entrée élégante pour la page avec animation SVG
 * S'affiche uniquement lors de la première visite de la session
 *
 * @package lunivers-theme
 */

import gsap from "gsap";

let preloaderTimeline = null;

/**
 * Initialise le préloader
 */
export function initPreloader() {
  const preloader = document.querySelector("#preloader");
  const mainContent = document.querySelector("#page");

  if (!preloader) {
    return;
  }

  // Vérifier si le préloader a déjà été affiché dans cette session
  const preloaderShown = sessionStorage.getItem("preloaderShown");

  if (preloaderShown === "true") {
    // Si déjà affiché, masquer immédiatement le préloader et afficher le contenu
    gsap.set(preloader, { display: "none" });
    if (mainContent) {
      gsap.set(mainContent, { autoAlpha: 1 });
    }
    // Déclencher l'événement personnalisé de manière asynchrone
    // pour laisser le temps au DOM de se mettre à jour
    requestAnimationFrame(() => {
      window.dispatchEvent(new CustomEvent("preloaderComplete"));
    });
    return;
  }

  // Créer la timeline du préloader
  preloaderTimeline = gsap.timeline({
    onComplete: () => {
      // Masquer le préloader après l'animation
      gsap.set(preloader, { display: "none" });
      // Marquer dans sessionStorage que le préloader a été affiché
      sessionStorage.setItem("preloaderShown", "true");
      // Déclencher l'événement personnalisé pour indiquer que le préloader est terminé
      window.dispatchEvent(new CustomEvent("preloaderComplete"));
    },
  });

  const svg = preloader.querySelector("#preloader-logo-svg");
  const logoContainer = preloader.querySelector(".preloader-logo");

  if (svg) {
    // Animation du conteneur du logo
    gsap.set(logoContainer, { autoAlpha: 0, scale: 0.95 });
    preloaderTimeline.to(logoContainer, {
      autoAlpha: 1,
      scale: 1,
      duration: 0.5,
      ease: "power2.out",
    });

    // Animer les groupes SVG (g) avec stagger progressif
    const groups = svg.querySelectorAll("g");
    if (groups.length > 0) {
      // Séparer le groupe "lion" des autres groupes
      const lionGroup = svg.querySelector('g[id="lion"]');
      const otherGroups = Array.from(groups).filter((g) => g.id !== "lion");

      // Masquer uniquement les éléments des autres groupes (pas ceux du lion)
      const lionPaths = lionGroup
        ? lionGroup.querySelectorAll("path, rect")
        : [];
      const otherElements = [];
      otherGroups.forEach((group) => {
        otherElements.push(...group.querySelectorAll("path, rect"));
      });

      // Masquer les éléments des autres groupes
      if (otherElements.length > 0) {
        gsap.set(otherElements, {
          autoAlpha: 0,
          scale: 0.85,
          transformOrigin: "center center",
        });
      }

      // Masquer le groupe lion initialement (mais pas les paths individuels)
      if (lionGroup) {
        gsap.set(lionGroup, {
          autoAlpha: 0,
          scale: 0.85,
          transformOrigin: "center center",
        });

        // Animer le groupe lion en entier (pas les paths individuels)
        preloaderTimeline.to(lionGroup, {
          autoAlpha: 1,
          scale: 1,
          duration: 0.8,
          ease: "power2.out",
        });
      }

      // Animer les autres groupes (texte) path par path
      otherGroups.forEach((group, groupIndex) => {
        const elements = group.querySelectorAll("path, rect");

        // Animation pour chaque groupe de texte
        preloaderTimeline.to(
          elements,
          {
            autoAlpha: 1,
            scale: 1,
            duration: 0.7,
            ease: "power2.out",
            stagger: {
              amount: 0.4, // Délai total pour tous les éléments du groupe
              from: "start",
            },
          },
          lionGroup ? groupIndex * 0.2 + 0.3 : groupIndex * 0.2 // Délai après le lion
        );
      });

      // Animer les rectangles (lignes décoratives) après les groupes
      // Ne prendre que les rectangles qui ne sont pas dans un groupe
      const allRects = svg.querySelectorAll("rect");
      const rectsOutsideGroups = Array.from(allRects).filter((rect) => {
        // Vérifier si le rectangle n'est pas dans un groupe
        return !rect.closest("g");
      });

      if (rectsOutsideGroups.length > 0) {
        // Masquer les rectangles initialement
        gsap.set(rectsOutsideGroups, {
          autoAlpha: 0,
          scaleX: 0,
        });

        preloaderTimeline.to(
          rectsOutsideGroups,
          {
            autoAlpha: 1,
            scaleX: 1,
            duration: 0.6,
            ease: "power2.out",
            stagger: 0.1,
          },
          "-=0.3"
        );
      }
    } else {
      // Si pas de groupes, animer directement tous les paths
      const paths = svg.querySelectorAll("path, rect");
      if (paths.length > 0) {
        gsap.set(paths, {
          autoAlpha: 0,
          scale: 0.85,
          transformOrigin: "center center",
        });
        preloaderTimeline.to(paths, {
          autoAlpha: 1,
          scale: 1,
          duration: 0.8,
          ease: "power2.out",
          stagger: {
            amount: 0.6,
            from: "start",
          },
        });
      }
    }
  } else {
    // Fallback si pas de SVG (texte)
    if (logoContainer) {
      preloaderTimeline.from(logoContainer, {
        scale: 0.8,
        autoAlpha: 0,
        duration: 0.8,
        ease: "power3.out",
      });
    }
  }

  // Animation de sortie
  preloaderTimeline.to(
    preloader,
    {
      autoAlpha: 0,
      duration: 0.6,
      ease: "power2.in",
    },
    "-=0.3"
  );

  // Animation du contenu principal (fade in)
  if (mainContent) {
    gsap.set(mainContent, { autoAlpha: 0 });
    preloaderTimeline.to(
      mainContent,
      {
        autoAlpha: 1,
        duration: 0.8,
        ease: "power2.out",
      },
      "-=0.4"
    );
  }
}

/**
 * Détruit le préloader (cleanup)
 */
export function destroyPreloader() {
  if (preloaderTimeline) {
    preloaderTimeline.kill();
    preloaderTimeline = null;
  }
}
