<!-- Products List Professional UI -->
<style>
    .page-header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-title h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    /* FORCED HYPER-ROBUST PAGINATION STYLES */
    .pagination-container,
    div.pagination-container {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 16px !important;
        padding: 15px 25px !important;
        margin-top: 40px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4) !important;
    }

    .pagination-container nav,
    .pagination-container ul {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        gap: 8px !important;
    }

    .pagination-container li {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        display: block !important;
    }

    .pagination-container li::before {
        display: none !important;
        content: none !important;
    }

    .pagination-container a,
    .pagination-container span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0 18px !important;
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 10px !important;
        color: #fff !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        transition: all 0.2s !important;
        line-height: 1 !important;
    }

    .pagination-container a:hover {
        background: var(--yellow) !important;
        color: #000 !important;
        transform: translateY(-2px) !important;
    }

    .pagination-container li.active span {
        background: var(--yellow) !important;
        color: #000 !important;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3) !important;
    }

    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: var(--black-mid);
    }

    .product-name {
        font-weight: 600;
        color: var(--white);
    }

    .product-price {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        color: var(--yellow);
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .status-badge {
        min-width: 80px;
        justify-content: center;
    }
</style>

<div class="page-header-actions">
    <div class="page-title">
        <h2>Daftar Produk</h2>
        <div style="color: var(--gray-500); font-size: 0.9rem;">Kelola koleksi produk dan ketersediaan stok</div>
    </div>
    <a href="<?= base_url('admin/product/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="table-card" style="border-radius: 16px; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Produk</th>
                <th>Nama & Kategori</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('assets/img/products/' . $product->image) ?>"
                                alt="<?= htmlspecialchars($product->name) ?>" class="product-img"
                                onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'">
                        </td>
                        <td>
                            <div class="product-name"><?= htmlspecialchars($product->name) ?></div>
                            <div class="product-category"><?= htmlspecialchars($product->category_name) ?></div>
                        </td>
                        <td style="text-align: right;">
                            <span class="product-price">Rp <?= number_format($product->price, 0, ',', '.') ?></span>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge <?= $product->is_active ? 'badge-active' : 'badge-inactive' ?> status-badge">
                                <?= $product->is_active ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display:flex; gap:8px; justify-content: flex-end;">
                                <a href="<?= base_url('admin/product/edit/' . $product->id) ?>" class="btn btn-outline btn-sm"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('admin/product/delete/' . $product->id) ?>"
                                    class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        <div style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-box-open fa-4x"
                                style="color: var(--gray-700); margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray-500);">Belum ada produk yang ditambahkan.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($pagination)): ?>
    <?php
    $start_row = ($page_number - 1) * $per_page + 1;
    $end_row = min($page_number * $per_page, $total_rows);
    ?>
    <div class="pagination-container">
        <div class="pagination-info">Menampilkan <?= $start_row ?> - <?= $end_row ?> dari <?= $total_rows ?> produk</div>
        <?= $pagination ?>
    </div>
<?php endif; ?>