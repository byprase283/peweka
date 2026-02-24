<!-- Orders List Professional UI -->
<style>
    .orders-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 25px;
    }

    .orders-title h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 5px;
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

    /* Modern Pill Tabs */
    .status-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 5px;
        overflow-x: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .status-tabs::-webkit-scrollbar {
        display: none;
    }

    .status-tab {
        padding: 10px 20px;
        background: var(--black-card);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 50px;
        color: var(--gray-400);
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-tab:hover {
        background: rgba(255, 215, 0, 0.05);
        border-color: rgba(255, 215, 0, 0.2);
        color: var(--white);
    }

    .status-tab.active {
        background: var(--yellow);
        color: var(--black);
        border-color: var(--yellow);
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2);
    }

    .status-tab .count {
        background: rgba(0, 0, 0, 0.1);
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .status-tab.active .count {
        background: rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Table Styling */
    .orders-table-container {
        border-radius: 16px;
        overflow: hidden;
    }

    .orders-table th {
        background: rgba(255, 255, 255, 0.02);
        padding: 18px 20px;
        color: var(--gray-500);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .orders-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .order-id-cell {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--yellow);
    }

    .customer-cell {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        font-weight: 600;
        color: var(--white);
    }

    .customer-wa {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .price-cell {
        font-weight: 700;
        color: var(--white);
    }

    .date-cell {
        font-size: 0.85rem;
        color: var(--gray-400);
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 10px;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state i {
        color: var(--gray-700);
        margin-bottom: 15px;
    }

    .empty-state p {
        color: var(--gray-500);
        font-size: 1rem;
    }
</style>

<div class="orders-page-header">
    <div class="orders-title">
        <h2>Daftar Pesanan</h2>
        <div style="color: var(--gray-500); font-size: 0.9rem;">Kelola dan pantau status transaksi toko</div>
    </div>
</div>

<div class="status-tabs">
    <a href="<?= base_url('admin/orders') ?>" class="status-tab <?= empty($current_status) ? 'active' : '' ?>">
        Semua
    </a>
    <a href="<?= base_url('admin/orders/pending') ?>"
        class="status-tab <?= $current_status == 'pending' ? 'active' : '' ?>">
        ⏳ Pending
    </a>
    <a href="<?= base_url('admin/orders/confirmed') ?>"
        class="status-tab <?= $current_status == 'confirmed' ? 'active' : '' ?>">
        ✅ Confirmed
    </a>
    <a href="<?= base_url('admin/orders/shipped') ?>"
        class="status-tab <?= $current_status == 'shipped' ? 'active' : '' ?>">
        🚚 Shipped
    </a>
    <a href="<?= base_url('admin/orders/delivered') ?>"
        class="status-tab <?= $current_status == 'delivered' ? 'active' : '' ?>">
        ✔️ Delivered
    </a>
    <a href="<?= base_url('admin/orders/rejected') ?>"
        class="status-tab <?= $current_status == 'rejected' ? 'active' : '' ?>">
        ❌ Rejected
    </a>
</div>

<div class="table-card orders-table-container">
    <table class="orders-table">
        <thead>
            <tr>
                <th>Kode Order</th>
                <th>Pelanggan</th>
                <th style="text-align: right;">Total Bayar</th>
                <th style="text-align: center;">Status</th>
                <th style="padding-left: 40px;">Waktu Pesan</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="order-id-cell"><?= $order->order_code ?></td>
                        <td>
                            <div class="customer-cell">
                                <span class="customer-name"><?= htmlspecialchars($order->customer_name) ?></span>
                                <span class="customer-wa"><?= htmlspecialchars($order->customer_phone) ?></span>
                            </div>
                        </td>
                        <td class="price-cell" style="text-align: right;">
                            Rp <?= number_format($order->total, 0, ',', '.') ?>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-<?= $order->status ?>" style="min-width: 90px; justify-content: center;">
                                <?= ucfirst($order->status) ?>
                            </span>
                        </td>
                        <td class="date-cell" style="padding-left: 40px;">
                            <div><?= date('d M Y', strtotime($order->created_at)) ?></div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">
                                <?= date('H:i', strtotime($order->created_at)) ?> WIB
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <div class="actions-cell" style="justify-content: flex-end;">
                                <a href="<?= base_url('admin/order/' . $order->id) ?>" class="btn btn-outline btn-sm"
                                    style="padding: 6px 15px;">
                                    Detail
                                </a>
                                <a href="<?= base_url('admin/order/send-wa/' . $order->id) ?>" class="btn btn-success btn-icon"
                                    target="_blank" title="Kirim WA">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-shopping-basket fa-4x"></i>
                            <p>Tidak ada pesanan ditemukan pada kategori ini.</p>
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
        <div class="pagination-info">Menampilkan <?= $start_row ?> - <?= $end_row ?> dari <?= $total_rows ?> pesanan</div>
        <?= $pagination ?>
    </div>
<?php endif; ?>