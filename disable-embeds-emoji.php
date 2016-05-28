<?php
/**
 * Plugin Name: Disable Embeds and Emoji
 * Description: Easily disable WordPress 4.4+ oEmbed and Emoji Links, Javascripts, CSS, Rewrite using this plugin.
 * Version:     1.0.0
 * Author:      Abhishek_Ghosh
 * Author URI:  https://thecustomizewindows.com
 * License:     GPLv2+
 *
 * @package disable-embeds-emoji
 */
/**
 * Disable Emoji
 *
 * - Disable emoji
 * - Removes emoji CSS and Js
 *
 * @since 1.0.0
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
/**
 * Disable embeds on init.
 *
 * - Removes the needed query vars.
 * - Disables oEmbed discovery.
 * - Completely removes the related JavaScript.
 *
 * @since 1.0.0
 */
function disable_embeds_emoji_init() {
	/* @var WP $wp */
	global $wp;
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'init', 'disable_embeds_emoji_init', 9999 );
/**
 * Removes the 'wpembed' TinyMCE plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array The modified list.
 */
function disable_embeds_emoji_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}
/**
 * Remove all rewrite rules related to embeds.
 *
 * @since 1.0.0
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function disable_embeds_emoji_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}
/**
 * Remove embeds rewrite rules on plugin activation.
 *
 * @since 1.0.0
 */
function disable_embeds_emoji_remove_rewrite_rules() {
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_emoji_remove_rewrite_rules' );

/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @since 1.0.0
 */
function disable_embeds_emoji_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_emoji_rewrites' );
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'disable_embeds_emoji_flush_rewrite_rules' );
