<?php

class LA_PressKitShortcode {

	const SHORTCODE_NAME = 'leaf-academy-logos';

	public static function init() {

		add_shortcode(LA_PressKitShortcode::SHORTCODE_NAME, array(__CLASS__, 'render'), 10, 2);
	}

	public static function render($atts = array(), $content = "") {

		$content = '';

		$attributesMerged = shortcode_atts(array(), $atts, LA_PressKitShortcode::SHORTCODE_NAME);

		$stylesheetUri = get_stylesheet_directory_uri();
		$logosFolderPath = $stylesheetUri . '/images/logos/';
		$content .= '<div class="pure-g presskit">';
		$content .= '<h3>' . __('Logos and press kit', __TEXTDOMAIN__) . '</h3>';

		$content .= '<div class="pure-u-sm-1-2 logo-part">';
		$content .= '<div class="logo-wrap">';
		$content .= '<img src="' . $logosFolderPath . 'leaf-academy-logo-small.png" />';
		$content .= '</div>';
		$content .= '<a href="' . $logosFolderPath . 'leaf-academy-logo-full.jpg" title="">JPG</a>';
		$content .= '<a href="' . $logosFolderPath . 'leaf-academy-logo-full.png" title="">PNG</a>';
		$content .= '</div>';

		$content .= '<div class="pure-u-sm-1-2 logo-part">';
		$content .= '<div class="logo-wrap logo-wrap-inverted">';
		$content .= '<img src="' . $logosFolderPath . 'leaf-academy-logo-white-small.png" />';
		$content .= '</div>';
		$content .= '<a href="' . $logosFolderPath . 'leaf-academy-logo-white-full.jpg" title="">JPG</a>';
		$content .= '<a href="' . $logosFolderPath . 'leaf-academy-logo-white-full.png" title="">PNG</a>';
		$content .= '</div>';

		$content .= '</div><!-- /.pure-g -->';

		return $content;
	}
}