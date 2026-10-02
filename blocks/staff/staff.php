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

$heading_id = ck_heading_id($block);

ob_start();
?>
<div class="ck-staff__decorations" aria-hidden="true">
    <?php (new LeftUDecoration)->output(); ?>
    <?php (new RightCircleDecoration)->output(); ?>
</div>
<?php
$decorations = ob_get_clean();

ck_section_open($block, 'staff', array(
    'labelledby' => $header ? $heading_id : '',
    'after' => $decorations,
));
?>

    <?php if ($header || $content || $btn) : ?>
        <div class="ck-staff__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="ck-staff__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>

            <?php if ($content) : ?>
                <div class="ck-staff__content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>

            <?php if (!empty($btn['url'])) :
                (new Button($btn, 'ck-staff__btn', 'simple'))->output();
            endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($staff_members) : ?>
        <ul class="ck-staff__list" data-stagger>
            <?php foreach ($staff_members as $member) :
                $image_id = !empty($member['image']['ID']) ? $member['image']['ID'] : $placeholder_id;
            ?>
                <li class="ck-staff__member ck-surface ck-surface--interactive">
                    <div class="ck-staff__member-media">
                        <?php echo wp_get_attachment_image($image_id, $media_size, false, array(
                            'class' => 'ck-staff__img',
                            'alt' => $member['name'] ?? '',
                        )); ?>
                    </div>
                    <div class="ck-staff__member-details">
                        <?php if (!empty($member['name'])) : ?>
                            <h3 class="ck-staff__member-name"><?php echo esc_html($member['name']); ?></h3>
                        <?php endif; ?>
                        <?php if (!empty($member['credentials'])) : ?>
                            <p class="ck-staff__member-credentials"><?php echo esc_html($member['credentials']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($member['description'])) : ?>
                            <p class="ck-staff__member-description"><?php echo esc_html($member['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php ck_section_close(); ?>
