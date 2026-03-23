<?php get_header(); ?>

<?php
$img = get_template_directory_uri() . '/assets/img';

/* -------------------------------------------------------
   HARDCODED PRODUCT DATA
   Replace with WooCommerce query later.
   Images go in: assets/img/
------------------------------------------------------- */

$products_cat1 = [
    [
        'id'          => 1,
        'name'        => 'Soft life glide · 001',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item1.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Natural',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 2,
        'name'        => 'Soft life glide · 002',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item2.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Noir',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 3,
        'name'        => 'Soft life glide · 003',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item3.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Ivory',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 4,
        'name'        => 'Soft life glide · 004',
        'price'       => '₩138,000',
        'sold_out'    => true,
        'img'         => $img . '/modal-item4.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Blush',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 5,
        'name'        => 'Soft life glide · 005',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item5.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Stone',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 6,
        'name'        => 'Soft life glide · 006',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item6.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Sage',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 7,
        'name'        => 'Soft life glide · 007',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item7.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Dusk',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 8,
        'name'        => 'Soft life glide · 008',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item8.png',
        'detail_img1' => $img . '/detail-img1.png',
        'detail_img2' => $img . '/detail-img2.png',
        'desc'        => 'A luminous textile crafted for the soft life. Light enough to float, structured enough to mean something.',
        'color'       => 'Mist',
        'material'    => '100% Silk',
        'made_in'     => 'Korea',
    ],
];

$products_cat2 = [
    [
        'id'          => 1,
        'name'        => 'Peace Peace Market · 001',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b1.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Natural',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 2,
        'name'        => 'Peace Peace Market · 002',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b2.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Ecru',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 3,
        'name'        => 'Peace Peace Market · 003',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b3.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Sand',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 4,
        'name'        => 'Peace Peace Market · 004',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b4.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Clay',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 5,
        'name'        => 'Peace Peace Market · 005',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b5.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Slate',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 6,
        'name'        => 'Peace Peace Market · 006',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b6.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Moss',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 7,
        'name'        => 'Peace Peace Market · 007',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b7.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Fog',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
    [
        'id'          => 8,
        'name'        => 'Peace Peace Market · 008',
        'price'       => '₩138,000',
        'sold_out'    => false,
        'img'         => $img . '/modal-item-b8.png',
        'detail_img1' => $img . '/detail-img-b1.png',
        'detail_img2' => $img . '/detail-img-b2.png',
        'desc'        => 'Designed for those who move through the world slowly and on their own terms.',
        'color'       => 'Bone',
        'material'    => '100% Linen',
        'made_in'     => 'Korea',
    ],
];

/* Helper: render product grid */
function slg_product_item( $p ) {
    $sold_out_attr = $p['sold_out'] ? 'true' : 'false';
    ?>
    <div class="modal-item"
        data-title="<?php echo esc_attr( $p['name'] ); ?>"
        data-price="<?php echo esc_attr( $p['price'] ); ?>"
        data-sold-out="<?php echo esc_attr( $sold_out_attr ); ?>"
        data-desc="<?php echo esc_attr( $p['desc'] ); ?>"
        data-img1="<?php echo esc_url( $p['detail_img1'] ); ?>"
        data-img2="<?php echo esc_url( $p['detail_img2'] ); ?>"
        data-color="<?php echo esc_attr( $p['color'] ); ?>"
        data-material="<?php echo esc_attr( $p['material'] ); ?>"
        data-made-in="<?php echo esc_attr( $p['made_in'] ); ?>">
        <img src="<?php echo esc_url( $p['img'] ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>" />
        <div class="modal-info">
            <p class="product-name"><?php echo esc_html( $p['name'] ); ?></p>
            <p class="product-price">
                <?php if ( $p['sold_out'] ) : ?>
                    <span class="price"><?php echo esc_html( $p['price'] ); ?></span>
                    <span class="sold-out">Sold Out</span>
                <?php else : ?>
                    <?php echo esc_html( $p['price'] ); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php
}
?>

<main>

    <!-- Background Video -->
    <!-- Place bg.mp4 in assets/video/ -->
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/bg.mp4' ); ?>" type="video/mp4">
        </video>
    </div>

    <!-- Content Layer -->
    <div class="content">

        <!-- Logo -->
        <div class="logo-item shop-item draggableItem">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( $img . '/logo.png' ); ?>" alt="Soft Life Glide" />
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
        <!-- Place shop-item1.png in assets/img/ -->
        <div class="shop-item shop-item1 draggableItem" data-modal="modal-01">
            <img src="<?php echo esc_url( $img . '/shop-item1.png' ); ?>" alt="Soft Life Glide" />
            <div class="shop-overlay">
                <a>01 · Soft Life Glide</a>
            </div>
        </div>

        <!-- Shop Item 2 — Category 2 -->
        <!-- Place shop-item2.png in assets/img/ -->
        <div class="shop-item shop-item2 draggableItem" data-modal="modal-02">
            <img src="<?php echo esc_url( $img . '/shop-item2.png' ); ?>" alt="Peace Peace Market" />
            <div class="shop-overlay">
                <a>02 · Soft Life Glide — Peace Peace Market</a>
            </div>
        </div>

    </div><!-- .content -->

    <!-- =====================
         MODAL 01 — Category 1
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
                <?php foreach ( $products_cat1 as $p ) : slg_product_item( $p ); endforeach; ?>
            </div>
        </div>
    </div>

    <!-- =====================
         MODAL 02 — Category 2
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
                <?php foreach ( $products_cat2 as $p ) : slg_product_item( $p ); endforeach; ?>
            </div>
        </div>
    </div>

    <!-- =====================
         DETAIL MODAL
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
                <div class="detail-left">
                    <h1 class="detail-title"></h1>
                    <p class="detail-price"></p>
                    <p class="detail-desc"></p>
                    <button class="add-cart-btn">ADD TO CART</button>
                    <div class="aside-info">
                        <div class="toggle-group">
                            <button class="toggle-button">— DETAILS</button>
                            <div class="details-table">
                                <div class="row">
                                    <span class="label">Color</span>
                                    <span class="detail-color"></span>
                                </div>
                                <div class="row">
                                    <span class="label">Material</span>
                                    <span class="detail-material"></span>
                                </div>
                                <div class="row">
                                    <span class="label">Made in</span>
                                    <span class="detail-made-in"></span>
                                </div>
                            </div>
                        </div>
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
                    <!-- Images injected by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Button -->
    <div class="cart-btn">
        <button>Cart <span class="cart-count">0</span></button>
    </div>

    <!-- =====================
         CART MODAL
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
                <button class="add-cart-btn total-btn">CHECKOUT NOW</button>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>
