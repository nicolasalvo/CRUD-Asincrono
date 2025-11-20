// Header injection for site-wide logout button (shows on all pages except login.php)
(function () {
  try {
    const path = window.location.pathname || '';
    // Do not show on the login page
    if (path.includes('login.php') || path.includes('login.html')) return;

    // Ensure we don't add the button multiple times
    if (document.getElementById('btn-logout')) return;

    // Create container and button
    const container = document.createElement('div');
    container.className = 'header-actions';

    const btn = document.createElement('button');
    btn.id = 'btn-logout';
    btn.type = 'button';
    btn.className = 'boton-secundario';
    btn.hidden = true;
    btn.setAttribute('aria-label', 'Cerrar sesión');
    btn.textContent = 'Cerrar sesión';

    container.appendChild(btn);

    // Try to find an existing header to append into; otherwise put at top of body
    const header = document.querySelector('.encabezado-aplicacion') || document.querySelector('header');
    if (header) {
      header.appendChild(container);
    } else {
      // insert at top
      document.body.insertBefore(container, document.body.firstChild);
    }

    const role = localStorage.getItem('userRole');
    if (role) btn.hidden = false;

    btn.addEventListener('click', (e) => {
      e.preventDefault();
      try {
        localStorage.removeItem('userRole');
        // Optional: remove any other app keys
      } catch (err) {
        console.warn('Error clearing localStorage', err);
      }
      // Redirect to login page
      window.location.href = '/login.php';
    });

    // Optionally observe changes to localStorage from other tabs
    window.addEventListener('storage', (ev) => {
      if (ev.key === 'userRole') {
        const has = Boolean(ev.newValue);
        btn.hidden = !has;
      }
    });
  } catch (err) {
    // Fail silently
    console.error('header.js error', err);
  }
})();
