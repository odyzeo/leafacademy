<?php

lfa_blog_set_blog_section_as_being_viewed();
get_header();

$author = new LFA_Author(get_queried_object_id());

add_action("wp_footer", function () {
    ?>
    <script>
        LFABlog.toggleAuthorPanel();
        LFABlog.setupBlogger();
    </script>
    <?php
});

?>

<div id="main-content" class="main-content main-content-author-blogger">
    <div id="primary" class="content-area">
        <div id="content" class="site-content corner-fx corner-fx-greengrey" role="main">
            <section id="post-<?php the_ID(); ?>" <?php post_class("blogger"); ?>>

                <div class="lfa-author-panel">
                    <div class="lfa-author-panel-inner">
                        <?php if($author->hasAvatar()) :?>
                            <div class="lfa-author-panel-avatar">
                                <div class="lfa-author-panel-avatar-inner">
                                    <a href="<?php echo esc_attr($author->getUrl());?>" class="lfa-author-panel-avatar-image-wrapper">
                                    <?php echo $author->getAvatar();?>
                                </a>
                                </div>
                            </div>
                        <?php endif;?>
                        <header class="lfa-author-panel-meta">
                            <h1 class="lfa-author-panel-name">
                                <a href="<?php echo esc_attr($author->getUrl());?>" class="lfa-author-panel-name-link">
                                    <?php echo esc_attr($author->getFullName()); ?>
                                </a>
                            </h1>
                            <?php if(mb_strlen($author->getPosition())):?>
                                <p class="lfa-author-panel-position"><?php echo esc_attr($author->getPosition()); ?></p>
                            <?php endif;?>
                        </header>
                        <?php if(mb_strlen($author->getBio())):?>
                            <div class="lfa-author-panel-body">
                                <p class="lfa-author-panel-bio">
                                    <?php echo nl2br(esc_attr($author->getBio()));?>
                                </p>
                            </div>
                        <?php endif;?>
                    </div>
                </div>

                <div class="post-thumbnail corner-fx corner-fx-greywhite">
                    <?php
                    // echo get_the_post_thumbnail( get_page_by_path(LFA_BLOGGER_URL_BASE)->ID, "leafacademy-full-width" );
                    the_author_image();
                    the_post_thumbnail_caption(get_page_by_path(LFA_BLOGGER_URL_BASE), sprintf("%s's articles", ucfirst($author->getFirstName())));
                    ?>
                </div>


                <?php if(have_posts()) : ?>
                    <div class="bloger-articles-list">
                        <div class="bloger-articles-list-inner">
                            <?php $i=0; while(have_posts()) : the_post(); $i++;?>
                                <?php $author = new LFA_Author(get_the_author_meta('ID'));?>
                                <div class="blogger-articles-list-item <?php echo $i % 2 ? " odd " : "";?>">
                                    <div class="blogger-articles-list-item-inner">
                                        <article <?php post_class("blogger-article");?>>
                                            <div class="blogger-article-inner">
                                                <div class="blogger-article-content">
                                                    <header class="blogger-article-header">
                                                        <div class="blogger-article-meta">
                                                            <time class="blogger-article-date" datetime="<?php echo get_the_date("r");?>"><?php echo get_the_date("j. n. Y");?></time>
                                                            <?php $cats = lfa_blog_get_post_categories(get_the_ID());?>
                                                            <?php if($cats) : ?>
                                                                <span class="blogger-article-meta-divider" role="presentation">|</span>
                                                                <div class="blogger-article-categories-list">
                                                                    <?php foreach($cats as $cat) : ?>
                                                                        <div class="blogger-article-categories-list-item">
                                                                            <a class="blogger-article-categories-list-item-link" href="<?php echo get_term_link($cat);?>"><?php echo apply_filters("single_cat_title", $cat->name);?></a>
                                                                        </div>
                                                                    <?php endforeach;?>
                                                                </div>
                                                            <?php endif;?>
                                                        </div>
                                                        <h3 class="blogger-article-title">
                                                            <a class="blogger-article-title-link" href="<?php the_permalink()?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
                                                        </h3>
                                                    </header>
                                                    <div class="blogger-article-body">
                                                        <p class="blogger-article-excerpt"><?php echo get_the_excerpt();?></p>
                                                        <div class="blogger-article-cta">
                                                            <a class="btn green-grey blogger-article-cta-btn" href="<?php the_permalink()?>">Read more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                            <?php endwhile;?>
                        </div>
                    </div>

                    <div class="blogger-pagination">
                        <?php
                        global $wp_query;

                        $big = 999999999; // need an unlikely integer

                        echo paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, get_query_var('paged') ),
                            'total' => $wp_query->max_num_pages
                        ) );
                        ?>
                    </div>

                <?php else :?>

                    <div class="no-articles-at-this-time"><?php echo esc_attr($author->getFirstName());?> is yet to publish his new article.</div>

                <?php endif;?>

            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>
