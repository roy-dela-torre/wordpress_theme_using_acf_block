<?php
/**
 * Template part for displaying default card content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chusie-kokoro
 */

?>

<article class="ck-template-part-card__card">
	<div class="ck-template-part-card__card-contents">
		<?php if (get_the_title()) : ?>
			<h3 class="ck-template-part-card__card-title"><?php the_title() ?></h3>
		<?php endif; ?>

		<?php if (get_the_excerpt()) : ?>
			<div class="ck-template-part-card__card-body">
				<?php the_excerpt() ?>
			</div>
		<?php endif; ?>
	</div>
</article>