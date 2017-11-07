<?php
/**
 * Template Name: Blog/Authors
 */

lfa_blog_set_blog_section_as_being_viewed();
$authors = lfa_get_authors();

get_header(); ?>
<div id="main-content" class="main-content">
    <div id="primary" class="content-area">
        <div id="content" class="site-content corner-fx corner-fx-greengrey" role="main">
            <?php if ( have_posts() ) : the_post(); ?>

                <section id="post-<?php the_ID(); ?>" <?php post_class("bloggers"); ?>>
                    <?php leafacademy_post_thumbnail(); ?>

                    <?php  if(!empty($authors)):?>
                        <div class="entry-content">
                            <div class="block block-team">
                                <div class="items do-match-height">
                                    <?php foreach(lfa_get_authors() as $author) : ?>

                                        <article class="item">
                                            <a title="<?php echo esc_attr($author->getFullName());?>" data-continue="true" href="<?php echo esc_attr($author->getUrl()); ?>" class="image do-bg-image" style="background-image: url('<?php echo $author->getAvatarSrc(); ?>');">
                                                <?php echo $author->getAvatar();?>
                                            </a>
                                            <h2 class="name">
                                                <a href="<?php echo esc_attr($author->getUrl()); ?>" title="<?php echo esc_attr($author->getFullName());?>"><?php echo esc_attr($author->getFullName());?></a>
                                            </h2>
                                            <?php if(mb_strlen($author->getPosition())):?>
                                                <div class="position">
                                                    <?php echo esc_attr($author->getPosition());?>
                                                </div>
                                            <?php endif;?>
                                        </article>

                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>

                    <?php else : ?>

                        <div>Sorry, no bloggers at this time :( .</div>

                    <?php endif; ?>
                </section>


            <?php else :?>

                <div>Sorry, no bloggers at this time :( .</div>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
