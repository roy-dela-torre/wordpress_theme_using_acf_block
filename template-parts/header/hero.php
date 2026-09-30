<?php

/**
 * Custom template part for default hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package launchpad
 */

?>
<?php if (!is_home() && !is_front_page()) : 

    // Load values and assign defaults.
    $header = get_field('hero_header_text') ?? "";
    if (!$header) {
        $header = get_the_title();
    }
    $btn = get_field('hero_button') ?? null;
    $content = get_field('hero_content') ?? "";
    ?>

    <section class="lp-block lp-hero-default anchor">
        <div class="block-contents">
            <div class="lp-hero-default__text-contents">

                <?php if ($header) : ?>
                    <h1 class="lp-hero-default__header"><?php echo esc_html($header); ?></h1>
                <?php endif; ?>

                <? if ($content) : ?>
                    <div class="lp-hero-default__content"><?= $content ?></div>
                <?php endif; ?>

                <?php if ($btn): 
                    (new Button($btn))->output();
                endif; ?>
            </div>
        </div>
        <img class='lp-hero-default__background-icon' src="<?php echo esc_url( get_parent_theme_file_uri( 'assets/img/clarvida-icon.png' ) ); ?>" alt="" />
    </section>

<?php endif; ?>