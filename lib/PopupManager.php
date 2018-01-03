<?php

/**
 * Popup manager is responsible for everything to do with popups shown
 * automatically to new and returning users.
 */
class PopupManager {

	const COOKIE_NAME = 'la_last_visit';

	public static function init() {

		add_action('wp_footer', array(__CLASS__, 'onWpFooter'));

	}

	public static function onWpFooter() {

		$lastVisitTime = filter_has_var(INPUT_COOKIE, self::COOKIE_NAME) ? filter_input(INPUT_COOKIE, self::COOKIE_NAME) : NULL;
		$contentFieldName = ($lastVisitTime === NULL) ? 'la_popup_new_visitor' : 'la_popup_returning_visitor';

		echo '<div id="floating-popup" class="hidden">';
		echo '<a href="#" class="close-btn"></a>';
		the_field($contentFieldName, intval(get_option('page_on_front')));
		echo '</div>';

		$host = parse_url(get_option('siteurl'), PHP_URL_HOST);
		setcookie(self::COOKIE_NAME, current_time('timestamp', true), strtotime('+1 month'), '/', $host);
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
