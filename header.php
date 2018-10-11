<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
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
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/gotham-rnd/408453/B2F6C17C212922BE4.css"/>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/dist/html5.min.js"></script>
	<![endif]-->

	<!-- Google Tag Manager -->
	<script>(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start':
						new Date().getTime(), event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-TZ5SRR4');</script>
	<!-- End Google Tag Manager -->

	<?php wp_head(); ?>
    
    <!-- social buttons -->
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "0fc5aeb4-121a-4970-848f-0ac5c9132a2c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
    
    
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

    <header id="header">
        <div class="top">

		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TZ5SRR4" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->

	        <?php
	        $hideSocialBarOnMobile = get_field('hide_header_social_bar_on_mobile');
	        $topNavExtraCssClasses = $hideSocialBarOnMobile ? 'hide-on-mobile' : '';
	        ?>
	        <div class="top-nav <?php echo $topNavExtraCssClasses; ?>">
			<?php wp_nav_menu(array('container' => FALSE, 'theme_location' => 'secondary_top', 'menu_class' => 'menu', 'menu_id' => 'secondary-top-menu', 'depth' => 1)); ?>
                <div class="search">
                    <?php get_search_form(); ?>
                </div>
			<div class="language-switcher-wrap">
				<div class="language-switcher">
					<?php if (function_exists('icl_get_languages')): ?>
						<?php $languages = icl_get_languages('skip_missing=0&orderby=name&order=asc&link_empty_to=' . home_url('/{%lang}/')); ?>
						<?php foreach ($languages as $language): ?>
							<?php if ($language['active'] == 1): ?>
								<a href="<?php echo $language['url']; ?>" data-lang="<?php echo $language['language_code']; ?>" class="language-switcher-trigger"><img class="lang-icon" src="<?php echo $language['country_flag_url']; ?>" /><?php echo $language['language_code']; ?></a>
							<?php endif; ?>
						<?php endforeach; ?>
						<ul>
							<?php foreach ($languages as $language): ?>
								<?php if ($language['active'] == 0): ?>
									<li>
										<a href="<?php echo $language['url']; ?>"><span><img class="lang-icon" src="<?php echo $language['country_flag_url']; ?>" /><?php echo $language['language_code']; ?></span></a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						<a href="/" data-lang="EN" class="language-switcher-trigger">EN</a>
						<ul>
							<li><a href="http://academy.leaf.sk/"><span>SK</span></a></li>
							<li><a href="http://academy.leaf.sk/cz"><span>CZ</span></a></li>
							<li><a href="http://academy.leaf.sk/de"><span>AT</span></a></li>
							<li><a href="http://academy.leaf.sk/hu"><span>DE</span></a></li>
							<li><a href="http://academy.leaf.sk/pl"><span>PL</span></a></li>

						</ul>
					<?php endif; ?>
					<span aria-hidden="true" class="stretchy-nav-bg"></span>
				</div>
                </div>
                <div class="top-social top-social-alt">
                   <?php echo DISPLAY_ULTIMATE_PLUS(); ?>
                </div>
            </div>
            
            <div class="top-social top-social-main">
                <?php echo DISPLAY_ULTIMATE_PLUS(); ?>
            </div>
            
            <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <span class="image">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0" y="0" width="42" height="42" viewBox="0 0 42 42" enable-background="new 0 0 42 42" xml:space="preserve"><path fill="#00B24C" d="M27.1 20.3c2.2-0.5 4.2-1.1 6.2-1.8 1-0.4 2.1-0.8 3.2-1.2 1.1-0.5 2.2-1 3.3-1.5 0.4-0.2 0.7-0.4 1.1-0.6 -0.2-0.6-0.4-1.2-0.6-1.7 -0.6-1.7-1.5-3.1-2.4-4.5 -0.4-0.6-0.8-1.1-1.2-1.6 -11.1 9.6-14.5 12.5-14.5 12.5 0.3-1 0.6-1.6 1.6-3.2 1.8-3.2 4.1-6.1 6.7-8.6 1.5-1.4 2.9-2.6 3.8-3.2 -0.3-0.3-0.7-0.5-1-0.8 -3.1 2.4-4 3.4-6.5 6.2 -1 1.2-2 2.5-2.8 3.8 -0.5 0.7-0.9 1.4-1.3 2.2h0c0.2-1.3 0.6-2.7 1-3.9 0.7-2.3 1.6-4.7 2.5-6.5 0.5-1 1.1-2 1.6-2.9 0.2-0.3 0.7-1.1 0.9-1.4 -0.4-0.1-0.8-0.3-1.2-0.4 -0.6 1-0.9 1.4-1.9 3.1 -0.6 1.2-1.2 2.4-1.8 3.7 -0.4 1-0.8 2-1.2 3.1 -0.2 0.7-0.4 1.4-0.6 2.1 -0.1 0.4-0.2 0.8-0.3 1.2 0-4.7 0-9.5 0-14.2 -0.5 0-0.7 0-1.3 0 0 4.7 0 9.5 0 14.2 -0.1-0.4-0.2-0.8-0.3-1.2 -0.3-1.1-0.6-2.2-1-3.3 -0.9-2.4-1.9-4.7-3.1-6.7 -0.4-0.7-0.8-1.3-1.2-2 -0.4 0.1-0.8 0.3-1.2 0.4 0.4 0.6 1.5 2.4 2.4 4.1 1.2 2.3 2.2 4.9 2.9 7.6 0.4 1.6 0.6 2.4 0.7 3.1 -0.6-1-1.2-2-1.9-3 -1.7-2.4-3.6-4.6-5.6-6.6 -1.4-1.4-2.7-2.3-3.2-2.7 -0.6 0.5-0.7 0.5-1 0.8 1.1 0.8 2.3 1.8 3.7 3.1 0.9 0.8 3.5 3.6 5.7 6.9 0.4 0.6 0.8 1.2 1.1 1.7 1 1.6 1.1 2.2 1.5 3.2 -5-4.3-9.5-8.2-14.5-12.5 -2.1 2.5-3.4 5-4.3 7.8 0.7 0.4 1.4 0.7 2.2 1.1 1.9 0.9 4.2 1.8 4.7 2 2.1 0.8 4.5 1.5 6.9 2 1.9 0.4 3.6 0.7 5.5 0.8 0.6 0 1.5 0 2.1 0C24.1 20.8 25.6 20.6 27.1 20.3zM41.5 17.7c-0.1-0.4-0.1-0.8-0.2-1.3 -0.3 0.1-0.5 0.3-0.8 0.4 -0.9 0.5-1.9 0.9-2.8 1.3 -2.9 1.3-6 2.4-9.4 3.2 -1.1 0.3-2.3 0.5-3.4 0.6 -0.6 0.1-1.2 0.1-1.7 0.2 0.3 0.1 0.5 0.2 0.8 0.3 0.7 0.3 1.5 0.5 2.3 0.7 2.2 0.6 4.7 1 7.1 1.3 1.1 0.1 2.2 0.2 3.2 0.3 0.8 0.1 1.6 0.1 2.5 0.1 0.8 0 1.6 0.1 2.5 0.1 0.2-1 0.3-2.3 0.4-3.5C41.8 20.2 41.7 18.7 41.5 17.7zM3.8 24.9c0.5 0 1-0.1 1.5-0.1 0.3 0 0.6 0 0.8-0.1 0.6-0.1 1.2-0.1 1.8-0.2 2.7-0.3 5.9-0.7 7.9-1.3 2.1-0.6 2.7-0.9 3.1-1.1 -3-0.2-6.7-1.1-9.7-2.1 -0.9-0.3-1.7-0.6-2.5-0.9 -1-0.4-2-0.8-2.9-1.2 -1-0.5-1.9-0.9-3-1.5 -0.2 0.7-0.3 1.7-0.4 2.7 -0.1 0.7-0.1 1.4-0.1 2.1 0 1.4 0.2 2.8 0.4 3.7 0.4 0 0.8 0 1.2 0C2.5 25 3.2 24.9 3.8 24.9zM15.9 26.3c0.4-0.4 0.9-0.7 1.3-1.1 0.3-0.2 0.5-0.5 0.8-0.7 0.3-0.3 0.5-0.5 0.8-0.9 -4.9 1.9-11.8 2.6-17.9 2.7 0.5 2.1 1.6 4.6 3.2 6.9 1.9-0.9 3.8-1.8 5.6-2.8C11.9 29.2 14 27.8 15.9 26.3zM27.9 25c-1.2-0.2-2.3-0.5-3.4-0.9 -0.5-0.2-0.9-0.3-1.4-0.5 1 1.1 2.1 2 3.2 2.9 3.4 2.7 7.3 4.8 11.5 6.7 1.4-2 2.5-4.1 3.2-6.9 -2.2-0.1-4.3-0.2-6.4-0.3C32.3 25.7 30.1 25.4 27.9 25zM17.4 28.7c0.5-1 1-2 1.5-3.1 0.1-0.3 0.2-0.5 0.4-0.8 -0.7 0.7-1.4 1.3-2 1.9 -1.3 1.1-2.7 2.1-4.2 3.1 -1.3 0.8-2.5 1.5-3.9 2.3 -1.8 0.9-3.7 1.8-4.2 2 1.9 2.3 3.9 3.9 6.2 5.1 0.7-1 1.4-2 2-3C14.6 33.9 16.1 31.4 17.4 28.7zM25.5 27.4c-1-0.8-2.1-1.7-2.9-2.5 0.2 0.5 0.4 0.9 0.6 1.4 0.5 1.1 1.1 2.2 1.7 3.3 1.3 2.4 2.6 4.7 4 6.9 0.6 0.9 1.2 1.8 1.9 2.7 1.4-0.8 2.6-1.7 3.8-2.6 0.5-0.4 1.9-1.8 2.4-2.5 -1.1-0.4-2.1-1-3.1-1.5C30.9 31.1 28.1 29.4 25.5 27.4zM18.7 29c-0.4 0.8-0.8 1.6-1.3 2.4 -1 1.7-1.9 3.5-3 5.1 -0.5 0.8-1 1.6-1.6 2.4 -0.2 0.3-0.4 0.6-0.6 0.9 0 0 0 0 0 0 2.1 1 5.1 1.9 8.2 1.9 0-5.4 0-10.8 0-16.3C19.8 26.7 19.2 27.8 18.7 29zM28 37.2c-0.3-0.5-0.6-1-0.9-1.4 -0.9-1.4-1.8-3-2.6-4.5 -0.3-0.5-0.5-1-0.8-1.5 -0.4-0.6-0.7-1.3-1-2 -0.3-0.7-0.8-1.5-1.1-2.3 0 5.4 0 10.9 0 16.3 3.1-0.1 5.9-0.9 8.2-1.9v0c-0.2-0.3-0.5-0.6-0.7-0.9C28.7 38.3 28.3 37.8 28 37.2z"/></svg>
                    <span class="leaf">LEAF</span><span class="academy">ACADEMY</span>
                </span>
                <span class="slogan"><?php echo str_replace('for', '<br />for',get_bloginfo( 'description' )); ?></span>
            </a>
            
        </div>
        
        <nav class="nav">
            <a href="/" class="nav-toggle"><i class="icon"></i><span class="text">Navigation</span></a>
            <?php wp_nav_menu( array( 'container'=>false, 'theme_location' => 'primary', 'menu_class' => 'menu', 'menu_id' => 'primary-menu' ) ); ?>
        </nav>
    </header><!-- #header -->

    <!-- #masthead -->
<?php

$inlineCss = '';

if (is_singular('page')) {
	
	$meta_bgimage = get_post_meta(get_the_ID(), 'la_bg_pattern', true);
	if (!empty($meta_bgimage)) {
		$inlineCss = ' style="background-image: url(\'' . wp_get_attachment_url($meta_bgimage) . '\');"';
	}

}

?>
	<div id="main" class="site-main"<?php echo $inlineCss; ?>>
