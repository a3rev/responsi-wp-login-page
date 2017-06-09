(function($) {
    customizeLoginPageLogic = {};
    $(document).ready(function() {
        $('li#accordion-section-login_sections').on('collapsed', function() {
            $('.iPhoneCheckContainer #responsi_custom_login_preview_active').removeAttr('checked').iphoneStyle("refresh");
        });
        $('li#accordion-section-login_sections').on('expanded', function() {
            $('.iPhoneCheckContainer #responsi_custom_login_preview_active').prop('checked', true).iphoneStyle("refresh");
        });
    });
}(jQuery));