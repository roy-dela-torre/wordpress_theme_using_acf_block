/**
 * Accordions block.
 *
 * Toggles `.is-open` + aria-expanded on click. Uses event delegation so it
 * also works for block previews re-rendered inside the editor.
 */
(function () {
	'use strict';

	if (window.lpAccordionReady) {
		return;
	}
	window.lpAccordionReady = true;

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('.lp-accordion__trigger');
		if (!trigger) {
			return;
		}

		const item = trigger.closest('.lp-accordion__item');
		const isOpen = item.classList.toggle('is-open');
		trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
	});
})();
