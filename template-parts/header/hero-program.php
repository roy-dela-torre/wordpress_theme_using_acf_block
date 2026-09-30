<?php

/**
 * Custom template part for service hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package launchpad
 */

// Load values and assign defaults.
$header = get_field('display_title') ?? get_the_title();
$btn = get_field('hero_button') ?? null;
?>

<section class="lp-block lp-hero-default anchor">
    <div class="block-contents">
        <div class="lp-hero-default__text-contents">

            <?php if ($header) : ?>
                <h1 class="lp-hero-default__header"><?php echo esc_html($header); ?></h1>
            <?php endif; ?>

            <?php if ($btn): 
                (new Button($btn))->output();
            endif; ?>
        </div>
    </div>
    <img class='lp-hero-default__background-icon' src="<?php echo esc_url( get_parent_theme_file_uri( 'assets/img/clarvida-icon.png' ) ); ?>" alt="" />
</section>
