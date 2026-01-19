/**
 * Guide Pratique Navigation Module
 * Gère la navigation sticky et le scroll smooth pour le layout guide_pratique
 */

export function initGuidePratique() {
  const guideContainer = document.querySelector('[data-guide-pratique]');
  
  if (!guideContainer) {
    return;
  }

  const tocNav = guideContainer.querySelector('[data-toc-nav]');
  const tocLinks = guideContainer.querySelectorAll('[data-toc-link]');
  const sections = guideContainer.querySelectorAll('[data-section]');

  if (!tocNav || tocLinks.length === 0 || sections.length === 0) {
    return;
  }

  // Fonction pour obtenir l'ID de section depuis un lien
  const getSectionIdFromLink = (link) => {
    const href = link.getAttribute('href');
    return href ? href.replace('#', '') : null;
  };

  // Fonction pour trouver la section active
  const getActiveSection = () => {
    let activeSection = null;
    const scrollPosition = window.scrollY + 150; // Offset pour le header

    sections.forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionBottom = sectionTop + sectionHeight;

      if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
        activeSection = section;
      }
    });

    // Si on est en bas de page, activer la dernière section
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 100) {
      activeSection = sections[sections.length - 1];
    }

    return activeSection;
  };

  // Fonction pour mettre à jour l'état actif des liens
  const updateActiveLink = () => {
    const activeSection = getActiveSection();
    
    if (!activeSection) {
      return;
    }

    const activeSectionId = activeSection.getAttribute('id');

    tocLinks.forEach((link) => {
      const linkSectionId = getSectionIdFromLink(link);
      
      if (linkSectionId === activeSectionId) {
        link.classList.add('text-primary-500', 'font-semibold');
        // Retirer toutes les classes de couleur possibles
        link.classList.remove('text-neutral-600', 'text-brown-700', 'text-cream-200');
      } else {
        link.classList.remove('text-primary-500', 'font-semibold');
        // Ajouter une classe de couleur par défaut si aucune n'est présente
        if (!link.classList.contains('text-neutral-600') && 
            !link.classList.contains('text-brown-700') && 
            !link.classList.contains('text-cream-200')) {
          link.classList.add('text-neutral-600');
        }
      }
    });
  };

  // Gérer le clic sur les liens de la table des matières
  tocLinks.forEach((link) => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      
      const sectionId = getSectionIdFromLink(link);
      const targetSection = guideContainer.querySelector(`#${sectionId}`);
      
      if (targetSection) {
        const offsetTop = targetSection.offsetTop - 100; // Offset pour le header sticky
        
        // Utiliser Lenis si disponible, sinon scroll natif
        if (window.lenis) {
          window.lenis.scrollTo(offsetTop, {
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
          });
        } else {
          window.scrollTo({
            top: offsetTop,
            behavior: 'smooth',
          });
        }

        // Mettre à jour l'URL sans recharger la page
        if (history.pushState) {
          history.pushState(null, null, `#${sectionId}`);
        }
      }
    });
  });

  // Observer les sections pour mettre à jour l'état actif
  const observerOptions = {
    root: null,
    rootMargin: '-100px 0px -66% 0px',
    threshold: 0,
  };

  const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const sectionId = entry.target.getAttribute('id');
        
        tocLinks.forEach((link) => {
          const linkSectionId = getSectionIdFromLink(link);
          
          if (linkSectionId === sectionId) {
            link.classList.add('text-primary-500', 'font-semibold');
            // Retirer toutes les classes de couleur possibles
            link.classList.remove('text-neutral-600', 'text-brown-700', 'text-cream-200');
          } else {
            link.classList.remove('text-primary-500', 'font-semibold');
            // Ajouter une classe de couleur par défaut si aucune n'est présente
            if (!link.classList.contains('text-neutral-600') && 
                !link.classList.contains('text-brown-700') && 
                !link.classList.contains('text-cream-200')) {
              link.classList.add('text-neutral-600');
            }
          }
        });
      }
    });
  }, observerOptions);

  // Observer toutes les sections
  sections.forEach((section) => {
    sectionObserver.observe(section);
  });

  // Mettre à jour l'état actif au scroll (fallback)
  let ticking = false;
  const handleScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        updateActiveLink();
        ticking = false;
      });
      ticking = true;
    }
  };

  window.addEventListener('scroll', handleScroll, { passive: true });

  // Mettre à jour l'état initial
  updateActiveLink();

  // Gérer le hash dans l'URL au chargement de la page
  if (window.location.hash) {
    const hash = window.location.hash.replace('#', '');
    const targetSection = guideContainer.querySelector(`#${hash}`);
    
    if (targetSection) {
      setTimeout(() => {
        const offsetTop = targetSection.offsetTop - 100;
        
        if (window.lenis) {
          window.lenis.scrollTo(offsetTop, {
            duration: 1.2,
          });
        } else {
          window.scrollTo({
            top: offsetTop,
            behavior: 'smooth',
          });
        }
      }, 100);
    }
  }
}

