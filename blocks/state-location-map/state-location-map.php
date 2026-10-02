<?php

/**
 * State Location Map template (WP Store Locator filtered to one state).
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$sub_header = get_field('sub_header');
$content = get_field('content');
$btn = get_field('button');

$state = get_field('state');

$heading_id = ck_heading_id($block);

ck_section_open($block, 'state-location-map', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $sub_header || $content || $btn) : ?>
        <div class="ck-state-location-map__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-state-location-map__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($sub_header) : ?>
                <p class="ck-state-location-map__sub-header ck-lead"><?php echo esc_html($sub_header); ?></p>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="ck-state-location-map__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>

            <?php ck_buttons($btn, null, 'ck-btn-group--center'); ?>
        </div>
    <?php endif; ?>

    <div class="ck-state-location-map__locator">
        <?php
        if ($state) {
            // Saved for wpsl_store_data; globals don't survive the pre wpsl_* filters.
            $_SESSION['clarvida_wpsl_states'] = array($state);
        }

        $_SESSION['clarvida_wpsl_show_filters'] = true;

        $state_term = get_term($state);
        if ($state_term && !is_wp_error($state_term)) {
            echo do_shortcode('[wpsl category="' . esc_attr($state_term->slug) . '" start_location="' . esc_attr($state_term->name) . '"]');
        } else {
            echo do_shortcode('[wpsl]');
        }
        ?>
    </div>

<?php ck_section_close(); ?>
