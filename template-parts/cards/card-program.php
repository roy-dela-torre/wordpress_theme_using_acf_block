<?php
/**
 * Template part for displaying program card content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package launchpad
 */

global $post;
$title = get_field("display_title", $post->ID) ?: get_the_title();

$state = get_state($post);

?>

<article class="lp-template-part-program-card card" data-state="<?= $state ?>">
	<a href="<?= get_the_permalink() ?>" class="lp-template-part-program-card__contents card__contents">
		<?php if ($title) : ?>
			<h3 class="lp-template-part-program-card__title card__title"><?= $title ?></h3>
		<?php endif; ?>

		<?php if (get_the_excerpt()) : ?>
			<div class="lp-template-part-program-card__body card__body">
				<?php the_excerpt() ?>
			</div>
		<?php endif; ?>

		<span aria-hidden="true" class="btn btn-simple lp-template-part-program-card__btn card__btn">
			Learn More <svg width="3" height="6" viewBox="0 0 3 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.394287 0.307495L2.3756 2.84767L0.394287 5.38784"></path></svg>
		</span>
	</a>
</article>