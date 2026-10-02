<?php

/**
 * State Location Map (No Cards) template: content panel beside the map.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');
$btn = get_field('button');

$state = get_field('state');

$heading_id = ck_heading_id($block);

ck_section_open($block, 'state-location-map-no-cards', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <div class="ck-state-location-map-no-cards__content ck-surface">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-state-location-map-no-cards__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
            <div class="ck-state-location-map-no-cards__body"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <?php ck_buttons($btn, null, 'ck-state-location-map-no-cards__actions'); ?>
    </div>

    <div class="ck-state-location-map-no-cards__locator">
        <?php
        if ($state) {
            // Saved for wpsl_store_data; globals don't survive the pre wpsl_* filters.
            $_SESSION['clarvida_wpsl_states'] = array($state);
        } else {
            unset($_SESSION['clarvida_wpsl_states']);
        }

        $state_term = get_term($state);
        if ($state_term && !is_wp_error($state_term)) {
            echo do_shortcode('[wpsl category="' . esc_attr($state_term->slug) . '" start_location="' . esc_attr($state_term->name) . '"]');
        } else {
            echo do_shortcode('[wpsl]');
        }
        ?>
    </div>

<?php ck_section_close(); ?>
