<?php
/**
 * ACF Options Page registration.
 * Field group is defined in acf-json/group_slg_home_nav.json
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', 'slg_register_options_pages' );
function slg_register_options_pages() {
    if ( ! function_exists( 'acf_add_options_page' ) ) return;

    acf_add_options_page( [
        'page_title'  => 'Home Navigation',
        'menu_title'  => 'Home Navigation',
        'menu_slug'   => 'slg-home-navigation',
        'capability'  => 'edit_theme_options',
        'parent_slug' => 'themes.php',
        'redirect'    => false,
    ] );
}
