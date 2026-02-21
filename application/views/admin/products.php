<!-- Products List -->
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('admin/product/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="table-card">
    <div class="card-header">
        <h3><i class="fas fa-tshirt"></i> Daftar Produk</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <img src="<?= base_url('assets/img/products/' . $product->image) ?>" 
                             alt="<?= htmlspecialchars($product->name) ?>"
                             onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'"
                             style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
                    </td>
                    <td><strong><?= htmlspecialchars($product->name) ?></strong></td>
                    <td>Rp <?= number_format($product->price, 0, ',', '.') ?></td>
                    <td>
                        <span class="badge <?= $product->is_active ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $product->is_active ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="<?= base_url('admin/product/edit/' . $product->id) ?>" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= base_url('admin/product/delete/' . $product->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding:30px; color:var(--gray-500);">Belum ada produk.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
