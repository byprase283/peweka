<!-- Dashboard -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--yellow);">📦</div>
        <div class="stat-value"><?= $total_orders ?></div>
        <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: #FFD700;">⏳</div>
        <div class="stat-value" style="color: var(--yellow);"><?= $pending ?></div>
        <div class="stat-label">Menunggu Konfirmasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--blue);">✅</div>
        <div class="stat-value" style="color: var(--blue);"><?= $confirmed ?></div>
        <div class="stat-label">Dikonfirmasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--purple);">🚚</div>
        <div class="stat-value" style="color: var(--purple);"><?= $shipped ?></div>
        <div class="stat-label">Dikirim</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color: var(--green);">✔️</div>
        <div class="stat-value" style="color: var(--green);"><?= $delivered ?></div>
        <div class="stat-label">Diterima</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value" style="color: var(--yellow); font-size: 1.4rem;">Rp <?= number_format($revenue, 0, ',', '.') ?></div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="table-card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> Pesanan Terbaru</h3>
        <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_orders)): ?>
                <?php foreach (array_slice($recent_orders, 0, 10) as $order): ?>
                <tr>
                    <td><strong><?= $order->order_code ?></strong></td>
                    <td><?= htmlspecialchars($order->customer_name) ?></td>
                    <td>Rp <?= number_format($order->total, 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $order->status ?>"><?= ucfirst($order->status) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                    <td><a href="<?= base_url('admin/order/' . $order->id) ?>" class="btn btn-outline btn-sm">Detail</a></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:30px; color:var(--gray-500);">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
