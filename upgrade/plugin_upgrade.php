<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

// Add text at below of plugin row on Plugin Manager page
add_action('install_plugins_pre_plugin-information', array('Responsi_WP_Login_Page_Upgrade', 'display_changelog'));

add_filter('plugins_api_result', array('Responsi_WP_Login_Page_Upgrade', 'make_compatibility'), 11, 3);

// Defined this plugin as external so that WordPress don't call to the WordPress.org Plugin Install API
add_filter( 'plugins_api', array('Responsi_WP_Login_Page_Upgrade', 'is_external'), 11, 3 );

class Responsi_WP_Login_Page_Upgrade
{

	//Displays current version details on Plugin's page
   	public static function display_changelog(){
        if($_REQUEST["plugin"] != get_option('a3rev_responsi_wp_login_page_plugin'))
            return;

        $page_text = self::get_changelog();
        echo $page_text;

        exit;
    }

    public static function get_changelog(){
		$options = array(
			'method' 	=> 'POST',
			'timeout' 	=> 8,
			'body' 		=> array(
							'plugin' 		=> get_option('a3rev_responsi_wp_login_page_plugin'),
							'domain_name'	=> $_SERVER['SERVER_NAME'],
							'address_ip'	=> $_SERVER['SERVER_ADDR'],
						)
				);

        $raw_response = wp_remote_request(RESPONSI_WPLOGIN_MANAGER_URL . "/changelog.php", $options);

        if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code']){
            $page_text = __('Error: ', 'responsi-wp-login-page').' '.$raw_response->get_error_message();
        }else{
            $page_text = $raw_response['body'];
        }
        return stripslashes($page_text);
    }

	public static function make_compatibility( $info, $action, $args ) {
		global $wp_version;
		$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
		$our_plugin_name = get_option('a3rev_responsi_wp_login_page_plugin');
		if ( $action == 'plugin_information' ) {
			if ( version_compare( $wp_version, '3.7', '<=' ) ) {
				if ( is_object( $args ) && isset( $args->slug ) && $args->slug == $our_plugin_name ) {
					$info->tested = $wp_version;
				}
			} elseif ( version_compare( $wp_version, '3.7', '>' ) && is_array( $args ) && isset( $args['body']['request'] ) ) {
				$request = unserialize( $args['body']['request'] );
				if ( $request->slug == $our_plugin_name ) {
					$info->tested = $wp_version;
				}
			}
		}
		return $info;
	}

	public static function is_external( $external, $action, $args ) {
		if ( 'plugin_information' == $action ) {
			if ( is_object( $args ) && isset( $args->slug ) &&  get_option('a3rev_responsi_wp_login_page_plugin') == $args->slug ) {
				global $wp_version;
				$external = array(
					'tested'  => $wp_version
				);
				$external = (object) $external;
			}
		}
		return $external;
	}
}
?>
