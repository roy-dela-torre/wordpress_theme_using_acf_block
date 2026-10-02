<?php

/**
 * Accordions template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');
$btn = get_field('button');

$accordions = get_field('accordions') ?: array();

$heading_id = ck_heading_id($block);
$panel_prefix = ck_heading_id($block, 'panel');

ck_section_open($block, 'accordion', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <div class="ck-accordion__intro">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-accordion__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
            <div class="ck-accordion__content"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <?php ck_buttons($btn, null, 'ck-accordion__actions'); ?>
    </div>

    <?php if ($accordions) : ?>
        <div class="ck-accordion__list" data-stagger>
            <?php foreach ($accordions as $index => $item) :
                $is_open = $index === 0; // First item open by default.
                $panel_id = $panel_prefix . '-' . $index;
                $trigger_id = $panel_id . '-trigger';
            ?>
                <div class="ck-accordion__item ck-surface<?php echo $is_open ? ' is-open' : ''; ?>">
                    <h3 class="ck-accordion__item-heading">
                        <button type="button"
                            id="<?php echo esc_attr($trigger_id); ?>"
                            class="ck-accordion__trigger"
                            aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>">
                            <span class="ck-accordion__item-title"><?php echo esc_html($item['title'] ?? ''); ?></span>
                            <span class="ck-accordion__icon" aria-hidden="true"></span>
                        </button>
                    </h3>

                    <div id="<?php echo esc_attr($panel_id); ?>"
                        class="ck-accordion__panel"
                        role="region"
                        aria-labelledby="<?php echo esc_attr($trigger_id); ?>">
                        <div class="ck-accordion__panel-inner">
                            <div class="ck-accordion__panel-content"><?php echo wp_kses_post($item['content'] ?? ''); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php ck_section_close(); ?>
