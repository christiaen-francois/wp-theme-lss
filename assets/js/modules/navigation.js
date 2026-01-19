/**
 * Module de gestion de la navigation mobile et des sous-menus
 */
export function initNavigation() {
  const menuToggle = document.querySelector("[data-menu-toggle]");
  const menuClose = document.querySelector("[data-menu-close]");
  const menu = document.querySelector("[data-menu]");
  const body = document.body;

  if (!menuToggle || !menu || !menuClose) {
    return;
  }

  function openMenu() {
    menu.classList.remove("translate-x-full");
    menuToggle.setAttribute("aria-expanded", "true");
    body.style.overflow = "hidden";
  }

  function closeMenu() {
    menu.classList.add("translate-x-full");
    menuToggle.setAttribute("aria-expanded", "false");
    body.style.overflow = "";
  }

  // Ouvrir le menu
  menuToggle.addEventListener("click", (e) => {
    e.preventDefault();
    openMenu();
  });

  // Fermer le menu
  menuClose.addEventListener("click", (e) => {
    e.preventDefault();
    closeMenu();
  });

  // Fermer au clic sur un lien (sauf ceux avec sous-menus)
  const menuLinks = menu.querySelectorAll("a");
  menuLinks.forEach((link) => {
    const parentItem = link.closest(".has-submenu");
    if (!parentItem) {
      link.addEventListener("click", () => {
        closeMenu();
      });
    }
  });

  // Fermer avec Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !menu.classList.contains("translate-x-full")) {
      closeMenu();
    }
  });

  // Fermer au clic en dehors (optionnel)
  menu.addEventListener("click", (e) => {
    if (e.target === menu) {
      closeMenu();
    }
  });

  // Gestion des sous-menus
  initSubmenus();
}

/**
 * Initialise la gestion des sous-menus (mobile et desktop)
 */
function initSubmenus() {
  const submenuToggles = document.querySelectorAll(".submenu-toggle");
  const hasSubmenuItems = document.querySelectorAll(".has-submenu");

  // Gestion mobile : toggle au clic
  submenuToggles.forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      const parentItem = toggle.closest(".has-submenu");
      const submenu = parentItem?.querySelector(".sub-menu");

      if (!parentItem || !submenu) {
        return;
      }

      const isExpanded = toggle.getAttribute("aria-expanded") === "true";

      // Fermer tous les autres sous-menus
      submenuToggles.forEach((otherToggle) => {
        if (otherToggle !== toggle) {
          otherToggle.setAttribute("aria-expanded", "false");
          const otherParent = otherToggle.closest(".has-submenu");
          const otherSubmenu = otherParent?.querySelector(".sub-menu");
          if (otherSubmenu) {
            otherSubmenu.classList.remove("active");
          }
        }
      });

      // Toggle le sous-menu actuel
      toggle.setAttribute("aria-expanded", !isExpanded);
      submenu.classList.toggle("active");
    });
  });

  // Gestion desktop : hover pour ouvrir/fermer
  hasSubmenuItems.forEach((item) => {
    const submenu = item.querySelector(".sub-menu");
    if (!submenu) {
      return;
    }

    // Desktop : hover
    item.addEventListener("mouseenter", () => {
      if (window.innerWidth >= 768) {
        submenu.classList.add("active");
      }
    });

    item.addEventListener("mouseleave", () => {
      if (window.innerWidth >= 768) {
        submenu.classList.remove("active");
      }
    });

    // Desktop : fermer au clic en dehors
    document.addEventListener("click", (e) => {
      if (window.innerWidth >= 768 && !item.contains(e.target)) {
        submenu.classList.remove("active");
      }
    });
  });
}

