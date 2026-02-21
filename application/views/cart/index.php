<style>
    /* --- 1. CONFIG & WRAPPER --- */
    :root {
        --cart-bg: #0b0b0b;
        --card-bg: #141414;
        --border-color: rgba(255, 255, 255, 0.08);
        --accent-color: #ffc107;
    }

    .cart-page-wrapper {
        background-color: var(--cart-bg);
        min-height: 80vh;
        padding-top: 100px;
        padding-bottom: 140px;
    }

    /* --- 2. CARD ITEM (FIX VERTIKAL) --- */
    /* Container untuk item cart akan dipaksa vertical oleh Bootstrap (d-flex flex-column) */

    .cart-card-custom {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 8px;
        position: relative;
        /* Pastikan card mengambil lebar penuh dari container induknya */
        width: 100%;
        display: flex;
        flex-direction: row;
        /* Isi card tetap menyamping (Gbr kiri, Teks kanan) */
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .cart-card-custom:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* --- 3. PRODUCT IMAGE --- */
    .cart-img-wrapper {
        width: 100px;
        height: 100px;
        background-color: #000;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        border: 1px solid var(--border-color);
    }

    .cart-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* --- 4. TEXT CONTENT --- */
    .cart-content-area {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 90px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 4px;
        padding-right: 35px;
        /* Space agar tidak nabrak tombol hapus */
        line-height: 1.3;
    }

    .variant-badge {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 12px;
        display: block;
    }

    /* --- 5. PRICE & QTY (BARIS BAWAH) --- */
    .action-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-top: auto;
    }

    .price-tag {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--accent-color);
    }

    /* Qty Control Modern */
    .qty-control {
        display: flex;
        align-items: center;
        background: #000;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        padding: 3px;
        height: 38px;
    }

    .btn-qty {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-qty:hover {
        color: var(--accent-color);
        background: rgba(255, 255, 255, 0.1);
    }

    .input-qty {
        width: 35px;
        background: transparent;
        border: none;
        color: #fff;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        pointer-events: none;
    }

    /* --- 6. DELETE BUTTON (POJOK KANAN ATAS) --- */
    .btn-delete-absolute {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        z-index: 5;
    }

    .btn-delete-absolute:hover {
        background: #dc3545;
        color: #fff;
    }

    /* --- 7. SUMMARY CARD (STICKY) --- */
    .summary-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        position: sticky;
        top: 120px;
    }
</style>

<div class="cart-page-wrapper">
    <div class="container">

        <div
            class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <div
                class="d-flex align-items-center justify-content-between mb-5 pb-3 border-bottom border-secondary border-opacity-25">

                <h1 class="h4 text-white mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="fas fa-shopping-bag text-warning-custom"></i>
                    Keranjang <span class="badge bg-warning text-dark rounded-pill ms-1" id="cartCountHeader">0</span>
                </h1>

                <!-- <a href="<?= base_url('shop') ?>" class="btn btn-outline-warning btn-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-plus me-1"></i> Tambah Item
                </a> -->

            </div>


        </div>

        <div class="row g-4">

            <div class="col-lg-8">
                <div id="loadingCart" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"></div>
                </div>

                <div id="cartContent" class="d-flex flex-column gap-3" style="display: none; width: 100%;"></div>

                <div id="emptyCart" style="display: none;" class="cart-card-custom py-5 d-block text-center">
                    <div style="width:100%; text-align:center;">
                        <i class="fas fa-shopping-cart fa-3x text-secondary mb-3 opacity-50"></i>
                        <h4 class="text-white fw-bold mb-2">Keranjang Kosong</h4>
                        <a href="<?= base_url('shop') ?>" class="btn btn-warning rounded-pill px-5 fw-bold mt-3">
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-none d-lg-block">
                <div class="summary-card">
                    <h3 class="fw-bold text-white mb-4 border-bottom border-secondary border-opacity-25 pb-3">
                        Ringkasan
                        </h>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-secondary fs-6">Total Belanja </span>
                            &nbsp;
                            <span class="cartSubtotal h4 mb-0 text-warning fw-bold"> Rp. 0</span>
                        </div>

                        <button onclick="submitCheckout()"
                            class="btn btn-warning w-100 py-3 rounded-3 fw-bold text-dark text-uppercase shadow-warning">
                            Checkout (<span class="cartCountBtn">0</span>)
                        </button>


                </div>
            </div>
        </div>
    </div>

    <!-- <div class="fixed-bottom bg-dark border-top border-secondary border-opacity-25 p-3 d-lg-none shadow-lg"
        style="z-index: 9999;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex flex-column">
                    <span class="text-secondary" style="font-size: 11px;">Total Tagihan</span>
                    <span class="cartSubtotal text-warning fw-bold fs-5">Rp. 0</span>
                </div>
                <button onclick="submitCheckout()" class="btn btn-warning px-4 py-2 fw-bold rounded-3 flex-grow-1"
                    style="max-width: 200px;">
                    Checkout (<span class="cartCountBtn">0</span>)
                </button>
            </div>
        </div>
    </div> -->

    <form id="checkoutForm" action="<?= base_url('order/process_cart') ?>" method="post" class="d-none">
        <input type="hidden" name="cart_data" class="cartDataInput">
    </form>
</div>

<template id="cartItemTemplate">
    <div class="cart-card-custom mb-3">

        <button type="button" class="btn-delete-absolute" onclick="removeCartItem(this)" title="Hapus Item">
            <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
        </button>

        <div class="cart-img-wrapper">
            <img src="" class="cart-item-img" alt="Product">
        </div>

        <div class="cart-content-area">
            <div>
                <h6 class="product-title cart-item-name">Nama Produk</h6>
                <span class="variant-badge cart-item-variant">Variant</span>
            </div>

            <div class="action-row">
                <div class="price-tag cart-item-price">Rp. 0</div>

                <div class="qty-control">
                    <button type="button" class="btn-qty" onclick="updateCartItemQty(this, -1)">
                        <i class="fas fa-minus" style="font-size: 10px;"></i>
                    </button>
                    <input type="text" class="input-qty cart-item-qty" value="1" readonly>
                    <button type="button" class="btn-qty" onclick="updateCartItemQty(this, 1)">
                        <i class="fas fa-plus" style="font-size: 10px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () { renderCart(); });

    function renderCart() {
        var cart = (typeof getCart === 'function') ? getCart() : [];
        var loading = document.getElementById('loadingCart');
        var content = document.getElementById('cartContent');
        var empty = document.getElementById('emptyCart');

        if (loading) loading.style.display = 'none';

        if (!cart || cart.length === 0) {
            content.style.display = 'none';
            empty.style.display = 'block';
            updateSummaries(0, 0);
            return;
        }

        empty.style.display = 'none';

        // PENTING: Pastikan display flex dan arah kolom
        content.style.display = 'flex';
        content.style.flexDirection = 'column';
        content.innerHTML = '';

        var subtotal = 0;
        var totalQty = 0;
        var template = document.getElementById('cartItemTemplate');

        cart.forEach(function (item, index) {
            var clone = template.content.cloneNode(true);
            var card = clone.querySelector('.cart-card-custom');
            card.setAttribute('data-index', index);

            clone.querySelector('.cart-item-img').src = '<?= base_url('assets/img/products/') ?>' + item.product_image;
            clone.querySelector('.cart-item-name').textContent = item.product_name;
            clone.querySelector('.cart-item-variant').textContent = item.size + ' / ' + item.color;

            var itemTotal = item.product_price * item.quantity;
            clone.querySelector('.cart-item-price').textContent = 'Rp. ' + itemTotal.toLocaleString('id-ID');
            clone.querySelector('.cart-item-qty').value = item.quantity;

            subtotal += itemTotal;
            totalQty += item.quantity;
            content.appendChild(clone);
        });

        updateSummaries(subtotal, totalQty);
        document.querySelectorAll('.cartDataInput').forEach(el => el.value = JSON.stringify(cart));
    }

    function updateSummaries(totalPrice, totalQty) {
        var fmtPrice = 'Rp. ' + totalPrice.toLocaleString('id-ID');
        document.querySelectorAll('.cartSubtotal').forEach(el => el.textContent = fmtPrice);
        document.querySelectorAll('.cartCountBtn').forEach(el => el.textContent = totalQty);

        var headerCount = document.getElementById('cartCountHeader');
        if (headerCount) headerCount.textContent = '(' + totalQty + ')';

        var mobileFooter = document.querySelector('.fixed-bottom');
        if (mobileFooter) {
            mobileFooter.style.display = (totalQty > 0 && window.innerWidth < 992) ? 'block' : 'none';
        }

        if (typeof updateCartBadge === 'function') updateCartBadge();
    }

    function updateCartItemQty(btn, delta) {
        var card = btn.closest('.cart-card-custom');
        var index = parseInt(card.getAttribute('data-index'));
        var cart = getCart();

        if (cart[index]) {
            cart[index].quantity += delta;
            if (cart[index].quantity < 1) cart[index].quantity = 1;
            saveCart(cart); renderCart();
        }
    }

    function removeCartItem(btn) {
        var card = btn.closest('.cart-card-custom');
        var index = parseInt(card.getAttribute('data-index'));
        if (confirm("Hapus item ini?")) {
            var cart = getCart();
            cart.splice(index, 1);
            saveCart(cart); renderCart();
        }
    }

    function submitCheckout() {
        if (getCart().length > 0) document.getElementById('checkoutForm').submit();
        else alert("Keranjang kosong");
    }
</script>