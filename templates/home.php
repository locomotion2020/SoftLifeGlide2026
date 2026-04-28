<?php
/**
 * Template Name: Homepage
 *
 * @package softlifeglide2026
 */

get_header();

$img = get_template_directory_uri() . '/assets/img';

/* -------------------------------------------------------
   HOME NAVIGATION — ACF Repeater
------------------------------------------------------- */
$nav_items = get_field( 'nav_items', 'option' ) ?: [];

/* Collect enabled shop items for modal generation */
$shop_items = [];
foreach ( $nav_items as $item ) {
    if (
        $item['item_type'] === 'shop' &&
        ! empty( $item['item_enabled'] ) &&
        ! empty( $item['item_modal_id'] ) &&
        ! empty( $item['item_category_slug'] )
    ) {
        $shop_items[] = $item;
    }
}
?>

<main>

    <?php
    $bg_choice    = get_field( 'bg_choice', 'option' );
    $bg_video_url = get_field( 'bg_video_url', 'option' );
    $bg_image     = get_field( 'bg_image', 'option' );
    ?>

    <?php if ( $bg_choice === 'Image' && ! empty( $bg_image ) ) : ?>
    <!-- Background Image -->
    <div class="image-background">
        <img src="<?php echo esc_url( $bg_image['url'] ); ?>"
             alt="<?php echo esc_attr( $bg_image['alt'] ); ?>" />
    </div>

    <?php else : ?>
    <!-- Background Video -->
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <?php if ( ! empty( $bg_video_url ) ) : ?>
            <source src="<?php echo esc_url( $bg_video_url ); ?>" type="video/mp4">
            <?php else : ?>
            <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/bg.mp4' ); ?>" type="video/mp4">
            <?php endif; ?>
        </video>
    </div>
    <?php endif; ?>

    <!-- Content Layer -->
    <div class="content">

        <?php foreach ( $nav_items as $item ) :
            if ( empty( $item['item_enabled'] ) ) continue;
            $type = $item['item_type'];
        ?>

            <?php if ( $type === 'logo' ) : ?>
            <!-- Logo -->
            <div class="logo-item shop-item draggableItem">
                <a href="<?php echo esc_url( $item['item_url'] ?: '#' ); ?>"
                   target="<?php echo esc_attr( $item['item_target'] ?: '_self' ); ?>">
                    <img src="<?php echo esc_url( $item['item_image'] ?: ( $img . '/logo.svg' ) ); ?>" alt="Logo" />
                </a>
            </div>

            <?php elseif ( $type === 'instagram' ) : ?>
            <!-- Instagram -->
            <div class="logo-item instagram draggableItem">
                <a href="<?php echo esc_url( $item['item_url'] ?: '#' ); ?>" target="_blank" rel="noopener noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            </div>

            <?php elseif ( $type === 'shop' ) :
                $modal_id = esc_attr( $item['item_modal_id'] );
                $label    = $item['item_label'] ?: '';
                $image    = $item['item_image'] ?: '';
            ?>
            <!-- Shop Item -->
            <div class="shop-item draggableItem" data-modal="<?php echo $modal_id; ?>">
                <?php if ( $image ) : ?>
                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $label ); ?>" />
                <?php endif; ?>
                <div class="shop-overlay">
                    <a><?php echo esc_html( $label ); ?></a>
                </div>
            </div>

            <?php elseif ( $type === 'intro' ) : ?>
            <!-- Introduction Icon -->
            <div class="intro-icon draggableItem">
                <button aria-label="About">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="28" viewBox="0 0 18 28" fill="none">
                        <path d="M10.5586 21.3918C10.2041 22.9649 7.98542 23.0895 7.38176 21.6738C7.29228 21.4642 7.25717 21.2355 7.25604 21.0078L7.24925 16.166C7.24925 16.0449 7.25717 15.9237 7.28436 15.8048C7.43386 15.1569 7.97523 14.5567 8.64571 14.4728C9.37962 14.3811 9.87456 14.4173 10.6221 14.184C14.3437 13.0231 15.7198 8.32068 13.1964 5.32274C10.3582 1.95106 4.86064 3.06212 3.58762 7.26285C3.24219 8.40109 3.65218 9.89496 2.28063 10.4295C1.43233 10.7602 0.273699 10.2347 0.0743653 9.32867C-0.2269 7.96052 0.44132 5.82674 1.10728 4.61262C3.92739 -0.532683 10.9471 -1.58825 15.1603 2.53774C15.7243 3.09043 16.277 3.82774 16.6621 4.51861C19.6521 9.87457 16.5454 16.4231 10.6187 17.635L10.5949 21.0373C10.5949 21.1562 10.5847 21.2762 10.5586 21.3918Z" fill="white"></path>
                        <path d="M8.91985 27.9103C9.93223 27.9103 10.7529 27.0896 10.7529 26.0772C10.7529 25.0648 9.93223 24.2441 8.91985 24.2441C7.90747 24.2441 7.08677 25.0648 7.08677 26.0772C7.08677 27.0896 7.90747 27.9103 8.91985 27.9103Z" fill="white"></path>
                    </svg>
                </button>
            </div>

            <?php endif; ?>

        <?php endforeach; ?>

    </div><!-- .content -->

    <!-- =====================
         PRODUCT MODALS (generated from ACF repeater shop items)
    ===================== -->
    <?php foreach ( $shop_items as $shop_item ) :
        $modal_id = esc_attr( $shop_item['item_modal_id'] );
        $products = slg_get_products_by_category( $shop_item['item_category_slug'] );
    ?>
    <div class="product-modal" id="<?php echo $modal_id; ?>">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <button class="modal-close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                        <path d="M16.3896 0.388056L0.389648 16.3301" stroke="black" stroke-width="1.1"/>
                        <path d="M16.3896 16.3893L0.389648 0.447266" stroke="black" stroke-width="1.1"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ( $products as $product ) : slg_render_product_card( $product ); endforeach; ?>
                <?php if ( empty( $products ) ) : ?>
                    <p style="color:var(--darkgrey); padding:var(--lh);">No products found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- =====================
         DETAIL MODAL (populated via AJAX)
    ===================== -->
    <div class="detail-modal" id="detail-modal">
        <div class="detail-overlay"></div>
        <div class="detail-content">
            <button class="modal-close2" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <line x1="0.5" y1="10.3508" x2="20.436" y2="10.3508" stroke="black"/>
                    <path d="M10.5 20.3535L0.5 10.3535L10.5 0.353516" stroke="black" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="detail-inner">
                <!-- Content injected by AJAX -->
            </div>
        </div>
    </div>

    <!-- =====================
         GALLERY SLIDER MODAL
    ===================== -->
    <div class="gallery-modal" id="gallery-modal">
        <div class="gallery-overlay"></div>
        <div class="gallery-ui">
            <button class="gallery-close" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <path d="M16.3896 0.388056L0.389648 16.3301" stroke="white" stroke-width="1.1"/>
                    <path d="M16.3896 16.3893L0.389648 0.447266" stroke="white" stroke-width="1.1"/>
                </svg>
            </button>
            <button class="gallery-arrow gallery-prev" aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <line x1="0.5" y1="10.3508" x2="20.436" y2="10.3508" stroke="white"/>
                    <path d="M10.5 20.3535L0.5 10.3535L10.5 0.353516" stroke="white" stroke-linejoin="round"/>
                </svg>
            </button>
            <img class="gallery-img" src="" alt="" />
            <button class="gallery-arrow gallery-next" aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <line x1="20.5" y1="10.3508" x2="0.564" y2="10.3508" stroke="white"/>
                    <path d="M10.5 0.353516L20.5 10.3535L10.5 20.3535" stroke="white" stroke-linejoin="round"/>
                </svg>
            </button>
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
        <div class="cart-overlay"></div>
        <div class="cart-content">
            <button class="modal-close3" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <path d="M16.3896 0.388056L0.389648 16.3301" stroke="black" stroke-width="1.1"/>
                    <path d="M16.3896 16.3893L0.389648 0.447266" stroke="black" stroke-width="1.1"/>
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
                <p class="cart-checkout-note">Taxes and shipping calculated at checkout</p>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="add-cart-btn total-btn">CHECKOUT NOW</a>
            </div>
        </div>
    </div>

</main>

<?php get_footer();
