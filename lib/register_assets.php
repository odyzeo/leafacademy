<?php

function lfab_get_asset_path($filename, $type) {

	return get_template_directory() . "/blog/{$type}/dist/$filename";
}

function lfab_get_asset_webpath($filename, $type) {

	return get_template_directory_uri() . "/blog/{$type}/dist/$filename";
}

add_action("wp_enqueue_scripts", function () {

	wp_enqueue_style("lfab-css-style", lfab_get_asset_webpath("blog.min.css", "css"), FALSE, filemtime(lfab_get_asset_path("blog.min.css", "css")));
	wp_enqueue_script("lfab-js", lfab_get_asset_webpath("blog.min.js", "js"), array("jquery"), filemtime(lfab_get_asset_path("blog.min.js", "js")), TRUE);

}, 999);

add_action("admin_enqueue_scripts", function () {

	wp_enqueue_style('lfab-admin-style', lfab_get_asset_webpath("admin-style.min.css", "css"), FALSE, filemtime(lfab_get_asset_path("admin-style.min.css", "css")));
	wp_enqueue_script('lfab-admin-script', lfab_get_asset_webpath("admin-script.min.js", "js"), FALSE, filemtime(lfab_get_asset_path("admin-script.min.js", "js")));

});