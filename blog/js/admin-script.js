jQuery(function ($) {
    $(document).on("click", "[data-action='enable-user']", function (e) {
        var item = $(this);
        var uid = item.data("id");

        item.prop("disabled", true).addClass("waiting");

        $.ajax({
            type: "post",
            url : ajaxurl,
            data: {
                action: "enable_blogger",
                uid: uid
            },
            complete: function () {
                item.prop("disabled", "false").removeClass("waiting");
            },
            error: function () {
                alert("There is an internet connection problem. Please, try again.")
            },
            success: function (response) {
                if(response.status) {

                    var ui = $('[data-role="enable-blogger-ui"]');

                    if(ui.length) {
                        ui.remove();
                        alert("Blogger was enabled!");
                    } else {
                        item.parent().html("Yes");
                    }

                } else{
                    alert("Please, try again.")
                }
            }
        });
    });
});

var LFA_ADMIN_SCRIPT = {
    blogger: {
        initSubmitForReview: function () {
            jQuery(function ($) {
                $('[data-action="submit-for-review"]').click(function (e) {

                    if(!confirm("Have you checked everything?")) {
                        return false;
                    }

                    var $btn = $(this);
                    var $form = $btn.closest("form");

                    $form.append('<input type="hidden" name="submitForReview" value="1" />');

                    $form.get(0).submit();
                });
            });
        }
    }
};