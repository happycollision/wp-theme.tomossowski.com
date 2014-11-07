<?php get_header(); ?>
<?php query_posts('post_type=hc_portfolio&showposts=-1&orderby=date&order=DESC');?>
			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'portfolio' );
			?>


<?php get_footer(); ?>
