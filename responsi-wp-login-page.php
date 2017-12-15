<?php
/*
Plugin Name: Responsi WP Login Page
Description: Dont want your client / customers seeing the WordPress logo and links when they login to your site? Responsi WP Login Page allows you to customize the login page to match your sites design and remove all reference to WordPress.
Version: 1.2.4
Author: a3THEMES
Author URI: http://a3rev.com/
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

if(!defined("RESPONSI_WPLOGIN_MANAGER_URL"))
	define("RESPONSI_WPLOGIN_MANAGER_URL", "http://a3api.com/responsi_premium");

update_option('a3rev_responsi_wp_login_page_plugin', 'responsi_wp_login_page');

function responsi_wp_login_page_activate_validate() {
    if ( 'responsi' !== get_template() ) {
        echo sprintf( __( 'This is a plugin for Responsi Framework, you need to install <a href="%s" target="_blank">Responsi Framework</a> theme from WordPress first before can activate this.', 'responsi-wp-login-page' ), 'https://wordpress.org/themes/responsi-framework/' );
        die();
    }
    update_option('a3rev_responsi_wp_login_page_version', '1.2.4');
    update_option('responsi_wp_login_page_installed', true);
}

register_activation_hook(__FILE__,'responsi_wp_login_page_activate_validate');

if ( !file_exists( get_theme_root().'/responsi/functions.php' ) ) return;
if ( !isset( $_POST['wp_customize'] ) && get_option('template') != 'responsi' ) return;
if ( isset( $_POST['wp_customize'] ) && $_POST['wp_customize'] == 'on' && isset( $_POST['theme'] ) && stristr( $_POST['theme'], 'responsi' ) === FALSE ) return;

add_action( 'after_setup_theme', 'responsi_addon_wp_login_page_upgrade_version' );
function responsi_addon_wp_login_page_upgrade_version() {

	if ( version_compare(get_option('a3rev_responsi_wp_login_page_version'), '1.2.4') === -1 ) {
		global $responsi_wp_login_page;
		//$theme = get_option( 'stylesheet' );
        //$version = str_replace('.', '_', '1.2.4');
        //update_option( 'theme_mods_'.$theme.'_wp_login_page_'.$version, $responsi_wp_login_page->global_responsi_options_wp_login_page() );
        $responsi_wp_login_page->build_css_after_addon_updated();
	}
	update_option('a3rev_responsi_wp_login_page_version', '1.2.4');
}

include ( 'upgrade/plugin_upgrade.php' );
include ( 'admin/responsi-wp-login-page-admin.php' );
include ( 'admin/responsi-wp-login-page-init.php' );
include ( 'classes/responsi-wp-login-page-function.php' );
include ( 'classes/responsi-wp-login-page-class.php' );

add_filter('responsi_includes_customizer','wp_login_page_includes_customizer');
function wp_login_page_includes_customizer( $includes_customizer ) {
	$includes_customizer[] = RESPONSI_WPLOGIN_PATH.'/customize/customize.php';
	return $includes_customizer;
}

?>