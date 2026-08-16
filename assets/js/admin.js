/**
 * admin.js
 * -----------------------------------------------------------------------
 * Small UX helpers for the admin panel:
 *   - Sidebar toggle on small screens
 *   - Confirmation prompt before any "Delete" action
 *   - Live image preview when choosing a file to upload
 * -----------------------------------------------------------------------
 */
(function () {
  'use strict';

  var sidebar = document.getElementById('adminSidebar');
  var toggle = document.getElementById('adminSidebarToggle');
  if (sidebar && toggle) {
    toggle.addEventListener('click', function () {
      var isOpen = sidebar.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  // Any form/button with data-confirm="..." asks before submitting.
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('submit', function (e) {
      if (!window.confirm(el.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });

  // File inputs with data-preview="#targetId" show a live thumbnail preview.
  document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var target = document.querySelector(input.getAttribute('data-preview'));
      if (!target || !input.files || !input.files[0]) return;
      var reader = new FileReader();
      reader.onload = function (e) {
        target.src = e.target.result;
        target.style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    });
  });
})();
