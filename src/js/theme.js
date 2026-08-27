import { initNavToggle } from './nav-toggle';
import { initNavDropdowns } from './nav-dropdown';
import { initDialogs } from './dialog';
import { initAccordions } from './accordion';
import { initLenis } from './lenis-init';

document.addEventListener('DOMContentLoaded', () => {
	initNavToggle();
	initNavDropdowns();
	initDialogs();
	initAccordions();
	initLenis();
});
