/**
 * Smart Sticky Header
 *
 * Comportement :
 * - Le header est toujours visible en haut de page (< 200px)
 * - Au scroll vers le bas, le header se cache
 * - Au scroll vers le haut, le header réapparaît
 * - Animation fluide avec transition CSS
 * - Compatible avec Lenis smooth scroll
 */

export function initSmartHeader() {
  const header = document.querySelector("[data-smart-header]");
  const headerSpacer = document.getElementById("header-spacer");

  if (!header) return;

  // Configuration
  const config = {
    threshold: 200, // Distance avant d'activer le comportement show/hide
    scrollDelta: 5, // Sensibilité minimale pour détecter un changement de direction
  };

  // État
  let isHeaderVisible = true;
  let lastScrollY = 0;
  let ticking = false;

  // Fonction pour mettre à jour la hauteur du spacer
  function updateSpacerHeight() {
    if (headerSpacer) {
      const headerHeight = header.offsetHeight;
      headerSpacer.style.height = `${headerHeight}px`;
    }
  }

  // Fonction pour montrer le header
  function showHeader() {
    if (!isHeaderVisible) {
      header.style.transform = "translateY(0)";
      header.classList.remove("header-hidden");
      header.classList.add("header-visible");
      isHeaderVisible = true;
    }
  }

  // Fonction pour cacher le header
  function hideHeader() {
    if (isHeaderVisible) {
      header.style.transform = "translateY(-100%)";
      header.classList.add("header-hidden");
      header.classList.remove("header-visible");
      isHeaderVisible = false;
    }
  }

  // Fonction de gestion du scroll (fonctionne avec ou sans Lenis)
  function updateHeader() {
    const currentScrollY = window.scrollY;

    // Si on est en haut de page, toujours montrer le header
    if (currentScrollY < config.threshold) {
      showHeader();
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    const scrollDiff = currentScrollY - lastScrollY;

    // Scroll vers le haut
    if (scrollDiff < -config.scrollDelta) {
      showHeader();
      lastScrollY = currentScrollY;
    }
    // Scroll vers le bas
    else if (scrollDiff > config.scrollDelta) {
      hideHeader();
      lastScrollY = currentScrollY;
    }

    ticking = false;
  }

  // Utiliser requestAnimationFrame pour optimiser
  function onScroll() {
    if (!ticking) {
      requestAnimationFrame(updateHeader);
      ticking = true;
    }
  }

  // Initialisation
  updateSpacerHeight();

  // Toujours utiliser le scroll natif (compatible avec Lenis qui modifie window.scrollY)
  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", updateSpacerHeight);

  // Exposer des méthodes pour une utilisation externe
  window.smartHeader = {
    show: showHeader,
    hide: hideHeader,
    updateSpacer: updateSpacerHeight,
  };

  // Émettre un événement quand le smart header est initialisé
  window.dispatchEvent(new CustomEvent("smartHeaderReady"));
}
