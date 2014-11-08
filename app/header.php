<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title><?php wp_title( '|', true, 'right' );?></title>
    <meta name="viewport" content="width=device-width">

		<script src="//use.typekit.net/lbo3qcx.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>    

    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <script src="<?php bloginfo('template_directory');?>/js/vendor/modernizr-2.6.2.min.js"></script>

<?php wp_head();?>
</head>

<body <?php body_class(); ?>>
<!--[if IE 6]>
	<span style="background-color:#fff;color:#000;font-size:1.5em;">You are using Internet Explorer 6 to access the internet.  Please either upgrade to a newer version, or switch to <a href="http://www.browsehappy.com">another browser</a>.</span>
<![endif]-->
<header id="nameplate"><a href="<?php bloginfo('url');?>"><h1>Tom Ossowski</h1></a></header>

<?php 
	global $happycol_main_nav;
	$args = array(
		'depth'        => -1, //Displays all pages without nested <ul> structure.
		'title_li'     => '', //removes title before listing all the pages
		'echo'		   => 0   //Allows for storage in a variable
	); 
	$happycol_main_nav = wp_list_pages( $args );
?> 

<nav id="main_navigation">
	<input type="checkbox" id="menu">
	<label for="menu" onclick>Navigation</label>
	<ul>
		<?php echo $happycol_main_nav; ?>
	</ul>
	<div class="anchor"></div>
</nav>



<?php global $content_exists;
$content_exists = FALSE;
if(count($wp_query->posts) > 0){
	$content_exists = TRUE;
?>

<div id="main_content">

<?php if(is_front_page()):?>
<div id="praise_box" class="clearfix">
	<?php $praise = new WP_Query("post_type=praise&showposts=1&orderby=rand");
		if($praise->have_posts()): while ($praise->have_posts()) : $praise->the_post(); 
			$custom = get_post_custom($post->ID);
	?>
    <blockquote><?php the_content();?></blockquote>
	<div class="meta">
		<span class="author">&mdash;<?php echo $custom['praise_author'][0];?></span>
	    <span class="topic">on <span class="show_title"><?php the_title();?></span></span>
	</div><!--meta-->
	
	<?php endwhile; else: ?>
    <span class="bb_quote">Director, Music Director<br>Member SSDC</span>
	
	<?php endif; // ending if $praise->have_posts()
	wp_reset_query();?>
    
</div><!--praise_box-->
<?php endif; //this is the if that determined if we were on the front or not ?>


<?php } // ending if content exists ?>