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
add_action( 'init', function() {
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}, PHP_INT_MAX - 1 );
