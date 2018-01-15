<?php
/**
 * Twenty Fourteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
require_once dirname(__FILE__) . "/functions-leafacademyblog.php";
require_once dirname(__FILE__) . "/lib/PopupManager.php";

PopupManager::init();

/**
 * Set up the content width value based on the theme's design.
 *
 * @see leafacademy_content_width()
 *
 * @since Twenty Fourteen 1.0
 */
if (!isset($content_width)) {
	$content_width = 474;
}

/**
 * LEAF Academy only works in WordPress 3.6 or later.
 */
if (version_compare($GLOBALS['wp_version'], '3.6', '<')) {
	require get_template_directory() . '/inc/back-compat.php';
}

function get_youtube_video_id($url) {

	preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
	return $matches[1];

}

define('__TEXTDOMAIN__', 'leafacademy');

if (!function_exists('leafacademy_setup')) :

	/**
	 * LEAF Academy setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 * @since LEAF Academy 1.0
	 */
	function leafacademy_setup() {

		/*
		 * Make LEAF Academy available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on LEAF Academy, use a find and
		 * replace to change 'leafacademy' to the name of your theme in all
		 * template files.
		 */
		load_theme_textdomain('leafacademy', get_template_directory() . '/languages');

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style(array('css/editor-style.css', leafacademy_font_url(), 'genericons/genericons.css'));

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support('automatic-feed-links');

		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(640, 250, true);
		add_image_size('leafacademy-full-width', 1280, 500, true);
		add_image_size('leafacademy-small-square', 190, 190, true);
		add_image_size('leafacademy-block-image', 600, 1000, false);
		add_image_size('leafacademy-block-wide-image', 1000, 1000, false);

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(array(
			'primary' => __('Top primary menu', 'leafacademy'),
			'secondary_top' => __('Top Secondary menu', 'leafacademy'),
				//'secondary' => __( 'Secondary menu in left sidebar', 'leafacademy' ),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		));

		/*
		 * Enable support for Post Formats.
		 * See https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support('post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		));

		// This theme allows users to set a custom background.
		add_theme_support('custom-background', apply_filters('leafacademy_custom_background_args', array(
			'default-color' => 'f5f5f5',
		)));

		// Add support for featured content.
		add_theme_support('featured-content', array(
			'featured_content_filter' => 'leafacademy_get_featured_posts',
			'max_posts' => 6,
		));

		// This theme uses its own gallery styles.
		add_filter('use_default_gallery_style', '__return_false');

	}

endif; // leafacademy_setup
add_action('after_setup_theme', 'leafacademy_setup');

/**
 * Adjust content_width value for image attachment template.
 *
 * @since LEAF Academy 1.0
 */
function leafacademy_content_width() {
	if (is_attachment() && wp_attachment_is_image()) {
		$GLOBALS['content_width'] = 810;
	}

}

add_action('template_redirect', 'leafacademy_content_width');

/**
 * Getter function for Featured Content Plugin.
 *
 * @since LEAF Academy 1.0
 *
 * @return array An array of WP_Post objects.
 */
function leafacademy_get_featured_posts() {
	/**
	 * Filter the featured posts to return in LEAF Academy.
	 *
	 * @since LEAF Academy 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters('leafacademy_get_featured_posts', array());

}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since LEAF Academy 1.0
 *
 * @return bool Whether there are featured posts.
 */
function leafacademy_has_featured_posts() {
	return !is_paged() && (bool) leafacademy_get_featured_posts();

}

/**
 * Register three LEAF Academy widget areas.
 *
 * @since LEAF Academy 1.0
 */
function leafacademy_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
	register_widget('LEAF_Academy_Widget');

	register_sidebar(array(
		'name' => __('Primary Sidebar', 'leafacademy'),
		'id' => 'sidebar-1',
		'description' => __('Main sidebar that appears on the left.', 'leafacademy'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	));
	register_sidebar(array(
		'name' => __('Content Sidebar', 'leafacademy'),
		'id' => 'sidebar-2',
		'description' => __('Additional sidebar that appears on the right.', 'leafacademy'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	));
	register_sidebar(array(
		'name' => __('Footer Widget Area', 'leafacademy'),
		'id' => 'sidebar-3',
		'description' => __('Appears in the footer section of the site.', 'leafacademy'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	));

}

add_action('widgets_init', 'leafacademy_widgets_init');

/**
 * Register Lato Google font for LEAF Academy.
 *
 * @since LEAF Academy 1.0
 *
 * @return string
 */
function leafacademy_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ('off' !== _x('on', 'Lato font: on or off', 'leafacademy')) {
		$query_args = array(
			'family' => urlencode('Lato:300,400,700,900,300italic,400italic,700italic'),
			'subset' => urlencode('latin,latin-ext'),
		);
		$font_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
	}

	return $font_url;

}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since LEAF Academy 1.0
 */
function leafacademy_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style('leafacademy-lato', leafacademy_font_url(), array(), null);

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.3');

	// Load our main stylesheet.
	wp_enqueue_style('leafacademy-style', get_template_directory_uri() . '/css/style.css', array(), '1.0.19');

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('leafacademy-ie', get_template_directory_uri() . '/css/ie.css', array('leafacademy-style'), '20131205');
	wp_enqueue_style('leafacademy-slick', get_template_directory_uri() . '/js/slick/slick.css', array('leafacademy-style'), '20131205');
	wp_style_add_data('leafacademy-ie', 'conditional', 'lt IE 9');

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	if (is_singular() && wp_attachment_is_image()) {
		wp_enqueue_script('leafacademy-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20130402');
	}

	if (is_front_page() && 'slider' == get_theme_mod('featured_content_layout')) {
		wp_enqueue_script('leafacademy-slider', get_template_directory_uri() . '/js/slider.js', array('jquery'), '20151102', true);

		wp_localize_script('leafacademy-slider', 'featuredSliderDefaults', array(
			'prevText' => __('Previous', 'leafacademy'),
			'nextText' => __('Next', 'leafacademy')
		));
	}
	wp_enqueue_script('leafacademy-matchHeight', get_template_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), '20151102', true);
	wp_enqueue_script('leafacademy-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array('jquery'), '20151102', true);
	wp_enqueue_script('leafacademy-masonry', get_template_directory_uri() . '/js/masonry.pkgd.min.js', array('jquery'), '20151102', true);
	wp_enqueue_script('leafacademy-slick', get_template_directory_uri() . '/js/slick/slick.min.js', array('jquery'), '20151102', true);
	wp_enqueue_script('leafacademy-script', get_template_directory_uri() . '/js/functions.js', array('jquery'), '201711001', true);

}

add_action('wp_enqueue_scripts', 'leafacademy_scripts');

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since LEAF Academy 1.0
 */
function leafacademy_admin_fonts() {
	wp_enqueue_style('leafacademy-lato', leafacademy_font_url(), array(), null);

}

add_action('admin_print_scripts-appearance_page_custom-header', 'leafacademy_admin_fonts');

if (!function_exists('leafacademy_the_attached_image')) :

	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since LEAF Academy 1.0
	 */
	function leafacademy_the_attached_image() {
		$post = get_post();
		/**
		 * Filter the default LEAF Academy attachment size.
		 *
		 * @since LEAF Academy 1.0
		 *
		 * @param array $dimensions {
		 *     An array of height and width dimensions.
		 *
		 *     @type int $height Height of the image in pixels. Default 810.
		 *     @type int $width  Width of the image in pixels. Default 810.
		 * }
		 */
		$attachment_size = apply_filters('leafacademy_attachment_size', array(810, 810));
		$next_attachment_url = wp_get_attachment_url();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts(array(
			'post_parent' => $post->post_parent,
			'fields' => 'ids',
			'numberposts' => -1,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order',
		));

		// If there is more than 1 attachment in a gallery...
		if (count($attachment_ids) > 1) {
			foreach ($attachment_ids as $attachment_id) {
				if ($attachment_id == $post->ID) {
					$next_id = current($attachment_ids);
					break;
				}
			}

			// get the URL of the next image attachment...
			if ($next_id) {
				$next_attachment_url = get_attachment_link($next_id);
			}

			// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link(reset($attachment_ids));
			}
		}

		printf('<a href="%1$s" rel="attachment">%2$s</a>', esc_url($next_attachment_url), wp_get_attachment_image($post->ID, $attachment_size)
		);

	}

endif;

if (!function_exists('leafacademy_list_authors')) :

	/**
	 * Print a list of all site contributors who published at least one post.
	 *
	 * @since LEAF Academy 1.0
	 */
	function leafacademy_list_authors() {
		$contributor_ids = get_users(array(
			'fields' => 'ID',
			'orderby' => 'post_count',
			'order' => 'DESC',
			'who' => 'authors',
		));

		foreach ($contributor_ids as $contributor_id) :
			$post_count = count_user_posts($contributor_id);

			// Move on if user has not published a post (yet).
			if (!$post_count) {
				continue;
			}
			?>

			<div class="contributor">
				<div class="contributor-info">
					<div class="contributor-avatar"><?php echo get_avatar($contributor_id, 132); ?></div>
					<div class="contributor-summary">
						<h2 class="contributor-name"><?php echo get_the_author_meta('display_name', $contributor_id); ?></h2>
						<p class="contributor-bio">
							<?php echo get_the_author_meta('description', $contributor_id); ?>
						</p>
						<a class="button contributor-posts-link" href="<?php echo esc_url(get_author_posts_url($contributor_id)); ?>">
							<?php printf(_n('%d Article', '%d Articles', $post_count, 'leafacademy'), $post_count); ?>
						</a>
					</div><!-- .contributor-summary -->
				</div><!-- .contributor-info -->
			</div><!-- .contributor -->

			<?php
		endforeach;

	}

endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since LEAF Academy 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function leafacademy_body_classes($classes) {
	if (is_multi_author()) {
		$classes[] = 'group-blog';
	}

	if (get_header_image()) {
		$classes[] = 'header-image';
	} elseif (!in_array($GLOBALS['pagenow'], array('wp-activate.php', 'wp-signup.php'))) {
		$classes[] = 'masthead-fixed';
	}

	if (is_archive() || is_search() || is_home()) {
		$classes[] = 'list-view';
	}

	if ((!is_active_sidebar('sidebar-2') ) || is_page_template('page-templates/full-width.php') || is_page_template('page-templates/contributors.php') || is_page_template('page-templates/homepage.php') || is_page_template('page-templates/contact.php') || is_attachment()) {
		$classes[] = 'full-width';
	}

	if (is_active_sidebar('sidebar-3')) {
		$classes[] = 'footer-widgets';
	}

	if (is_singular() && !is_front_page()) {
		$classes[] = 'singular';
	}

	if (is_front_page() && 'slider' == get_theme_mod('featured_content_layout')) {
		$classes[] = 'slider';
	} elseif (is_front_page()) {
		$classes[] = 'grid';
	}

	return $classes;

}

add_filter('body_class', 'leafacademy_body_classes');

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since LEAF Academy 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function leafacademy_post_classes($classes) {
	if (!post_password_required() && !is_attachment() && has_post_thumbnail()) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;

}

add_filter('post_class', 'leafacademy_post_classes');

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since LEAF Academy 1.0
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function leafacademy_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed()) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo('name', 'display');

	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && ( is_home() || is_front_page() )) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if (( $paged >= 2 || $page >= 2 ) && !is_404()) {
		$title = "$title $sep " . sprintf(__('Page %s', 'leafacademy'), max($paged, $page));
	}

	return $title;

}

add_filter('wp_title', 'leafacademy_wp_title', 10, 2);

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if (!class_exists('Featured_Content') && 'plugins.php' !== $GLOBALS['pagenow']) {
	require get_template_directory() . '/inc/featured-content.php';
}


// add hook
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects_sub_menu', 10, 2);

// filter_hook function to react on sub_menu flag
function my_wp_nav_menu_objects_sub_menu($sorted_menu_items, $args) {
	if (isset($args->sub_menu)) {
		$root_id = 0;

		// find the current menu item
		foreach ($sorted_menu_items as $menu_item) {
			if ($menu_item->current) {
				// set the root id based on whether the current menu item has a parent or not
				$root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
				break;
			}
		}

		// find the top level parent
		if (!isset($args->direct_parent)) {
			$prev_root_id = $root_id;
			while ($prev_root_id != 0) {
				foreach ($sorted_menu_items as $menu_item) {
					if ($menu_item->ID == $prev_root_id) {
						$prev_root_id = $menu_item->menu_item_parent;
						// don't set the root_id to 0 if we've reached the top of the menu
						if ($prev_root_id != 0)
							$root_id = $menu_item->menu_item_parent;
						break;
					}
				}
			}
		}
		$menu_item_parents = array();
		foreach ($sorted_menu_items as $key => $item) {
			// init menu_item_parents
			if ($item->ID == $root_id)
				$menu_item_parents[] = $item->ID;
			if (in_array($item->menu_item_parent, $menu_item_parents)) {
				// part of sub-tree: keep!
				$menu_item_parents[] = $item->ID;
			} else if (!( isset($args->show_parent) && in_array($item->ID, $menu_item_parents) )) {
				// not part of sub-tree: away with it!
				unset($sorted_menu_items[$key]);
			}
		}

		return $sorted_menu_items;
	} else {
		return $sorted_menu_items;
	}

}

// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;

}

// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'my_mce_buttons_2');

// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats($init_array) {
	// Define the style_formats array
	$style_formats = array(
		// Each array child is a format with it's own settings

		array(
			'title' => 'Smaller paragraph',
			'selector' => 'p',
			'block' => 'p',
			'classes' => 'small',
			'wrapper' => true,
		),
		array(
			'title' => 'Green text',
			//'selector' => '',  
			'inline' => 'span',
			'classes' => 'green-text',
			'wrapper' => true,
		),
		array(
			'title' => 'Button green/grey',
			'inline' => 'a',
			'classes' => 'btn green-grey',
			'wrapper' => true,
		),
		array(
			'title' => 'Button grey/green',
			'inline' => 'a',
			'classes' => 'btn grey-green',
			'wrapper' => true,
		),
		array(
			'title' => 'Button green/white',
			'inline' => 'a',
			'classes' => 'btn green',
			'wrapper' => true,
		),
		array(
			'title' => 'Button grey/white',
			'inline' => 'a',
			'classes' => 'btn grey',
			'wrapper' => true,
		),
	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode($style_formats);

	return $init_array;

}

// Attach callback to 'tiny_mce_before_init' 
add_filter('tiny_mce_before_init', 'my_mce_before_init_insert_formats');

function inline_featured_image($atts, $content = null, $name = null) {
	global $post;

	if (class_exists('Dynamic_Featured_Image')) {
		global $dynamic_featured_image;
		$featured_images = $dynamic_featured_image->get_featured_images($post->ID);

		if (!isset($attr['index'])) {
			$attr['index'] = 1;
		}
		$fi = $featured_images[$attr['index'] - 1];
		if ($fi) {
			//return print_r($fi,true);       
			return leafacademy_inline_featured_image($fi["attachment_id"]);
		} else {
			return false;
		}
	}

}

add_shortcode('featured_image', 'inline_featured_image');



add_action('init', 'leafacademy_create_post_type');

function leafacademy_create_post_type() {
	register_post_type('team_member', array(
		'labels' => array(
			'name' => __('Team Members', __TEXTDOMAIN__),
			'singular_name' => __('Team Member', __TEXTDOMAIN__)
		),
		'public' => false,
		'has_archive' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		//'menu_icon' =>
		'hierarchical' => false,
		'supports' => array(
			'title', 'editor', 'thumbnail'
		)
			)
	);

	register_post_type('grid_block', array(
		'labels' => array(
			'name' => __('Grid Blocks', __TEXTDOMAIN__),
			'singular_name' => __('Grid Block', __TEXTDOMAIN__)
		),
		'public' => false,
		'has_archive' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 6,
		//'menu_icon' =>
		'hierarchical' => false,
		'supports' => array(
			'title', 'editor', 'thumbnail'
		)
			)
	);

}

define('LEAFACADEMY_TEAM_MEMBER_EMAIL_META_KEY', '_team_member_email');
define('LEAFACADEMY_TEAM_MEMBER_TYPE_META_KEY', '_team_member_type');

function leafacademy_metabox_team_member_meta_boxes() {

	add_meta_box(
			'leafacademy_metabox_team_member_email', __("Team member email", __TEXTDOMAIN__), 'leafacademy_metabox_team_member_email_callback', 'team_member', 'normal', 'low'
	);
	add_meta_box(
			'leafacademy_metabox_team_member_type', __("Member type", __TEXTDOMAIN__), 'leafacademy_metabox_team_member_type_callback', 'team_member', 'normal', 'low'
	);

}

add_action('add_meta_boxes', 'leafacademy_metabox_team_member_meta_boxes');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function leafacademy_metabox_team_member_email_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_team_member_email', 'leafacademy_metabox_team_member_email_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_TEAM_MEMBER_EMAIL_META_KEY, true);

	echo '<input type="email" name="_team_member_email" class="text  email widefat large" id="_team_member_email" value="' . esc_attr($value) . '">';
	echo '<p class="description">' . __("Team member email address.", __TEXTDOMAIN__) . '</p>';

}

function leafacademy_metabox_team_member_type_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_team_member_type', 'leafacademy_metabox_team_member_type_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_TEAM_MEMBER_TYPE_META_KEY, true);

	echo '<select  name="_team_member_type" class=" widefat" id="_team_member_type">';
	echo '<option value="our-team" ' . ($value == "our-team" ? 'selected="selected"' : '') . ' >Our team</option>';
	echo '<option value="advisory-council" ' . ($value == "advisory-council" ? 'selected="selected"' : '') . ' >Advisory council</option>';
	echo '</select>';
	// echo '<p class="description">'.__("Block background color", __TEXTDOMAIN__).'</p>';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function leafacademy_metabox_team_member_email_save_date($post_id) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if (!isset($_POST['leafacademy_metabox_team_member_email_nonce']) || !isset($_POST['leafacademy_metabox_team_member_type_nonce'])) {
		return;
	}

	// Verify that the nonce is valid.
	if (!wp_verify_nonce($_POST['leafacademy_metabox_team_member_email_nonce'], 'leafacademy_metabox_team_member_email')) {
		return;
	}
	// Verify that the nonce is valid.
	if (!wp_verify_nonce($_POST['leafacademy_metabox_team_member_type_nonce'], 'leafacademy_metabox_team_member_type')) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Check the user's permissions.
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	/* OK, it's safe for us to save the data now. */

	// Sanitize user input.
	$email = isset($_POST["_team_member_email"]) ? $_POST["_team_member_email"] : "";
	$type = isset($_POST["_team_member_type"]) ? $_POST["_team_member_type"] : "";

	// Update the meta field in the database.
	update_post_meta($post_id, LEAFACADEMY_TEAM_MEMBER_EMAIL_META_KEY, $email);
	update_post_meta($post_id, LEAFACADEMY_TEAM_MEMBER_TYPE_META_KEY, $type);

}

add_action('save_post', 'leafacademy_metabox_team_member_email_save_date');


/* * ******* BLOCKS ********** */
define('LEAFACADEMY_BLOCK_TAG_META_KEY', '_block_tag');
define('LEAFACADEMY_BLOCK_BGCOLOR_META_KEY', '_block_bgcolor');
define('LEAFACADEMY_BLOCK_WIDE_META_KEY', '_block_wide');
define('LEAFACADEMY_BLOCK_HIDE_TITLE_META_KEY', '_block_hide_title');

function leafacademy_metabox_block() {

	add_meta_box(
			'leafacademy_metabox_block_tag', __("Block tag", __TEXTDOMAIN__), 'leafacademy_metabox_block_tag_callback', 'grid_block', 'normal', 'low'
	);

	add_meta_box(
			'leafacademy_metabox_block_bgcolor', __("Background color", __TEXTDOMAIN__), 'leafacademy_metabox_block_bgcolor_callback', 'grid_block', 'normal', 'low'
	);

	add_meta_box(
			'leafacademy_metabox_block_wide', __("Block size", __TEXTDOMAIN__), 'leafacademy_metabox_block_wide_callback', 'grid_block', 'normal', 'low'
	);
	add_meta_box(
			'leafacademy_metabox_block_hide_title', __("Block title options", __TEXTDOMAIN__), 'leafacademy_metabox_block_hide_title_callback', 'grid_block', 'normal', 'low'
	);

}

add_action('add_meta_boxes', 'leafacademy_metabox_block');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function leafacademy_metabox_block_tag_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_block_tag', 'leafacademy_metabox_block_tag_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_BLOCK_TAG_META_KEY, true);

	echo '<input type="text" name="_block_tag" class="text  widefat large" id="_block_tag" value="' . esc_attr($value) . '">';
	echo '<p class="description">' . __("Block tag used in shortcodes", __TEXTDOMAIN__) . '</p>';

}

function leafacademy_metabox_block_bgcolor_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_block_bgcolor', 'leafacademy_metabox_block_bgcolor_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_BLOCK_BGCOLOR_META_KEY, true);

	echo '<select  name="_block_bgcolor" class=" widefat" id="_block_bgcolor">';
	echo '<option value="white" ' . ($value == "white" ? 'selected="selected"' : '') . ' >White</option>';
	echo '<option value="green" ' . ($value == "green" ? 'selected="selected"' : '') . ' >Green</option>';
	echo '<option value="darkgrey" ' . ($value == "darkgrey" ? 'selected="selected"' : '') . ' >Grey</option>';
	echo '<option value="yellow" ' . ($value == "yellow" ? 'selected="selected"' : '') . ' >Yellow</option>';
	echo '</select>';
	echo '<p class="description">' . __("Block background color", __TEXTDOMAIN__) . '</p>';

}

function leafacademy_metabox_block_hide_title_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_block_hide_title', 'leafacademy_metabox_block_hide_title_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_BLOCK_HIDE_TITLE_META_KEY, true);

	echo '<input type="hidden"  name="_block_hide_title" class="" value="0" />';
	echo '<input type="checkbox"  name="_block_hide_title" class="" value="1" id="_block_hide_title" ' . ($value ? 'checked="checked"' : '') . ' />';
	echo '<label for="_block_hide_title">Hide block title in views</label>';

}

function leafacademy_metabox_block_wide_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_block_wide', 'leafacademy_metabox_block_wide_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_BLOCK_WIDE_META_KEY, true);

	echo '<input type="hidden"  name="_block_wide" class="" value="0" />';
	echo '<input type="checkbox"  name="_block_wide" class="" value="1" id="_block_wide" ' . ($value == 1 ? 'checked="checked"' : '') . ' />';
	echo '<label for="_block_wide">Double width block</label>';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function leafacademy_metabox_block_tag_save_date($post_id) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if (!isset($_POST['leafacademy_metabox_block_tag_nonce']) || !isset($_POST['leafacademy_metabox_block_bgcolor_nonce']) || !isset($_POST['leafacademy_metabox_block_hide_title_nonce']) || !isset($_POST['leafacademy_metabox_block_wide_nonce'])
	) {
		return;
	}

	// Verify that the nonce is valid.
	if (!wp_verify_nonce($_POST['leafacademy_metabox_block_tag_nonce'], 'leafacademy_metabox_block_tag')) {
		return;
	}
	// Verify that the nonce is valid.
	if (!wp_verify_nonce($_POST['leafacademy_metabox_block_bgcolor_nonce'], 'leafacademy_metabox_block_bgcolor')) {
		return;
	}
	// Verify that the nonce is valid.
	/* if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_block_hide_title_nonce'], 'leafacademy_metabox_hide_title' ) ) {
	  return;
	  } */

	// Verify that the nonce is valid.
	/* if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_block_wide_nonce'], 'leafacademy_metabox_block_wide' ) ) {
	  return;
	  } */

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Check the user's permissions.
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	/* OK, it's safe for us to save the data now. */

	// Sanitize user input.
	$tag = isset($_POST["_block_tag"]) ? $_POST["_block_tag"] : "";
	$bgcolor = isset($_POST["_block_bgcolor"]) ? $_POST["_block_bgcolor"] : "";
	$hideTitle = isset($_POST["_block_hide_title"]) ? $_POST["_block_hide_title"] : "";
	$wide = isset($_POST["_block_wide"]) ? $_POST["_block_wide"] : 0;

	// Update the meta field in the database.
	update_post_meta($post_id, LEAFACADEMY_BLOCK_TAG_META_KEY, $tag);
	update_post_meta($post_id, LEAFACADEMY_BLOCK_BGCOLOR_META_KEY, $bgcolor);
	update_post_meta($post_id, LEAFACADEMY_BLOCK_HIDE_TITLE_META_KEY, $hideTitle);
	update_post_meta($post_id, LEAFACADEMY_BLOCK_WIDE_META_KEY, $wide);

}

add_action('save_post', 'leafacademy_metabox_block_tag_save_date');


if (!function_exists('leafacademy_team_members')) : // output

	function leafacademy_team_members($atts, $do_shortcode = 1, $strip_shortcodes = 0) {

		$type = isset($atts["type"]) ? $atts["type"] : "our-team";
		$args = array(
			'posts_per_page' => 100,
			'offset' => 0,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_key' => "_team_member_type",
			'meta_value' => $type,
			'post_type' => 'team_member',
			'post_status' => 'publish',
			'suppress_filters' => true
		);

		$posts = get_posts($args);

		$html = '';
		foreach ($posts as $post) {

			$meta_email = get_post_meta($post->ID, '_team_member_email', true);
			$img_url = null;
			$img = get_the_post_thumbnail($post->ID, 'medium');

			if (has_post_thumbnail($post->ID)) {

				$img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'leafacademy-small-square');
				$img_url = $img[0];
			}

			$content = $post->post_content;
			if ($do_shortcode == 1) {
				$content = do_shortcode($content);
			}
			if ($strip_shortcodes == 1) {
				$content = strip_shortcodes($content);
			}

			$content = wpautop($content);

			$html .= '<article class="item">';
			$html .= '<a name="' . $post->post_name . '"></a>';
			$html .= '<a href="#' . $post->post_name . '" class="image do-bg-image" data-supress-scroll><img src="' . ($img_url ? $img_url : '/wp-content/themes/leafacademy/images/team-member.png ') . '"></a>';
			$html .= '<h2 class="name">' . $post->post_title . '</h2>';

			$meta_job_role = get_post_meta($post->ID, 'la_job_role', true);
			if (!empty($meta_job_role)) {
				$html .= '<span class="job-role">' . esc_html($meta_job_role) . '</span>';
			}

			if ($meta_email) {

				$html .='<div class="contact">';
				$html .= '<a href="mailto:' . $meta_email . '">' . $meta_email . '</a>';
				$html .= '</div>';
			}

			$html .='<div class="about"><a class="close"></a><p>' . $content . '</p></div>';
			$html .= '</article>';
		}

		$html = '<div class="block block-team"><div class="items do-match-height">' . $html . '</div></div>';
		return $html;

	}

endif; // end of function_exists()

add_shortcode('team_members', 'leafacademy_team_members');

if (!function_exists('leafacademy_blocks_grid')) : // output

	function leafacademy_blocks_grid($atts, $do_shortcode = 1, $strip_shortcodes = 0) {

		$tag = isset($atts["tag"]) ? $atts["tag"] : "";
		$matchHeight = isset($atts["matchheight"]) ? $atts["matchheight"] : false;

		$args = array(
			'posts_per_page' => 100,
			'offset' => 0,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_key' => "_block_tag",
			'meta_value' => $tag,
			'post_type' => 'grid_block',
			'post_status' => 'publish',
			'suppress_filters' => false
		);

		$posts = get_posts($args);

		$html = '';
		foreach ($posts as $post) {

			$isMajorBlock = get_field('la_major_block', $post->ID);
			$meta_bgcolor = get_post_meta($post->ID, '_block_bgcolor', true);
			$meta_bgimage = get_post_meta($post->ID, 'la_bg_pattern', true);
			$meta_hidetitle = get_post_meta($post->ID, '_block_hide_title', true);
			$meta_wide = get_post_meta($post->ID, '_block_wide', true);

			$img_url = null;

			$img = get_the_post_thumbnail($post->ID, 'medium');
			$sizeClass = $meta_wide ? "wide-column" : "";

			if (has_post_thumbnail($post->ID)) {

				$img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'leafacademy-block-image');
				$imgWide = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'leafacademy-block-wide-image');
				$img_url = $meta_wide ? $img[0] : $imgWide[0];
			}

			//slideshow
			$slides = '';
			if (class_exists('Dynamic_Featured_Image')) {

				global $dynamic_featured_image;
				$featured_images = $dynamic_featured_image->get_featured_images($post->ID);
				foreach ($featured_images as $fi) {
					$slides .='<div class="item do-bg-image">' . wp_get_attachment_image($fi["attachment_id"], 'leafacademy-full-width', false, null) . '</div>';
				}
				if (!empty($slides) && !empty($img_url)) { //este prvy featured image treba pridat lebo ten neni v poli  $featured_images
					$slides = '<div class="item do-bg-image"><img src="' . $img_url . '"></div>' . $slides;
				}
			}

			$content = $post->post_content;
			if ($do_shortcode == 1) {
				$content = do_shortcode($content);
			}
			if ($strip_shortcodes == 1) {
				$content = strip_shortcodes($content);
			}

			$inlineCss = '';
			if (!empty($meta_bgimage)) {
				$inlineCss = 'background-image: url(\'' . wp_get_attachment_url($meta_bgimage) . '\');';
			}

			if ($isMajorBlock) {
				$inlineCss .= 'order: -1;';
			}

			if (!empty($slides)) {
				$html .= '<article class="item image ' . $sizeClass . '" style="' . $inlineCss . '"><div class="images-wrap">' . $slides . '</div></article>';
				$blocksClass = 'block-gallery';
			} else if (trim(strip_tags($content)) == "" && !empty($img)) {
				if ($matchHeight) {
					$html .='<article class="item image do-bg-image ' . $sizeClass . '" style="' . $inlineCss . '"><img src="' . $img_url . '"></article>';
				} else {
					$html .='<article class="item image ' . $sizeClass . '" style="' . $inlineCss . '"><img src="' . $img_url . '"></article>';
				}
			} else if (stripos($content, '<iframe') !== false && $meta_hidetitle) {
				$html .='<article class="item image ' . $sizeClass . '" style="' . $inlineCss . '">' . $content . '</article>';
			} else {

				$content = wpautop($content);
				$html .= '<article class="item bg-' . $meta_bgcolor . ' ' . $sizeClass . '" style="' . $inlineCss . '">';

				if (!$meta_hidetitle) {
					$html .= '<h2 class="title with-spacer">' . $post->post_title . '</h2>';
				}

				$html .= '<div class="text formated-output">' . $content . '</div>';
				$html .= '</article>';
			}
		}
		if ($matchHeight) {
			$html = '<div class="block block-items-list ' . $blocksClass . ' do-match-height">' . $html . '</div>';
		} else {
			$html = '<div class="block block-items-list ' . $blocksClass . ' do-mosaic-grid">' . $html . '</div>';
		}
		return $html;

	}

endif; // end of function_exists()


add_shortcode('blocks_grid', 'leafacademy_blocks_grid');

add_filter('manage_grid_block_posts_columns', 'grid_blocks_table_head');

function grid_blocks_table_head($defaults) {

	$defaults['_block_tag'] = 'Tag';
	return $defaults;

}

add_action('manage_grid_block_posts_custom_column', 'grid_blocks_table_content', 10, 2);

function grid_blocks_table_content($column_name, $post_id) {
	if ($column_name == '_block_tag') {
		$tag = get_post_meta($post_id, '_block_tag', true);
		echo $tag;
	}

}

define('LEAFACADEMY_POST_LINK_META_KEY', '_post_link');
define('LEAFACADEMY_POST_LINK_LABEL_META_KEY', '_post_link_label');
define('LEAFACADEMY_POST_LINK_TARGET_KEY', '_post_link_target');
define('LEAFACADEMY_POST_FEED_META_KEY', '_post_feed');
define('LEAFACADEMY_POST_FEED_VIDEO_META_KEY', '_post_feed_video');

//todo bolo nedefinovane ,tak so mto definoval
define('LEAFACADEMY_POST_LINK_TARGET_META_KEY', '_link_target_meta_key');

function leafacademy_metabox_post_meta_boxes() {

	add_meta_box(
			'leafacademy_metabox_post_link', __("Button URL", __TEXTDOMAIN__), 'leafacademy_metabox_post_link_callback', 'post', 'normal', 'low'
	);
	add_meta_box(
			'leafacademy_metabox_post_link_label', __("Button label", __TEXTDOMAIN__), 'leafacademy_metabox_post_link_label_callback', 'post', 'normal', 'low'
	);
	add_meta_box(
			'leafacademy_metabox_post_link_target', __("Button options", __TEXTDOMAIN__), 'leafacademy_metabox_post_link_target_callback', 'post', 'normal', 'low'
	);

	array_map(function ($postType) {
		add_meta_box(
				'leafacademy_metabox_post_feed', __("Show in news feed", __TEXTDOMAIN__), 'leafacademy_metabox_post_feed_callback', $postType, 'normal', 'low'
		);
	}, array("post", LFA_POST_TYPE_BLOG_ARTICLE));

}

add_action('add_meta_boxes', 'leafacademy_metabox_post_meta_boxes');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function leafacademy_metabox_post_link_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_post_link', 'leafacademy_metabox_post_link_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_META_KEY, true);

	echo '<input type="url" name="_post_link" class="url  widefat large" id="_post_link" value="' . esc_attr($value) . '">';
	//echo '<p class="description">'.__("Button link.", __TEXTDOMAIN__).'</p>';

}

function leafacademy_metabox_post_link_label_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_post_link_label', 'leafacademy_metabox_post_link_label_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_LABEL_META_KEY, true);

	echo '<input type="text" name="_post_link_label" class="url  widefat large" id="_post_link_label" value="' . esc_attr($value) . '">';
	//echo '<p class="description">'.__("Button label.", __TEXTDOMAIN__).'</p>';

}

function leafacademy_metabox_post_link_target_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_post_link_target', 'leafacademy_metabox_post_link_target_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_POST_LINK_TARGET_META_KEY, true);

	echo '<input type="hidden"  name="_post_link_target" class="" value="0" />';
	echo '<input type="checkbox"  name="_post_link_target" class="" value="1" id="_post_link_target" ' . ($value == 1 ? 'checked="checked"' : '') . ' />';
	echo '<label for="_post_link_target">Open in new window?</label>';

}

function leafacademy_metabox_post_feed_callback($post) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_post_feed', 'leafacademy_metabox_post_feed_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_POST_FEED_META_KEY, true);

	echo '<input type="hidden"  name="_post_feed" class="" value="0" />';
	echo '<input type="checkbox"  name="_post_feed" class="" value="1" id="_post_feed" ' . ($value == 1 ? 'checked="checked"' : '') . ' />';
	echo '<label for="_post_feed">Show in news feed carousel on homepage?</label>';


	// Add an nonce field so we can check for it later.
	wp_nonce_field('leafacademy_metabox_post_feed_video', 'leafacademy_metabox_post_feed_video_nonce');

	$value = get_post_meta($post->ID, LEAFACADEMY_POST_FEED_VIDEO_META_KEY, true);

	echo '<br /><label for="_post_feed_video">Feed video (Youtube URL):</label>';
	echo '<input type="text"  name="_post_feed_video" class="" style="width:400px;" value="' . $value . '" id="_post_feed_video"  />';

	if (!empty($value)) {
		$videoId = get_youtube_video_id($value);
		if ($videoId) {
			echo '<br /><iframe width="320" height="180" src="https://www.youtube.com/embed/' . $videoId . '?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';
		}
	}

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function leafacademy_metabox_post_link_save_date($post_id) {

	//IHA: rusim verifikaciu nonces, pretoze jeden metabox sa pouziva aj pri blog articles a potom ot nefunguje :D
	// Check if our nonce is set.
	/* if ( ! isset( $_POST['leafacademy_metabox_post_link_nonce'] )
	  || ! isset( $_POST['leafacademy_metabox_post_link_label_nonce'] )
	  || ! isset( $_POST['leafacademy_metabox_post_link_target_nonce'] )
	  || ! isset( $_POST['leafacademy_metabox_post_feed_nonce'] )
	  || ! isset( $_POST['leafacademy_metabox_post_feed_video_nonce'] )) {
	  return;
	  }

	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_post_link_nonce'], 'leafacademy_metabox_post_link' ) ) {
	  return;
	  }
	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_post_link_label_nonce'], 'leafacademy_metabox_post_link_label' ) ) {
	  return;
	  }
	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_post_link_target_nonce'], 'leafacademy_metabox_post_link_target' ) ) {
	  return;
	  }
	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_post_feed_nonce'], 'leafacademy_metabox_post_feed' ) ) {
	  return;
	  }
	  // Verify that the nonce is valid.
	  if ( ! wp_verify_nonce( $_POST['leafacademy_metabox_post_feed_video_nonce'], 'leafacademy_metabox_post_feed_video' ) ) {
	  return;
	  } */

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Check the user's permissions.
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	/* OK, it's safe for us to save the data now. */

	// Sanitize user input.
	$link = isset($_POST["_post_link"]) ? $_POST["_post_link"] : "";
	$linkLabel = isset($_POST["_post_link_label"]) ? $_POST["_post_link_label"] : "";
	$linkTarget = isset($_POST["_post_link_target"]) ? $_POST["_post_link_target"] : "";
	$feed = isset($_POST["_post_feed"]) ? $_POST["_post_feed"] : "";
	$feedVideo = isset($_POST["_post_feed_video"]) ? $_POST["_post_feed_video"] : "";

	// Update the meta field in the database.
	update_post_meta($post_id, LEAFACADEMY_POST_LINK_META_KEY, $link);
	update_post_meta($post_id, LEAFACADEMY_POST_LINK_LABEL_META_KEY, $linkLabel);
	update_post_meta($post_id, LEAFACADEMY_POST_LINK_TARGET_META_KEY, $linkTarget);
	update_post_meta($post_id, LEAFACADEMY_POST_FEED_META_KEY, $feed);
	update_post_meta($post_id, LEAFACADEMY_POST_FEED_VIDEO_META_KEY, $feedVideo);

}

add_action('save_post', 'leafacademy_metabox_post_link_save_date');

function lfa_wpdocs_excerpt_more($more) {

	return '…';

}

add_filter('excerpt_more', 'lfa_wpdocs_excerpt_more');

add_filter('acf/settings/save_json', 'lfa_save_acf_json');
add_filter('acf/settings/load_json', 'lfa_load_acf_json');

/**
 * Save ACF fields
 *
 * @param $path
 *
 * @return string
 */
function lfa_save_acf_json($path) {

	$path = get_stylesheet_directory() . '/acf-settings/';
	return $path;

}

/**
 * Load ACF fields
 *
 * @param $paths
 *
 * @return array
 */
function lfa_load_acf_json($paths) {

	unset($paths[0]);
	$paths[] = get_stylesheet_directory() . '/acf-settings/';
	return $paths;

}
