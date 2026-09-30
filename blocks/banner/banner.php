<?php

/**
 * Banner template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$btn = get_field('button');

lp_section_open($block, 'banner', array(
    'animation' => 'zoom-in',
));
?>

    <div class="lp-banner__inner lp-surface">
        <?php if ($header) : ?>
            <p class="lp-banner__header"><?php echo esc_html($header); ?></p>
        <?php endif; ?>

        <?php lp_buttons($btn, null, 'lp-banner__actions'); ?>
    </div>

<?php lp_section_close(); ?>
