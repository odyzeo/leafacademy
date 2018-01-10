<?php

/**
 * Popup manager is responsible for everything to do with popups shown
 * automatically to new and returning users.
 */
class PopupManager {

	const LAST_VISIT_COOKIE_NAME = 'la_last_visit';
	const FIRST_VISIT_COOKIE_NAME = 'la_first_visit';

	private static $contentFieldName = NULL;

	public static function init() {

		add_action('wp_loaded', array(__CLASS__, 'checkTheVisitorCookie'), 10, 0);
		add_action('wp_footer', array(__CLASS__, 'onWpFooter'));

	}

	public static function checkTheVisitorCookie() {

		$firstVisitTime = filter_has_var(INPUT_COOKIE, self::FIRST_VISIT_COOKIE_NAME) ? filter_input(INPUT_COOKIE, self::FIRST_VISIT_COOKIE_NAME) : NULL;
		$lastVisitTime = filter_has_var(INPUT_COOKIE, self::LAST_VISIT_COOKIE_NAME) ? filter_input(INPUT_COOKIE, self::LAST_VISIT_COOKIE_NAME) : NULL;

		$host = parse_url(get_option('siteurl'), PHP_URL_HOST);

		setcookie(self::LAST_VISIT_COOKIE_NAME, $currentTimestamp, strtotime('+1 month'), '/', $host);
		if ($firstVisitTime === NULL) {
			setcookie(self::FIRST_VISIT_COOKIE_NAME, current_time('timestamp', true), strtotime('+1 year'), '/', $host);
		}

		self::$contentFieldName = 'la_popup_returning_visitor';
		$currentTimestamp = current_time('timestamp', true);
		if ($lastVisitTime === NULL || date('Ymd', $firstVisitTime) === date('Ymd', $currentTimestamp)) {
			self::$contentFieldName = 'la_popup_new_visitor';
		}

	}

	public static function onWpFooter() {

		if (self::$contentFieldName === NULL) {
			return;
		}

		//	take excluded pages into account
		$shouldPopupRender = TRUE;

		if (is_page()) {

			$excludedPages = get_field('la_popup_excluded_pages', intval(get_option('page_on_front')));
			if (is_array($excludedPages) && in_array(get_the_ID(), $excludedPages)) {
				$shouldPopupRender = FALSE;
			}
		}

		if (!$shouldPopupRender) {
			return;
		}

		echo '<div id="floating-popup" class="hidden">';
		echo '<a href="#" class="close-btn"></a>';
		the_field(self::$contentFieldName, intval(get_option('page_on_front')));
		echo '</div>';
		?>

		<script type="text/javascript">
			jQuery.noConflict();
			(function($) {
				$(function() {

					$(document).ready(function() {

						if (typeof window.la_popup_closed_by_user === 'undefined') {
							window.la_popup_closed_by_user = false;
						}

						function scrollTrackPageview() {

							if (window.la_popup_closed_by_user) {
								return;
							}

							var scrollOffset = $(window).scrollTop();
							if (scrollOffset > 100) {
								$('#floating-popup').removeClass('hidden');
							} else {
								$('#floating-popup').addClass('hidden');
							}
						}

						scrollTrackPageview();
						$(window).on("scroll", scrollTrackPageview);

						$('#floating-popup').on('click', 'a.close-btn', function() {

							$('#floating-popup').addClass('hidden');
							window.la_popup_closed_by_user = true;
							return false;
						});
					});

				});

			})(jQuery);
		</script>

		<?php

	}

}
