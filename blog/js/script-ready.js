jQuery(function ($) {

    var valEmail = function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

    $('[data-action="lfab-show-articles"]').click(function (e) {

        e.preventDefault();

        var $btn = $(this);
        var forceCategory = $btn.data("force-category") === true;

        var categoryID = $btn.data("id");

        if(isNaN(categoryID)) {
            return false;
        }

        if(forceCategory) {
            LFABlog.articles.clearCategories();
        }

        LFABlog.articles.currentPage = 1;

        LFABlog.articles.toggleSelectedCategory(categoryID);
        LFABlog.articles.reloadSelectedCategoriesInDOM();
        LFABlog.articles.reloadArticlesByActiveCategory();

        if(forceCategory) {
            LFABlog.articles.scrollToTop();
        }
    });

    $('[data-action="toggle-toggle-login-lost-password"]').click(function (e) {
        e.preventDefault();

        LFABlog.loginForm.toggleLostPasswordArea();
    });

    $(document).on("click", '[data-action="lfa-blog-prev"]', function (e) {
        LFABlog.articles.pagination.goPrev();
    });

    $(document).on("click", '[data-action="lfa-blog-next"]', function (e) {
        LFABlog.articles.pagination.goNext();
    });

    $(document).on("click", ".registerformuploadbtn", function (e) {
        $(".lfa-blog-field-file").click();
    });

    $(document).on("change", ".lfa-blog-field-file", function (e) {
        LFABlog.registerForm.processChangeAvatarNodeEvent(e);

        LFABlog.registerForm.tryToShowOrHideRemovePhotoBtn();

        LFABlog.registerForm.tryToCreateAvatarPreview();
    });

    $(".registerformavatarfieldgroupremovephotobtn").click(function () {
        LFABlog.registerForm.clearAvatar();
    });

    LFABlog.articles.tryToMarkBlogInMenuAsSelected();
});