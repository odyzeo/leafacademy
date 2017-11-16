<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php leafacademy_post_thumbnail(); ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages(array(
			'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'leafacademy') . '</span>',
			'after' => '</div>',
			'link_before' => '<span>',
			'link_after' => '</span>',
		));
		?>

		<?php edit_post_link(__('Edit', 'leafacademy'), '<span class="edit-link">', '</span>'); ?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
