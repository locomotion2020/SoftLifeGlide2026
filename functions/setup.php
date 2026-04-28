<?php
function slg_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_image_size( 'slg-product-thumb', 1200, 1600, true );
}
add_action( 'after_setup_theme', 'slg_theme_setup' );

function slg_enqueue_assets() {
    $ver = SLG_VERSION;
    $uri = get_template_directory_uri();

    // Styles
    // wp_enqueue_style( 'slg-typekit',  'https://use.typekit.net/cur3npe.css', [], null );
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

// Adobe font embed
add_action('wp_head', 'slg_head_add_3rd_party_tags');

function slg_head_add_3rd_party_tags() {
    ?>
    <script>
        (function(d) {
            var config = {
            kitId: 'cur3npe',
            scriptTimeout: 3000,
            async: true
            },
            h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
        })(document);
    </script>

<?php
}

// Allow SVG uploads in media library
add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
} );

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    if ( $ext === 'svg' || $ext === 'svgz' ) {
        $data['ext']  = $ext;
        $data['type'] = 'image/svg+xml';
    }
    return $data;
}, 10, 4 );

// Product helpers
require_once __DIR__ . '/products.php';

// AJAX handlers
require_once __DIR__ . '/ajax.php';

// ACF Options Page — Home Navigation
require_once __DIR__ . '/options.php';

// WooCommerce checkout customizations
require_once __DIR__ . '/checkout.php';
