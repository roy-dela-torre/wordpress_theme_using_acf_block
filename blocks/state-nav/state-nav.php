<?php

/**
 * State Nav template.
 *
 * Expandable cards: the title links to the state page, the chevron reveals
 * a short summary + button.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('body');

$cards = get_field('cards');

$heading_id = lp_heading_id($block);
$panel_prefix = lp_heading_id($block, 'panel');

lp_section_open($block, 'state-nav', array(
    'default_bg' => 'light',
    'labelledby' => $header ? $heading_id : '',
));
?>

    <?php if ($header || $content) : ?>
        <div class="lp-state-nav__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-state-nav__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-state-nav__body"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($cards) && is_array($cards)) : ?>
        <div class="lp-state-nav__grid" data-stagger>
            <?php foreach ($cards as $index => $card) :
                $card_title = $card['card_title'] ?? '';
                $card_body = $card['card_body'] ?? '';
                $card_link = !empty($card['page']['url']) ? $card['page'] : null;

                if ($card_link) {
                    // ACF auto-fills the link text with the target page's title; treat that as unset.
                    $linked_post_id = url_to_postid($card_link['url']);
                    $is_autofill = $linked_post_id && $card_link['title'] === get_the_title($linked_post_id);
                    if ($is_autofill || empty($card_link['title'])) {
                        $card_link['title'] = __('Learn more', 'launchpad');
                    }
                }

                $panel_id = $panel_prefix . '-' . $index;
                $has_panel = $card_body || $card_link;
            ?>
                <div class="lp-state-nav__card lp-surface">
                    <div class="lp-state-nav__card-head">
                        <h3 class="lp-state-nav__card-title">
                            <?php if ($card_link) : ?>
                                <a class="lp-state-nav__card-link" href="<?php echo esc_url($card_link['url']); ?>" target="<?php echo esc_attr($card_link['target'] ?: '_self'); ?>">
                                    <span><?php echo esc_html($card_title); ?></span>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><path d="M1 8H14M9 3L14 8L9 13" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html($card_title); ?>
                            <?php endif; ?>
                        </h3>

                        <?php if ($has_panel) : ?>
                            <button class="lp-state-nav__card-trigger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>">
                                <span class="screen-reader-text"><?php echo esc_html(sprintf(__('More about %s', 'launchpad'), $card_title)); ?></span>
                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true" focusable="false"><path d="M1 1.5L6 6.5L11 1.5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if ($has_panel) : ?>
                        <div id="<?php echo esc_attr($panel_id); ?>" class="lp-state-nav__card-panel">
                            <div class="lp-state-nav__card-panel-inner">
                                <?php if ($card_body) : ?>
                                    <div class="lp-state-nav__card-body"><?php echo wp_kses_post($card_body); ?></div>
                                <?php endif; ?>

                                <?php if ($card_link) :
                                    (new Button($card_link, 'lp-state-nav__card-btn', 'secondary'))->output();
                                endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php lp_section_close(); ?>
