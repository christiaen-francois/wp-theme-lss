/**
 * Module de gestion du formulaire de newsletter
 * Validation côté client et soumission AJAX
 */
export function initNewsletterForm() {
  const forms = document.querySelectorAll("[data-newsletter-form]");

  if (!forms.length) {
    return;
  }

  forms.forEach((form) => setupForm(form));
}

/**
 * Configure un formulaire de newsletter
 */
function setupForm(form) {
  const container = form.closest(".layout-newsletter") || form.parentElement;
  const submitButton = form.querySelector("[data-submit-button]");
  const emailInput = form.querySelector('[name="email"]');
  const successMessage = container.querySelector("[data-success-message]");
  const errorMessage = container.querySelector("[data-error-message]");

  if (!emailInput) {
    return;
  }

  // Validation en temps réel
  emailInput.addEventListener("blur", () => validateEmail(emailInput));
  emailInput.addEventListener("input", () => clearFieldError(emailInput));

  // Soumission du formulaire
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Réinitialiser les messages
    hideMessages(successMessage, errorMessage);

    // Valider l'email
    if (!validateEmail(emailInput)) {
      showError(errorMessage, "Veuillez entrer une adresse email valide.");
      return;
    }

    // Désactiver le bouton pendant l'envoi
    setSubmitButtonState(submitButton, false);

    try {
      const formData = new FormData(form);
      formData.append("action", "nl_newsletter_form");

      const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showSuccess(successMessage, data.data.message || "Merci pour votre inscription !");
        form.reset();
        clearFieldError(emailInput);
      } else {
        if (data.data?.errors?.email) {
          showFieldError(emailInput, data.data.errors.email);
        }
        showError(errorMessage, data.data?.message || "Une erreur est survenue. Veuillez réessayer.");
      }
    } catch (error) {
      console.error("Erreur lors de l'envoi du formulaire:", error);
      showError(errorMessage, "Une erreur réseau est survenue. Veuillez réessayer.");
    } finally {
      setSubmitButtonState(submitButton, true);
    }
  });
}

/**
 * Valide le champ email
 */
function validateEmail(field) {
  const value = field.value.trim();
  let isValid = true;
  let errorMsg = "";

  if (!value) {
    isValid = false;
    errorMsg = "L'adresse email est requise.";
  } else {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      isValid = false;
      errorMsg = "Veuillez entrer une adresse email valide.";
    }
  }

  if (isValid) {
    clearFieldError(field);
  } else {
    showFieldError(field, errorMsg);
  }

  return isValid;
}

/**
 * Affiche une erreur pour un champ
 */
function showFieldError(field, message) {
  field.classList.add("ring-2", "ring-red-500");
  field.classList.remove("focus:ring-cream-200");

  let errorElement = field.parentElement.querySelector("[data-field-error]");
  if (!errorElement) {
    errorElement = document.createElement("p");
    errorElement.setAttribute("data-field-error", "");
    errorElement.className = "mt-2 text-sm text-red-600";
    field.parentElement.appendChild(errorElement);
  }

  errorElement.textContent = message;
  errorElement.setAttribute("role", "alert");
}

/**
 * Efface l'erreur d'un champ
 */
function clearFieldError(field) {
  field.classList.remove("ring-2", "ring-red-500");
  field.classList.add("focus:ring-cream-200");

  const errorElement = field.parentElement.querySelector("[data-field-error]");
  if (errorElement) {
    errorElement.remove();
  }
}

/**
 * Affiche le message de succès
 */
function showSuccess(element, message) {
  if (element) {
    element.textContent = message;
    element.classList.remove("hidden");
    element.setAttribute("role", "alert");
    element.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }
}

/**
 * Affiche le message d'erreur
 */
function showError(element, message) {
  if (element) {
    element.textContent = message;
    element.classList.remove("hidden");
    element.setAttribute("role", "alert");
    element.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }
}

/**
 * Masque tous les messages
 */
function hideMessages(successMessage, errorMessage) {
  if (successMessage) {
    successMessage.classList.add("hidden");
  }
  if (errorMessage) {
    errorMessage.classList.add("hidden");
  }
}

/**
 * Change l'état du bouton de soumission
 */
function setSubmitButtonState(button, enabled) {
  if (button) {
    button.disabled = !enabled;
    if (enabled) {
      button.classList.remove("opacity-50", "cursor-not-allowed");
      button.querySelector("[data-button-text]").textContent =
        button.dataset.originalText || "S'inscrire";
    } else {
      button.classList.add("opacity-50", "cursor-not-allowed");
      button.dataset.originalText =
        button.querySelector("[data-button-text]")?.textContent || "S'inscrire";
      const textEl = button.querySelector("[data-button-text]");
      if (textEl) {
        textEl.textContent = "Envoi...";
      }
    }
  }
}
