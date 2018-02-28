<?php
/**
 * Register Activation Hook
 */
function responsi_wp_login_page_install() {
	global $responsi_wp_login_page;
	$responsi_wp_login_page->responsi_build_css_theme_actived();
}

/**
* Load Localisation files.
*
* Note: the first-loaded translation file overrides any following ones if the same translation is present.
*
* Locales found in:
*         - WP_LANG_DIR/responsi-wp-login-page/responsi-wp-login-page-LOCALE.mo
*          - /wp-content/plugins/responsi-wp-login-page/languages/responsi-wp-login-page-LOCALE.mo (which if not found falls back to)
*          - WP_LANG_DIR/plugins/responsi-wp-login-page-LOCALE.mo
*/
function wp_login_page_addon_load_plugin_textdomain() {
    $locale = apply_filters( 'plugin_locale', get_locale(), 'responsi-wp-login-page' );
    load_textdomain( 'responsi-wp-login-page', WP_LANG_DIR . '/responsi-wp-login-page/responsi-wp-login-page-' . $locale . '.mo' );
    load_plugin_textdomain( 'responsi-wp-login-page', false, RESPONSI_WPLOGIN_FOLDER . '/languages/' );
}

/**
 * Load languages file
 */
function load_plugin_textdomain_responsi_wp_login_page() {
	if ( get_option('responsi_wp_login_page_installed') ) {
		delete_option('responsi_wp_login_page_installed');
		responsi_wp_login_page_install();
	}
	wp_login_page_addon_load_plugin_textdomain();
}
// Add language
add_action('init', 'load_plugin_textdomain_responsi_wp_login_page');

function responsi_wp_login_page_settings_link($links) { 
	$customize_url =  ( ( is_ssl() || force_ssl_admin() ) ? str_replace( 'http:', 'https:', admin_url( 'customize.php' ) ) : str_replace( 'https:', 'http:', admin_url( 'customize.php' ) ) ) ;
  	$customize_url .= '?autofocus[section]=login_sections';
  	$settings_link = '<a href="'.$customize_url.'">'.__( 'Settings', 'responsi-wp-login-page' ).'</a>'; 
  	array_unshift($links, $settings_link); 
  	return $links; 
}
 
add_filter("plugin_action_links_".RESPONSI_WPLOGIN_NAME, 'responsi_wp_login_page_settings_link' );
?>