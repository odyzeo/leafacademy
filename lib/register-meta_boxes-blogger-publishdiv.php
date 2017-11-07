<?php

//custom publish bo pre bloggerov
//zobrazuje sa len bloggerom

add_action( 'add_meta_boxes', function () {

    if(!lfa_current_user_is_blogger()) {
        return false;
    }

    add_meta_box(
        'lfa_custom_metabox_blogger_publishdiv',
        __( "Publishing controls", __TEXTDOMAIN__ ),
        'lfa_custom_metabox_blogger_publishdiv_cb',
        LFA_POST_TYPE_BLOG_ARTICLE,
        'side',
        'high'
    );
} );

/**
 * @param $post WP_Post
 */
function lfa_custom_metabox_blogger_publishdiv_cb( $post ) {

    $article = new Blog_Article($post);

    if($article->lastRevisionIsRejected()) : ?>
        <div class="review-blogger-message">This article was rejected by admin. Please, make corrections and submit for the review again.</div>
    <?php elseif($article->lastRevisionIsPendingReview()) :?>
        <div class="review-blogger-message">Your article is currently under review.</div>
    <?php elseif($article->lastRevisionIsApproved()) :?>
        <div class="review-blogger-message">Great! Your last article update was approved!</div>
    <?php endif;?>

    <div id="submitpost" class="submitbox">
        <div class="publishing-action">
            <span>Save the post:</span>
            <input type="submit" name="publish" id="publish" class="button default button-large blogger-publish-btn" value="Save">
            <div style="clear: both"></div>
        </div>
        <div class="publishing-action">
            <span>Submit for review:</span>
            <input data-action="submit-for-review" type="submit" name="publish" id="publish" class="button button-primary button-large blogger-publish-btn" value="Submit">
            <div style="clear: both"></div>
        </div>
    </div>

    <script>
        LFA_ADMIN_SCRIPT.blogger.initSubmitForReview();
    </script>

    <?php
}

add_action( 'save_post', function ($post_id) {

    if(!isset($_POST["submitForReview"])) {
        return false;
    }

    if(!lfa_current_user_is_blogger()) {
        return false;
    }

    if(LFA_POST_TYPE_BLOG_ARTICLE !== get_post_type($post_id)) {
        return false;
    }

    if(defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return false;
    }

    $blogArticle = new Blog_Article(get_post($post_id));

    if($blogArticle->pushLastRevisionForReview()) {
        lfa_send_mail_about_blog_article_to_admin(get_post($post_id));
    }
}, 10, 1 );