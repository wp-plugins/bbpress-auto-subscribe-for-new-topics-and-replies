<?php
/*
  Plugin Name: bbPress auto subscribe for new topics and replies
  Plugin URI: http://www.ifs-net.de
  Description: Automatically check the subscription checkbox for new bbpress topics or bbpress replies. Saves the last state via ajax for each user and for new topics and new replies.
  Version: 1.0
  Author: Florian Schießl
  Author URI: http://www.ifs-net.de
  License: OpenSource under GPL2
 */

/**
 * Actions
 */

add_action('wp_print_scripts', 'bbpress_auto_subscription_ajax_load_scripts');
add_action('wp_ajax_bbpress_auto_subscription_topic_response', 'bbpress_auto_subscription_topic_ajax_process_request');
add_action('bbp_theme_after_topic_form_subscriptions', 'bbpress_auto_subscription_topic');
add_action('bbp_theme_after_reply_form_subscription', 'bbpress_auto_subscription_reply');

/**
 * FUNCTIONS
 */

/**
 * Load ajax scripts
 */
function bbpress_auto_subscription_ajax_load_scripts() {
    // load jquery file that sends the $.post request
    wp_enqueue_script("bbpress-auto-subscription-ajax-handle", plugin_dir_url(__FILE__) . 'js/bbpress-auto-subscription.js', array('jquery'));

    // make ajaxurl variable available
    wp_localize_script('bbpress-auto-subscription-ajax-handle', 'the_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
}

/**
 * Process the ajax request when the checkbox for subscriptions is changed
 */
function bbpress_auto_subscription_topic_ajax_process_request() {
    $current_user = wp_get_current_user();
    if (isset($_POST["post_var"])) {
        $response = $_POST["post_var"];
        // update user preferences
        update_user_meta($current_user->ID, 'bbpress_auto_subscription_topic', $response);
        die();
    }
}

/**
 * Generic function to avoid duplicated code
 * @param type $meta_key
 */
function bbpress_auto_subscription_generic($meta_key) {
    $current_user = wp_get_current_user();
    // get user setting
    $setting = get_user_meta($current_user->ID, $meta_key, true);
    if (!($setting) || (($setting != 'true') && ($setting != 'false'))) {
        // default: checkbox is checked
        $setting = 'true';
        update_user_meta($current_user->ID, $meta_key, $setting);
    }
    // if subscription is set or was set by default activate the checkbox
    if ($setting == 'true') {
        echo '<script type="text/javascript">jQuery("#bbp_topic_subscription").prop("checked","checked");</script>';
    }
}

/**
 * This action is for new created topics
 */
function bbpress_auto_subscription_topic() {
    $meta_key = 'bbpress_auto_subscription_topic';
    bbpress_auto_subscription_generic($meta_key);
}

/**
 * This action is for new resplies to existing topics
 */
function bbpress_auto_subscription_reply() {
    $meta_key = 'bbpress_auto_subscription_reply';
    bbpress_auto_subscription_generic($meta_key);
}


?>