<!-- Product List -->
<div class="product-list-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Koleksi Produk</h2>
            <p class="section-subtitle">Temukan gaya terbaikmu dengan koleksi terbaru dari
                <?= get_setting('site_name', 'Peweka') ?>.</p>
        </div>

        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center">
                    <p>Belum ada produk yang tersedia saat ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= base_url('assets/img/products/' . $p->image) ?>"
                                alt="<?= htmlspecialchars($p->name) ?>"
                                onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'">
                            <div class="product-overlay">
                                <a href="<?= base_url('produk/' . $p->id) ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title">
                                <a href="<?= base_url('produk/' . $p->id) ?>"><?= htmlspecialchars($p->name) ?></a>
                            </h3>
                            <div class="product-price">Rp <?= number_format($p->price, 0, ',', '.') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Additional styles for the product grid if not in style.css */
    .product-list-section {
        padding: 60px 0;
    }

    .section-header {
        margin-bottom: 50px;
    }

    .section-subtitle {
        color: var(--gray-500);
        margin-top: 10px;
    }
</style>