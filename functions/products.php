<?php
/**
 * WooCommerce product helper functions.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get published products by WooCommerce category slug.
 */
function slg_get_products_by_category( $category_slug ) {
    if ( ! function_exists( 'wc_get_products' ) ) return [];
    return wc_get_products( [
        'status'   => 'publish',
        'limit'    => -1,
        'category' => [ $category_slug ],
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ] );
}

/**
 * Render a single product card for the product list modal.
 * Uses the 'slg-product-thumb' image size (1200×1600, 3:4 crop).
 */
function slg_render_product_card( $product ) {
    $image_id  = $product->get_image_id();
    $thumb_url = $image_id
        ? wp_get_attachment_image_url( $image_id, 'slg-product-thumb' )
        : wc_placeholder_img_src( 'slg-product-thumb' );
    $in_stock  = $product->is_in_stock();
    ?>
    <div class="modal-item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" />
        <div class="modal-info">
            <p class="product-name"><?php echo esc_html( $product->get_name() ); ?></p>
            <p class="product-price">
                <?php if ( ! $in_stock ) : ?>
                    <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                    <span class="sold-out">Sold Out</span>
                <?php else : ?>
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php
}
