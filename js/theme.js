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

	/**
	 * single.php's quick-links sidebar: marks the link for whichever H2 section
	 * is currently in view. IntersectionObserver, not a scroll listener —
	 * cheaper, and immune to the usual "which element is 'current' while
	 * scrolling fast" edge cases a naive scroll-position comparison runs into.
	 */
	function initToc() {
	  const links = document.querySelectorAll('.toc__link');
	  if (!links.length) return;
	  const linksById = new Map();
	  const targets = [];
	  links.forEach(link => {
	    const id = link.getAttribute('href').slice(1);
	    const target = document.getElementById(id);
	    if (!target) return;
	    linksById.set(id, link);
	    targets.push(target);
	  });
	  if (!targets.length) return;
	  function setActive(id) {
	    linksById.forEach((link, linkId) => {
	      link.classList.toggle('is-active', linkId === id);
	    });
	  }

	  // Headings crossing a line just below the sticky header, rather than
	  // "anywhere in the viewport" — that band is what actually reads as
	  // "the section you're currently at" while scrolling.
	  const observer = new IntersectionObserver(entries => {
	    entries.forEach(entry => {
	      if (entry.isIntersecting) {
	        setActive(entry.target.id);
	      }
	    });
	  }, {
	    rootMargin: '-15% 0px -70% 0px'
	  });
	  targets.forEach(target => observer.observe(target));

	  // Nothing has crossed the line yet on initial load (e.g. page opened
	  // already scrolled to a #hash) — fall back to the first heading so a
	  // link is always marked active rather than none at all.
	  setActive(targets[0].id);
	}

	/**
	 * Clamped watermark parallax + settle for section background words.
	 *
	 * Any <section data-watermark> containing a .{block}-watermark > span word
	 * gets two behaviours, both driven here so every watermark block shares one
	 * implementation instead of per-instance inline scripts:
	 *
	 * 1. Settle (adds .is-in-view once, via IntersectionObserver) — the CSS
	 *    fades/scales the word in. Same pattern the old inline scripts used.
	 * 2. Travel (scroll listener) — translates the watermark div so the word
	 *    tracks the viewport like position:sticky did, but clamped to
	 *    [0, sectionHeight - wordHeight]: the word can never cross its
	 *    section's bounds, which is the bleed sticky caused (seen live when an
	 *    intro WHAT painted over the section below). The word is a real <span>
	 *    (not a ::before) precisely so its height is measurable — clamping
	 *    against an estimate would reintroduce the same bug at odd font sizes.
	 *
	 * Bails entirely under prefers-reduced-motion (word stays put, faint).
	 */
	function initWatermarks() {
	  const sections = document.querySelectorAll('section[data-watermark]');
	  if (!sections.length) return;
	  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	    return;
	  }

	  // Viewport offset the word tracks, mirroring the old sticky top.
	  const navHeight = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) * parseFloat(getComputedStyle(document.documentElement).fontSize) || 0;
	  const items = [];
	  sections.forEach(section => {
	    const mover = section.querySelector(':scope > [class$="-watermark"]');
	    const word = mover ? mover.querySelector(':scope > span') : null;
	    if (!mover || !word) return;
	    items.push({
	      section,
	      mover,
	      word
	    });
	  });
	  if (!items.length) return;
	  function update() {
	    const viewportHeight = window.innerHeight;
	    items.forEach(({
	      section,
	      mover,
	      word
	    }) => {
	      const rect = section.getBoundingClientRect();
	      if (rect.bottom <= 0 || rect.top >= viewportHeight) return;
	      const maxTravel = Math.max(0, section.offsetHeight - word.offsetHeight);
	      const target = Math.min(Math.max(navHeight - rect.top, 0), maxTravel);
	      mover.style.transform = `translate3d(0, ${target.toFixed(1)}px, 0)`;
	    });
	    ticking = false;
	  }
	  let ticking = false;
	  function onScroll() {
	    if (!ticking) {
	      window.requestAnimationFrame(update);
	      ticking = true;
	    }
	  }
	  const observer = new IntersectionObserver(entries => {
	    entries.forEach(entry => {
	      if (!entry.isIntersecting) return;
	      entry.target.classList.add('is-in-view');
	      observer.unobserve(entry.target);
	    });
	  }, {
	    threshold: 0.2,
	    rootMargin: '0px 0px -10% 0px'
	  });
	  items.forEach(({
	    section
	  }) => observer.observe(section));
	  window.addEventListener('scroll', onScroll, {
	    passive: true
	  });
	  window.addEventListener('resize', onScroll);
	  onScroll();
	}

	document.addEventListener('DOMContentLoaded', () => {
	  initNavToggle();
	  initNavDropdowns();
	  initDialogs();
	  initAccordions();
	  initLenis();
	  initToc();
	  initWatermarks();
	});

})();
//# sourceMappingURL=theme.js.map
