/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
(function($) {
	var body = $('body'),
			_window = $(window),
			nav, button, menu;

	nav = $('#primary-navigation');
	button = nav.find('.menu-toggle');
	menu = nav.find('.nav-menu');

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on('hashchange.leafacademy', function() {
		var hash = location.hash.substring(1), element;

		if (!hash) {
			return;
		}

		element = document.getElementById(hash);

		if (element) {
			if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy(0, -80);
		}
	});

	$(function() {

		/*
		 * Fixed header for large screen.
		 * If the header becomes more than 48px tall, unfix the header.
		 *
		 * The callback on the scroll event is only added if there is a header
		 * image and we are not on mobile.
		 */
		if (_window.width() > 781) {
			var mastheadHeight = $('#masthead').height(),
					toolbarOffset, mastheadOffset;

			if (mastheadHeight > 48) {
				body.removeClass('masthead-fixed');
			}

			if (body.is('.header-image')) {
				toolbarOffset = body.is('.admin-bar') ? $('#wpadminbar').height() : 0;
				mastheadOffset = $('#masthead').offset().top - toolbarOffset;

				_window.on('scroll.leafacademy', function() {
					if (_window.scrollTop() > mastheadOffset && mastheadHeight < 49) {
						body.addClass('masthead-fixed');
					} else {
						body.removeClass('masthead-fixed');
					}
				});
			}
		}

		// Focus styles for menus.
		$('.primary-navigation, .secondary-navigation').find('a').on('focus.leafacademy blur.leafacademy', function() {
			$(this).parents().toggleClass('focus');
		});
	});

	/**
	 * @summary Add or remove ARIA attributes.
	 * Uses jQuery's width() function to determine the size of the window and add
	 * the default ARIA attributes for the menu toggle if it's visible.
	 * @since Twenty Fourteen 1.4
	 */
	function onResizeARIA() {
		if (781 > _window.width()) {
			button.attr('aria-expanded', 'false');
			menu.attr('aria-expanded', 'false');
			button.attr('aria-controls', 'primary-menu');
		} else {
			button.removeAttr('aria-expanded');
			menu.removeAttr('aria-expanded');
			button.removeAttr('aria-controls');
		}
	}

	_window
			.on('load.leafacademy', onResizeARIA)
			.on('resize.leafacademy', function() {
				onResizeARIA();
			});

	_window.load(function() {
		// Arrange footer widgets vertically.
		if ($.isFunction($.fn.masonry)) {
			$('#footer-sidebar').masonry({
				itemSelector: '.widget',
				columnWidth: function(containerWidth) {
					return containerWidth / 4;
				},
				gutterWidth: 0,
				isResizable: true,
				isRTL: $('body').is('.rtl')
			});
		}

		// Initialize Featured Content slider.
		if (body.is('.slider')) {
			$('.featured-content').featuredslider({
				selector: '.featured-content-inner > article',
				controlsContainer: '.featured-content'
			});
		}
	});

})(jQuery);


/*-------------------------------------------*/
/*--global variables
 /*--*/
window.screenXS = 480;
window.screenSM = 768;
window.screenMD = 992;
window.screenLG = 1200;
/*-------------------------------------------*/
(function($) {

	$(document).ready(function() {
		/*-------------------------------------------*/
		/*--vars
		 /*--*/
		var $elResponsiveMenu = $('#header .nav > .menu');
		/*-------------------------------------------*/
		/*--true window dimensions
		 /*--*/
		function viewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window)) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return {width: e[ a + 'Width' ], height: e[ a + 'Height' ]};
		}
		/*-------------------------------------------*/
		/*--do background image from 'data-bg-image' attribute or from child image
		 /*--*/
		function fnDoBgImage(el) {
			var $el = el;
			if ($el.find('img').length != 0 || $el.find('*[data-bg-image]').length != 0) {
				var img;
				if ($el.find('*[data-bg-image]').length != 0) {
					img = $el.find('*[data-bg-image]').attr('data-bg-image');
				}
				if ($el.find('img').length != 0) {
					img = $el.find('img').attr('src');
					$el.find('img').hide();
				}
				$el.css('background-image', 'url(' + img + ')');
			}
		}
		$('.do-bg-image').each(function() {
			fnDoBgImage($(this));
		});
		/*-------------------------------------------*/
		/*--read background image from 'data-bg-image' attribute
		 /*--*/
		$('*[data-bg-image]').each(function() {
			var img = $(this).attr('data-bg-image');
			$(this).css('background-image', 'url(' + img + ')');
		});

		/*-------------------------------------------*/
		/*--scrool functionality
		 /*--*/
		function fnSmoothScrollToElement(hash) {
			
			var target = $(hash);
				target = target.length ? target : $('[name=' + hash.slice(1) + ']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top - $('#header').outerHeight() - $('#wpadminbar').outerHeight()
					}, 500);
					return false;
				}
				
		};
		
		$('a[href*=#]:not([href=#]):not([data-supress-scroll])').click(function() {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
				fnSmoothScrollToElement(this.hash);
			}
		});

		function resizeMain() {

			var offset = 0;
			if ($(document).height() > $(window).height()) {
				offset = $('#header').outerHeight();   //lebo ked sa scrollne tak sa zmnis header a uz to neni dost velke
			}
			$('.entry-content').css({minHeight: offset + $(window).height() - /*$('#contact-map').outerHeight()-*/$('#footer').outerHeight() - $('#header').outerHeight() - $('.post-thumbnail').first().height()});

			//mapka ma byt stvorcova
			$('#contact-map').css({height: Math.min(400, $('#contact-map').width())});
		}

		/*-------------------------------------------*/
		/*--sticky element
		 /*--*/
		function fnStickyElement(el, cl) {
			var $el = $(el),
					$class = cl + 'placeholder';
			if ($('.' + $class).length == 0) {
				$el.before('<div class="' + $class + '"></div>');
			}
			/*$('.'+$class).css({height: $el.outerHeight()});*/
		}
		/*-------------------------------------------*/
		/*--header scroll
		 /*--*/
		function fnHeaderScroll() {

			var $el = $('body');
			var $className = 'header-mini';
			var $scrollTop = $(window).scrollTop();

			if ($scrollTop < 1) {

				$el.removeClass($className);
				$(document).trigger('header-scroll');

			} else if (!$el.hasClass($className)) {

				$el.addClass($className);
				$(document).trigger('header-scroll');

			}
		}
		/*-------------------------------------------*/
		/*--responsive menu
		 /*--*/
		function fnResponsiveMenu() {
			if (viewport()['width'] > screenLG) {
				$elResponsiveMenu.show();
			} else {
				$elResponsiveMenu.hide();
				$('#header .nav .nav-toggle').removeClass('open');
				$('header > nav > ul.menu > li.expanded').removeClass('expanded').attr({
					'aria-expanded': 'false'
				});
			}
		}

		$('#header .nav .nav-toggle').click(function(event) {
			event.preventDefault();
			$(this).toggleClass('open');
			$elResponsiveMenu.fadeToggle('fast');
		});

		$('header > nav > ul.menu > li.menu-item-has-children > a').on('click', function() {

			var menuLink = $(this).closest('li');
			var isExpanded = menuLink.hasClass('expanded');

			$(this).closest('ul').find('li.expanded').find('.sub-menu').slideUp();
			$(this).closest('ul').find('li.expanded').removeClass('expanded').attr({
				'aria-expanded': 'false'
			});

			if (!isExpanded) {

				menuLink.addClass('expanded').attr({
					'aria-expanded': 'true'
				});

				menuLink.find('.sub-menu').slideDown();

			}
			return false;
		});

		/*-------------------------------------------*/
		/*--do same height
		 /*--*/
		function fnMatchHeightBreakpoint() {
			var $el = $('.do-match-height').children();
			if (viewport()['width'] > screenMD) {
				$el.matchHeight();
			} else {
				$el.matchHeight('remove');
			}
		}
		/*-------------------------------------------*/
		/*--do mosaic grid
		 /*--*/
		function fnMasonry() {
			$('.do-mosaic-grid').each(function() {
				$('.do-mosaic-grid').imagesLoaded(function() {
					$('.do-mosaic-grid').masonry({
						itemSelector: '.item'
					});
				});
			});
		}

		/*-------------------------------------------*/
		/*--search
		 /*--*/
		$('.search input.search-field').each(function() {
			var $par = $(this).parents('.search');
			$(this).focus(function() {
				$par.addClass('active');
			});
			$(this).blur(function() {
				$par.removeClass('active');
				$(this).val('');
			});
		});

		/*-------------------------------------------*/
		/*--gallery slider
		 /*--*/
		$('.block-gallery .images-wrap').each(function() {
			$(this).slick({
				arrows: true,
				autoplay: false,
				autoplaySpeed: 5000,
				dots: false,
				draggable: true,
				fade: false,
				infinite: true,
				prevArrow: '<a class="slick-dir-nav prev"></a>',
				nextArrow: '<a class="slick-dir-nav next"></a>',
				speed: 500,
				slidesToShow: 1,
				slidesToScroll: 1,
				swipe: true,
				customPaging: function(slider, i) {
					return '<a class="tab"></a>';
				}
			});
		});

		function resizePostThumb(show, $postThumb) {
			if (!$postThumb)
				$postThumb = $('.post-thumbnail');
			$postThumb.css({height: 'auto'});
			$postThumb.each(function() {
				$(this).css({height: $(this).height()});
			});
			if (show)
				$('.post-thumbnail-caption-wrapper-td', $postThumb).fadeTo(300, 1);
		}

		$('.post-thumbnail img').each(function() {
			var $img = $(this);

			if ($img.height() < 10) {
				$img.load(function() {
					resizePostThumb(true, $img.parents('.post-thumbnail'));
				});
			} else {
				resizePostThumb(true, $img.parents('.post-thumbnail'));
			}

		});

		// forms
		$('body').on('focus input', '.field-wrap.label-above.text-wrap input, .field-wrap.label-above.textarea-wrap textarea', function() {
			var $this = $(this);
			$this.parent().addClass('focused');
		});
		$('body').on('blur', '.field-wrap.label-above.text-wrap input, .field-wrap.label-above.textarea-wrap textarea', function() {
			var $this = $(this);
			if ($.trim($this.val()) == "") {
				$this.parent().removeClass('focused');
			}
		});
		$('.field-wrap.label-above.text-wrap input.ninja-forms-field, .field-wrap.label-above.textarea-wrap textarea.ninja-forms-field').each(function() {
			var $this = $(this);
			if ($.trim($this.val()) != "") {
				$this.parent().addClass('focused');
			}
		})

		$('.ninja-forms-form input[type=submit]').wrap('<span class="btn green-grey" />');


		//====================================================
		//  Initialization language switcher
		//====================================================

		if ($('.language-switcher').length > 0) {
			var stretchyNavs = $('.language-switcher');

			stretchyNavs.each(function() {
				var stretchyNav = $(this),
						stretchyNavTrigger = stretchyNav.find('.language-switcher-trigger'),
						stretchyLink = stretchyNav.find('li');


				stretchyNavTrigger.on('click', function(event) {
					stretchyNav.toggleClass('nav-is-visible');
					return false;
				});

				stretchyLink.on('click', function(event) {
					var inst = $(this),
							thisLang = inst.find('a').attr('data-lang');

					$('.language-switcher-trigger').text(thisLang);
					$('.language-switcher li a').each(function() {
						$(this).removeClass('active');
					});
					inst.find('a').addClass('active');
				});
			});

			$(document).on('click', function(event) {
				(!$(event.target).is('.language-switcher-trigger') && !$(event.target).is('.language-switcher-trigger span')) && stretchyNavs.removeClass('nav-is-visible');
			});
		}

		//homepageVideo
		function resizeVideo() {
			var windowHeight = Math.min($(window).height(), 600);
			var windowWidth = $(window).width();
			var videoWidth = 854;
			var videoHeight = 480;
			var videoInnerWidth = 854;
			var videoInnerHeight = 380;
			var videoRatio = videoWidth / videoHeight;
			var videoInnerRatio = videoInnerWidth / videoInnerHeight;
			var resizeCondition = videoInnerRatio < windowWidth / windowHeight;
			var heightScale = videoHeight / videoInnerHeight;

			$('.header').css({height: windowHeight});
			windowHeight = heightScale * windowHeight;  // lebo pasiky vo videu hore dole

			if (resizeCondition) {
				videoWidth = windowWidth;
				videoHeight = videoWidth / videoRatio;
			} else {
				videoHeight = windowHeight;
				videoWidth = videoHeight * videoRatio;
			}

			$('#intro-video').css({height: videoHeight, width: videoWidth, marginTop: -videoHeight / 2, marginLeft: -videoWidth / 2});
		}

		if ($('#intro-video').length) {
			$(window).resize(function() {
				resizeVideo();
			});
			//$(window).resize();  
			resizeVideo();
		}


		//blockVideo
		function resizeBlockVideo() {

			var videoWidth = 854;
			var videoHeight = 480;
			var videoInnerWidth = 854;
			var videoInnerHeight = 380;
			
			$('.block-items-list .item iframe').each(function() {
				var $block = $(this).closest('.item');
				var windowHeight = $block.outerHeight();
				var windowWidth = $block.outerWidth();
				var videoRatio = videoWidth / videoHeight;
				var videoInnerRatio = videoInnerWidth / videoInnerHeight;
				var resizeCondition = videoInnerRatio < windowWidth / windowHeight;
				var heightScale = videoHeight / videoInnerHeight;

				windowHeight = heightScale * windowHeight;  // lebo pasiky vo videu hore dole

				if (resizeCondition) {
					videoWidth = windowWidth;
					videoHeight = videoWidth / videoRatio;
				} else {
					videoHeight = windowHeight;
					videoWidth = videoHeight * videoRatio;
				}
				$block.css({position: 'relative', overflow: 'hidden'});
				$('iframe', $block).css({height: videoHeight, width: videoWidth, marginTop: -videoHeight / 2, marginLeft: -videoWidth / 2, position: 'absolute', top: '50%', left: '50%'});
			});

		}

		if ($('.block-items-list .item iframe').length) {
			$(window).resize(function() {
				resizeBlockVideo();
			});
			resizeBlockVideo();
		}

		/*-------------------------------------------*/
		/*--team
		 /*--*/
		function fnGetHashFromUrl() {
			var $hash = window.location.hash;
			fnTeamAbout($hash);
		}
		function fnGetHashFromHref(el) {
			
			var $hash = el.prop('hash');
			fnTeamAbout($hash);
			
			if (history) {
				history.pushState({}, $hash, $hash);
			}
			
		}
		function fnTeamPlaceholders(nth) {
			$('.block-team .item:nth-child(3n)').after('<div class="placeholder-about"></div>');
			if ($('.block-team .item').length % 3) {
				$('.block-team .items').append('<div class="placeholder-about"></div>');
			}
		}
		function fnTeamResizeFix() {

			$('.placeholder-about').each(function() {
				if (!$(this).hasClass('active') && $(this).css('display') === 'block') {
					$(this).hide();
					$(this).removeClass('active');
				}
			});
			
		}
		
		function fnTeamAbout(hash) {

			var $name = hash.substring(1);
			if ($name.length) {
				
				var $el = $('a[name=' + $name + ']'),
						$par = $el.parent(),
						$sib = $el.parent().siblings('.item'),
						$holds = $par.siblings('.placeholder-about'),
						$hold = $par.nextAll('.placeholder-about').first(),
						$text = $el.siblings('.about').html();

				if (!$par.hasClass('active')) {

					$sib.removeClass('active');
					$par.addClass('active');

				}

				if ($hold.hasClass('active')) {

					$hold.html($text);

				} else {

					$holds.hide().removeClass('active');
					$hold.html($text);
					$hold.show().addClass('active');

				}
				
				fnSmoothScrollToElement(hash);

			}

		}

		$('.block-team .item a.image').click(function(e) {

			if ($(this).data("continue")) {
				return true;
			}

			fnGetHashFromHref($(this));
			return false;

		});

		$(document).on('click', '.block-team .close', function() {
			$('.block-team .active').removeClass('active');
			fnTeamResizeFix();
		});

		/*-------------------------------------------*/
		/*--triggers
		 /*--*/

		function fnInit() {

			fnHeaderScroll();
			fnStickyElement('#header', 'header');
			fnMatchHeightBreakpoint();
			fnMasonry();
			resizeMain();

		}

		$(window).scroll(function() {

			fnHeaderScroll();
			fnStickyElement('#header', 'header');

		});

		$(window).resize(function() {

			fnStickyElement('#header', 'header');
			fnMatchHeightBreakpoint();
			fnTeamResizeFix();
			resizeMain();

		});

		// Listen for orientation changes
		window.addEventListener("orientationchange", function() {
			fnResponsiveMenu();
			resizePostThumb();
		}, false);

		$(window).load(function() {

			fnInit();

			$(window).resize();

			$(document).on('click', '.slider-wrap .read-more', function() {
				$(this).closest('.slider-wrap').toggleClass('expanded');
				return false;

			});

			fnTeamPlaceholders(3);
			fnGetHashFromUrl();

			if ($('body').hasClass('page') && $('.entry-content').length > 0 && $('#la-calendar').length === 0) {

				/* Substitutes Empty Space for $nbsp; in Post Content */
				var oldhtml = $('.entry-content').html();
				var newhtml = oldhtml.replace(/&nbsp;/g, ' ');

				$('.entry-content').html(newhtml);

			}

			var newsListItems = $('.news-list-content .formated-output');
			if (newsListItems.length > 0) {

				newsListItems.each(function() {

					var newsListItem = $(this);

					/* Substitutes Empty Space for $nbsp; in Post Content */
					var oldhtml = newsListItem.html();
					var newhtml = oldhtml.replace(/&nbsp;/g, ' ');

					newsListItem.html(newhtml);

				});
			}


		});

		/*-------------------------------------------*/
	});
})(jQuery);