/**
 * File embed-facade.js.
 *
 * Swaps a `.ck-embed-facade` poster for the real iframe when clicked.
 * Markup comes from ck_embed_facade() in inc/embed-facade.php.
 * Uses event delegation so it also works for block previews in the editor.
 */
(function () {
	'use strict';

	const FACADE = '[data-ck-embed]';
	const TRIGGER = '.ck-embed-facade__trigger';
	const POSTER = '.ck-embed-facade__poster';

	/**
	 * Creates the iframe and hands focus to it.
	 */
	const load = (facade) => {
		if (!facade || facade.classList.contains('is-loaded')) {
			return;
		}

		const iframe = document.createElement('iframe');
		iframe.src = facade.dataset.src;
		iframe.title = facade.dataset.title || '';
		iframe.allowFullscreen = true;
		iframe.setAttribute('loading', 'eager');

		if (facade.dataset.allow) {
			iframe.setAttribute('allow', facade.dataset.allow);
		}
		if (facade.dataset.referrerpolicy) {
			iframe.setAttribute('referrerpolicy', facade.dataset.referrerpolicy);
		}

		facade.appendChild(iframe);
		facade.classList.add('is-loaded');
		iframe.focus();
	};

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest(TRIGGER);
		if (!trigger) {
			return;
		}
		event.preventDefault();
		load(trigger.closest(FACADE));
	});

	/**
	 * Warm up the connection as soon as the visitor shows intent (hover/focus),
	 * so the player starts faster after the click.
	 */
	const warmed = new Set();
	const warm = (event) => {
		const trigger = event.target.closest && event.target.closest(TRIGGER);
		if (!trigger) {
			return;
		}

		const facade = trigger.closest(FACADE);
		let origin;
		try {
			origin = new URL(facade.dataset.src).origin;
		} catch (e) {
			return;
		}

		if (warmed.has(origin)) {
			return;
		}
		warmed.add(origin);

		const link = document.createElement('link');
		link.rel = 'preconnect';
		link.href = origin;
		document.head.appendChild(link);
	};

	document.addEventListener('pointerover', warm, { passive: true });
	document.addEventListener('focusin', warm);

	/**
	 * YouTube only has a max-res thumbnail for some videos. When it is missing
	 * YouTube returns a tiny grey placeholder, so fall back to hqdefault.
	 */
	const checkPoster = (img) => {
		const fallback = img.dataset.fallback;
		if (!fallback || img.src === fallback) {
			return;
		}
		if (img.complete && img.naturalWidth > 0 && img.naturalWidth < 200) {
			img.src = fallback;
		}
	};

	document.addEventListener('load', (event) => {
		if (event.target.matches && event.target.matches(POSTER)) {
			checkPoster(event.target);
		}
	}, true);

	document.addEventListener('error', (event) => {
		const img = event.target;
		if (img.matches && img.matches(POSTER) && img.dataset.fallback && img.src !== img.dataset.fallback) {
			img.src = img.dataset.fallback;
		}
	}, true);

	document.querySelectorAll(POSTER + '[data-fallback]').forEach(checkPoster);
})();
