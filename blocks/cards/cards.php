<?php

/**
 * Cards template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('body');
$btn = get_field('button');

$cards = get_field('cards');
$media_size = 'medium_large';

$decorations = get_field('decorations');

$column_num = (int) get_field('column_number_override') ?: 3;

$heading_id = lp_heading_id($block);

$inline_editing = function_exists('acf_inline_toolbar_editing_attrs');

lp_section_open($block, 'cards', array(
    'default_bg' => 'light',
    'class' => 'lp-cards--' . $column_num . '-col',
    'labelledby' => $header ? $heading_id : '',
    'after' => $decorations ? '<div class="lp-cards__decoration circle-decoration" aria-hidden="true"></div>' : '',
));
?>

    <?php if ($header || $content || is_admin()) : ?>
        <div class="lp-cards__intro">
            <?php if ($header || is_admin()) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-cards__header" <?php echo $inline_editing ? acf_inline_text_editing_attrs('header', array('placeholder' => 'Header goes here...')) : ''; ?>><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-cards__body"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($cards) && is_array($cards)) : ?>
        <div class="lp-cards__grid" data-stagger <?php echo $inline_editing ? acf_inline_toolbar_editing_attrs(array('cards')) : ''; ?>>
            <?php foreach ($cards as $card) :
                $card_title = $card['card_title'] ?? '';
                $card_image = $card['card_image'] ?? null;
                $card_body = $card['card_body'] ?? '';
                $card_button = !empty($card['card_button']['url']) ? $card['card_button'] : null;
                $is_dark = !empty($card['darken_background']);
            ?>
                <article class="lp-cards__card lp-surface<?php echo $card_button ? ' lp-surface--interactive' : ''; ?><?php echo $is_dark ? ' lp-cards__card--dark' : ''; ?>">
                    <?php if ($card_image) : ?>
                        <div class="lp-cards__card-media">
                            <?php echo wp_get_attachment_image($card_image['ID'], $media_size, false, array(
                                'class' => 'lp-cards__card-img',
                                'sizes' => '(max-width: 767px) 100vw, 400px',
                            )); ?>
                        </div>
                    <?php endif; ?>

                    <div class="lp-cards__card-contents">
                        <?php if ($card_title) : ?>
                            <h3 class="lp-cards__card-title">
                                <?php if ($card_button) : ?>
                                    <a class="lp-cards__card-link" href="<?php echo esc_url($card_button['url']); ?>" target="<?php echo esc_attr($card_button['target'] ?: '_self'); ?>"<?php echo ($card_button['target'] ?? '') === '_blank' ? ' rel="noopener"' : ''; ?>><?php echo esc_html($card_title); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($card_title); ?>
                                <?php endif; ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($card_body) : ?>
                            <div class="lp-cards__card-body"><?php echo wp_kses_post($card_body); ?></div>
                        <?php endif; ?>

                        <?php if ($card_button) : ?>
                            <span class="btn btn-simple lp-cards__card-btn" aria-hidden="true"><?php echo esc_html($card_button['title']); ?> <svg width="3" height="6" viewBox="0 0 3 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.394287 0.307495L2.3756 2.84767L0.394287 5.38784"></path></svg></span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($btn) : ?>
        <div class="lp-cards__footer" <?php echo $inline_editing ? acf_inline_toolbar_editing_attrs(array('button')) : ''; ?>>
            <?php lp_buttons($btn, null, 'lp-btn-group--center'); ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
