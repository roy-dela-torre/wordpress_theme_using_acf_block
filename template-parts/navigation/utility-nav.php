<?php if ( has_nav_menu( 'utility-menu' ) ) : ?>
<nav class="utility-nav" aria-label="<?php esc_attr_e( 'Utility Menu', 'chusie-kokoro' ); ?>">
    <div class="utility-nav__inner">
        <?php wp_nav_menu( array(
            'theme_location' => 'utility-menu',
            'menu_id'        => 'utility-menu',
            'container'      => false,
            'depth'          => 1,
            'fallback_cb'    => false,
        ) ); ?>
    </div>
</nav>
<?php endif; ?>
