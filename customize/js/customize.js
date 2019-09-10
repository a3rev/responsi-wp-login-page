/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
(function($) {
    function customize_login_page_preview() {
        var css = '';

        if (wp.customize.value('responsi_custom_login_logo')() != '') {
            $("#login h1 a").html('<img src="' + wp.customize.value('responsi_custom_login_logo')() + '" /> <style type="text/css">body #login h1 a { background:none !important; width: auto;height:auto !important;text-indent:inherit !important;}body #login h1 a img{ background:none !important; width: auto;height:auto !important;max-width:100% !important;}</style>');
        } else {
            $("#login h1 a").html(wp.customize.value('blogname')());
        }

        if (wp.customize.value('responsi_custom_login_logo_url')() != '') {
            $("#login h1 a").attr('href', wp.customize.value('responsi_custom_login_logo_url')());
        } else {
            $("#login h1 a").attr('href', 'https://wordpress.org/');
        }

        if (wp.customize.value('responsi_custom_login_logo_title')() != '') {
            $("#login h1 a").attr('title', wp.customize.value('responsi_custom_login_logo_title')());
        } else {
            $("#login h1 a").attr('title', 'Powered by WordPress');
        }

        var bg_css = '';
        bg_css += _cFn.renderBG('responsi_login_page_bg', true);
        if (wp.customize.value('responsi_login_page_bg_image_enable')() == 'true') {
            bg_css += 'background-image:url(' + wp.customize.value('responsi_login_page_bg_image')() + ');';
            bg_css += 'background-attachment:' + wp.customize.value('responsi_login_page_bg_image_attachment')() + ';';
            bg_css += 'background-repeat:' + wp.customize.value('responsi_login_page_bg_image_repeat')() + ';';
            bg_css += 'background-position:' + wp.customize.value('responsi_login_page_bg_image_position_horizontal')() + ' ' + wp.customize.value('responsi_login_page_bg_image_position_vertical')() + ';';
            if (wp.customize.value('responsi_use_login_page_bg_size')() == 'true') {
                bg_css += 'background-size:' + wp.customize.value('responsi_login_page_bg_size_width')() + ' ' + wp.customize.value('responsi_login_page_bg_size_height')() + ';';
            } else {
                bg_css += 'background-size:auto;';
            }
        } else {
            bg_css += 'background-image:url() !important;';
        }

        css += 'html body.login, html body.login.mobile-view #wrap:after {' + bg_css + '}';

        var container_css = '';
        container_css += _cFn.renderBG('responsi_login_page_container_bg', true);
        container_css += _cFn.renderBorderBoxs('responsi_login_page_container_border',true);
        container_css += _cFn.renderShadow('responsi_login_page_container_box_shadow', true);
        container_css += _cFn.renderMarPad('responsi_login_page_container_padding', 'padding', true);

        css += 'html body.login #login { ' + container_css + '}';

        css += '.login form, body.login label, body.login a, .login form .forgetmenot label {';
        css += _cFn.renderTypo('responsi_login_page_container_font', true);
        css += '}';

        css += 'body.login #login * a{';
        css += 'color: ' + wp.customize.value('responsi_login_link_color')() + ' !important;';
        css += '}';

        css += 'body.login #login * a:hover {';
        css += 'color: ' + wp.customize.value('responsi_login_link_hover_color')() + ' !important;';
        css += '}';

        var responsi_login_button_text_transform = wp.customize.value('responsi_login_button_text_transform')();
        var responsi_login_button_color = wp.customize.value('responsi_login_button_color')();
        var responsi_login_button_gradient_from = wp.customize.value('responsi_login_button_gradient_from')();
        var responsi_login_button_gradient_to = wp.customize.value('responsi_login_button_gradient_to')();
        var responsi_login_button_text_shadow = wp.customize.value('responsi_login_button_text_shadow')();
        var login_button_text_shadow = '0 0px 0px rgba(0, 0, 0, 0.25)';
        if (responsi_login_button_text_shadow == 'true') {
            login_button_text_shadow = '0 -1px 1px rgba(0, 0, 0, 0.25)';
        }
        css += 'body.login #login * input[type="submit"]{' + _cFn.renderMarPad('responsi_login_button_padding', 'padding') + _cFn.renderTypo('responsi_login_button_text', true) + 'text-transform: ' + responsi_login_button_text_transform + ';' + _cFn.renderBorder('responsi_login_button_border_top', 'top') + _cFn.renderBorder('responsi_login_button_border_bottom', 'bottom') + _cFn.renderBorder('responsi_login_button_border_left', 'left') + _cFn.renderBorder('responsi_login_button_border_right', 'right') + 'background-color: ' + responsi_login_button_color + ';text-shadow: ' + login_button_text_shadow + ' !important;background: ' + responsi_login_button_color + ';background: linear-gradient( ' + responsi_login_button_gradient_from + ' , ' + responsi_login_button_gradient_to + ' );' + _cFn.renderRadius('responsi_login_button_border_radius_tl', 'top-left') + _cFn.renderRadius('responsi_login_button_border_radius_tr', 'top-right') + _cFn.renderRadius('responsi_login_button_border_radius_bl', 'bottom-left') + _cFn.renderRadius('responsi_login_button_border_radius_br', 'bottom-right') + _cFn.renderShadow('responsi_login_button_border_box_shadow') + '}';

        if ($('#responsi_wplogin_custom_css').length > 0) {
            $('#responsi_wplogin_custom_css').html(css);
        } else {
            $('head').append('<style id="responsi_wplogin_custom_css">' + css + '</style>');
        }
    }
    var fonts_fields = [
        'responsi_login_page_container_font',
        'responsi_login_button_text'
    ];

    var background_fields = [
        'responsi_login_page_bg',
        'responsi_login_page_container_bg'
    ];

    var border_fields = [
        'responsi_login_button_border_top',
        'responsi_login_button_border_bottom',
        'responsi_login_button_border_left',
        'responsi_login_button_border_right',
    ];

    var border_boxes_fields = [
        'responsi_login_page_container_border',
    ];

    var border_radius_fields = [
        'responsi_login_button_border_radius_tl',
        'responsi_login_button_border_radius_tr',
        'responsi_login_button_border_radius_bl',
        'responsi_login_button_border_radius_br',
    ];

    var shadow_fields = [
        'responsi_login_page_container_box_shadow',
        'responsi_login_button_border_box_shadow'
    ];

    var margin_padding_fields = [
        'responsi_login_page_container_padding',
        'responsi_login_button_padding'
    ];

    var single_fields = [
        'responsi_custom_login_logo',
        'responsi_custom_login_logo_url',
        'responsi_custom_login_logo_title',
        'responsi_login_page_bg_image_enable',
        'responsi_login_page_bg_image',
        'responsi_use_login_page_bg_size',
        'responsi_login_page_bg_size_width',
        'responsi_login_page_bg_size_height',
        'responsi_login_page_bg_image_attachment',
        'responsi_login_page_bg_image_position_vertical',
        'responsi_login_page_bg_image_position_horizontal',
        'responsi_login_page_bg_image_repeat',
        'responsi_login_link_color',
        'responsi_login_link_hover_color',
        'responsi_login_button_text_transform',
        'responsi_login_button_color',
        'responsi_login_button_gradient_from',
        'responsi_login_button_gradient_to',
        'responsi_login_button_text_shadow'
    ];

    $.each(fonts_fields, function(inx, val) {
        $.each(ctrlFonts, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(background_fields, function(inx, val) {
        $.each(ctrlBG, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(border_fields, function(inx, val) {
        $.each(ctrlBorder, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(border_boxes_fields, function(inx, val) {
        $.each(ctrlBorderBoxes, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(border_radius_fields, function(inx, val) {
        $.each(ctrlRadius, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(shadow_fields, function(inx, val) {
        $.each(ctrlShadow, function(i, v) {
            wp.customize(val + '[' + v + ']', function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(margin_padding_fields, function(inx, val) {
        $.each(ctrlMarPad, function(i, v) {
            wp.customize(val + v, function(value) {
                value.bind(function(to) {
                    customize_login_page_preview();
                });
            });
        });
    });

    $.each(single_fields, function(inx, val) {
        wp.customize(val, function(value) {
            value.bind(function(to) {
                customize_login_page_preview();
            });
        });
    });
})(jQuery);