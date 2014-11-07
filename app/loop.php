<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<nav class="pagination">
		<div class="nav-previous"><?php next_posts_link( '<span class="meta-nav">&larr;</span> Older posts' ); ?></div>
		<div class="nav-next"><?php previous_posts_link( 'Newer posts <span class="meta-nav">&rarr;</span>' ); ?></div>
	</nav><!-- #nav-above -->
<?php endif; ?>

<?php /* If there are no posts to display, do nothing */ ?>
<?php if ( ! have_posts() ) : ?>


<?php else: while ( have_posts() ) : the_post(); ?>

<?php /* How to display all other posts. */ ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

			<h2 class="entry-title"><?php the_title(); ?></h2>

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content clearfix">
				<?php if(!is_page('resume')) { ?><div class="inner_wrapper"><? } ?>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
				<?php if(!is_page('resume')) { ?></div><? } ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

		</article><!-- #post-## -->

<?php endwhile; // End the loop. ?>
<?php endif; // End the if of ! have_posts() ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
