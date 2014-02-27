jQuery(document).ready(function($) {
    $("#bbp_topic_subscription").change(function() {
        var dataChecked = {
            action: 'bbpress_auto_subscription_topic_response',
            post_var: 'true'
        };
        var dataUnChecked = {
            action: 'bbpress_auto_subscription_topic_response',
            post_var: 'false'
        };
        if ($("#bbp_topic_subscription").attr("checked")) {
            $.post(the_ajax_script.ajaxurl, dataChecked, function(response) {
            });
        } else {
            $.post(the_ajax_script.ajaxurl, dataUnChecked, function(response) {
            });
        }
        return false;
    });
});