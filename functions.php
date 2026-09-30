<?php

/**
 * launchpad functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package launchpad
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function launchpad_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on launchpad, use a find and replace
	 * to change 'launchpad' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('launchpad', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in multiple locations.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'launchpad'),
			'utility-menu' => esc_html__('Utility Menu', 'launchpad'),
			'footer-menu' => esc_html__('Footer Menu', 'launchpad'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'launchpad_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'launchpad_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function launchpad_content_width()
{
	$GLOBALS['content_width'] = apply_filters('launchpad_content_width', 640);
}
add_action('after_setup_theme', 'launchpad_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function launchpad_widgets_init()
{
	//setup widgets here
}
add_action('widgets_init', 'launchpad_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function launchpad_scripts()
{

	wp_enqueue_style(
		'launchpad-google-fonts',
		'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap',
		[],
		null
	);

	wp_enqueue_style('launchpad-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('launchpad-style', 'rtl', 'replace');

	wp_enqueue_style('launchpad-mega-menu', get_template_directory_uri() . '/css/mega-menu.css', array(), _S_VERSION);
	wp_enqueue_style('launchpad-utility-nav', get_template_directory_uri() . '/css/utility-nav.css', array(), _S_VERSION);


	if (is_home()) {
		wp_enqueue_style('launchpad-blog', get_template_directory_uri() . '/css/blog.css', array(), _S_VERSION);
	}

	wp_enqueue_script('launchpad-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
	wp_enqueue_script('launchpad-smooth-scroll', get_template_directory_uri() . '/js/smooth-scroll.js', array(), _S_VERSION, true);

	wp_enqueue_script('launchpad-js', get_template_directory_uri() . '/js/launchpad.js', array(), _S_VERSION, true);
	wp_enqueue_script('launchpad-animations', get_template_directory_uri() . '/js/animations.js', array(), _S_VERSION, array('in_footer' => true, 'strategy' => 'defer'));


	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'launchpad_scripts');

/**
 * Click-to-load embed facade. Loaded on the front end and inside the block
 * editor iframe so previews behave the same.
 */
function launchpad_embed_facade_script()
{
	wp_enqueue_script('launchpad-embed-facade', get_template_directory_uri() . '/js/embed-facade.js', array(), _S_VERSION, array('in_footer' => true, 'strategy' => 'defer'));
}
add_action('enqueue_block_assets', 'launchpad_embed_facade_script');
//add_action('admin_enqueue_scripts', 'launchpad_scripts');

function enqueue_swiper()
{
	wp_enqueue_style('swiper-css', get_template_directory_uri() . '/inc/swiper/swiper-bundle.min.css', array(), _S_VERSION);
	wp_enqueue_script('swiper-js', get_template_directory_uri() . '/inc/swiper/swiper-bundle.min.js', array(), _S_VERSION);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper');
add_action('admin_enqueue_scripts', 'enqueue_swiper');
add_action('enqueue_block_assets', 'enqueue_swiper');


/**
 * Append outbound icon SVG to utility nav items with the "icon-outbound" CSS class.
 */
function launchpad_utility_nav_outbound_icon($title, $item, $args, $depth)
{
	if ('utility-menu' !== $args->theme_location) {
		return $title;
	}
	if (in_array('icon-outbound', (array) $item->classes, true)) {
		$title .= '<svg class="utility-nav__icon-outbound" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 5H5.00004C4.55801 5 4.13409 5.1756 3.82153 5.48816C3.50897 5.80072 3.33337 6.22464 3.33337 6.66667V15C3.33337 15.442 3.50897 15.866 3.82153 16.1785C4.13409 16.4911 4.55801 16.6667 5.00004 16.6667H13.3334C13.7754 16.6667 14.1993 16.4911 14.5119 16.1785C14.8244 15.866 15 15.442 15 15V10M9.16671 10.8333L16.6667 3.33333M16.6667 3.33333H12.5M16.6667 3.33333V7.5" stroke="currentColor" stroke-width="1.83333" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
	return $title;
}
add_filter('nav_menu_item_title', 'launchpad_utility_nav_outbound_icon', 10, 4);

/**
 * Custom Walker for Mega Menu Navigation.
 */
require get_template_directory() . '/inc/class-mega-menu-walker.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Components.
 */
require get_template_directory() . '/inc/components.php';

/**
 * Section wrapper (padding, background, animation) shared by all ACF blocks.
 */
require get_template_directory() . '/inc/section.php';

/**
 * Click-to-load facade for video/map iframes.
 */
require get_template_directory() . '/inc/embed-facade.php';


/**
 * Settings for WP Store Locator
 **/
require(locate_template('inc/wp_store_locator_setting.php'));

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}



function deny_blocks($allowed_blocks)
{
	// Get all registered blocks
	$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	// Disable specific blocks

	//text
	unset($blocks['core/details']);
	unset($blocks['core/pullquote']);
	unset($blocks['core/table']);
	unset($blocks['core/verse']);
	unset($blocks['core/quote']);
	unset($blocks['core/code']);
	unset($blocks['core/math']);
	unset($blocks['core/preformatted']);

	//media
	unset($blocks['core/gallery']);
	unset($blocks['core/audio']);
	unset($blocks['core/cover']);
	unset($blocks['core/file']);
	unset($blocks['core/media-text']);
	unset($blocks['core/video']);
	unset($blocks['core/icon']);

	//design
	unset($blocks['core/accordion']);
	unset($blocks['core/buttons']);
	unset($blocks['core/columns']);
	unset($blocks['core/more']);
	unset($blocks['core/nextpage']);

	//embeds
	unset($blocks['core/embed']);

	//widgets
	unset($blocks['core/legacy-widget']);

	unset($blocks['core/archives']);
	unset($blocks['core/calendar']);
	unset($blocks['core/terms-query']);
	unset($blocks['core/categories']);
	unset($blocks['core/latest-comments']);
	unset($blocks['core/latest-posts']);
	unset($blocks['core/page-list']);
	unset($blocks['core/rss']);
	unset($blocks['core/social-links']);
	unset($blocks['core/tag-cloud']);
	unset($blocks['core/search']);

	//theme
	unset($blocks['core/navigation']);
	unset($blocks['core/site-logo']);
	unset($blocks['core/site-title']);
	unset($blocks['core/site-tagline']);
	unset($blocks['core/query']);
	unset($blocks['core/avatar']);
	unset($blocks['core/title']);
	unset($blocks['core/post-title']);
	unset($blocks['core/post-excerpt']);
	unset($blocks['core/post-featured-image']);
	unset($blocks['core/post-author-name']);
	unset($blocks['core/post-comments-count']);
	unset($blocks['core/post-comments-link']);
	unset($blocks['core/post-date']);
	unset($blocks['core/date']);
	unset($blocks['core/post-modified-date']);
	unset($blocks['core/term-count']);
	unset($blocks['core/term-description']);
	unset($blocks['core/term-name']);
	unset($blocks['core/post-navigation-link']);
	unset($blocks['core/post-terms']);
	unset($blocks['core/query-title']);
	unset($blocks['core/loginout']);
	unset($blocks['core/post-time-to-read']);
	unset($blocks['core/post-word-count']);
	unset($blocks['core/read-more']);
	unset($blocks['core/comments']);
	unset($blocks['core/post-comments-form']);
	unset($blocks['core/post-author-biography']);
	unset($blocks['core/breadcrumbs']);



	return array_keys($blocks);
}
add_filter('allowed_block_types_all', 'deny_blocks');



/**
 * We use WordPress's init hook to make sure
 * our blocks are registered early in the loading
 * process.
 *
 * @link https://developer.wordpress.org/reference/hooks/init/
 */
function launchpad_register_acf_blocks()
{
	register_block_type(__DIR__ . '/blocks/hero-home');
	register_block_type(__DIR__ . '/blocks/content-media-img');
	register_block_type(__DIR__ . '/blocks/content-media-bubble');
	register_block_type(__DIR__ . '/blocks/content-media-embed');
	register_block_type(__DIR__ . '/blocks/callout');
	register_block_type(__DIR__ . '/blocks/embed');
	register_block_type(__DIR__ . '/blocks/columns');
	register_block_type(__DIR__ . '/blocks/cards');
	register_block_type(__DIR__ . '/blocks/state-nav');
	register_block_type(__DIR__ . '/blocks/us-map');
	register_block_type(__DIR__ . '/blocks/testimonials');
	register_block_type(__DIR__ . '/blocks/staff');
	register_block_type(__DIR__ . '/blocks/post-type-cards');
	register_block_type(__DIR__ . '/blocks/banner');
	register_block_type(__DIR__ . '/blocks/accordion');
	register_block_type(__DIR__ . '/blocks/state-location-map');
	register_block_type(__DIR__ . '/blocks/state-location-map-no-cards');
	register_block_type(__DIR__ . '/blocks/cognito-form');
	register_block_type(__DIR__ . '/blocks/logo-cards');
	register_block_type(__DIR__ . '/blocks/content-w-decorations');
	register_block_type(__DIR__ . '/blocks/location-map');
	register_block_type(__DIR__ . '/blocks/service-program-filter');
	register_block_type(__DIR__ . '/blocks/maps-cards');
}
add_action('init', 'launchpad_register_acf_blocks');

// Create a new catogory that will contain all ACF blocks
add_filter('block_categories_all', function ($categories, $post) {
	$custom = [
		[
			'slug' => 'custom-blocks',
			'title' => 'Custom Blocks'
		],
	];

	// Put your category at the top of the list:
	return array_merge($custom, $categories);
}, 10, 2);

/**
 * Add editor styles to the block editor
 *
 * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/
 */
add_action('after_setup_theme', function () {
	// Let WP know you have editor styles
	add_theme_support('editor-styles');
	// This will load your theme's style.css into the block editor
	add_editor_style('style.css');
});


add_action('admin_init', function () {
	// Redirect any user trying to access comments page
	global $pagenow;

	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}

	// Remove comments metabox from dashboard
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	// Disable support for comments and trackbacks in post types
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
	remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
});
add_action('admin_init', function () {
	// Redirect any user trying to access comments page
	global $pagenow;

	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}

	// Remove comments metabox from dashboard
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	// Disable support for comments and trackbacks in post types
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
	remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
});

/**
 * Force-enable Description and CSS Classes fields in the menu editor.
 */
add_filter('manage_nav-menus_columns', function ($columns) {
	$columns['description'] = __('Description', 'launchpad');
	$columns['css-classes'] = __('CSS Classes', 'launchpad');
	return $columns;
});

/**
 * Filter the except length to 20 words.
 */
function launchpad_custom_excerpt_length($length)
{
	return 20;
}
add_filter('excerpt_length', 'launchpad_custom_excerpt_length', 999);

function launchpad_add_gtm_head_snippet()
{
	$gtm_group = get_field('gtm', 'option');
	if ($gtm_group && $gtm_group['gtm_head_snippet']) {
		echo $gtm_group['gtm_head_snippet'];
	}
}
add_action('wp_head', 'launchpad_add_gtm_head_snippet', 10);

function launchpad_add_gtm_body_snippet()
{
	$gtm_group = get_field('gtm', 'option');
	if ($gtm_group && $gtm_group['gtm_body_snippet']) {
		echo $gtm_group['gtm_body_snippet'];
	}
}
add_action('wp_body_open', 'launchpad_add_gtm_body_snippet', 10);

function enable_breadcrumbs()
{
	$show_on_homepage = false;
	$show_current = true;
	$delimiter = '»';
	$home_label = 'Home';
	$before_wrap = '<span class="current">';
	$after_wrap = '</span>';

	/* Don't change values below */
	global $post;
	$home_url = get_bloginfo('url');

	if (is_home() || is_front_page()) {
		$on_homepage = true;
	} else {
		$on_homepage = false;
	}

	if (!$show_on_homepage && $on_homepage) {
		return;
	}

	/* Proceed with showing the breadcrumbs */
	$position = 1;
	$breadcrumbs = '<ol id="crumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';

	/* Home link */
	$breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$breadcrumbs .= '<a itemprop="item" href="' . esc_url($home_url) . '"><span itemprop="name">' . esc_html($home_label) . '</span></a>';
	$breadcrumbs .= '<meta itemprop="position" content="' . $position . '" />';
	$breadcrumbs .= '</li>';

	$ancestors = array();
	if (get_post_type($post) === "program") {
		$state = get_state($post);
		array_push($ancestors, get_page_by_path($state));
		array_push($ancestors, get_page_by_path($state . "/programs"));
	} elseif (get_post_type($post) === "wpsl_stores") {
		$state = get_state($post);
		array_push($ancestors, get_page_by_path($state));
		array_push($ancestors, get_page_by_path($state . "/locations"));
	} elseif (get_post_type($post) === "service") {
		$state = get_state($post);
		array_push($ancestors, get_page_by_path($state));
		array_push($ancestors, get_page_by_path($state . "/services"));
	} elseif (is_page() && $post->post_parent) { // Default Case
		$ancestors = array_reverse(get_post_ancestors($post));
	}

	foreach ($ancestors as $ancestor) {
		$position++;
		$breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$breadcrumbs .= $delimiter . ' <a itemprop="item" href="' . esc_url(get_permalink($ancestor)) . '"><span itemprop="name">' . esc_html(get_the_title($ancestor)) . '</span></a>';
		$breadcrumbs .= '<meta itemprop="position" content="' . $position . '" />';
		$breadcrumbs .= '</li>';
	}

	/* Current page */
	if ($show_current) {
		$position++;
		$breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$breadcrumbs .= $delimiter . ' ' . $before_wrap . '<span itemprop="name">' . esc_html(get_the_title()) . '</span>' . $after_wrap;
		$breadcrumbs .= '<meta itemprop="position" content="' . $position . '" />';
		$breadcrumbs .= '</li>';
	}

	$breadcrumbs .= '</ol>';
	echo $breadcrumbs;
}

function get_state($post)
{

	$term_name = "";
	if (get_post_type($post) === "program") {
		$term_name = "program-state";
	} elseif (get_post_type($post) === "wpsl_stores") {
		$term_name = "wpsl_store_category";
	} elseif (get_post_type($post) === "service") {
		$term_name = "service-state";
	}

	$state = '';
	$state_terms = wp_get_post_terms($post->ID, $term_name);
	if ($state_terms) {
		if ($state_terms[0]->slug) {
			$state = $state_terms[0]->slug;
		}
	}

	return $state;

}

function get_state_slug_by_term_id($term_id)
{

	$state_slug = "";
	$state_term = get_term($term_id);
	if ($state_term) {
		$state_slug = $state_term->slug;
	}
	return $state_slug;
}

function get_wpsl_state_id($state_slug)
{

	$state_id = 0;

	$state_obj = get_term_by('slug', $state_slug, 'wpsl_store_category');
	if ($state_obj && $state_obj->term_id) {
		$state_id = $state_obj->term_id;
	}

	return $state_id;

}

function get_wpsl_filtered_by_ids($ids, $state_slug)
{

	$output = "";
	if ($ids) {
		$state_id = get_wpsl_state_id($state_slug);

		$_SESSION['clarvida_wpsl_states'] = array($state_id); // Save for using it in wpsl_store_data, global varaibles won't work in those pre wpsl_* filters

		$_SESSION['clarvida_wpsl_ids'] = $ids; // Save for using it in wpsl_store_data, global varaibles won't work in those pre wpsl_* filters

		$output = do_shortcode('[wpsl start_location="' . $state_slug . '"]');
	}
	return $output;
}


//takes an array of posts, and returns html for checkboxes wrapped in <li> elements (does not generate container ul)
function generate_post_checkboxes($posts)
{
	$output = "";
	foreach ($posts as $post) {
		$post_display_name = get_field("display_title", $post->ID) ?? get_the_title($post->ID);
		$output .= "<li><label><input type='checkbox' value='$post->ID'> $post_display_name</label></li>";
	}
	return $output;
}

//takes a state slug and returns html for checkboxes wrapped in <li> elements (does not generate container ul)
function generate_state_program_checkboxes($state_slug)
{

	$output = "";

	$locations = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => 'wpsl_stores',
		'tax_query' => array(
			array(
				'taxonomy' => 'wpsl_store_category',
				'field' => 'slug',
				'terms' => $state_slug
			)
		),
	));


	$valid_program_ids = array();
	foreach ($locations as $location) {
		$location_programs = get_post_meta($location->ID, 'available_programs', true);
		$location_programs = ($location_programs === '') ? array() : $location_programs;
		$valid_program_ids = array_merge($valid_program_ids, $location_programs);
	}
	$valid_program_ids = array_unique($valid_program_ids);

	if (empty($valid_program_ids)) {
		return $output;
	}

	$programs = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => 'program',
		'post__in' => $valid_program_ids
	));


	if ($programs) {
		$output .= generate_post_checkboxes($programs);
	}

	return $output;
}

//takes a state slug and returns html for checkboxes wrapped in <li> elements (does not generate container ul)
function generate_state_service_checkboxes($state_slug)
{

	$output = "";

	$locations = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => 'wpsl_stores',
		'tax_query' => array(
			array(
				'taxonomy' => 'wpsl_store_category',
				'field' => 'slug',
				'terms' => $state_slug
			)
		),
	));

	$valid_service_ids = array();
	foreach ($locations as $location) {
		$location_services = get_post_meta($location->ID, 'available_services', true);
		$location_services = ($location_services === '') ? array() : $location_services;
		$valid_service_ids = array_unique(array_merge($valid_service_ids, $location_services));
	}

	if (empty($valid_service_ids)) {
		return $output;
	}

	$services = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => 'service',
		'post__in' => $valid_service_ids
	));

	if ($services) {
		$output .= generate_post_checkboxes($services);
	}

	return $output;

}


function generate_program_filter_html($state_slug = "")
{

	$output = "";

	$output .= "<div id='program-filter' class='filter-group'>";
	$output .= generate_program_filter_inner_html($state_slug);
	$output .= "</div>";

	return $output;
}

function generate_program_filter_inner_html($state_slug = "")
{
	$output = "";

	if ($state_slug) {

		$program_checkboxes = generate_state_program_checkboxes($state_slug);
		if ($program_checkboxes === "") {
			return $output;
		}

		$output .= "<p class='filter-heading'>Programs</p>";
		$output .= "<ul class='wpsl-checkboxes wpsl-custom-checkboxes' data-name='programs'>";
		$output .= $program_checkboxes;
		$output .= "</ul>";
	}

	return $output;
}


function generate_service_filter_html($state_slug = "")
{
	$output = "";

	$output .= "<div id='service-filter' class='filter-group'>";
	$output .= generate_service_filter_inner_html($state_slug);
	$output .= "</div>";

	return $output;
}

function generate_service_filter_inner_html($state_slug = "")
{
	$output = "";

	if ($state_slug) {

		$service_checkboxes = generate_state_service_checkboxes($state_slug);
		if ($service_checkboxes === "") {
			return $output;
		}

		$output .= "<p class='filter-heading'>Services</p>";
		$output .= "<ul class='wpsl-checkboxes wpsl-custom-checkboxes' data-name='services'>";
		$output .= $service_checkboxes;
		$output .= "</ul>";
	}

	return $output;

}


add_action('rest_api_init', function () {
	register_rest_route('custom-wpsl', '/program-filters/', [
		'methods' => 'GET',
		'callback' => 'program_filters_callback',
		'permission_callback' => '__return_true' // public access
	]);
});
function program_filters_callback($request)
{
	$state_id = sanitize_text_field($request->get_param('state_id'));

	$state_slug = get_state_slug_by_term_id($state_id);
	$program_filters_html = generate_program_filter_inner_html($state_slug);

	return rest_ensure_response($program_filters_html);
}


add_action('rest_api_init', function () {
	register_rest_route('custom-wpsl', '/service-filters/', [
		'methods' => 'GET',
		'callback' => 'service_filters_callback',
		'permission_callback' => '__return_true' // public access
	]);
});
function service_filters_callback($request)
{
	$state_id = sanitize_text_field($request->get_param('state_id'));

	$state_slug = get_state_slug_by_term_id($state_id);
	$service_filters_html = generate_service_filter_inner_html($state_slug);

	return rest_ensure_response($service_filters_html);
}


add_action('rest_api_init', function () {
	register_rest_route('custom-clarvida', '/service-program-cards/', [
		'methods' => 'GET',
		'callback' => 'service_program_cards_callback',
		'permission_callback' => '__return_true' // public access
	]);
});
function service_program_cards_callback($request)
{

	$post_type = $request->get_param('post_type');
	if ($post_type !== 'service' && $post_type !== 'program') {
		return rest_ensure_response(""); //prevent queries for other post types
	}

	$state = $request->get_param("state");

	$taxonomy_filter_value = $state;
	$taxonomy_filter_key = $post_type . "-state";

	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => $post_type,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy_filter_key,
				'field' => 'slug',
				'terms' => $taxonomy_filter_value
			)
		),
	));


	$output_html = "";

	//todo: loop through posts and generate html
	ob_start();
	if ($posts):
		global $post;
		foreach ($posts as $post):
			setup_postdata($post);
			get_template_part('template-parts/cards/card', get_post_type());
		endforeach;
		wp_reset_query();
	endif;
	$output_html = ob_get_clean();

	return rest_ensure_response($output_html);
}