<?php
class WP_Login_Page_Responsi_Customize
{
    public function __construct()
    {
        add_filter('global_responsi_settings', array( $this, 'global_responsi_settings' ));
        
        add_filter('responsi_default_options_wp_login_page', array(
            $this,
            'controls_settings'
        ));
        add_filter('responsi_customize_register_panels', array(
            $this,
            'panels'
        ));
        add_filter('responsi_customize_register_sections', array(
            $this,
            'sections'
        ));
        add_filter('responsi_customize_register_settings', array(
            $this,
            'controls_settings'
        ));
        add_action('customize_preview_init', array(
            $this,
            'customize_preview_init'
        ), 11);
        add_action('customize_controls_enqueue_scripts', array(
            $this,
            'customize_controls_enqueue_scripts'
        ), 11);
    }

    public function customize_controls_enqueue_scripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_script( 'login-page-customize', RESPONSI_WPLOGIN_URL . '/customize/js/customize.logic' . $suffix . '.js', array( 'jquery', 'customize-controls' ), '5.3.0', 1 );
    }

    public function customize_preview_init()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_script('customize-wploginpage-preview', RESPONSI_WPLOGIN_URL . '/customize/js/customize' . $suffix . '.js', array(
            'jquery',
            'customize-preview',
            'responsi-customize-function'
        ), '5.3.0', 1);

    }

    public function global_responsi_settings($options)
    {
        global $responsi_options_wp_login_page;
        $options = array_merge($options, $responsi_options_wp_login_page);
        return $options;
    }

    public function panels($panels)
    {
        $_panels                          = array();
        $panels                           = array_merge($panels, $_panels);
        return $panels;
    }

    public function sections($sections)
    {

        $_sections                       = array();
        $_sections['login_sections'] = array(
            'title' => __('WP Login Page', 'responsi-wp-login-page'),
            'priority' => 16,
            'panel' => 'pages_panel',
        );
        $sections = array_merge($sections, $_sections);
        return $sections;
    }

    public function controls_settings($controls_settings)
    {

        global $responsi_options_wp_login_page;

        $_controls_settings = array();

        $_controls_settings['lbwplogin1'] = array(
            'control' => array(
                'label'      => __('Preview Login Page', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel',
                'input_attrs' => array( 'class' => 'hide')
            ),
            'setting' => array()
        );

        $_controls_settings['responsi_custom_login_preview_active'] = array(
            'control' => array(
                'label'      => __('Preview Login Page', 'responsi-wp-login-page'),
                'description' => __('Turn ON preview Login page and see change style settings.', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'    => 'responsi_custom_login_preview_active',
                'type'       => 'icheckbox',
                'input_attrs' => array( 'class' => 'hide')
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_custom_login_preview_active']) ? $responsi_options_wp_login_page['responsi_custom_login_preview_active'] : 'false',
            )
        );

        $_controls_settings['lbwplogin2'] = array(
            'control' => array(
                'label'      => __('Login Form Container', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel'
            ),
            'setting' => array()
        );
        $_controls_settings['responsi_login_page_container_bg'] = array(
            'control' => array(
                'label'      => __('Background Color', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'ibackground'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_container_bg']) ? $responsi_options_wp_login_page['responsi_login_page_container_bg'] : array( 'onoff' => 'true', 'color' => '#ffffff'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_page_container_border'] = array(
            'control' => array(
                'label' => __('Border', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_container_border']) ? $responsi_options_wp_login_page['responsi_login_page_container_border'] : array('width' => '1','style' => 'solid','color' => '#d2d2d2'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_page_container_border_radius'] = array(
            'control' => array(
                'label'      => __('Border Corner', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border_radius'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_container_border_radius']) ? $responsi_options_wp_login_page['responsi_login_page_container_border_radius'] : array('corner' => 'rounded','rounded_value' => '6'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_page_container_box_shadow'] = array(
            'control' => array(
                'label'      => __('Border Shadow', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'box_shadow'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_container_box_shadow']) ? $responsi_options_wp_login_page['responsi_login_page_container_box_shadow'] : array( 'onoff' => 'true' , 'h_shadow' => '0px' , 'v_shadow' => '1px', 'blur' => '3px' , 'spread' => '0px', 'color' => '#d2d2d2', 'inset' => '' ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_page_container_padding'] = array(
            'control' => array(
                'label'      => __('Padding', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multitext',
                'type'       => 'multitext',
                'choices' => array(
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                    'left' => 'Left',
                    'right' => 'Right',
                )
            ),
            'setting' => array(
                'default'       => array( 
                    isset($responsi_options_wp_login_page['responsi_login_page_container_padding_top']) ? $responsi_options_wp_login_page['responsi_login_page_container_padding_top'] : '40' , 
                    isset($responsi_options_wp_login_page['responsi_login_page_container_padding_bottom']) ? $responsi_options_wp_login_page['responsi_login_page_container_padding_bottom'] : '40',
                    isset($responsi_options_wp_login_page['responsi_login_page_container_padding_left']) ? $responsi_options_wp_login_page['responsi_login_page_container_padding_left'] : '40',
                    isset($responsi_options_wp_login_page['responsi_login_page_container_padding_right']) ? $responsi_options_wp_login_page['responsi_login_page_container_padding_right'] : '40'
                ),
                'transport' => 'postMessage',
            )
        );

        $_controls_settings['lbwplogin3'] = array(
            'control' => array(
                'label'      => __('Login Form Logo', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel'
            ),
            'setting' => array()
        );
        $_controls_settings['responsi_custom_login_logo'] = array(
            'control' => array(
                'label'      => __('Login Form Logo', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_custom_login_logo',
                'type'       => 'iupload'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_custom_login_logo']) ? $responsi_options_wp_login_page['responsi_custom_login_logo'] : '',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_custom_login_logo_url'] = array(
            'control' => array(
                'label'      => __( 'Logo Link URL', 'responsi-wp-login-page' ),
                'section'    => 'login_sections',
                'settings'   => 'responsi_custom_login_logo_url',
                'type'       => 'itext'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_custom_login_logo_url']) ? $responsi_options_wp_login_page['responsi_custom_login_logo_url'] : 'https://wordpress.org/',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_custom_login_logo_title'] = array(
            'control' => array(
                'label'      => __('Logo Hover Message', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_custom_login_logo_title',
                'type'       => 'itext'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_custom_login_logo_title']) ? $responsi_options_wp_login_page['responsi_custom_login_logo_title'] : 'Powered by WordPress',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['lbwplogin4'] = array(
            'control' => array(
                'label'      => __('Login Form Font', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel'
            ),
            'setting' => array()
        );

        $_controls_settings['responsi_login_page_container_font'] = array(
            'control' => array(
                'label' => __('Font', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'typography'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_container_font']) ? $responsi_options_wp_login_page['responsi_login_page_container_font'] : array('size' => '14','line_height' => '1.5','face' => 'Open Sans','style' => 'normal','color' => '#777777'),
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_link_color'] = array(
            'control' => array(
                'label'      => __('Text link Colour', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_link_color',
                'type'       => 'icolor'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_link_color']) ? $responsi_options_wp_login_page['responsi_login_link_color'] : '#999999',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_link_hover_color'] = array(
            'control' => array(
                'label'      => __('Link Colour on Mouse Over', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_link_hover_color',
                'type'       => 'icolor'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_link_hover_color']) ? $responsi_options_wp_login_page['responsi_login_link_hover_color'] : '#0073aa',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['lbwplogin5'] = array(
            'control' => array(
                'label'      => __('Login Button', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel'
            ),
            'setting' => array()
        );
        $_controls_settings['responsi_login_button_text'] = array(
            'control' => array(
                'label' => __('Font', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'typography'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_text']) ? $responsi_options_wp_login_page['responsi_login_button_text'] : array('size' => '13','line_height' => '1','face' => 'Open Sans','style' => 'normal','color' => '#FFFFFF'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_text_shadow'] = array(
            'control' => array(
                'label'      => __('Font Shadow', 'responsi'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_button_text_shadow',
                'type'       => 'icheckbox',
            ),
            'setting' => array(
                'default'       => isset($responsi_options['responsi_login_button_text_shadow']) ? $responsi_options['responsi_login_button_text_shadow'] : 'false',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_text_transform'] = array(
            'control' => array(
                'label'      => __('Transform', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_button_text_transform',
                'type'       => 'iradio',
                'input_attrs' => array(
                    'checked_label' => 'ON',
                    'unchecked_label' => 'OFF',
                    'container_width' => 80
                ),
                'choices' => array( "none" => "None","uppercase" => "Uppercase", "lowercase" => "Lowercase")
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_text_transform']) ? $responsi_options_wp_login_page['responsi_login_button_text_transform'] : 'none',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_color'] = array(
            'control' => array(
                'label'      => __('Base Background Colour', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_button_color',
                'type'       => 'icolor'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_color']) ? $responsi_options_wp_login_page['responsi_login_button_color'] : '#0085ba',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_gradient_from'] = array(
            'control' => array(
                'label'      => __('Gradient from', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_button_gradient_from',
                'type'       => 'icolor'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_gradient_from']) ? $responsi_options_wp_login_page['responsi_login_button_gradient_from'] : '#0085ba',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_gradient_to'] = array(
            'control' => array(
                'label'      => __('Gradient to', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_button_gradient_to',
                'type'       => 'icolor'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_gradient_to']) ? $responsi_options_wp_login_page['responsi_login_button_gradient_to'] : '#0085ba',
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_top'] = array(
            'control' => array(
                'label' => __('Border - Top', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_top']) ? $responsi_options_wp_login_page['responsi_login_button_border_top'] : array('width' => '1','style' => 'solid','color' => '#0073aa'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_bottom'] = array(
            'control' => array(
                'label' => __('Border - Bottom', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_bottom']) ? $responsi_options_wp_login_page['responsi_login_button_border_bottom'] : array('width' => '1','style' => 'solid','color' => '#006799'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_left'] = array(
            'control' => array(
                'label' => __('Border - Left', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_left']) ? $responsi_options_wp_login_page['responsi_login_button_border_left'] : array('width' => '1','style' => 'solid','color' => '#006799'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_right'] = array(
            'control' => array(
                'label' => __('Border - Right', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_right']) ? $responsi_options_wp_login_page['responsi_login_button_border_right'] : array('width' => '1','style' => 'solid','color' => '#006799'),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_radius_tl'] = array(
            'control' => array(
                'label'      => __('Border Corner - Top Left', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border_radius'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_radius_tl']) ? $responsi_options_wp_login_page['responsi_login_button_border_radius_tl'] : array( 'corner' => 'rounded' , 'rounded_value' => 3 ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_radius_tr'] = array(
            'control' => array(
                'label'      => __('Border Corner - Top Right', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border_radius'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_radius_tr']) ? $responsi_options_wp_login_page['responsi_login_button_border_radius_tr'] : array( 'corner' => 'rounded' , 'rounded_value' => 3 ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_radius_bl'] = array(
            'control' => array(
                'label'      => __('Border Corner - Bottom Left', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border_radius'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_radius_bl']) ? $responsi_options_wp_login_page['responsi_login_button_border_radius_bl'] : array( 'corner' => 'rounded' , 'rounded_value' => 3 ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_radius_br'] = array(
            'control' => array(
                'label'      => __('Border Corner - Bottom Right', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'border_radius'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_radius_br']) ? $responsi_options_wp_login_page['responsi_login_button_border_radius_br'] : array( 'corner' => 'rounded' , 'rounded_value' => 3 ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_border_box_shadow'] = array(
            'control' => array(
                'label'      => __('Border Shadow', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'box_shadow'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_button_border_box_shadow']) ? $responsi_options_wp_login_page['responsi_login_button_border_box_shadow'] : array( 'onoff' => 'false' , 'h_shadow' => '0px' , 'v_shadow' => '1px', 'blur' => '0px' , 'spread' => '0px', 'color' => '#006799', 'inset' => '' ),
                'transport' => 'postMessage'
            )
        );
        $_controls_settings['responsi_login_button_padding'] = array(
            'control' => array(
                'label'      => __('Padding', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multitext',
                'type'       => 'multitext',
                'choices' => array(
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                    'left' => 'Left',
                    'right' => 'Right',
                )
            ),
            'setting' => array(
                'default'       => array(
                    isset($responsi_options_wp_login_page['responsi_login_button_padding_top']) ? $responsi_options_wp_login_page['responsi_login_button_padding_top'] : '8' , 
                    isset($responsi_options_wp_login_page['responsi_login_button_padding_bottom']) ? $responsi_options_wp_login_page['responsi_login_button_padding_bottom'] : '8',
                    isset($responsi_options_wp_login_page['responsi_login_button_padding_left']) ? $responsi_options_wp_login_page['responsi_login_button_padding_left'] : '12',
                    isset($responsi_options_wp_login_page['responsi_login_button_padding_right']) ? $responsi_options_wp_login_page['responsi_login_button_padding_right'] : '12'
                ),
                'transport' => 'postMessage',
            )
        );

        $_controls_settings['lbwplogin6'] = array(
            'control' => array(
                'label'      => __('Login Page Background', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'type'       => 'ilabel'
            ),
            'setting' => array()
        );
        $_controls_settings['responsi_login_page_bg'] = array(
            'control' => array(
                'label'      => __('Background Colour', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'multiple',
                'type'       => 'ibackground'
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_bg']) ? $responsi_options_wp_login_page['responsi_login_page_bg'] : array( 'onoff' => 'true', 'color' => '#f1f1f1'),
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_image_enable'] = array(
            'control' => array(
                'label'      => __('Background Image', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'    => 'responsi_login_page_bg_image_enable',
                'type'       => 'icheckbox',
                'input_attrs' => array( 'class' => 'collapsed-custom')
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_bg_image_enable']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image_enable'] : 'true',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_image'] = array(
            'control' => array(
                'label'      => __('Image', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_page_bg_image',
                'type'       => 'iupload',
                'input_attrs' => array( 'class' => 'hide-custom')
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_bg_image']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image'] : '',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_use_login_page_bg_size'] = array(
            'control' => array(
                'label'      => __('Image Resizer', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_use_login_page_bg_size',
                'type'       => 'icheckbox',
                'input_attrs' => array(
                    'class' => 'hide-custom collapsed'
                )
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_use_login_page_bg_size']) ? $responsi_options_wp_login_page['responsi_use_login_page_bg_size'] : 'false',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_size'] = array(
            'control' => array(
                'label'      => __('Image Size', 'responsi-wp-login-page'),
                'description' => __( 'Tip! Use Square large background image with settings 100% width and auto height for best display in all browsers.', 'responsi-wp-login-page' ),
                'section'    => 'login_sections',
                'settings'   => 'multitext',
                'type'       => 'multitext',
                'choices' => array(
                    'width' => 'Width',
                    'height' => 'Height'
                ),
                'input_attrs' => array(
                    'class' => 'hide-custom hide last'
                )
            ),
            'setting' => array(
                'default'       => array( 
                    isset($responsi_options_wp_login_page['responsi_login_page_bg_size_width']) ? $responsi_options_wp_login_page['responsi_login_page_bg_size_width'] : '100%' , 
                    isset($responsi_options_wp_login_page['responsi_login_page_bg_size_height']) ? $responsi_options_wp_login_page['responsi_login_page_bg_size_height'] : 'auto'
                ),
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_image_attachment'] = array(
            'control' => array(
                'label'      => __('Image Attachment', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_page_bg_image_attachment',
                'type'       => 'iswitcher',
                'choices' => array(
                    'checked_value' => 'inherit',
                    'checked_label' => 'SCROLL',
                    'unchecked_value' => 'fixed',
                    'unchecked_label' => 'FIXED',
                    'container_width' => 104,
                ),
                'input_attrs' => array( 'class' => 'hide-custom')
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_bg_image_attachment']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image_attachment'] : 'inherit',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_image_position'] = array(
            'control' => array(
                'label'      => __('Image Alignment', 'responsi-wp-login-page'),
                'description' => __( 'Supports absolute values left, right, center, top, bottom or pixel values e.g. 20px for inner container positioning of image', 'responsi-wp-login-page' ),
                'section'    => 'login_sections',
                'settings'   => 'multitext',
                'type'       => 'multitext',
                'choices' => array(
                    'vertical' => 'Vertical',
                    'horizontal' => 'Horizontal'
                ),
                'input_attrs' => array( 'class' => 'hide-custom')
            ),
            'setting' => array(
                'default'       => array( 
                    isset($responsi_options_wp_login_page['responsi_login_page_bg_image_position_vertical']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image_position_vertical'] : '0' , 
                    isset($responsi_options_wp_login_page['responsi_login_page_bg_image_position_horizontal']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image_position_horizontal'] : '0'
                ),
                'transport' => 'postMessage'
            )
        );

        $_controls_settings['responsi_login_page_bg_image_repeat'] = array(
            'control' => array(
                'label'      => __('Image Repeat', 'responsi-wp-login-page'),
                'section'    => 'login_sections',
                'settings'   => 'responsi_login_page_bg_image_repeat',
                'type'       => 'iradio',
                'input_attrs' => array(
                    'checked_label' => 'ON',
                    'unchecked_label' => 'OFF',
                    'container_width' => 80,
                    'class' => 'hide-custom hide-custom-last',
                ),
                'choices' => array(
                    'no-repeat' => 'No repeat',
                    'repeat' => 'Repeat',
                    'repeat-x' => 'Repeat Horizontally',
                    'repeat-y' => 'Repeat Vertically'
                )
            ),
            'setting' => array(
                'default'       => isset($responsi_options_wp_login_page['responsi_login_page_bg_image_repeat']) ? $responsi_options_wp_login_page['responsi_login_page_bg_image_repeat'] : 'repeat',
                'transport' => 'postMessage'
            )
        );

        $_controls_settings = apply_filters('_wp_login_page_controls_settings', $_controls_settings);
        $controls_settings  = array_merge($controls_settings, $_controls_settings);
        return $controls_settings;
    }
}
new WP_Login_Page_Responsi_Customize();
?>