/*-------------------------------------------*/
/*--global variables
 /*--*/
window.screenXS = 480;
window.screenSM = 768;
window.screenMD = 992;
window.screenLG = 1200;
/*-------------------------------------------*/
(function ($) {
	$(document).ready(function(){
		/*-------------------------------------------*/
		/*--vars
		/*--*/
		var $elResponsiveMenu = $('#header .nav > .menu');
		/*-------------------------------------------*/
		/*--true window dimensions
		/*--*/
		function viewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}
		/*-------------------------------------------*/
		/*--scrool functionality
		/*--*/
		$('a[href*=#]:not([href=#])').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname){
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top - $('#header').outerHeight()
					}, 500);
					return false;
				}
			}
		});
		/*-------------------------------------------*/
		/*--sticky element
		/*--*/
		function fnSickyElement(el,cl){
			var $el = $(el),
				$class = cl+'placeholder';
			if($('.'+$class).length==0){
				$el.before('<div class="'+$class+'"></div>');
			}
		}
		/*-------------------------------------------*/
		/*--header scroll
		/*--*/
		function fnHeaderScroll(){
			var $el = $('body'),
				$className = 'header-mini',
				$scrollTop = $(window).scrollTop();
			if($scrollTop<1){
				$el.removeClass($className);
			}else{
				$el.addClass($className);
			}
		}
		/*-------------------------------------------*/
		/*--responsive menu
		/*--*/
		function fnResponsiveMenu(){
			if(viewport()['width']>screenLG){
				$elResponsiveMenu.show();
			}else{
				$elResponsiveMenu.hide();
			}
		}
		$('#header .nav .nav-toggle').click(function(event){
			event.preventDefault();
			$elResponsiveMenu.fadeToggle('fast');
		});
		/*-------------------------------------------*/
		/*--do same height
		/*--*/
		function fnMatchHeightBreakpoint(){
			var $el = $('.do-match-height').children();
			if(viewport()['width']>screenLG){
				$el.matchHeight();
			}else{
				$el.matchHeight('remove');
			}
		}
		/*-------------------------------------------*/
		/*--search
		/*--*/
		$('.search input').each(function(){
			var $par = $(this).parent('.search');
			$(this).focus(function(){
				$par.addClass('active');
			});
			$(this).blur(function(){
				$par.removeClass('active');
				$(this).val('');
			});
		});
		/*-------------------------------------------*/
		/*--triggers
		/*--*/
		function fnInit(){
			fnHeaderScroll();
			fnSickyElement('#header','header');
			fnMatchHeightBreakpoint();
		}
		fnInit();
		$(window).load(function(){
			fnInit();
		});
		$(window).scroll(function(){
			fnHeaderScroll();
			fnSickyElement('#header','header');
		});
		$(window).resize(function(){
			fnSickyElement('#header','header');
			fnMatchHeightBreakpoint();
			fnResponsiveMenu();
		});
		/*-------------------------------------------*/
	});
})(jQuery);