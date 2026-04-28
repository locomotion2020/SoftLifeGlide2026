jQuery(function ($) {

    /* ======================
       CLICKABLE ITEMS
    ====================== */
    const items = document.querySelectorAll('.draggableItem');

    items.forEach(function (item) {
        item.addEventListener('click', function () {

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

            // Intro icon: open introduction modal
            if (item.classList.contains('intro-icon')) {
                $('#intro-modal').addClass('active');
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
       INTRO MODAL (? icon) — OPEN / CLOSE
    ====================== */
    $('.intro-modal-close').on('click', function () {
        $('#intro-modal').removeClass('active');
    });

    $(document).on('click', function (e) {
        if (
            $('#intro-modal').hasClass('active') &&
            !$(e.target).closest('#intro-modal, .intro-icon').length
        ) {
            $('#intro-modal').removeClass('active');
        }
    });

    /* ======================
       FOOTER NAV — Information (info bar)
    ====================== */
    $('#footer-information').on('click', function () {
        $('#page-content-modal').removeClass('active');
        $('#info-bar').toggleClass('active');
        $(this).toggleClass('active');
    });

    $(document).on('click', function (e) {
        if (
            $('#info-bar').hasClass('active') &&
            !$(e.target).closest('#info-bar, #footer-information').length
        ) {
            $('#info-bar').removeClass('active');
            $('#footer-information').removeClass('active');
        }
    });

    /* ======================
       FOOTER NAV — Page Content Modal (T&C / Privacy Policy)
    ====================== */
    function openPageModal( pageId ) {
        $('#info-bar').removeClass('active'); // close info bar if open
        $('#footer-information').removeClass('active');
        $('#page-content-modal')
            .addClass('active')
            .find('.page-modal-title').text('');
        $('#page-content-modal .page-modal-body').html(
            '<p class="page-modal-loading font-myungjo">Loading...</p>'
        );

        $.ajax({
            url:  slgData.ajaxUrl,
            type: 'POST',
            data: { action: 'slg_get_page_content', page_id: pageId },
            success: function ( response ) {
                if ( response.success ) {
                    $('#page-content-modal .page-modal-title').text( response.data.title );
                    $('#page-content-modal .page-modal-body').html( response.data.content );
                } else {
                    $('#page-content-modal .page-modal-body').html(
                        '<p class="page-modal-loading font-myungjo">Content not found.</p>'
                    );
                }
            },
            error: function () {
                $('#page-content-modal .page-modal-body').html(
                    '<p class="page-modal-loading font-myungjo">Error loading content.</p>'
                );
            },
        });
    }

    $('#footer-terms').on('click',   function () { openPageModal( 59 ); });
    $('#footer-privacy').on('click', function () { openPageModal( 3 ); });

    $('.page-modal-close').on('click', function () {
        $('#page-content-modal').removeClass('active');
    });

    $(document).on('click', function (e) {
        if (
            $('#page-content-modal').hasClass('active') &&
            !$(e.target).closest('#page-content-modal, #footer-terms, #footer-privacy').length
        ) {
            $('#page-content-modal').removeClass('active');
        }
    });

    /* ======================
       DETAIL MODAL — OPEN via AJAX
    ====================== */
    function openDetailModal(productId) {
        if (!productId) return;

        $('#detail-modal .detail-inner').html('<div class="detail-loading font-myungjo">Loading...</div>');
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
                    $('#detail-modal .detail-inner').html('<div class="detail-loading font-myungjo">Failed to load product.</div>');
                }
            },
            error: function () {
                $('#detail-modal .detail-inner').html('<div class="detail-loading font-myungjo">Error loading product.</div>');
            },
        });
    }

    $(document).on('click', '.modal-item img, .modal-item .product-name', function () {
        openDetailModal( $(this).closest('.modal-item').data('product-id') );
    });

    // Open detail modal from cart item image or title
    $(document).on('click', '.cart-item .item-image img, .cart-item .item-title', function () {
        $('.cart-modal').removeClass('active');
        openDetailModal( $(this).closest('.cart-item').data('product-id') );
    });

    /* ======================
       DETAIL MODAL — CLOSE
    ====================== */
    $('.modal-close2, .detail-overlay').on('click', function () {
        $('#detail-modal').removeClass('active');
        // Keep body scroll locked if a product list or cart modal is still open
        if ( ! $('.product-modal.active').length && ! $('.cart-modal.active').length ) {
            $('body').css('overflow', '');
        }
    });

    /* ======================
       GALLERY SLIDER MODAL
    ====================== */
    var galleryImages = [];
    var galleryIndex  = 0;

    function openGallery(images, startIndex) {
        galleryImages = images;
        galleryIndex  = startIndex;
        showGallerySlide();
        $('#gallery-modal').addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeGallery() {
        $('#gallery-modal').removeClass('active');
        // Keep scroll locked if detail modal is still open
        if ( ! $('#detail-modal').hasClass('active') ) {
            $('body').css('overflow', '');
        }
    }

    function showGallerySlide() {
        var src = galleryImages[galleryIndex];
        $('#gallery-modal .gallery-img').attr('src', src).attr('alt', '');
    }

    // Open gallery when clicking product images inside detail modal
    $(document).on('click', '#detail-modal .detail-right img', function () {
        var imgs = [];
        $('#detail-modal .detail-right img').each(function () {
            imgs.push($(this).attr('src'));
        });
        var idx = $('#detail-modal .detail-right img').index(this);
        openGallery(imgs, idx);
    });

    $('.gallery-close, .gallery-overlay').on('click', closeGallery);

    $('.gallery-next').on('click', function () {
        galleryIndex = (galleryIndex + 1) % galleryImages.length;
        showGallerySlide();
    });

    $('.gallery-prev').on('click', function () {
        galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
        showGallerySlide();
    });

    $(document).on('keydown', function (e) {
        if ( ! $('#gallery-modal').hasClass('active') ) return;
        if (e.key === 'ArrowRight') {
            galleryIndex = (galleryIndex + 1) % galleryImages.length;
            showGallerySlide();
        } else if (e.key === 'ArrowLeft') {
            galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
            showGallerySlide();
        } else if (e.key === 'Escape') {
            closeGallery();
        }
    });

    // Touch swipe
    var swipeStartX = 0;
    document.getElementById('gallery-modal').addEventListener('touchstart', function (e) {
        swipeStartX = e.touches[0].clientX;
    }, { passive: true });
    document.getElementById('gallery-modal').addEventListener('touchend', function (e) {
        if ( ! $('#gallery-modal').hasClass('active') ) return;
        var dx = e.changedTouches[0].clientX - swipeStartX;
        if (Math.abs(dx) < 50) return;
        if (dx < 0) {
            galleryIndex = (galleryIndex + 1) % galleryImages.length;
        } else {
            galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
        }
        showGallerySlide();
    }, { passive: true });

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

                    loadCart();
                    openCartModal();
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
    function openCartModal() {
        loadCart();
        $('.cart-modal').addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeCartModal() {
        $('.cart-modal').removeClass('active');
        if ( ! $('.product-modal.active').length && ! $('#detail-modal').hasClass('active') ) {
            $('body').css('overflow', '');
        }
    }

    $('.cart-btn').on('click', openCartModal);
    $('.modal-close3, .cart-overlay').on('click', closeCartModal);

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
