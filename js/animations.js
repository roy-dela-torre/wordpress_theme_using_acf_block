/**
 * File animations.js.
 *
 * Scroll reveal for Chusie Kokoro blocks. Pairs with sass/components/_animations.scss.
 *
 * - Every `.ck-block[data-animate]` section gets its items tagged with `.ck-anim`:
 *   the direct children of `.block-contents`, plus the children of any
 *   `[data-stagger]` container (cards, columns, stats, …).
 * - Each item is revealed when it scrolls into view. Items that enter together
 *   are staggered in DOM order.
 * - After the entrance finishes the helper class is removed, so hover
 *   transitions on cards/buttons are not slowed down by the reveal delay.
 */
(function () {
	'use strict';

	window.ckAnimReady = true;

	const SECTION_SELECTOR = '.ck-block[data-animate]:not([data-animate="none"])';
	const SKIP = 'script, style, template, noscript, link';
	const MAX_STAGGER = 6;

	const sections = document.querySelectorAll(SECTION_SELECTOR);
	if (!sections.length) {
		return;
	}

	const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// No animation: show everything straight away.
	if (reduceMotion || !('IntersectionObserver' in window)) {
		sections.forEach((section) => section.classList.add('ck-anim-ready', 'is-inview'));
		return;
	}

	/**
	 * Collects the elements to animate inside a section.
	 */
	const collectItems = (section) => {
		const contents = section.querySelector(':scope > .block-contents');
		if (!contents) {
			return [];
		}

		const items = [];
		const add = (el) => {
			if (!el.matches(SKIP)) {
				items.push(el);
			}
		};

		Array.from(contents.children).forEach((child) => {
			if (child.hasAttribute('data-stagger')) {
				Array.from(child.children).forEach(add);
			} else {
				add(child);
			}

			// Nested stagger containers inside a column, e.g. stats in a content column.
			child.querySelectorAll('[data-stagger]').forEach((group) => {
				Array.from(group.children).forEach(add);
			});
		});

		return items;
	};

	/**
	 * Cleans up once an item has finished its entrance.
	 */
	const finish = (el) => {
		const done = (event) => {
			if (event.target !== el) {
				return;
			}
			el.removeEventListener('transitionend', done);
			el.classList.remove('ck-anim', 'is-inview');
			el.style.removeProperty('--ck-anim-i');
		};
		el.addEventListener('transitionend', done);
	};

	const itemObserver = new IntersectionObserver((entries) => {
		const visible = entries
			.filter((entry) => entry.isIntersecting)
			.map((entry) => entry.target)
			.sort((a, b) => (a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1));

		visible.forEach((el, index) => {
			el.style.setProperty('--ck-anim-i', Math.min(index, MAX_STAGGER));
			el.classList.add('is-inview');
			itemObserver.unobserve(el);
			finish(el);
		});
	}, {
		rootMargin: '0px 0px -10% 0px',
		threshold: 0.1,
	});

	// Sections are observed too, so their background image can fade in.
	const sectionObserver = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-inview');
				sectionObserver.unobserve(entry.target);
			}
		});
	}, {
		threshold: 0,
	});

	sections.forEach((section) => {
		collectItems(section).forEach((item) => {
			item.classList.add('ck-anim');
			itemObserver.observe(item);
		});
		section.classList.add('ck-anim-ready');
		sectionObserver.observe(section);
	});
})();
