<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample_Plugin
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?";
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {

	if( !defined( 'RESPONSI_WPLOGIN_TRAVIS' ) ){
		define( 'RESPONSI_WPLOGIN_TRAVIS', true );
	}

	// required woocommerce first
	define( 'WC_TAX_ROUNDING_MODE', 'auto' );
	define( 'WC_USE_TRANSACTIONS', false );

	echo esc_html( 'Loading plugin' . PHP_EOL );
	require dirname( dirname( __FILE__ ) ) . '/responsi-wp-login-page.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

function _manual_install_data() {

	if( !defined( 'RESPONSI_WPLOGIN_TRAVIS' ) ){
		define( 'RESPONSI_WPLOGIN_TRAVIS', true );
	}

	echo esc_html( 'Installing My Plugin Data ...' . PHP_EOL );

	define( 'WP_UNINSTALL_PLUGIN', true );

	// Reload capabilities after install, see https://core.trac.wordpress.org/ticket/28374.
	$GLOBALS['wp_roles'] = null; // WPCS: override ok.
	wp_roles();
}
tests_add_filter( 'setup_theme', '_manual_install_data' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
