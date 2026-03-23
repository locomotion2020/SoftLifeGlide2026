<?php
function slg_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
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
    wp_enqueue_script( 'slg-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', [ 'slg-gsap' ], null, true );
    wp_enqueue_script( 'slg-aos',           'https://unpkg.com/aos@2.3.1/dist/aos.js', [], null, true );
    wp_enqueue_script( 'slg-main',          $uri . '/assets/js/main.js', [ 'jquery', 'slg-gsap' ], $ver, true );
}
add_action( 'wp_enqueue_scripts', 'slg_enqueue_assets' );
