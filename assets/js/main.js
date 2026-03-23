$(function () {

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

            // Logo: navigate on click
            if (item.classList.contains('logo-item') && !item.classList.contains('instagram')) {
                const link = item.querySelector('a');
                if (link) window.location.href = link.getAttribute('href');
                return;
            }

            // Instagram: open link
            if (item.classList.contains('instagram')) {
                const link = item.querySelector('a');
                if (link) window.open(link.getAttribute('href'), '_blank');
                return;
            }

            // Shop item: open category modal
            const targetModalId = item.dataset.modal;
            if (!targetModalId) return;

            $('.product-modal').removeClass('active');
            const $target = $('#' + targetModalId);
            $target.addClass('active').scrollTop(0);
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
            if (!img.complete) {
                img.onload = applySize;
            } else {
                applySize();
            }
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
            const elWidth  = el.offsetWidth;
            const elHeight = el.offsetHeight;
            const cell     = shuffled[index];

            const randX = Math.max(0, Math.random() * (cellWidth  - elWidth));
            const randY = Math.max(0, Math.random() * (cellHeight - elHeight));

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
            !$(e.target).closest('.modal-content, .draggableItem').length
        ) {
            $('.product-modal').removeClass('active');
            $('body').css('overflow', '');
        }
    });

    /* ======================
       DETAIL MODAL — OPEN (dynamic)
    ====================== */
    $(document).on('click', '.modal-item img, .modal-item .product-name', function () {
        const $item = $(this).closest('.modal-item');

        const title    = $item.data('title');
        const price    = $item.data('price');
        const soldOut  = $item.data('sold-out');  // "true" / "false"
        const desc     = $item.data('desc');
        const img1     = $item.data('img1');
        const img2     = $item.data('img2');
        const color    = $item.data('color');
        const material = $item.data('material');
        const madeIn   = $item.data('made-in');

        // Populate text fields
        $('#detail-modal .detail-title').text(title);
        $('#detail-modal .detail-price').text(price);
        $('#detail-modal .detail-desc').text(desc);
        $('#detail-modal .detail-color').text(color);
        $('#detail-modal .detail-material').text(material);
        $('#detail-modal .detail-made-in').text(madeIn);

        // Populate images
        let imgHtml = '<img src="' + img1 + '" alt="' + title + '">';
        if (img2) imgHtml += '<img src="' + img2 + '" alt="' + title + '">';
        $('#detail-modal .detail-right').html(imgHtml);

        // Sold out state
        if (soldOut === 'true' || soldOut === true) {
            $('.add-cart-btn').not('.total-btn').prop('disabled', true).text('SOLD OUT');
        } else {
            $('.add-cart-btn').not('.total-btn').prop('disabled', false).text('ADD TO CART');
        }

        // Store current product on modal for cart use
        $('#detail-modal').data('current', {
            title:   title,
            price:   price,
            img:     img1,
            soldOut: soldOut,
        });

        $('#detail-modal').addClass('active');
        $('body').css('overflow', 'hidden');
        $('#detail-modal .detail-right').scrollTop(0);
    });

    /* ======================
       DETAIL MODAL — CLOSE
    ====================== */
    $('.modal-close2, .detail-overlay').on('click', function () {
        $('#detail-modal').removeClass('active');
        $('body').css('overflow', '');
    });

    /* ======================
       CART — JS STORE
    ====================== */
    let cart = [];

    function formatPrice(num) {
        return '₩' + num.toLocaleString('ko-KR');
    }

    function parsePriceNum(priceStr) {
        return parseInt(priceStr.replace(/[^0-9]/g, ''), 10) || 0;
    }

    function updateCartUI() {
        const total = cart.reduce(function (sum, item) {
            return sum + parsePriceNum(item.price) * item.qty;
        }, 0);
        const count = cart.reduce(function (sum, item) { return sum + item.qty; }, 0);

        $('.cart-count').text(count);
        $('.cart-count-label').text(count);
        $('.cart-subtotal').text(formatPrice(total));

        if (count > 0) {
            $('.cart-btn').addClass('active');
        } else {
            $('.cart-btn').removeClass('active');
        }

        const $wrap = $('.cart-items-wrap');
        $wrap.empty();

        if (cart.length === 0) {
            $wrap.html('<p class="cart-empty">Your cart is empty.</p>');
            return;
        }

        cart.forEach(function (item, idx) {
            const $el = $([
                '<div class="cart-item" data-index="' + idx + '">',
                '  <label class="item-check"><input type="checkbox" checked /><span class="checkmark"></span></label>',
                '  <div class="item-image"><img src="' + item.img + '" alt="' + item.title + '"></div>',
                '  <div class="item-info">',
                '    <h3 class="item-title">' + item.title + '</h3>',
                '    <p class="item-price">' + item.price + '</p>',
                '    <div class="item-quantity">',
                '      <button class="qty-btn minus">−</button>',
                '      <span class="qty-value">' + item.qty + '</span>',
                '      <button class="qty-btn plus">+</button>',
                '      <button class="remove-btn">Remove</button>',
                '    </div>',
                '  </div>',
                '</div>',
            ].join(''));
            $wrap.append($el);
        });
    }

    // Add to cart
    $(document).on('click', '.add-cart-btn:not(.total-btn)', function () {
        const current = $('#detail-modal').data('current');
        if (!current || current.soldOut === 'true') return;

        const existing = cart.find(function (i) { return i.title === current.title; });
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ title: current.title, price: current.price, img: current.img, qty: 1 });
        }

        updateCartUI();
        $('.cart-modal').addClass('active');
    });

    // Quantity buttons
    $(document).on('click', '.qty-btn.plus', function () {
        const idx = $(this).closest('.cart-item').data('index');
        cart[idx].qty += 1;
        updateCartUI();
    });

    $(document).on('click', '.qty-btn.minus', function () {
        const idx = $(this).closest('.cart-item').data('index');
        if (cart[idx].qty > 1) {
            cart[idx].qty -= 1;
        } else {
            cart.splice(idx, 1);
        }
        updateCartUI();
    });

    $(document).on('click', '.remove-btn', function () {
        const idx = $(this).closest('.cart-item').data('index');
        cart.splice(idx, 1);
        updateCartUI();
    });

    /* ======================
       CART MODAL — OPEN / CLOSE
    ====================== */
    $('.cart-btn').on('click', function () {
        $('.cart-modal').addClass('active');
    });

    $('.modal-close3').on('click', function () {
        $('.cart-modal').removeClass('active');
    });

    /* ======================
       ASIDE TOGGLE
    ====================== */
    $(document).on('click', '.toggle-button', function () {
        const $btn     = $(this);
        const $content = $btn.next('.details-table');
        const $aside   = $btn.closest('.aside-info');
        const isFirst  = $('.toggle-button').index($btn) === 0;

        $content.slideToggle(100, function () {
            const isVisible  = $content.is(':visible');
            const currentTxt = $btn.text().replace('— ', '').trim();

            $btn.text(isVisible ? '— ' + currentTxt : currentTxt);

            if (isFirst) {
                if (isVisible) {
                    $aside.removeClass('no-gap');
                } else {
                    $aside.addClass('no-gap');
                }
            }
        });
    });

});
