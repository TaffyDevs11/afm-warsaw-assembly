/**
 * main.js
 * -----------------------------------------------------------------------
 * Shared front-end behaviour for every public page:
 *   - Fades out the initial page loader once the page has rendered
 *   - Mobile navigation toggle (hamburger menu + dropdown expand)
 *   - Scroll-triggered "reveal" animations for any element with
 *     class="reveal" (and its reveal--left/right/scale variants)
 *   - Adds a subtle "scrolled" state to the header
 *
 * Kept dependency-free (vanilla JS) and uses IntersectionObserver, which
 * is well supported and cheap on mobile devices.
 * -----------------------------------------------------------------------
 */
(function () {
  'use strict';

  // ---- Page loader -------------------------------------------------
  function hideLoader() {
    document.body.classList.add('is-loaded');
  }
  if (document.readyState === 'complete') {
    hideLoader();
  } else {
    window.addEventListener('load', function () {
      // Small delay so the loader animation is visible even on fast connections.
      setTimeout(hideLoader, 350);
    });
  }

  // ---- Mobile nav toggle --------------------------------------------
  var navToggle = document.getElementById('navToggle');
  var primaryNav = document.getElementById('primaryNav');

  if (navToggle && primaryNav) {
    navToggle.addEventListener('click', function () {
      var isOpen = primaryNav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // On mobile, tapping a dropdown parent link expands its children
    // instead of navigating away immediately.
    var dropdownParents = primaryNav.querySelectorAll('.has-dropdown > a');
    dropdownParents.forEach(function (link) {
      link.addEventListener('click', function (e) {
        if (window.innerWidth <= 860) {
          var parentLi = link.parentElement;
          var alreadyExpanded = parentLi.classList.contains('is-expanded');
          if (!alreadyExpanded) {
            e.preventDefault();
            parentLi.classList.add('is-expanded');
          }
        }
      });
    });
  }

  // ---- Sticky header shadow on scroll --------------------------------
  var header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', function () {
      header.style.boxShadow = window.scrollY > 12 ? '0 6px 20px rgba(12,24,54,0.10)' : 'none';
    }, { passive: true });
  }

  // ---- Scroll-triggered reveal animations ----------------------------
  var revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(function (el, index) {
      // Slight stagger for elements revealed together (e.g. grid cards).
      el.style.transitionDelay = (index % 6) * 70 + 'ms';
      observer.observe(el);
    });
  } else {
    // Fallback: no IntersectionObserver support, just show everything.
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }
})();
