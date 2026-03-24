<?php
function slg_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'slg_theme_setup' );

function slg_enqueue_assets() {
    $ver = SLG_VERSION;
    $uri = get_template_directory_uri();

    // Styles
    wp_enqueue_style( 'slg-typekit',  'https://use.typekit.net/oan3kdr.css', [], null );
    wp_enqueue_style( 'slg-aos',      'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null );
    wp_enqueue_style( 'slg-module',   $uri . '/assets/css/module.css', [], $ver );
    wp_enqueue_style( 'slg-main',     $uri . '/assets/css/index.css', [ 'slg-module' ], $ver );

    // Scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'slg-gsap',          'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js', [], null, true );
    wp_enqueue_script( 'slg-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js', [ 'slg-gsap' ], null, true );
    wp_enqueue_script( 'slg-aos',           'https://unpkg.com/aos@2.3.1/dist/aos.js', [], null, true );
    wp_enqueue_script( 'slg-main',          $uri . '/assets/js/main.js', [ 'jquery', 'slg-gsap' ], $ver, true );

    // Pass data to JS
    wp_localize_script( 'slg-main', 'slgData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'slg_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'slg_enqueue_assets' );

add_filter( 'show_admin_bar', '__return_false' );

// Remove unnecessary WordPress block styles
function slg_dequeue_block_styles() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'slg_dequeue_block_styles', 20 );

// AJAX handlers
require_once __DIR__ . '/ajax.php';
