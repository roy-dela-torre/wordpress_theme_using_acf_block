<?php
/**
 * The template for displaying blog home page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chusie-kokoro
 */

get_header();

$blog_page_id = get_option('page_for_posts');
$blog_title = $blog_page_id ? get_the_title($blog_page_id) : 'Blog';
?>

	<main id="primary" class="site-main">

		<?php
		// Render any block editor content from the Posts page
		if ($blog_page_id) :
			$blog_page = get_post($blog_page_id);
			if ($blog_page && !empty($blog_page->post_content)) : ?>
				<div class="entry-content">
					<?php echo apply_filters('the_content', $blog_page->post_content); ?>
				</div>
			<?php endif;
		endif;
		?>

		<section class="ck-blog no_bg">
			<div class="block-contents">

				<?php if ( have_posts() ) : ?>

					<div class="ck-blog__grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'blog-card' );
						endwhile;
						?>
					</div>

					<?php the_posts_pagination( array(
						'prev_text' => '&laquo; Previous',
						'next_text' => 'Next &raquo;',
						'class'     => 'ck-blog__pagination',
					) ); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>

			</div>
		</section>

	</main>

<?php
get_footer();
