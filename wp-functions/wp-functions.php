<?php
/*
Plugin Name: Brandon Himpfen WordPress Functions
Version: 0.1
Plugin URI: https://www.himpfen.com/
Description: Site-Specific WordPress Functions.
Author: Brandon Himpfen
Author URI: https://www.himpfen.com/
*/

// Remove "Lost your password?" text from login page
function remove_lostpassword_text ( $text ) {
	 $words = "Lost your password?";
     if ($text == $words){$text = '';}
        return $text;
     }
add_filter( 'gettext', 'remove_lostpassword_text' );

// Block Pinterest From Pinning Your Images
function no_images_pinning() {
    echo '<meta name="pinterest" content="nopin" />';
}
add_action( 'wp_head', 'no_images_pinning' );

// Clean search permalinks /?s= to /search/
function clean_search_link() {
	global $wp_rewrite;
	if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
		return;

	$search_base = $wp_rewrite->search_base;
	if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
		wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
		exit();
	}
}
add_action( 'template_redirect', 'clean_search_link' );

// If there is only one search result, redirect to that post
function redirect_single_post_search() {
    if (is_search() && is_main_query()) {
        global $wp_query;
        if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            exit;
        }
    }
} 
add_action('template_redirect', 'redirect_single_post_search' );

// Remove WordPress Meta Generator
remove_action('wp_head', 'wp_generator');

// Remove the WordPress Generator tag in RSS Feeds
function remove_wp_generator_rss() {
	return'';
}
add_filter('the_generator','remove_wp_generator_rss');

// Bloginfo shortcode
function bloginfo_shortcode( $atts ) {
   extract(shortcode_atts(array(
       'value' => '',
   ), $atts));
   return get_bloginfo($value);
}
add_shortcode('bloginfo', 'bloginfo_shortcode');
