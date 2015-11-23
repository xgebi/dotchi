<?php
/**
 *  The template for single page
 *
 * @package dotchi
 */
 
 get_header();
 ?>


 <?php 
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); 
		get_template_part( 'templates/template', get_post_format() );			
	endwhile;
	if ( comments_open() || get_comments_number() ) :
					comments_template();
				else: ?>
					If you want to talk with me about this post, please, contact me @ygebi on twitter or sarah@dyslexicpanda.com.
				<?php endif;
else:
	get_template_part( 'templates/template', 'none' );
endif;
?> 
 
<?php get_footer(); ?>
