<?php

/**
 * Callout template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');

$btn = get_field('button');
$btn_2 = get_field('button_2');

$disclaimer = get_field('disclaimer');

// "Background" toggle on this block shows the content inside a panel.
$has_panel = (bool) get_field('background');

$heading_id = lp_heading_id($block);

lp_section_open($block, 'callout', array(
    'class' => $has_panel ? 'lp-callout--panel' : '',
    'labelledby' => $header ? $heading_id : '',
    'animation' => 'zoom-in',
));
?>

    <div class="lp-callout__inner">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-callout__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
            <div class="lp-callout__content"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <?php lp_buttons($btn, $btn_2, 'lp-btn-group--center lp-callout__actions'); ?>

        <?php if ($disclaimer) : ?>
            <p class="lp-callout__disclaimer disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
        <?php endif; ?>
    </div>

<?php lp_section_close(); ?>
