<!-- Product List -->
<div class="product-list-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Koleksi Produk</h2>
            <p class="section-subtitle">Temukan gaya terbaikmu dengan koleksi terbaru dari
                <?= get_setting('site_name', 'Peweka') ?>.
            </p>
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
                                <a href="<?= base_url('produk/' . $p->slug) ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title">
                                <a href="<?= base_url('produk/' . $p->slug) ?>"><?= htmlspecialchars($p->name) ?></a>
                            </h3>
                            <div class="product-price">Rp <?= number_format($p->price, 0, ',', '.') ?></div>
                            <div class="product-desc-wrapper mt-2" onclick="event.preventDefault(); event.stopPropagation();">
                                <p class="product-desc-list collapsed" id="desc-list-<?= $p->id ?>">
                                    <?= htmlspecialchars($p->description) ?>
                                </p>
                                <?php if (strlen($p->description) > 80): ?>
                                    <button type="button" class="btn-toggle-list-desc"
                                        onclick="toggleListDesc(this, <?= $p->id ?>)">
                                        Lihat Selengkapnya
                                    </button>
                                <?php endif; ?>
                            </div>
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

    /* Product List Description Toggle Styles */
    .product-desc-wrapper {
        position: relative;
        z-index: 5;
    }

    .product-desc-list {
        font-size: 0.85rem;
        color: var(--gray-400);
        margin-bottom: 4px;
        line-height: 1.5;
        transition: all 0.3s ease;
    }

    .product-desc-list.collapsed {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 3em;
    }

    .btn-toggle-list-desc {
        background: none;
        border: none;
        color: var(--yellow);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0;
        cursor: pointer;
        display: block;
        transition: all 0.2s;
    }

    .btn-toggle-list-desc:hover {
        text-decoration: underline;
        transform: translateX(3px);
    }
</style>

<script>
    function toggleListDesc(btn, id) {
        const desc = document.getElementById('desc-list-' + id);
        const isCollapsed = desc.classList.contains('collapsed');

        if (isCollapsed) {
            desc.classList.remove('collapsed');
            btn.textContent = 'Sembunyikan';
        } else {
            desc.classList.add('collapsed');
            btn.textContent = 'Lihat Selengkapnya';
        }
    }
</script>