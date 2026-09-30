/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
	/**
	 * Mega Menu: open/close panels on hover, click, and keyboard.
	 */
	const megaItems = document.querySelectorAll( '.menu-item-has-mega' );
	let closeTimeout = null;

	function closeMegaPanel( item ) {
		item.classList.remove( 'is-open' );
		const panel = item.querySelector( '.lp-mega-panel' );
		if ( panel ) {
			panel.setAttribute( 'aria-hidden', 'true' );
		}
		const trigger = item.querySelector( ':scope > a' );
		if ( trigger ) {
			trigger.setAttribute( 'aria-expanded', 'false' );
		}
	}

	function openMegaPanel( item ) {
		// Close any other open panels first
		megaItems.forEach( function( other ) {
			if ( other !== item ) {
				closeMegaPanel( other );
			}
		} );

		item.classList.add( 'is-open' );
		const panel = item.querySelector( '.lp-mega-panel' );
		if ( panel ) {
			panel.setAttribute( 'aria-hidden', 'false' );
		}
		const trigger = item.querySelector( ':scope > a' );
		if ( trigger ) {
			trigger.setAttribute( 'aria-expanded', 'true' );
		}
	}

	function isMobile() {
		return window.getComputedStyle( button ).display !== 'none';
	}

	megaItems.forEach( function( item ) {
		const trigger = item.querySelector( ':scope > a' );

		// Desktop: hover with delay
		item.addEventListener( 'mouseenter', function() {
			if ( isMobile() ) return;
			clearTimeout( closeTimeout );
			openMegaPanel( item );
		} );

		item.addEventListener( 'mouseleave', function() {
			if ( isMobile() ) return;
			closeTimeout = setTimeout( function() {
				closeMegaPanel( item );
			}, 150 );
		} );

		// Click/tap: toggle on mobile, also works as keyboard fallback
		if ( trigger ) {
			trigger.addEventListener( 'click', function( event ) {
				if ( isMobile() || item.classList.contains( 'is-open' ) === false ) {
					// On mobile always toggle; on desktop only open if not already open
					if ( isMobile() ) {
						event.preventDefault();
						if ( item.classList.contains( 'is-open' ) ) {
							closeMegaPanel( item );
						} else {
							openMegaPanel( item );
						}
					}
				}
			} );
		}

		// Keyboard: Escape closes panel
		item.addEventListener( 'keydown', function( event ) {
			if ( event.key === 'Escape' && item.classList.contains( 'is-open' ) ) {
				closeMegaPanel( item );
				if ( trigger ) {
					trigger.focus();
				}
			}
		} );
	} );

	// Close mega panels when clicking outside
	document.addEventListener( 'click', function( event ) {
		megaItems.forEach( function( item ) {
			if ( ! item.contains( event.target ) ) {
				closeMegaPanel( item );
			}
		} );
	} );

}() );
