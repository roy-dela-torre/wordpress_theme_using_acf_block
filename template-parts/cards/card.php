<?php
/**
 * Template part for displaying default card content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package launchpad
 */

?>

<article class="lp-template-part-card__card">
	<div class="lp-template-part-card__card-contents">
		<?php if (get_the_title()) : ?>
			<h3 class="lp-template-part-card__card-title"><?php the_title() ?></h3>
		<?php endif; ?>

		<?php if (get_the_excerpt()) : ?>
			<div class="lp-template-part-card__card-body">
				<?php the_excerpt() ?>
			</div>
		<?php endif; ?>
	</div>
</article>