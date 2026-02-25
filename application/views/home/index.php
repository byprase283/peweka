<style>
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
</style>
<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-fire"></i> New Collection 2026
                </div>
                <h1>Express Your <span>Culture</span><br>Define The <span>Future</span></h1>
                <p class="tagline">
                    <?= get_setting('site_about', 'Peweka hadir dengan koleksi streetwear premium yang menggabungkan budaya lokal dan gaya masa depan.') ?>
                </p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="#products" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i> Lihat Koleksi
                    </a>
                    <a href="#about" class="btn btn-outline btn-lg">
                        <i class="fas fa-info-circle"></i> Tentang Kami
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <!-- <div class="hero-logo-circle">P</div> -->
                <img src="<?= base_url('assets/img/' . get_setting('site_logo', 'logo.png')) ?>"
                    alt="<?= get_setting('site_name', 'Peweka') ?> Logo" class="hero-logo-circle">
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="section" id="products">
    <div class="container">
        <div class="section-header">
            <h2>Koleksi <span>Terbaru</span></h2>
            <p>Temukan gaya yang paling cocok dengan kepribadianmu. Semua produk menggunakan bahan premium.</p>
        </div>

        <!-- Category Pills -->
        <?php if (!empty($categories)): ?>
            <div class="category-container" style="margin-bottom: 40px;">
                <div class="category-pills">
                    <a href="<?= base_url() ?>#products"
                        class="category-pill <?= empty($active_category) ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i> Semua
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= base_url('?category=' . $cat->slug) ?>#products"
                            class="category-pill <?= $active_category == $cat->slug ? 'active' : '' ?>">
                            <i class="fas fa-tag"></i> <?= $cat->name ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="products-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <a href="<?= base_url('produk/' . $product->id) ?>" class="product-card" id="product-<?= $product->id ?>">
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
                            <h3>
                                <?= htmlspecialchars($product->name) ?>
                            </h3>
                            <p class="card-desc">
                                <?= htmlspecialchars($product->description) ?>
                            </p>
                            <div class="price-area">
                                <?php if ($product->discount_percent > 0): ?>
                                    <span class="price-old">Rp <?= number_format($product->original_price, 0, ',', '.') ?></span>
                                    <span class="discount-text"><?= $product->discount_name ?> -<?= $product->discount_percent ?>%</span>
                                <?php endif; ?>
                                <div class="price">
                                    Rp <?= number_format($product->price, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
                    <p class="text-gray">Belum ada produk tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($products)): ?>
            <div style="text-align: center; margin-top: 50px;">
                <a href="<?= base_url('shop') ?>" class="btn btn-outline" style="padding: 12px 30px;">
                    Lihat Semua Produk <i class="fas fa-arrow-right" style="margin-left: 8px; font-size: 0.8rem;"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- About Section -->
<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div>
                <div class="section-header" style="text-align: left; margin-bottom: 20px;">
                    <h2>Kenapa <span><?= get_setting('site_name', 'Peweka') ?></span>?</h2>
                </div>
                <p style="color: var(--gray-400); line-height: 1.8; margin-bottom: 20px;">
                    <?= nl2br(htmlspecialchars(get_setting('site_about', 'Peweka lahir dari semangat menggabungkan kekayaan budaya lokal dengan desain masa depan. Kami percaya bahwa fashion bukan hanya tentang penampilan, tapi juga tentang identitas dan cerita.'))) ?>
                </p>
                <div class="about-features">
                    <div class="feature-card">
                        <div class="feature-icon">🧵</div>
                        <h4>Premium Quality</h4>
                        <p>Bahan cotton combed premium, jahitan rapi dan tahan lama</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎨</div>
                        <h4>Unique Design</h4>
                        <p>Desain eksklusif yang tidak akan kamu temukan di tempat lain</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📦</div>
                        <h4>Fast Delivery</h4>
                        <p>Pengiriman cepat ke seluruh Indonesia</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💎</div>
                        <h4>Limited Edition</h4>
                        <p>Koleksi terbatas agar gayamu tetap eksklusif</p>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <!-- <div class="hero-logo-circle" style="width: 300px; height: 300px; font-size: 6rem;"> -->
                <img src="<?= base_url('assets/img/' . get_setting('site_logo', 'logo.png')) ?>"
                    alt="<?= get_setting('site_name', 'Peweka') ?> Logo" class="hero-logo-circle">
                <!-- </div> -->
            </div>
        </div>
    </div>
</section>