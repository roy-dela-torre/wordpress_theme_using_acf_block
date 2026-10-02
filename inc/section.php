<?php
/**
 * Shared section wrapper for every Chusie Kokoro ACF block.
 *
 * Reads the "Block: Section Settings" field group (acf-json/group_6a5f10a2c4d01.json)
 * and prints the standard block wrapper:
 *
 *   <section class="ck-block ck-{slug} has_bg|no_bg …" data-animate="fade-up">
 *       <div id="{anchor}" class="block-contents {className}">
 *           …block markup…
 *       </div>
 *       <div class="ck-section__tint"></div>
 *       <div class="ck-section__media"><img class="ck-section__cover"></div>
 *   </section>
 *
 * Usage inside a block template:
 *
 *   ck_section_open($block, 'cards', ['default_bg' => 'light']);
 *       …markup…
 *   ck_section_close();
 *
 * @package chusie-kokoro
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Background presets an editor can choose, mapped to whether they need light text.
 */
function ck_section_presets()
{
	return array(
		'light' => false,
		'muted' => false,
		'dark'  => true,
		'brand' => true,
	);
}

/**
 * Entrance animations an editor can choose.
 */
function ck_section_animations()
{
	return array('fade-up', 'fade', 'zoom-in', 'slide-left', 'slide-right', 'none');
}

/**
 * Stack of open sections so ck_section_close() knows what to print.
 *
 * @return array
 */
function &ck_section_stack()
{
	static $stack = array();
	return $stack;
}

/**
 * Unique, stable id for a block's main heading (used for aria-labelledby).
 *
 * @param array  $block  ACF block array.
 * @param string $suffix Optional suffix.
 * @return string
 */
function ck_heading_id($block, $suffix = 'title')
{
	$id = !empty($block['id']) ? $block['id'] : uniqid('block_');
	return 'ck-' . sanitize_html_class($id) . '-' . $suffix;
}

/**
 * Accepts a hex or rgb(a) color string from the ACF color picker.
 *
 * @param mixed $color Raw value.
 * @return string Safe CSS color or ''.
 */
function ck_sanitize_css_color($color)
{
	if (!is_string($color)) {
		return '';
	}
	$color = trim($color);

	if (sanitize_hex_color($color)) {
		return $color;
	}

	if (preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(,\s*(0|1|0?\.\d+)\s*)?\)$/', $color)) {
		return $color;
	}

	return '';
}

/**
 * Rough "is this a dark color" check so text flips to white automatically.
 *
 * @param string $color Hex or rgb(a) color.
 * @return bool
 */
function ck_is_dark_color($color)
{
	$rgb = null;

	if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color, $m)) {
		$hex = $m[1];
		if (strlen($hex) === 3) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		$rgb = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
	} elseif (preg_match('/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})/', $color, $m)) {
		$rgb = array((int) $m[1], (int) $m[2], (int) $m[3]);
	}

	if (!$rgb) {
		return false;
	}

	// Relative luminance (WCAG).
	$channel = function ($c) {
		$c = $c / 255;
		return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
	};
	$luminance = 0.2126 * $channel($rgb[0]) + 0.7152 * $channel($rgb[1]) + 0.0722 * $channel($rgb[2]);

	return $luminance < 0.4;
}

/**
 * Collects the section settings for the current block.
 *
 * @param array $args See ck_section_open().
 * @return array
 */
function ck_section_settings($args)
{
	$get = function ($name) {
		return function_exists('get_field') ? get_field($name) : null;
	};

	$settings = array(
		'id'         => (string) $get('section_id'),
		'class'      => (string) $get('section_class'),
		'animation'  => (string) $get('section_animation'),
		'padding'    => (array) $get('section_padding'),
		'bg_type'    => (string) $get('section_bg_type'),
		'bg_color'   => ck_sanitize_css_color($get('section_bg_color')),
		'grad_start' => ck_sanitize_css_color($get('section_gradient_start')),
		'grad_end'   => ck_sanitize_css_color($get('section_gradient_end')),
		'grad_angle' => $get('section_gradient_angle'),
		'bg_image'   => $get('section_bg_image'),
		'tint'       => (bool) $get('section_tint'),
		'text_color' => (string) $get('section_text_color'),
	);

	if ($settings['bg_type'] === '' || $settings['bg_type'] === 'default') {
		$settings['bg_type'] = $args['default_bg'];
	}

	if (!in_array($settings['animation'], ck_section_animations(), true)) {
		$settings['animation'] = $args['animation'];
	}

	return $settings;
}

/**
 * Opens a block section. Must be paired with ck_section_close().
 *
 * @param array  $block ACF block array passed to the render template.
 * @param string $slug  Block slug without the ck- prefix, e.g. 'cards'.
 * @param array  $args {
 *     @type string $default_bg     Background used when the editor leaves "Default":
 *                                  none|light|muted|dark|brand. Default 'none'.
 *     @type string $animation      Default entrance animation. Default 'fade-up'.
 *     @type string $class          Extra classes for the <section> (modifiers).
 *     @type string $contents_class Extra classes for .block-contents.
 *     @type string $labelledby     Heading id for aria-labelledby (see ck_heading_id()).
 *     @type array  $attrs          Extra attributes for the <section>, e.g. data-*.
 *     @type string $after          Decorative markup printed after .block-contents
 *                                  (outside the animated content), e.g. background icons.
 * }
 */
function ck_section_open($block, $slug, $args = array())
{
	$args = wp_parse_args($args, array(
		'default_bg'     => 'none',
		'animation'      => 'fade-up',
		'class'          => '',
		'contents_class' => '',
		'labelledby'     => '',
		'attrs'          => array(),
		'after'          => '',
	));

	static $rendered = 0;
	$rendered++;

	$s        = ck_section_settings($args);
	$classes  = array('ck-block', 'ck-' . $slug);
	$styles   = array();
	$has_bg   = false;
	$is_dark  = false;
	$media    = '';
	$tint     = false;
	$presets  = ck_section_presets();

	/* Background
	--------------------------------------------- */
	switch ($s['bg_type']) {
		case 'color':
			if ($s['bg_color']) {
				$styles[] = '--ck-bg:' . $s['bg_color'];
				$has_bg   = true;
				$is_dark  = ck_is_dark_color($s['bg_color']);
			}
			break;

		case 'gradient':
			if ($s['grad_start'] && $s['grad_end']) {
				$angle    = is_numeric($s['grad_angle']) ? (int) $s['grad_angle'] : 135;
				$styles[] = sprintf('--ck-bg:linear-gradient(%ddeg, %s 0%%, %s 100%%)', $angle, $s['grad_start'], $s['grad_end']);
				$has_bg   = true;
				$is_dark  = ck_is_dark_color($s['grad_start']) && ck_is_dark_color($s['grad_end']);
			}
			break;

		case 'image':
			$image_id = is_array($s['bg_image']) ? ($s['bg_image']['ID'] ?? 0) : (int) $s['bg_image'];
			if ($image_id) {
				$has_bg = true;
				$tint   = $s['tint'];
				$is_dark = $tint;

				// The first section on the page is usually the LCP element: load it eagerly.
				$eager = $rendered === 1;
				$media = wp_get_attachment_image($image_id, $eager ? 'full' : 'large', false, array(
					'class'         => 'ck-section__cover ck-' . $slug . '__cover',
					'alt'           => '',
					'loading'       => $eager ? 'eager' : 'lazy',
					'fetchpriority' => $eager ? 'high' : 'auto',
					'decoding'      => 'async',
					'sizes'         => '100vw',
				));
			}
			break;

		default:
			if (isset($presets[$s['bg_type']])) {
				$classes[] = 'ck-section--bg-' . $s['bg_type'];
				$has_bg    = true;
				$is_dark   = $presets[$s['bg_type']];
			}
	}

	if ($has_bg) {
		if ($s['text_color'] === 'light') {
			$is_dark = true;
		} elseif ($s['text_color'] === 'dark') {
			$is_dark = false;
		}
	}

	$classes[] = $has_bg ? 'has_bg' : 'no_bg';
	if ($is_dark) {
		$classes[] = 'is-dark';
	}

	/* Padding (desktop px values; CSS scales them for tablet/mobile)
	--------------------------------------------- */
	$sides = array('top' => 'pt', 'right' => 'pr', 'bottom' => 'pb', 'left' => 'pl');
	foreach ($sides as $side => $token) {
		$value = $s['padding'][$side] ?? '';
		if ($value !== '' && $value !== null && is_numeric($value)) {
			$styles[]  = '--ck-' . $token . ':' . max(0, (int) $value) . 'px';
			$classes[] = 'has-custom-' . $token;
		}
	}

	/* Custom classes
	--------------------------------------------- */
	foreach (preg_split('/\s+/', trim($s['class'] . ' ' . $args['class'])) as $custom) {
		if ($custom !== '') {
			$classes[] = sanitize_html_class($custom);
		}
	}

	/* Attributes
	--------------------------------------------- */
	$attrs = array(
		'class'        => implode(' ', array_unique($classes)),
		'style'        => $styles ? implode(';', $styles) : '',
		'data-animate' => $s['animation'],
	);

	if ($args['labelledby']) {
		$attrs['aria-labelledby'] = $args['labelledby'];
	}

	foreach ((array) $args['attrs'] as $name => $value) {
		if ($value !== '' && $value !== null && $value !== false) {
			$attrs[$name] = $value;
		}
	}

	$anchor = $s['id'] ? sanitize_title($s['id']) : ($block['anchor'] ?? '');

	$contents_classes = trim('block-contents ' . $args['contents_class'] . ' ' . ($block['className'] ?? ''));

	echo '<section';
	foreach ($attrs as $name => $value) {
		if ($value !== '') {
			printf(' %s="%s"', esc_attr($name), esc_attr($value));
		}
	}
	echo '>';

	printf(
		'<div%s class="%s">',
		$anchor ? ' id="' . esc_attr($anchor) . '"' : '',
		esc_attr($contents_classes)
	);

	$stack   = &ck_section_stack();
	$stack[] = array(
		'slug'  => $slug,
		'tint'  => $tint,
		'media' => $media,
		'after' => (string) $args['after'],
	);
}

/**
 * Closes the section opened by ck_section_open() and prints background layers.
 */
function ck_section_close()
{
	$stack   = &ck_section_stack();
	$section = array_pop($stack);

	echo '</div>';

	if ($section) {
		if ($section['after']) {
			echo $section['after']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built by the block template.
		}

		if ($section['tint']) {
			printf('<div class="ck-section__tint ck-%s__tint" aria-hidden="true"></div>', esc_attr($section['slug']));
		}

		if ($section['media']) {
			echo '<div class="ck-section__media" aria-hidden="true">' . $section['media'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() output.
		}
	}

	echo '</section>';
}

/**
 * Prints a primary + secondary button pair inside .ck-btn-group.
 *
 * @param array|null $primary   ACF link array.
 * @param array|null $secondary ACF link array.
 * @param string     $class     Extra classes for the group wrapper.
 */
function ck_buttons($primary = null, $secondary = null, $class = '')
{
	$primary   = !empty($primary['url']) ? $primary : null;
	$secondary = !empty($secondary['url']) ? $secondary : null;

	if (!$primary && !$secondary) {
		return;
	}

	echo '<div class="' . esc_attr(trim('ck-btn-group ' . $class)) . '">';
	if ($primary) {
		(new Button($primary, '', 'primary'))->output();
	}
	if ($secondary) {
		(new Button($secondary, '', $primary ? 'secondary' : 'primary'))->output();
	}
	echo '</div>';
}

/**
 * Adds .ck-js to <html> as early as possible so animations can pre-hide
 * content without a flash. If js/animations.js never runs, the class is
 * removed again so nothing stays hidden.
 */
function ck_animation_head_flag()
{
	echo "<script>document.documentElement.classList.add('ck-js');setTimeout(function(){if(!window.ckAnimReady){document.documentElement.classList.remove('ck-js');}},4000);</script>\n";
}
add_action('wp_head', 'ck_animation_head_flag', 1);
