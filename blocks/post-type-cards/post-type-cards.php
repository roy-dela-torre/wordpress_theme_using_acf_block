<?php

/**
 * Post Type Cards template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$sub_header = get_field('sub_header');
$content = get_field('body');
$btn = get_field('button');

$decorations = get_field('decorations');

$post_type = get_field('post_type') ?: 'post';

$post_limit = -1;
if (get_field('limit_number_of_posts')) {
    $post_limit = (int) get_field('post_limit') ?: -1;
}

// Optional taxonomy filter per post type.
$taxonomy_filters = array(
    'post' => array('field' => 'category', 'taxonomy' => 'category'),
    'service' => array('field' => 'service_state', 'taxonomy' => 'service-state'),
    'program' => array('field' => 'program_state', 'taxonomy' => 'program-state'),
);

$query_args = array(
    'posts_per_page' => $post_limit,
    'post_type' => $post_type,
    'no_found_rows' => true,
);

if (isset($taxonomy_filters[$post_type])) {
    $filter = $taxonomy_filters[$post_type];
    $term = get_term(get_field($filter['field']));
    if ($term && !is_wp_error($term)) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => $filter['taxonomy'],
                'field' => 'slug',
                'terms' => $term->slug,
            ),
        );
    }
}

$posts = get_posts($query_args);

$heading_id = lp_heading_id($block);

lp_section_open($block, 'post-type-cards', array(
    'default_bg' => 'light',
    'labelledby' => $header ? $heading_id : '',
    'after' => $decorations ? '<div class="lp-post-type-cards__decoration circle-decoration" aria-hidden="true"></div>' : '',
));
?>

    <?php if ($header || $sub_header || $content) : ?>
        <div class="lp-post-type-cards__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-post-type-cards__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($sub_header) : ?>
                <p class="lp-post-type-cards__sub-header lp-lead"><?php echo esc_html($sub_header); ?></p>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-post-type-cards__body"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($posts) :
        global $post; ?>
        <div class="lp-post-type-cards__grid" data-stagger>
            <?php foreach ($posts as $post) :
                setup_postdata($post);
                get_template_part('template-parts/cards/card', get_post_type());
            endforeach; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php if ($btn) : ?>
        <div class="lp-post-type-cards__footer">
            <?php lp_buttons($btn, null, 'lp-btn-group--center'); ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
