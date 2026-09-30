<?php

/**
 * Homepage Hero template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$sub_header = get_field('sub_header');
$btn = get_field('button');

$hero_image = get_field('hero_image');
$media_size = 'full';

$stats = get_field('stats');

$heading_id = lp_heading_id($block);

$decorations = '<img class="lp-hero-home__background-icon" src="' . esc_url(get_parent_theme_file_uri('assets/img/clarvida-icon.png')) . '" alt="" aria-hidden="true" loading="lazy" decoding="async" />';

lp_section_open($block, 'hero-home', array(
    'default_bg' => 'brand',
    'labelledby' => $header ? $heading_id : '',
    'after' => $decorations,
));
?>

    <div class="lp-hero-home__content">
        <?php if ($header) : ?>
            <h1 id="<?php echo esc_attr($heading_id); ?>" class="lp-hero-home__header"><?php echo esc_html($header); ?></h1>
        <?php endif; ?>

        <?php if ($sub_header) : ?>
            <p class="lp-hero-home__sub-header lp-lead"><?php echo esc_html($sub_header); ?></p>
        <?php endif; ?>

        <?php lp_buttons($btn, null, 'lp-hero-home__actions'); ?>

        <?php if ($stats) : ?>
            <ul class="lp-hero-home__stats" data-stagger>
                <?php foreach ($stats as $stat) : ?>
                    <li class="lp-hero-home__stat">
                        <?php if (!empty($stat['header'])) : ?>
                            <span class="lp-hero-home__stat-value"><?php echo esc_html($stat['header']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($stat['body'])) : ?>
                            <span class="lp-hero-home__stat-label"><?php echo esc_html($stat['body']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if ($hero_image) : ?>
        <div class="lp-hero-home__media">
            <?php echo wp_get_attachment_image($hero_image['ID'], $media_size, false, array(
                'class' => 'lp-hero-home__img',
                'loading' => 'eager',
                'fetchpriority' => 'high',
                'decoding' => 'async',
            )); ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
