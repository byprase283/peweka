<style>
    .form-control {
        width: 70%;
    }

    .btn {
        padding: 15px 25px;

    }
</style>
<div class="container" style="padding-top: 120px; padding-bottom: 60px;">

    <div class="row">

        <!-- Filter Sidebar (Off-Canvas) -->
        <div class="filter-sidebar" id="filterSidebar">
            <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="m-0" style="font-family:'Outfit', sans-serif; font-weight:700;">Filter</h4>
                <button type="button" class="btn btn-sm btn-outline" onclick="toggleFilterSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div> -->

            <form action="<?= base_url('shop') ?>" method="get">

                <!-- Search -->
                <div class="filter-group mb-3" style="margin-bottom: 30px;">
                    <label class="filter-label">Cari Produk</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Kata kunci..." style=""
                            value="<?= htmlspecialchars($filters['search']) ?>">
                        <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <!-- Categories -->
                <div class="filter-group mb-3" style="margin-bottom: 30px;">
                    <label class="filter-label">Kategori</label>
                    <div class="category-buttons">
                        <label class="filter-btn-wrapper">
                            <input type="radio" name="category" value="" <?= empty($filters['category']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span class="filter-btn">Semua</span>
                        </label>
                        <?php foreach ($categories as $cat): ?>
                            <label class="filter-btn-wrapper">
                                <input type="radio" name="category" value="<?= $cat->slug ?>"
                                    <?= $filters['category'] == $cat->slug ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span class="filter-btn"><?= $cat->name ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group mb-4">
                    <label class="filter-label">Rentang Harga</label>
                    <div class="price-slider-wrapper">
                        <div class="slider-values">
                            <span id="min-price-display">Rp
                                <?= number_format($filters['min_price'] ?: $price_range->min_price, 0, ',', '.') ?></span>
                            <span id="max-price-display">Rp
                                <?= number_format($filters['max_price'] ?: $price_range->max_price, 0, ',', '.') ?></span>
                        </div>
                        <div class="dual-range-slider">
                            <div class="slider-track"></div>
                            <input type="range" min="<?= $price_range->min_price ?>"
                                max="<?= $price_range->max_price ?>"
                                value="<?= $filters['min_price'] ?: $price_range->min_price ?>" id="slider-min"
                                step="5000">
                            <input type="range" min="<?= $price_range->min_price ?>"
                                max="<?= $price_range->max_price ?>"
                                value="<?= $filters['max_price'] ?: $price_range->max_price ?>" id="slider-max"
                                step="5000">
                        </div>
                        <input type="hidden" name="min_price" id="input-min" value="<?= $filters['min_price'] ?>">
                        <input type="hidden" name="max_price" id="input-max" value="<?= $filters['max_price'] ?>">
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 10px;">
                    <button type="submit" class="btn btn-sm btn-outline flex-fill">
                        Terapkan
                    </button>

                    <a href="<?= base_url('shop') ?>" class="btn btn-danger btn-sm flex-fill">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Product Grid (Full Width) -->
        <div class="col-12">
            <!-- Premium Shop Header Redesign -->
            <div class="shop-header-premium">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="shop-title-area">
                        <!-- <h2 style="font-family:'Outfit', sans-serif; margin-bottom: 0;">Belanja <span>Koleksi</span>
                        </h2> -->
                    </div>
                    <div class="shop-actions-area">
                        <button class="btn-filter-premium" onclick="toggleFilterSidebar()">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <div class="sort-dropdown-wrapper">
                            <select class="sort-select-premium" onchange="location = this.value;">
                                <option
                                    value="<?= current_url() ?>?<?= http_build_query(array_merge($filters, ['sort' => 'newest'])) ?>"
                                    <?= $filters['sort'] == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                                <option
                                    value="<?= current_url() ?>?<?= http_build_query(array_merge($filters, ['sort' => 'price_low'])) ?>"
                                    <?= $filters['sort'] == 'price_low' ? 'selected' : '' ?>>Harga Terendah</option>
                                <option
                                    value="<?= current_url() ?>?<?= http_build_query(array_merge($filters, ['sort' => 'price_high'])) ?>"
                                    <?= $filters['sort'] == 'price_high' ? 'selected' : '' ?>>Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="shop-meta-info">
                    Menampilkan <?= count($products) ?> Produk Pilihan
                    <i class="fas fa-circle"></i>
                    Streetwear Culture 2026
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <div style="font-size: 4rem; color: var(--gray-600); margin-bottom: 20px;">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Produk tidak ditemukan</h3>
                    <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                    <a href="<?= base_url('shop') ?>" class="btn btn-primary mt-3">Lihat Semua Produk</a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="<?= base_url('produk/' . $product->slug) ?>" class="product-card">
                            <div class="card-image">
                                <?php if ($product->created_at > date('Y-m-d H:i:s', strtotime('-7 days'))): ?>
                                    <span class="card-badge">New</span>
                                <?php endif; ?>
                                <?php if ($product->discount_percent > 0): ?>
                                    <span class="card-badge discount"
                                        style="top: 40px; background: #dc3545;">-<?= $product->discount_percent ?>%</span>
                                <?php endif; ?>
                                <img src="<?= base_url('assets/img/products/' . $product->image) ?>"
                                    alt="<?= htmlspecialchars($product->name) ?>"
                                    onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'">
                            </div>
                            <div class="card-body">
                                <small class="text-muted"
                                    style="font-size: 0.75rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:5px; display:block;">
                                    <?= isset($product->category_name) ? $product->category_name : 'Uncategorized' ?>
                                </small>
                                <h3><?= htmlspecialchars($product->name) ?></h3>
                                <div class="price-area">
                                    <?php if ($product->discount_percent > 0): ?>
                                        <span class="price-old">Rp
                                            <?= number_format($product->original_price, 0, ',', '.') ?></span>
                                        <span class="discount-text"><?= $product->discount_name ?>
                                            -<?= $product->discount_percent ?>%</span>
                                    <?php endif; ?>
                                    <div class="price">Rp <?= number_format($product->price, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    <?= $pagination ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleFilterSidebar()"></div>

    <style>
        /* Additional Inline Styles for specific shop logic not in main CSS yet */
        .price-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .price-old {
            text-decoration: line-through;
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        .discount-text {
            color: #5bc0de;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 5px;
        }

        .card-badge.discount {
            background: #dc3545 !important;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-btn-wrapper input {
            display: none;
        }

        .filter-btn {
            display: inline-block;
            padding: 6px 16px;
            border: 1px solid var(--gray-600);
            border-radius: 50px;
            color: var(--gray-300);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn-wrapper input:checked+.filter-btn {
            background: var(--yellow);
            color: var(--black);
            border-color: var(--yellow);
            font-weight: 700;
        }

        .filter-btn:hover {
            border-color: var(--yellow);
            color: var(--yellow);
        }

        .filter-btn-wrapper input:checked+.filter-btn:hover {
            color: var(--black);
        }

        .filter-color-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--black-mid);
            border: 1px solid var(--gray-700);
            border-radius: 6px;
            color: var(--gray-400);
            font-size: 0.8rem;
            cursor: pointer;
        }

        .filter-color-btn:hover,
        .filter-color-btn.active {
            border-color: var(--yellow);
            color: var(--yellow);
        }

        .filter-color-btn span {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        function toggleFilterSidebar() {
            document.getElementById('filterSidebar').classList.toggle('show');
            document.getElementById('sidebarBackdrop').classList.toggle('show');
            document.body.style.overflow = document.body.style.overflow === 'hidden' ? '' : 'hidden';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Range slider logic (same as before)
            const sliderMin = document.getElementById('slider-min');
            const sliderMax = document.getElementById('slider-max');
            const displayMin = document.getElementById('min-price-display');
            const displayMax = document.getElementById('max-price-display');
            const inputMin = document.getElementById('input-min');
            const inputMax = document.getElementById('input-max');
            const minGap = 10000;
            const track = document.querySelector('.slider-track');

            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function slideMin() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);
                if (valMax - valMin <= minGap) {
                    sliderMin.value = valMax - minGap;
                }
                displayMin.textContent = formatRupiah(sliderMin.value);
                inputMin.value = sliderMin.value;
                fillColor();
            }

            function slideMax() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);
                if (valMax - valMin <= minGap) {
                    sliderMax.value = valMin + minGap;
                }
                displayMax.textContent = formatRupiah(sliderMax.value);
                inputMax.value = sliderMax.value;
                fillColor();
            }

            function fillColor() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);
                let percent1 = ((valMin - sliderMin.min) / (sliderMin.max - sliderMin.min)) * 100;
                let percent2 = ((valMax - sliderMax.min) / (sliderMax.max - sliderMax.min)) * 100;
                track.style.background = `linear-gradient(to right, var(--gray-600) ${percent1}%, var(--yellow) ${percent1}%, var(--yellow) ${percent2}%, var(--gray-600) ${percent2}%)`;
            }

            sliderMin.addEventListener('input', slideMin);
            sliderMax.addEventListener('input', slideMax);
            fillColor();
        });
    </script>



    <style>
        /* Custom Styles for Shop Page */
        .filter-label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            font-size: 0.9rem;
            color: var(--white);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--gray-400);
            transition: all 0.2s;
        }

        .custom-radio:hover {
            color: var(--yellow);
        }

        .custom-radio input {
            accent-color: var(--yellow);
        }

        .custom-radio input:checked+span {
            color: var(--yellow);
            font-weight: 600;
        }

        .btn-xs {
            padding: 4px 10px;
            font-size: 0.75rem;
        }



        @media (min-width: 769px) {

            /* Utility classes for desktop visibility */
            .d-md-none {
                display: none !important;
            }

            .d-md-block {
                display: block !important;
            }
        }

        /* Price Slider Styles */
        .price-slider-wrapper {
            padding: 10px 0;
        }

        .slider-values {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--yellow);
            margin-bottom: 10px;
        }

        .dual-range-slider {
            position: relative;
            width: 100%;
            height: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .slider-track {
            width: 100%;
            height: 5px;
            background: var(--gray-600);
            border-radius: 5px;
            position: absolute;
            top: 0;
        }

        .dual-range-slider input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 5px;
            background: transparent;
            position: absolute;
            top: 0;
            pointer-events: none;
        }

        .dual-range-slider input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: var(--yellow);
            cursor: pointer;
            pointer-events: auto;
            box-shadow: 0 0 0 2px var(--black);
            margin-top: -7px;
            /* Adjust based on track height */
        }

        .dual-range-slider input[type="range"]::-moz-range-thumb {
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: var(--yellow);
            cursor: pointer;
            pointer-events: auto;
            box-shadow: 0 0 0 2px var(--black);
            border: none;
        }

        .dual-range-slider input[type="range"]:active::-webkit-slider-thumb {
            background: var(--white);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sliderMin = document.getElementById('slider-min');
            const sliderMax = document.getElementById('slider-max');
            const displayMin = document.getElementById('min-price-display');
            const displayMax = document.getElementById('max-price-display');
            const inputMin = document.getElementById('input-min');
            const inputMax = document.getElementById('input-max');
            const minGap = 10000; // Minimum gap between handles
            const track = document.querySelector('.slider-track');

            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function slideMin() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);

                if (valMax - valMin <= minGap) {
                    sliderMin.value = valMax - minGap;
                }
                displayMin.textContent = formatRupiah(sliderMin.value);
                inputMin.value = sliderMin.value;
                fillColor();
            }

            function slideMax() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);

                if (valMax - valMin <= minGap) {
                    sliderMax.value = valMin + minGap;
                }
                displayMax.textContent = formatRupiah(sliderMax.value);
                inputMax.value = sliderMax.value;
                fillColor();
            }

            function fillColor() {
                let valMin = parseInt(sliderMin.value);
                let valMax = parseInt(sliderMax.value);
                let percent1 = ((valMin - sliderMin.min) / (sliderMin.max - sliderMin.min)) * 100;
                let percent2 = ((valMax - sliderMax.min) / (sliderMax.max - sliderMax.min)) * 100;

                track.style.background = `linear-gradient(to right, var(--gray-600) ${percent1}%, var(--yellow) ${percent1}%, var(--yellow) ${percent2}%, var(--gray-600) ${percent2}%)`;
            }

            sliderMin.addEventListener('input', slideMin);
            sliderMax.addEventListener('input', slideMax);

            // Initial call
            fillColor();
        });
    </script>