<?php
/**
 * Template Name: Contact page with map
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 
get_header(); ?> 





<div id="main-content" class="main-content contact">

    <div class="block block-front-sections do-match-height">
            <article class="item narrow-column bg-darkgrey formated-output">
                <div class="section"><?php echo get_the_title(190); ?></div>
                <?php if(function_exists('show_text_block')) { echo show_text_block(190, false); } ?>
                <br />
                <iframe id="contact-map" width="100%" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJRY0m7UiJbEcRwZYSK9-dFq4&key=AIzaSyAm3drQLpF0AMLAcUJmAUvJ9Qv-cYmC_8k" allowfullscreen></iframe>
            </article>
            <article class=" bg-white wide-column">
                
                
                <div class="item">
                <?php
                    // Start the Loop.
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part( 'content', 'page' );

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) {
                            comments_template();
                        }
                    endwhile;
                ?>
                </div>
            </article>
          
    </div>
    
    
   
</div> 
<?php
//get_sidebar();

get_footer();
