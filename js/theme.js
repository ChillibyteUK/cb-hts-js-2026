/*!
 * cb-hts-js-2026 v1.0.0 (https://github.com/LamcatUK/cb-hts-js-2026)
 * Copyright 2026 LamcatUK
 * Licensed under GPL-3.0
 */
(function () {
	'use strict';

	/**
	 * Mobile nav toggle. Wires any button with aria-controls pointing at a
	 * .navbar-collapse to show/hide it and keep aria-expanded in sync — this is
	 * the entire replacement for Bootstrap's Collapse component for this use case.
	 */
	function initNavToggle() {
	  document.querySelectorAll('.navbar-toggler[aria-controls]').forEach(toggler => {
	    const target = document.getElementById(toggler.getAttribute('aria-controls'));
	    if (!target) return;
	    toggler.addEventListener('click', () => {
	      const isOpen = target.classList.toggle('is-open');
	      toggler.setAttribute('aria-expanded', String(isOpen));
	    });

	    // Close after choosing a link — expected mobile nav behaviour.
	    target.querySelectorAll('a').forEach(link => {
	      link.addEventListener('click', () => {
	        target.classList.remove('is-open');
	        toggler.setAttribute('aria-expanded', 'false');
	      });
	    });
	  });
	}

	/**
	 * Click-to-open nav dropdowns. Each dropdown-toggle button shows/hides its
	 * linked .dropdown-menu and keeps aria-expanded in sync. Clicking elsewhere,
	 * or pressing Escape, closes whatever is open — this is the entire
	 * replacement for hover-based submenus.
	 */
	function initNavDropdowns() {
	  const toggles = document.querySelectorAll('.dropdown-toggle[aria-controls]');
	  function close(toggle) {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    menu.classList.remove('is-open');
	    toggle.setAttribute('aria-expanded', 'false');
	  }
	  function closeAllExcept(except) {
	    toggles.forEach(toggle => {
	      if (toggle !== except) close(toggle);
	    });
	  }
	  toggles.forEach(toggle => {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    toggle.addEventListener('click', event => {
	      event.stopPropagation();
	      const isOpen = menu.classList.toggle('is-open');
	      toggle.setAttribute('aria-expanded', String(isOpen));
	      closeAllExcept(toggle);
	    });
	  });
	  document.addEventListener('click', event => {
	    if (event.target.closest('.dropdown-menu')) return;
	    closeAllExcept();
	  });
	  document.addEventListener('keydown', event => {
	    if (event.key !== 'Escape') return;
	    const openToggle = Array.from(toggles).find(toggle => toggle.getAttribute('aria-expanded') === 'true');
	    closeAllExcept();
	    if (openToggle) openToggle.focus();
	  });
	}

	/**
	 * Native <dialog> wiring — replaces Bootstrap's Modal component entirely.
	 * showModal()/close() do the heavy lifting (focus trap, Escape-to-close,
	 * ::backdrop); this just connects trigger/close buttons to a target dialog.
	 *
	 * Markup:
	 *   <button data-dialog-target="my-dialog">Open</button>
	 *   <dialog id="my-dialog">
	 *     <button data-dialog-close>Close</button>
	 *     ...
	 *   </dialog>
	 */
	function initDialogs() {
	  document.querySelectorAll('[data-dialog-target]').forEach(trigger => {
	    const dialog = document.getElementById(trigger.getAttribute('data-dialog-target'));
	    if (!(dialog instanceof HTMLDialogElement)) return;
	    trigger.addEventListener('click', () => dialog.showModal());
	    dialog.querySelectorAll('[data-dialog-close]').forEach(closeBtn => {
	      closeBtn.addEventListener('click', () => dialog.close());
	    });

	    // Click on the backdrop (the dialog element itself, outside its content) closes it.
	    dialog.addEventListener('click', event => {
	      if (event.target === dialog) dialog.close();
	    });
	  });
	}

	/**
	 * Accordion toggle — replaces Bootstrap's Collapse-driven accordion
	 * component entirely (no data-bs-toggle, no Bootstrap JS). Keeps
	 * Bootstrap's own class vocabulary (.accordion-button.collapsed,
	 * .accordion-collapse.show) since the CSS is written against it, same
	 * "Bootstrap-style class names, zero Bootstrap" approach as nav-toggle.js.
	 *
	 * One panel open at a time per .accordion, matching Bootstrap's
	 * data-bs-parent behaviour — closing every other open panel in the same
	 * .accordion when one opens.
	 */
	function initAccordions() {
	  document.querySelectorAll('.accordion').forEach(accordion => {
	    accordion.querySelectorAll('.accordion-button').forEach(button => {
	      button.addEventListener('click', () => {
	        const panel = document.getElementById(button.getAttribute('aria-controls'));
	        if (!panel) return;
	        const isOpen = !button.classList.contains('collapsed');
	        accordion.querySelectorAll('.accordion-button:not(.collapsed)').forEach(openButton => {
	          if (openButton === button) return;
	          openButton.classList.add('collapsed');
	          openButton.setAttribute('aria-expanded', 'false');
	          const openPanel = document.getElementById(openButton.getAttribute('aria-controls'));
	          if (openPanel) openPanel.classList.remove('show');
	        });
	        button.classList.toggle('collapsed', isOpen);
	        button.setAttribute('aria-expanded', String(!isOpen));
	        panel.classList.toggle('show', !isOpen);
	      });
	    });
	  });
	}

	/**
	 * Lenis smooth scroll. Ported from cb-hts2026's inline wp_footer script —
	 * same options (smooth/lerp), same window.lenis + raf loop — as a proper
	 * module here instead, since `lenis` is enqueued as a real script dependency
	 * (see inc/enqueue.php) rather than relied on via load order.
	 */
	function initLenis() {
	  if (typeof Lenis === 'undefined') return;
	  const lenis = new Lenis({
	    smooth: true,
	    lerp: 0.1
	  });
	  window.lenis = lenis;
	  function raf(time) {
	    lenis.raf(time);
	    requestAnimationFrame(raf);
	  }
	  requestAnimationFrame(raf);
	}

	document.addEventListener('DOMContentLoaded', () => {
	  initNavToggle();
	  initNavDropdowns();
	  initDialogs();
	  initAccordions();
	  initLenis();
	});

})();
//# sourceMappingURL=theme.js.map
