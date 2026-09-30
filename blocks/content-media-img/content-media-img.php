<?php

/**
 * Content with Image template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$body = get_field('body');
$btn = get_field('button');

$img = get_field('image');
$media_size = 'large';
$img_pos = get_field('image_position') === 'left' ? 'left' : 'right';

$tele_track = get_field('tele_track');

$heading_id = lp_heading_id($block);

lp_section_open($block, 'content-media-img', array(
    'class' => 'lp-content-media-img--media-' . $img_pos,
    'labelledby' => $header ? $heading_id : '',
    'attrs' => array('data-telemetry-track' => $tele_track),
));
?>

    <div class="lp-content-media-img__content">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-content-media-img__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($body) : ?>
            <div class="lp-content-media-img__body"><?php echo wp_kses_post($body); ?></div>
        <?php endif; ?>

        <?php lp_buttons($btn, null, 'lp-content-media-img__actions'); ?>
    </div>

    <?php if ($img) : ?>
        <figure class="lp-content-media-img__media">
            <?php echo wp_get_attachment_image($img['ID'], $media_size, false, array(
                'class' => 'lp-content-media-img__img',
                'sizes' => '(max-width: 767px) 100vw, 50vw',
            )); ?>
        </figure>
    <?php endif; ?>

<?php lp_section_close(); ?>
