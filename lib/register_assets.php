<?php

function lfab_get_asset_path($filename, $type) {
    return get_template_directory()."/blog/{$type}/$filename";
}

function lfab_get_asset_webpath($filename, $type) {
    return get_template_directory_uri()."/blog/{$type}/$filename";
}

add_action("wp_enqueue_scripts", function () {

    wp_enqueue_style("lfab-css-categories", lfab_get_asset_webpath("categories.css", "css"), false, filemtime(lfab_get_asset_path("categories.css", "css")));
    wp_enqueue_style("lfab-css-articles", lfab_get_asset_webpath("articles.css", "css"), false, filemtime(lfab_get_asset_path("articles.css", "css")));
    wp_enqueue_style("lfab-css-author", lfab_get_asset_webpath("author.css", "css"), false, filemtime(lfab_get_asset_path("author.css", "css")));
    wp_enqueue_style("lfab-css-author-articles", lfab_get_asset_webpath("author-articles.css", "css"), false, filemtime(lfab_get_asset_path("author-articles.css", "css")));
    wp_enqueue_style("lfab-css-single-article", lfab_get_asset_webpath("single-article.css", "css"), false, filemtime(lfab_get_asset_path("single-article.css", "css")));

    //include after all components
    wp_enqueue_style("lfab-css-style", lfab_get_asset_webpath("style.css", "css"), false, filemtime(lfab_get_asset_path("style.css", "css")));

    wp_enqueue_script("lfab-js-exif", lfab_get_asset_webpath("exif.js", "js"), false, filemtime(lfab_get_asset_path("exif.js", "js")));
    wp_enqueue_script("lfab-js-functions", lfab_get_asset_webpath("script-functions.js", "js"), array("jquery"), filemtime(lfab_get_asset_path("script-functions.js", "js")));
    wp_enqueue_script("lfab-js-ready", lfab_get_asset_webpath("script-ready.js", "js"), array("jquery"), filemtime(lfab_get_asset_path("script-ready.js", "js")), true);
}, 999);

add_action("admin_enqueue_scripts", function () {
    wp_enqueue_style( 'lfab-admin-style', lfab_get_asset_webpath("admin-style.css", "css"), false, filemtime(lfab_get_asset_path("admin-style.css", "css")) );
    wp_enqueue_script( 'lfab-admin-script', lfab_get_asset_webpath("admin-script.js", "js"), false, filemtime(lfab_get_asset_path("admin-script.js", "js")) );
});