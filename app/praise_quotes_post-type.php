<?php
/*
Plugin Name: Praise Post-Type
Plugin URI:
Description: Allows users to add/remove praise entries.
Author: Don Denton
Version: 1.0
Author URI: http://www.ddphotodesign.com
*/

add_action('init', 'praise_init');
function praise_init() 
{
  $labels = array(
    'name' => _x('Praise', 'post type general name'),
    'singular_name' => _x('Praise', 'post type singular name'),
    'add_new' => _x('Add New', 'Praise'),
    'add_new_item' => __('Add New Praise'),
    'edit_item' => __('Edit Praise'),
    'new_item' => __('New Praise'),
    'view_item' => __('View Praise'),
    'search_items' => __('Search Praises'),
    'not_found' =>  __('No praises found'),
    'not_found_in_trash' => __('No praises found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array('title','editor')
  ); 
  register_post_type('praise',$args);
}

//add filter to insure the text Praise, or praise, is displayed when user updates a praise 
add_filter('post_updated_messages', 'praise_updated_messages');
function praise_updated_messages( $messages ) {

  $messages['praise'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Praise updated. <a href="%s">View Praise</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Praise updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('praise restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Praise published. <a href="%s">View Praise</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Praise saved.'),
    8 => sprintf( __('Praise submitted. <a target="_blank" href="%s">Preview praise</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Praise scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Praise</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Praise draft updated. <a target="_blank" href="%s">Preview Praise</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}



/*
 *
 Now for some sweet stuff. Making custom fields user-friendly!
 *
 */
add_action("admin_init", "praise_meta_init");
 
function praise_meta_init(){
  //format: add_meta_box( $id, $title, $callback, $page, $context, $priority );
  add_meta_box("praise_author_name", "Author", "praise_author_meta", "praise", "normal", "low");
}
 
function praise_author_meta() {
  global $post;
  $custom = get_post_custom($post->ID);
  $praise_author = $custom["praise_author"][0];
  $pub_date = $custom["pub_date"][0];
  ?>
  <p><label>Author and/or Publication:</label><br />
  <input type="hidden" value="NotAuto" name="autosave_check" />
  <input type="text" value="<?php echo $praise_author; ?>" size="50" name="praise_author">
  </p>
  <p><label>Publication Date:</label><br />
  <input type="text" value="<?php echo $pub_date; ?>" size="50" name="pub_date">
  </p>
  
  <?php
}

//Now to make sure the data gets saved:
add_action('save_post', 'save_praise_details');
function save_praise_details(){
	global $post;

	if($_POST['autosave_check']){
	update_post_meta($post->ID, "praise_author", $_POST["praise_author"]);
	update_post_meta($post->ID, "pub_date", $_POST["pub_date"]);
	}
}

?>
