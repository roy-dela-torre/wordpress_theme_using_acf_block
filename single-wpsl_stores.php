<?php
/**
* The template for a single WPSL store
*
* @package HelloElementor
*/
get_header(); 

global $post;

$address         = get_post_meta( 'wpsl_address', true );
$address2        = get_post_meta( 'wpsl_address2', true );
$city            = get_post_meta( 'wpsl_city', true );
$state           = get_post_meta( 'wpsl_state', true );
$zip             = get_post_meta( 'wpsl_zip', true );
$counties_served = get_post_meta( 'wpsl_counties_served', true );

$phones             = get_field('phone_numbers');
$gmb_url 			= get_field('gmb_url');

//$career_url         = get_field('career_url', $queried_object->ID);
$ctas               = get_field('ctas');
$available_programs = get_field('available_programs');
$available_services = get_field('available_services');
$testimonials       = get_field('testimonials');

$terms = get_the_terms( $post, 'wpsl_store_category' );
$state_slug = '';

if ( $terms ) {
	$state_slug = $terms[0]->slug;
}

$service_information = get_field('service_information');
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
			<div class="content">
				<h1 class="entry-title"><?php single_post_title(); ?></h1>
				<address>
					<?php if (!empty($address)) { ?>
						<?php if ( $gmb_url ) echo '<a href="' .  $gmb_url . '" target="_blank">'; ?>
						<span class="address"><?php echo $address; ?></span>
						<?php if ( $address2 ) : echo '<span class="address2">' . $address2 . '</span>'; endif; ?>
						<span class="city-state-zip">
							<?php echo $city . ', ' . $state . ' ' . $zip; ?>
						</span>
						<?php if ( $gmb_url ) echo '</a>'; ?>
					<?php } ?>
				</address>
				<?php if ( $counties_served ) : ?>
					<p class="counties-served"><strong>Counties Served: </strong><?php echo $counties_served; ?></p>
				<?php endif; ?>
				<hr />
				<?php if ( $phones ) : ?>
					<?php echo '<ul class="phones">'; ?>
					<?php foreach( $phones as $phone ) :
						$isFax = stripos($phone['phone_name'], 'fax') !== false; ?>
					<?php echo '<li>'; ?>
					<?php if ( $phone['phone_name'] ) : 
						echo '<strong>' . $phone['phone_name'] . ': </strong> '; 
					endif; ?>
					<?php if ( $phone['phone_number'] ) :
						if (!$isFax && strpos($phone['phone_number'],'+') === FALSE) {
							$cleanNumber = preg_replace('/[^0-9]/', '', $phone['phone_number'] ?? '');
							$formattedNumber = preg_match('/^1/', $cleanNumber) ? '+' . $cleanNumber : '+1' . $cleanNumber;
							echo '<a href="tel:' . $formattedNumber . '">' . $phone['phone_number'] . '</a>';
					 	} else {
							echo $phone['phone_number'];
						}
					endif; ?>
					<?php echo '</li>'; ?>
					<?php endforeach; ?>
					<?php echo '</ul>'; ?>
				<?php endif; ?>

				<?php if ( $ctas ) : ?>
					<div class="cta-wrapper">
					<?php foreach( $ctas as $cta ) : ?>
						<a class="cta" href="<?php echo esc_attr( $cta['cta_url'] ); ?>" target="_blank">
							<?php echo esc_html( $cta['cta_label'] ); ?>
							<svg aria-hidden="true" viewBox="0 0 256 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>
						</a>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="map">
				<?php echo do_shortcode( '[wpsl_map id="' . $post->ID . '"]' ); ?>
			</div>
		</header>
		<div class="entry-content">
			<?php if ($service_information) {
				echo '<div class="service-info" style="margin-bottom: 80px !important;">';
				echo $service_information; 
				echo '</div>';
			} ?>
			
			
			
			<?php if ( $available_programs ) : ?>
				<h2 class="programs-headline">Programs Available</h2>

				<div class="programs-grid">
				<?php foreach( $available_programs as $available_program ) : 
					$post = $available_program;
					setup_postdata($available_program);
					get_template_part( 'template-parts/cards/card', get_post_type() );
					wp_reset_postdata();
				endforeach; ?>
				</div>
			<?php endif; ?>


			<?php if ( $available_services ) : ?>
				<h2 class="services-headline">Services Available</h2>

				<div class="services-grid">
				<?php foreach( $available_services as $available_service ) : 
					$post = $available_service;
					setup_postdata($available_service);
					get_template_part( 'template-parts/cards/card', get_post_type() );
					wp_reset_postdata();
				endforeach; ?>
				</div>
			<?php endif; ?>


			</div>
		</article>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
