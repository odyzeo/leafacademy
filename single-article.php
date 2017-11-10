<?php
lfa_blog_set_blog_section_as_being_viewed();

add_action("wp_footer", function () {
	?>
	<script>
		LFABlog.toggleAuthorPanel();
	</script>
	<?php
});

get_header();
?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php //while(have_posts()) : the_post();    ?>
		<?php
		$author = new LFA_Author(get_the_author_meta("ID"));
		$categories = lfa_blog_get_post_categories(get_the_ID());
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class("single-article"); ?>>

			<div class="lfa-author-panel">
				<div class="lfa-author-panel-inner">
					<?php if ($author->hasAvatar()) : ?>
						<div class="lfa-author-panel-avatar">
							<div class="lfa-author-panel-avatar-inner">
								<a href="<?php echo esc_attr($author->getUrl()); ?>" class="lfa-author-panel-avatar-image-wrapper">
									<?php echo $author->getAvatar(); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
					<header class="lfa-author-panel-meta">
						<h1 class="lfa-author-panel-name">
							<a href="<?php echo esc_attr($author->getUrl()); ?>" class="lfa-author-panel-name-link">
								<?php echo esc_attr($author->getFullName()); ?>
							</a>
						</h1>
						<?php if (mb_strlen($author->getPosition())): ?>
							<p class="lfa-author-panel-position"><?php echo esc_attr($author->getPosition()); ?></p>
						<?php endif; ?>
					</header>
					<?php if (mb_strlen($author->getBio())): ?>
						<div class="lfa-author-panel-body">
							<p class="lfa-author-panel-bio">
								<?php echo nl2br(esc_attr($author->getBio())); ?>
							</p>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<header class="entry-header">
				<div class="entry-meta">
					<time class="entry-date" datetime="<?php echo get_the_date("r"); ?>"><?php echo get_the_date("j. n. Y"); ?></time>
					<?php if ($categories): ?>
						<span class="entry-meta-divider" role="presentation">|</span>
						<div class="entry-meta-categories">
							<?php
							$i = 0;
							foreach ($categories as $cat) : $i++;
								?>

								<a class="entry-meta-category" href="<?php echo get_term_link($cat); ?>"><?php echo apply_filters("single_cat_title", $cat->name); ?></a><?php if ($i < sizeof($categories)) : ?><span role="presentation" class="entry-meta-category-divider">,</span><?php endif; ?>

							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php the_title('<h1 class="entry-title">', '</h1>'); ?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(array(
					'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'leafacademy') . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
				));
				?>
				<ul class="lfa-single-article-additional-links">
					<li>
						<a href="<?php echo esc_attr($author->getUrl()); ?>" class="btn green-grey"><?php _e('More articles from this author', 'leafacademy'); ?></a>
					</li>
					<?php if ($categories): ?>
						<li>
							<a href="<?php echo get_term_link(reset($categories)); ?>" class="btn green-grey"><?php _e('More articles from this category', 'leafacademy'); ?></a>
						</li>
					<?php endif; ?>
				</ul>
			</div><!-- .entry-content -->

		</article><!-- #post-## -->

		<?php //endwhile;   ?>

	</div><!-- #content -->
</div><!-- #primary -->

<?php
get_footer();
