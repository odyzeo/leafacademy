<?php
/**
 * Template Name: Blog/Articles
 */


lfa_blog_set_blog_section_as_being_viewed();

//v javascripte mame pripravenu premennu, ktoru potrbeujeme naplnit udajmi z JS
//LFABlog je nacitany v hlavicke
//potom je to cele inicializovane cez document ready, v script-ready.js vo footri
add_action("wp_head", function () {
    ?>
    <script>
        LFABlog.articles.articleToCategoryMap = <?php echo json_encode(lfa_blog_get_post_to_category_map());?>;
        LFABlog.articles.isBlogSecttionArticleListingBeingViewed = true;
    </script>
    <?php
}, 1000);

if(lfa_blog_is_taxonomy_category()) {

    add_action("wp_head", function () {

        ?>

        <script>
            LFABlog.articles.activeCategories.push(<?php echo lfa_blog_get_current_taxonomy_category_id();?>);
            LFABlog.articles.reloadSelectedCategoriesInDOM();
        </script>

        <?php
    }, 1001);
}

add_action("wp_head", function () {
    ?>
    <script>
        LFABlog.articles.reloadArticlesByActiveCategory();
    </script>
    <?php
}, 1002);

$page = lfa_get_blog_categories_page();

get_header(); ?>

<div id="main-content" class="main-content">

    <div id="primary" class="content-area">
        <div id="content" class="site-content corner-fx corner-fx-greengrey" role="main">


                <section id="post-0" <?php post_class();?>>

                    <?php if($page instanceof WP_Post) : ?>
                        <div class="post-thumbnail corner-fx corner-fx-greywhite">
                            <?php echo get_the_post_thumbnail($page->ID, 'leafacademy-full-width' ); ?>
                            <span class="post-thumbnail-caption-wrapper">
                            <span class="post-thumbnail-caption-wrapper-tr">
                                <span class="post-thumbnail-caption-wrapper-td" style="opacity: 1;">
                                    <strong class="post-thumbnail-caption"><?php single_term_title();?></strong>
                                </span>
                            </span>
                        </span>
                        </div>
                    <?php endif;?>

                    <?php $articles = lfa_get_blog_articles(); ?>

                    <?php if($articles->have_posts()) : ?>
                        <div class="lfa-blog-category-list">
                            <div class="lfa-blog-category-list-inner">
                                <?php get_template_part("blog/components/categories");?>
                            </div>
                        </div>

                        <div class="lfa-blog-article-list">
                            <div class="lfa-blog-article-list-inner">
                                <?php $i = 0; while($articles->have_posts()) : $articles->the_post(); $i++; ?>
                                    <div aria-hidden="false" class="lfa-blog-article-list-item <?php echo $i % 2 ? " odd " : "";?>">
                                        <?php get_template_part("blog/components/article-item");?>
                                    </div>
                                <?php endwhile;?>
                            </div>
                        </div>

                        <footer class="lfa-blog-footer">
                            <?php //pagination will be here :?>
                        </footer>

                    <?php else :?>

                        <div class="no-articles-at-this-time">Sorry, no articles at this time :( .</div>

                    <?php endif;?>

                </section>

                <?php wp_reset_postdata();?>

        </div>
    </div>
</div>

<?php get_footer();