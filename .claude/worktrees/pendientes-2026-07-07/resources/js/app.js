import './bootstrap';

// Loading state en formularios
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('[type="submit"]');
      if (btn) btn.classList.add('btn-loading');
    });
  });
});
