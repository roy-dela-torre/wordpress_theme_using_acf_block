<?php

/**
 * Testimonials Slider template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$testimonials = get_field('testimonials') ?: array();

$heading_id = lp_heading_id($block);

lp_section_open($block, 'testimonials', array(
    'default_bg' => 'light',
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header) : ?>
        <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-testimonials__header"><?php echo esc_html($header); ?></h2>
    <?php endif; ?>

    <?php if ($testimonials) : ?>
        <div class="lp-testimonials__slider">
            <div class="lp-testimonials__slider-container swiper testimonial-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <figure class="swiper-slide lp-testimonials__slide lp-surface">
                            <svg class="lp-testimonials__quote-icon" width="40" height="32" viewBox="0 0 40 32" aria-hidden="true" focusable="false"><path d="M0 32V19.2C0 8.4 6.1 1.9 16.4 0l1.8 4.6C12.4 6.3 9.6 10 9.3 15.2H17V32H0zm22.8 0V19.2C22.8 8.4 28.9 1.9 39.2 0L41 4.6c-5.8 1.7-8.6 5.4-8.9 10.6h7.7V32H22.8z"/></svg>
                            <?php if (!empty($testimonial['content'])) : ?>
                                <blockquote class="lp-testimonials__slide-content">
                                    <?php echo wp_kses_post(wpautop($testimonial['content'])); ?>
                                </blockquote>
                            <?php endif; ?>
                            <?php if (!empty($testimonial['name'])) : ?>
                                <figcaption class="lp-testimonials__slide-name"><?php echo esc_html($testimonial['name']); ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>

<?php if (is_admin()) : ?>
<script type="text/javascript">
    // Make sure Swiper initializes inside the block editor preview.
    document.dispatchEvent(new Event('testimonialSliderBlockLoadedEvent'));
</script>
<?php endif; ?>
