<?php
/**
 * Click-to-load facade for iframes (YouTube, Vimeo, Google Maps, anything else).
 *
 * Instead of printing the iframe, we print a light poster + button. The real
 * iframe is only created when the visitor clicks (js/embed-facade.js), which
 * saves megabytes of third-party JS and keeps LCP/INP fast.
 *
 * Usage:
 *   echo ck_embed_facade(get_field('embed'), ['poster' => get_field('embed_poster')]);
 *
 * @package chusie-kokoro
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Pulls the attributes we care about out of an <iframe> snippet or a bare URL.
 *
 * @param string $embed Raw embed code or URL.
 * @return array|null
 */
function ck_embed_parse($embed)
{
	$embed = trim((string) $embed);
	if ($embed === '') {
		return null;
	}

	// Bare URL (e.g. a YouTube watch link pasted into the field).
	if (preg_match('#^https?://\S+$#i', $embed)) {
		return array('src' => $embed, 'title' => '', 'width' => '', 'height' => '', 'allow' => '', 'referrerpolicy' => '');
	}

	if (stripos($embed, '<iframe') === false || !class_exists('WP_HTML_Tag_Processor')) {
		return null;
	}

	$tags = new WP_HTML_Tag_Processor($embed);
	if (!$tags->next_tag('iframe')) {
		return null;
	}

	$src = (string) $tags->get_attribute('src');
	if ($src === '') {
		return null;
	}

	return array(
		'src'            => html_entity_decode($src),
		'title'          => (string) $tags->get_attribute('title'),
		'width'          => (string) $tags->get_attribute('width'),
		'height'         => (string) $tags->get_attribute('height'),
		'allow'          => (string) $tags->get_attribute('allow'),
		'referrerpolicy' => (string) $tags->get_attribute('referrerpolicy'),
	);
}

/**
 * Works out the provider, the iframe URL to load on click, and the poster.
 *
 * @param string $src Original iframe src or page URL.
 * @return array
 */
function ck_embed_provider($src)
{
	$query = array();
	$parts = wp_parse_url($src);
	if (!empty($parts['query'])) {
		parse_str($parts['query'], $query);
	}

	// YouTube: embed/, watch?v=, shorts/, live/, youtu.be/
	if (preg_match('#(?:youtube(?:-nocookie)?\.com/(?:embed/|shorts/|live/|watch\?(?:.*&)?v=)|youtu\.be/)([\w-]{11})#i', $src, $m)) {
		$id     = $m[1];
		$params = array_intersect_key($query, array_flip(array('start', 'end', 'list', 'si')));
		if (isset($query['t']) && !isset($params['start'])) {
			$params['start'] = (int) $query['t'];
		}
		$params = array_merge($params, array('autoplay' => 1, 'rel' => 0));

		return array(
			'type'      => 'youtube',
			'id'        => $id,
			'src'       => add_query_arg($params, 'https://www.youtube-nocookie.com/embed/' . $id),
			'page_url'  => 'https://www.youtube.com/watch?v=' . $id,
			'poster'    => 'https://i.ytimg.com/vi/' . $id . '/maxresdefault.jpg',
			'fallback'  => 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg',
			'allow'     => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
			'ratio'     => '16 / 9',
		);
	}

	// Vimeo: player.vimeo.com/video/ID or vimeo.com/ID
	if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $src, $m)) {
		$id     = $m[1];
		$params = array_intersect_key($query, array_flip(array('h')));
		$params = array_merge($params, array('autoplay' => 1, 'dnt' => 1));

		return array(
			'type'     => 'vimeo',
			'id'       => $id,
			'src'      => add_query_arg($params, 'https://player.vimeo.com/video/' . $id),
			'page_url' => 'https://vimeo.com/' . $id,
			'poster'   => '',
			'fallback' => '',
			'allow'    => 'autoplay; fullscreen; picture-in-picture',
			'ratio'    => '16 / 9',
		);
	}

	// Google Maps
	if (preg_match('#google\.[a-z.]+/maps|maps\.google\.#i', $src)) {
		return array(
			'type'     => 'map',
			'id'       => '',
			'src'      => $src,
			'page_url' => '',
			'poster'   => '',
			'fallback' => '',
			'allow'    => '',
			'ratio'    => '',
		);
	}

	return array(
		'type'     => 'iframe',
		'id'       => '',
		'src'      => $src,
		'page_url' => '',
		'poster'   => '',
		'fallback' => '',
		'allow'    => '',
		'ratio'    => '',
	);
}

/**
 * Title + thumbnail from the provider's oEmbed endpoint, cached for a week.
 *
 * @param string $url Public video page URL.
 * @return array { title, thumbnail }
 */
function ck_embed_oembed_data($url)
{
	$key    = 'ck_oembed_' . md5($url);
	$cached = get_transient($key);
	if (is_array($cached)) {
		return $cached;
	}

	$data = array('title' => '', 'thumbnail' => '');

	if (function_exists('_wp_oembed_get_object')) {
		$response = _wp_oembed_get_object()->get_data($url, array('width' => 1280));
		if ($response) {
			$data['title']     = isset($response->title) ? (string) $response->title : '';
			$data['thumbnail'] = isset($response->thumbnail_url) ? (string) $response->thumbnail_url : '';
		}
	}

	set_transient($key, $data, $data['title'] ? WEEK_IN_SECONDS : DAY_IN_SECONDS);

	return $data;
}

/**
 * Returns the facade markup for an embed. Falls back to the raw embed when
 * it isn't an iframe (e.g. a script-based form embed).
 *
 * @param string $embed Embed code or URL.
 * @param array  $args {
 *     @type array|int $poster Optional ACF image (array or ID) used as the poster.
 *     @type string    $title  Accessible title; defaults to the iframe/oEmbed title.
 *     @type string    $ratio  CSS aspect-ratio, e.g. '16 / 9'. Defaults per provider.
 *     @type string    $class  Extra classes on the facade wrapper.
 * }
 * @return string
 */
function ck_embed_facade($embed, $args = array())
{
	$args = wp_parse_args($args, array(
		'poster' => null,
		'title'  => '',
		'ratio'  => '',
		'class'  => '',
	));

	$iframe = ck_embed_parse($embed);
	if (!$iframe) {
		return (string) $embed;
	}

	$provider = ck_embed_provider($iframe['src']);
	$poster   = '';

	// Providers' copy-paste codes use generic titles; they describe nothing.
	$iframe_title = preg_match('/^(youtube video player|vimeo video player|embedded content)$/i', trim($iframe['title'])) ? '' : $iframe['title'];
	$title        = $args['title'] ?: $iframe_title;

	if (in_array($provider['type'], array('youtube', 'vimeo'), true)) {
		// The real video title is the most descriptive label for "Play video: …".
		$oembed = ck_embed_oembed_data($provider['page_url']);
		$title  = $oembed['title'] ?: $title;
		if ($provider['type'] === 'vimeo' && $oembed['thumbnail']) {
			$provider['poster'] = $oembed['thumbnail'];
		}
	}

	/* Accessible labels
	--------------------------------------------- */
	switch ($provider['type']) {
		case 'youtube':
		case 'vimeo':
			$title  = $title ?: __('Video', 'chusie-kokoro');
			/* translators: %s: video title */
			$label  = sprintf(__('Play video: %s', 'chusie-kokoro'), $title);
			$button = __('Play video', 'chusie-kokoro');
			$icon   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l11-6.86a1 1 0 0 0 0-1.72l-11-6.86A1 1 0 0 0 8 5.14z"/></svg>';
			break;

		case 'map':
			$title  = $title ?: __('Map', 'chusie-kokoro');
			/* translators: %s: map title */
			$label  = sprintf(__('Load interactive map: %s', 'chusie-kokoro'), $title);
			$button = __('View map', 'chusie-kokoro');
			$icon   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>';
			break;

		default:
			$title  = $title ?: __('Embedded content', 'chusie-kokoro');
			/* translators: %s: embed title */
			$label  = sprintf(__('Load embedded content: %s', 'chusie-kokoro'), $title);
			$button = __('Load content', 'chusie-kokoro');
			$icon   = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M8.7 16.3 4.4 12l4.3-4.3-1.4-1.4L1.6 12l5.7 5.7 1.4-1.4zm6.6 0 4.3-4.3-4.3-4.3 1.4-1.4 5.7 5.7-5.7 5.7-1.4-1.4z"/></svg>';
	}

	/* Poster image
	--------------------------------------------- */
	$poster_id = is_array($args['poster']) ? ($args['poster']['ID'] ?? 0) : (int) $args['poster'];

	if ($poster_id) {
		$poster = wp_get_attachment_image($poster_id, 'large', false, array(
			'class'    => 'ck-embed-facade__poster',
			'alt'      => '',
			'loading'  => 'lazy',
			'decoding' => 'async',
		));
	} elseif ($provider['poster']) {
		$poster = sprintf(
			'<img class="ck-embed-facade__poster" src="%s"%s alt="" width="1280" height="720" loading="lazy" decoding="async">',
			esc_url($provider['poster']),
			$provider['fallback'] ? ' data-fallback="' . esc_url($provider['fallback']) . '"' : ''
		);
	}

	/* Aspect ratio
	--------------------------------------------- */
	$ratio = $args['ratio'] ?: $provider['ratio'];
	if (!$ratio && is_numeric($iframe['width']) && is_numeric($iframe['height']) && (int) $iframe['height'] > 0) {
		$ratio = (int) $iframe['width'] . ' / ' . (int) $iframe['height'];
	}
	$ratio = $ratio ?: '16 / 9';

	$classes = array('ck-embed-facade', 'ck-embed-facade--' . $provider['type']);
	if (!$poster) {
		$classes[] = 'ck-embed-facade--placeholder';
	}
	if ($args['class']) {
		$classes[] = $args['class'];
	}

	$allow          = $provider['allow'] ?: $iframe['allow'];
	$referrerpolicy = $iframe['referrerpolicy'] ?: ($provider['type'] === 'map' ? 'no-referrer-when-downgrade' : 'strict-origin-when-cross-origin');

	ob_start();
	?>
	<div class="<?php echo esc_attr(implode(' ', $classes)); ?>"
		style="--ck-embed-ratio: <?php echo esc_attr($ratio); ?>"
		data-ck-embed
		data-src="<?php echo esc_url($provider['src']); ?>"
		data-title="<?php echo esc_attr($title); ?>"
		data-allow="<?php echo esc_attr($allow); ?>"
		data-referrerpolicy="<?php echo esc_attr($referrerpolicy); ?>">
		<button type="button" class="ck-embed-facade__trigger" aria-label="<?php echo esc_attr($label); ?>">
			<span class="ck-embed-facade__icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			<span class="ck-embed-facade__label"><?php echo esc_html($provider['type'] === 'iframe' || $provider['type'] === 'map' ? $button : $title); ?></span>
		</button>
		<?php echo $poster; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above. ?>
		<noscript>
			<iframe src="<?php echo esc_url(remove_query_arg('autoplay', $provider['src'])); ?>" title="<?php echo esc_attr($title); ?>" loading="lazy" allowfullscreen<?php echo $allow ? ' allow="' . esc_attr($allow) . '"' : ''; ?>></iframe>
		</noscript>
	</div>
	<?php
	return ob_get_clean();
}
