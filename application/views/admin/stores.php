<!-- Stores List Professional UI -->
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

    .store-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--yellow);
        letter-spacing: 0.5px;
        font-size: 1.1rem;
    }

    .store-info {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .location-text {
        color: var(--gray-400);
        font-size: 0.85rem;
        line-height: 1.4;
    }
</style>

<div class="page-header-actions">
    <div class="page-title">
        <h2>Daftar Toko / Gudang</h2>
        <div style="color: var(--gray-500); font-size: 0.9rem;">Kelola lokasi toko dan titik keberangkatan pengiriman
            (Origin)</div>
    </div>
    <!-- No "Add Store" button in vouchers.php but if there was one it would go here -->
    <!-- <a href="<?= base_url('admin/store/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Toko
    </a> -->
</div>

<div class="table-card" style="border-radius: 16px; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Nama Toko</th>
                <th>Telepon</th>
                <th>Lokasi (Origin)</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stores)): ?>
                <?php foreach ($stores as $s): ?>
                    <tr>
                        <td>
                            <div class="store-name"><?= htmlspecialchars($s->name) ?></div>
                            <?php if ($s->is_default): ?>
                                <div class="store-info"><i class="fas fa-star text-yellow"></i> Toko Utama</div>
                            <?php endif; ?>
                        </td>
                        <td style="color: var(--white); font-weight: 500;">
                            <?= $s->phone ?: '-' ?>
                        </td>
                        <td>
                            <div class="location-text">
                                <?= $s->subdistrict_name ? $s->subdistrict_name . ', ' : '' ?>
                                <br>
                                <?= $s->city_name ? $s->city_name . ', ' : '' ?>
                                <?= $s->province_name ?: '-' ?>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($s->is_default): ?>
                                <span class="badge badge-active">Default (Origin)</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Cabang</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display:flex; gap:8px; justify-content: flex-end;">
                                <a href="<?= site_url('admin/store/edit/' . $s->id) ?>" class="btn btn-outline btn-sm"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        <div style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-store-alt fa-4x"
                                style="color: var(--gray-700); margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray-500);">Belum ada data toko yang ditambahkan.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($pagination)): ?>
    <div style="margin-top: 25px;">
        <?= $pagination ?>
    </div>
<?php endif; ?>