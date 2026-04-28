<?php
/**
 * AJAX handlers for SLG theme.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ======================
   PRODUCT DETAIL
====================== */
function slg_ajax_product_detail() {
    check_ajax_referer( 'slg_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    if ( ! $product_id ) wp_send_json_error( 'Invalid product ID.' );

    $product = wc_get_product( $product_id );
    if ( ! $product ) wp_send_json_error( 'Product not found.' );

    // Gallery images if they exist, otherwise fall back to featured image
    $gallery_ids = $product->get_gallery_image_ids();
    $image_ids   = ! empty( $gallery_ids )
        ? $gallery_ids
        : array_filter( [ $product->get_image_id() ] );

    $images_html = '';
    foreach ( $image_ids as $img_id ) {
        $url = wp_get_attachment_image_url( $img_id, 'large' );
        if ( $url ) {
            $images_html .= '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
        }
    }

    // Attributes
    $attr_rows_html = '';
    foreach ( $product->get_attributes() as $attribute ) {
        $label  = wc_attribute_label( $attribute->get_name(), $product );
        $values = $attribute->is_taxonomy()
            ? wc_get_product_terms( $product_id, $attribute->get_name(), [ 'fields' => 'names' ] )
            : $attribute->get_options();

        $attr_rows_html .= '<div class="row">'
            . '<span class="label">' . esc_html( $label ) . '</span>'
            . '<span>' . esc_html( implode( ', ', $values ) ) . '</span>'
            . '</div>';
    }

    $in_stock    = $product->is_in_stock();
    $btn_text    = $in_stock ? 'ADD TO CART' : 'SOLD OUT';
    $btn_disable = $in_stock ? '' : ' disabled';

    $shipping_text = get_field( 'shipping_guide', $product_id ) ?: get_field( 'shipping_guide', 'option' );
    $return_text   = get_field( 'return_guide',   $product_id ) ?: get_field( 'return_guide',   'option' );

    ob_start();
    ?>
    <div class="detail-left">
        <h1 class="detail-title"><?php echo esc_html( $product->get_name() ); ?></h1>
        <p class="detail-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
        <p class="detail-desc"><?php echo wp_kses_post( $product->get_description() ); ?></p>
        <button class="add-cart-btn"
                data-product-id="<?php echo esc_attr( $product_id ); ?>"
                data-quantity="1"
                <?php echo $btn_disable; ?>>
            <?php echo esc_html( $btn_text ); ?>
        </button>
        <div class="aside-info">
            <?php if ( $attr_rows_html ) : ?>
            <div class="toggle-group">
                <button class="toggle-button">— DETAILS</button>
                <div class="details-table">
                    <?php echo $attr_rows_html; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="toggle-group">
                <button class="toggle-button">— Shipping &amp; Returns</button>
                <div class="details-table">
                    <?php if ( $shipping_text ) : ?>
                    <div class="row">
                        <span class="label">Shipping</span>
                        <span><?php echo wp_kses_post( $shipping_text ); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ( $return_text ) : ?>
                    <div class="row">
                        <span class="label">Returns</span>
                        <span><?php echo wp_kses_post( $return_text ); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="detail-right">
        <?php echo $images_html; ?>
    </div>
    <?php
    wp_send_json_success( [ 'html' => ob_get_clean() ] );
}
add_action( 'wp_ajax_slg_product_detail',        'slg_ajax_product_detail' );
add_action( 'wp_ajax_nopriv_slg_product_detail', 'slg_ajax_product_detail' );

/* ======================
   PAGE CONTENT (Terms & Conditions, Privacy Policy)
====================== */
function slg_ajax_get_page_content() {
    $page_id = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
    if ( ! $page_id ) wp_send_json_error( 'Invalid page ID.' );

    $page = get_post( $page_id );
    if ( ! $page || $page->post_status !== 'publish' ) {
        wp_send_json_error( 'Page not found.' );
    }

    wp_send_json_success( [
        'title'   => html_entity_decode( $page->post_title, ENT_QUOTES, 'UTF-8' ),
        'content' => apply_filters( 'the_content', $page->post_content ),
    ] );
}
add_action( 'wp_ajax_slg_get_page_content',        'slg_ajax_get_page_content' );
add_action( 'wp_ajax_nopriv_slg_get_page_content', 'slg_ajax_get_page_content' );

/* ======================
   ADD TO CART
====================== */
function slg_ajax_add_to_cart() {
    check_ajax_referer( 'slg_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $quantity   = isset( $_POST['quantity'] )   ? absint( $_POST['quantity'] )   : 1;

    if ( ! $product_id ) wp_send_json_error( 'Invalid product.' );

    $added = WC()->cart->add_to_cart( $product_id, $quantity );
    if ( $added === false ) wp_send_json_error( 'Could not add to cart.' );

    WC()->cart->calculate_totals();

    wp_send_json_success( [
        'count' => WC()->cart->get_cart_contents_count(),
        'total' => wp_strip_all_tags( WC()->cart->get_cart_total() ),
    ] );
}
add_action( 'wp_ajax_slg_add_to_cart',        'slg_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_slg_add_to_cart', 'slg_ajax_add_to_cart' );

/* ======================
   GET CART HTML
====================== */
function slg_ajax_get_cart() {
    $items = WC()->cart->get_cart();
    $count = WC()->cart->get_cart_contents_count();
    $total = html_entity_decode( wp_strip_all_tags( WC()->cart->get_cart_total() ), ENT_HTML5, 'UTF-8' );

    ob_start();
    if ( empty( $items ) ) {
        echo '<p class="cart-empty">Your cart is empty.</p>';
    } else {
        foreach ( $items as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $qty     = $cart_item['quantity'];
            $img_id  = $product->get_image_id();
            $img_url = $img_id
                ? wp_get_attachment_image_url( $img_id, 'slg-product-thumb' )
                : wc_placeholder_img_src();
            ?>
            <div class="cart-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>" data-product-id="<?php echo esc_attr( $cart_item['product_id'] ); ?>">
                <label class="item-check">
                    <input type="checkbox" checked />
                    <span class="checkmark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                            <path d="M0.5 3.07583L3.1297 5.65152L8.31414 0.5" stroke="black" stroke-linecap="round"/>
                        </svg>
                    </span>
                </label>
                <div class="item-image">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                </div>
                <div class="item-info">
                    <h3 class="item-title"><?php echo esc_html( $product->get_name() ); ?></h3>
                    <p class="item-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
                    <div class="item-quantity">
                        <button class="qty-btn minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="1" viewBox="0 0 14 1" fill="none">
                                <path d="M0.5 0.5H12.9502" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <span class="qty-value"><?php echo esc_html( $qty ); ?></span>
                        <button class="qty-btn plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M0.5 6.77637H12.9502" stroke="black" stroke-linecap="round"/>
                                <path d="M6.72461 0.5L6.72461 13.0527" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <button class="remove-btn"    data-key="<?php echo esc_attr( $cart_item_key ); ?>">Remove</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    wp_send_json_success( [
        'html'  => ob_get_clean(),
        'count' => $count,
        'total' => $total,
    ] );
}
add_action( 'wp_ajax_slg_get_cart',        'slg_ajax_get_cart' );
add_action( 'wp_ajax_nopriv_slg_get_cart', 'slg_ajax_get_cart' );

/* ======================
   UPDATE CART (qty / remove)
====================== */
function slg_ajax_update_cart() {
    check_ajax_referer( 'slg_nonce', 'nonce' );

    $cart_key = isset( $_POST['cart_key'] ) ? sanitize_text_field( $_POST['cart_key'] ) : '';
    $quantity  = isset( $_POST['quantity'] )  ? absint( $_POST['quantity'] )              : 0;

    if ( ! $cart_key ) wp_send_json_error( 'Invalid cart key.' );

    if ( $quantity === 0 ) {
        WC()->cart->remove_cart_item( $cart_key );
    } else {
        WC()->cart->set_quantity( $cart_key, $quantity );
    }

    WC()->cart->calculate_totals();
    slg_ajax_get_cart(); // return refreshed cart
}
add_action( 'wp_ajax_slg_update_cart',        'slg_ajax_update_cart' );
add_action( 'wp_ajax_nopriv_slg_update_cart', 'slg_ajax_update_cart' );
