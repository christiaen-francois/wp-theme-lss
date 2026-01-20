import "../css/main.css";

import Lenis from "@studio-freight/lenis";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

import { initScrollAnimations } from "./animations";
import { initNavigation } from "./modules/navigation";
import { initGlightbox } from "./modules/glightbox";
import { initSwiper } from "./modules/swiper";
import { initLazyLoad } from "./modules/lazyload";
import { initPreloader } from "./modules/preloader";
import { initGuidePratique } from "./modules/guide-pratique";
import { initContactForm } from "./modules/contact";
import { initNewsletterForm } from "./modules/newsletter";
import { initSmartHeader } from "./modules/smart-header";
import { initParallax } from "./modules/parallax";

gsap.registerPlugin(ScrollTrigger);

let lenis;

function initLenis() {
  lenis = new Lenis({
    lerp: 0.1,
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }

  requestAnimationFrame(raf);

  // Sync ScrollTrigger with Lenis
  lenis.on("scroll", ScrollTrigger.update);

  // Exposer lenis sur window pour utilisation dans d'autres modules
  window.lenis = lenis;
}

function initApp() {
  // Initialiser le préloader en premier
  initPreloader();

  // Attendre que le préloader soit terminé avant d'initialiser le reste
  window.addEventListener("preloaderComplete", () => {
    initLenis();
    initLazyLoad();
    initParallax();
    initScrollAnimations();
    initNavigation();
    initSmartHeader();
    initGlightbox();
    initSwiper();
    initGuidePratique();
    initContactForm();
    initNewsletterForm();

    // Rafraîchir ScrollTrigger après l'initialisation pour s'assurer que tout fonctionne
    requestAnimationFrame(() => {
      ScrollTrigger.refresh();
    });
  });

  // Si pas de préloader, initialiser directement
  const preloader = document.querySelector("#preloader");
  if (!preloader) {
    initLenis();
    initLazyLoad();
    initParallax();
    initScrollAnimations();
    initNavigation();
    initSmartHeader();
    initGlightbox();
    initSwiper();
    initGuidePratique();
    initContactForm();
    initNewsletterForm();
  }
}

document.addEventListener("DOMContentLoaded", initApp);
