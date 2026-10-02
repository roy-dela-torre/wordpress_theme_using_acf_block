<?php

/**
 * Embed (Full Bleed) template.
 *
 * Renders the embed as a click-to-load facade so the third-party iframe
 * (map, video, …) only loads when a visitor asks for it.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$embed = get_field('embed');

// 'muted' default keeps this full-bleed block out of the plain-section
// spacing collapse, so neighbouring text never touches the map/video.
ck_section_open($block, 'embed', array(
    'default_bg' => 'muted',
    'animation' => 'fade',
));
?>

    <?php if ($embed) : ?>
        <?php echo ck_embed_facade($embed, array('class' => 'ck-embed__facade')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside ck_embed_facade(). ?>
    <?php elseif (is_admin()) : ?>
        <p class="ck-embed__empty"><?php esc_html_e('Please add an embed code.', 'chusie-kokoro'); ?></p>
    <?php endif; ?>

<?php ck_section_close(); ?>
