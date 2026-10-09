(() => {
  "use strict";

  const body = document.body;
  const header = document.getElementById("siteHeader");
  const menuToggle = document.getElementById("menuToggle");
  const mobileMenuLinks = document.querySelectorAll("[data-menu-link]");
  const progressBar = document.getElementById("pageProgressBar");

  function setMenu(open) {
    if (!menuToggle) return;
    body.classList.toggle("nav-open", open);
    menuToggle.setAttribute("aria-expanded", String(open));
    menuToggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
  }

  menuToggle?.addEventListener("click", () => {
    setMenu(!body.classList.contains("nav-open"));
  });

  mobileMenuLinks.forEach((link) => link.addEventListener("click", () => setMenu(false)));

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") setMenu(false);
  });

  document.addEventListener("click", (event) => {
    if (!body.classList.contains("nav-open") || !header) return;
    if (!header.contains(event.target)) setMenu(false);
  });

  function syncScrollUI() {
    header?.classList.toggle("is-scrolled", window.scrollY > 12);

    if (progressBar) {
      const scrollable = document.documentElement.scrollHeight - window.innerHeight;
      const progress = scrollable > 0 ? Math.min(100, (window.scrollY / scrollable) * 100) : 0;
      progressBar.style.width = `${progress}%`;
    }
  }

  window.addEventListener("scroll", syncScrollUI, { passive: true });
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1180) setMenu(false);
    syncScrollUI();
  });
  syncScrollUI();

  // Hero carousel
  const heroDataElement = document.getElementById("heroSlidesData");
  const heroSlides = Array.from(document.querySelectorAll("[data-hero-slide]"));
  const heroDots = Array.from(document.querySelectorAll("[data-hero-dot]"));
  const heroEyebrow = document.querySelector("[data-hero-eyebrow]");
  const heroTitle = document.querySelector("[data-hero-title]");
  const heroText = document.querySelector("[data-hero-text]");
  let heroContent = [];
  let activeHeroIndex = 0;
  let heroTimer = null;

  if (heroDataElement) {
    try {
      heroContent = JSON.parse(heroDataElement.textContent || "[]");
    } catch (error) {
      console.error("Unable to read hero content.", error);
    }
  }

  function showHeroSlide(index) {
    if (!heroSlides.length) return;
    activeHeroIndex = (index + heroSlides.length) % heroSlides.length;

    heroSlides.forEach((slide, slideIndex) => {
      slide.classList.toggle("is-active", slideIndex === activeHeroIndex);
    });

    heroDots.forEach((dot, dotIndex) => {
      const active = dotIndex === activeHeroIndex;
      dot.classList.toggle("is-active", active);
      dot.setAttribute("aria-current", active ? "true" : "false");
    });

    const content = heroContent[activeHeroIndex];
    if (!content) return;

    if (heroEyebrow) heroEyebrow.textContent = content.eyebrow || "";
    if (heroTitle) heroTitle.innerHTML = formatHeroTitle(content.title || "");
    if (heroText) heroText.textContent = content.text || "";
  }

  function formatHeroTitle(title) {
    const phrases = ["thoughtfully connected.", "specialist Ayurveda care.", "your wellness journey."];
    let formatted = escapeHtml(title);
    phrases.forEach((phrase) => {
      formatted = formatted.replace(phrase, `<em>${phrase}</em>`);
    });
    return formatted;
  }

  function escapeHtml(value) {
    const div = document.createElement("div");
    div.textContent = value;
    return div.innerHTML;
  }

  function startHeroCarousel() {
    window.clearInterval(heroTimer);
    if (heroSlides.length > 1 && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      heroTimer = window.setInterval(() => showHeroSlide(activeHeroIndex + 1), 6500);
    }
  }

  heroDots.forEach((dot) => {
    dot.addEventListener("click", () => {
      showHeroSlide(Number(dot.dataset.heroDot));
      startHeroCarousel();
    });
  });

  if (heroSlides.length) {
    showHeroSlide(0);
    startHeroCarousel();
  }

  document.addEventListener("visibilitychange", () => {
    if (document.hidden) window.clearInterval(heroTimer);
    else startHeroCarousel();
  });

  // Service filter
  const serviceFilters = Array.from(document.querySelectorAll("[data-service-filter]"));
  const serviceCards = Array.from(document.querySelectorAll("[data-service-card]"));

  function filterServices(filter) {
    serviceFilters.forEach((button) => {
      const active = button.dataset.serviceFilter === filter;
      button.classList.toggle("is-active", active);
      button.setAttribute("aria-pressed", String(active));
    });

    serviceCards.forEach((card) => {
      const visible = filter === "all" || card.dataset.serviceCard === filter;
      card.classList.toggle("is-hidden", !visible);
      card.setAttribute("aria-hidden", String(!visible));
    });
  }

  serviceFilters.forEach((button) => {
    button.addEventListener("click", () => filterServices(button.dataset.serviceFilter || "all"));
  });

  // Appointment form to Devya system
  const appointmentForm = document.getElementById("appointmentForm");
  const preferredDate = document.getElementById("preferredDate");
  const serviceSelect = document.getElementById("service");
  const appointmentStatus = document.getElementById("appointmentStatus");
  const appointmentSubmit = appointmentForm?.querySelector('button[type="submit"]');

  if (preferredDate) {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");
    preferredDate.min = `${year}-${month}-${day}`;
  }

  if (serviceSelect) {
    const requestedService = new URLSearchParams(window.location.search).get("service");
    if (requestedService) {
      const matchingOption = Array.from(serviceSelect.options).find((option) => option.value === requestedService);
      if (matchingOption) serviceSelect.value = requestedService;
    }
  }

  function showAppointmentStatus(message, state = "info") {
    if (!appointmentStatus) return;

    appointmentStatus.textContent = message;
    appointmentStatus.dataset.state = state;
    appointmentStatus.classList.add("is-visible");
  }

  appointmentForm?.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (!appointmentForm.reportValidity()) return;

    const formData = new FormData(appointmentForm);
    const endpoint = appointmentForm.dataset.endpoint || appointmentForm.action;

    if (!endpoint) {
      appointmentForm.submit();
      return;
    }

    appointmentSubmit?.setAttribute("disabled", "disabled");
    showAppointmentStatus("Submitting your appointment request...", "info");

    try {
      const response = await fetch(endpoint, {
        method: "POST",
        body: formData,
        headers: {
          Accept: "application/json"
        }
      });

      const payload = await response.json().catch(() => null);

      if (!response.ok) {
        const validationErrors = payload?.errors ? Object.values(payload.errors).flat().join(" ") : "";
        throw new Error(validationErrors || payload?.message || "Unable to submit the appointment request.");
      }

      if (!payload?.booking_number) {
        throw new Error("Unable to confirm your booking. Please call the Centre before submitting again.");
      }

      showAppointmentStatus(`Your appointment request has been received. Booking number: ${payload.booking_number}. Staff will contact you to confirm availability.`, "success");
      appointmentForm.reset();
    } catch (error) {
      showAppointmentStatus(error.message || "Unable to submit the appointment request. Please call the Centre.", "error");
    } finally {
      appointmentSubmit?.removeAttribute("disabled");
    }
  });

  // FAQ accordions
  document.querySelectorAll(".faq-question").forEach((button) => {
    button.addEventListener("click", () => {
      const item = button.closest(".faq-item");
      const answer = item?.querySelector(".faq-answer");
      if (!item || !answer) return;

      const willOpen = !item.classList.contains("is-open");

      document.querySelectorAll(".faq-item.is-open").forEach((openItem) => {
        if (openItem === item) return;
        openItem.classList.remove("is-open");
        const openButton = openItem.querySelector(".faq-question");
        const openAnswer = openItem.querySelector(".faq-answer");
        openButton?.setAttribute("aria-expanded", "false");
        if (openAnswer) openAnswer.style.maxHeight = "0px";
      });

      item.classList.toggle("is-open", willOpen);
      button.setAttribute("aria-expanded", String(willOpen));
      answer.style.maxHeight = willOpen ? `${answer.scrollHeight}px` : "0px";
    });
  });

  // Reveal animation
  const revealTargets = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const revealObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px" }
    );

    revealTargets.forEach((target, index) => {
      target.style.transitionDelay = `${Math.min(index % 4, 3) * 70}ms`;
      revealObserver.observe(target);
    });
  } else {
    revealTargets.forEach((target) => target.classList.add("is-visible"));
  }
})();
