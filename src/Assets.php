<?php

namespace src;
class PROJECT_Assets {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
    }
    public function wp_enqueue_scripts() {
        wp_enqueue_style('google-font-poppins', 'https://fonts.googleapis.com/css?family=Poppins&display=swap');
        wp_enqueue_style( 'project-main-style', get_stylesheet_directory_uri() . '/assets/css/index.css', array(), time() );
        wp_enqueue_script( 'project-main-js', get_stylesheet_directory_uri() . '/assets/js/index.js', array(), time() );
        wp_localize_script( 'project-main-js', 'projectData', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'root_url' => get_site_url(),
        ]);
        if ( is_checkout() ) {
            wp_enqueue_style( 'project-extra-style', get_stylesheet_directory_uri() . '/assets/css/checkout.css', [''], time() );
            wp_enqueue_script( 'project-checkout', get_stylesheet_directory_uri() . '/assets/js/checkout.js', [], time() );

        }
        if ( is_account_page() ) {
            wp_enqueue_style( 'project-account', get_stylesheet_directory_uri() . '/assets/css/account.css', [ 'woocommerce-general'], time() );
        }
    }
}
