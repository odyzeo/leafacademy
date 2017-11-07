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
    <link rel="stylesheet" type="text/css" href="//cloud.typography.com/7330954/710488/css/fonts.css" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
    
    <!-- social buttons -->
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "0fc5aeb4-121a-4970-848f-0ac5c9132a2c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
    <script>
       <?php /*******
       * 
       * TRACKOVACIE KODY treba podla potreby dat aj do page-templates/blank.php
       *
       * 
       ******/ ?>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-68458102-1', 'auto');
      ga('send', 'pageview');


     /* (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga'); */
      ga('create', 'UA-38435203-6',  {'name':'b'});
      ga('b.send', 'pageview');
    </script>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1566332653658329";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

    <header id="header">
        <div class="top">
            
            <div class="top-nav">
               <?php wp_nav_menu( array( 'container'=>false, 'theme_location' => 'secondary_top', 'menu_class' => 'menu', 'menu_id' => 'secondary-top-menu', 'depth'=>1 ) ); ?>
               <!-- <ul class="menu">
                    <li><a href="/">Contact</a></li>
                    <li><a href="/">Support us</a></li>
                    <li><a href="/">Media</a></li>
                </ul> -->
                <div class="search">
                    <?php get_search_form(); ?>
                    <!--
                    <input type="search" role="search">
                    <button type="submit"></button>
                    -->
                    <!--
                    <form role="search" method="get" class="search-form" action="http://leafacademy.bytriad.sk/">
                        <label>
                            <span class="screen-reader-text">Search for:</span>
                            <input type="search" class="search-field" placeholder="Search …" value="" name="s" title="Search for:">
                        </label>
                        <input type="submit" class="search-submit" value="Search">
                    </form>
                    -->
                   
                </div>
                 <div class="language-switcher-wrap">
                 <div class="language-switcher">
                  <a href="/" data-lang="EN" class="language-switcher-trigger">EN</a>
                  <ul>
                        <li><a href="http://academy.leaf.sk/"><span>SK</span></a></li>
                        <li><a href="http://academy.leaf.sk/cz"><span>CZ</span></a></li>
                        <li><a href="http://academy.leaf.sk/de"><span>AT</span></a></li>
                        <li><a href="http://academy.leaf.sk/hu"><span>DE</span></a></li>
                        <li><a href="http://academy.leaf.sk/pl"><span>PL</span></a></li>
                    
                  </ul><span aria-hidden="true" class="stretchy-nav-bg"></span>
                </div>
                </div>
                <div class="top-social top-social-alt">
                   <?php echo DISPLAY_ULTIMATE_PLUS(); ?>
                </div>
            </div>
            
            <!--<a href="/" class="logo">
                <span    class="image">LEAF ACADEMY</span>
                <span class="slogan">Boarding School for Future Leadership</span>
            </a> -->
            <div class="top-social top-social-main">
                <?php echo DISPLAY_ULTIMATE_PLUS(); ?>
            </div>
            
            <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <span class="image">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0" y="0" width="42" height="42" viewBox="0 0 42 42" enable-background="new 0 0 42 42" xml:space="preserve"><path fill="#00B24C" d="M27.1 20.3c2.2-0.5 4.2-1.1 6.2-1.8 1-0.4 2.1-0.8 3.2-1.2 1.1-0.5 2.2-1 3.3-1.5 0.4-0.2 0.7-0.4 1.1-0.6 -0.2-0.6-0.4-1.2-0.6-1.7 -0.6-1.7-1.5-3.1-2.4-4.5 -0.4-0.6-0.8-1.1-1.2-1.6 -11.1 9.6-14.5 12.5-14.5 12.5 0.3-1 0.6-1.6 1.6-3.2 1.8-3.2 4.1-6.1 6.7-8.6 1.5-1.4 2.9-2.6 3.8-3.2 -0.3-0.3-0.7-0.5-1-0.8 -3.1 2.4-4 3.4-6.5 6.2 -1 1.2-2 2.5-2.8 3.8 -0.5 0.7-0.9 1.4-1.3 2.2h0c0.2-1.3 0.6-2.7 1-3.9 0.7-2.3 1.6-4.7 2.5-6.5 0.5-1 1.1-2 1.6-2.9 0.2-0.3 0.7-1.1 0.9-1.4 -0.4-0.1-0.8-0.3-1.2-0.4 -0.6 1-0.9 1.4-1.9 3.1 -0.6 1.2-1.2 2.4-1.8 3.7 -0.4 1-0.8 2-1.2 3.1 -0.2 0.7-0.4 1.4-0.6 2.1 -0.1 0.4-0.2 0.8-0.3 1.2 0-4.7 0-9.5 0-14.2 -0.5 0-0.7 0-1.3 0 0 4.7 0 9.5 0 14.2 -0.1-0.4-0.2-0.8-0.3-1.2 -0.3-1.1-0.6-2.2-1-3.3 -0.9-2.4-1.9-4.7-3.1-6.7 -0.4-0.7-0.8-1.3-1.2-2 -0.4 0.1-0.8 0.3-1.2 0.4 0.4 0.6 1.5 2.4 2.4 4.1 1.2 2.3 2.2 4.9 2.9 7.6 0.4 1.6 0.6 2.4 0.7 3.1 -0.6-1-1.2-2-1.9-3 -1.7-2.4-3.6-4.6-5.6-6.6 -1.4-1.4-2.7-2.3-3.2-2.7 -0.6 0.5-0.7 0.5-1 0.8 1.1 0.8 2.3 1.8 3.7 3.1 0.9 0.8 3.5 3.6 5.7 6.9 0.4 0.6 0.8 1.2 1.1 1.7 1 1.6 1.1 2.2 1.5 3.2 -5-4.3-9.5-8.2-14.5-12.5 -2.1 2.5-3.4 5-4.3 7.8 0.7 0.4 1.4 0.7 2.2 1.1 1.9 0.9 4.2 1.8 4.7 2 2.1 0.8 4.5 1.5 6.9 2 1.9 0.4 3.6 0.7 5.5 0.8 0.6 0 1.5 0 2.1 0C24.1 20.8 25.6 20.6 27.1 20.3zM41.5 17.7c-0.1-0.4-0.1-0.8-0.2-1.3 -0.3 0.1-0.5 0.3-0.8 0.4 -0.9 0.5-1.9 0.9-2.8 1.3 -2.9 1.3-6 2.4-9.4 3.2 -1.1 0.3-2.3 0.5-3.4 0.6 -0.6 0.1-1.2 0.1-1.7 0.2 0.3 0.1 0.5 0.2 0.8 0.3 0.7 0.3 1.5 0.5 2.3 0.7 2.2 0.6 4.7 1 7.1 1.3 1.1 0.1 2.2 0.2 3.2 0.3 0.8 0.1 1.6 0.1 2.5 0.1 0.8 0 1.6 0.1 2.5 0.1 0.2-1 0.3-2.3 0.4-3.5C41.8 20.2 41.7 18.7 41.5 17.7zM3.8 24.9c0.5 0 1-0.1 1.5-0.1 0.3 0 0.6 0 0.8-0.1 0.6-0.1 1.2-0.1 1.8-0.2 2.7-0.3 5.9-0.7 7.9-1.3 2.1-0.6 2.7-0.9 3.1-1.1 -3-0.2-6.7-1.1-9.7-2.1 -0.9-0.3-1.7-0.6-2.5-0.9 -1-0.4-2-0.8-2.9-1.2 -1-0.5-1.9-0.9-3-1.5 -0.2 0.7-0.3 1.7-0.4 2.7 -0.1 0.7-0.1 1.4-0.1 2.1 0 1.4 0.2 2.8 0.4 3.7 0.4 0 0.8 0 1.2 0C2.5 25 3.2 24.9 3.8 24.9zM15.9 26.3c0.4-0.4 0.9-0.7 1.3-1.1 0.3-0.2 0.5-0.5 0.8-0.7 0.3-0.3 0.5-0.5 0.8-0.9 -4.9 1.9-11.8 2.6-17.9 2.7 0.5 2.1 1.6 4.6 3.2 6.9 1.9-0.9 3.8-1.8 5.6-2.8C11.9 29.2 14 27.8 15.9 26.3zM27.9 25c-1.2-0.2-2.3-0.5-3.4-0.9 -0.5-0.2-0.9-0.3-1.4-0.5 1 1.1 2.1 2 3.2 2.9 3.4 2.7 7.3 4.8 11.5 6.7 1.4-2 2.5-4.1 3.2-6.9 -2.2-0.1-4.3-0.2-6.4-0.3C32.3 25.7 30.1 25.4 27.9 25zM17.4 28.7c0.5-1 1-2 1.5-3.1 0.1-0.3 0.2-0.5 0.4-0.8 -0.7 0.7-1.4 1.3-2 1.9 -1.3 1.1-2.7 2.1-4.2 3.1 -1.3 0.8-2.5 1.5-3.9 2.3 -1.8 0.9-3.7 1.8-4.2 2 1.9 2.3 3.9 3.9 6.2 5.1 0.7-1 1.4-2 2-3C14.6 33.9 16.1 31.4 17.4 28.7zM25.5 27.4c-1-0.8-2.1-1.7-2.9-2.5 0.2 0.5 0.4 0.9 0.6 1.4 0.5 1.1 1.1 2.2 1.7 3.3 1.3 2.4 2.6 4.7 4 6.9 0.6 0.9 1.2 1.8 1.9 2.7 1.4-0.8 2.6-1.7 3.8-2.6 0.5-0.4 1.9-1.8 2.4-2.5 -1.1-0.4-2.1-1-3.1-1.5C30.9 31.1 28.1 29.4 25.5 27.4zM18.7 29c-0.4 0.8-0.8 1.6-1.3 2.4 -1 1.7-1.9 3.5-3 5.1 -0.5 0.8-1 1.6-1.6 2.4 -0.2 0.3-0.4 0.6-0.6 0.9 0 0 0 0 0 0 2.1 1 5.1 1.9 8.2 1.9 0-5.4 0-10.8 0-16.3C19.8 26.7 19.2 27.8 18.7 29zM28 37.2c-0.3-0.5-0.6-1-0.9-1.4 -0.9-1.4-1.8-3-2.6-4.5 -0.3-0.5-0.5-1-0.8-1.5 -0.4-0.6-0.7-1.3-1-2 -0.3-0.7-0.8-1.5-1.1-2.3 0 5.4 0 10.9 0 16.3 3.1-0.1 5.9-0.9 8.2-1.9v0c-0.2-0.3-0.5-0.6-0.7-0.9C28.7 38.3 28.3 37.8 28 37.2z"/></svg>
                    <span class="leaf">LEAF</span><span class="academy">ACADEMY</span>
                    <?php /*bloginfo( 'name' ); */?>
                </span>
                <span class="slogan"><?php echo str_replace('for', '<br />for',get_bloginfo( 'description' )); ?></span>
            </a>
            
            
        </div>
        
        <nav class="nav">
            <a href="/" class="nav-toggle"><i class="icon"></i><span class="text">Navigation</span></a>
            <?php wp_nav_menu( array( 'container'=>false, 'theme_location' => 'primary', 'menu_class' => 'menu', 'menu_id' => 'primary-menu' ) ); ?>
            <!--<ul class="menu">
                <li>
                    <a href="/">LEAF Academy</a>
                    <ul class="menu">
                        <li><a href="/">Welcome</a></li>
                        <li><a href="/">Key Features</a></li>
                        <li><a href="/">Our mission</a></li>
                        <li><a href="/">What makes us unique</a></li>
                        <li><a href="/">Our team</a></li>
                        <li><a href="/">Advisory council</a></li>
                        <li><a href="/">About LEAF</a></li>
                    </ul>
                </li>
                <li class="active"><a href="/">Education</a></li>
                <li><a href="/">Community</a></li>
                <li><a href="/">Admission</a></li>
                <li><a href="/">News and events</a></li>
            </ul>-->
        </nav>
    </header><!-- #header -->

    <!-- #masthead -->

	<div id="main" class="site-main">
