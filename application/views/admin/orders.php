<!-- Orders List -->
<div class="tab-filters">
    <a href="<?= base_url('admin/orders') ?>" class="<?= empty($current_status) ? 'active' : '' ?>">Semua</a>
    <a href="<?= base_url('admin/orders/pending') ?>" class="<?= $current_status == 'pending' ? 'active' : '' ?>">⏳ Pending</a>
    <a href="<?= base_url('admin/orders/confirmed') ?>" class="<?= $current_status == 'confirmed' ? 'active' : '' ?>">✅ Confirmed</a>
    <a href="<?= base_url('admin/orders/shipped') ?>" class="<?= $current_status == 'shipped' ? 'active' : '' ?>">🚚 Shipped</a>
    <a href="<?= base_url('admin/orders/delivered') ?>" class="<?= $current_status == 'delivered' ? 'active' : '' ?>">✔️ Delivered</a>
    <a href="<?= base_url('admin/orders/rejected') ?>" class="<?= $current_status == 'rejected' ? 'active' : '' ?>">❌ Rejected</a>
</div>

<div class="table-card">
    <div class="card-header">
        <h3><i class="fas fa-shopping-bag"></i> Daftar Pesanan <?= $current_status ? '(' . ucfirst($current_status) . ')' : '' ?></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>WhatsApp</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong><?= $order->order_code ?></strong></td>
                    <td><?= htmlspecialchars($order->customer_name) ?></td>
                    <td><?= htmlspecialchars($order->customer_phone) ?></td>
                    <td>Rp <?= number_format($order->total, 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $order->status ?>"><?= ucfirst($order->status) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="<?= base_url('admin/order/' . $order->id) ?>" class="btn btn-outline btn-sm">Detail</a>
                            <a href="<?= base_url('admin/order/send-wa/' . $order->id) ?>" class="btn btn-success btn-sm" target="_blank" title="Kirim WA">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center; padding:30px; color:var(--gray-500);">Tidak ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
