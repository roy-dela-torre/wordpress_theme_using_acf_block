<?php

/**
 * Content Bubble with Image template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$body = get_field('body');

$img = get_field('image');
$media_size = 'large';
$icon_size = 'medium';

$tele_track = get_field('tele_track');

$heading_id = lp_heading_id($block);

lp_section_open($block, 'content-media-bubble', array(
    'labelledby' => $header ? $heading_id : '',
    'attrs' => array('data-telemetry-track' => $tele_track),
));
?>

    <div class="lp-content-media-bubble__content">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-content-media-bubble__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($body) : ?>
            <div class="lp-content-media-bubble__body"><?php echo wp_kses_post($body); ?></div>
        <?php endif; ?>

        <?php if (have_rows('content')) :
            while (have_rows('content')) : the_row();

                if (get_row_layout() === 'stats_section' && have_rows('stats')) : ?>
                    <dl class="lp-content-media-bubble__stats" data-stagger>
                        <?php while (have_rows('stats')) : the_row(); ?>
                            <div class="lp-content-media-bubble__stat">
                                <dt class="lp-content-media-bubble__stat-value"><?php echo esc_html(get_sub_field('stat')); ?></dt>
                                <dd class="lp-content-media-bubble__stat-label"><?php echo esc_html(get_sub_field('description')); ?></dd>
                            </div>
                        <?php endwhile; ?>
                    </dl>

                <?php elseif (get_row_layout() === 'badge_section') :
                    $badge_img = get_sub_field('badge_image');
                    $badge_description = get_sub_field('description'); ?>
                    <div class="lp-content-media-bubble__badge">
                        <?php if ($badge_img) :
                            echo wp_get_attachment_image($badge_img['ID'], $icon_size, false, array('class' => 'lp-content-media-bubble__badge-img'));
                        endif; ?>
                        <?php if ($badge_description) : ?>
                            <p class="lp-content-media-bubble__badge-text"><?php echo esc_html($badge_description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif;

            endwhile;
        endif; ?>
    </div>

    <?php if ($img) : ?>
        <figure class="lp-content-media-bubble__media">
            <?php echo wp_get_attachment_image($img['ID'], $media_size, false, array(
                'class' => 'lp-content-media-bubble__img',
                'sizes' => '(max-width: 767px) 80vw, 500px',
            )); ?>
        </figure>
    <?php endif; ?>

<?php lp_section_close(); ?>
