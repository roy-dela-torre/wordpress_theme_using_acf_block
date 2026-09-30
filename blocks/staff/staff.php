<?php

/**
 * Staff template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$header = get_field('header');
$content = get_field('content');
$btn = get_field('button');

$staff_members = get_field('staff_members') ?: array();
$media_size = 'medium';
$placeholder_id = 5291; // Media library placeholder used when a member has no photo.

$heading_id = lp_heading_id($block);

ob_start();
?>
<div class="lp-staff__decorations" aria-hidden="true">
    <?php (new LeftUDecoration)->output(); ?>
    <?php (new RightCircleDecoration)->output(); ?>
</div>
<?php
$decorations = ob_get_clean();

lp_section_open($block, 'staff', array(
    'labelledby' => $header ? $heading_id : '',
    'after' => $decorations,
));
?>

    <?php if ($header || $content || $btn) : ?>
        <div class="lp-staff__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-staff__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="lp-staff__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>

            <?php if (!empty($btn['url'])) :
                (new Button($btn, 'lp-staff__btn', 'simple'))->output();
            endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($staff_members) : ?>
        <ul class="lp-staff__list" data-stagger>
            <?php foreach ($staff_members as $member) :
                $image_id = !empty($member['image']['ID']) ? $member['image']['ID'] : $placeholder_id;
            ?>
                <li class="lp-staff__member lp-surface lp-surface--interactive">
                    <div class="lp-staff__member-media">
                        <?php echo wp_get_attachment_image($image_id, $media_size, false, array(
                            'class' => 'lp-staff__img',
                            'alt' => $member['name'] ?? '',
                        )); ?>
                    </div>
                    <div class="lp-staff__member-details">
                        <?php if (!empty($member['name'])) : ?>
                            <h3 class="lp-staff__member-name"><?php echo esc_html($member['name']); ?></h3>
                        <?php endif; ?>
                        <?php if (!empty($member['credentials'])) : ?>
                            <p class="lp-staff__member-credentials"><?php echo esc_html($member['credentials']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($member['description'])) : ?>
                            <p class="lp-staff__member-description"><?php echo esc_html($member['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php lp_section_close(); ?>
