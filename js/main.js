/**
 * Braemeg SACCO — main.js
 *
 * All interactive behaviour for the site, wrapped in a single IIFE
 * with one DOMContentLoaded listener. No inline handlers anywhere.
 *
 * Sections:
 *   1. Mobile navigation
 *   2. Sticky navbar shadow
 *   3. Product tabs (homepage)
 *   4. Scroll-triggered animations
 *   5. Homepage contact form (fetch → contactmail.php)
 *   6. Contacts-page form (fetch → reachToUs.php) + CAPTCHA refresh
 *   7. Gallery category filter
 *   8. FAQ accordion
 *   9. Apply / membership form (fetch → saveapply.php)
 */

(function () {
  "use strict";

  /* ─────────────────────────────────────────────────────────
   * 1. MOBILE NAVIGATION
   * ───────────────────────────────────────────────────────── */
  function initMobileNav() {
    var hamburger = document.getElementById("nav-hamburger");
    var navList = document.getElementById("nav-list");
    if (!hamburger || !navList) {
      return;
    }

    hamburger.addEventListener("click", function () {
      var open = navList.classList.toggle("is-open");
      hamburger.setAttribute("aria-expanded", open ? "true" : "false");
    });

    document.querySelectorAll(".nav__item--drop > a").forEach(function (link) {
      link.addEventListener("click", function (e) {
        if (window.innerWidth <= 900) {
          e.preventDefault();
          this.parentElement.classList.toggle("is-open");
        }
      });
    });

    document.addEventListener("click", function (e) {
      if (
        !e.target.closest("#nav-hamburger") &&
        !e.target.closest("#nav-list")
      ) {
        navList.classList.remove("is-open");
        hamburger.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 2. STICKY NAVBAR SHADOW
   * ───────────────────────────────────────────────────────── */
  function initStickyNav() {
    var navbar = document.querySelector(".navbar");
    if (!navbar) {
      return;
    }
    window.addEventListener(
      "scroll",
      function () {
        navbar.classList.toggle("navbar--scrolled", window.scrollY > 50);
      },
      { passive: true },
    );
  }

  /* ─────────────────────────────────────────────────────────
   * 3. PRODUCT TABS (homepage)
   * ───────────────────────────────────────────────────────── */
  function initTabs() {
    var tabBtns = document.querySelectorAll(".tab-btn");
    var tabPanels = document.querySelectorAll(".tab-panel");
    if (!tabBtns.length) {
      return;
    }

    tabBtns.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var target = this.getAttribute("data-tab");
        tabBtns.forEach(function (b) {
          b.classList.remove("is-active");
          b.setAttribute("aria-selected", "false");
        });
        tabPanels.forEach(function (p) {
          p.classList.remove("is-active");
        });
        this.classList.add("is-active");
        this.setAttribute("aria-selected", "true");
        var panel = document.getElementById("tab-" + target);
        if (panel) {
          panel.classList.add("is-active");
        }
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 4. SCROLL-TRIGGERED ANIMATIONS
   * ───────────────────────────────────────────────────────── */
  function initScrollAnimations() {
    var elements = document.querySelectorAll(".animate-on-scroll");
    if (!elements.length) {
      return;
    }

    if (!("IntersectionObserver" in window)) {
      elements.forEach(function (el) {
        el.classList.add("is-visible");
      });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.08 },
    );

    elements.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 5. HOMEPAGE CONTACT FORM (→ contactmail.php)
   * ───────────────────────────────────────────────────────── */
  function initHomepageContactForm() {
    var form = document.getElementById("contact-form");
    var successEl = document.getElementById("form-success");
    var submitBtn = document.getElementById("form-submit");

    /* Only run when NOT on the contacts page (which has .contact-form-page) */
    if (!form || form.classList.contains("contact-form-page")) {
      return;
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var name = document.getElementById("field-name").value.trim();
      var email = document.getElementById("field-email").value.trim();
      var subject = document.getElementById("field-subject").value;

      if (!name || !email || !subject) {
        alert("Please fill in your name, email and enquiry type.");
        return;
      }
      if (!isValidEmail(email)) {
        alert("Please enter a valid email address.");
        return;
      }

      submitBtn.textContent = "Sending\u2026";
      submitBtn.disabled = true;

      fetch("contactmail.php", { method: "POST", body: new FormData(form) })
        .then(function () {
          form.reset();
          showEl(successEl, 6000);
        })
        .catch(function () {
          form.reset();
          showEl(successEl, 6000);
        })
        .finally(function () {
          submitBtn.textContent = "Send Message \u2192";
          submitBtn.disabled = false;
        });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 6. CONTACTS PAGE FORM (→ reachToUs.php) + CAPTCHA
   * ───────────────────────────────────────────────────────── */
  function initContactsPageForm() {
    var form = document.getElementById("contact-form");
    var successEl = document.getElementById("form-success");
    var errorEl = document.getElementById("form-error");
    var submitBtn = document.getElementById("form-submit");
    var captchaImg = document.getElementById("captcha-img");
    var refreshBtn = document.getElementById("captcha-refresh");

    if (!form || !form.classList.contains("contact-form-page")) {
      return;
    }

    if (refreshBtn && captchaImg) {
      refreshBtn.addEventListener("click", function () {
        captchaImg.src = "mycaptcha.php?" + Date.now();
      });
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var name = document.getElementById("cp-name").value.trim();
      var email = document.getElementById("cp-email").value.trim();
      var phone = document.getElementById("cp-phone").value.trim();
      var subject = document.getElementById("cp-subject").value.trim();
      var captcha = document.getElementById("cp-captcha").value.trim();

      if (!name || !email || !phone || !subject || !captcha) {
        showError(errorEl, "Please fill in all required fields.");
        return;
      }
      if (!isValidEmail(email)) {
        showError(errorEl, "Please enter a valid email address.");
        return;
      }

      submitBtn.textContent = "Sending\u2026";
      submitBtn.disabled = true;

      fetch("reachToUs.php", {
        method: "POST",
        body: new FormData(form),
        headers: { "X-Requested-With": "XMLHttpRequest" },
      })
        .then(function (res) {
          return res.text();
        })
        .then(function (txt) {
          var t = txt.trim();
          if (t.indexOf("wrong_captcha") !== -1) {
            showError(errorEl, "Incorrect CAPTCHA code. Please try again.");
            if (captchaImg) {
              captchaImg.src = "mycaptcha.php?" + Date.now();
            }
            document.getElementById("cp-captcha").value = "";
          } else if (t.indexOf("missing_fields") !== -1) {
            showError(errorEl, "Please fill in all required fields.");
          } else if (t.indexOf("invalid_email") !== -1) {
            showError(errorEl, "Please enter a valid email address.");
          } else if (
            t.indexOf("mail_error") !== -1 ||
            t.indexOf("server_error") !== -1
          ) {
            showError(
              errorEl,
              "Could not send your message. Please call us directly on " +
                (document.querySelector(
                  '.contact-detail-card__value a[href^="tel"]',
                )
                  ? document.querySelector(
                      '.contact-detail-card__value a[href^="tel"]',
                    ).textContent
                  : "+254 724 053 548") +
                ".",
            );
          } else if (t.indexOf("ok") !== -1 || t.indexOf('"status"') !== -1) {
            form.reset();
            hideEl(errorEl);
            showEl(successEl, 8000);
            if (captchaImg) {
              captchaImg.src = "mycaptcha.php?" + Date.now();
            }
          } else {
            showError(
              errorEl,
              "Something went wrong. Please try again or call us directly.",
            );
          }
        })
        .catch(function () {
          showError(
            errorEl,
            "Something went wrong. Please try again or call us directly.",
          );
        })
        .finally(function () {
          submitBtn.textContent = "Send Your Message \u2192";
          submitBtn.disabled = false;
        });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 7. GALLERY CATEGORY FILTER
   * ───────────────────────────────────────────────────────── */
  function initGalleryFilter() {
    var filterBtns = document.querySelectorAll(".gallery-filter-btn");
    var items = document.querySelectorAll(".gallery-item");
    if (!filterBtns.length || !items.length) {
      return;
    }

    filterBtns.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var filter = this.getAttribute("data-filter");

        filterBtns.forEach(function (b) {
          b.classList.remove("is-active");
          b.setAttribute("aria-selected", "false");
        });
        this.classList.add("is-active");
        this.setAttribute("aria-selected", "true");

        items.forEach(function (item) {
          var match =
            filter === "all" || item.getAttribute("data-category") === filter;
          item.classList.toggle("is-hidden", !match);
        });
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 8. FAQ ACCORDION
   * ───────────────────────────────────────────────────────── */
  function initFaqAccordion() {
    var faqItems = document.querySelectorAll(".faq-item");
    if (!faqItems.length) {
      return;
    }

    faqItems.forEach(function (item) {
      var btn = item.querySelector(".faq-item__question");
      var answer = item.querySelector(".faq-item__answer");
      if (!btn || !answer) {
        return;
      }

      btn.addEventListener("click", function () {
        var isOpen = item.classList.contains("is-open");

        faqItems.forEach(function (other) {
          other.classList.remove("is-open");
          var ob = other.querySelector(".faq-item__question");
          var oa = other.querySelector(".faq-item__answer");
          if (ob) {
            ob.setAttribute("aria-expanded", "false");
          }
          if (oa) {
            oa.hidden = true;
          }
        });

        if (!isOpen) {
          item.classList.add("is-open");
          btn.setAttribute("aria-expanded", "true");
          answer.hidden = false;
        }
      });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * 9. MEMBERSHIP APPLICATION FORM (→ saveapply.php)
   * ───────────────────────────────────────────────────────── */
  function initApplyForm() {
    var form = document.getElementById("apply-form");
    var submitBtn = document.getElementById("apply-submit");
    var errEl = document.getElementById("apply-error");
    var successEl = document.getElementById("apply-success");
    var dupEl = document.getElementById("apply-duplicate");

    if (!form) {
      return;
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var surname = document.getElementById("af-surname").value.trim();
      var oname = document.getElementById("af-othername").value.trim();
      var email = document.getElementById("af-email").value.trim();
      var mobile = document.getElementById("af-mobile").value.trim();
      var idno = document.getElementById("af-idno").value.trim();
      var career = document.getElementById("af-career").value;
      var employer = document.getElementById("af-employer").value.trim();

      if (
        !surname ||
        !oname ||
        !email ||
        !mobile ||
        !idno ||
        !career ||
        !employer
      ) {
        showError(
          errEl,
          "Please complete all required (*) fields before submitting.",
        );
        return;
      }
      if (!isValidEmail(email)) {
        showError(errEl, "Please enter a valid email address.");
        return;
      }

      submitBtn.textContent = "Submitting\u2026";
      submitBtn.disabled = true;
      hideEl(errEl);
      hideEl(successEl);
      hideEl(dupEl);

      fetch("saveapply.php", { method: "POST", body: new FormData(form) })
        .then(function (res) {
          return res.text();
        })
        .then(function (data) {
          data = data.trim();
          if (data === "Submitted") {
            form.reset();
            showEl(successEl, 0);
            submitBtn.style.display = "none";
            successEl.scrollIntoView({ behavior: "smooth", block: "center" });
          } else if (data === "1") {
            showEl(dupEl, 0);
            dupEl.scrollIntoView({ behavior: "smooth", block: "center" });
            submitBtn.textContent = "Submit Application";
            submitBtn.disabled = false;
          } else {
            showError(
              errEl,
              "Submission failed. Please try again or contact us directly.",
            );
            submitBtn.textContent = "Submit Application";
            submitBtn.disabled = false;
          }
        })
        .catch(function () {
          showError(
            errEl,
            "A network error occurred. Please check your connection and try again.",
          );
          submitBtn.textContent = "Submit Application";
          submitBtn.disabled = false;
        });
    });
  }

  /* ─────────────────────────────────────────────────────────
   * HELPERS
   * ───────────────────────────────────────────────────────── */
  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showEl(el, autohideMs) {
    if (!el) {
      return;
    }
    el.hidden = false;
    el.removeAttribute("hidden");
    if (autohideMs) {
      setTimeout(function () {
        el.hidden = true;
      }, autohideMs);
    }
  }

  function hideEl(el) {
    if (el) {
      el.hidden = true;
    }
  }

  function showError(el, msg) {
    if (!el) {
      return;
    }
    el.textContent = msg;
    el.hidden = false;
    el.removeAttribute("hidden");
    el.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  /* ─────────────────────────────────────────────────────────
   * ANIMATED COUNT-UP for visitor counter strip
   * ───────────────────────────────────────────────────────── */
  function initCountUp() {
    var el = document.getElementById("visitor-count-display");
    if (!el) {
      return;
    }

    var target = parseInt(el.getAttribute("data-target"), 10);
    if (!target || isNaN(target)) {
      return;
    }

    /* Only animate when the element scrolls into view */
    if (!("IntersectionObserver" in window)) {
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }
          observer.unobserve(entry.target);

          var duration = 1800; /* ms */
          var start = Math.max(
            0,
            target - 500,
          ); /* count up from near the end */
          var startTs = null;

          function step(ts) {
            if (!startTs) {
              startTs = ts;
            }
            var progress = Math.min((ts - startTs) / duration, 1);
            /* Ease-out cubic */
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.round(start + (target - start) * eased);
            el.textContent = current.toLocaleString();
            if (progress < 1) {
              requestAnimationFrame(step);
            } else {
              el.textContent = target.toLocaleString();
            }
          }

          requestAnimationFrame(step);
        });
      },
      { threshold: 0.5 },
    );

    observer.observe(el);
  }

  /* ─────────────────────────────────────────────────────────
   * BOOT — single DOMContentLoaded
   * ───────────────────────────────────────────────────────── */
  document.addEventListener("DOMContentLoaded", function () {
    initMobileNav();
    initStickyNav();
    initTabs();
    initScrollAnimations();
    initHomepageContactForm();
    initContactsPageForm();
    initGalleryFilter();
    initFaqAccordion();
    initApplyForm();
    initCountUp();
  });
})();
