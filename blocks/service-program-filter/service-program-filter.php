<?php

/**
 * Service / Program Filter template.
 *
 * Visitors choose a state and matching cards load via the
 * custom-clarvida/service-program-cards REST route (see functions.php).
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('body');

$post_type = get_field('post_type') === 'service' ? 'service' : 'program';
$taxonomy = $post_type . '-state';

$states = get_terms(array(
    'taxonomy' => $taxonomy,
    'hide_empty' => true,
));

$heading_id = ck_heading_id($block);

ck_section_open($block, 'service-program-filter', array(
    'default_bg' => 'light',
    'labelledby' => $header ? $heading_id : '',
));
?>

    <div class="ck-service-program-filter__intro">
        <?php if ($header) : ?>
            <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-service-program-filter__header"><?php echo esc_html($header); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
            <div class="ck-service-program-filter__body"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <form class="ck-service-program-filter__filters" onsubmit="return false;">
            <label class="ck-service-program-filter__label" for="service-program-states">
                <?php
                /* translators: %s: "programs" or "services" */
                echo esc_html(sprintf(__('Select your state to view the %ss offered in your area.', 'chusie-kokoro'), $post_type));
                ?>
            </label>
            <div class="ck-service-program-filter__select-wrap">
                <select name="service-program-states" id="service-program-states" class="ck-service-program-filter__select">
                    <option value=""><?php esc_html_e('Select Your State', 'chusie-kokoro'); ?></option>
                    <?php if (!is_wp_error($states)) :
                        foreach ($states as $state) : ?>
                            <option value="<?php echo esc_attr($state->slug); ?>"><?php echo esc_html($state->name); ?></option>
                        <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <input type="hidden" value="<?php echo esc_attr($post_type); ?>" id="post-type">
        </form>
    </div>

    <div class="ck-service-program-filter__grid" id="ck-service-program-filter__grid" aria-live="polite"></div>

<?php ck_section_close(); ?>

<?php if (is_admin()) : ?>
<script type="text/javascript">
    document.dispatchEvent(new Event('serviceProgramFilterBlockLoaded'));
</script>
<?php endif; ?>
