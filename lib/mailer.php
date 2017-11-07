<?php


function lfa_get_blogname() {
    return sprintf("%s Blog", esc_attr(get_option("blogname")));
}


function lfa_blog_send_email($recipient, $subject, $body) {
    return wp_mail($recipient, $subject, $body);
}


function lfa_send_mail_about_blog_article_to_admin(WP_Post $post) {

    $permalink = admin_url("post.php?post={$post->ID}&action=edit");

    $blogname = lfa_get_blogname();

    $body = "
    Hi,
    
    there is a blog article waiting for your review.
    Check it out  <strong><a href='{$permalink}' target='_blank'>here</a></strong>.
    
    {$blogname}";

    $adminEmail = get_option("admin_email");

    return lfa_blog_send_email($adminEmail, "New blog article is pending review", $body);
}


function lfa_send_mail_lost_password(WP_User $user, $newPassword) {

    $blogname = lfa_get_blogname();

    $body = "
    Hi ".esc_attr($user->user_firstname).",
    
    you have requested a new password for your account on <strong>{$blogname}</strong>.
    Your new password is <strong>".esc_attr($newPassword)."</strong>.
    
    
    {$blogname}";

    return lfa_blog_send_email($user->user_email, "Your new password", $body);
}

function lfa_send_mail_blogger_registered_to_bloger(WP_User $user) {

    $sitename = lfa_get_blogname();

    $body = "
    Hi ".esc_attr($user->user_firstname).",
    
    welcome to the <strong>{$sitename}</strong>.
    Your account is&nbsp;being reviewed by&nbsp;our administrators and&nbsp;you are&nbsp;going to&nbsp;be&nbsp;notified, when the&nbsp;review is&nbsp;completed.
    
    
    {$sitename}";

    return lfa_blog_send_email($user->user_email, sprintf("Your account on %s", $sitename), $body);
}

function lfa_send_mail_blogger_registered_to_admin(WP_User $user) {

    $sitename = lfa_get_blogname();
    $editUrl = admin_url("user-edit.php?user_id=".$user->ID);

    $body = "
    Hi there,
    
    <strong>".esc_attr($user->display_name)."</strong> has just registered on <strong>{$sitename}</strong>.
    Please, review the user <strong><a href='{$editUrl}' target='_blank'>here</a></strong>.
    
    
    {$sitename}";

    $adminEmail = get_option("admin_email");

    return lfa_blog_send_email($adminEmail, "New blogger registration", $body);
}

function lfa_send_mail_blogger_account_activated(WP_User $user) {
    $sitename = lfa_get_blogname();

    $url = home_url("/blog/login");
    $adminUrl = lfa_get_login_link(admin_url("/post-new.php?post_type=".LFA_POST_TYPE_BLOG_ARTICLE));

    $body = "
    Hi ".esc_attr($user->user_firstname).",
    
    welcome to the <strong>{$sitename}</strong>.
    
    Your account has been reviewed and successfully activated.
    You can login <strong><a href='{$url}'>here</a></strong> with a password, that you set during registration.
    We are looking forward to your first article. <strong><a href='{$adminUrl}' target='_blank'>Start here</a></strong>.
    
    
    {$sitename}
    ";

    return lfa_blog_send_email($user->user_email, "Your account has been activated", $body);
}


function lfa_send_mail_blogger_article_rejected(WP_Post $post, $reason) {
    $user = get_user_by("id", $post->post_author);

    if(!($user instanceof WP_User)) {
        return false;
    }

    $title = apply_filters("the_title", $post->post_title);

    $sitename = lfa_get_blogname();

    $editLink = lfa_get_login_link(get_edit_post_link($post->ID, ""));

    $body = "
    Hi ".esc_attr($user->user_firstname).",
    
    your article <em>&#8222;<a href='".esc_attr($editLink)."' target='_blank'>{$title}</a>&#8220;</em> on <strong>{$sitename}</strong> has been rejected.
    A reason for the rejection is as follows:
    <pre>".nl2br(esc_attr($reason))."</pre>
    Please, review the article, fix the mentioned issues and submit the article for the review again.
    
    Thank you.
    
    
    {$sitename}";

    return lfa_blog_send_email($user->user_email, "Your article has been rejected", $body);
}


function lfa_send_mail_blogger_article_approved(WP_Post $post) {
    $user = get_user_by("id", $post->post_author);

    if(!($user instanceof WP_User)) {
        return false;
    }

    $permalink = get_permalink($post);

    $sitename = lfa_get_blogname();

    $body = "
    Hi ".esc_attr($user->user_firstname).",
    
    your article on <strong>{$sitename}</strong> has been approved.
    Check it out <strong><a href='{$permalink}' target='_blank'>here</a></strong>.
    
    
    {$sitename}";

    return lfa_blog_send_email($user->user_email, "Your article has been approved", $body);

}