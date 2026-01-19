/**
 * GLightbox module for galleries
 * https://github.com/biati-digital/glightbox
 */
import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.css';

/**
 * Initialize GLightbox for galleries
 */
export function initGlightbox() {
  const gallerySelectors = [
    '[data-lightbox="galerie"]',
    '.layout-galerie a',
    '.gallery a',
  ];

  gallerySelectors.forEach((selector) => {
    const links = document.querySelectorAll(selector);
    
    if (links.length > 0) {
      const lightbox = GLightbox({
        selector: selector,
        touchNavigation: true,
        loop: true,
        autoplayVideos: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        slideEffect: 'slide',
        moreText: 'Voir plus',
        moreLength: 60,
        closeButton: true,
        touchFollowAxis: true,
        keyboardNavigation: true,
        closeOnOutsideClick: true,
        dragAutoSnap: false,
        cssEfects: {
          fade: { in: 'fadeIn', out: 'fadeOut' },
          slide: { in: 'slideInRight', out: 'slideOutLeft' },
        },
      });
    }
  });
}

