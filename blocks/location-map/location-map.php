<?php

/**
 * Location Map template (WP Store Locator, all locations + filters).
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$sub_header = get_field('sub_header');
$content = get_field('content');
$btn = get_field('button');

$heading_id = ck_heading_id($block);

ck_section_open($block, 'location-map', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $sub_header || $content || $btn) : ?>
        <div class="ck-location-map__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-location-map__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($sub_header) : ?>
                <p class="ck-location-map__sub-header ck-lead"><?php echo esc_html($sub_header); ?></p>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="ck-location-map__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>

            <?php ck_buttons($btn, null, 'ck-btn-group--center'); ?>
        </div>
    <?php endif; ?>

    <div class="ck-location-map__locator">
        <?php
        $_SESSION['clarvida_wpsl_show_filters'] = true;
        echo do_shortcode('[wpsl]');
        ?>
    </div>

<?php ck_section_close(); ?>
