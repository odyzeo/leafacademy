<?php
/**
 * Template Name: Blog/Register
 */

if(is_user_logged_in() && lfa_current_user_is_blogger()) {
    wp_redirect(admin_url());
    exit;
}

add_action("wp_footer", function () {
    ?>
    <script>
        LFABlog.registerForm.submitFormInit();
    </script>
    <?php
}, 900);

lfa_blog_set_blog_section_as_being_viewed();
get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <div id="content" class="site-content corner-fx corner-fx-greengrey" role="main">
            <section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php leafacademy_post_thumbnail(); ?>
                <?php while(have_posts()): the_post();?>
                 
                    <form class="registerform" action="">
                        <fieldset class="registerformfields">
                            <div class="registerformfieldgroup">
                                <input type="email" placeholder="E-mail *" required maxlength="100" class="lfa-blog-field" name="email" data-field />
                            </div>
                            <div class="registerformfieldgroup">
                                <input type="password" id="reg-field-password" placeholder="Password *" required maxlength="100" class="lfa-blog-field" name="password" data-field />
                            </div>
                            <div class="registerformfieldgroup">
                                <input type="password" id="reg-field-confirm" placeholder="Confirm password *" required maxlength="100" class="lfa-blog-field" name="confirm" data-field data-type="password_confirm" data-password-field="reg-field-password" />
                            </div>
                            <div class="registerformfieldgroup">
                                <input type="text" placeholder="First name *" required maxlength="100" class="lfa-blog-field" name="first_name" data-field />
                            </div>
                            <div class="registerformfieldgroup">
                                <input type="text" placeholder="Last name *" required maxlength="100" class="lfa-blog-field" name="last_name" data-field />
                            </div>
                            <div class="registerformfieldgroup">
                                <input type="text" placeholder="Role at LEAF Academy" required maxlength="200" class="lfa-blog-field" name="occupation" data-field />
                            </div>
                            <div class="registerformfieldgroup">
                                <textarea data-type="text" placeholder="About yourself" required name="bio" data-field maxlength="1000" class="lfa-blog-field data-field"></textarea>
                            </div>
                            <div class="registerformsubmitinfo">
                             ** all of the&nbsp;information above may&nbsp;be modified later&nbsp;on.
                            </div>
                            <div class="registervop">
                             <input type="checkbox" class="" id="blog-vop" name="vop" />
                             I agree to the blog rules below
                            </div>
                            <div class="registerformavatarfieldgroup">
                                <input type="file" name="file" class="lfa-blog-field-file" accept="image/*" tabindex="-1" />
                                <button type="button" class="registerformuploadbtn" tabindex="1">
                                    <span class="registerformuploadbtnlabel">Upload avatar</span>
                                    <span class="registerformuploadbtnpreview"></span>
                                </button>
                                <div class="registerformavatarfieldgroupremovephoto">
                                    <button class="registerformavatarfieldgroupremovephotobtn" type="button">Remove</button>
                                </div>
                            </div>
                        </fieldset>
                        <div class="registerformsubmit">
                            <button type="submit" class="btn green-grey loginformsubmitbtn">FINISH REGISTRATION</button>
                        </div>
                       
                    </form>
                    <div class="registerformsuccess">
                        You have been successfully registered. <br>
                        We are&nbsp;processing your request and&nbsp;one of&nbsp;our administrators will review your profile and&nbsp;activate your account.<br>
                        You will receive an email about that.<br>
                        <br>
                        Thank you for your patience.
                    </div>
                    <div class="registration_rules">
                        <?php the_content();?>
                    </div>
                <?php endwhile; ?>
            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>
