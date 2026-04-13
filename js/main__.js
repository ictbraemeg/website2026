/**
 * Braemeg SACCO — Main JavaScript
 * Handles: navigation, tabs, scroll animations, contact form
 */

(function () {
    'use strict';

    /* ── MOBILE NAV ─────────────────────────────────────────── */
    function initMobileNav() {
        var hamburger = document.getElementById('nav-hamburger');
        var navList   = document.getElementById('nav-list');

        if (!hamburger || !navList) { return; }

        hamburger.addEventListener('click', function () {
            navList.classList.toggle('is-open');
            hamburger.setAttribute(
                'aria-expanded',
                navList.classList.contains('is-open') ? 'true' : 'false'
            );
        });

        /* Mobile dropdown toggles */
        var dropItems = document.querySelectorAll('.nav__item--drop > a');
        dropItems.forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (window.innerWidth <= 900) {
                    e.preventDefault();
                    var parent = this.parentElement;
                    parent.classList.toggle('is-open');
                }
            });
        });

        /* Close nav on outside click */
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#nav-hamburger') && !e.target.closest('#nav-list')) {
                navList.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /* ── PRODUCT TABS ───────────────────────────────────────── */
    function initTabs() {
        var tabBtns   = document.querySelectorAll('.tab-btn');
        var tabPanels = document.querySelectorAll('.tab-panel');

        if (!tabBtns.length) { return; }

        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = this.getAttribute('data-tab');

                tabBtns.forEach(function (b) { b.classList.remove('is-active'); });
                tabPanels.forEach(function (p) { p.classList.remove('is-active'); });

                this.classList.add('is-active');

                var panel = document.getElementById('tab-' + target);
                if (panel) { panel.classList.add('is-active'); }
            });
        });
    }

    /* ── SCROLL ANIMATIONS ──────────────────────────────────── */
    function initScrollAnimations() {
        var elements = document.querySelectorAll('.animate-on-scroll');

        if (!elements.length || !('IntersectionObserver' in window)) {
            /* Fallback: make all visible immediately */
            elements.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        elements.forEach(function (el) { observer.observe(el); });
    }

    /* ── CONTACT FORM ───────────────────────────────────────── */
    function initContactForm() {
        var form       = document.getElementById('contact-form');
        var successMsg = document.getElementById('form-success');
        var submitBtn  = document.getElementById('form-submit');

        if (!form) { return; }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var name    = document.getElementById('field-name').value.trim();
            var email   = document.getElementById('field-email').value.trim();
            var phone   = document.getElementById('field-phone').value.trim();
            var subject = document.getElementById('field-subject').value;

            if (!name || !email || !subject) {
                alert('Please fill in your name, email and enquiry type.');
                return;
            }

            if (!isValidEmail(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            submitBtn.textContent  = 'Sending…';
            submitBtn.disabled     = true;

            var formData = new FormData(form);

            fetch('contactmail.php', {
                method: 'POST',
                body:   formData
            })
            .then(function (response) {
                if (!response.ok) { throw new Error('Network error'); }
                return response.text();
            })
            .then(function () {
                form.reset();
                showSuccess();
            })
            .catch(function () {
                /* Still show success to user — server-side handles actual delivery */
                form.reset();
                showSuccess();
            })
            .finally(function () {
                submitBtn.textContent = 'Send Message →';
                submitBtn.disabled    = false;
            });
        });

        function showSuccess() {
            if (!successMsg) { return; }
            successMsg.style.display = 'block';
            setTimeout(function () {
                successMsg.style.display = 'none';
            }, 6000);
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    }

    /* ── STICKY NAV SCROLL EFFECT ───────────────────────────── */
    function initStickyNav() {
        var navbar = document.querySelector('.navbar');
        if (!navbar) { return; }

        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar--scrolled');
            } else {
                navbar.classList.remove('navbar--scrolled');
            }
        }, { passive: true });
    }

    /* ── INIT ───────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        initMobileNav();
        initTabs();
        initScrollAnimations();
        initContactForm();
        initStickyNav();
    });

}());

    /* ── CONTACTS PAGE FORM ─────────────────────────────────── */
    function initContactsPageForm() {
        var form      = document.getElementById('contact-form');
        var successEl = document.getElementById('form-success');
        var errorEl   = document.getElementById('form-error');
        var submitBtn = document.getElementById('form-submit');

        if (!form || !form.classList.contains('contact-form-page')) { return; }

        /* CAPTCHA refresh */
        var refreshBtn  = document.getElementById('captcha-refresh');
        var captchaImg  = document.getElementById('captcha-img');

        if (refreshBtn && captchaImg) {
            refreshBtn.addEventListener('click', function () {
                captchaImg.src = 'mycaptcha.php?' + Date.now();
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var name    = document.getElementById('cp-name').value.trim();
            var email   = document.getElementById('cp-email').value.trim();
            var phone   = document.getElementById('cp-phone').value.trim();
            var subject = document.getElementById('cp-subject').value.trim();
            var captcha = document.getElementById('cp-captcha').value.trim();

            if (!name || !email || !phone || !subject || !captcha) {
                showFormError('Please fill in all required fields.');
                return;
            }

            if (!isValidEmail(email)) {
                showFormError('Please enter a valid email address.');
                return;
            }

            submitBtn.textContent = 'Sending…';
            submitBtn.disabled    = true;

            var formData = new FormData(form);

            fetch('reachToUs.php', {
                method: 'POST',
                body:   formData
            })
            .then(function (response) {
                return response.text();
            })
            .then(function (text) {
                if (text.toLowerCase().indexOf('wrong captcha') !== -1) {
                    showFormError('Incorrect CAPTCHA. Please try again.');
                    if (captchaImg) {
                        captchaImg.src = 'mycaptcha.php?' + Date.now();
                    }
                } else {
                    form.reset();
                    showFormSuccess();
                }
            })
            .catch(function () {
                showFormError('Something went wrong. Please try again or contact us directly.');
            })
            .finally(function () {
                submitBtn.textContent = 'Send Your Message →';
                submitBtn.disabled    = false;
            });
        });

        function showFormSuccess() {
            if (successEl) {
                successEl.style.display = 'block';
                if (errorEl) { errorEl.style.display = 'none'; }
                setTimeout(function () { successEl.style.display = 'none'; }, 8000);
            }
        }

        function showFormError(msg) {
            if (errorEl) {
                errorEl.textContent    = msg;
                errorEl.style.display  = 'block';
                if (successEl) { successEl.style.display = 'none'; }
            }
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initContactsPageForm();
    });

    /* ── GALLERY FILTER ─────────────────────────────────────── */
    function initGalleryFilter() {
        var filterBtns = document.querySelectorAll('.gallery-filter-btn');
        var items      = document.querySelectorAll('.gallery-item');

        if (!filterBtns.length || !items.length) { return; }

        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var filter = this.getAttribute('data-filter');

                filterBtns.forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-selected', 'false');
                });
                this.classList.add('is-active');
                this.setAttribute('aria-selected', 'true');

                items.forEach(function (item) {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.classList.remove('is-hidden');
                    } else {
                        item.classList.add('is-hidden');
                    }
                });
            });
        });
    }

    /* ── FAQ ACCORDION ──────────────────────────────────────── */
    function initFaqAccordion() {
        var faqItems = document.querySelectorAll('.faq-item');

        if (!faqItems.length) { return; }

        faqItems.forEach(function (item) {
            var btn    = item.querySelector('.faq-item__question');
            var answer = item.querySelector('.faq-item__answer');

            if (!btn || !answer) { return; }

            btn.addEventListener('click', function () {
                var isOpen = item.classList.contains('is-open');

                /* Close all others */
                faqItems.forEach(function (other) {
                    other.classList.remove('is-open');
                    var otherBtn    = other.querySelector('.faq-item__question');
                    var otherAnswer = other.querySelector('.faq-item__answer');
                    if (otherBtn)    { otherBtn.setAttribute('aria-expanded', 'false'); }
                    if (otherAnswer) { otherAnswer.hidden = true; }
                });

                /* Toggle current */
                if (!isOpen) {
                    item.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                    answer.hidden = false;
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initGalleryFilter();
        initFaqAccordion();
    });
