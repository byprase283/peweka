<div class="table-card">
    <div class="card-header">
        <h3>Daftar Toko / Gudang</h3>
        <small class="text-muted">Toko default akan digunakan sebagai titik keberangkatan (Origin) pengiriman.</small>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Toko</th>
                    <th>Telepon</th>
                    <th>Lokasi (Origin)</th>
                    <th>Status</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stores)): ?>
                    <?php foreach ($stores as $s): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars($s->name) ?>
                                </strong>
                            </td>
                            <td>
                                <?= $s->phone ?: '-' ?>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <?= $s->subdistrict_name ? $s->subdistrict_name . ', ' : '' ?>
                                    <?= $s->city_name ? $s->city_name . ', ' : '' ?>
                                    <?= $s->province_name ?: '-' ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($s->is_default): ?>
                                    <span class="badge badge-delivered"><i class="fas fa-check-circle"></i> Default (Origin)</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Cabang</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/store/edit/' . $s->id) ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data toko.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>