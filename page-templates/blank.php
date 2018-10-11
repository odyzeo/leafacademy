<?php
/**
 * Template Name: Blank page without header/footer
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
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="stylesheet" type="text/css" href="//cloud.typography.com/7330954/710488/css/fonts.css" />
    <!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/dist/html5.min.js"></script>
    <![endif]-->
    <?php wp_head(); ?>

    <!-- social buttons -->
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "0fc5aeb4-121a-4970-848f-0ac5c9132a2c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

</head>
<body <?php body_class(); ?>>

	<?php if (function_exists('gtm4wp_the_gtm_tag')) {
		gtm4wp_the_gtm_tag();
	} ?>

    <div id="page" class="hfeed site">
            <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );

                endwhile;
            ?>

    </div>

	<?php do_action('blank_page_wp_footer'); ?>
</body>
</html>