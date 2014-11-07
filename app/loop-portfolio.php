<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>

<?php endif; ?>

<h2 class="entry-title page-title">Portfolio</h2>

<?php while ( have_posts() ) : the_post(); ?>

	<?php //portfolio_page_item($post);?>
	
	<?php $items = new HCPortfolio(get_the_ID()); //ddprint($items);?>
	
		<a href="<?php $items->single_link();?>" class="portfolio_item clearfix"><!--Link to play video-->
		<?php if(has_post_thumbnail()) { ?>
			<img class="portfolio_image" src="<?php echo happycol_get_thumbnail_url('medium'); ?>" /> 
		<?php } ?>
			<div class="meta_wrapper"><div class="meta">
				<h3><?php $items->title();?></h3>
				<div class="stats">
					<div><?php $items->location(); ?></div>
					<div><?php $items->position(); ?></div>
				</div><!--stats-->
			</div><!--meta--></div>
		</a><!--portfolio item-->

    
<?php endwhile;