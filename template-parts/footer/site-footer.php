<?php
$logo_html = get_custom_logo();
$logo_html = is_string($logo_html) ? $logo_html : '';
$icon_size = 'thumbnail';

$footer_logo = get_field('footer_logo', 'option');

$contact_title = get_field('footer_title_contact', 'option');
$contact_title = is_string($contact_title) ? $contact_title : '';

$social_title = get_field('footer_title_social', 'option');
$social_title = is_string($social_title) ? $social_title : '';

$career_banner_title = get_field('career_banner_title', 'option');
$career_banner_brief = get_field('career_banner_brief', 'option');
$career_banner_cta = get_field('career_banner_cta', 'option');

$has_logo = ($logo_html !== '') || $footer_logo;
$has_contact = !empty($contact_title)
        || have_rows('footer_email', 'option')
        || have_rows('footer_phones', 'option')
        || have_rows('footer_addresses', 'option');
$has_social = !empty($social_title) || have_rows('footer_icons_social', 'option');

$custom_address = get_field('custom_address', 'option');
$partnership_image = get_field('partnership_image', 'option');
$partnership_image_link = get_field('partnership_image_link', 'option');

$compliance_title = get_field('compliance_title', 'option');
$compliance_text = get_field('compliance_text', 'option');
$compliance_links = get_field('compliance_links', 'option');



$menu_locations = get_nav_menu_locations();
$footer_menu_id = $menu_locations['footer-menu'] ?? 0;
$footer_menu = $footer_menu_id ? wp_get_nav_menu_object($footer_menu_id) : null;
$footer_menu_name = $footer_menu ? $footer_menu->name : '';
$menu_items = $footer_menu_id ? wp_get_nav_menu_items($footer_menu_id) : [];
$menu_items = is_array($menu_items) ? array_values(array_filter($menu_items, function ($item) {
    return !empty($item->title) && !empty($item->url);
})) : [];
?>

<div class="lp-footer__top">
    <div class="lp-footer__inner">

        <div class="lp-footer__career-banner">
            <!-- career-banner-title -->
             <?php if ($career_banner_title): ?>
				<h3> <?php echo esc_html($career_banner_title); ?>  </h3>
			<?php endif; ?>
            <!-- career-banner-brief -->
             <div> <?php echo $career_banner_brief ?> </div>
            <!-- carerr-banner-link -->
            <?php
            if ($career_banner_cta) {
                (new Button($career_banner_cta))->output();
            }
            ?>   
        </div>


        <div class="lp-footer__contact-disclaimer-container">
            <div class="lp-footer__contact-container">

                <div class="lp-footer-social-info-container">
                    <!-- logo -->
                    <?php if ($has_logo || $has_contact) : ?>
                        <div class="lp-footer__brand">
                            <?php if ($has_logo) : ?>
                                <div class="lp-footer__logo">
                                    <?php if ($footer_logo) : ?>
                                        <a href="/" class="lp-footer__logo-link">
                                            <?php echo wp_get_attachment_image($footer_logo['ID'], 'full'); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo wp_kses_post($logo_html); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                    <!-- custom address -->
                    <?php if ($custom_address) : ?>
                        <div class="lp-footer__custom-address">
                            <?php echo $custom_address; ?>
                        </div>
                    <?php endif; ?>



                    <!-- socials -->
                    <?php if ($has_social) : ?>
                        <div class="lp-footer__social-media">
                            <?php if (have_rows('footer_icons_social', 'option')) : ?>
                                <div class="lp-footer__social">
                                    <?php while (have_rows('footer_icons_social', 'option')) : the_row(); ?>
                                        <?php
                                        $icon_social = get_sub_field('footer_icon_social');
                                        $link = get_sub_field('footer_link_social');
                                        ?>

                                        <?php if ($link && $icon_social) : ?>
                                            <a class="lp-footer__social-link"
                                            href="<?php echo esc_url($link); ?>"
                                            target="_blank">
                                                <?php echo wp_get_attachment_image($icon_social['ID'], $icon_size, false, ['class' => 'lp-footer__social-icon']); ?>
                                            </a>
                                        <?php elseif ($icon_social) : ?>
                                            <span class="lp-footer__social-link">
                                                <?php echo wp_get_attachment_image($icon_social['ID'], $icon_size, false, ['class' => 'lp-footer__social-icon']); ?>
                                            </span>
                                        <?php endif; ?>

                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>





                <!-- partnership-image -->
                <?php if ($partnership_image) : ?>
                    <a href="<?php echo esc_url($partnership_image_link); ?>" class="lp-footer__partnership-image">
                        <?php echo wp_get_attachment_image($partnership_image['ID'], 'full'); ?>
                    </a>
                <?php endif; ?>



            </div>


            <div class="lp-footer__menu-discalimers">
                <!-- primary nav -->
                <?php if (!empty($menu_items)) : ?>
                    <nav class="lp-footer__quick-links" aria-label="<?php echo esc_attr__('Quick Links', 'launchpad'); ?>">
                        <ul class="lp-footer__links">
                            <?php foreach ($menu_items as $item) : ?>
                                <li class="lp-footer__link-wrapper">
                                    <a class="lp-footer__link" href="<?php echo esc_url($item->url); ?>">
                                        <?php echo esc_html($item->title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <!-- disclaimer link grid -->
                <div class="disclaimer-link-grid-container">
                    <?php
                    if (have_rows('menu_grid', 'option')) : ?>
                        <div class="menu-grid">
                            <?php while (have_rows('menu_grid', 'option')) : the_row(); ?>
                                <?php
                                $link = get_sub_field('grid_link');
                                if ($link) :
                                    $url    = $link['url'];
                                    $title  = $link['title'];
                                    $target = $link['target'] ? $link['target'] : '_self';
                                ?>
                                    <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                                        <?php echo esc_html($title); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="lp-footer__hr"></div>

                <div class="compliance-info-container">
                    <h3 class="compliance-title"> <?php echo esc_html($compliance_title); ?> </h3>
                    <div class="compliance-text-container"> <?php echo $compliance_text ?> </div>
                     <?php while (have_rows('compliance_links', 'option')) : the_row(); ?>
                        <?php
                        $link = get_sub_field('compliance_link');
                        if ($link) :
                            $url    = $link['url'];
                            $title  = $link['title'];
                            $target = $link['target'] ? $link['target'] : '_self';
                        ?>
                            <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                                <?php echo esc_html($title); ?>
                            </a>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>

            </div>

        </div>

    </div>
</div>


