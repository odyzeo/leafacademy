<?php
/**
 * Template Name: Blog/Login
 */

if(is_user_logged_in()) {

    $backUrl = isset($_GET["back"]) ? urldecode($_GET["back"]) : null;

    if($backUrl !== null) {
        header("Location: {$backUrl}", true, 302);
        exit;
    } else {
        if(lfa_current_user_is_blogger()) {
            wp_redirect(admin_url());
            exit;
        }
    }

}

add_action("wp_footer", function () {
    ?>

    <script>
        LFABlog.loginForm.initLogging();
        LFABlog.loginForm.initLostPassword();

        LFABlog.loginForm.backUrl = <?php echo json_encode(isset($_GET["back"]) ? $_GET["back"] : null);?>;
    </script>

    <?php
}, 999);

lfa_blog_set_blog_section_as_being_viewed();
get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <div id="content" class="site-content corner-fx corner-fx-greengrey" role="main">
            <section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php leafacademy_post_thumbnail(); ?>
                <?php while(have_posts()): the_post();?>
                    <form class="loginform" action="" aria-hidden="false">
                        <fieldset class="loginformfields">
                            <div class="loginformfieldgroup">
                                <input autofocus type="email" placeholder="E-mail" required maxlength="100" class="lfa-blog-field" name="email" />
                            </div>
                            <div class="loginformfieldgroup">
                                <input type="password" placeholder="Password" required maxlength="100" class="lfa-blog-field" name="password" />
                            </div>
                            <div class="loginformsubmit">
                                <button type="submit" class="btn green-grey loginformsubmitbtn">LOG IN</button>
                            </div>
                        </fieldset>
                    </form>
                    <form class="lostpasswordform" aria-hidden="true">
                        <fieldset class="lostpasswordformfields">
                            <div class="lostpasswordformfieldgroup">
                                <input type="email" placeholder="Your e-mail" required maxlength="100" class="lfa-blog-field" name="email" />
                            </div>
                            <div class="lostpasswordformsubmit">
                                <button type="submit" class="btn green-grey lostpasswordformsubmitbtn">SEND</button>
                            </div>
                        </fieldset>
                    </form>
                    <div class="lostpasswordwassent">
                        New password was sent to&nbsp;your e-mail address.
                    </div>
                        <ul class="loginformlinks">
                            <li class="loginformlink" aria-hidden="false" id="loginformlink-lost-password">
                                <button class="loginformlinkitem" type="button" data-action="toggle-toggle-login-lost-password">Forgotten password</button>
                            </li>
                            <li class="loginformlink" aria-hidden="true" id="loginformlink-login">
                                <button class="loginformlinkitem" type="button" data-action="toggle-toggle-login-lost-password">Login</button>
                            </li>
                            <li class="loginformlink">
                                <a class="loginformlinkitem" href="/blog/register">Register</a>
                            </li>
                        </ul>
                <?php endwhile; ?>
            </section>
        </div>
    </div>
</div>


<?php get_footer(); ?>
