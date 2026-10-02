<?php
/**
 * Template part for displaying post card content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package chusie-kokoro
 */

global $post;

?>

<article class="ck-template-part-post-card card" >
	<a href="<?= get_the_permalink() ?>">
		<?php if (get_the_post_thumbnail()) : ?>
			<div class="ck-template-part-post-card__card-img-wrapper card__img-wrapper">
				<?php echo get_the_post_thumbnail($post, 'medium_large', ['class' => 'ck-template-part-post-card__card-img']); ?>
			</div>
		<?php endif; ?>

		<div class="ck-template-part-post-card__contents card__contents">

			<?php if (get_the_title()) : ?>
				<h3 class="ck-template-part-post-card__title card__title"><?= get_the_title() ?></h3>
			<?php endif; ?>

			<span aria-hidden="true" class="btn btn-simple ck-template-part-post-card__btn card__btn">
				Learn More <svg width="3" height="6" viewBox="0 0 3 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.394287 0.307495L2.3756 2.84767L0.394287 5.38784"></path></svg>
			</span>
		</div>
	</a>
</article>