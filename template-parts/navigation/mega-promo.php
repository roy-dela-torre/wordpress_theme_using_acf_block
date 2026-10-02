<?php
/**
 * Mega Menu - Promo Column
 *
 * Renders the branded left column of the mega menu panel.
 * Expects $mega_item (WP_Post nav menu item) via set_query_var().
 *
 * @package chusie-kokoro
 */

$mega_item = get_query_var( 'mega_item' );
if ( ! $mega_item ) return;

$heading     = esc_html( $mega_item->title );
$description = $mega_item->description ? wp_kses_post( $mega_item->description ) : '';
$url         = esc_url( $mega_item->url );
?>

<div class="ck-mega-panel__promo">
	<?php if ( $heading ) : ?>
		<h3 class="ck-mega-panel__promo-heading"><?= $heading; ?></h3>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="ck-mega-panel__promo-body"><?= $description; ?></p>
	<?php endif; ?>

	<?php if ( $url && $url !== '#' ) : ?>
		<a href="<?= $url; ?>" class="ck-mega-panel__promo-btn"><?= esc_html( $mega_item->attr_title ?: 'More About Us' ); ?></a>
	<?php endif; ?>
</div>
