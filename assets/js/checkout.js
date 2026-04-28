jQuery(function ($) {

    /* ======================
       KAKAO POSTCODE SEARCH
       Works for both billing and shipping fields
    ====================== */
    function injectPostcodeSearch(postcodeId, address1Id, cityId, address2Id) {
        var $postcodeInput = $(postcodeId);
        if (!$postcodeInput.length) return;

        // Inject button inside .woocommerce-input-wrapper (not the <p> wrapper)
        // so WooCommerce's own form-row layout is not disrupted
        var $inputWrapper = $postcodeInput.closest('.woocommerce-input-wrapper');
        if (!$inputWrapper.length) return;

        var $btn = $('<button type="button" class="slg-postcode-btn">우편번호 검색</button>');

        $btn.on('click', function () {
            new kakao.Postcode({
                oncomplete: function (data) {
                    $(postcodeId).val(data.zonecode).trigger('change');
                    $(address1Id).val(data.roadAddress).trigger('change');
                    // Fill hidden city field (free-text, WC accepts any string)
                    $(cityId).val(data.sigungu).trigger('change');
                    // Do NOT fill state — WC requires ISO codes; state is hidden + not required
                    $(address2Id).val('').trigger('change').focus();
                }
            }).open();
        });

        $inputWrapper.append($btn);

        // Clicking the readonly inputs also triggers the search
        $(postcodeId + ', ' + address1Id).on('click', function () {
            $btn.trigger('click');
        });
    }

    // Billing address fields
    injectPostcodeSearch(
        '#billing_postcode',
        '#billing_address_1',
        '#billing_city',
        '#billing_address_2'
    );

    // Shipping address fields (shown when "ship to different address" is checked)
    injectPostcodeSearch(
        '#shipping_postcode',
        '#shipping_address_1',
        '#shipping_city',
        '#shipping_address_2'
    );

    /* ======================
       PHONE NUMBER FORMATTING
    ====================== */

    // Block non-digit key input (allow control keys)
    $(document).on('keydown', '#billing_phone', function (e) {
        var controlKeys = [
            'Backspace', 'Tab', 'Enter', 'Delete',
            'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
            'Home', 'End'
        ];
        if (controlKeys.indexOf(e.key) !== -1) return;
        if (e.ctrlKey || e.metaKey) return;
        if (!/^\d$/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Auto-format with hyphens on every input event (handles paste too)
    $(document).on('input', '#billing_phone', function () {
        var digits = $(this).val().replace(/\D/g, '').substring(0, 11);
        var formatted = '';

        if (digits.startsWith('02')) {
            // Seoul landline: 02-XXXX-XXXX
            if (digits.length <= 2) {
                formatted = digits;
            } else if (digits.length <= 5) {
                formatted = digits.slice(0, 2) + '-' + digits.slice(2);
            } else if (digits.length <= 9) {
                formatted = digits.slice(0, 2) + '-' + digits.slice(2, 5) + '-' + digits.slice(5);
            } else {
                formatted = digits.slice(0, 2) + '-' + digits.slice(2, 6) + '-' + digits.slice(6, 10);
            }
        } else {
            // Mobile / other regions: 010-XXXX-XXXX
            if (digits.length <= 3) {
                formatted = digits;
            } else if (digits.length <= 6) {
                formatted = digits.slice(0, 3) + '-' + digits.slice(3);
            } else if (digits.length <= 10) {
                formatted = digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits.slice(6);
            } else {
                formatted = digits.slice(0, 3) + '-' + digits.slice(3, 7) + '-' + digits.slice(7, 11);
            }
        }

        $(this).val(formatted);
    });

});
