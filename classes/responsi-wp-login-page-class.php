<?php
class Responsi_WP_Login_Page {
	var $admin_page;

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'login_enqueue_scripts',array( $this,'customize_options'), 9 );
		add_action( 'init',array( $this,'customize_options'), 2 );
		add_action( 'responsi_after_setup_theme', array( $this,'responsi_build_css_theme_actived') );
		add_filter( 'responsi_google_webfonts', array( $this,'responsi_addon_google_webfonts'));
		add_action( 'wp_enqueue_scripts',  array( $this, 'customize_preview_inline_style'), 11 );
		add_action( 'login_enqueue_scripts',  array( $this, 'customize_preview_inline_style'), 11 );
		add_action( 'template_include',       array( $this, 'on_active_template_preview'),99 );
		add_action( 'customize_save_after', array( $this, 'responsi_customize_save_options') );
	}

	public function responsi_customize_save_options( $settings ) {

		$slug = 'wp_login_page';

	    global ${'responsi_options_' . $slug}, $wp_customize;

		$post_value = array();

		if( isset($_POST['customized']) ){
			$post_value = json_decode( wp_unslash( $_POST['customized'] ), true );
			$post_value = apply_filters( 'responsi_customized_post_value', $post_value );
		}else{
			$post_value = $settings->changeset_data();
			$post_value = apply_filters( 'responsi_customized_changeset_data_value', $post_value );
		}

		if( is_array( ${'responsi_options_' . $slug} ) && count( ${'responsi_options_' . $slug} ) > 0 && is_array( $post_value ) && count( $post_value ) > 0 ){
			
			add_filter( 'default_settings_' . $slug, create_function( '', 'return true;' ) );
			
			$_default_options = responsi_default_options( $slug );

			if ( defined( str_replace("-","_", get_stylesheet() ) . '_' . $slug ) ) {
				if ( function_exists('default_option_child_theme') ){
					$_default_options = array_replace_recursive( $_default_options, default_option_child_theme() );
				}
			}

			if( is_array( $_default_options ) && count( $_default_options ) > 0 ){
				
				$new_options = get_option( $slug . '_'.get_stylesheet(), array() );

				$build_sass = false;

				if( is_array( $new_options ) ){
					
					if ( is_array($post_value) ) {
						
						if ( is_object( $post_value ) ){
			                $post_value = clone $post_value;
			            }

			            if( is_array( $post_value ) && isset( $post_value['responsi_custom_login_preview_active'] ) ){
							$post_value['responsi_custom_login_preview_active'] = 'false';
						}

			            foreach( $post_value as $key => $value ){
							if( array_key_exists( $key, $_default_options ) ){

								if( is_array( $value ) && isset( $new_options[$key] ) && is_array( $new_options[$key] ) ){
									$new_options[$key] = array_merge( $new_options[$key], $value );
								}else{
									$new_options[$key] = $value;
								}
								$build_sass = true;
							}
						}

						$_customize_options = array_replace_recursive( ${'responsi_options_' . $slug}, $post_value );
						foreach( $_customize_options as $key => $value ){
							if( array_key_exists( $key, $_default_options )){
								if( isset( $new_options[$key] ) ){
									if( is_array( $new_options[$key] ) && is_array( $_default_options[$key] ) ){
										$new_opt = array_diff_assoc( $new_options[$key], $_default_options[$key] );
										if( is_array( $new_opt ) && count( $new_opt ) > 0 ){
											$new_options[$key] = $new_opt;
										}else{
											unset($new_options[$key]);
										}
									}else{
										if( !is_array( $new_options[$key] ) && !is_array($_default_options[$key]) && $new_options[$key] == $_default_options[$key] ){
											unset($new_options[$key]);
										}
									}
								}
								delete_option( $key );
							}
						}
					}
				}

				if( $wp_customize && !$wp_customize->is_theme_active()){
					$build_sass = true;
				}

				if( $build_sass ) {
					update_option( $slug . '_'.get_stylesheet(), $new_options );
					$this->responsi_dynamic_css();
					do_action( $slug . '_build_dynamic_css_success', $post_value );
				}
			}
		}
	}

	public function responsi_dynamic_css() {
	    $dynamic_css      = '';
	    $dynamic_css = $this->responsi_build_dynamic_css();
	    if ( '' !== $dynamic_css ) {
	        set_theme_mod( 'wp_login_page_custom_css', $dynamic_css );
	    }
	}

	public function customize_options(){

		$slug = 'wp_login_page';

	    global ${'responsi_options_' . $slug}, $wp_customize;

	    if( !function_exists('responsi_default_options') ){
	    	return;
	    }

	    $_childthemes = get_stylesheet();

	    $_default_options = responsi_default_options( $slug );

	    $_customize_options = $_default_options;

		if ( defined( str_replace("-","_", $_childthemes ) . '_' . $slug ) ) {
			if ( function_exists('default_option_child_theme') ){
				$_customize_options = array_replace_recursive( $_customize_options, default_option_child_theme() );
			}
		}

		$responsi_mods = get_option( $slug . '_'.$_childthemes, array() );

	    if( is_array( $responsi_mods ) ){
	        $_customize_options = array_replace_recursive( $_customize_options, $responsi_mods );
	    }
	    
	    if( 'responsi-blank-child' == $_childthemes ){
	        $responsi_mods = get_option( $slug .'_responsi', array() );
	        $responsi_blank_child =  get_option( $slug . '_responsi-blank-child', array() );
	        if( is_array($responsi_mods) ){
	            $_customize_options = array_replace_recursive( $_customize_options, $responsi_mods );
	        }
	        if( is_array($responsi_blank_child) ){
	            $_customize_options = array_replace_recursive( $_customize_options, $responsi_blank_child );
	        }
	    }

	    if( is_customize_preview() && ( isset( $_REQUEST['changeset_uuid'] ) || isset( $_REQUEST['customize_changeset_uuid'] ) ) ){
	        $changeset_data = $wp_customize->changeset_data();
	        if ( is_array($changeset_data) ) {
	            if( count( $changeset_data ) > 0 ){
	                $_customize_options_preview = array();
	                foreach ( $changeset_data as $setting_id => $setting_params ){
	                    if ( ! array_key_exists( 'value', $setting_params ) ) {
	                        continue;
	                    }
	                    if ( isset( $setting_params['type'] ) && 'theme_mod' === $setting_params['type'] ) {
							$namespace_pattern = '/^(?P<stylesheet>.+?)::(?P<setting_id>.+)$/';
							if ( preg_match( $namespace_pattern, $setting_id, $matches ) && get_stylesheet() === $matches['stylesheet'] ) {
								$_customize_options_preview[ $matches['setting_id'] ] = $setting_params['value'];
							}
						} else {
							$_customize_options_preview[ $setting_id ] = $setting_params['value'];
						}
	                }
	                $_customize_options_preview = apply_filters( 'responsi_customized_post_value', $_customize_options_preview );
	                if ( is_array($_customize_options) && is_array( $_customize_options_preview ) && count( $_customize_options_preview ) > 0 ) {
	                    if ( is_object( $_customize_options_preview ) ){
	                        $_customize_options_preview = clone $_customize_options_preview;
	                    }
	                    $_customize_options = array_replace_recursive( $_customize_options, $_customize_options_preview );
	                }
	            }
	        }
	    }

	    if (isset($_POST['customized'])) {
	        
	        $post_value = json_decode(wp_unslash($_POST['customized']), true);
	        $post_value = apply_filters('responsi_customized_post_value', $post_value);
	        if ( is_array($_customize_options) && is_array($post_value) ) {
	            if ( is_object( $post_value ) ){
	                $post_value = clone $post_value;
	            }
	            $_customize_options = array_replace_recursive($_customize_options, $post_value);
	        }

	    }

	    ${'responsi_options_' . $slug} = $_customize_options;

	    return ${'responsi_options_' . $slug};
	}

	public function responsi_build_dynamic_css( $preview = false ) {

		global $responsi_options, $responsi_options_wp_login_page;

	    if( !function_exists('responsi_default_options') ){
	    	return;
	    }

	    /*if ( !$preview ) {
	        $responsi_options_wp_login_page = $this->customize_options();
	    } else {
	        global $responsi_options_wp_login_page;
	    }*/
	    
	    if (!is_array($responsi_options_wp_login_page)) {
            return '';
        }

		$output = '';
		if( is_array ($responsi_options_wp_login_page['responsi_login_page_bg']) ){
			$responsi_login_page_bg = $responsi_options_wp_login_page['responsi_login_page_bg'];
		}else{
			$responsi_login_page_bg = array( 'onoff' => 'true', 'color' => '#ffffff' );
		}

		$responsi_login_page_bg_image_enable = $responsi_options_wp_login_page['responsi_login_page_bg_image_enable'];
		$responsi_login_page_bg_image = $responsi_options_wp_login_page['responsi_login_page_bg_image'];
		$responsi_login_page_bg_image_attachment = $responsi_options_wp_login_page['responsi_login_page_bg_image_attachment'];
		$responsi_login_page_bg_image_position_horizontal = $responsi_options_wp_login_page['responsi_login_page_bg_image_position_horizontal'];
		$responsi_login_page_bg_image_position_vertical = $responsi_options_wp_login_page['responsi_login_page_bg_image_position_vertical'];
		$responsi_login_page_bg_image_repeat = $responsi_options_wp_login_page['responsi_login_page_bg_image_repeat'];

		$responsi_use_login_page_bg_size = 'false';
	    if( $responsi_options_wp_login_page['responsi_use_login_page_bg_size']){
	        $responsi_use_login_page_bg_size    = $responsi_options_wp_login_page['responsi_use_login_page_bg_size'];
	    }

	    $responsi_login_page_bg_size_width    = 'auto';
	    if( $responsi_options_wp_login_page['responsi_login_page_bg_size_width']){
	        $responsi_login_page_bg_size_width    =  strtolower( trim($responsi_options_wp_login_page['responsi_login_page_bg_size_width']) );
	    }
	    $responsi_login_page_bg_size_height    = 'auto';
	    if( $responsi_options_wp_login_page['responsi_login_page_bg_size_height']){
	        $responsi_login_page_bg_size_height    =  strtolower( trim($responsi_options_wp_login_page['responsi_login_page_bg_size_height']) );
	    }
	    $responsi_login_page_bg_size = '';
	    if($responsi_use_login_page_bg_size == 'true'){
	        $responsi_login_page_bg_size = 'background-size:'. $responsi_login_page_bg_size_width.' '. $responsi_login_page_bg_size_height.';';
	    }

		$bg_css = '';
		if ($responsi_login_page_bg ) $bg_css .= responsi_generate_background_color( $responsi_login_page_bg );
		if( $responsi_login_page_bg_image_enable == 'true' ){
			if ($responsi_login_page_bg_image ) $bg_css .= 'background-image:url('.$responsi_login_page_bg_image.');';
			if ($responsi_login_page_bg_image_attachment ) $bg_css .= 'background-attachment:'.$responsi_login_page_bg_image_attachment.';';
			if ($responsi_login_page_bg_image_position_horizontal != '' && $responsi_login_page_bg_image_position_vertical != '' ) {
				$bg_css .= 'background-position:'.strtolower( $responsi_login_page_bg_image_position_horizontal ).' '.strtolower( $responsi_login_page_bg_image_position_vertical ).';';
			}
			if ($responsi_login_page_bg_image_repeat ) {
				$bg_css .= 'background-repeat:'.$responsi_login_page_bg_image_repeat.';';
			}
			$bg_css .= $responsi_login_page_bg_size;
		}
		$output .= '
		html body.login {'.$bg_css.'}
		body.login.mobile-view #wrap:after{
		    content:"";
		    z-index: -1;
		    position: fixed;
		    top: 0;
		    left: 0;
		    height: 100%;
		    width: 100%;
		    '.$bg_css.'
		    background-attachment: inherit !important;
		}
		';

	    $responsi_login_page_container_bg        = $responsi_options_wp_login_page['responsi_login_page_container_bg'];
	    if( !is_array( $responsi_login_page_container_bg ) ){
	        $responsi_login_page_container_bg = array( 'onoff' => 'true', 'color' => $responsi_login_page_container_bg );
	    }
	    $responsi_login_page_container_border        = $responsi_options_wp_login_page['responsi_login_page_container_border'];
	    $responsi_login_page_container_box_shadow        = $responsi_options_wp_login_page['responsi_login_page_container_box_shadow'];
	    $responsi_login_page_container_padding_top                 = $responsi_options_wp_login_page['responsi_login_page_container_padding_top'];
	    $responsi_login_page_container_padding_bottom              = $responsi_options_wp_login_page['responsi_login_page_container_padding_bottom'];
	    $responsi_login_page_container_padding_left                = $responsi_options_wp_login_page['responsi_login_page_container_padding_left'];
	    $responsi_login_page_container_padding_right               = $responsi_options_wp_login_page['responsi_login_page_container_padding_right'];
	    $container_css = '';
	    $container_css .= responsi_generate_background_color( $responsi_login_page_container_bg );
	    $container_css .= responsi_generate_border_boxes($responsi_login_page_container_border);
	    $container_css .= responsi_generate_box_shadow($responsi_login_page_container_box_shadow, false);
	    $container_css .= '
			padding-top:' . $responsi_login_page_container_padding_top . 'px;
			padding-bottom:' . $responsi_login_page_container_padding_bottom . 'px;
			padding-left:' . $responsi_login_page_container_padding_left . 'px;
			padding-right:' . $responsi_login_page_container_padding_right . 'px;
		';

		$output .= '
		html body.login #login {
			'.$container_css.'
			border-collapse: separate;
			clear: both;
			display: block;
			margin-bottom: 24px;
			position: relative;
			text-align: left;
		}
		.login form:after {
			clear: both;
			content: ".";
			display: block;
			height: 0;
			overflow: hidden;
			visibility: hidden;
		}
		html body.login form, html body.login form.loginform, html body.login #login form.loginform {
			background: none repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
			border: 0 none !important;
			box-shadow:none !important;
			margin: 0 0 20px !important;
			padding: 0 !important;
		}
		.login #nav, .login #backtoblog {
			clear: both;
			margin: 0;
			padding: 10px 0 0;
			text-align: center;
		}
		html body.login {
			padding-top: 114px;
		}
		body #login h1:first-child a:first-child{
			max-width:100%;
			padding:0 !important;
		}
		body.login h1 a{
			margin-bottom:10px !important;
		}
		.login #login_error,.login .message{margin-bottom:15px !important;}
		.login #registerform p > label{margin-right: 5px;}';

		$responsi_login_page_container_font               = $responsi_options_wp_login_page['responsi_login_page_container_font'];
		$responsi_login_link_color               = $responsi_options_wp_login_page['responsi_login_link_color'];
		$responsi_login_link_hover_color               = $responsi_options_wp_login_page['responsi_login_link_hover_color'];
		$output .= '.login form, body.login label, body.login a, .login form .forgetmenot label {
			'.responsi_generate_fonts($responsi_login_page_container_font ).'
			vertical-align: baseline;
		}';
		$output .= 'body.login #login * a {
			color:'. $responsi_login_link_color .' !important;
		}';
		$output .= 'body.login #login * a:hover {
			color:'. $responsi_login_link_hover_color .' !important;
		}';

		$responsi_login_button_text           = $responsi_options_wp_login_page['responsi_login_button_text'];
	    $responsi_login_button_text_transform = $responsi_options_wp_login_page['responsi_login_button_text_transform'];
	    $button = $responsi_options_wp_login_page['responsi_login_button_color'];

	    $login_button_text_shadow = '0 0px 0px rgba(0, 0, 0, 0.25)';
        if( isset( $responsi_options_wp_login_page['responsi_login_button_text_shadow'] ) && $responsi_options_wp_login_page['responsi_login_button_text_shadow'] == 'true' ){
            $login_button_text_shadow = '0 -1px 1px rgba(0, 0, 0, 0.25)';
        }

	    if ($button == '')
	        $button = 'transparent';
	    $responsi_login_button_gradient_from = $responsi_options_wp_login_page['responsi_login_button_gradient_from'];
	    if ($responsi_login_button_gradient_from == '')
	        $responsi_login_button_gradient_from = 'transparent';
	    $responsi_login_button_gradient_to = $responsi_options_wp_login_page['responsi_login_button_gradient_to'];
	    if ($responsi_login_button_gradient_to == '')
	        $responsi_login_button_gradient_to = 'transparent';
	    $responsi_login_button_padding_top    = $responsi_options_wp_login_page['responsi_login_button_padding_top'];
	    $responsi_login_button_padding_bottom = $responsi_options_wp_login_page['responsi_login_button_padding_bottom'];
	    $a_responsi_login_button_padding_top    = $responsi_login_button_padding_top + 1;
	    $a_responsi_login_button_padding_bottom = $responsi_login_button_padding_bottom + 1;
	    $responsi_login_button_padding_left  = $responsi_options_wp_login_page['responsi_login_button_padding_left'];
	    $responsi_login_button_padding_right = $responsi_options_wp_login_page['responsi_login_button_padding_right'];
	    $responsi_login_button_border_top    = $responsi_options_wp_login_page['responsi_login_button_border_top'];
	    $responsi_login_button_border_bottom = $responsi_options_wp_login_page['responsi_login_button_border_bottom'];
	    $responsi_login_button_border_left   = $responsi_options_wp_login_page['responsi_login_button_border_left'];
	    $responsi_login_button_border_right  = $responsi_options_wp_login_page['responsi_login_button_border_right'];
	    $responsi_login_button_border_radius_tl = responsi_generate_border_radius_value($responsi_options_wp_login_page['responsi_login_button_border_radius_tl']);
	    $responsi_login_button_border_radius_tr = responsi_generate_border_radius_value($responsi_options_wp_login_page['responsi_login_button_border_radius_tr']);
	    $responsi_login_button_border_radius_bl = responsi_generate_border_radius_value($responsi_options_wp_login_page['responsi_login_button_border_radius_bl']);
	    $responsi_login_button_border_radius_br = responsi_generate_border_radius_value($responsi_options_wp_login_page['responsi_login_button_border_radius_br']);
	    $responsi_login_button_border_box_shadow = responsi_generate_box_shadow($responsi_options_wp_login_page['responsi_login_button_border_box_shadow'], false);

	    $output .= '
		body.login #login * input[type="submit"]{
			' . responsi_generate_fonts($responsi_login_button_text, true) . '
			text-transform: ' . $responsi_login_button_text_transform . ';
			text-shadow: '. $login_button_text_shadow .';
			padding-top:' . $responsi_login_button_padding_top . 'px;
			padding-bottom:' . $responsi_login_button_padding_bottom . 'px;
			padding-left:' . $responsi_login_button_padding_left . 'px;
			padding-right:' . $responsi_login_button_padding_right . 'px;
			background-color: ' . $button . ';
			background: linear-gradient( ' . $responsi_login_button_gradient_from . ' , ' . $responsi_login_button_gradient_to . ' );
			border-top:' . $responsi_login_button_border_top["width"] . 'px ' . $responsi_login_button_border_top["style"] . ' ' . $responsi_login_button_border_top["color"] . ';
			border-bottom:' . $responsi_login_button_border_bottom["width"] . 'px ' . $responsi_login_button_border_bottom["style"] . ' ' . $responsi_login_button_border_bottom["color"] . ';
			border-left:' . $responsi_login_button_border_left["width"] . 'px ' . $responsi_login_button_border_left["style"] . ' ' . $responsi_login_button_border_left["color"] . ';
			border-right:' . $responsi_login_button_border_right["width"] . 'px ' . $responsi_login_button_border_right["style"] . ' ' . $responsi_login_button_border_right["color"] . ';
			border-radius:' . $responsi_login_button_border_radius_tl . ' ' . $responsi_login_button_border_radius_tr . ' ' . $responsi_login_button_border_radius_br . ' ' . $responsi_login_button_border_radius_bl . ';
			' . $responsi_login_button_border_box_shadow . '
			cursor: pointer;
			height:auto;
		}';

		if( function_exists('responsi_minify_css') ){
        	$output = responsi_minify_css( $output );
        }

		return $output;
	}

	public function responsi_build_css_theme_actived(){
		$this->responsi_dynamic_css();
	}

	public function build_css_after_addon_updated(){
		$this->responsi_dynamic_css();
	}

	public function customize_preview_inline_style(){
		//login_enqueue_scripts
		if ( is_customize_preview() ) {
			wp_add_inline_style( 'customize-preview', $this->responsi_build_dynamic_css( true ) );
		} else {
			$wp_login_page_custom_css = get_theme_mod( 'wp_login_page_custom_css' );
			if ( false === $wp_login_page_custom_css ) {
				$this->responsi_dynamic_css();
				wp_add_inline_style( 'login', $this->responsi_build_dynamic_css( true ) );
			}else{
				wp_add_inline_style( 'login', get_theme_mod( 'wp_login_page_custom_css' ) );
			}
		}
	}

	public function responsi_addon_google_webfonts( $options ){
		global $responsi_options_wp_login_page;
		if( is_array( $options ) && is_array( $responsi_options_wp_login_page ) ){
			$options  = array_merge( $options, $responsi_options_wp_login_page );
		}
		return $options;
	}

	public function on_active_template_preview( $template ) {
		global $current_user, $responsi_options_wp_login_page;
		if( $responsi_options_wp_login_page['responsi_custom_login_preview_active'] == 'true' && is_customize_preview() ){
			add_action('login_head','wp_head');
			add_action('login_footer','wp_footer');
			ini_set('error_reporting', 0);
			return ( ABSPATH . '/wp-login.php' );
		}
		return $template;
	}

	public function responsi_wp_login_page_upgrade(){

	    $upgrade = get_option('responsi_wp_login_page_upgrade_border_boxes' );
	    
	    if( $upgrade != 'done' ){
	       
	        $responsi_options_wp_login_page = get_option( 'wp_login_page_'.get_stylesheet(), array());

	        $list_options = array( 
	            'responsi_login_page_container_border'       			=> 'responsi_login_page_container_border_radius',
	        );

	        foreach ( $list_options as $border => $corner ) {
	            
	            if( !is_array( $corner ) ){

	                $corners = array();

	                if ( isset( $responsi_options_wp_login_page[ $corner ] ) && isset( $responsi_options_wp_login_page[ $border ] ) ){
	                
	                    if( isset( $responsi_options_wp_login_page[ $corner ]['corner'] ) && $responsi_options_wp_login_page[ $corner ]['corner'] == 'rounded' && isset( $responsi_options_wp_login_page[ $corner ]['rounded_value'] ) ){
	                        $corners = array( 'corner' => 'rounded', 'topleft' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'topright' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'bottomright' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'bottomleft' => $responsi_options_wp_login_page[ $corner ]['rounded_value'] );
	                        $responsi_options_wp_login_page[$border] = array_merge( $responsi_options_wp_login_page[ $border ], $corners );
	                    }
	                    unset( $responsi_options_wp_login_page[$corner] );
	                
	                }elseif ( isset( $responsi_options_wp_login_page[ $corner ] ) && !isset( $responsi_options_wp_login_page[ $border ] ) ){

	                    if( isset( $responsi_options_wp_login_page[ $corner ]['corner'] ) && $responsi_options_wp_login_page[ $corner ]['corner'] == 'rounded' && isset( $responsi_options_wp_login_page[ $corner ]['rounded_value'] ) ){
	                        $corners = array( 'corner' => 'rounded', 'topleft' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'topright' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'bottomright' => $responsi_options_wp_login_page[ $corner ]['rounded_value'], 'bottomleft' => $responsi_options_wp_login_page[ $corner ]['rounded_value'] );
	                        $responsi_options_wp_login_page[$border] = array_merge( array('width' => '0','style' => 'solid','color' => '#DBDBDB'), $corners );
	                    }
	                    unset( $responsi_options_wp_login_page[$corner] );
	                }
	                
	            }
	            
	        }

	        update_option( 'wp_login_page_'.get_stylesheet(), $responsi_options_wp_login_page );

	        $upgrade = update_option('responsi_wp_login_page_upgrade_border_boxes', 'done' );
	    }

	}
}
global $responsi_wp_login_page;
$responsi_wp_login_page = new Responsi_WP_Login_Page();
?>
