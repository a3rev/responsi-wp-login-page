<?php
/*
Plugin Name: Responsi WP Login Page
Description: Don't want your client / customers seeing the WordPress logo and links when they login to your site? Responsi WP Login Page allows you to customize the login page to match your sites design and remove all reference to WordPress.
Version: 1.4.1
Author: a3rev Software
Author URI: https://a3rev.com/
Update URI: a3-responsi-wp-login-page
Requires at least: 4.4
Tested up to: 5.9
Text Domain: responsi-wp-login-page
Domain Path: /languages
License: This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007

	Responsi WP Login Page. Plugin for the Responsi Framework.
	Copyright © 2011 a3THEMES

	a3THEMES
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
define( 'RESPONSI_WPLOGIN_PATH', dirname(__FILE__));
define( 'RESPONSI_WPLOGIN_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'RESPONSI_WPLOGIN_NAME', plugin_basename(__FILE__) );
define( 'RESPONSI_WPLOGIN_URL', str_replace( array( 'http:','https:' ), '', untrailingslashit( plugins_url( '/', __FILE__ ) ) ) );
define( 'RESPONSI_WPLOGIN_IMAGES_URL', RESPONSI_WPLOGIN_URL . '/assets/images' );
define( 'RESPONSI_WPLOGIN_JS_URL', RESPONSI_WPLOGIN_URL . '/assets/js' );
define( 'RESPONSI_WPLOGIN_CSS_URL', RESPONSI_WPLOGIN_URL . '/assets/css' );
define( 'RESPONSI_WPLOGIN_KEY', 'responsi_wp_login_page' );
define( 'RESPONSI_WPLOGIN_VERSION', '1.4.1' );

function responsi_wp_login_page_activate_validate() {
    if ( 'responsi' !== get_template() ) {
        echo sprintf( wp_kses_post( 'This is a plugin for Responsi Framework, you need to install <a href="%s" target="_blank" rel="noopener">Responsi Framework</a> theme from WordPress first before can activate this.', 'responsi-wp-login-page' ), 'https://wordpress.org/themes/responsi-framework/' );
        die();
    }
    update_option('a3rev_responsi_wp_login_page_version', RESPONSI_WPLOGIN_VERSION );
    update_option('responsi_wp_login_page_installed', true);
}

register_activation_hook(__FILE__,'responsi_wp_login_page_activate_validate');

if( !defined( 'RESPONSI_WPLOGIN_TRAVIS' ) ){
	if ( !file_exists( get_theme_root().'/responsi/functions.php' ) ) return;
	if ( !isset( $_POST['wp_customize'] ) && get_option('template') != 'responsi' ) return;
	if ( isset( $_POST['wp_customize'] ) && wp_unslash($_POST['wp_customize']) == 'on' && isset( $_POST['theme'] ) && stristr( wp_unslash($_POST['theme']), 'responsi' ) === FALSE ) return;
	if ( version_compare(get_option('responsi_framework_version'), '6.9.5', '<') ) return;
}

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
    require __DIR__ . '/vendor/autoload.php';

    global $responsi_wp_login_page_admin,$responsi_wp_login_page;
    $responsi_wp_login_page_admin                   = new \A3Rev\RWPLogin\Admin();
    $responsi_wp_login_page                         = new \A3Rev\RWPLogin\Main();
                                                      new \A3Rev\RWPLogin\Customizer();

} else {
    return;
}

add_action( 'after_setup_theme', 'responsi_addon_wp_login_page_upgrade_version' );
function responsi_addon_wp_login_page_upgrade_version() {

	if ( version_compare(get_option('a3rev_responsi_wp_login_page_version'), '1.4.1') === -1 ) {
		global $responsi_wp_login_page;
        $responsi_wp_login_page->build_css_after_addon_updated();
	}
	
	update_option('a3rev_responsi_wp_login_page_version', RESPONSI_WPLOGIN_VERSION );
}

include ( 'upgrade/plugin_upgrade.php' );
include ( 'admin/responsi-wp-login-page-init.php' );
include ( 'classes/responsi-wp-login-page-function.php' );

?>