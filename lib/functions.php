<?php

//v templatoch, ktore su "blogove" toto treba nastvit na true (funkciou lfa_blog_set_blog_section_as_being_viewed())
//aby sa spravne selectli menu polozky
global $lfa_blog_section_is_being_viewed;
$lfa_blog_section_is_being_viewed = false;

function lfa_current_user_is_blogger() {
    global $current_user;
    return in_array(LFA_USER_ROLE_BLOGGER, $current_user->roles, true);
}


/**
 * @return WP_Query|WP_Post[]
 */
function lfa_get_blog_articles() {

    $args = array(
        'post_type' => LFA_POST_TYPE_BLOG_ARTICLE,
        "nopaging" => true,
        "meta_key" => "_once_approved"
    );

    return new WP_Query( $args );
}


/**
 * @return WP_Query|WP_Post[]
 */
function lfa_get_author_articles($authorId, $perPage) {

    $args = array(
        'post_type' => LFA_POST_TYPE_BLOG_ARTICLE,
        "author" => $authorId,
        "posts_per_page" => $perPage,
        "paged" => get_query_var('paged') ? get_query_var('paged') : 1,
        "meta_key" => "_once_approved"
    );

    return new WP_Query( $args );
}


/**
 * @return array|LFA_Author[]
 */
function lfa_get_authors() {
    $user_query = new WP_User_Query( array(
        "role" => LFA_USER_ROLE_BLOGGER,
        'meta_key' => 'last_name',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    ) );

    $results = array();

    foreach($user_query->get_results() as $user) {
        $author =new LFA_Author($user);

        if(!$author->isEnabled()) {
            continue;
        }

        $results[] = $author;
    }

    return $results;
}


function lfa_blog_get_available_categories() {
    return get_terms( LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY, array(
        'hide_empty' => true,
    ) );
}


function lfa_blog_is_taxonomy_category() {
    return is_tax(LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY);
}

function lfa_blog_get_current_taxonomy_category_id() {
    if(!lfa_blog_is_taxonomy_category()) {
        return null;
    }

    $term = get_queried_object();

    if(!is_object($term)) {
        return null;
    }

    return $term->term_id;
}

function lfa_blog_get_post_to_category_map() {
    $data = array();

    $cats = lfa_blog_get_available_categories();

    foreach($cats as $cat) {
        $data[$cat->term_id] = array();

        $pages = get_posts(array(
            'post_type' => LFA_POST_TYPE_BLOG_ARTICLE,
            'numberposts' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY,
                    'field' => 'id',
                    'terms' => $cat->term_id,
                    'include_children' => false
                )
            )
        ));

        if($pages) {
            foreach($pages as $page) {
                $data[$cat->term_id][] = $page->ID;
            }
        }
    }

    return $data;
}


function lfa_blog_get_post_categories($id) {
    $data = get_the_terms($id, LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY);

    if(!$data) {
        return array();
    }

    return $data;
}


function lfa_is_blog_section_viewed() {
    global $lfa_blog_section_is_being_viewed;
    return $lfa_blog_section_is_being_viewed;
}

function lfa_blog_set_blog_section_as_being_viewed() {
    global $lfa_blog_section_is_being_viewed;
    $lfa_blog_section_is_being_viewed = true;
}


function lfa_upload_user_avatar(WP_User $user) {
    if ( ! function_exists( 'wp_handle_upload' ) )
        require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $avatar = wp_handle_upload( $_FILES['avatar'], array('test_form' => FALSE));

    if($avatar && $avatar["url"]) {
        $meta_value = array(
            "full" => $avatar["url"]
        );

        update_user_meta( $user->ID, 'simple_local_avatar', $meta_value );

        return true;
    }

    return false;
}

function lfa_generateusernamefromemail($email) {
    $uname = explode("@", $email);
    $uname = $uname[0];

    $finalUsername = $uname;
    $finalUsername = sanitize_file_name($finalUsername);

    $i = 0;
    $max = 20;

    while(username_exists($finalUsername)) {

        if($i >= $max) {
            $finalUsername = uniqid();
            break;
        }

        $i++;

        $finalUsername = $uname.$i;
    }

    return $finalUsername;
}


function lfa_get_login_link($backUrl = null) {
    $url = home_url("/blog/login");

    if($backUrl !== null) {
        $url .= mb_strpos($url, "?") === false ? "?" : "&";
        $url .= "back=".urlencode($backUrl);
    }

    return $url;
}


function lfa_get_blog_categories_page() {
    return get_page_by_path("blog/categories");
}