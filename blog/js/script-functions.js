var LFABlog = {
	articles: {
		activeCategories: [],
		articleToCategoryMap: {},
		isBlogSectionBeingViewed: false,
		isBlogSecttionArticleListingBeingViewed: false,
		perPage: 5,
		currentPage: 1,
		postIdsForFilter: [],
		isActiveCategory: function(id) {
			id = parseInt(id);

			if (isNaN(id)) {
				return false;
			}

			return LFABlog.articles.activeCategories.indexOf(id) !== -1;
		},
		clearCategories: function() {
			LFABlog.articles.activeCategories = [];
		},
		doSelectCategory: function(id) {
			if (LFABlog.articles.isActiveCategory(id)) {
				return false;
			}

			LFABlog.articles.activeCategories.push(id);
		},
		doDeselectCategory: function(id) {
			if (!LFABlog.articles.isActiveCategory(id)) {
				return false;
			}

			var categoryIndex = LFABlog.articles.activeCategories.indexOf(id);

			LFABlog.articles.activeCategories.splice(categoryIndex, 1);
		},
		toggleSelectedCategory: function(id) {
			if (LFABlog.articles.isActiveCategory(id)) {
				LFABlog.articles.doDeselectCategory(id);
			} else {
				LFABlog.articles.doSelectCategory(id);
			}
		},
		reloadSelectedCategoriesInDOM: function() {

			jQuery(function($) {
				$(".lfab-category-link").removeClass("active").filter(function() {
					var id = $(this).data("id");
					return LFABlog.articles.isActiveCategory(id);
				}).addClass("active");
			});
		},
		scrollToTop: function() {
			jQuery(function($) {
				$("html, body").animate({
					scrollTop: $(".lfa-blog-category-list").offset().top - ($(window).height() / 4)
				}, {
				});
			});
		},
		reloadArticlesByActiveCategory: function() {

			jQuery(function($) {
				var $items = $(".lfa-blog-article-list-item");
				$items.attr("aria-hidden", "true").removeClass("odd");

				LFABlog.articles.postIdsForFilter = [];

				if (LFABlog.articles.activeCategories.length > 0) {

					var postsToDisplay = [];

					LFABlog.articles.activeCategories.map(function(activeCategory) {
						var categoryPosts = LFABlog.articles.articleToCategoryMap[activeCategory];

						if (Array.isArray(categoryPosts) && categoryPosts.length) {
							categoryPosts.map(function(postId) {
								if (postsToDisplay.indexOf(postId) === -1) {
									postsToDisplay.push(postId);
								}
							});
						}

					});

					$items = $items.filter(function() {
						var $item = $(this);
						var $article = $item.find(".lfab-article-item");
						var $articleId = $article.data("id");

						return postsToDisplay.indexOf($articleId) !== -1;
					});
				}

				$items.each(function() {
					var $item = $(this);
					var $article = $item.find(".lfab-article-item");
					var $articleId = $article.data("id");

					if (LFABlog.articles.postIdsForFilter.indexOf($articleId) === -1) {
						LFABlog.articles.postIdsForFilter.push($articleId);
					}
				});

				LFABlog.articles.showPaginatedArticles();
				LFABlog.articles.pagination.reloadDOM();

				LFABlog.articles.stylizeOddArticles();
			});
		},
		pagination: {
			reloadDOM: function() {
				jQuery(function($) {

					var $baseNode = $(".lfa-blog-footer");

					$baseNode.html("");

					if (LFABlog.articles.pagination.getTotalPages() <= 1) {
						return false;
					}

					var $pagination = $("<div />");
					$pagination.addClass("lfa-blog-pagination");
					$pagination.appendTo($baseNode);

					var $prevBtn = $("<button />");
					$prevBtn.appendTo($pagination);
					$prevBtn.addClass("lfa-blog-pagination-btn").addClass(".lfa-blog-pagination-btn-prev");
					$prevBtn.prop("type", "button").attr("data-action", "lfa-blog-prev").text("Previous");

					if (!LFABlog.articles.pagination.canGoPrev()) {
						$prevBtn.prop("disabled", true);
					}

					var $infoLabel = $("<span />");
					$infoLabel.appendTo($pagination);
					$infoLabel.addClass("lfa-blog-pagination-label");
					$infoLabel.text("Pages: " + LFABlog.articles.currentPage + " " + " of " + LFABlog.articles.pagination.getTotalPages());

					var $nextBtn = $("<button />");
					$nextBtn.appendTo($pagination);
					$prevBtn.addClass("lfa-blog-pagination-btn").addClass(".lfa-blog-pagination-btn-next");
					$nextBtn.prop("type", "button").attr("data-action", "lfa-blog-next").text("Next");

					if (!LFABlog.articles.pagination.canGoNext()) {
						$nextBtn.prop("disabled", true);
					}
				});
			},
			getStart: function() {
				return (LFABlog.articles.currentPage - 1) * LFABlog.articles.perPage;
			},
			getEnd: function() {
				return LFABlog.articles.currentPage * LFABlog.articles.perPage;
			},
			getTotalPages: function() {
				return Math.ceil(LFABlog.articles.postIdsForFilter.length / LFABlog.articles.perPage);
			},
			goPrev: function() {
				if (!LFABlog.articles.pagination.canGoPrev()) {
					return false;
				}

				LFABlog.articles.currentPage--;
				LFABlog.articles.reloadArticlesByActiveCategory();
				LFABlog.articles.scrollToTop();
			},
			goNext: function() {
				if (!LFABlog.articles.pagination.canGoNext()) {
					return false;
				}

				LFABlog.articles.currentPage++;
				LFABlog.articles.reloadArticlesByActiveCategory();
				LFABlog.articles.scrollToTop();
			},
			canGoPrev: function() {
				return LFABlog.articles.currentPage > 1;
			},
			canGoNext: function() {
				return LFABlog.articles.currentPage < LFABlog.articles.pagination.getTotalPages();
			}
		},
		showPaginatedArticles: function() {

			jQuery(function($) {
				var $items = $(".lfa-blog-article-list-item").filter(function() {
					var $item = $(this);
					var $article = $item.find(".lfab-article-item");
					var $articleId = $article.data("id");

					return LFABlog.articles.postIdsForFilter.indexOf($articleId) !== -1;
				});

				$items = $items.slice(LFABlog.articles.pagination.getStart(), LFABlog.articles.pagination.getEnd());

				$items.attr("aria-hidden", "false");
			});
		},
		stylizeOddArticles: function() {
			jQuery(function($) {
				var i = 0;
				$('.lfa-blog-article-list-item[aria-hidden="false"]').each(function() {
					i++;

					if (i % 2) {
						$(this).addClass("odd");
					}
				});
			});
		},
		tryToMarkBlogInMenuAsSelected: function() {

			if (LFABlog.articles.isBlogSectionBeingViewed) {
				jQuery(function($) {
					var $mainItems = $("#header .nav > .menu > .menu-item");

					$mainItems.each(function() {
						var $item = $(this);
						var $link = $item.children("a");

						var linkTitle = $link.text();

						if (typeof linkTitle == "undefined") {
							return false;
						}

						linkTitle = linkTitle.trim().toLowerCase();

						if (linkTitle === "blog") {
							$item.addClass("current-menu-parent").addClass("current-menu-page-parent-blog");
						}
					});
				});
			}

			if (LFABlog.articles.isBlogSecttionArticleListingBeingViewed) {
				jQuery(function($) {
					var $selectedBlogItem = $(".current-menu-page-parent-blog");
					var $selectedBlogItemLink = $selectedBlogItem.children("a");

					if ($selectedBlogItemLink.length) {
						var $scope = $selectedBlogItem.closest(".menu-item");
						var $outElem = $('[href="' + $selectedBlogItemLink.attr("href") + '"]', $scope);
						$outElem.closest(".menu-item").addClass("current-menu-item");
					}
				});
			}
		}
	},
	resizeAuthorPanel: function() {

		jQuery(function($) {

			if (mediaQueryMinWidth(1200)) {
				
				var currentHeight = $(".lfa-author-panel").height();
				var newHeight = $(window).height() - $('#primary-menu').height() - $('#header > .top').height() - visPx(jQuery('#footer'));
				if ($('#wpadminbar').length > 0) {
					newHeight -= $('#wpadminbar').height();
				}

				if (currentHeight !== newHeight) {
					$(".lfa-author-panel").css({
						'height': newHeight + 'px'
					});
				}

			}

		});
	},
	toggleAuthorPanel: function() {

		jQuery(function($) {
			function dooo() {
				if (mediaQueryMinWidth(1200)) {
					$(".lfa-author-panel").appendTo($("nav.nav"));
					LFABlog.resizeAuthorPanel();
				} else {
					if ($('section[id^="post"]').length) {
						$(".lfa-author-panel").prependTo($('section[id^="post"]'));
					} else {
						$(".lfa-author-panel").appendTo($('article[id^="post"]'));
					}
				}
			}

			dooo();

			$(window).load(dooo).resize(dooo);
			$(window).scroll(LFABlog.resizeAuthorPanel);
			$(document).on('header-scroll', function() {
				setTimeout(LFABlog.resizeAuthorPanel, 250);
			});

		});
	},
	setupBlogger: function() {
		jQuery(function($) {
			function dooo() {
				$("section.blogger").css("min-height", $(window).height() / 1.3);
			}

			dooo();

			$(window).load(dooo).resize(dooo);
		});
	},
	lostPasswordAreaShown: false,
	backUrl: null,
	loginForm: {
		toggleLostPasswordArea: function() {
			if (LFABlog.loginForm.lostPasswordAreaShown) {
				LFABlog.loginForm.lostPasswordAreaShown = false;
			} else {
				LFABlog.loginForm.lostPasswordAreaShown = true;
			}

			LFABlog.loginForm.reloadLostPasswordAreaDOM();
		},
		reloadLostPasswordAreaDOM: function() {
			jQuery(function($) {

				if (LFABlog.loginForm.lostPasswordAreaShown) {
					$(".loginform").attr("aria-hidden", "true");
					$("#loginformlink-lost-password").attr("aria-hidden", "true");

					$(".lostpasswordform").attr("aria-hidden", "false").find('[type="email"]').focus();
					$("#loginformlink-login").attr("aria-hidden", "false");
				} else {
					$(".loginform").attr("aria-hidden", "false").find('[type="email"]').focus();
					$("#loginformlink-lost-password").attr("aria-hidden", "false");

					$(".lostpasswordform").attr("aria-hidden", "true");
					$("#loginformlink-login").attr("aria-hidden", 'true');
				}


			});
		},
		initLostPassword: function() {
			jQuery(function($) {
				$(".lostpasswordform").submit(function(e) {
					e.preventDefault();

					var $form = $(this);
					var $fields = $form.find("fieldset");
					var $submitBtn = $form.find('[type="submit"]');

					var $loginField = $fields.find('[type="email"]');
					var login = $loginField.val().trim().toLowerCase();

					if (login.length === 0) {
						alert("Please, enter e-mail address.");
						return false;
					} else if (!validateEmail(login)) {
						alert("Your e-mail is invalid. Please, check it and try again.");
						return false;
					}

					$fields.prop("disabled", true);
					$submitBtn.prop("disabled", true).addClass("btn-loader");

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							email: login,
							action: "blogger_lost_password"
						},
						error: function() {
							alert("Please, check your internet connection and try again.");
						},
						complete: function() {
							$fields.prop("disabled", false);
							$submitBtn.prop("disabled", false).removeClass("btn-loader");
						},
						success: function(response) {
							if (response.status) {
								LFABlog.loginForm.toggleLostPasswordArea();

								$(".loginformlinks").remove();
								var $lostPassword = $(".lostpasswordwassent");

								$lostPassword.fadeIn();
								$("html, body").animate({scrollTop: $lostPassword.offset().top});

								setTimeout(function() {
									$lostPassword.fadeOut();
								}, 10 * 1000);

							} else {
								alert("You may have entered incorrect e-mail. \n\nPlease, try again.");
							}
						}
					});

				}).attr("novalidate", true);
			});
		},
		initLogging: function() {
			jQuery(function($) {
				$(".loginform").submit(function(e) {
					e.preventDefault();

					var $form = $(this);
					var $fields = $form.find("fieldset");
					var $submitBtn = $form.find('[type="submit"]');

					var $loginField = $fields.find('[type="email"]');
					var $passwordField = $fields.find('[type="password"]');

					var login = $loginField.val().trim().toLowerCase();
					var password = $passwordField.val();

					if (login.length === 0) {
						alert("Please, enter e-mail address.");
						return false;
					} else if (!validateEmail(login)) {
						alert("Your e-mail is invalid. Please, check it and try again.");
						return false;
					}

					if (password.length === 0) {
						alert("Please, enter your password.");
						return false;
					}

					$fields.prop("disabled", true);
					$submitBtn.prop("disabled", true).addClass("btn-loader");

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							login: login,
							password: password,
							action: "blogger_login"
						},
						error: function() {
							alert("Please, check your internet connection and try again.");
						},
						complete: function() {
							$fields.prop("disabled", false);
							$submitBtn.prop("disabled", false).removeClass("btn-loader");
						},
						success: function(response) {
							if (response.status) {

								$fields.prop("disabled", true);
								$submitBtn.prop("disabled", true).addClass("btn-loader");

								if (LFABlog.loginForm.backUrl) {
									window.document.location.href = LFABlog.loginForm.backUrl;
								} else {
									window.document.location.href = "/blog";
								}

							} else {
								alert("You may have entered incorrect e-mail or password. \n\nPlease, try again.");
							}
						}
					});

				}).attr("novalidate", true);
			});
		}
	},
	registerForm: {
		processChangeAvatarNodeEvent: function(e) {

			jQuery(function($) {
				var file = e.target.files && e.target.files.length ? e.target.files[0] : null;
				var value = $(e.target).val();

				var $btn = $(".registerformuploadbtn");
				var $btnLabel = $btn.find(".registerformuploadbtnlabel");


				if (value.length) {

					if (file) {
						value = file.name;
					}

					$btnLabel.text(value);
				} else {
					$btnLabel.text("Upload photo");
				}


				if (e.target.files) {

				} else {

				}
			});
		},
		tryToShowOrHideRemovePhotoBtn: function() {

			jQuery(function($) {
				var $node = $(".registerformavatarfieldgroupremovephoto");

				if ($(".lfa-blog-field-file").val().length) {
					$node.show();
				} else {
					$node.hide();
				}
			});
		},
		clearAvatar: function() {
			jQuery(function($) {

				var node = $(".lfa-blog-field-file");

				node.replaceWith(node.clone(false));
				$(".lfa-blog-field-file").trigger("change");
			});
		},
		tryToCreateAvatarPreview: function() {
			jQuery(function($) {
				var $file = $(".lfa-blog-field-file");
				var $fileNode = $file.get(0);

				var $bcgNode = $(".registerformuploadbtnpreview");

				$bcgNode.css("background-image", "none").css("transform", "none");

				if (typeof FileReader != "undefined") {

					if ($fileNode.files.length) {
						
						var reader = new FileReader();
						reader.onload = function(e) {
							$bcgNode.get(0).style.backgroundImage = 'url(' + this.result + ')';
						};
						reader.readAsDataURL($fileNode.files[0]);
						
					}
				}
			});
		},
		submitFormInit: function() {
			jQuery(function($) {
				$(".registerform").submit(function(e) {
					e.preventDefault();

					var $form = $(this);
					var $fieldset = $form.find("fieldset");
					var $fields = $form.find('[data-field]');
					var $submitBtn = $form.find('[type="submit"]');
					var $file = $form.find(".lfa-blog-field-file");

					if (!LFABlog.registerForm.validateForm($fields)) {
						alert("Please, check marked fields and try again");
						return false;
					}
					if (!$('#blog-vop').is(':checked')) {
						alert("You must agree to the blog rules below.");
						return false;
					}

					var email = $form.find('[type="email"]').val().trim().toLowerCase();
					var password = $form.find('[type="password"]').val();
					var first_name = $form.find('[name="first_name"]').val().trim();
					var last_name = $form.find('[name="last_name"]').val().trim();
					var occupation = $form.find('[name="occupation"]').val().trim();
					var bio = $form.find('[name="bio"]').val().trim();

					$fieldset.prop("disabled", true);
					$submitBtn.prop("disabled", true).addClass("btn-loader");

					var ajax = new XMLHttpRequest();
					ajax.open("POST", ajaxurl, true);
					ajax.setRequestHeader("X-Requested-With", "XMLHttpRequest");

					var formData = new FormData;

					formData.append("action", "blogger_registration");
					formData.append("email", email);
					formData.append("password", password);
					formData.append("first_name", first_name);
					formData.append("last_name", last_name);
					formData.append("occupation", occupation);
					formData.append("bio", bio);

					if ($file.length && $file.get(0).files && $file.get(0).files.length) {
						formData.append("avatar", $file.get(0).files[0]);
					}

					var complete = function() {
						$fieldset.prop("disabled", false);
						$submitBtn.prop("disabled", false).removeClass("btn-loader");
					};

					ajax.onerror = function() {
						complete();

						alert("Please, check your internet connection and try again.");
					};

					ajax.onload = function(e) {
						complete();

						var response = false;

						try {
							response = JSON.parse(e.target.responseText);
						} catch (E) {

						}

						if (response.status) {
							$(".registerformsuccess").fadeIn();
							$(".registerform").fadeOut();
						} else {
							alert(response.reason || "Unexpected error occurred. Please, try again.");
						}
					};

					ajax.onabort = function() {
						complete();
					};

					ajax.send(formData);

				}).attr("novalidate", true).find('[data-field]').each(function() {
					var $field = $(this);

					$field.on("input change blur keyup", function(e) {
						LFABlog.registerForm.validateField($field);
					});
				});
			});
		},
		validateForm: function($fields) {

			var $ = jQuery;

			var formValidated = true;

			$fields.each(function() {
				var $field = $(this);
				var fieldValidated = LFABlog.registerForm.validateField($field);

				formValidated = formValidated === true ? fieldValidated : false;
			});

			return formValidated;
		},
		validateField: function($field) {

			var $ = jQuery;

			var value = $field.val();
			var $parent = $field.parent();
			var isRequired = $field.prop("required") === true;
			var type = $field.data("type") || $field.attr("type");

			var fieldValidated = false;

			switch (type) {
				case "email":
					value = value.toLowerCase().trim();

					if (isRequired || (!isRequired && value.length)) {
						fieldValidated = validateEmail(value);
					} else {
						fieldValidated = true;
					}

					break;
				case "text":
					value = value.trim();

					if (isRequired || (!isRequired && value.length)) {
						fieldValidated = value.length > 1;
					} else {
						fieldValidated = true;
					}

					break;
				case "password_confirm":
					var passwordField = $("#" + $field.data("password-field"));
					var passwordFieldValue = passwordField.val();

					fieldValidated = (value.length > 0) && passwordFieldValue === value;
					break;
				case "password":

					fieldValidated = value.length >= 5;

					break;
			}
			$parent.find('.has-error-text').remove();
			if (fieldValidated) {
				$parent.removeClass("has-error");
			} else {
				$parent.append('<span class="has-error-text">This field is mandatory.</span>')
				$parent.addClass("has-error");

			}

			return fieldValidated;
		}
	}
};

function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

window.matchMedia || (window.matchMedia = function() {
	"use strict";
// For browsers that support matchMedium api such as IE 9 and webkit
	var styleMedia = (window.styleMedia || window.media);
// For those that don't support matchMedium
	if (!styleMedia) {
		var style = document.createElement('style'),
				script = document.getElementsByTagName('script')[0],
				info = null;
		style.type = 'text/css';
		style.id = 'matchmediajs-test';
		script.parentNode.insertBefore(style, script);
// 'style.currentStyle' is used by IE <= 8 and 'window.getComputedStyle' for all other browsers
		info = ('getComputedStyle' in window) && window.getComputedStyle(style, null) || style.currentStyle;
		styleMedia = {
			matchMedium: function(media) {
				var text = '@media ' + media + '{ #matchmediajs-test { width: 1px; } }';
// 'style.styleSheet' is used by IE <= 8 and 'style.textContent' for all other browsers
				if (style.styleSheet) {
					style.styleSheet.cssText = text;
				} else {
					style.textContent = text;
				}
// Test if media query is true or false
				return info.width === '1px';
			}
		};
	}
	return function(media) {
		return {
			matches: styleMedia.matchMedium(media || 'all'),
			media: media || 'all'
		};
	};
}());

function mediaQueryMaxWidth(maxWidth) {
	return matchMedia('screen and (max-width: ' + maxWidth + 'px)').matches;
}

function mediaQueryMinWidth(minWidth) {
	return matchMedia('screen and (min-width: ' + minWidth + 'px)').matches;
}

function mediaQueryMaxHeight(maxHeight) {
	return matchMedia('screen and (max-height: ' + maxHeight + 'px)').matches;
}

function scrolledFromBottom(alter) {
	return ($(window).scrollTop() >= $(document).height() - $(window).height() - alter);
}

function visPx(el) {

	var elH = jQuery(el).outerHeight(),
			H = jQuery(window).height(),
			r = el[0].getBoundingClientRect(), t = r.top, b = r.bottom;
	return Math.max(0, t > 0 ? Math.min(elH, H - t) : (b < H ? b : H));

}