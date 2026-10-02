<?php
/**
 * Custom Walker for Mega Menu Navigation.
 *
 * Transforms standard WordPress submenus into mega menu panels
 * with an optional promo column and a grid of link cards.
 *
 * @package chusie-kokoro
 */

class Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Track whether we're inside the card grid wrapper.
	 */
	private $grid_open = false;

	/**
	 * Opens the sub-menu wrapper.
	 * At depth 0, outputs a mega panel div instead of <ul class="sub-menu">.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<div class="ck-mega-panel" aria-hidden="true">';
			$this->grid_open = false;
		} else {
			// Fallback for unexpected deeper levels
			$output .= '<ul class="sub-menu">';
		}
	}

	/**
	 * Closes the sub-menu wrapper.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			// Close the grid wrapper if it was opened
			if ( $this->grid_open ) {
				$output .= '</div><!-- .ck-mega-panel__grid -->';
				$this->grid_open = false;
			}
			$output .= '</div><!-- .ck-mega-panel -->';
		} else {
			$output .= '</ul>';
		}
	}

	/**
	 * Outputs a single menu item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth === 0 ) {
			$this->render_top_level_item( $output, $item, $args );
		} elseif ( $depth === 1 ) {
			$is_promo = in_array( 'mega-promo', (array) $item->classes, true );

			if ( $is_promo ) {
				$this->render_promo_item( $output, $item );
			} else {
				// Open the grid wrapper before the first card
				if ( ! $this->grid_open ) {
					$output .= '<div class="ck-mega-panel__grid">';
					$this->grid_open = true;
				}
				$this->render_card_item( $output, $item );
			}
		} else {
			// Fallback for unexpected deeper levels
			$output .= '<li class="' . esc_attr( implode( ' ', $item->classes ) ) . '">';
			$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
		}
	}

	/**
	 * Closes a single menu item.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</li>';
		}
		// Depth 1 items (promo/cards) are self-closing divs/anchors — no end_el needed.
	}

	/**
	 * Renders a top-level navigation item.
	 */
	private function render_top_level_item( &$output, $item, $args ) {
		$classes   = (array) $item->classes;
		$classes[] = 'menu-item';

		// Add mega class if this item has children
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'menu-item-has-mega';
		}

		$class_attr = implode( ' ', array_filter( $classes ) );

		$output .= '<li class="' . esc_attr( $class_attr ) . '">';
		$output .= '<a href="' . esc_url( $item->url ) . '">';
		$output .= esc_html( $item->title );

		// Add dropdown arrow for items with children
		if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$output .= ' <span class="ck-mega-arrow"></span>';
		}

		$output .= '</a>';
	}

	/**
	 * Renders the promo column using a template part.
	 */
	private function render_promo_item( &$output, $item ) {
		// Close the grid if it was already opened (promo should come before grid)
		if ( $this->grid_open ) {
			$output .= '</div><!-- .ck-mega-panel__grid -->';
			$this->grid_open = false;
		}

		ob_start();
		set_query_var( 'mega_item', $item );
		get_template_part( 'template-parts/navigation/mega-promo' );
		$output .= ob_get_clean();
	}

	/**
	 * Renders a link card using a template part.
	 */
	private function render_card_item( &$output, $item ) {
		ob_start();
		set_query_var( 'mega_item', $item );
		get_template_part( 'template-parts/navigation/mega-card' );
		$output .= ob_get_clean();
	}
}
