/**
 * Accordions block.
 *
 * Toggles `.is-open` + aria-expanded on click. Uses event delegation so it
 * also works for block previews re-rendered inside the editor.
 */
(function () {
	'use strict';

	if (window.ckAccordionReady) {
		return;
	}
	window.ckAccordionReady = true;

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('.ck-accordion__trigger');
		if (!trigger) {
			return;
		}

		const item = trigger.closest('.ck-accordion__item');
		const isOpen = item.classList.toggle('is-open');
		trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
	});
})();
