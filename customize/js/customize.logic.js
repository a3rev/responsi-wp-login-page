(function($) {
    customizeLoginPageLogic = {};
    $( window ).on( 'load', function() {

        $('li#accordion-section-login_sections').on( 'collapsed', function(){
            $('#responsi_custom_login_preview_active').val('false').trigger("change");
        });

        $('li#accordion-section-login_sections').on( 'expanded', function(){
            $('#responsi_custom_login_preview_active').val('true').trigger("change");
        });

    });
}(jQuery));