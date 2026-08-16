/**
 * gallery.js
 * -----------------------------------------------------------------------
 * Powers the public Gallery page: clicking a masonry photo opens it in a
 * simple full-screen lightbox. The masonry layout itself is pure CSS
 * (column-count), so this file only handles the lightbox interaction.
 * -----------------------------------------------------------------------
 */
(function () {
  'use strict';

  var lightbox = document.getElementById('lightbox');
  if (!lightbox) return;

  var lightboxImg = document.getElementById('lightboxImage');
  var closeBtn = lightbox.querySelector('.lightbox__close');
  var items = document.querySelectorAll('.masonry__item img');

  function openLightbox(src, alt) {
    lightboxImg.src = src;
    lightboxImg.alt = alt || '';
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.classList.remove('is-open');
    lightboxImg.src = '';
    document.body.style.overflow = '';
  }

  items.forEach(function (img) {
    img.addEventListener('click', function () {
      openLightbox(img.getAttribute('src'), img.getAttribute('alt'));
    });
  });

  if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) closeLightbox();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
  });
})();
