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

$heading_id = ck_heading_id($block);

ck_section_open($block, 'callout', array(
    'class' => $has_panel ? 'ck-callout--panel' : '',
    'labelledby' => $header ? $heading_id : '',
    'animation' => 'zoom-in',
));
?>

    <div class="ck-callout__inner">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-callout__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
            <div class="ck-callout__content"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <?php ck_buttons($btn, $btn_2, 'ck-btn-group--center ck-callout__actions'); ?>

        <?php if ($disclaimer) : ?>
            <p class="ck-callout__disclaimer disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
        <?php endif; ?>
    </div>

<?php ck_section_close(); ?>
