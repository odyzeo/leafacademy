<?php
/**
 * Custom template tags for Twenty Fourteen
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
if (!function_exists('leafacademy_paging_nav')) :

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
	 */
	function leafacademy_paging_nav($query = NULL) {

		global $wp_query, $wp_rewrite;

		if ($query === NULL) {
			$query = $wp_query;
		}

		// Don't print empty markup if there's only one page.
		if ($query->max_num_pages < 2) {
			return;
		}

		$paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
		$pagenum_link = html_entity_decode(get_pagenum_link());
		$query_args = array();
		$url_parts = explode('?', $pagenum_link);

		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $query_args);
		}

		$pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links(array(
			'base' => $pagenum_link,
			'format' => $format,
			'total' => $query->max_num_pages,
			'current' => $paged,
			'mid_size' => 1,
			'add_args' => array_map('urlencode', $query_args),
			'prev_text' => __('', 'leafacademy'),
			'next_text' => __('', 'leafacademy'),
		));

		if ($links) :
			?>
			<nav class="navigation paging-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php _e('Posts navigation', 'leafacademy'); ?></h1>
				<div class="pagination loop-pagination">
					<?php echo $links; ?>
				</div><!-- .pagination -->
			</nav><!-- .navigation -->
		<?php
		endif;

	}

endif;

if (!function_exists('leafacademy_post_nav')) :

	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @since Twenty Fourteen 1.0
	 */
	function leafacademy_post_nav() {

		// Don't print empty markup if there's nowhere to navigate.
		$previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(FALSE, '', TRUE);
		$next = get_adjacent_post(FALSE, '', FALSE);

		if (!$next && !$previous) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e('Post navigation', 'leafacademy'); ?></h1>

			<?php if (is_single()) : ?>
				<div class="nav-links">
					<div class="leaf-nav-post">
						<div class="leaf-nav-post-inner">
							<?php
							if (is_attachment()) :
								previous_post_link('%link', __('<span class="meta-nav">Published In</span>%title', 'leafacademy'));
							else :
								previous_post_link('%link', __('<span class="meta-nav"><i>&#8249;</i> Previous Post</span>', 'leafacademy'));
								next_post_link('%link', __('<span class="meta-nav">Next Post <i>&#8250;</i></span>', 'leafacademy'));
							endif;
							?>
						</div>
					</div>
				</div><!-- .nav-links -->
			<?php endif; ?>
		</nav><!-- .navigation -->
		<?php

	}

endif;

if (!function_exists('leafacademy_posted_on')) :

	/**
	 * Print HTML with meta information for the current post-date/time and author.
	 *
	 * @since Twenty Fourteen 1.0
	 */
	function leafacademy_posted_on() {

		if (is_sticky() && is_home() && !is_paged()) {
			echo '<span class="featured-post">' . __('Sticky', 'leafacademy') . '</span>';
		}

		// Set up and print post meta information.
		printf('<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>', esc_url(get_permalink()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()
		);

	}

endif;

/**
 * Find out if blog has more than one category.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return boolean true if blog has more than 1 category
 */
function leafacademy_categorized_blog() {

	if (FALSE === ($all_the_cool_cats = get_transient('leafacademy_category_count'))) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories(array(
			'hide_empty' => 1,
		));

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count($all_the_cool_cats);

		set_transient('leafacademy_category_count', $all_the_cool_cats);
	}

	if (1 !== (int)$all_the_cool_cats) {
		// This blog has more than 1 category so leafacademy_categorized_blog should return true
		return TRUE;
	} else {
		// This blog has only 1 category so leafacademy_categorized_blog should return false
		return FALSE;
	}

}

/**
 * Flush out the transients used in leafacademy_categorized_blog.
 *
 * @since Twenty Fourteen 1.0
 */
function leafacademy_category_transient_flusher() {

	// Like, beat it. Dig?
	delete_transient('leafacademy_category_count');

}

add_action('edit_category', 'leafacademy_category_transient_flusher');
add_action('save_post', 'leafacademy_category_transient_flusher');

function leafacademy_the_post_thumbnail_caption($caption = NULL) {

	global $post;

	$thumbnail_id = get_post_thumbnail_id($post->ID);
	$thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

	if ($thumbnail_image && isset($thumbnail_image[0])) {

		if ($caption === NULL) {
			$caption = $thumbnail_image[0]->post_excerpt;
		}
		$description = $thumbnail_image[0]->post_content;

		echo '<span class="post-thumbnail-caption-wrapper"><span class="post-thumbnail-caption-wrapper-tr"><span class="post-thumbnail-caption-wrapper-td">';
		echo '<strong class="post-thumbnail-caption"><h1>' . esc_html($caption) . '</h1></strong>';
		if ($description) {
			echo '<span class="post-thumbnail-description">' . $description . '</span>';
		}
		echo '</span></span></span>';
	}

}

add_filter('the_post_thumbnail_caption', 'leafacademy_the_post_thumbnail_caption', 10, 1);

if (!function_exists('leafacademy_post_thumbnail')) :

	/**
	 * Display an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index
	 * views, or a div element when on single views.
	 *
	 * @since Twenty Fourteen 1.0
	 * @since Twenty Fourteen 1.4 Was made 'pluggable', or overridable.
	 */
	function leafacademy_post_thumbnail($targetUrl = '') {

		if (post_password_required() || is_attachment()) {
			return;
		}

		$mobileFeaturedImageId = get_field('mobile_featured_image');
		$mobileFeaturedImageAvailable = is_numeric($mobileFeaturedImageId);

		$thumbnailWrapperExtraCssClass = $mobileFeaturedImageAvailable ? 'hide-on-mobile' : '';
		if (is_singular() && empty($targetUrl)): ?>

			<?php if (has_post_thumbnail()): ?>
				<div class="post-thumbnail corner-fx corner-fx-greywhite <?php echo $thumbnailWrapperExtraCssClass; ?>">
					<?php
					if ((!is_active_sidebar('sidebar-2') || is_page_template('page-templates/full-width.php'))) {

						the_post_thumbnail('leafacademy-full-width');
						the_post_thumbnail_caption();
					} else {

						the_post_thumbnail();
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ($mobileFeaturedImageAvailable): ?>

				<div class="post-thumbnail-mobile">
					<?php $mobileFeaturedImageSource = wp_get_attachment_image_src($mobileFeaturedImageId, 'full'); ?>
					<img src="<?php echo $mobileFeaturedImageSource[0]; ?>"/>
				</div>

			<?php endif; ?>

		<?php else: ?>

			<?php $hyperlink = empty($targetUrl) ? get_permalink() : $targetUrl; ?>
			<?php if (has_post_thumbnail()): ?>

				<a class="post-thumbnail corner-fx corner-fx-greywhite <?php echo $thumbnailWrapperExtraCssClass; ?>" href="<?php echo $hyperlink; ?>" aria-hidden="true">
					<?php
					if ((!is_active_sidebar('sidebar-2') || is_page_template('page-templates/full-width.php'))) {
						the_post_thumbnail('leafacademy-full-width');
						the_post_thumbnail_caption();
					} else {
						the_post_thumbnail('leafacademy-full-width', array('alt' => get_the_title()));
					}
					?>
				</a>
			<?php endif; ?>

			<?php if ($mobileFeaturedImageAvailable): ?>

				<a class="post-thumbnail-mobile" href="<?php echo $hyperlink; ?>">
					<?php $mobileFeaturedImageSource = wp_get_attachment_image_src($mobileFeaturedImageId, 'full'); ?>
					<img src="<?php echo $mobileFeaturedImageSource[0]; ?>"/>
				</a>

			<?php endif; ?>

		<?php
		endif; // End is_singular()

	}

endif;

function leafacademy_inline_featured_image($attachmentId) {

	$thumbnail_image = get_posts(array('p' => $attachmentId, 'post_type' => 'attachment'));

	// return print_r($thumbnail_image, true)."****".$thumbnail_id; 

	if ($thumbnail_image && isset($thumbnail_image[0])) {
		$caption = $thumbnail_image[0]->post_excerpt;
		$description = $thumbnail_image[0]->post_content;

		$res = '<div class="post-thumbnail post-thumbnail-inline corner-fx corner-fx-greywhite">';
		$res .= wp_get_attachment_image($attachmentId, 'leafacademy-full-width', FALSE, NULL);
		$res .= '<span class="post-thumbnail-caption-wrapper"><span class="post-thumbnail-caption-wrapper-tr"><span class="post-thumbnail-caption-wrapper-td">';
		$res .= '<strong class="post-thumbnail-caption">' . $caption . '</strong>';
		if ($description)
			$res .= '<span class="post-thumbnail-description">' . $description . '</span>';
		$res .= '</span></span></span></div>';
	}
	return $res;

}

if (!function_exists('leafacademy_excerpt_more') && !is_admin()) :

	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ...
	 * and a Continue reading link.
	 *
	 * @since Twenty Fourteen 1.3
	 *
	 * @param string $more Default Read More excerpt link.
	 *
	 * @return string Filtered Read More excerpt link.
	 */
	function leafacademy_excerpt_more($more) {

		$link = sprintf('<a href="%1$s" class="more-link">%2$s</a>', esc_url(get_permalink(get_the_ID())),
			/* translators: %s: Name of current post */
			sprintf(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'leafacademy'), '<span class="screen-reader-text">' . get_the_title(get_the_ID()) . '</span>')
		);
		return ' &hellip; ' . $link;

	}

	add_filter('excerpt_more', 'leafacademy_excerpt_more');
endif;
