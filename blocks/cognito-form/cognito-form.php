<?php

/**
 * Cognito Form template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');

$form_embed = get_field('form');
$disclaimer = get_field('disclaimer');

$heading_id = lp_heading_id($block);

lp_section_open($block, 'cognito-form', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $content) : ?>
        <div class="lp-cognito-form__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-cognito-form__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-cognito-form__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="lp-cognito-form__form lp-surface">
        <?php echo $form_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted form embed code from an editor. ?>

        <?php if ($disclaimer) : ?>
            <p class="lp-cognito-form__disclaimer disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
        <?php endif; ?>
    </div>

<?php lp_section_close(); ?>
