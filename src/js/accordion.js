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
export function initAccordions() {
	document.querySelectorAll('.accordion').forEach((accordion) => {
		accordion.querySelectorAll('.accordion-button').forEach((button) => {
			button.addEventListener('click', () => {
				const panel = document.getElementById(button.getAttribute('aria-controls'));
				if (!panel) return;

				const isOpen = !button.classList.contains('collapsed');

				accordion.querySelectorAll('.accordion-button:not(.collapsed)').forEach((openButton) => {
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
