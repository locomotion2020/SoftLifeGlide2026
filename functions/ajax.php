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
                    <div class="row">
                        <span class="label">Shipping</span>
                        <span>Standard 3–5 business days<br>Express 1–2 business days</span>
                    </div>
                    <div class="row">
                        <span class="label">Returns</span>
                        <span>Exchange only. Must be initiated within 7 days of delivery.</span>
                    </div>
                    <p class="return-info">All items are final sale. We do not offer refunds.</p>
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
    $total = wp_strip_all_tags( WC()->cart->get_cart_total() );

    ob_start();
    if ( empty( $items ) ) {
        echo '<p class="cart-empty">Your cart is empty.</p>';
    } else {
        foreach ( $items as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $qty     = $cart_item['quantity'];
            $img_id  = $product->get_image_id();
            $img_url = $img_id
                ? wp_get_attachment_image_url( $img_id, 'thumbnail' )
                : wc_placeholder_img_src();
            ?>
            <div class="cart-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
                <label class="item-check">
                    <input type="checkbox" checked />
                    <span class="checkmark"></span>
                </label>
                <div class="item-image">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                </div>
                <div class="item-info">
                    <h3 class="item-title"><?php echo esc_html( $product->get_name() ); ?></h3>
                    <p class="item-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
                    <div class="item-quantity">
                        <button class="qty-btn minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">−</button>
                        <span class="qty-value"><?php echo esc_html( $qty ); ?></span>
                        <button class="qty-btn plus"  data-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
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
