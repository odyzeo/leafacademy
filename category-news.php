<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

<section id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
		<div class="post-thumbnail corner-fx corner-fx-greywhite">
			<img width="1280" height="500" src="http://www.leafacademy.eu/wp-content/uploads/2015/11/Daniel_Dluhy_-_Leaf_internet_0049.jpg">
			<span class="post-thumbnail-caption-wrapper">
				<span class="post-thumbnail-caption-wrapper-tr">
					<span class="post-thumbnail-caption-wrapper-td">
						<strong class="post-thumbnail-caption"><?php echo single_cat_title('', false); ?></strong>
					</span></span></span>    
		</div>

		<div class="entry-content news-items"> 

			<!--
			<?php var_dump($paged); ?>
			-->
			<?php
			// Start the Loop.
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$args = array(
				'posts_per_page' => 10,
				'orderby' => 'date',
				'post_status' => 'publish',
				'post_type' => 'post',
				'order' => 'DESC',
				'category_name' => 'news',
				'paged' => $paged
			);
			
			$pageposts = get_posts($args);
			if ($pageposts):

				global $post;
				foreach ($pageposts as $post):

					setup_postdata($post);
					?>
					<article>
						<div class="news-list-thumb">
							<?php if (has_post_thumbnail()) { ?>
								<a href="<?php the_permalink(); ?>" class="post-smallthumbnail corner-fx corner-fx-greywhite image do-bg-image">
									<?php
									the_post_thumbnail('large');
									//leafacademy_post_thumbnail();
									?>
								</a>
								<?php
							}
							?>  

						</div>
						<div class="news-list-content">
							<h3 class="title green-text"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="post-date"><?php echo get_the_date(get_option('date_format'), $post->ID); ?></div>
							<div class="text formated-output">
								<?php the_excerpt(); ?>
								<?php
								$buttonLink = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_META_KEY, true);
								$buttonLabel = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_LABEL_META_KEY, true);
								$buttonTarget = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_TARGET_META_KEY, true);
								?>
								<?php if ($buttonLink) : ?>
									<p>
										<a class="btn green-grey" <?php if ($buttonTarget) echo 'target="_blank"' ?> href="<?php echo $buttonLink ?>"><?php echo ($buttonLabel ? $buttonLabel : $buttonLink) ?></a>
									</p>
								<?php endif; ?>
							</div>
						</div>
						<div class="less-clearfix"></div>
					</article>
					<?php
				endforeach;
			endif;

			// Previous/next page navigation.
			leafacademy_paging_nav();
			?>

		</div><!-- .category-news -->
	</div><!-- #content -->
</section><!-- #primary -->

<?php
get_footer();
