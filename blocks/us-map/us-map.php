<?php

/**
 * Interactive US Map template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values.
$header = get_field('header');
$sub_header = get_field('sub_header');
$body = get_field('body');
$btn = get_field('button');
$caption = get_field('caption');

$decorations = get_field('decorations');

// State code to full name mapping.
$state_names = array(
    'AL' => 'Alabama',        'AK' => 'Alaska',        'AZ' => 'Arizona',
    'AR' => 'Arkansas',       'CA' => 'California',    'CO' => 'Colorado',
    'CT' => 'Connecticut',    'DE' => 'Delaware',      'FL' => 'Florida',
    'GA' => 'Georgia',        'HI' => 'Hawaii',        'ID' => 'Idaho',
    'IL' => 'Illinois',       'IN' => 'Indiana',       'IA' => 'Iowa',
    'KS' => 'Kansas',         'KY' => 'Kentucky',      'LA' => 'Louisiana',
    'ME' => 'Maine',          'MD' => 'Maryland',      'MA' => 'Massachusetts',
    'MI' => 'Michigan',       'MN' => 'Minnesota',     'MS' => 'Mississippi',
    'MO' => 'Missouri',       'MT' => 'Montana',       'NE' => 'Nebraska',
    'NV' => 'Nevada',         'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
    'NM' => 'New Mexico',     'NY' => 'New York',      'NC' => 'North Carolina',
    'ND' => 'North Dakota',   'OH' => 'Ohio',          'OK' => 'Oklahoma',
    'OR' => 'Oregon',         'PA' => 'Pennsylvania',  'RI' => 'Rhode Island',
    'SC' => 'South Carolina', 'SD' => 'South Dakota',  'TN' => 'Tennessee',
    'TX' => 'Texas',          'UT' => 'Utah',          'VT' => 'Vermont',
    'VA' => 'Virginia',       'WA' => 'Washington',    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',      'WY' => 'Wyoming',
);

// Build state map from repeater.
$state_map = array();
foreach ((get_field('states') ?: array()) as $row) {
    $code = strtoupper(trim($row['state_code'] ?? ''));
    $url = $row['state_url']['url'] ?? '';
    if ($code && $url) {
        $state_map[$code] = array(
            'url' => $url,
            'target' => $row['state_url']['target'] ?: '_self',
        );
    }
}

$sort_by_name = function ($a, $b) use ($state_names) {
    return strcmp($state_names[$a] ?? $a, $state_names[$b] ?? $b);
};
uksort($state_map, $sort_by_name);

// Small east-coast states get a clickable legend next to the map.
$small_east_coast = array('CT', 'DE', 'FL', 'GA', 'MA', 'MD', 'ME', 'NC', 'NH', 'NJ', 'NY', 'PA', 'VA', 'RI', 'SC', 'VT', 'WV');
$legend_states = array_intersect_key($state_map, array_flip($small_east_coast));

// Inline SVG without the XML prolog/doctype (invalid inside HTML).
$svg = '';
$svg_path = get_template_directory() . '/blocks/us-map/us-map.svg';
if (file_exists($svg_path)) {
    $svg = file_get_contents($svg_path);
    $svg = substr($svg, (int) strpos($svg, '<svg'));
    $svg = preg_replace('/<svg\b/', '<svg aria-hidden="true" focusable="false"', $svg, 1);
}

$heading_id = lp_heading_id($block);

$after = '';
if ($decorations) {
    ob_start(); ?>
    <div class="lp-us-map__decorations" aria-hidden="true">
        <?php (new LeftUDecoration)->output(); ?>
        <?php (new RightCircleDecoration)->output(); ?>
    </div>
    <?php
    $after = ob_get_clean();
}

lp_section_open($block, 'us-map', array(
    'labelledby' => $header ? $heading_id : '',
    'after' => $after,
));
?>

    <?php if ($header || $sub_header || $body) : ?>
        <div class="lp-us-map__intro">
            <?php if ($header) : ?>
                <h2 id="<?php echo esc_attr($heading_id); ?>" class="lp-us-map__header"><?php echo esc_html($header); ?></h2>
            <?php endif; ?>
            <?php if ($sub_header) : ?>
                <p class="lp-us-map__sub-header lp-lead"><?php echo esc_html($sub_header); ?></p>
            <?php endif; ?>
            <?php if ($body) : ?>
                <div class="lp-us-map__body"><?php echo wp_kses_post($body); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="lp-us-map__map-area">
        <div class="lp-us-map__map-wrapper" data-states="<?php echo esc_attr(wp_json_encode($state_map)); ?>">
            <?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-bundled SVG. ?>
        </div>

        <?php if ($legend_states) : ?>
            <ul class="lp-us-map__legend">
                <?php foreach ($legend_states as $code => $data) : ?>
                    <li>
                        <a href="<?php echo esc_url($data['url']); ?>"
                            target="<?php echo esc_attr($data['target']); ?>"
                            data-state="<?php echo esc_attr($code); ?>"><?php echo esc_html($state_names[$code] ?? $code); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if ($caption) : ?>
        <div class="lp-us-map__caption"><?php echo wp_kses_post($caption); ?></div>
    <?php endif; ?>

    <?php if ($state_map) : ?>
        <nav class="lp-us-map__state-nav" aria-label="<?php esc_attr_e('Locations by state', 'launchpad'); ?>">
            <ul class="lp-us-map__state-list">
                <?php foreach ($state_map as $code => $data) : ?>
                    <li>
                        <a href="<?php echo esc_url($data['url']); ?>"
                            target="<?php echo esc_attr($data['target']); ?>"><?php echo esc_html($state_names[$code] ?? $code); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>

    <?php lp_buttons($btn, null, 'lp-btn-group--center'); ?>

<?php lp_section_close(); ?>

<?php if (is_admin()) : ?>
<script type="text/javascript">
    document.dispatchEvent(new Event('usMapBlockLoaded'));
</script>
<?php endif; ?>
