<?php
class Responsi_WP_Login_Page_Admin {
	var $admin_page;

	public function __construct () {
		$this->init();
	}

	public function init () {
		add_filter( 'responsi_expanded_support',  array( $this, 'responsi_expanded_support_addon' ) );
		add_filter( 'filter_responsi_template_developer', array( &$this, '_add_filter_responsi_template_developer'), 11 );
	}

	public function responsi_expanded_support_addon( $arrays ){
		$arrays['responsithemes_wp_login_page'] = 'wp_login_page';
		return $arrays;
	}

	public static function _add_filter_responsi_template_developer( $framework_options ){
		$shortname = 'framework_a3rev';

		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();

		$add_menu_place = 'Developer Settings Menu';

		$new_options = array();

		foreach ($framework_options as $option) {
			$new_options[] = $option;

			if ($option['name'] == $add_menu_place) {
				$new_options[] = array( 	'name' => __( 'WP Login Page Customize Menu', 'responsi-wp-login-page' ),
					"class" => "visible",
					'id' => $shortname . '_permissions_wp_login_page_addon_roles',
					'std'=> array('administrator'),
					"options" => $roles,
					"placeholder" => 'Select Roles who can access',
					'type' => 'chosen-multicheck' );
			}

		}

		return $new_options;
	}

}
global $responsi_wp_login_page_admin;
$responsi_wp_login_page_admin = new Responsi_WP_Login_Page_Admin();

function _customize_menu_wp_login_page(){
	$_permisstion = true;

	if( function_exists('framework_a3rev_super_user_menu_permission') ){
		if( framework_a3rev_super_user_menu_permission('framework_a3rev_permissions_wp_login_page_addon_roles') ){
			return true;
		}else{
			return false;
		}
	}else{
		return true;
	}
	return true;
}
?>