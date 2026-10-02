<?php

/**
 * Template part for displaying blog post cards on blog index
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chusie-kokoro
 */

$categories = get_the_category();
$primary_cat = ! empty($categories) ? $categories[0] : null;
$author_id = get_the_author_meta('ID');
$ck_avatar = get_field('lp_avatar', 'user_' . $author_id);
?>

<article class="ck-blog__card">

    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="ck-blog__card-img-link">
            <?php the_post_thumbnail('medium_large', ['class' => 'ck-blog__card-img']); ?>
        </a>
    <?php endif; ?>

    <div class="ck-blog__card-body">

        <div class="ck-blog__taxonomy">
            <?php if ($primary_cat) : ?>
                <a href="<?php echo esc_url(get_category_link($primary_cat->term_id)); ?>" class="ck-blog__card-cat">
                    <?php echo esc_html($primary_cat->name); ?>
                </a>
            <?php endif; ?>
        </div>

        <h3 class="ck-blog__card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <!-- <div class="ck-blog__card-excerpt">
            <?php //echo wp_trim_words(get_the_excerpt(), 20); ?>
        </div> -->

    </div>
    <div class="ck-blog__card-footer">

        <div class="ck-blog__card-meta">
            <span class="ck-blog__author">
                <span class="ck-blog__author-avatar-container">
                    <?php if ($ck_avatar) : ?>
                        <?php echo wp_get_attachment_image($ck_avatar['ID'], 'thumbnail', false, ['class' => 'ck-blog__author-avatar']); ?>
                    <?php endif; ?>
                </span>
                <span class="ck-blog__author-name"><?php the_author(); ?></span>
            </span>

            <span class="ck-blog__publish-date">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            </span>
        </div>

    </div>

    <a href="<?php the_permalink(); ?>">
        <div class="ck-blog__card-footer-cta">

            <span class="ck-blog__card-cta-text">Read More</span>
            <span class="ck-blog__card_cta-icon">→</span>

        </div>
    </a>
</article>