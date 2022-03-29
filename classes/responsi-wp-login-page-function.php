<?php
/*---------------------------------------------------------------------------------*/
/* WP Login logo */
/*---------------------------------------------------------------------------------*/

if (!function_exists('responsi_custom_login_logo')) {
    function responsi_custom_login_logo()
    {
    	wp_enqueue_style( 'responsi-wp-login-page' );
    	global $responsi_options_wp_login_page;
        $logo = $responsi_options_wp_login_page['responsi_custom_login_logo'];
        if (trim($logo) != '') {
        	?>
			<script type="text/javascript">
				jQuery(window).on('load', function () {
                    jQuery("#login h1 a").html('<img src="<?php echo esc_url($logo); ?>" /> <style type="text/css">body #login h1 a { background:none !important; width: auto;height:auto !important;text-indent:inherit !important;}body #login h1 a img{ background:none !important; width: auto;height:auto !important;max-width:100% !important;}</style>');
                });
			</script>
			<?php
        }
    }
}

/*---------------------------------------------------------------------------------*/
/* WP Login logo URL */
/*---------------------------------------------------------------------------------*/

if ( !function_exists('responsi_custom_login_logo_url') ) {
    function responsi_custom_login_logo_url($text)
    {
    	global $responsi_options_wp_login_page;

        $responsi_custom_login_logo_url = isset( $responsi_options_wp_login_page['responsi_custom_login_logo_url'] ) ? esc_url( $responsi_options_wp_login_page['responsi_custom_login_logo_url'] ) : '';

        return $responsi_custom_login_logo_url; // Escaping via esc_url() is done in wp-login.php.
    }
}

/*---------------------------------------------------------------------------------*/
/* WP Login logo title */
/*---------------------------------------------------------------------------------*/

if (!function_exists('responsi_custom_login_logo_title')) {
    function responsi_custom_login_logo_title($text)
    {
    	global $responsi_options_wp_login_page;

        $responsi_custom_login_logo_title = isset( $responsi_options_wp_login_page['responsi_custom_login_logo_title'] ) ? esc_attr( $responsi_options_wp_login_page['responsi_custom_login_logo_title'] ) : '';

        return $responsi_custom_login_logo_title; // Escaping via esc_attr() is done in wp-login.php.
    }
}

function responsi_custom_login_enqueue_scripts() {
    wp_enqueue_script('jquery' );
    global $responsi_options_wp_login_page;
	if ('' != $responsi_options_wp_login_page['responsi_custom_login_logo']) {
        add_action('login_head', 'responsi_custom_login_logo');
    }
	if ('' != $responsi_options_wp_login_page['responsi_custom_login_logo_url']) {
        add_filter('login_headerurl', 'responsi_custom_login_logo_url', 10);
    }
	if ('' != $responsi_options_wp_login_page['responsi_custom_login_logo_title']) {
        add_filter('login_headertext', 'responsi_custom_login_logo_title', 10);
    }
}

add_action('login_enqueue_scripts', 'responsi_custom_login_enqueue_scripts');
?>