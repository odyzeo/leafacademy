<nav class="lfab-categories">
    <?php foreach(lfa_blog_get_available_categories() as $category): ?>
        <div class="lfab-category">
            <a data-action="lfab-show-articles" data-id="<?php echo $category->term_id;?>" class="btn green-white lfab-category-link <?php echo ($category->term_id == lfa_blog_get_current_taxonomy_category_id()) ? " active " : ""; ?>" href="<?php echo get_term_link($category);?>">
                <svg class="left" height="30" width="15" role="presentation">
                    <polygon points="0,15 15,30 15,0"></polygon>
                </svg>
                <span class="btn-label">
                    <?php echo apply_filters( 'single_tag_title', $category->name );?>
                </span>
                <svg class="right" height="30" width="15" role="presentation">
                    <polygon points="0,0 0,30 15,15"></polygon>
                </svg>
            </a>
        </div>
    <?php endforeach;?>
</nav>