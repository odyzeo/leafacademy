<?php

define("LFA_POST_TYPE_BLOG_ARTICLE", "article");
define("LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY", "article-category");
define("LFA_USER_ROLE_BLOGGER", "blogger");
define("LFA_BLOGGER_URL_BASE", "blog/bloggers/");

define('DISALLOW_FILE_EDIT', true);

require_once dirname(__FILE__) . "/lib/vendor/autoload.php";
require_once dirname(__FILE__) . "/lib/mailer.php";
require_once dirname(__FILE__) . "/lib/LFA_Author.php";
require_once dirname(__FILE__) . "/lib/register_post_type.php";
require_once dirname(__FILE__) . "/lib/register_users.php";
require_once dirname(__FILE__) . "/lib/functions.php";
require_once dirname(__FILE__) . "/lib/register_user_profile_fields.php";
require_once dirname(__FILE__) . "/lib/register_assets.php";
require_once dirname(__FILE__) . "/lib/register-meta_boxes.php";
require_once dirname(__FILE__) . "/lib/Blog_Article.php";

//maily z webu budeme odosielat cez php mailer
//je tu taktiez aktivny plugin wp-email-templates, ktory nam ostyluje emaily
add_action('phpmailer_init', function (PHPMailer $mailer) {
    $mailer->IsSMTP();
    $mailer->Host = "smtp.websupport.sk"; // your SMTP server
    $mailer->Username = "blog@leafacademy.eu";
    $mailer->Password = "hIoOdS3OX2";
    $mailer->setFrom($mailer->Username, get_option("blogname"));
    $mailer->Sender = $mailer->Username;
    $mailer->CharSet = "utf-8";
    $mailer->isHTML(true);
    $mailer->SMTPAuth = true;
}, 10, 1);



//ak je to blogger, z admin listungu dame prec quick edit
add_filter('post_row_actions',function ($actions) {

    if(is_admin()) {

        /**
         * @var $screen WP_Screen
         */
        $screen = get_current_screen();

        if($screen instanceof WP_Screen) {
            if($screen->parent_base === "edit" && $screen->post_type === LFA_POST_TYPE_BLOG_ARTICLE) {
                if(isset($actions['inline hide-if-no-js'])) {
                    unset($actions['inline hide-if-no-js']);
                }

                if(isset($actions["view"])) {
                    unset($actions["view"]);
                }
            }
        }
    }
    return $actions;
},10,1);


//este dodoatocna kontrola, aby sa neaktivovany blogger nemohol prihlasit nijako
add_action("admin_init", function () {
    if(lfa_current_user_is_blogger()) {

        $u = new LFA_Author(wp_get_current_user());

        if(!$u->isEnabled()) {
            wp_die("Please, activate your account first.", 403);
        }
    }
}, 100);


//pre bloggera odoberieme metaboxy, ktore su default a nema ich vidiet
add_action("add_meta_boxes", function () {
    if(lfa_current_user_is_blogger()) {
        remove_meta_box('authordiv', LFA_POST_TYPE_BLOG_ARTICLE, 'normal');
        remove_meta_box('revisionsdiv', LFA_POST_TYPE_BLOG_ARTICLE, 'normal');
        remove_meta_box('submitdiv', LFA_POST_TYPE_BLOG_ARTICLE, "side");

        remove_meta_box('leafacademy_metabox_post_feed', LFA_POST_TYPE_BLOG_ARTICLE, "normal");
    }
}, 100);


//submit div odoberieme pri pridavani/editovani blogovych clankov pre kazdeho okrem bloggerov
//ale ak "nie blogger" pridava blogovy clanok, tak tak mu ho tam zobrazime
add_action("current_screen", function ($screen) {

    if(!lfa_current_user_is_blogger()) {
        if($screen->post_type === LFA_POST_TYPE_BLOG_ARTICLE && $screen->base === "post" && $screen->action !== "add") {
            remove_meta_box('submitdiv', LFA_POST_TYPE_BLOG_ARTICLE, 'normal');
        }
    }
});


//mame custom linky, preto si musime urobit aj custom vyznacivanie aktivnych linkov
add_action("wp_head", function () {
    if(lfa_is_blog_section_viewed()) {
        ?>
        <script>
            LFABlog.articles.isBlogSectionBeingViewed = true;
        </script>
        <?php
    }
}, 13);


//user rola blogger nebude mat "Dashboard" link
add_action('admin_menu', function () {
    if(lfa_current_user_is_blogger()) {
        remove_menu_page("index.php");
    }
});


//user role blogger sa defaultne nedostane na dashboard/index.php stranku
add_action("current_screen", function ($screen) {
    if(lfa_current_user_is_blogger()) {
        switch($screen->id) {
            case "dashboard":
                wp_redirect(get_edit_user_link());
                break;
        }
    }
});

//defaultne wordpress nema podporu pre custom template "author-{role}.php", takze to pridame
function lfa_author_role_template($templates = '') {
    $author = get_queried_object();
    $role = $author->roles[0];

    if(!is_array($templates) && !empty($templates)) {
        $templates = locate_template(array("author-{$role}.php", $templates), false);
    } elseif(empty($templates)) {
        $templates = locate_template("author-{$role}.php", false);
    } else {
        $new_template = locate_template(array("author-{$role}.php"));
        if(!empty($new_template)) {
            array_unshift($templates, $new_template);
        }
    }

    return $templates;
}

add_filter('author_template', 'lfa_author_role_template');


//zakazame updaty pluginov, pretoze sme ich upravovali
add_filter('site_transient_update_plugins', function ($value) {

    $pluginsToDisableUpdates = array(
        "advanced-custom-fields/acf.php",
        "dynamic-featured-image/dynamic-featured-image.php",
        "enable-media-replace/enable-media-replace.php",
        "wonderm00ns-simple-facebook-open-graph-tags/wonderm00n-open-graph.php",
        "header-footer/plugin.php",
        "post-types-order/post-types-order.php",
        "relevanssi/relevanssi.php",
        "reveal-template/reveal-template.php",
        "reusable-text-blocks/text-blocks.php",
        "w3-total-cache/w3-total-cache.php",
        "sp-faq/faq.php",
        "email-templates/index.php",
        "ultimate-social-media-plus/ultimate_social_media_icons.php"
    );

    array_map(function ($plugin) use ($value) {
        if(isset($value->response[$plugin])) {
            unset($value->response[$plugin]);
        }
    }, $pluginsToDisableUpdates);

    return $value;
}, 10, 1);


//zrusime hlasku, ktora hovori o update wordpressu
add_action('admin_init', function () {
    remove_action('admin_notices', 'update_nag', 3);
}, 1);


//pre nase custom clanky dame vlastnu dlzku excerptu
add_filter('excerpt_length', function ($length) {
    global $post;

    if(LFA_POST_TYPE_BLOG_ARTICLE === $post->post_type) {
        return 25;
    }

    return $length;
}, 12);


//pre nase custom blog clanky dame custom excerpt
add_filter('excerpt_more', function ($more) {
    global $post;

    if(LFA_POST_TYPE_BLOG_ARTICLE === $post->post_type) {
        return "&hellip;";
    }

    return $more;
}, 12);


//pre rolu blogger budu mat author linky custom format
add_filter("author_link", function ($link, $id) {
    $user = new LFA_Author($id);

    if($user->isBlogger()) {
        return home_url('/') . LFA_BLOGGER_URL_BASE . $user->nickname;
    }

    return $link;
}, 80, 2);


//aby nam fungovali custom linky pre bloggerov, musime pre ne urobit rewrite rules
add_filter('rewrite_rules_array', function ($rules) {
    $newrules = array();
    $newrules[LFA_BLOGGER_URL_BASE . '([^/]+)/page/?([0-9]{1,})/?$'] = 'index.php?author_name=$matches[1]&paged=$matches[2]';
    $newrules[LFA_BLOGGER_URL_BASE . '([^/]+)/?$'] = 'index.php?author_name=$matches[1]';

    $rules = $newrules + $rules;

    return $rules;
});


//tuto je viac akcii
//v administracii vidi rola bologger len post_types, ktorych je sam autorom, cize blogy, media, atd...
//upravime aj query pre author_template, kde wp_query bude queriovat blogove article
add_action('pre_get_posts', function ($query) {

    /**
     * @var $query WP_Query
     */
    if($query->is_author() && $query->is_main_query()) {
        $query->set("post_type", LFA_POST_TYPE_BLOG_ARTICLE);

        if(!$query->is_admin) {
            $query->set("meta_key", "_once_approved");
        }

    } else if($query->is_admin) {
        if(lfa_current_user_is_blogger()) {
            $query->set('author', get_current_user_id());
        }
    }
});


//do zoznamu userov prida stlpec, kolko maju napisanych clankov
add_filter('manage_users_columns', function ($column) {

    $cb = $column["cb"];
    unset($column["cb"]);
    $username = $column["username"];
    unset($column["username"]);

    $column = array_reverse($column);

    $column["is_enabled num"] = "Enabled";
    $column["username"] = $username;
    $column["cb"] = $cb;
    $column = array_reverse($column);

    $column['blog_articles num'] = 'Blog articles';

    return $column;
});
add_filter('manage_users_custom_column', function ($val, $column_name, $user_id) {
    switch($column_name) {
        case 'blog_articles num' :
            $count = count_user_posts($user_id, LFA_POST_TYPE_BLOG_ARTICLE);

            if($count > 0) {
                $url = admin_url("edit.php?author={$user_id}&post_type=" . LFA_POST_TYPE_BLOG_ARTICLE);
                return "<a href='{$url}'>{$count}</a>";
            }

            return $count;
        case "is_enabled num":
            $user = new LFA_Author($user_id);

            if(!$user->isBlogger()) {
                return "-";
            } else if($user->isEnabled()) {
                return "Yes";
            }

            return "<button data-action='enable-user' data-id='{$user_id}' class='button'>Enable</button>";
    }
    return $val;
}, 10, 3);


//admin ajax, enable blogger
add_action('wp_ajax_enable_blogger', function () {

    header("Content-type: application/json, charset=utf-8");

    $uid = (int)$_POST["uid"];

    $user = new LFA_Author($uid);

    if($user) {
        if($user->doEnable()) {
            wp_die(json_encode(array("status" => true)));
        }
    }

    wp_die(json_encode(array("status" => false)));
});


//pre bloggerov v admine dame do body class, pretoze cez css sa skryvaju nejake prvky
add_filter("admin_body_class", function ($classes) {
    $classes .= lfa_current_user_is_blogger() ? " user-role-blogger " : "";

    return $classes;
});


//bloggeri budu moct editovat alebo zobrazit len article, ktorym su sami autorom
add_action("current_screen", function ($screen) {

    /**
     * @var $screen WP_Screen
     */

    if(is_admin() && lfa_current_user_is_blogger()) {
        if($screen instanceof WP_Screen) {
            if($screen->post_type === LFA_POST_TYPE_BLOG_ARTICLE) {
                $postId = (int)$_GET["post"];
                $post = get_post($postId);
                if($post instanceof WP_Post) {
                    if(intval($post->post_author) !== get_current_user_id()) {
                        wp_die(__('Cheatin&#8217; uh?'), 403);
                    }
                }
            }
        }
    }
});


//ked sa clovek odhlasi, tak ho redirectneme na home, je to kvoli blogu
//toto presmerovanie funguje globalne vo wordpresse
add_filter("logout_redirect", function () {
    return home_url();
});


//ajax pre login
//hook je registrovany len pre neprihlasenych userov
add_action("wp_ajax_nopriv_blogger_login", function () {
    $login = mb_strtolower(trim($_POST["login"]));
    $password = $_POST["password"];

    header("Content-type: application/json, charset=utf-8");

    if(filter_var($login, FILTER_VALIDATE_EMAIL) === false) {
        wp_die(json_encode(array("status" => false)));
    }

    $user = get_user_by("email", $login);

    if(!($user instanceof WP_User)) {
        wp_die(json_encode(array("status" => false)));
    }

    if(wp_check_password($password, $user->user_pass, $user->ID)) {
        $credentials = array(
            "user_login" => $user->nickname,
            "user_password" => $password,
            "remember" => true
        );

        $author = new LFA_Author($user);

        if(!$author->isEnabled()) {
            wp_die(json_encode(array("status" => false)));
        }

        $login = wp_signon($credentials);

        if($login instanceof WP_User) {
            wp_die(json_encode(array("status" => true)));
        }
    }

    wp_die(json_encode(array("status" => false)));
});


//ajax pre lost password
//hook je registrovany len pre neprihlasenych userov
add_action("wp_ajax_nopriv_blogger_lost_password", function () {
    $login = mb_strtolower(trim($_POST["email"]));

    $user = get_user_by("email", $login);

    header("Content-type: application/json, charset=utf-8");

    if(!($user instanceof WP_User)) {
        wp_die(json_encode(array("status" => false)));
    }

    $newPassword = wp_generate_password(9, false, false);

    wp_set_password($newPassword, $user->ID);

    lfa_send_mail_lost_password($user, $newPassword);

    wp_die(json_encode(array("status" => true)));
});


//ajax pre registraciu bloggera
//hook je registrovany len pre neprihlasenych userov
add_action("wp_ajax_nopriv_blogger_registration", function () {
    header("Content-type: application/json, charset=utf-8");

    $email = mb_strtolower(trim($_POST["email"]));
    $password = $_POST["password"];
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $occupation = trim($_POST["occupation"]);
    $bio = trim($_POST["bio"]);

    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        wp_die(json_encode(array("status" => false, "reason" => "Please, provide a valid e-mail address.")));
    }

    if(mb_strlen($password) < 5) {
        wp_die(json_encode(array("status" => false, "reason" => "The password has to have at least 5 characters.")));
    }

    if(mb_strlen($first_name) < 1) {
        wp_die(json_encode(array("status" => false, "reason" => "Please, provide your first name.")));
    }

    if(mb_strlen($last_name) < 1) {
        wp_die(json_encode(array("status" => false, "reason" => "Please, provide your last name.")));
    }

    if(email_exists($email)) {
        wp_die(json_encode(array("status" => false, "reason" => "The e-mail address has already been registered. \n\nPlease, try another one.")));
    }

    $userdata = array(
        'user_login' => lfa_generateusernamefromemail($email),
        'user_pass' => $password,
        "user_email" => $email,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "description" => $bio,
        "role" => LFA_USER_ROLE_BLOGGER,
    );

    $user_id = wp_insert_user($userdata);

    if($user_id instanceof WP_Error) {
        wp_die(json_encode(array("status" => false, "reason" => "Unexpected error occurred, please, try again.")));
    }

    $user_id = intval($user_id);
    $user_id = (int)$user_id;

    if($user_id > 1) {
        $user = get_user_by("id", $user_id);

        if($user instanceof WP_User) {
            update_user_meta($user->ID, "_user_position", $occupation);

            lfa_upload_user_avatar($user);
            lfa_send_mail_blogger_registered_to_bloger($user);
            lfa_send_mail_blogger_registered_to_admin($user);

            wp_die(json_encode(array("status" => true)));
        }
    }

    wp_die(json_encode(array("status" => false, "reason" => "Unexpected error occurred. Please, try again.")));
});


//do frontendu si pastneme ajaxurl, kvoli ajax requestom
add_action("wp_head", function () {
    ?>
    <script>
        var ajaxurl = <?php echo json_encode(admin_url('admin-ajax.php'));?>;
    </script>
    <?php
});


//clanky, ktore vytvori blogger musia ist na schvalenie adminovi
//cize ked sa zobrazuje single-article stranka, pred tym sa cekne, ci ten post ma nejaku reviziu
//current_queried_object sa nastavuje kvoli inym castiam wordpressu, co nepracuju s globalnou premennou $post, napr vo wp_title
add_action("wp", function () {

    /**
     * @var $wp_query WP_Query
     */

    global $wp_query, $post;

    if($wp_query->is_singular(LFA_POST_TYPE_BLOG_ARTICLE) && !$wp_query->is_404()) {

        if($wp_query->is_preview()) {
            $wp_query->set_404();
            status_header(404);
            return false;
        }

        if(!($post instanceof WP_Post)) {
            $wp_query->set_404();
            status_header(404);
            return false;
        }

        $article = new Blog_Article($post);

        if(!$article->modifyPostByRevision($post)) {
            $wp_query->set_404();
            status_header(404);
            return false;
        }

        setup_postdata($post);

        $wp_query->post = $post;
        $wp_query->queried_object = $post;
        $wp_query->queried_object_id = $post->ID;
    }
});



//upravime stlpce v zozname blogovych clankov
add_filter( 'manage_article_posts_columns', function ($columns) {
    $columns = array_reverse($columns);
    $cb = null;
    if(isset($columns['cb'])) {
        $cb = $columns['cb'];
        unset($columns['cb']);
    }

    $columns['title2'] = $columns['title'];
    if($cb !== null) {
        $columns['cb'] = $cb;
    }
    $columns = array_reverse($columns);

    unset( $columns['title'] );

    return $columns;
} );
add_action( 'manage_article_posts_custom_column' , function ($column, $post_id) {
    $post = new Blog_Article(get_post($post_id));

    $additional = "";

    if($post->lastRevisionIsRejected()) {
        $additional = " - <span style='color: red'>Rejected</span>";
    } else if ($post->lastRevisionIsPendingReview()) {
        $additional = " - Pending review";
    }

    switch ( $column ) {
        case "title2":
            echo '<strong>
                    <a class="row-title" href="'.get_edit_post_link($post->post->ID).'">'.esc_attr(apply_filters("the_title", $post->post->post_title)).'</a>
                    '.$additional.'
                </strong>';
            break;
    }
}, 10, 2 );



//fix dalsieho pluginu, aby nam nepridaval k clankom meta boxy
add_filter("fb_og_metabox_exclude_types", function ($types) {
    $types[] = LFA_POST_TYPE_BLOG_ARTICLE;

    return $types;
});

//fix dalsieho pluginu, aby nam nepridaval k clankom meta boxy
add_filter("dfi_post_types", function ($types) {

    if(isset($types[LFA_POST_TYPE_BLOG_ARTICLE])) {
        unset($types[LFA_POST_TYPE_BLOG_ARTICLE]);
    }

    return $types;
});


/**
 * vo wordpressovom loope, nech sa pre kazdy WP post objekt typu article nacita pre neho posledna revizia
 * potom funkcie the_title, the_content budu pouzivat udaje z revizie
 * toto je kvoli loopom, clanok v detaile sa upravuje v akcii "wp"
 */
add_action("the_post", function ($post = null, $wp_query = null) {

    /**
     * @var $post WP_Post|null
     * @var $wp_query WP_Query|null
     */

    if($wp_query === null || ($wp_query instanceof WP_Query && !$wp_query->is_main_query())) {
        if($post instanceof WP_Post) {
            if($post->post_type === LFA_POST_TYPE_BLOG_ARTICLE) {
                $article = new Blog_Article($post);
                $article->modifyPostByRevision($post);
            }
        }
    }
});