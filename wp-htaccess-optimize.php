<?php
/*
Plugin Name: WP htaccess Optimize (beta)
Description: simply configure your htaccess to optimize your site!
Version: 0.1.0
Author: Florian Luce
Author URI: florian-luce.info
Text Domain: wp-htaccess-optimize
License: GPL2
*/


/* ------ -
* Include plugin files
* --- -
*
*/
require_once( 'wp-htaccess-optimize-admin-ui.php' );
require_once( 'wp-htaccess-optimize-htaccess-generator.php' );

/* ------ -
 * Adding WP HTACCESS Optimize options page to the admin menu
 * --- -
 *
 */
add_action( 'admin_menu', function() {

    add_submenu_page( 'options-general.php',
        __( 'htaccess settings', 'wp-htaccess-optimize' ),
        __( 'WP htaccess Optimize', 'wp-htaccess-optimize' ),
        'manage_options',
        'wpho-theme-htaccess-menu',
        'wpho_display_admin_ui_content_view'
    );
} );
