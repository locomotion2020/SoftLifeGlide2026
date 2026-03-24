<?php
/**
 * Plugin Name: SLG Product Importer (One-Time Use)
 * Description: Creates WooCommerce categories and products from hardcoded data. DELETE after use.
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $nonce_action = 'slg_import_products';

    if (
        isset( $_POST['slg_run_import'] ) &&
        wp_verify_nonce( $_POST['slg_import_nonce'], $nonce_action )
    ) {
        if ( ! function_exists( 'wc_get_product' ) ) {
            echo '<div class="notice notice-error"><p>WooCommerce is not active.</p></div>';
        } else {
            $result = slg_do_import();
            echo '<div class="notice notice-success"><p><strong>Import complete:</strong> ' . esc_html( $result ) . ' — You can now deactivate and delete this plugin.</p></div>';
        }
    }

    ?>
    <div class="notice notice-warning">
        <p><strong>SLG Product Importer</strong> — Creates 2 product categories and 16 products.</p>
        <form method="post">
            <?php wp_nonce_field( $nonce_action, 'slg_import_nonce' ); ?>
            <input type="hidden" name="slg_run_import" value="1">
            <?php submit_button( 'Run Import Now', 'primary', 'submit', false ); ?>
        </form>
        <p style="color:#b00;">After import, deactivate and delete this plugin.</p>
    </div>
    <?php
} );

function slg_do_import() {
    $data = [
        'Soft Life Glide' => [
            [ 'name' => 'Soft life glide · 001', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Natural', 'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 002', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Noir',    'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 003', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Ivory',   'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 004', 'price' => '138000', 'sold_out' => true,  'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Blush',   'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 005', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Stone',   'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 006', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Sage',    'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 007', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Dusk',    'material' => '100% Silk', 'made_in' => 'Korea' ],
            [ 'name' => 'Soft life glide · 008', 'price' => '138000', 'sold_out' => false, 'desc' => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.', 'color' => 'Mist',    'material' => '100% Silk', 'made_in' => 'Korea' ],
        ],
        'Peace Peace Market' => [
            [ 'name' => 'Peace Peace Market · 001', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Natural', 'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 002', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Ecru',    'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 003', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Sand',    'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 004', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Clay',    'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 005', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Slate',   'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 006', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Moss',    'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 007', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Fog',     'material' => '100% Linen', 'made_in' => 'Korea' ],
            [ 'name' => 'Peace Peace Market · 008', 'price' => '138000', 'sold_out' => false, 'desc' => 'Designed for those who move through the world slowly and on their own terms.', 'color' => 'Bone',    'material' => '100% Linen', 'made_in' => 'Korea' ],
        ],
    ];

    $created = 0;
    $skipped = 0;

    foreach ( $data as $cat_name => $products ) {

        // Get or create product category
        $cat_slug = sanitize_title( $cat_name );
        $cat      = get_term_by( 'slug', $cat_slug, 'product_cat' );

        if ( ! $cat ) {
            $inserted = wp_insert_term( $cat_name, 'product_cat', [ 'slug' => $cat_slug ] );
            $cat_id   = is_wp_error( $inserted ) ? 0 : $inserted['term_id'];
        } else {
            $cat_id = $cat->term_id;
        }

        foreach ( $products as $p ) {
            $sku = sanitize_title( $p['name'] );

            // Skip if SKU already exists
            if ( wc_get_product_id_by_sku( $sku ) ) {
                $skipped++;
                continue;
            }

            $product = new WC_Product_Simple();
            $product->set_name( $p['name'] );
            $product->set_status( 'publish' );
            $product->set_description( $p['desc'] );
            $product->set_regular_price( $p['price'] );
            $product->set_sku( $sku );
            $product->set_manage_stock( false );
            $product->set_stock_status( $p['sold_out'] ? 'outofstock' : 'instock' );

            if ( $cat_id ) {
                $product->set_category_ids( [ $cat_id ] );
            }

            // Local (non-taxonomy) attributes — no taxonomy registration needed
            $attrs = [];
            foreach ( [
                'Color'    => $p['color'],
                'Material' => $p['material'],
                'Made in'  => $p['made_in'],
            ] as $attr_name => $attr_value ) {
                $attr = new WC_Product_Attribute();
                $attr->set_id( 0 );                      // 0 = local attribute
                $attr->set_name( $attr_name );
                $attr->set_options( [ $attr_value ] );
                $attr->set_visible( true );
                $attr->set_variation( false );
                $attrs[] = $attr;
            }
            $product->set_attributes( $attrs );

            $product->save();
            $created++;
        }
    }

    return "Created {$created} products, skipped {$skipped} (already existed).";
}
