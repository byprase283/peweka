<!-- Product Detail -->
<div class="product-detail">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Product Image -->
            <div class="product-image-main">
                <div class="main-img-wrapper mb-3">
                    <img id="mainImage" src="<?= base_url('assets/img/products/' . $product->image) ?>"
                        alt="<?= htmlspecialchars($product->name) ?>" class="img-fluid rounded"
                        style="width: 100%; aspect-ratio: 1/1; object-fit: cover;"
                        onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'">
                </div>
                <?php if (!empty($images)): ?>
                    <div class="product-thumbnails d-flex gap-2" style="overflow-x: auto;">
                        <img src="<?= base_url('assets/img/products/' . $product->image) ?>" class="thumbnail active"
                            onclick="changeMainImage(this, '<?= base_url('assets/img/products/' . $product->image) ?>')"
                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--yellow);">
                        <?php foreach ($images as $img): ?>
                            <img src="<?= base_url('assets/img/products/' . $img->image) ?>" class="thumbnail"
                                onclick="changeMainImage(this, '<?= base_url('assets/img/products/' . $img->image) ?>')"
                                style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent;">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1>
                    <?= htmlspecialchars($product->name) ?>
                </h1>
                <div class="price-container mb-3">
                    <?php if ($product->discount_percent > 0): ?>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="original-price-tag">Rp
                                <?= number_format($product->original_price, 0, ',', '.') ?></span>
                            <span class="badge bg-danger"><?= $product->discount_name ?>
                                -<?= $product->discount_percent ?>%</span>
                        </div>
                    <?php endif; ?>
                    <div class="price-tag">Rp <?= number_format($product->price, 0, ',', '.') ?></div>
                </div>
                <div class="product-desc-wrapper">
                    <p class="product-desc collapsed" id="productDesc">
                        <?= nl2br(htmlspecialchars($product->description)) ?>
                    </p>
                    <?php if (strlen($product->description) > 150): ?>
                        <button type="button" class="btn-toggle-desc" onclick="toggleFullDesc(this)">
                            <span>Lihat Selengkapnya</span> <i class="fas fa-chevron-down"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <form id="productForm">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="hidden" name="variant_id" id="selectedVariant" value="">
                    <input type="hidden" name="product_name" id="productName"
                        value="<?= htmlspecialchars($product->name) ?>">
                    <input type="hidden" name="product_price" id="productPrice" value="<?= (int) $product->price ?>">
                    <input type="hidden" name="product_image" id="productImage" value="<?= $product->image ?>">

                    <!-- Size Selector -->
                    <div class="variant-selector">
                        <label><i class="fas fa-ruler"></i> Pilih Ukuran</label>
                        <div class="size-options" id="sizeOptions">
                            <?php
                            $available_sizes = [];
                            foreach ($variants as $v) {
                                if ($v->stock > 0 && !in_array($v->size, $available_sizes)) {
                                    $available_sizes[] = $v->size;
                                }
                            }
                            $all_sizes = ['S', 'M', 'L', 'XL', 'XXL', 'ALL'];
                            foreach ($all_sizes as $size):
                                $has_stock = in_array($size, $available_sizes);
                                $size_exists = false;
                                foreach ($variants as $v) {
                                    if ($v->size === $size) {
                                        $size_exists = true;
                                        break;
                                    }
                                }
                                if (!$size_exists)
                                    continue;
                                ?>
                                <button type="button" class="size-btn <?= !$has_stock ? 'disabled' : '' ?>"
                                    data-size="<?= $size ?>" <?= !$has_stock ? 'disabled' : '' ?>
                                    onclick="selectSize(this, '<?= $size ?>')">
                                    <?= $size ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Color Selector -->
                    <div class="variant-selector">
                        <label><i class="fas fa-palette"></i> Pilih Warna</label>
                        <div class="color-options" id="colorOptions">
                            <p class="text-gray" style="font-size: 0.85rem;">Pilih ukuran terlebih dahulu</p>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <p class="stock-info" id="stockInfo" style="display: none;">
                        Stok tersedia: <span id="stockCount">0</span>
                    </p>

                    <!-- Quantity -->
                    <div class="quantity-selector" id="qtySection" style="display: none;">
                        <label>Jumlah:</label>
                        <div class="qty-controls">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantity" id="qtyInput" class="qty-input" value="1" min="1"
                                max="1" readonly>
                            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button type="button" class="btn btn-primary btn-lg btn-block mt-3" id="addToCartBtn"
                        onclick="handleAddToCart()">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </form>

                <div class="mt-2" style="display: flex; gap: 20px; font-size: 0.85rem; color: var(--gray-500);">
                    <span><i class="fas fa-shield-alt text-yellow"></i> Pembayaran Aman</span>
                    <span><i class="fas fa-truck text-yellow"></i> Pengiriman Cepat</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header-custom">
            <div class="success-icon-wrapper">
                <div class="success-icon-bg"></div>
                <i class="fas fa-check"></i>
            </div>
        </div>
        <div class="modal-body-custom text-center">
            <h3>Berhasil!</h3>
            <p>Produk telah ditambahkan ke keranjang belanja Anda.</p>
        </div>
        <div class="modal-footer-custom">
            <a href="<?= base_url('cart') ?>" class="btn-checkout-premium">
                <span>Lihat Keranjang</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            <button onclick="closeModal()" class="btn-continue-shopping">
                Lanjut Belanja
            </button>
        </div>
    </div>
</div>

<style>
    .original-price-tag {
        text-decoration: line-through;
        color: var(--gray-500);
        font-size: 1.1rem;
    }

    .product-desc-wrapper {
        margin-bottom: 1.5rem;
    }

    .product-desc {
        color: var(--gray-400);
        margin-bottom: 0.5rem;
        line-height: 1.6;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .product-desc.collapsed {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        max-height: 4.8em;
    }

    .btn-toggle-desc {
        background: none;
        border: none;
        color: var(--yellow);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-toggle-desc:hover {
        color: var(--white);
        transform: translateX(5px);
    }

    .btn-toggle-desc i {
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .btn-toggle-desc.active i {
        transform: rotate(180deg);
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10001;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
    }

    .modal-content {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(10, 10, 10, 0.98) 100%);
        padding: 40px 30px;
        border-radius: 28px;
        max-width: 400px;
        width: 90%;
        border: 1px solid rgba(255, 215, 0, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 20px rgba(255, 215, 0, 0.05);
        transform: scale(0.9) translateY(20px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .modal-overlay.show .modal-content {
        transform: scale(1) translateY(0);
    }

    .modal-header-custom {
        display: flex;
        justify-content: center;
        margin-bottom: 25px;
    }

    .success-icon-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--black);
        z-index: 1;
    }

    .success-icon-wrapper i {
        position: relative;
        z-index: 2;
    }

    .success-icon-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--yellow);
        border-radius: 30px;
        transform: rotate(45deg);
        z-index: 1;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    }

    .modal-body-custom h3 {
        font-family: var(--font-primary);
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--white);
        letter-spacing: -0.5px;
    }

    .modal-body-custom p {
        color: var(--gray-400);
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 30px;
    }

    .modal-footer-custom {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-checkout-premium {
        background: var(--yellow);
        color: var(--black);
        padding: 16px 24px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(255, 215, 0, 0.2);
    }

    .btn-checkout-premium:hover {
        background: var(--yellow-light);
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(255, 215, 0, 0.3);
    }

    .btn-continue-shopping {
        background: rgba(255, 255, 255, 0.05);
        color: var(--gray-300);
        padding: 14px 24px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-continue-shopping:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--white);
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 35px 20px;
        }

        .modal-body-custom h3 {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    // Variant data from PHP
    var variants = <?= json_encode($variants) ?>;
    var selectedSize = null;
    var selectedColor = null;
    var selectedVariantObj = null;

    function selectSize(el, size) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        selectedSize = size;
        selectedColor = null;
        selectedVariantObj = null;
        document.getElementById('selectedVariant').value = '';
        document.getElementById('addToCartBtn').disabled = true;
        document.getElementById('stockInfo').style.display = 'none';
        document.getElementById('qtySection').style.display = 'none';

        // Show available colors for this size
        var colorContainer = document.getElementById('colorOptions');
        colorContainer.innerHTML = '';

        var colorsForSize = variants.filter(function (v) {
            return v.size === size && v.stock > 0;
        });

        // Get unique colors
        var uniqueColors = [];
        var colorMap = {};
        colorsForSize.forEach(function (v) {
            if (!colorMap[v.color]) {
                colorMap[v.color] = true;
                uniqueColors.push(v);
            }
        });

        uniqueColors.forEach(function (v) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'color-btn';
            btn.style.backgroundColor = v.color_hex;
            btn.title = v.color;
            if (v.color_hex === '#FFFFFF' || v.color_hex === '#ffffff') {
                btn.style.border = '3px solid var(--gray-600)';
            }

            // If multiple colors, let user choose. If only one, it will be auto-selected below.
            if (uniqueColors.length > 1) {
                btn.onclick = function () { selectColor(this, v); };
            }

            colorContainer.appendChild(btn);

            // Add color label
            var label = document.createElement('span');
            label.style.cssText = 'font-size:0.75rem;color:var(--gray-500);margin-left:-5px;margin-right:10px;';
            label.textContent = v.color;
            colorContainer.appendChild(label);

            // Auto-select if it's the only color
            if (uniqueColors.length === 1) {
                selectColor(btn, v);
            }
        });
    }

    function selectColor(el, variant) {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        selectedColor = variant.color;
        selectedVariantObj = variant;

        document.getElementById('selectedVariant').value = variant.id;
        document.getElementById('stockInfo').style.display = 'block';
        document.getElementById('stockCount').textContent = variant.stock;
        document.getElementById('qtySection').style.display = 'flex';
        document.getElementById('qtyInput').max = variant.stock;
        document.getElementById('qtyInput').value = 1;
        document.getElementById('addToCartBtn').disabled = false;

        // Update Price Display
        if (variant.price) {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(variant.price).replace('Rp', 'Rp ');
            document.querySelector('.price-tag').textContent = formattedPrice;
            document.getElementById('productPrice').value = variant.price;

            // Handle Original Price Display for variants
            const originalPriceTag = document.querySelector('.original-price-tag');
            if (originalPriceTag && variant.original_price && variant.original_price > variant.price) {
                const formattedOriginal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(variant.original_price).replace('Rp', 'Rp ');
                originalPriceTag.textContent = formattedOriginal;
            }
        }

        // Visual feedback that the button is "ready"
        document.getElementById('addToCartBtn').style.opacity = '1';
    }

    function changeQty(delta) {
        var input = document.getElementById('qtyInput');
        var newVal = parseInt(input.value) + delta;
        if (newVal < 1) newVal = 1;
        if (newVal > parseInt(input.max)) newVal = parseInt(input.max);
        input.value = newVal;
    }

    function changeMainImage(thumb, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(t => {
            t.classList.remove('active');
            t.style.border = '2px solid transparent';
        });
        thumb.classList.add('active');
        thumb.style.border = '2px solid var(--yellow)';
    }

    function handleAddToCart() {
        if (!selectedSize) {
            showNotice("Pilih ukuran terlebih dahulu", "error");
            // Scroll to size options
            document.getElementById('sizeOptions').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (!selectedVariantObj) {
            showNotice("Pilih warna terlebih dahulu", "error");
            return;
        }

        var item = {
            product_id: document.querySelector('input[name="product_id"]').value,
            product_name: document.getElementById('productName').value,
            product_price: parseFloat(document.getElementById('productPrice').value),
            product_image: document.getElementById('productImage').value,
            variant_id: selectedVariantObj.id,
            size: selectedVariantObj.size,
            color: selectedVariantObj.color,
            stock: parseInt(selectedVariantObj.stock),
            quantity: parseInt(document.getElementById('qtyInput').value)
        };

        addToCart(item);

        // Show modal with animation
        var modal = document.getElementById('successModal');
        modal.style.display = 'flex';
        // Force reflow
        modal.offsetHeight;
        modal.classList.add('show');
    }

    function closeModal() {
        var modal = document.getElementById('successModal');
        modal.classList.remove('show');
        // Wait for animation to finish before hiding
        setTimeout(function () {
            modal.style.display = 'none';
        }, 300);
    }

    function showNotice(msg, type) {
        // Simple toast notification
        var toast = document.createElement('div');
        toast.className = 'custom-toast ' + type;
        toast.innerHTML = (type === 'error' ? '<i class="fas fa-exclamation-circle"></i> ' : '<i class="fas fa-check-circle"></i> ') + msg;
        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.add('show'), 100);

        // Animate out
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    function toggleFullDesc(btn) {
        const desc = document.getElementById('productDesc');
        const isCollapsed = desc.classList.contains('collapsed');

        if (isCollapsed) {
            desc.classList.remove('collapsed');
            btn.classList.add('active');
            btn.querySelector('span').textContent = 'Sembunyikan';
        } else {
            desc.classList.add('collapsed');
            btn.classList.remove('active');
            btn.querySelector('span').textContent = 'Lihat Selengkapnya';
            desc.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
</script>

<style>
    .custom-toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #333;
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        z-index: 10002;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        white-space: nowrap;
    }

    .custom-toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .custom-toast.error {
        background: #dc3545;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .custom-toast i {
        font-size: 1.2rem;
    }
</style>