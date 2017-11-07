<?php

//custom schvalovacie interface blogovych clankov pre "nie bloggerov"
//ak ide "nie blogger" pridavat clanok, ma tam klasicke wordpressovske submit buttony
//ak ho ide editovat, ma tam uz len schvalovacie buttony
//povodne buttony su skryvanr cez cssko
add_action( 'add_meta_boxes', function () {

    $screen = get_current_screen();

    if($screen instanceof WP_Screen) {
        if($screen->action === "add" && $screen->post_type === LFA_POST_TYPE_BLOG_ARTICLE) {
            return false;
        }
    }

    if(lfa_current_user_is_blogger()) {
        return false;
    }

    add_meta_box(
        'lfa_custom_metabox_admin_approve_reject_blog',
        __( "Review controls", __TEXTDOMAIN__ ),
        'lfa_custom_metabox_admin_approve_reject_blog_cb',
        LFA_POST_TYPE_BLOG_ARTICLE,
        'side',
        'high'
    );
} );

/**
 * @param $post WP_Post
 */
function lfa_custom_metabox_admin_approve_reject_blog_cb( $post ) {

    $author = new LFA_Author($post->post_author);
    $article = new Blog_Article($post);

    if($article->lastRevisionIsPendingReview()) : ?>

        <div id="submitpost" class="submitbox">
            <div class="publishing-action">
                <span style="margin-top: 6px;">Save article:</span>
                <input data-id="save-btn" name="save" type="submit" class="button default button-large blogger-publish-btn" value="Save">
                <div style="clear: both"></div>
            </div>
            <div class="publishing-action">
                <span>Approve revision:</span>
                <button type="button" data-action="approve-revision" class="button button-primary button-large blogger-publish-btn">Approve</button>
                <div style="clear: both"></div>
            </div>
            <div class="publishing-action">
                <div class="admin-blog-rejection-area-header">
                    <span>Reject revision:</span>
                    <button style="float: right" data-action="reject-revision" type="button" class="button button-primary delete admin-blog-article-reject-btn wp-red-btn-style">Reject</button>
                    <div style="clear: both"></div>
                </div>
                <div class="admin-blog-rejection-area">
                    <div class="admin-blog-rejection-area-title">Please, give <em><?php echo esc_attr($author->getFirstName());?></em> a reason, why you are rejecting this article:</div>
                    <div class="admin-blog-rejection-area-reason-wrapper">
                        <textarea class="admin-blog-rejection-area-reason-input" placeholder="I need to reject this article because of..."></textarea>
                    </div>
                </div>
                <div class="admin-blog-rejection-area-footer">
                    <button class="button primary-button" data-action="cancel-rejection" type="button">Cancel</button>
                    &nbsp;
                    <input data-action="to-reject" name="save" type="submit" class="button blogger-publish-btn wp-red-btn-style" value="Reject">
                </div>
            </div>
        </div>

        <script>
            jQuery(function ($) {

                (function () {

                    $('[data-action="to-reject"]').click(function () {
                        var $btn = $(this);
                        var $form = $btn.closest("form");
                        var $reason = $(".admin-blog-rejection-area-reason-input");

                        if($reason.val().length < 1) {
                            alert("Please, give a reason for your rejection.");
                            $reason.focus();
                            return false;
                        }

                        $reason.attr("name", "articleRejection");

                        $('[data-id="save-btn"]').click();
                    });

                    $('[data-action="cancel-rejection"]').click(function () {
                        var $header = $(".admin-blog-rejection-area-header");
                        var $area = $(".admin-blog-rejection-area");
                        var $footer = $(".admin-blog-rejection-area-footer");

                        $area.hide().find("textarea").val("");
                        $header.show();
                        $footer.hide();
                    });

                    $('[data-action="reject-revision"]').click(function ()  {
                        var $header = $(".admin-blog-rejection-area-header");
                        var $area = $(".admin-blog-rejection-area");
                        var $footer = $(".admin-blog-rejection-area-footer");

                        $area.show().find("textarea").focus();
                        $header.hide();
                        $footer.show();
                    });

                    $('[data-action="approve-revision"]').click(function (e) {
                        e.preventDefault();
                        var $btn = $(this);
                        var $form = $btn.closest("form");

                        $form.append('<input type="hidden" name="articleApproval" value="1" />');

                        $('[data-id="save-btn"]').click();
                    });
                })();

            });
        </script>

    <?php elseif ($article->lastRevisionIsApproved()) : ?>
        <div class="admin-message-in-blogger-article">You have approved the last revision from the author.</div>
        <div id="submitpost" class="submitbox">
            <div class="preview-action">
                <span style="margin-top: 6px;">Save article:</span>
                <span class="just-submit-admin-button">
                    <?php submit_button("Save article", "primary", "submit", false);?>
                </span>
                <div style="clear: both"></div>
                <div class="just-save-article-desc">Saves the article without rejecting or approving, as well as without notifying the author.</div>
            </div>
        </div>
    <?php elseif ($article->lastRevisionIsRejected()) : ?>
        <div class="admin-message-in-blogger-article">You have rejected the last revision from the author.</div>
        <div id="submitpost" class="submitbox">
            <div class="preview-action">
                <span style="margin-top: 6px;">Save article:</span>
                <span class="just-submit-admin-button">
                    <?php submit_button("Save article", "primary", "submit", false);?>
                </span>
                <div style="clear: both"></div>
                <div class="just-save-article-desc">Saves the article without rejecting or approving, as well as without notifying the author.</div>
            </div>
        </div>
    <?php endif;?>

    <?php
}

add_action( 'save_post', function ($post_id) {

    if(lfa_current_user_is_blogger()) {
        return false;
    }

    if(LFA_POST_TYPE_BLOG_ARTICLE !== get_post_type($post_id)) {
        return false;
    }

    if("publish" !== get_post_status( $post_id )) {
        return false;
    }

    if(defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return false;
    }

    $blogArticle = new Blog_Article(get_post($post_id));

    if(isset($_POST["articleRejection"]) && mb_strlen($_POST["articleRejection"])) {
        $reason = trim($_POST["articleRejection"]);

        if($blogArticle->pushLastRevisionForRejection($reason)) {
            lfa_send_mail_blogger_article_rejected($blogArticle->post, $reason);
        }
    } else {

        if(isset($_POST["articleApproval"])) {
            if($blogArticle->pushLastRevisionAsApproved()) {
                lfa_send_mail_blogger_article_approved($blogArticle->post);
            }
        }
    }


}, 10, 1 );