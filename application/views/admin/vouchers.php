<!-- Vouchers List Professional UI -->
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

    .voucher-code {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--yellow);
        letter-spacing: 1px;
        font-size: 1.1rem;
    }

    .voucher-info {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .voucher-value {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        color: var(--white);
    }
</style>

<div class="page-header-actions">
    <div class="page-title">
        <h2>Master Voucher</h2>
        <div style="color: var(--gray-500); font-size: 0.9rem;">Kelola kode promo dan diskon belanja pelanggan</div>
    </div>
    <a href="<?= base_url('admin/voucher/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Voucher
    </a>
</div>

<div class="table-card" style="border-radius: 16px; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Kode Voucher</th>
                <th style="text-align: center;">Tipe & Nilai</th>
                <th style="text-align: center;">Kuota (Sisa)</th>
                <th style="text-align: center;">Expired</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vouchers)): ?>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td>
                            <div class="voucher-code"><?= htmlspecialchars($v->code) ?></div>
                            <div class="voucher-info">Min. Order: Rp <?= number_format($v->min_order, 0, ',', '.') ?></div>
                        </td>
                        <td style="text-align: center;">
                            <div class="voucher-value">
                                <?php if ($v->type === 'percentage'): ?>
                                    <?= $v->value ?>%
                                    <?php if ($v->max_discount): ?>
                                        <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: normal;">
                                            Max: Rp <?= number_format($v->max_discount, 0, ',', '.') ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Rp <?= number_format($v->value, 0, ',', '.') ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="text-align: center; color: var(--white); font-weight: 600;">
                            <?= $v->used ?> / <?= $v->quota ?>
                        </td>
                        <td style="text-align: center; color: var(--gray-400); font-size: 0.85rem;">
                            <?= $v->expired_at ? date('d M Y', strtotime($v->expired_at)) : '<span style="color:var(--gray-600)">Unlimited</span>' ?>
                        </td>
                        <td style="text-align: center;">
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
                        <td style="text-align: right;">
                            <div style="display:flex; gap:8px; justify-content: flex-end;">
                                <a href="<?= base_url('admin/voucher/edit/' . $v->id) ?>" class="btn btn-outline btn-sm"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('admin/voucher/delete/' . $v->id) ?>"
                                    class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Hapus voucher ini?')">
                                    <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <div style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-ticket-alt fa-4x"
                                style="color: var(--gray-700); margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray-500);">Belum ada voucher yang ditambahkan.</p>
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
        <div class="pagination-info">Menampilkan <?= $start_row ?> - <?= $end_row ?> dari <?= $total_rows ?> voucher</div>
        <?= $pagination ?>
    </div>
<?php endif; ?>