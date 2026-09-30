<?php

/**
 * Icon Columns template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');

$columns = get_field('columns') ?: array();
$icon_size = 'medium';

$text_align = get_field('column_text_alignment') === 'center' ? 'center' : 'left';

$column_num_override = (int) get_field('column_number_override');
$column_num = $column_num_override ?: min(max(count($columns), 1), 4);

$heading_id = lp_heading_id($block);

lp_section_open($block, 'columns', array(
    'class' => 'lp-columns--' . $column_num . '-col lp-columns--text-' . $text_align,
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $content) : ?>
        <div class="lp-columns__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-columns__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-columns__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($columns) : ?>
        <div class="lp-columns__cols" data-stagger>
            <?php foreach ($columns as $column) :
                $icon = $column['icon'] ?? null;
                $title = $column['title'] ?? '';
                $description = $column['description'] ?? '';
                $is_circular = !empty($column['force_circular_image']);
            ?>
                <article class="lp-columns__col">
                    <?php if ($icon) : ?>
                        <div class="lp-columns__icon-wrapper<?php echo $is_circular ? ' lp-columns__icon-wrapper--circle' : ''; ?>">
                            <?php echo wp_get_attachment_image($icon['ID'], $icon_size, false, array('class' => 'lp-columns__icon')); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h3 class="lp-columns__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <div class="lp-columns__description"><?php echo wp_kses_post($description); ?></div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
