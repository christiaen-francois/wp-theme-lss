import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

/**
 * Initialise l'effet parallax sur les headers avec images de fond
 * Compatible avec Lenis smooth scroll
 */
export function initParallax() {
  // Respecter prefers-reduced-motion
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;

  if (prefersReducedMotion) {
    return;
  }

  // Sélectionner tous les headers avec background image parallax
  const parallaxSections = document.querySelectorAll(
    ".layout-hero, .page-hero, .layout-separateur, .layout-cta"
  );

  parallaxSections.forEach((section) => {
    // Trouver l'élément background avec la classe parallax-bg
    const bgElement = section.querySelector(".parallax-bg");

    if (!bgElement) return;

    // Configurer le parallax sur cet élément
    setupParallaxElement(section, bgElement);
  });

  // Hero slider (front-page) - chaque slide a son propre parallax
  const heroSlider = document.querySelector(".hero-slider");
  if (heroSlider) {
    const slides = heroSlider.querySelectorAll(".swiper-slide");
    slides.forEach((slide) => {
      const bgElement = slide.querySelector(".parallax-bg");
      if (bgElement) {
        // Pour les slides, on utilise le slider comme trigger
        setupParallaxElement(heroSlider, bgElement);
      }
    });
  }

  // Rafraîchir ScrollTrigger après setup
  ScrollTrigger.refresh();
}

/**
 * Configure l'effet parallax sur un élément
 */
function setupParallaxElement(section, bgElement) {
  // Configuration du parallax
  const parallaxIntensity = 50; // Pixels de déplacement total

  // Position initiale : l'image commence légèrement remontée
  // pour qu'en scrollant vers le bas elle descende
  gsap.set(bgElement, {
    y: -parallaxIntensity,
  });

  // Animation parallax avec ScrollTrigger
  gsap.to(bgElement, {
    y: parallaxIntensity,
    ease: "none",
    scrollTrigger: {
      trigger: section,
      start: "top bottom",
      end: "bottom top",
      scrub: true, // Synchronisation smooth avec le scroll (Lenis compatible)
      invalidateOnRefresh: true, // Recalculer sur resize
    },
  });
}

/**
 * Nettoie les instances ScrollTrigger pour le parallax
 * Utile pour les transitions de page ou le cleanup
 */
export function destroyParallax() {
  ScrollTrigger.getAll().forEach((trigger) => {
    if (trigger.vars.trigger) {
      const triggerElement = trigger.vars.trigger;
      if (
        triggerElement.classList &&
        (triggerElement.classList.contains("layout-hero") ||
          triggerElement.classList.contains("page-hero") ||
          triggerElement.classList.contains("layout-separateur") ||
          triggerElement.classList.contains("layout-cta"))
      ) {
        trigger.kill();
      }
    }
  });
}
