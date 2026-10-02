<?php
/**
 * Mega Menu - Link Card
 *
 * Renders a single link card in the mega menu grid.
 * Expects $mega_item (WP_Post nav menu item) via set_query_var().
 *
 * @package chusie-kokoro
 */

$mega_item = get_query_var( 'mega_item' );
if ( ! $mega_item ) return;

$title       = esc_html( $mega_item->title );
$description = $mega_item->description ? wp_kses_post( $mega_item->description ) : '';
$url         = esc_url( $mega_item->url );
?>

<a href="<?= $url; ?>" class="ck-mega-panel__card">
	<?php if ( $title ) : ?>
		<h3 class="ck-mega-panel__card-title"><?= $title; ?></h3>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="ck-mega-panel__card-desc"><?= $description; ?></p>
	<?php endif; ?>
</a>
