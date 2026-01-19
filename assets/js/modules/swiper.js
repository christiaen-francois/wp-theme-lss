/**
 * Swiper module for sliders
 * https://swiperjs.com
 */
import Swiper from "swiper";
import {
  Navigation,
  Pagination,
  Autoplay,
  EffectFade,
  EffectCoverflow,
  EffectCreative,
} from "swiper/modules";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/effect-fade";
import "swiper/css/effect-coverflow";
import "swiper/css/effect-creative";

/**
 * Initialize Swiper sliders
 */
export function initSwiper() {
  // Hero slider
  const heroSlider = document.querySelector(".hero-slider.swiper");

  if (heroSlider) {
    new Swiper(heroSlider, {
      modules: [Navigation, Pagination, Autoplay, EffectFade],
      effect: "fade",
      fadeEffect: {
        crossFade: true,
      },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      loop: true,
      speed: 1000,
      pagination: {
        el: heroSlider.querySelector(".swiper-pagination"),
        clickable: true,
      },
      navigation: {
        nextEl: heroSlider.querySelector(".swiper-button-next"),
        prevEl: heroSlider.querySelector(".swiper-button-prev"),
      },
    });
  }

  // Testimonials slider with custom effect
  const testimonialsSlider = document.querySelector(
    ".testimonials-slider.swiper"
  );

  if (testimonialsSlider) {
    new Swiper(testimonialsSlider, {
      modules: [Autoplay, EffectCreative],
      effect: "creative",
      creativeEffect: {
        prev: {
          // shadow: true,
          translate: ["-20%", 0, -1],
          opacity: 0,
        },
        next: {
          translate: ["100%", 0, 0],
          opacity: 0,
        },
      },
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      speed: 800,
      grabCursor: true,
      breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        768: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 1,
          spaceBetween: 40,
        },
      },
      // Auto height
      autoHeight: true,
    });
  }

  // Generic slider with data-swiper attribute
  const swiperContainers = document.querySelectorAll(
    "[data-swiper]:not(.testimonials-slider)"
  );

  swiperContainers.forEach((container) => {
    const swiperType = container.getAttribute("data-swiper");
    const config = {
      modules: [Navigation, Pagination, Autoplay],
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },
    };

    // Add pagination if element exists
    const paginationEl = container.querySelector(".swiper-pagination");
    if (paginationEl) {
      config.pagination = {
        el: paginationEl,
        clickable: true,
      };
    }

    // Add navigation if elements exist
    const nextEl = container.querySelector(".swiper-button-next");
    const prevEl = container.querySelector(".swiper-button-prev");
    if (nextEl && prevEl) {
      config.navigation = {
        nextEl: nextEl,
        prevEl: prevEl,
      };
    }

    new Swiper(container, config);
  });

  // Itinerary galleries with sticky scroll following
  const itineraryGalleries = document.querySelectorAll(
    "[data-itinerary-gallery]"
  );

  if (itineraryGalleries.length > 0) {
    itineraryGalleries.forEach((gallery, index) => {
      const swiper = new Swiper(gallery, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 16,
        speed: 600,
        loop: true,
        grabCursor: true,
        allowTouchMove: true,
        pagination: {
          el: gallery.querySelector(".swiper-pagination"),
          clickable: true,
          dynamicBullets: true,
        },
        navigation: {
          nextEl: gallery.querySelector(".swiper-button-next"),
          prevEl: gallery.querySelector(".swiper-button-prev"),
        },
        // Auto-play désactivé par défaut, sera contrôlé par ScrollTrigger
        autoplay: false,
      });

      // Trouver l'élément parent de l'itinéraire pour le scroll
      const itineraryItem = gallery.closest(".itinerary-item");
      if (!itineraryItem) return;

      // Obtenir le nombre de slides
      const totalSlides = swiper.slides.length;
      let isUserInteracting = false;

      // Empêcher l'auto-advance pendant l'interaction utilisateur
      gallery.addEventListener("mouseenter", () => {
        isUserInteracting = true;
      });

      gallery.addEventListener("mouseleave", () => {
        isUserInteracting = false;
      });

      swiper.on("slideChange", () => {
        isUserInteracting = true;
        setTimeout(() => {
          isUserInteracting = false;
        }, 1000);
      });

      // Synchroniser le slider avec le scroll de la page via ScrollTrigger
      ScrollTrigger.create({
        trigger: itineraryItem,
        start: "top top",
        end: "bottom top",
        pin: false,
        scrub: 0.5, // Smooth scrubbing
        onUpdate: (self) => {
          if (isUserInteracting) return;

          // Calculer la progression du scroll (0 à 1)
          const progress = self.progress;
          
          // Convertir la progression en index de slide
          const targetSlide = Math.floor(progress * totalSlides);
          const currentSlide = swiper.realIndex;

          // Changer de slide seulement si nécessaire
          if (targetSlide !== currentSlide && targetSlide < totalSlides) {
            swiper.slideToLoop(targetSlide, 600);
          }
        },
      });
    });
  }
}
