<?php
session_start();

// Add Custom Fields
//add_filter( 'wpsl_meta_box_fields', 'custom_meta_box_fields' );

function custom_meta_box_fields( $meta_fields ) {

  $meta_fields[__( 'Additional Information', 'wpsl' )] = array(
    'counties_served' => array(
        'label' => __( 'Counties Served (Separate with commas)', 'wpsl' )
    )
  );

 return $meta_fields;
}

// Include the Custom Data In the JSON Response
add_filter( 'wpsl_frontend_meta_fields', 'custom_frontend_meta_fields' );

function custom_frontend_meta_fields( $store_fields ) {
  $store_fields['wpsl_counties_served'] = array(
    'name' => 'counties_served',
    'type' => 'text'
  );

  return $store_fields;
}

// Custom Store Meta
add_filter( 'wpsl_store_meta', 'custom_store_meta', 10, 2 );

function custom_store_meta($store_meta, $store_id) {
  global $wpsl_settings;

// Build Phones
$phones = get_field('phone_numbers', $store_id);
$phone_output = '';
$i = 0;

if ($phones) {
    foreach ($phones as $phone) {
        $phone_output .= '<p>';
        if (!empty($phone['phone_name'])) {
            $phone_output .= '<strong>' . esc_html($phone['phone_name']) . ': </strong><br />';
        }
        if (!empty($phone['phone_number'])) {
            $phone_output .= esc_html($phone['phone_number']);
        }
        $phone_output .= '</p>';

        $i++;
        if ($i === 3) break; // Stop after 3 phones
    }
}

$store_meta['phones'] = $phone_output;

  // category
  $terms = get_the_terms( $store_id, 'wpsl_store_category' );
  $store_meta['link'] = '';
  $location_slug = $wpsl_settings['permalink_slug'];
  $slug = get_post_field( 'post_name', $store_id );
  $store_meta['cat'] = '';

  if ( $terms ) {
		if ( ! is_wp_error( $terms ) ) {
			$store_meta['cta'] = '/' . $terms[0]->slug . '/' . $location_slug . '/' . $slug;
      $store_meta['cat'] = $terms[0]->term_id;
		} else {
      $store_meta['cta'] = '/' . $location_slug . '/' . $slug;
    }
	} else {
    $store_meta['cta'] = '/' . $location_slug . '/' . $slug;
  }

  // Build Services
  $available_programs = get_field('available_programs', $store_id);
  $count = isset( $available_programs ) && is_array( $available_programs ) ? count( $available_programs ) : 0;
  $service_list = '';
  $j = 0;

	if ( $available_programs ) {
    $service_list .= '<ul>';
    foreach( $available_programs as $available_program ) {
      $title = get_the_title( $available_program->ID );
      $display_title = get_field('display_title', $available_program->ID);

      if ( empty( $display_title ) ) {
        $service_list .= '<li>' . $title . '</li>';
      } else {
        $service_list .= '<li>' . $display_title . '</li>';
      }

      $j++;
      if( $j === 5) break;
    }

    if ( $count > 5 ) {
      $service_list .= '<li><a href="' . $store_meta['cta'] . '">More Programs Available</a></li>';
    }

    $service_list .= '</ul>';
  }

  $store_meta['service_list'] = $service_list;

  $gmb_url = get_field('gmb_url', $store_id);
  $store_meta['gmb_url'] = $gmb_url;

  
	return $store_meta;
}

// Custom Listing
add_filter( 'wpsl_listing_template', 'custom_listing_template' );

function custom_listing_template() {

  global $wpsl, $wpsl_settings;

  $listing_template  = '<li data-store-id="<%= id %>">';
  $listing_template .= '<div class="wpsl-listing-content">';
  $listing_template .= '<h3 class="wpsl-listing-title"><%= store %></h3>';
  $listing_template .= '<a href="<%= gmb_url %>" class="wpsl-gmb-link" target="_blank">';
  $listing_template .= '<address class="wpsl-listing-address">';
  $listing_template .= '<span class="wpsl-listing-street"><%= address %></span>';
  $listing_template .= '<% if ( address2 ) { %>';
  $listing_template .= '<span class="wpsl-listing-street2"><%= address2 %></span>';
  $listing_template .= '<% } %>';
  $listing_template .= '<span class="wpsl-listing-city-state-zip">' . wpsl_address_format_placeholders() . '</span>';
  $listing_template .= '</address>';
  $listing_template .= '</a>';
  $listing_template .= '<hr />';
  $listing_template .= '<% if ( phones ) { %>';
  $listing_template .= '<%= phones %>';
  $listing_template .= '<% } %>';
  //$listing_template .= '<% if ( service_list ) { %>';
  //$listing_template .= '<p class="wpsl-listing-services"><strong>Programs Available:</strong></p>';
  //$listing_template .= '<%= service_list %>';
  //$listing_template .= '<% } %>';
  //$listing_template .= '<a href="<%= permalink %>" class="wpsl-listing-cta">' . esc_html( 'See All Location Details' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="7" height="20" viewBox="0 0 7 20" fill="none"><path d="M1.5 6.58496L5.5 10.585L1.5 14.585" stroke="#107093" stroke-width="1.5"/></svg></a>';
  $listing_template .= '<a href="<%= permalink %>" class="wpsl-listing-cta">' . esc_html( 'See All Location Details' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="7" height="20" viewBox="0 0 7 20" fill="none"><path d="M1.5 6.58496L5.5 10.585L1.5 14.585" stroke="#107093" stroke-width="1.5"/></svg></a>';
  $listing_template .= '</div>';
  $listing_template .= '</li>';

  return $listing_template;
}

// Custom Info Window
add_filter( 'wpsl_info_window_template', 'custom_info_window_template' );

function custom_info_window_template() {

  global $wpsl_settings, $wpsl;

  $info_window_template  = '<div data-store-id="<%= id %>" class="wpsl-info-window">';
  $info_window_template .= '<div class="wpsl-infowindow-title"><%= store %></div>';
  $info_window_template .= '<% if ( address ) { %>';
  $info_window_template .= '<div class="wpsl-infowindow-address"><%= address %></div>';
  $info_window_template .= '<% } %>';
  $info_window_template .= '<% if ( address2 ) { %>';
  $info_window_template .= '<div class="wpsl-infowindow-address2"><%= address2 %></div>';
  $info_window_template .= '<% } %>';
  $info_window_template .= '<div class="wpsl-infowindow-area">' . wpsl_address_format_placeholders() . '</div>';
  $info_window_template .= '<hr />';
  $info_window_template .= '<% if ( phones ) { %>';
  $info_window_template .= '<%= phones %>';
  $info_window_template .= '<% } %>';
  //$info_window_template .= '<% if ( service_list ) { %>';
  //$info_window_template .= '<p class="wpsl-infowindow-services"><strong>Services Available:</strong></p>';
  //$info_window_template .= '<%= service_list %>';
  //$info_window_template .= '<% } %>';
  //$info_window_template .= '<a href="<%= permalink %>" class="wpsl-infowindow-cta">' . esc_html( 'See All Location Details' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="7" height="20" viewBox="0 0 7 20" fill="none"><path d="M1.5 6.58496L5.5 10.585L1.5 14.585" stroke="#107093" stroke-width="1.5"/></svg></a>';
  $info_window_template .= '<a href="<%= permalink %>" class="wpsl-infowindow-cta">' . esc_html( 'See All Location Details' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="7" height="20" viewBox="0 0 7 20" fill="none"><path d="M1.5 6.58496L5.5 10.585L1.5 14.585" stroke="#107093" stroke-width="1.5"/></svg></a>';
  //$info_window_template .= '<%= createInfoWindowActions( id ) %>';
  $info_window_template .= '</div>';

  return $info_window_template;
}

/* Chnage Marker Folder URI */
define( 'WPSL_MARKER_URI', dirname( get_bloginfo( 'stylesheet_url') ) . '/wpsl-markers/' );

add_filter( 'wpsl_admin_marker_dir', 'custom_admin_marker_dir' );

function custom_admin_marker_dir() {

    $admin_marker_dir = get_stylesheet_directory() . '/wpsl-markers/';
    
    return $admin_marker_dir;
}

/* Hide the Start Marker */
add_filter( 'wpsl_js_settings', 'custom_js_settings' );

function custom_js_settings( $settings ) {

    $settings['startMarker'] = '';

    return $settings;
}

// Get Zip and State value and Pass PHP to JavaScript
function wpsl_php_to_js_enqueue() {
    $data_to_pass = array();

    $terms = get_terms( 'wpsl_store_category' );

    if ( $terms && !is_wp_error($terms) ) {
        foreach ( $terms as $term ) {
            $args = array( 
              'post_type' => 'wpsl_stores', 
              'post_status' => 'publish', 
              'numberposts' => 1,
              'fields' => 'ids',
              'tax_query' => array(
                  array(
                    'taxonomy' => 'wpsl_store_category',
                    'field' => 'term_id', 
                    'terms' => $term->term_id,
                    'include_children' => false
                  )
              )
          );
        
          $posts = get_posts($args);
          $post_id = $posts[0];

          $zip = get_post_meta( $post_id, 'wpsl_zip' );

          if (sizeof($zip) < 1) {
            array_push($zip, '');
          }

          $data_to_pass[] = array(
              'id' => $term->term_id,
              'zip' => $zip[0],
              'state' => $term->name,
          );
        }
    }

    wp_enqueue_script( 'php-to-js', get_stylesheet_directory_uri() . '/js/scripts.js', array(), null, true );
    wp_localize_script( 'php-to-js', 'php_vars', $data_to_pass );
}
add_action( 'wp_enqueue_scripts', 'wpsl_php_to_js_enqueue' );

// Load a Custom Store Locator Template
add_filter( 'wpsl_templates', 'custom_templates' );

function custom_templates( $templates ) {
    /**
     * The 'id' is for internal use and must be unique ( since 2.0 ).
     * The 'name' is used in the template dropdown on the settings page.
     * The 'path' points to the location of the custom template,
     * in this case the folder of your active theme.
     */
    $templates[] = array (
        'id'   => 'custom',
        'name' => 'Custom template',
        'path' => get_stylesheet_directory() . '/' . 'wpsl-templates/custom.php',
    );

    return $templates;
}

// Modify the search results data just before they are send to the store locator.
add_filter( 'wpsl_store_data', 'custom_store_data' );

function custom_store_data( $store_data ) {

  // Sort the results alphabetically by store name
  $custom_sort = array();
  
  foreach ( $store_data as $key => $row ) {
      $custom_sort[$key] = $row['store'];
  }

  array_multisort( $custom_sort, SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $store_data );

  // Remove store items if it is not in state attributes
  if ( isset( $_SESSION['clarvida_wpsl_states'] ) ) {
    $atts_categories = $_SESSION['clarvida_wpsl_states'];

    foreach ( $store_data as $key => $row ) {
      if ( !in_array( $row['cat'], $atts_categories ) ) {
        unset( $store_data[$key] );
      }
    }

    $store_data = array_values($store_data);
    unset($_SESSION['clarvida_wpsl_states']);
  }
  // Remove store items if it is not in id attributes
  if ( isset( $_SESSION['clarvida_wpsl_ids'] ) ) {
    $atts_ids = $_SESSION['clarvida_wpsl_ids'];

    foreach ( $store_data as $key => $row ) {
      if ( !in_array( $row['id'], $atts_ids ) ) {
        unset( $store_data[$key] );
      }
    }

    $store_data = array_values($store_data);
    unset($_SESSION['clarvida_wpsl_ids']);
  }

  // services and programs filters
  if (isset($_GET['programs']) || isset($_GET['services'])) {
		if ($_GET['programs'] || $_GET['services']) {

      //grab program and service IDs from $_GET, assigning an empty array if there are none
      if (isset($_GET['programs'])) {
			  $filter_programs = ($_GET['programs'] !== '') ? explode(",", $_GET['programs']) : array();
      } else {
        $filter_programs = array();
      }

      if (isset($_GET['services'])) {
			  $filter_services = ($_GET['services'] !== '') ? explode(",", $_GET['services']) : array();
      } else {
        $filter_services = array();
      }

			foreach ($store_data as $key => $data) {
        
				$store_id = $data['id'];

        //grab list of programs and services from CPT
				$programs = get_post_meta($store_id, 'available_programs', true);
				$services = get_post_meta($store_id, 'available_services', true);
        
        //make sure we have arrays and not strings
        $programs = ($programs === "") ? array() : $programs;
        $services = ($services === "") ? array() : $services;

				$has_programs = empty(array_diff($filter_programs, $programs));	
				$has_services = empty(array_diff($filter_services, $services));	

				if (!($has_programs && $has_services)) {
					unset($store_data[$key]);
				}
				
			}

      $store_data = array_values($store_data);

		}
	}

  return $store_data;
}

// Create Category Filter by Category IDs
function create_category_filter_by_cat_ids( $cat_ids ) {

  global $wpsl, $wpsl_settings;
  
  $terms = get_terms( array(
    'taxonomy'   => 'wpsl_store_category',
    'include'    => $cat_ids,
    'hide_empty' => true,
  ) );

  if ( count( $terms ) > 0 ) {
    $category  = '<div id="wpsl-category">';
    $category .= '<label for="wpsl-category-list">' . esc_html( $wpsl->i18n->get_translation( 'category_label', __( 'Category', 'wpsl' ) ) ) . '</label>';
    $category .= '<div class="wpsl-dropdown">';
    $category .= '<select name="wpsl-category" id="wpsl-category-list">';
    $category .= '<option value="0">Any</option>';
    foreach ( $terms as $term ) {
      $category .= '<option class="level-0" value="' . esc_attr( $term->term_id ) . '">'  . esc_html( $term->name ) . '</option>';
    }
    $category .= '</select>';
    $category .= '</div>';
    $category .= '</div>';

    return $category;
  }
}

