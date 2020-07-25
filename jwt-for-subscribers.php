<?php
/**
 * Plugin Name: JWT based on subscription
 * Plugin URI: #
 * Description: An extension to provide JWT based on subscribed users
 * Version: 1.0
 * Author: YAhamed Arshad
 * Author URI: https://github.com/achchu93
 */


// Bail if accessed directly 
defined( 'ABSPATH' ) || exit;


// Check dependancies
if( !in_array( 
        'woocommerce-subscriptions/woocommerce-subscriptions.php',
        get_option( 'active_plugins', array() ) 
) ) {
    return;
}

/**
 * Register JWT settings page
 * 
 */
function jws_options_page() {
    add_options_page(
        'JWT Subscriber',
        'JWT Subscriber',
        'manage_options',
        'jwt-subscriber',
        'jws_options_page_callback',
        99
    );
}
add_action( 'admin_menu', 'jws_options_page' );

/**
 * Register JWS settings
 */
function register_jws_settings() {
    register_setting( 'jws-option-group', 'jws_active', array( 'type' => 'boolean', 'default' => false ) );
    register_setting( 'jws-option-group', 'jws_subscription_status', array( 'type' => 'string', 'default' => 'active' ) );
    register_setting( 'jws-option-group', 'jws_unauthorized_message', array( 'type' => 'string', 'default' => 'You are not a subscriber' ) );
}
add_action( 'admin_init', 'register_jws_settings' );

/**
 * JWT settings renderer
 */
function jws_options_page_callback() {
    include_once dirname( __FILE__ ) . '/templates/jws-settings-page.php';
}

/**
 * Validate if the user is subscribed
 */
function jws_validate_user_subscription( $data, $user ) {
    $is_active = get_option( 'jws_active', false );
    $status    = get_option( 'jws_subscription_status', 'active' );
    if( $is_active && !wcs_user_has_subscription( $user->ID, '', $status ) ) {
        return new WP_Error(
            '[jwt_auth] user_not_subscribed',
            get_option( 'jws_unauthorized_message', 'You are not a subscriber' ),
            array(
                'status' => 403,
            )
        );
    }

    return $data;

}
add_filter( 'jwt_auth_token_before_dispatch', 'jws_validate_user_subscription', 10, 2 );