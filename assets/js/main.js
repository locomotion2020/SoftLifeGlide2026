jQuery(function ($) {

    /* ======================
       DRAGGABLE ITEMS
    ====================== */
    const items = document.querySelectorAll('.draggableItem');

    items.forEach(function (item) {
        let isDragging = false;
        let isMoved    = false;
        let offsetX    = 0;
        let offsetY    = 0;
        let startX     = 0;
        let startY     = 0;

        item.addEventListener('pointerdown', function (e) {
            if (e.target.closest('.shop-item-btn')) return;

            isDragging = true;
            isMoved    = false;
            startX     = e.clientX;
            startY     = e.clientY;

            item.setPointerCapture(e.pointerId);
            item.classList.add('dragging');

            offsetX = e.clientX - item.offsetLeft;
            offsetY = e.clientY - item.offsetTop;
        });

        item.addEventListener('pointermove', function (e) {
            if (!isDragging) return;

            const moveX = Math.abs(e.clientX - startX);
            const moveY = Math.abs(e.clientY - startY);
            if (moveX > 5 || moveY > 5) isMoved = true;

            item.style.left = (e.clientX - offsetX) + 'px';
            item.style.top  = (e.clientY - offsetY) + 'px';
        });

        item.addEventListener('pointerup', function (e) {
            isDragging = false;
            item.releasePointerCapture(e.pointerId);
            item.classList.remove('dragging');
        });

        item.addEventListener('click', function (e) {
            if (isMoved) {
                e.preventDefault();
                return;
            }

            // Logo: navigate, respect target attribute
            if (item.classList.contains('logo-item') && !item.classList.contains('instagram')) {
                const link = item.querySelector('a');
                if (link) {
                    link.getAttribute('target') === '_blank'
                        ? window.open(link.getAttribute('href'), '_blank')
                        : (window.location.href = link.getAttribute('href'));
                }
                return;
            }

            // Instagram
            if (item.classList.contains('instagram')) {
                const link = item.querySelector('a');
                if (link) window.open(link.getAttribute('href'), '_blank');
                return;
            }

            // Shop item: open category modal
            const targetModalId = item.dataset.modal;
            if (!targetModalId) return;

            $('.product-modal').removeClass('active');
            $('#' + targetModalId).addClass('active').scrollTop(0);
            $('body').css('overflow', 'hidden');
        });
    });

    /* ======================
       IMAGE SIZE SETTING
    ====================== */
    function setImageSize() {
        const images   = document.querySelectorAll('.shop-item img');
        const isMobile = window.innerWidth <= 768;
        const baseSize = isMobile ? 150 : 380;

        images.forEach(function (img) {
            function applySize() {
                const nw = img.naturalWidth;
                const nh = img.naturalHeight;
                img.style.width  = '';
                img.style.height = '';
                if (nw > nh) {
                    img.style.width  = baseSize + 'px';
                    img.style.height = 'auto';
                } else {
                    img.style.height = baseSize + 'px';
                    img.style.width  = 'auto';
                }
            }
            if (!img.complete) { img.onload = applySize; } else { applySize(); }
        });
    }

    /* ======================
       DISTRIBUTED LAYOUT
    ====================== */
    function shuffle(array) {
        return array.sort(function () { return Math.random() - 0.5; });
    }

    function distributedLayout() {
        const vw   = window.innerWidth;
        const vh   = window.innerHeight;
        const cols = Math.ceil(Math.sqrt(items.length));
        const rows = Math.ceil(items.length / cols);

        const cellWidth  = vw / cols;
        const cellHeight = vh / rows;

        const cells = [];
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols; c++) {
                cells.push({ x: c * cellWidth, y: r * cellHeight });
            }
        }

        const shuffled = shuffle(cells);

        items.forEach(function (el, index) {
            const cell  = shuffled[index];
            const randX = Math.max(0, Math.random() * (cellWidth  - el.offsetWidth));
            const randY = Math.max(0, Math.random() * (cellHeight - el.offsetHeight));

            gsap.to(el, {
                left:     cell.x + randX,
                top:      cell.y + randY,
                duration: 0.8,
                ease:     'power3.out',
            });
        });
    }

    setImageSize();
    distributedLayout();

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            setImageSize();
            distributedLayout();
        }, 100);
    });

    /* ======================
       PRODUCT LIST MODAL — CLOSE
    ====================== */
    $('.modal-close, .modal-overlay').on('click', function () {
        $('.product-modal').removeClass('active');
        $('body').css('overflow', '');
    });

    $(document).on('click', function (e) {
        if (
            $('.product-modal.active').length &&
            !$(e.target).closest('.modal-content, .draggableItem, .detail-modal').length
        ) {
            $('.product-modal').removeClass('active');
            $('body').css('overflow', '');
        }
    });

    /* ======================
       DETAIL MODAL — OPEN via AJAX
    ====================== */
    $(document).on('click', '.modal-item img, .modal-item .product-name', function () {
        const productId = $(this).closest('.modal-item').data('product-id');
        if (!productId) return;

        // Show modal immediately with loading state
        $('#detail-modal .detail-inner').html('<div class="detail-loading">Loading...</div>');
        $('#detail-modal').addClass('active');
        $('body').css('overflow', 'hidden');

        $.ajax({
            url:  slgData.ajaxUrl,
            type: 'POST',
            data: {
                action:     'slg_product_detail',
                nonce:      slgData.nonce,
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    $('#detail-modal .detail-inner').html(response.data.html);
                    $('#detail-modal .detail-right').scrollTop(0);
                } else {
                    $('#detail-modal .detail-inner').html('<div class="detail-loading">Failed to load product.</div>');
                }
            },
            error: function () {
                $('#detail-modal .detail-inner').html('<div class="detail-loading">Error loading product.</div>');
            },
        });
    });

    /* ======================
       DETAIL MODAL — CLOSE
    ====================== */
    $('.modal-close2, .detail-overlay').on('click', function () {
        $('#detail-modal').removeClass('active');
        // Keep body scroll locked if the product list modal is still open
        if ( ! $('.product-modal.active').length ) {
            $('body').css('overflow', '');
        }
    });

    /* ======================
       CART — SHARED UI UPDATE
    ====================== */
    function updateCartUI(data) {
        $('.cart-items-wrap').html(data.html);
        $('.cart-count, .cart-count-label').text(data.count);
        $('.cart-subtotal').text(data.total);
        if (parseInt(data.count, 10) > 0) {
            $('.cart-btn').addClass('active');
        } else {
            $('.cart-btn').removeClass('active');
        }
    }

    function loadCart() {
        $.ajax({
            url:  slgData.ajaxUrl,
            type: 'POST',
            data: { action: 'slg_get_cart' },
            success: function (response) {
                if (response.success) updateCartUI(response.data);
            },
        });
    }

    // Load cart count on page load (reflects existing WC session)
    loadCart();

    /* ======================
       ADD TO CART (WC AJAX)
    ====================== */
    $(document).on('click', '.add-cart-btn:not(.total-btn)', function () {
        const $btn      = $(this);
        const productId = $btn.data('product-id');
        if (!productId || $btn.prop('disabled')) return;

        $btn.prop('disabled', true).text('Adding...');

        $.ajax({
            url:  slgData.ajaxUrl,
            type: 'POST',
            data: {
                action:     'slg_add_to_cart',
                nonce:      slgData.nonce,
                product_id: productId,
                quantity:   $btn.data('quantity') || 1,
            },
            success: function (response) {
                if (response.success) {
                    var data = response.data;
                    $('.cart-count, .cart-count-label').text(data.count);
                    if (parseInt(data.count, 10) > 0) $('.cart-btn').addClass('active');

                    loadCart();                      // refresh cart modal content
                    $('.cart-modal').addClass('active'); // open cart
                }
                $btn.prop('disabled', false).text('ADD TO CART');
            },
            error: function () {
                $btn.prop('disabled', false).text('ADD TO CART');
            },
        });
    });

    /* ======================
       CART MODAL — OPEN / CLOSE
    ====================== */
    $('.cart-btn').on('click', function () {
        loadCart();
        $('.cart-modal').addClass('active');
    });

    $('.modal-close3').on('click', function () {
        $('.cart-modal').removeClass('active');
    });

    /* ======================
       CART — QTY & REMOVE
    ====================== */
    function cartUpdate(cartKey, quantity) {
        $.ajax({
            url:  slgData.ajaxUrl,
            type: 'POST',
            data: {
                action:   'slg_update_cart',
                nonce:    slgData.nonce,
                cart_key: cartKey,
                quantity: quantity,
            },
            success: function (response) {
                if (response.success) updateCartUI(response.data);
            },
        });
    }

    $(document).on('click', '.qty-btn.plus', function () {
        const $qty = $(this).closest('.item-quantity').find('.qty-value');
        cartUpdate($(this).data('key'), parseInt($qty.text(), 10) + 1);
    });

    $(document).on('click', '.qty-btn.minus', function () {
        const $qty = $(this).closest('.item-quantity').find('.qty-value');
        const qty  = Math.max(0, parseInt($qty.text(), 10) - 1);
        cartUpdate($(this).data('key'), qty);
    });

    $(document).on('click', '.remove-btn', function () {
        cartUpdate($(this).data('key'), 0);
    });

    /* ======================
       ASIDE TOGGLE
    ====================== */
    $(document).on('click', '.toggle-button', function () {
        const $btn     = $(this);
        const $content = $btn.next('.details-table');
        const $aside   = $btn.closest('.aside-info');
        const isFirst  = $aside.find('.toggle-button').index($btn) === 0;

        $content.slideToggle(100, function () {
            const isVisible  = $content.is(':visible');
            const currentTxt = $btn.text().replace('— ', '').trim();

            $btn.text(isVisible ? '— ' + currentTxt : currentTxt);

            if (isFirst) {
                $aside.toggleClass('no-gap', !isVisible);
            }
        });
    });

});
