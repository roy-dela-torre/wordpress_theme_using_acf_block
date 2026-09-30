<?php

/**
 * Content With Decorations template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');
$btn = get_field('button');
$disclaimer = get_field('disclaimer');

$heading_id = lp_heading_id($block);

lp_section_open($block, 'content-w-decorations', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header) : ?>
        <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-content-w-decorations__header"><?php echo esc_html($header); ?></h2>
    <?php endif; ?>

    <?php if ($content) : ?>
        <div class="lp-content-w-decorations__content"><?php echo wp_kses_post($content); ?></div>
    <?php endif; ?>

    <?php lp_buttons($btn, null, 'lp-content-w-decorations__actions'); ?>

    <?php if ($disclaimer) : ?>
        <p class="lp-content-w-decorations__disclaimer disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
    <?php endif; ?>

<?php lp_section_close(); ?>
