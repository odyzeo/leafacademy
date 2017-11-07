<!-- Facebook Conversion Code for Key Page Views - LEAF Academy - January 2016 -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6032258739530', {'value':'0.00','currency':'EUR'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6032258739530&amp;cd[value]=0.00&amp;cd[currency]=EUR&amp;noscript=1" /></noscript>





<!-- Facebook Pixel Code -->

<script>

!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?

n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;

n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;

t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,

document,'script','//connect.facebook.net/en_US/fbevents.js');

 

fbq('init', '896530477048306');

fbq('track', "PageView");</script>

<noscript><img height="1" width="1" style="display:none"

src="https://www.facebook.com/tr?id=896530477048306&ev=PageView&noscript=1"

/></noscript>

<!-- End Facebook Pixel Code -->



<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

		</div><!-- #main -->
        
        
        <footer id="footer">
            <div>
                <span class="part"><?php echo __( 'LEAF Academy, Sasinkova 13, 811 08 Bratislava, Slovakia', 'leafacademy' ); ?></span>
                <span class="part"><?php echo __( 'phone: +421 907 836 490', 'leafacademy' ); ?></span> 
                <span class="part"><?php echo __( 'e-mail:' ); ?> <a href="mailto:<?php echo esc_url( __( 'info@leafacademy.eu', 'leafacademy' ) ); ?>"><?php echo __( 'info@leafacademy.eu', 'leafacademy' ); ?></a></span> 
                <span class="part"><?php echo __( 'LEAF Organization:' ); ?> <a href="<?php echo esc_url( __( 'www.leaf.sk', 'leafacademy' ) ); ?>" target="_blank"><?php echo __( 'www.leaf.sk', 'leafacademy' ); ?></a></span>
            </div>
            
            <ul class="social">
                <li><span class='st_blogger_large blogger' displayText='Blogger'></span></li>
                <li><span class='st_facebook_large facebook' displayText='Facebook'></span></li>
                <li><span class='st_googleplus_large google-plus' displayText='Google +'></span></li>
                <li><span class='st_tumblr_large tumblr' displayText='Tumblr'></span></li>
                <li><span class='st_twitter_large twitter' displayText='Tweet'></span></li>
            </ul>
        </footer><!-- #footer -->

		
    </div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>