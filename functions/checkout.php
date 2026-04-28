<?php
/**
 * WooCommerce checkout field customizations
 *
 * - Removes last name, company, country (billing + shipping)
 * - Korean address flow: postcode search → address1 (auto) → address2 (manual)
 * - Reorders fields: name → email → phone → postcode → address1 → address2
 * - Hides state/city (auto-filled via Kakao JS, not required)
 */

/* -------------------------------------------------------
   1a. SET POSTCODE PRIORITY AT THE BASE LEVEL
       (runs before locale and other filters)
------------------------------------------------------- */
add_filter( 'woocommerce_default_address_fields', 'slg_default_address_field_order' );
function slg_default_address_field_order( $fields ) {
    // Put postcode (default 90) before address_1 (50) and address_2 (60)
    if ( isset( $fields['postcode'] ) ) {
        $fields['postcode']['priority'] = 35;
    }
    return $fields;
}

/* -------------------------------------------------------
   1b. MODIFY CHECKOUT FIELDS (billing + shipping)
------------------------------------------------------- */
add_filter( 'woocommerce_checkout_fields', 'slg_customize_checkout_fields' );

function slg_customize_checkout_fields( $fields ) {

    /* ---- BILLING ---- */
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_company'] );

    $fields['billing']['billing_first_name']['class']    = [ 'form-row-wide' ];
    $fields['billing']['billing_first_name']['priority'] = 10;

    $fields['billing']['billing_email']['priority'] = 20;
    $fields['billing']['billing_email']['class']    = [ 'form-row-first' ];

    $fields['billing']['billing_phone']['priority'] = 30;
    $fields['billing']['billing_phone']['class']    = [ 'form-row-last' ];

    $fields['billing']['billing_postcode']['priority']          = 35;
    $fields['billing']['billing_postcode']['label']             = '우편번호';
    $fields['billing']['billing_postcode']['class']             = [ 'form-row-wide', 'slg-postcode-field' ];
    $fields['billing']['billing_postcode']['custom_attributes'] = [ 'readonly' => 'readonly', 'autocomplete' => 'off' ];

    $fields['billing']['billing_address_1']['priority']          = 50;
    $fields['billing']['billing_address_1']['label']             = '주소';
    $fields['billing']['billing_address_1']['class']             = [ 'form-row-wide' ];
    $fields['billing']['billing_address_1']['custom_attributes'] = [ 'readonly' => 'readonly', 'autocomplete' => 'off' ];

    $fields['billing']['billing_address_2']['priority']    = 60;
    $fields['billing']['billing_address_2']['label']       = '상세주소';
    $fields['billing']['billing_address_2']['placeholder'] = '상세주소를 입력해 주세요 (동, 호수 등)';
    $fields['billing']['billing_address_2']['class']       = [ 'form-row-wide' ];

    $fields['billing']['billing_city']['priority'] = 70;
    $fields['billing']['billing_city']['required'] = false;
    $fields['billing']['billing_city']['class']    = [ 'slg-hidden-field' ];

    if ( isset( $fields['billing']['billing_country'] ) ) {
        $fields['billing']['billing_country']['class']    = [ 'slg-hidden-field' ];
        $fields['billing']['billing_country']['required'] = false;
        $fields['billing']['billing_country']['priority'] = 90;
    }

    if ( isset( $fields['billing']['billing_state'] ) ) {
        $fields['billing']['billing_state']['priority'] = 100;
        $fields['billing']['billing_state']['required'] = false;
        $fields['billing']['billing_state']['class']    = [ 'slg-hidden-field' ];
    }

    /* ---- SHIPPING (mirror billing customizations) ---- */
    if ( isset( $fields['shipping'] ) ) {
        unset( $fields['shipping']['shipping_last_name'] );
        unset( $fields['shipping']['shipping_company'] );

        $fields['shipping']['shipping_first_name']['class']    = [ 'form-row-wide' ];
        $fields['shipping']['shipping_first_name']['priority'] = 10;

        $fields['shipping']['shipping_postcode']['priority']          = 35;
        $fields['shipping']['shipping_postcode']['label']             = '우편번호';
        $fields['shipping']['shipping_postcode']['class']             = [ 'form-row-wide', 'slg-postcode-field' ];
        $fields['shipping']['shipping_postcode']['custom_attributes'] = [ 'readonly' => 'readonly', 'autocomplete' => 'off' ];

        $fields['shipping']['shipping_address_1']['priority']          = 50;
        $fields['shipping']['shipping_address_1']['label']             = '주소';
        $fields['shipping']['shipping_address_1']['class']             = [ 'form-row-wide' ];
        $fields['shipping']['shipping_address_1']['custom_attributes'] = [ 'readonly' => 'readonly', 'autocomplete' => 'off' ];

        $fields['shipping']['shipping_address_2']['priority']    = 60;
        $fields['shipping']['shipping_address_2']['label']       = '상세주소';
        $fields['shipping']['shipping_address_2']['placeholder'] = '상세주소를 입력해 주세요 (동, 호수 등)';
        $fields['shipping']['shipping_address_2']['class']       = [ 'form-row-wide' ];

        $fields['shipping']['shipping_city']['priority'] = 70;
        $fields['shipping']['shipping_city']['required'] = false;
        $fields['shipping']['shipping_city']['class']    = [ 'slg-hidden-field' ];

        if ( isset( $fields['shipping']['shipping_country'] ) ) {
            $fields['shipping']['shipping_country']['class']    = [ 'slg-hidden-field' ];
            $fields['shipping']['shipping_country']['required'] = false;
            $fields['shipping']['shipping_country']['priority'] = 90;
        }

        if ( isset( $fields['shipping']['shipping_state'] ) ) {
            $fields['shipping']['shipping_state']['priority'] = 100;
            $fields['shipping']['shipping_state']['required'] = false;
            $fields['shipping']['shipping_state']['class']    = [ 'slg-hidden-field' ];
        }
    }

    return $fields;
}

/* -------------------------------------------------------
   2. FORCE KOREA (KR) AS DEFAULT / HIDDEN COUNTRY
------------------------------------------------------- */
add_filter( 'default_checkout_billing_country',  'slg_default_country' );
add_filter( 'default_checkout_shipping_country', 'slg_default_country' );
function slg_default_country() {
    return 'KR';
}

// Override KR locale: postcode first, state/city not required
add_filter( 'woocommerce_get_country_locale', 'slg_country_locale' );
function slg_country_locale( $locale ) {
    $locale['KR']['postcode']['priority'] = 35; // ensure postcode sorts before address_1 (50)
    $locale['KR']['state']['required']    = false;
    $locale['KR']['state']['hidden']      = true;
    $locale['KR']['city']['required']     = false;
    return $locale;
}

// Inject hidden country = KR so validate_posted_data() always sees it
add_action( 'woocommerce_checkout_billing',  'slg_inject_hidden_billing_country' );
add_action( 'woocommerce_checkout_shipping', 'slg_inject_hidden_shipping_country' );
function slg_inject_hidden_billing_country() {
    echo '<input type="hidden" name="billing_country" value="KR">';
}
function slg_inject_hidden_shipping_country() {
    echo '<input type="hidden" name="shipping_country" value="KR">';
}

/* -------------------------------------------------------
   3. WRAP ORDER REVIEW HEADING + PANEL IN A SINGLE COLUMN DIV
      Allows CSS Grid to treat them as one right-column item.
------------------------------------------------------- */
add_action( 'woocommerce_checkout_before_order_review_heading', function() {
    echo '<div class="slg-order-review-col">';
}, 1 );

add_action( 'woocommerce_checkout_after_order_review', function() {
    echo '</div>';
}, 99 );

/* -------------------------------------------------------
   5. GO MAIN BUTTON ON ORDER RECEIVED PAGE
------------------------------------------------------- */
add_action( 'woocommerce_thankyou', 'slg_thankyou_go_main_button', 20 );
function slg_thankyou_go_main_button() {
    printf(
        '<div class="slg-go-main-wrap"><a href="%s" class="slg-go-main-btn">Go Main</a></div>',
        esc_url( home_url( '/' ) )
    );
}

/* -------------------------------------------------------
   6. ENQUEUE KAKAO POSTCODE + CHECKOUT JS/CSS
------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'slg_enqueue_checkout_assets' );

function slg_enqueue_checkout_assets() {
    $on_checkout       = is_checkout() && ! is_wc_endpoint_url( 'order-received' );
    $on_order_received = is_wc_endpoint_url( 'order-received' );

    if ( ! $on_checkout && ! $on_order_received ) return;

    wp_enqueue_style(
        'slg-checkout',
        get_template_directory_uri() . '/assets/css/checkout.css',
        [ 'slg-main' ],
        SLG_VERSION
    );

    if ( $on_checkout ) {
        wp_enqueue_script(
            'kakao-postcode',
            'https://t1.kakaocdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'slg-checkout',
            get_template_directory_uri() . '/assets/js/checkout.js',
            [ 'jquery', 'kakao-postcode' ],
            SLG_VERSION,
            true
        );
    }
}
