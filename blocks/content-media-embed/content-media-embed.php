<?php

/**
 * Content with Embed template.
 *
 * The embed (YouTube, Vimeo, Google Maps, any iframe) renders as a
 * click-to-load facade: visitors see a poster first, the iframe loads on click.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$sub_header = get_field('sub_header');
$body = get_field('body');
$btn = get_field('button');

$embed = get_field('embed');
$embed_pos = get_field('embed_position') === 'left' ? 'left' : 'right';

$heading_id = ck_heading_id($block);

ck_section_open($block, 'content-media-embed', array(
    'class' => 'ck-content-media-embed--media-' . $embed_pos,
    'labelledby' => $header ? $heading_id : '',
));
?>

    <div class="ck-content-media-embed__content">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-content-media-embed__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($sub_header) : ?>
            <p class="ck-content-media-embed__sub-header ck-lead"><?php echo esc_html($sub_header); ?></p>
        <?php endif; ?>

        <?php if ($body) : ?>
            <div class="ck-content-media-embed__body"><?php echo wp_kses_post($body); ?></div>
        <?php endif; ?>

        <?php ck_buttons($btn, null, 'ck-content-media-embed__actions'); ?>
    </div>

    <?php if ($embed) : ?>
        <div class="ck-content-media-embed__media">
            <?php echo ck_embed_facade($embed, array('title' => $header)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside ck_embed_facade(). ?>
        </div>
    <?php endif; ?>

<?php ck_section_close(); ?>
