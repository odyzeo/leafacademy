<?php

function lfab_register_post_types() {
    $args = array(
        'label'  => __("Blog article"),
        "labels" => array(
            'name'  => __("Blog articles"),
            'singular_name'  => __("Blog article"),
            'add_new'  => __("Add new"),
            'add_new_item'  => __("Add new blog article"),
            'edit_item'  => __("Edit blog article"),
            'new_item'  => __("New blog article"),
            'view_item'  => __("View blog article"),
            'view_items'  => __("View blog articles"),
            'search_items'  => __("Search blog articles"),
            'not_found'  => __("No blog articles found."),
            'not_found_in_trash'  => __("No blog articles found in trash."),
            'all_items'  => __("All blog articles"),
            'archives'  => __("Blog articles archive"),
            'attributes'  => __("Blog article attributes"),
            'insert_into_item'  => __("Insert into the blog article"),
            'uploaded_to_this_item'  => __("Uploaded to this article"),
        ),
        "description" => "Create blog articles.",
        "public" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-format-aside",
        "supports" => array("title", "editor", "author", "thumbnail", "excerpt", "revisions"),
        "has_archive" => false,
        'capability_type' => "article",
        "map_meta_cap" => false,
        'taxonomies'          => array( LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY ),
        "rewrite" => array(
            "slug" => "blog/articles"
        )
    );
    register_post_type( LFA_POST_TYPE_BLOG_ARTICLE, $args );
}
add_action( 'init', 'lfab_register_post_types' );



function lfab_register_custom_taxonomies() {
    register_taxonomy(
        LFA_TAXONOMY_BLOG_ARTICLE_CATEGORY,
        LFA_POST_TYPE_BLOG_ARTICLE,
        array(
            'label' => __( 'Category' ),
            "labels" => array(
                'name' => __( 'Categories' ),
                'singular_name' => __( 'Category' ),
                'all_items' => __( 'All categories' ),
                'edit_item' => __( 'Edit category' ),
                'view_item' => __( 'View category' ),
                'update_item' => __( 'Update category' ),
                'add_new_item' => __( 'Add new category' ),
                'new_item_name' => __( 'New category name' ),
                'parent_item' => __( 'Parent category' ),
                'search_items' => __( 'Search categories' ),
                'popular_items' => __( 'Popular categories' ),
                'separate_items_with_commas' => __( 'Separate categories with commas' ),
                'add_or_remove_items' => __( 'Add ore remove categories' ),
                'choose_from_most_used' => __( 'Choose from the most used categories' ),
                'not_found' => __( 'No categories found' ),
            ),
            'public' => true,
            "show_admin_column" => true,
            "show_tagcloud" => true,
            'hierarchical' => true,
            "rewrite" => array(
                "slug" => "blog/categories"
            ),
            "capabilities" => array(
                "manage_terms" => "read_private_articles",
                "edit_terms" => "read_private_articles",
                "delete_terms" => "read_private_articles",
                "assign_terms" => "edit_article",
            )
        )
    );
}
add_action( 'init', 'lfab_register_custom_taxonomies' );