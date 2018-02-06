<?php
/**
 * Template Name: Application
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width">
		<title><?php wp_title('|', TRUE, 'right'); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<link rel="stylesheet" type="text/css" href="//cloud.typography.com/7330954/710488/css/fonts.css"/>
		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
		<![endif]-->
		<?php wp_head(); ?>

		<!-- social buttons -->
		<script type="text/javascript">var switchTo5x = true;</script>
		<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
		<script type="text/javascript">stLight.options({
				publisher: "0fc5aeb4-121a-4970-848f-0ac5c9132a2c",
				doNotHash: false,
				doNotCopy: false,
				hashAddressBar: false
			});</script>

	</head>
	<body <?php body_class(); ?>>

		<?php if (function_exists('gtm4wp_the_gtm_tag')) {
			gtm4wp_the_gtm_tag();
		} ?>

		<div id="page" class="hfeed site">

			<div id="page" class="hfeed site">
				<?php while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="entry-content">

							<p>

								<a name="form802730794" id="formAnchor802730794"></a>
								<script type="text/javascript" src="https://fs18.formsite.com/include/form/embedManager.js?802730794"></script>
								<script type="text/javascript">
									EmbedManager.embed({
										key: "https://fs18.formsite.com/res/showFormEmbed?EParam=B6fiTn%2BRcO70RTMd3SAp3H5%2FpBxO5FChFzpUCZwnDno%3D&802730794",
										width: "100%",
										showFormLogin: true,
										mobileResponsive: true
									});
								</script>

							</p>

							<p>
								<?php edit_post_link(__('Edit', 'leafacademy'), '<span class="edit-link">', '</span>'); ?>
							</p>

						</div><!-- .entry-content -->

					</article><!-- #post-## -->
				<?php endwhile; ?>

			</div>

		</div>

		<?php do_action('blank_page_wp_footer'); ?>
	</body>
</html>