import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

/**
 * Initialise les animations au scroll avec ScrollTrigger et Lenis
 */
export function initScrollAnimations() {
  // Respecter prefers-reduced-motion
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;

  if (prefersReducedMotion) {
    // Désactiver les animations si l'utilisateur préfère les réduire
    return;
  }

  // Animation des layouts de contenu flexible
  initLayoutAnimations();

  // Fade-in on scroll ou au chargement si déjà visible
  const elements = document.querySelectorAll('[data-animate="fade-in"]');
  elements.forEach((el) => {
    animateElement(el, { y: 40 });
  });

  // Stagger (ex: liste de projets)
  const groups = document.querySelectorAll('[data-animate-group="stagger"]');
  groups.forEach((group) => {
    const items = group.querySelectorAll("[data-animate-item]");
    animateStaggerGroup(group, items);
  });
}

/**
 * Animer un élément individuel
 */
function animateElement(el, fromProps = { y: 40 }) {
  // Vérifier si l'élément est déjà visible au chargement
  const rect = el.getBoundingClientRect();
  const isVisible = rect.top < window.innerHeight * 0.8;

  gsap.set(el, { autoAlpha: 0, ...fromProps });

  if (isVisible) {
    // Animer immédiatement si déjà visible
    gsap.to(el, {
      autoAlpha: 1,
      y: 0,
      x: 0,
      scale: 1,
      duration: 1,
      ease: "power3.out",
      delay: 0.2,
    });
  } else {
    // Animer au scroll sinon
    gsap.to(el, {
      autoAlpha: 1,
      y: 0,
      x: 0,
      scale: 1,
      duration: 1,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top 80%",
        toggleActions: "play none none reverse",
      },
    });
  }
}

/**
 * Animer un groupe avec stagger
 */
function animateStaggerGroup(group, items) {
  // Vérifier si le groupe est déjà visible au chargement
  const rect = group.getBoundingClientRect();
  const isVisible = rect.top < window.innerHeight * 0.85;

  gsap.set(items, { autoAlpha: 0, y: 24 });

  if (isVisible) {
    // Animer immédiatement si déjà visible
    gsap.to(items, {
      autoAlpha: 1,
      y: 0,
      stagger: 0.08,
      duration: 0.9,
      ease: "power3.out",
      delay: 0.3,
    });
  } else {
    // Animer au scroll sinon
    gsap.to(items, {
      autoAlpha: 1,
      y: 0,
      stagger: 0.08,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: group,
        start: "top 85%",
        toggleActions: "play none none reverse",
      },
    });
  }
}

/**
 * Initialise les animations pour les layouts de contenu flexible
 */
function initLayoutAnimations() {
  // Layout Hero
  const heroLayouts = document.querySelectorAll(".layout-hero");
  heroLayouts.forEach((layout) => {
    const content = layout.querySelector(".relative.z-10");
    if (content) {
      gsap.set(content, { autoAlpha: 0, y: 60 });
      gsap.to(content, {
        autoAlpha: 1,
        y: 0,
        duration: 1.2,
        ease: "power3.out",
        scrollTrigger: {
          trigger: layout,
          start: "top 90%",
          toggleActions: "play none none none",
        },
      });
    }
  });

  // Layout Texte et Image
  const texteImageLayouts = document.querySelectorAll(".layout-texte-image");
  texteImageLayouts.forEach((layout) => {
    const image = layout.querySelector("img");
    const text = layout.querySelector(".prose");

    if (image) {
      gsap.set(image, { autoAlpha: 0 });
      gsap.to(image, {
        autoAlpha: 1,
        duration: 0.8,
        ease: "power2.out",
        scrollTrigger: {
          trigger: image,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
      });
    }

    if (text) {
      gsap.set(text, { autoAlpha: 0, y: 30 });
      gsap.to(text, {
        autoAlpha: 1,
        y: 0,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: text,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
        delay: image ? 0.2 : 0,
      });
    }
  });

  // Layout Témoignages - Seulement le header (pas le slider pour éviter conflit avec Swiper)
  const temoignagesLayouts = document.querySelectorAll(".layout-temoignages");
  temoignagesLayouts.forEach((layout) => {
    const header = layout.querySelector("header");

    if (header) {
      gsap.set(header, { autoAlpha: 0, y: 40 });
      gsap.to(header, {
        autoAlpha: 1,
        y: 0,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: header,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
      });
    }
    // Le slider Swiper gère ses propres animations, pas besoin d'animations GSAP
  });

  // Layout Services
  const servicesLayouts = document.querySelectorAll(".layout-services");
  servicesLayouts.forEach((layout) => {
    const header = layout.querySelector("header");
    const cards = layout.querySelectorAll("article");

    if (header) {
      gsap.set(header, { autoAlpha: 0, y: 40 });
      gsap.to(header, {
        autoAlpha: 1,
        y: 0,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: header,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
      });
    }

    if (cards.length > 0) {
      gsap.set(cards, { autoAlpha: 0, y: 40 });
      gsap.to(cards, {
        autoAlpha: 1,
        y: 0,
        stagger: {
          amount: 0.6,
          from: "start",
        },
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: layout,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
        delay: header ? 0.2 : 0,
      });
    }
  });

  // Layout FAQ
  const faqLayouts = document.querySelectorAll(".layout-faq");
  faqLayouts.forEach((layout) => {
    const header = layout.querySelector("header");
    const questions = layout.querySelectorAll("details");

    if (header) {
      gsap.set(header, { autoAlpha: 0, y: 40 });
      gsap.to(header, {
        autoAlpha: 1,
        y: 0,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: header,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
      });
    }

    if (questions.length > 0) {
      gsap.set(questions, { autoAlpha: 0, x: -30 });
      gsap.to(questions, {
        autoAlpha: 1,
        x: 0,
        stagger: 0.06,
        duration: 0.7,
        ease: "power2.out",
        scrollTrigger: {
          trigger: layout,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
        delay: header ? 0.2 : 0,
      });
    }
  });

  // Layout CTA
  const ctaLayouts = document.querySelectorAll(".layout-cta");
  ctaLayouts.forEach((layout) => {
    const content = layout.querySelector(".text-center, .text-left");
    const image = layout.querySelector("img");

    if (content) {
      gsap.set(content, { autoAlpha: 0, y: 40 });
      gsap.to(content, {
        autoAlpha: 1,
        y: 0,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: content,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
      });
    }

    if (image) {
      gsap.set(image, { autoAlpha: 0 });
      gsap.to(image, {
        autoAlpha: 1,
        duration: 0.8,
        ease: "power2.out",
        scrollTrigger: {
          trigger: image,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
        delay: 0.2,
      });
    }
  });

  // Layout Galerie
  const galerieLayouts = document.querySelectorAll(".layout-galerie");
  galerieLayouts.forEach((layout) => {
    const photos = layout.querySelectorAll("a");

    if (photos.length > 0) {
      gsap.set(photos, { autoAlpha: 0 });
      gsap.to(photos, {
        autoAlpha: 1,
        stagger: {
          amount: 0.6,
          grid: "auto",
          from: "start",
        },
        duration: 0.6,
        ease: "power2.out",
        scrollTrigger: {
          trigger: layout,
          start: "top 80%",
          toggleActions: "play none none reverse",
        },
      });
    }
  });

  // Layout Newsletter
  const newsletterLayouts = document.querySelectorAll(".layout-newsletter");
  newsletterLayouts.forEach((layout) => {
    const form = layout.querySelector("form");
    const content = layout.querySelector(".text-center");

    if (content) {
      gsap.set(content, { autoAlpha: 0, y: 30 });
      gsap.to(content, {
        autoAlpha: 1,
        y: 0,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: content,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
      });
    }

    if (form) {
      gsap.set(form, { autoAlpha: 0, y: 20 });
      gsap.to(form, {
        autoAlpha: 1,
        y: 0,
        duration: 0.8,
        ease: "power3.out",
        scrollTrigger: {
          trigger: form,
          start: "top 85%",
          toggleActions: "play none none reverse",
        },
        delay: 0.2,
      });
    }
  });
}
