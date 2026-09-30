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

$heading_id = lp_heading_id($block);

lp_section_open($block, 'content-media-embed', array(
    'class' => 'lp-content-media-embed--media-' . $embed_pos,
    'labelledby' => $header ? $heading_id : '',
));
?>

    <div class="lp-content-media-embed__content">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-content-media-embed__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($sub_header) : ?>
            <p class="lp-content-media-embed__sub-header lp-lead"><?php echo esc_html($sub_header); ?></p>
        <?php endif; ?>

        <?php if ($body) : ?>
            <div class="lp-content-media-embed__body"><?php echo wp_kses_post($body); ?></div>
        <?php endif; ?>

        <?php lp_buttons($btn, null, 'lp-content-media-embed__actions'); ?>
    </div>

    <?php if ($embed) : ?>
        <div class="lp-content-media-embed__media">
            <?php echo lp_embed_facade($embed, array('title' => $header)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside lp_embed_facade(). ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
