<?php
/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
?>

	<script type="text/javascript">

		var ytPlayers = [];
		var YouTubeIframeAPIReady = false;
		var domReady = false;

		function initPlayer(videoId, elementId) {

			if (!ytPlayers[elementId]) {

				ytPlayers[elementId] = new YT.Player(elementId, {
					videoId: videoId,
					height: '100%',
					width: '100%',
					playerVars: {
						color: 'white',
						showinfo: 0,
						rel: 0,
						autoplay: true,
						controls: 0,
						wmode: 'transparent'
					},
					events: {
						'onReady': function(event) {
							ytPlayers[elementId].mute();
						},
						'onStateChange': function() {

							var status = ytPlayers[elementId].getPlayerState();

							if (status === YT.PlayerState.ENDED) {
								ytPlayers[elementId].playVideo();
							}
						}

					}

				});

			}

		}

		var tag = document.createElement('script');

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		function onYouTubeIframeAPIReady() {
			YouTubeIframeAPIReady = true;
			<?php
			$mainVideoId = get_text_block_video_id(1670);
			if ($mainVideoId) {
				echo("initPlayer('$mainVideoId','intro-video'); ");
			}
			?>
			if (domReady) {
				initSlideVideos();
			}
		}

		function initSlideVideos() {

			jQuery('.chpcs_video.doInitPlayerLater').each(function() {

				var videoId = jQuery(this).data('video');
				if (videoId) {
					jQuery(this).css({backgroundImage: 'none'});
					initPlayer(videoId, jQuery(this).find('.chpcs_video_iframe').attr('id'));
				}

			});

		}

		jQuery(document).ready(function($) {
			domReady = true;
			if (YouTubeIframeAPIReady) {
				initSlideVideos();
			}
		});
	</script>

	<div id="main-content" class="main-content homepage">

		<?php if (has_post_thumbnail()): ?>
			<div class="entry-content">
				<div class="content-width">
					<?php $imageLink = get_post_meta(get_post_thumbnail_id(get_the_ID()), 'su_slide_link', TRUE); ?>
					<?php leafacademy_post_thumbnail($imageLink); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php

		$legacyMainSliderPosts = get_posts(array(
			'posts_per_page' => 1,
			'post_type' => 'text-blocks',
			'post_status' => 'publish',
			'meta_key' => '_text-blocks_in_main_slider',
			'meta_value' => '1',
			'suppress_filters' => 0
		));

		?>
		<?php if (!empty($legacyMainSliderPosts)): ?>
			<div class="entry-content campaign-intro">
				<div class="content-width">
					<?php
					$post_content = strip_shortcodes($legacyMainSliderPosts[0]->post_content);
					echo wpautop($post_content);
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$introInlineStyle = '';
		if (function_exists('get_text_block_image_url')) {

			if (function_exists('get_text_block_video_id')) {

				$introBackgroundImage = get_text_block_image_url(1670);
				if (!empty($introBackgroundImage)) {
					$introInlineStyle = 'style="background-image: url(' . $introBackgroundImage . ');"';
				}
			}
		}
		?>
		<a name="start"></a>

		<div class="block block-front block-front-news">
			<div class="slider">
				<h2 class="section">News feed</h2>
				<?php echo do_shortcode('[carousel-horizontal-news-content-slider]'); ?>
			</div>
		</div>

		<?php
		$eventsInlineStyle = '';
		if (function_exists('get_text_block_image_url')) {

			$eventsBackgroundImage = get_text_block_image_url(1673);
			$eventsInlineStyle = 'style="background-image: url(' . $eventsBackgroundImage . ');"';
		}
		?>
		<div class="block block-front block-front-events" <?php echo $eventsInlineStyle; ?>>
			<div class="overlay slider">
				<h2 class="section"><?php _e('Events', 'leafacademy'); ?></h2>
				<?php echo do_shortcode('[carousel-horizontal-events-content-slider]'); ?>
			</div>
		</div>

		<!-- .block-front-map -->
		<div class="block block-front-sections do-match-height">
			<article class="item bg-white">
				<div class="section"><?php echo get_the_title(54); ?></div>
				<?php
				if (function_exists('show_text_block')) {
					echo show_text_block(54, FALSE);
				}
				?>

			</article>
			<article class="item bg-green">
				<div class="section"><?php echo get_the_title(56); ?></div>
				<?php
				if (function_exists('show_text_block')) {
					echo show_text_block(56, FALSE);
				}
				?>

			</article>
			<article class="item bg-darkgrey">
				<div class="section"><?php echo get_the_title(58); ?></div>
				<?php
				if (function_exists('show_text_block')) {
					echo show_text_block(58, FALSE);
				}
				?>
			</article>
		</div><!-- .block-front-sections -->

	</div><!-- #main-content -->

<?php
get_footer();
