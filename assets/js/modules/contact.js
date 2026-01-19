/**
 * Module de gestion du formulaire de contact
 * Validation côté client et soumission AJAX
 */
export function initContactForm() {
  const form = document.querySelector("[data-contact-form]");

  if (!form) {
    return;
  }

  // Pré-remplir le champ subject depuis l'URL
  const urlParams = new URLSearchParams(window.location.search);
  const subjectParam = urlParams.get('subject');
  if (subjectParam) {
    const subjectField = form.querySelector('[name="subject"]');
    if (subjectField) {
      subjectField.value = decodeURIComponent(subjectParam);
    }
  }

  const submitButton = form.querySelector("[data-submit-button]");
  const successMessage = document.querySelector("[data-success-message]");
  const errorMessage = document.querySelector("[data-error-message]");

  // Validation en temps réel
  const inputs = form.querySelectorAll("input, textarea");
  inputs.forEach((input) => {
    input.addEventListener("blur", () => validateField(input));
    input.addEventListener("input", () => clearFieldError(input));
  });

  // Soumission du formulaire
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Réinitialiser les messages
    hideMessages();

    // Valider tous les champs
    let isValid = true;
    inputs.forEach((input) => {
      if (!validateField(input)) {
        isValid = false;
      }
    });

    if (!isValid) {
      showError("Veuillez corriger les erreurs dans le formulaire.");
      return;
    }

    // Désactiver le bouton pendant l'envoi
    setSubmitButtonState(false);

    try {
      const formData = new FormData(form);
      formData.append("action", "nl_contact_form");

      const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showSuccess(data.data.message || "Votre message a été envoyé avec succès.");
        form.reset();
        clearAllErrors();
      } else {
        // Afficher les erreurs de validation
        if (data.data?.errors) {
          displayFieldErrors(data.data.errors);
        }
        showError(data.data?.message || "Une erreur est survenue. Veuillez réessayer.");
      }
    } catch (error) {
      console.error("Erreur lors de l'envoi du formulaire:", error);
      showError("Une erreur réseau est survenue. Veuillez réessayer.");
    } finally {
      setSubmitButtonState(true);
    }
  });

  /**
   * Valide un champ individuel
   */
  function validateField(field) {
    const fieldName = field.name;
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = "";

    // Vérifier les champs requis
    if (field.hasAttribute("required") && !value) {
      isValid = false;
      errorMessage = "Ce champ est requis.";
    }

    // Validation spécifique par type
    if (value) {
      switch (fieldName) {
        case "email":
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = "Veuillez entrer une adresse email valide.";
          }
          break;

        case "phone":
          // Validation basique du téléphone (au moins 10 caractères)
          const phoneRegex = /^[\d\s\-\+\(\)]+$/;
          if (!phoneRegex.test(value) || value.replace(/\D/g, "").length < 10) {
            isValid = false;
            errorMessage = "Veuillez entrer un numéro de téléphone valide.";
          }
          break;

        case "message":
          if (value.length < 10) {
            isValid = false;
            errorMessage = "Le message doit contenir au moins 10 caractères.";
          }
          break;
      }
    }

    // Afficher ou masquer l'erreur
    if (isValid) {
      clearFieldError(field);
    } else {
      showFieldError(field, errorMessage);
    }

    return isValid;
  }

  /**
   * Affiche une erreur pour un champ
   */
  function showFieldError(field, message) {
    field.classList.add("border-red-500", "focus:border-red-500", "focus:ring-red-500");
    field.classList.remove("border-neutral-300", "focus:border-primary-500");

    // Trouver ou créer le message d'erreur
    let errorElement = field.parentElement.querySelector("[data-field-error]");
    if (!errorElement) {
      errorElement = document.createElement("p");
      errorElement.setAttribute("data-field-error", "");
      errorElement.className = "mt-1 text-sm text-red-600";
      field.parentElement.appendChild(errorElement);
    }

    errorElement.textContent = message;
    errorElement.setAttribute("role", "alert");
  }

  /**
   * Efface l'erreur d'un champ
   */
  function clearFieldError(field) {
    field.classList.remove("border-red-500", "focus:border-red-500", "focus:ring-red-500");
    field.classList.add("border-neutral-300", "focus:border-primary-500");

    const errorElement = field.parentElement.querySelector("[data-field-error]");
    if (errorElement) {
      errorElement.remove();
    }
  }

  /**
   * Efface toutes les erreurs
   */
  function clearAllErrors() {
    inputs.forEach((input) => clearFieldError(input));
  }

  /**
   * Affiche les erreurs de validation du serveur
   */
  function displayFieldErrors(errors) {
    Object.keys(errors).forEach((fieldName) => {
      const field = form.querySelector(`[name="${fieldName}"]`);
      if (field) {
        showFieldError(field, errors[fieldName]);
      }
    });
  }

  /**
   * Affiche le message de succès
   */
  function showSuccess(message) {
    if (successMessage) {
      successMessage.textContent = message;
      successMessage.classList.remove("hidden");
      successMessage.setAttribute("role", "alert");

      // Scroll vers le message
      successMessage.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
  }

  /**
   * Affiche le message d'erreur
   */
  function showError(message) {
    if (errorMessage) {
      errorMessage.textContent = message;
      errorMessage.classList.remove("hidden");
      errorMessage.setAttribute("role", "alert");

      // Scroll vers le message
      errorMessage.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
  }

  /**
   * Masque tous les messages
   */
  function hideMessages() {
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
  function setSubmitButtonState(enabled) {
    if (submitButton) {
      submitButton.disabled = !enabled;
      if (enabled) {
        submitButton.classList.remove("opacity-50", "cursor-not-allowed");
        submitButton.textContent = submitButton.dataset.originalText || "Envoyer";
      } else {
        submitButton.classList.add("opacity-50", "cursor-not-allowed");
        submitButton.dataset.originalText = submitButton.textContent;
        submitButton.textContent = "Envoi en cours...";
      }
    }
  }
}

