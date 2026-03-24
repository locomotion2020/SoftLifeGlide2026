<?php
/**
 * Template Name: Homepage
 *
 * @package softlifeglide2026
 */

get_header();

$img = get_template_directory_uri() . '/assets/img';

/* -------------------------------------------------------
   WOOCOMMERCE PRODUCT HELPERS
------------------------------------------------------- */
if ( ! function_exists( 'slg_get_products_by_category' ) ) {
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
}

if ( ! function_exists( 'slg_render_product_card' ) ) {
    function slg_render_product_card( $product ) {
        $image_id  = $product->get_image_id();
        $thumb_url = $image_id
            ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' )
            : wc_placeholder_img_src( 'woocommerce_thumbnail' );
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
}

$products_cat1 = slg_get_products_by_category( 'soft-life-glide' );
$products_cat2 = slg_get_products_by_category( 'peace-peace-market' );
?>

<main>

    <!-- Background Video (place bg.mp4 in assets/video/) -->
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/bg.mp4' ); ?>" type="video/mp4">
        </video>
    </div>

    <!-- Content Layer -->
    <div class="content">

        <!-- Logo -->
        <div class="logo-item shop-item draggableItem">
            <a href="https://paperpress313.mycafe24.com/" target="_blank">
                <img src="<?php echo esc_url( $img . '/logo.svg' ); ?>" alt="Paper Press" />
            </a>
        </div>

        <!-- Instagram -->
        <div class="logo-item instagram draggableItem">
            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>
        </div>

        <!-- Shop Item 1 — Category 1 -->
        <div class="shop-item shop-item1 draggableItem" data-modal="modal-01">
            <img src="<?php echo esc_url( $img . '/shop-item1.png' ); ?>" alt="Soft Life Glide" />
            <div class="shop-overlay">
                <a>01 · Soft Life Glide</a>
            </div>
        </div>

        <!-- Shop Item 2 — Category 2 -->
        <div class="shop-item shop-item2 draggableItem" data-modal="modal-02">
            <img src="<?php echo esc_url( $img . '/shop-item2.png' ); ?>" alt="Peace Peace Market" />
            <div class="shop-overlay">
                <a>02 · Soft Life Glide — Peace Peace Market</a>
            </div>
        </div>

    </div><!-- .content -->

    <!-- =====================
         MODAL 01 — Soft Life Glide
    ===================== -->
    <div class="product-modal" id="modal-01">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Soft Life Glide</span>
                <button class="modal-close" aria-label="Close">
                    <svg viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="4" y1="4" x2="22" y2="22" stroke-width="1.5"/>
                        <line x1="22" y1="4" x2="4" y2="22" stroke-width="1.5"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ( $products_cat1 as $product ) : slg_render_product_card( $product ); endforeach; ?>
                <?php if ( empty( $products_cat1 ) ) : ?>
                    <p style="color:var(--darkgrey); padding:var(--lh);">No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- =====================
         MODAL 02 — Peace Peace Market
    ===================== -->
    <div class="product-modal" id="modal-02">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Peace Peace Market</span>
                <button class="modal-close" aria-label="Close">
                    <svg viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="4" y1="4" x2="22" y2="22" stroke-width="1.5"/>
                        <line x1="22" y1="4" x2="4" y2="22" stroke-width="1.5"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ( $products_cat2 as $product ) : slg_render_product_card( $product ); endforeach; ?>
                <?php if ( empty( $products_cat2 ) ) : ?>
                    <p style="color:var(--darkgrey); padding:var(--lh);">No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- =====================
         DETAIL MODAL (populated via AJAX)
    ===================== -->
    <div class="detail-modal" id="detail-modal">
        <div class="detail-overlay"></div>
        <div class="detail-content">
            <button class="modal-close2" aria-label="Close">
                <svg viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="4" y1="4" x2="22" y2="22" stroke-width="1.1"/>
                    <line x1="22" y1="4" x2="4" y2="22" stroke-width="1.1"/>
                </svg>
            </button>
            <div class="detail-inner">
                <!-- Content injected by AJAX -->
            </div>
        </div>
    </div>

    <!-- Cart Button -->
    <div class="cart-btn">
        <button>Cart <span class="cart-count">0</span></button>
    </div>

    <!-- =====================
         CART MODAL (populated via AJAX)
    ===================== -->
    <div class="cart-modal">
        <div class="cart-content">
            <button class="modal-close3" aria-label="Close">
                <svg viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="4" y1="4" x2="22" y2="22" stroke-width="1.1"/>
                    <line x1="22" y1="4" x2="4" y2="22" stroke-width="1.1"/>
                </svg>
            </button>
            <div class="cart-list">
                <h3 class="cart-h">Cart <span class="cart-count-label">0</span></h3>
                <div class="cart-items-wrap">
                    <p class="cart-empty">Your cart is empty.</p>
                </div>
            </div>
            <div class="cart-checkout">
                <p class="total">Subtotal <span class="cart-subtotal">₩0</span></p>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="add-cart-btn total-btn">CHECKOUT NOW</a>
            </div>
        </div>
    </div>

</main>

<?php get_footer();
