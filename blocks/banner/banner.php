<?php

/**
 * Banner template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$btn = get_field('button');

ck_section_open($block, 'banner', array(
    'animation' => 'zoom-in',
));
?>

    <div class="ck-banner__inner ck-surface">
        <?php if ($header) : ?>
            <p class="ck-banner__header"><?php echo esc_html($header); ?></p>
        <?php endif; ?>

        <?php ck_buttons($btn, null, 'ck-banner__actions'); ?>
    </div>

<?php ck_section_close(); ?>
