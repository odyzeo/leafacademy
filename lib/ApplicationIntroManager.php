<?php

/**
 * Manages introduction on the "Application Form" page in order to wrap an embedded sign-up form with some extra
 * functionality to make it more user friendly.
 *
 * @since 1.6.6
 * @author Martin Krcho <martin.krcho@gmail.com>
 */
class ApplicationIntroManager {

	const SESSION_VISIT_COOKIE_NAME = 'la_appl_sess_visit';

	public static $showIntro = FALSE;

	public static function init() {

		add_action('wp_loaded', array(__CLASS__, 'checkTheVisitorCookie'), 10, 0);

	}

	public static function checkTheVisitorCookie() {

		$sessionVisitTime = filter_has_var(INPUT_COOKIE, self::SESSION_VISIT_COOKIE_NAME) ? filter_input(INPUT_COOKIE, self::SESSION_VISIT_COOKIE_NAME) : NULL;

		$host = parse_url(get_option('siteurl'), PHP_URL_HOST);
		$currentTimestamp = current_time('timestamp', TRUE);

		if ($sessionVisitTime === NULL) {

			setcookie(self::SESSION_VISIT_COOKIE_NAME, $currentTimestamp, 0, '/', $host);
			self::$showIntro = TRUE;
			return;

		}

		self::$showIntro = FALSE;

	}

}