<!-- Vouchers List -->
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('admin/voucher/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Voucher
    </a>
</div>

<div class="table-card">
    <div class="card-header">
        <h3><i class="fas fa-ticket-alt"></i> Master Voucher</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Nilai</th>
                <th>Min Order</th>
                <th>Kuota</th>
                <th>Terpakai</th>
                <th>Expired</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vouchers)): ?>
                <?php foreach ($vouchers as $v): ?>
                <tr>
                    <td><strong style="color:var(--yellow); letter-spacing:1px;"><?= htmlspecialchars($v->code) ?></strong></td>
                    <td><?= $v->type === 'percentage' ? 'Persentase' : 'Nominal' ?></td>
                    <td>
                        <?php if ($v->type === 'percentage'): ?>
                            <?= $v->value ?>%
                            <?php if ($v->max_discount): ?>
                                <br><small style="color:var(--gray-500);">Max: Rp <?= number_format($v->max_discount, 0, ',', '.') ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            Rp <?= number_format($v->value, 0, ',', '.') ?>
                        <?php endif; ?>
                    </td>
                    <td>Rp <?= number_format($v->min_order, 0, ',', '.') ?></td>
                    <td><?= $v->quota ?></td>
                    <td><?= $v->used ?></td>
                    <td><?= $v->expired_at ? date('d/m/Y', strtotime($v->expired_at)) : '-' ?></td>
                    <td>
                        <?php
                        $is_expired = $v->expired_at && date('Y-m-d') > $v->expired_at;
                        $is_full = $v->quota > 0 && $v->used >= $v->quota;
                        ?>
                        <?php if (!$v->is_active): ?>
                            <span class="badge badge-inactive">Nonaktif</span>
                        <?php elseif ($is_expired): ?>
                            <span class="badge badge-rejected">Expired</span>
                        <?php elseif ($is_full): ?>
                            <span class="badge badge-rejected">Habis</span>
                        <?php else: ?>
                            <span class="badge badge-active">Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="<?= base_url('admin/voucher/edit/' . $v->id) ?>" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('admin/voucher/delete/' . $v->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus voucher ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center; padding:30px; color:var(--gray-500);">Belum ada voucher.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
