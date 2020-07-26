<?php
/**
 * Plugin Name: JWT based on subscription
 * Plugin URI: https://github.com/achchu93/jwt-for-subscribers
 * Description: An extension to provide JWT based on subscribed users
 * Version: 1.0
 * Author: Ahamed Arshad
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
    register_setting( 'jws-option-group', 'jws_subscription_products' );
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
    $products  = get_option( 'jws_subscription_products', array() );

    if( !$is_active ) {
        return $data;
    }

    $has_subscription = false;

    if( is_array( $products ) && count( $products ) ) {
        foreach( $products as $product_id ) {
            $has_subscription = wcs_user_has_subscription( $user->ID, $product_id, $status );

            // bail out if subsciption is active for any product
            if( $has_subscription ) break;
        }
    }else{
        $has_subscription = wcs_user_has_subscription( $user->ID, '', $status );
    }

    if( !$has_subscription ) {
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


/**
 * Admin settings scripts
 */
function jws_admin_assets() {
    wp_enqueue_style( 'woocommerce_admin_styles' );
    wp_enqueue_script( 'wc-enhanced-select' );
}
add_action( 'admin_enqueue_scripts', 'jws_admin_assets', 99 );