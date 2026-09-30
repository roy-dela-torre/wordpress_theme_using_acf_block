<?php

/**
 * Logo Cards template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('body');

$disclaimer = get_field('disclaimer');
$btn = get_field('button');

$cards = get_field('cards');
$icon_size = 'medium';

$column_num = (int) get_field('column_number_override') ?: 3;

$heading_id = lp_heading_id($block);

lp_section_open($block, 'logo-cards', array(
    'default_bg' => 'light',
    'class' => 'lp-logo-cards--' . $column_num . '-col',
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $content) : ?>
        <div class="lp-logo-cards__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-logo-cards__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-logo-cards__body"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($cards) && is_array($cards)) : ?>
        <ul class="lp-logo-cards__grid" data-stagger>
            <?php foreach ($cards as $card) :
                $card_title = $card['card_title'] ?? '';
                $card_image = $card['card_image'] ?? null;
            ?>
                <li class="lp-logo-cards__card lp-surface lp-surface--interactive">
                    <?php if ($card_image) :
                        echo wp_get_attachment_image($card_image['ID'], $icon_size, false, array(
                            'class' => 'lp-logo-cards__card-img',
                            'alt' => $card_image['alt'] ?: $card_title,
                        ));
                    elseif ($card_title) : ?>
                        <p class="lp-logo-cards__card-title"><?php echo esc_html($card_title); ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($btn || $disclaimer) : ?>
        <div class="lp-logo-cards__footer">
            <?php if ($disclaimer) : ?>
                <p class="lp-logo-cards__disclaimer disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
            <?php endif; ?>
            <?php lp_buttons($btn); ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
