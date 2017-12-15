(function($) {
    
    customizeLoginPageLogic = {};
   	
   	wp.customize.bind( 'responsi-customize-ready', function() {

        $('li#accordion-section-login_sections').on( 'collapsed', function(){
            if( 'undefined' !== typeof wp.customize.control('responsi_custom_login_preview_active') ){
                wp.customize.control('responsi_custom_login_preview_active').setting.set('false');
            }
        });

        $('li#accordion-section-login_sections').on( 'expanded', function(){
        	if( 'undefined' !== typeof wp.customize.control('responsi_custom_login_preview_active') ){
                wp.customize.control('responsi_custom_login_preview_active').setting.set('true');
            }
        });

    });
    
}(jQuery));