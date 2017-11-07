<?php

function lfa_theme_is_deactivated () {
    remove_role(LFA_USER_ROLE_BLOGGER);
}
add_action('switch_theme', 'lfa_theme_is_deactivated');


function lfa_theme_is_activated () {
    add_role(LFA_USER_ROLE_BLOGGER, __("Blogger"), array(
        "read" => true,
        "level_0" => true,
        "level_1" => true,
        "upload_files" => true
    ));

    $role = get_role(LFA_USER_ROLE_BLOGGER);
    $role->add_cap("edit_article");
    $role->add_cap("edit_articles");
    $role->add_cap("delete_article");
    $role->add_cap("delete_articles");
    $role->add_cap("publish_articles");

    $adminRole = get_role("administrator");
    $adminRole->add_cap("edit_article");
    $adminRole->add_cap("read_article");
    $adminRole->add_cap("delete_article");
    $adminRole->add_cap("delete_articles");
    $adminRole->add_cap("edit_others_articles");
    $adminRole->add_cap("publish_articles");
    $adminRole->add_cap("read_private_articles");
    $adminRole->add_cap("edit_articles");
}
add_action('after_switch_theme', 'lfa_theme_is_activated');

if(isset($_GET["after_switch_theme"]) && $_GET["after_switch_theme"]) {
    add_action("init", "lfa_theme_is_activated");
}