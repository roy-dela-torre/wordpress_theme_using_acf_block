<?php

/**
 * Maps Cards template: location cards with address, phone and directions.
 *
 * @param array $block The block settings and attributes.
 */

// Load values.
$header = get_field('header');
$sub_header = get_field('sub_header');
$content = get_field('content');

$cards = get_field('cards') ?: array();
$media_size = 'medium_large';

$heading_id = lp_heading_id($block);

lp_section_open($block, 'maps-cards', array(
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $sub_header || $content) : ?>
        <div class="lp-maps-cards__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-maps-cards__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($sub_header) : ?>
                <p class="lp-maps-cards__sub-header lp-lead"><?php echo esc_html($sub_header); ?></p>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-maps-cards__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($cards) : ?>
        <ul class="lp-maps-cards__grid" data-stagger>
            <?php foreach ($cards as $card) :
                $title = $card['title'] ?? '';
                $image = $card['image'] ?? null;
                $address = $card['address'] ?? '';
                $phone = $card['phone'] ?? '';
                $map_link = !empty($card['map_link']['url']) ? $card['map_link'] : null;
                $description = $card['description'] ?? '';
            ?>
                <li class="lp-maps-cards__card lp-surface lp-surface--interactive">
                    <?php if ($image) : ?>
                        <div class="lp-maps-cards__card-media">
                            <?php echo wp_get_attachment_image($image['ID'], $media_size, false, array(
                                'class' => 'lp-maps-cards__img',
                                'sizes' => '(max-width: 767px) 100vw, 560px',
                            )); ?>
                        </div>
                    <?php endif; ?>

                    <div class="lp-maps-cards__card-details">
                        <?php if ($title) : ?>
                            <h3 class="lp-maps-cards__card-title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>

                        <?php if ($address) : ?>
                            <address class="lp-maps-cards__card-address"><?php echo nl2br(esc_html($address)); ?></address>
                        <?php endif; ?>

                        <?php if ($phone) : ?>
                            <p class="lp-maps-cards__card-phone">
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                            </p>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <p class="lp-maps-cards__card-description"><?php echo esc_html($description); ?></p>
                        <?php endif; ?>

                        <?php if ($map_link) :
                            $map_link['title'] = $map_link['title'] ?: __('Get Directions', 'launchpad');
                            $map_link['target'] = $map_link['target'] ?: '_blank';
                            (new Button($map_link, 'lp-maps-cards__card-btn', 'secondary'))->output();
                        endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php lp_section_close(); ?>
