<?php $author = new LFA_Author(get_the_author_meta('ID'));?>
<article <?php post_class("lfab-article-item");?> data-id="<?php the_ID();?>">
    <div class="lfab-article-item-inner">
        <div class="lfab-article-item-content">
            <header class="lfab-article-item-header">
                <div class="lfab-article-item-meta">
                    <time class="lfab-article-item-date" datetime="<?php echo get_the_date("r");?>"><?php echo get_the_date("j. n. Y");?></time>
                    <?php $cats = lfa_blog_get_post_categories(get_the_ID());?>
                    <?php if($cats) : ?>
                        <span class="lfab-article-item-meta-divider" role="presentation">|</span>
                        <div class="lfab-article-item-categories-list">
                            <?php foreach($cats as $cat) : ?>
                                <div class="lfab-article-item-categories-list-item">
                                    <a data-action="lfab-show-articles" data-force-category="true" data-id="<?php echo $cat->term_id;?>" class="lfab-article-item-categories-list-item-link" href="<?php echo get_term_link($cat);?>"><?php echo apply_filters("single_cat_title", $cat->name);?></a>
                                </div>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
                </div>
                <h3 class="lfab-article-item-title">
                    <a class="lfab-article-item-title-link" href="<?php the_permalink()?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
                </h3>
            </header>
            <div class="lfab-article-item-body">
                <p class="lfab-article-item-excerpt"><?php echo get_the_excerpt();?></p>
                <div class="lfab-article-item-cta">
                    <a class="btn green-grey lfab-article-item-body-cta-btn" href="<?php the_permalink()?>">Read more</a>
                </div>
            </div>
            <footer class="lfab-article-item-footer">
                <div class="lfab-article-item-author">
                    <div class="lfab-article-item-author-avatar">
                        <div class="lfab-article-item-author-avatar-inner">
                            <a href="<?php echo esc_attr($author->getUrl()); ?>" class="lfab-article-item-author-avatar-image-wrapper">
                                <?php echo $author->getAvatar();?>
                            </a>
                        </div>
                    </div>
                    <div class="lfab-article-item-author-name">
                        <a href="<?php echo esc_attr($author->getUrl()); ?>" class="lfab-article-item-author-name-link"><?php echo esc_attr($author->getFullName()); ?></a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</article>
