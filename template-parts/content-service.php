<?php
/**
 * Template part for displaying service page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chusie-kokoro
 */

global $post;


$state = get_state($post);

$service_id = get_the_ID();
$locations = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'     => 'wpsl_stores',
	'meta_query'	=> array(
		array(
			'key'       => 'available_services',
			'value'     => $service_id,
			'compare'   => 'LIKE',
		)
	)
));

$location_ids = array();
foreach ($locations as $location ) {
	$location_ids[] = $location->ID;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php chusie_kokoro_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();
		
		if ($location_ids) : ?>
			<h2 class="services-headline">Service Locations</h2>	
			<?php echo get_wpsl_filtered_by_ids($location_ids, $state);
		endif; ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
