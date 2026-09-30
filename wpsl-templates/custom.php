<?php 
global $wpsl_settings, $wpsl;

// load attributes


//check if state is pre-filtered
if ( isset($_SESSION['clarvida_wpsl_states'] ) ) {
    $atts_categories = $_SESSION['clarvida_wpsl_states'];
} else {
    $atts_categories = [];
}

$show_filters = false;
if ( isset($_SESSION['clarvida_wpsl_show_filters'])) {
    $show_filters = true;
}

$output         = $this->get_custom_css(); 
$autoload_class = ( !$wpsl_settings['autoload'] ) ? 'class="wpsl-not-loaded"' : '';

$output .= '<div id="wpsl-wrap">' . "\r\n";

if ($show_filters) {
    $output .= "\t" . '<div class="wpsl-search wpsl-clearfix ' . $this->get_css_classes() . '">' . "\r\n";
    $output .= "\t\t" . '<div id="wpsl-search-wrap">' . "\r\n";
    $output .= "\t\t\t" . '<form autocomplete="off">' . "\r\n";
    $output .= "\t\t\t" . '<div class="wpsl-input">' . "\r\n";
    $output .= "\t\t\t\t" . '<div><label for="wpsl-search-input">' . esc_html( $wpsl->i18n->get_translation( 'search_label', __( 'Your location', 'wpsl' ) ) ) . '</label></div>' . "\r\n";
    $output .= "\t\t\t\t" . '<input id="wpsl-search-input" type="text" value="' . apply_filters( 'wpsl_search_input', '' ) . '" name="wpsl-search-input" placeholder="" aria-required="true" />' . "\r\n";
    $output .= "\t\t\t" . '</div>' . "\r\n";

    if ( empty( $atts_categories ) ) {
        if ( $this->use_category_filter() ) {
            $output .= "<div class='filter-group'>";
            $output .= "<p class='filter-heading'>State</p>";
            $output .= $this->create_category_filter();
            $output .= "</div>";
        }
    } 

    $state_slug = "";
    if ( !empty( $atts_categories ) ) {
        $state_slug = get_state_slug_by_term_id($atts_categories[0]);
    }

    //programs filter
    $output .= generate_program_filter_html($state_slug);

    //services filter
    $output .= generate_service_filter_html($state_slug);


    $output .= "\t\t\t\t" . '<div class="wpsl-search-btn-wrap"><input id="wpsl-search-btn" type="submit" value="' . esc_attr( $wpsl->i18n->get_translation( 'search_btn_label', __( 'Search', 'wpsl' ) ) ) . '"></div>' . "\r\n";

    $output .= "\t\t" . '</form>' . "\r\n";
    $output .= "\t\t" . '</div>' . "\r\n";
    $output .= "\t" . '</div>' . "\r\n";
}

$output .= "\t" . '<div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>' . "\r\n";

$output .= "\t" . '<div id="wpsl-result-list">' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-stores" '. $autoload_class .'>' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-direction-details">' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";
$output .= '</div>' . "\r\n";



//clear session variables
unset($_SESSION['clarvida_wpsl_show_filters']);

return $output;