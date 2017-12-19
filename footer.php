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
					<span class="part"><?php echo __('LEAF Academy, Sasinkova 13, 811 08 Bratislava, Slovakia', 'leafacademy'); ?></span>
					<span class="part"><?php echo __('phone: +421 907 836 490', 'leafacademy'); ?></span> 
					<span class="part"><?php echo __('e-mail:'); ?> <a href="mailto:<?php echo esc_url(__('info@leafacademy.eu', 'leafacademy')); ?>"><?php echo __('info@leafacademy.eu', 'leafacademy'); ?></a></span> 
					<span class="part"><?php echo __('LEAF Organization:'); ?> <a href="<?php echo esc_url(__('www.leaf.sk', 'leafacademy')); ?>" target="_blank"><?php echo __('www.leaf.sk', 'leafacademy'); ?></a></span>
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