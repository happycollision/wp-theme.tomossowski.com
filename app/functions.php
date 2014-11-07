<?php
	locate_template('praise_quotes_post-type.php',true);
	locate_template('portfolio_post-type.php',true);

	add_editor_style();
	add_theme_support( 'post-thumbnails' );
	

function happycol_get_thumbnail_url($size_of_thumbnail = NULL){
	global $post;
	if(has_post_thumbnail()){
		$featured_image_url_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size_of_thumbnail );
		return $featured_image_url_array[0];
	}else{
		return FALSE;
	}
}

/**
 * TinyMCE customization
 * via http://blog.estherswhite.net/2009/11/customizing-tinymce/
 */
 
//add styles
function childtheme_mce_css($wp) {
return $wp .= ',' . get_bloginfo('stylesheet_directory') . '/css/mce.css';
}
add_filter( 'mce_css', 'childtheme_mce_css' );

//customize buttons
function childtheme_mce_btns2($orig) {
return array(/*'formatselect', */'styleselect', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'charmap', '|', 'undo', 'redo', 'wp_help', 'mymenubutton' );
}
add_filter( 'mce_buttons_2', 'childtheme_mce_btns2', 999 );

//specify styles instead of import
function childtheme_tiny_mce_before_init( $init_array ) {
$init_array['theme_advanced_styles'] = 
			"Show Title=show_title"// add ";" inside (left of double quote) string and concatonnate if adding more styles
			;
$init_array['theme_advanced_blockformats'] = "p,h5,h6";
			
			
return $init_array;
}
add_filter( 'tiny_mce_before_init', 'childtheme_tiny_mce_before_init' );

//change css for visual editor!!
add_filter('mce_css', 'my_editor_style');
function my_editor_style($url) {

  if ( !empty($url) )
    $url .= ',';

  // Change the path here if using sub-directory
  $url .= trailingslashit( get_stylesheet_directory_uri() ) . 'editor-style.css';

  return $url;
  
}
//end TinyMCE stuff

/* * * * * 
    The data row-a-lizer 1.0
	by Double D Photo & Design
	
	
*/
//Define terms used for shortcode. For example, if you use the values below,
// you would want your post to look like this in the editor:
// 		[resume][cols title="something"] My Content [/cols][/resume]

// Redefine as required.
$container_shortcode_call = 'resume';
$rowalizer_shortcode_call = 'cols';
$section_title = 'title';

//Pretty HTML: comment out the next lines for gross, but concise code.
$DD_line_breaks = "\n";
$DD_tabs = "    ";



//  ******* leave the rest alone unless you know what you are doing.

//Will normalize line breaks from user entry
function DD_line_breaks($atts,$content = null) {
	$output = str_replace(array('<p>','<br />','<br>'),"\n",$content);
	$output = str_replace('</p>','',$output);
	while (stristr($output,"\n\n")){
		$output = str_replace("\n\n","\n",$output);
		}
	return $output;
}


//The row-a-lizer shortcodes
add_shortcode("$container_shortcode_call", 'rowalizer_container_shortcode');
add_shortcode("$rowalizer_shortcode_call", 'rowalizer_shortcode');

function rowalizer_container_shortcode($atts,$content = null) {
	global $container_shortcode_call, $DD_line_breaks, $DD_tabs;
	$r = $DD_line_breaks;
	$t = $DD_tabs;
	$output = $r.'<!--<div class="'.$container_shortcode_call.'">-->' . do_shortcode($content) .$t.'<div class="anchor"></div>'.$r.$t.'<!--</div>--><!--'.$container_shortcode_call.'-->';
	$output = str_replace(array('<p>','</p>'),'',$output);
	return $output;
}
function rowalizer_shortcode($atts,$content = null) {
	global  $section_title, 
			$container_shortcode_call, 
			$DD_line_breaks, 
			$DD_tabs; 
	
	$r = $DD_line_breaks;
	$t = $DD_tabs;
	
	static $col_instance = 0;
	$col_instance++;

	if (!function_exists('rowalizer_cell_beginning')){
		function rowalizer_cell_beginning($cell_number){
			if($cell_number==3){
				return '(';
			}
		}
	}
	if (!function_exists('rowalizer_cell_ending')){
		function rowalizer_cell_ending($cell_number){
			if($cell_number==3){
				return ')';
			}
		}
	}
	
	extract(shortcode_atts(array(
		$section_title	=> '',
	), $atts));
	
	//add a break to begining for use later
	$begining = "\n";
	$content = $begining . $content;
	
	//Turn all forms of line break into a carriage return 
	//and turn all multiple carriage returns into SINGLE carriage return
	$content = DD_line_breaks('',$content);
	
	//Get rid of that first carriage return... and the last one
	$content = trim($content);
	
	//Break into rows
	$content = explode("\n", $content);

	//Break into columns (separated from rows currently)
	$i=0; while($content[$i]) {
		$content[$i] = explode("|", $content[$i]);
		$i++;
	}
	
	//creating a multi-dimensional foreach. By specifying keys ($rows and $cols) I retain NULL values.
	foreach ($content as $rows=>$row) {
		foreach ($row as $cols=>$cell) {
			if($tot_rows<$rows)$tot_rows = $rows;
			if($tot_cols<$cols)$tot_cols = $cols;
		}
	}
	++$tot_rows; ++$tot_cols;
	//echo "number of rows is ".$tot_rows.", and number of cols is ".$tot_cols.".<br/>";
	
	$row=0;
	$cell=0;
	while($tot_rows>0){
		$temp_tot_cols = $tot_cols;
		$output .=  $t . '<div class="row row-'.++$row.(($row%2) ? ' odd' : ' even').'">' . $r;
		while($tot_cols>0){
			
			$output .= $t.$t. '<span class="cell cell-'.++$cell.(($cell%2) ? ' odd' : ' even').
								((trim($content[($row-1)][($cell-1)])) ? NULL: ' empty').'">';
			$output .= rowalizer_cell_beginning($cell);
			$output .= ((trim($content[($row-1)][($cell-1)])) ? trim($content[($row-1)][($cell-1)]) : NULL
						/*'&nbsp;'*/);
			$output .= rowalizer_cell_ending($cell);
			$output .= '</span>'.$r;
			
			$tot_cols--;
		}
		$output .= $t.$t.'<div class="anchor"></div>'.$r;
		$output .= $t.$t.'</div><!-- row-'.$row.' -->'.$r;
		$tot_rows--;
		$tot_cols = $temp_tot_cols;
		$cell=0;
	}
	
	$output = str_replace(array('<p>','</p>'),'',$output);
	if($atts[$section_title]){
		$section_class = str_replace(' ','',$atts[$section_title]);
		$preCols =  '<div class="section section-'.$col_instance.(($col_instance%2) ? ' odd' : ' even').' section-'.$section_class.'">' . $r . 
					$t . '<span class="section_heading">' . $atts[$section_title] . '</span>' .$r;
		return $preCols . $output . $t .'<div class="anchor"></div></div><!--section'.$col_instance.'-->' . $r;
		break;
		}
	return $output;
}
// End rowalizer


/**
 * Makes some changes to the <title> tag, by filtering the output of wp_title().
 *
 * If we have a site description and we're viewing the home page or a blog posts
 * page (when using a static front page), then we will add the site description.
 *
 * If we're viewing a search result, then we're going to recreate the title entirely.
 * We're going to add page numbers to all titles as well, to the middle of a search
 * result title and the end of all other titles.
 *
 * The site title also gets added to all titles.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $title Title generated by wp_title()
 * @param string $separator The separator passed to wp_title(). Twenty Ten uses a
 * 	vertical bar, "|", as a separator in header.php.
 * @return string The new title, ready for the <title> tag.
 */
function twentyten_filter_wp_title( $title, $separator ) {
	// Don't affect wp_title() calls in feeds.
	if ( is_feed() )
		return $title;

	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;

	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'twentyten' ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), $paged );
		// Add the site name to the end:
		$title .= " $separator " . get_bloginfo( 'name', 'display' );
		// We're done. Let's send the new title back to wp_title():
		return $title;
	}

	// Otherwise, let's start by adding the site name to the end:
	$title .= get_bloginfo( 'name', 'display' );

	// If we have a site description and we're on the home/front page, add the description:
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $separator " . $site_description;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	// Return the new title to wp_title():
	return $title;
}
add_filter( 'wp_title', 'twentyten_filter_wp_title', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Twenty Ten 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );