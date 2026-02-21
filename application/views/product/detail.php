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
                <div class="price-tag">Rp
                    <?= number_format($product->price, 0, ',', '.') ?>
                </div>
                <p class="product-desc">
                    <?= htmlspecialchars($product->description) ?>
                </p>

                <form id="productForm">
                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <input type="hidden" name="variant_id" id="selectedVariant" value="">
                    <input type="hidden" name="product_name" id="productName"
                        value="<?= htmlspecialchars($product->name) ?>">
                    <input type="hidden" name="product_price" id="productPrice" value="<?= $product->price ?>">
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
                    <button type="button" class="btn btn-primary btn-lg btn-block mt-3" id="addToCartBtn" disabled
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
    <div class="modal-content text-center">
        <div class="modal-body py-4">
            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem; color: #28a745;"></i>
            <h3>Berhasil!</h3>
            <p>Produk telah ditambahkan ke keranjang belanja Anda.</p>
            <div class="d-flex gap-2 justify-content-center mt-4">
                <button onclick="closeModal()" class="btn btn-light"
                    style="border: 1px solid var(--gray-700); background: #333; color: white;">Lanjut Belanja</button>
                <a href="<?= base_url('cart') ?>" class="btn btn-primary"
                    style="background: var(--yellow); color: var(--black);">Lihat Keranjang</a>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: #1a1a1a;
        padding: 2.5rem;
        border-radius: 20px;
        max-width: 450px;
        width: 90%;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .text-success {
        color: #28a745;
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
        if (!selectedVariantObj) return;

        var item = {
            product_id: document.querySelector('input[name="product_id"]').value,
            product_name: document.getElementById('productName').value,
            product_price: parseFloat(document.getElementById('productPrice').value),
            product_image: document.getElementById('productImage').value,
            variant_id: selectedVariantObj.id,
            size: selectedVariantObj.size,
            color: selectedVariantObj.color,
            quantity: parseInt(document.getElementById('qtyInput').value)
        };

        addToCart(item);
        document.getElementById('successModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }
</script>