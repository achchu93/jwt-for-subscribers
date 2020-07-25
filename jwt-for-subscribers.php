<?php
/**
 * Plugin Name: JWT based on subscription
 * Plugin URI: #
 * Description: An extension to provide JWT based on subscribed users
 * Version: 1.0
 * Author: YAhamed Arshad
 * Author URI: https://github.com/achchu93
 */


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
 * JWT settings renderer
 */
function jws_options_page_callback() {
    echo '<div class="wrap"><h2>JWT for Subscriber Settings</h2></div>';
}