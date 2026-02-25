<!-- Order Detail Professional UI -->
<style>
    #shipModal {
        margin-top: 5px;
    }

    .order-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background: var(--black-card);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .order-code-badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 1.4rem;
        color: var(--yellow);
    }

    .info-card {
        background: var(--black-card);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 25px;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .info-card h4 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 1.1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 12px;
    }

    .info-card h4 i {
        color: var(--yellow);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.9rem;
    }

    .info-label {
        color: var(--gray-500);
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        text-align: right;
    }

    .order-items-table {
        margin-top: 15px;
    }

    .order-items-table th {
        background: rgba(255, 255, 255, 0.02);
        color: var(--gray-500);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 12px 15px;
    }

    .order-items-table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
    }

    .item-total-row {
        background: rgba(255, 215, 0, 0.02);
    }

    .item-total-label {
        font-weight: 700;
        color: var(--gray-300);
    }

    .item-total-value {
        font-weight: 800;
        color: var(--yellow);
        font-size: 1.1rem;
    }

    .shipping-estimate-box {
        background: rgba(255, 193, 7, 0.05);
        border: 1px dashed var(--yellow);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }

    .action-sidebar {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .action-btn {
        width: 100%;
        justify-content: flex-start;
        padding: 12px 20px;
    }

    .payment-proof-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--gray-700);
        cursor: pointer;
    }

    .payment-proof-container:hover .proof-overlay {
        opacity: 1;
    }

    .proof-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        color: white;
        font-weight: 600;
    }
</style>

<div class="mb-4">
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
        style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); color: #28a745; border-radius: 12px; padding: 15px 20px;">
        <i class="fas fa-check-circle me-2"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"
            style="float: right; background: none; border: none; color: white; opacity: 0.5;">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
        style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #dc3545; border-radius: 12px; padding: 15px 20px;">
        <i class="fas fa-exclamation-circle me-2"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"
            style="float: right; background: none; border: none; color: white; opacity: 0.5;">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<div class="order-header-info">
    <div>
        <div class="order-code-badge"><?= $order->order_code ?></div>
        <div style="color:var(--gray-500); font-size: 0.85rem; margin-top: 4px;">
            Dipesan pada <?= date('d M Y, H:i', strtotime($order->created_at)) ?>
        </div>
    </div>
    <div style="text-align: right;">
        <span class="badge badge-<?= $order->status ?>" style="padding: 6px 16px; font-size: 0.85rem;">
            <?= ucfirst($order->status) ?>
        </span>
        <div style="font-size: 1.2rem; font-weight: 800; color: var(--yellow); margin-top: 5px;">
            Rp <?= number_format($order->total, 0, ',', '.') ?>
        </div>
    </div>
</div>

<div class="row" style="display: flex; gap: 25px; align-items: stretch;">
    <!-- Main Content (Left) -->
    <div style="flex: 2; display: flex; flex-direction: column; gap: 25px;">

        <!-- Items Card -->
        <div class="info-card">
            <h4><i class="fas fa-shopping-cart"></i> Rincian Produk</h4>
            <div class="table-card order-items-table" style="background: transparent; border: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align: center;">Varian</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Harga Satuan</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order->items as $item): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($item->product_name) ?></div>
                                </td>
                                <td style="text-align: center; color: var(--gray-400);">
                                    <?= $item->size ?> / <?= $item->color ?>
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    <?= $item->quantity ?>
                                </td>
                                <td style="text-align: right;">
                                    Rp <?= number_format($item->subtotal / $item->quantity, 0, ',', '.') ?>
                                </td>
                                <td style="text-align: right; font-weight: 700; color: var(--white);">
                                    Rp <?= number_format($item->subtotal, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="info-item">
                    <span class="info-label">Subtotal Produk</span>
                    <span class="info-value">Rp <?= number_format($order->subtotal, 0, ',', '.') ?></span>
                </div>

                <?php if ($order->payment_method === 'cod'): ?>
                    <div class="shipping-estimate-box">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="font-weight: 700; color: var(--white);">
                                <i class="fas fa-truck-loading"></i> Ongkos Kirim <span
                                    class="badge badge-pending">Estimasi</span>
                            </span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fetchEstimate(<?= $order->id ?>)"
                                id="btnFetch">
                                <i class="fas fa-sync-alt"></i> Cek Estimasi
                            </button>
                        </div>

                        <div id="estimateResults" style="display: none; margin-bottom: 15px;">
                            <div class="spinner-border spinner-border-sm text-yellow" role="status"></div> Memuat...
                        </div>

                        <form action="<?= base_url('admin/order/update-shipping/' . $order->id) ?>" method="POST"
                            style="display: flex; gap: 10px;">
                            <div class="input-group" style="flex: 1; display:flex;">
                                <span
                                    style="background: var(--gray-800); padding: 10px 15px; border-radius: 8px 0 0 8px; border: 1px solid var(--gray-700); color: var(--gray-400); border-right:none;">Rp</span>
                                <input type="number" name="shipping_cost" class="form-control"
                                    value="<?= (int) $order->shipping_cost ?>" style="border-radius: 0 8px 8px 0;">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Ongkir</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="info-item">
                        <span class="info-label">Ongkos Kirim</span>
                        <span class="info-value">Rp <?= number_format($order->shipping_cost, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($order->discount > 0): ?>
                    <div class="info-item">
                        <span class="info-label">Diskon (<?= $order->voucher_code ?>)</span>
                        <span class="info-value" style="color: var(--green);">- Rp
                            <?= number_format($order->discount, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div class="info-item"
                    style="margin-top: 15px; padding-top: 15px; border-top: 2px solid var(--gray-800);">
                    <span style="font-size: 1.1rem; font-weight: 700;">TOTAL BAYAR</span>
                    <span style="font-size: 1.3rem; font-weight: 800; color: var(--yellow);">Rp
                        <?= number_format($order->total, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <?php if ($order->notes): ?>
            <div class="info-card" style="border-left: 4px solid var(--red);">
                <h4><i class="fas fa-sticky-note"></i> Catatan Pelanggan</h4>
                <p style="font-style: italic; color: var(--gray-300);">"<?= htmlspecialchars($order->notes) ?>"</p>
            </div>
        <?php endif; ?>

    </div>

    <!-- Sidebar (Right) -->
    <div style="flex: 1; display: flex; flex-direction: column; gap: 25px;">

        <!-- Customer Info -->
        <div class="info-card">
            <h4><i class="fas fa-user"></i> Pelanggan</h4>
            <div class="info-item">
                <span class="info-label">Nama</span>
                <span class="info-value"><?= htmlspecialchars($order->customer_name) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">WhatsApp</span>
                <span class="info-value">
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order->customer_phone) ?>" target="_blank"
                        style="color: var(--green);">
                        <?= htmlspecialchars($order->customer_phone) ?> <i class="fab fa-whatsapp"></i>
                    </a>
                </span>
            </div>
            <div style="margin-top: 15px;">
                <div class="info-label" style="margin-bottom: 5px;">Alamat Pengiriman:</div>
                <div style="font-size: 0.85rem; line-height: 1.5; color: var(--gray-300);">
                    <?= nl2br(htmlspecialchars($order->customer_address)) ?>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="info-card">
            <h4><i class="fas fa-wallet"></i> Pembayaran</h4>
            <div class="info-item">
                <span class="info-label">Metode</span>
                <span class="info-value">
                    <?php
                    $pm = $order->payment_method;
                    echo ($pm === 'midtrans') ? 'Midtrans' : (($pm === 'transfer') ? 'Transfer Bank' : (($pm === 'cod') ? 'COD' : ucfirst($pm)));
                    ?>
                </span>
            </div>

            <?php if ($order->tracking_number && $order->payment_method !== 'pickup'): ?>
                <div class="info-item" style="color: var(--yellow); font-weight: 700;">
                    <span class="info-label">No. Resi</span>
                    <span class="info-value">
                        <?= htmlspecialchars($order->tracking_number) ?>
                        <button type="button" class="btn btn-outline btn-sm ms-2"
                            style="padding: 2px 8px; font-size: 0.7rem; border-color: var(--yellow); color: var(--yellow);"
                            data-bs-toggle="modal" data-bs-target="#shipModal">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </span>
                </div>
            <?php elseif ($order->payment_method === 'pickup' && $order->status !== 'pending' && $order->status !== 'confirmed'): ?>
                <div class="info-item" style="color: var(--green); font-weight: 700;">
                    <span class="info-label">Metode</span>
                    <span class="info-value">Ambil di Toko</span>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px;">
                <div class="info-label" style="margin-bottom: 10px;">Bukti Pembayaran:</div>
                <?php if ($order->payment_proof): ?>
                    <div class="payment-proof-container"
                        onclick="window.open('<?= base_url('uploads/payments/' . $order->payment_proof) ?>', '_blank')">
                        <img src="<?= base_url('uploads/payments/' . $order->payment_proof) ?>" alt="Bukti Pembayaran"
                            style="width: 100%; display: block;">
                        <div class="proof-overlay">
                            <i class="fas fa-search-plus fa-2x"></i>
                        </div>
                    </div>
                <?php else: ?>
                    <div
                        style="padding: 30px; background: rgba(255,255,255,0.02); text-align: center; border-radius: 12px; color: var(--gray-600);">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <div style="font-size: 0.8rem;">Tidak ada bukti upload</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="info-card">
            <h4><i class="fas fa-cogs"></i> Kelola Order</h4>
            <div class="action-sidebar">
                <?php if ($order->status === 'pending'): ?>
                    <a href="<?= base_url('admin/order/confirm/' . $order->id) ?>" class="btn btn-success action-btn"
                        onclick="return confirm('Konfirmasi pembayaran ini?')">
                        <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                    </a>
                <?php endif; ?>

                <?php if ($order->status === 'confirmed'): ?>
                    <button type="button" class="btn btn-purple action-btn" data-bs-toggle="modal"
                        data-bs-target="#shipModal">
                        <i class="fas fa-box"></i>
                        <?= $order->payment_method === 'pickup' ? 'Tandai Siap Diambil' : 'Masukkan Resi / Kirim' ?>
                    </button>
                <?php endif; ?>

                <?php if ($order->status === 'shipped'): ?>
                    <a href="<?= base_url('admin/order/deliver/' . $order->id) ?>" class="btn btn-info action-btn"
                        onclick="return confirm('<?= $order->payment_method === 'pickup' ? 'Tandai pesanan sudah diambil?' : 'Status: Tiba di Tujuan?' ?>')">
                        <i class="fas fa-box-open"></i>
                        <?= $order->payment_method === 'pickup' ? 'Konfirmasi Sudah Diambil' : 'Konfirmasi Diterima' ?>
                    </a>
                <?php endif; ?>

                <?php if ($order->status === 'delivered'): ?>
                    <div
                        style="text-align: center; padding: 15px; background: rgba(34, 197, 94, 0.1); border-radius: 12px; border: 1px solid rgba(34, 197, 94, 0.2); color: var(--green); font-weight: 700;">
                        <i class="fas fa-check-double fa-2x mb-2 d-block"></i>
                        PESANAN SELESAI
                    </div>
                <?php endif; ?>

                <?php if (in_array($order->status, ['pending', 'confirmed'])): ?>
                    <a href="<?= base_url('admin/order/reject/' . $order->id) ?>" class="btn btn-danger action-btn"
                        onclick="return confirm('Tolak/Batalkan order ini?')">
                        <i class="fas fa-times-circle"></i> Batalkan Pesanan
                    </a>
                <?php endif; ?>

                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.05); margin: 5px 0;">

                <a href="<?= base_url('admin/order/send-wa/' . $order->id) ?>" class="btn btn-outline action-btn"
                    style="border-color: var(--green); color: var(--green);" target="_blank">
                    <i class="fab fa-whatsapp"></i> Update via WhatsApp
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Modal Masukkan Resi -->
<?php if (in_array($order->status, ['confirmed', 'shipped']) && !($order->status === 'shipped' && $order->payment_method === 'pickup')): ?>
    <div class="modal fade" id="shipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                <form action="<?= base_url('admin/order/ship/' . $order->id) ?>" method="POST">
                    <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px;">
                        <h5 class="modal-title" style="color: var(--yellow); font-weight: 700;">
                            <i class="fas <?= $order->payment_method === 'pickup' ? 'fa-store' : 'fa-truck' ?> me-2"></i>
                            <?php
                            if ($order->payment_method === 'pickup') {
                                echo 'Konfirmasi Siap Diambil';
                            } else {
                                echo $order->status === 'shipped' ? 'Update Nomor Resi' : 'Kirim Pesanan';
                            }
                            ?>
                        </h5>
                        <!-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button> -->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <?php if ($order->payment_method === 'pickup'): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-store fa-3x mb-3 text-yellow"></i>
                                <p style="color: var(--gray-300);">Tandai pesanan ini sebagai <strong>"Siap Diambil"</strong>?
                                </p>
                                <p style="font-size: 0.85rem; color: var(--gray-500);">Pelanggan akan diarahkan untuk mengambil
                                    pesanan di lokasi toko.</p>
                                <input type="hidden" name="tracking_number" value="PICKUP">
                            </div>
                        <?php else: ?>
                            <div class="form-group mb-0">
                                <label class="d-block mb-2"
                                    style="color: var(--gray-400); font-size: 0.85rem; font-weight: 600;">Nomor Resi
                                    Pengiriman</label>
                                <input type="text" name="tracking_number" class="form-control" id="trackingInput"
                                    placeholder="Contoh: JNE123456789..." value="<?= $order->tracking_number ?>"
                                    style="background: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 14px; color: #fff; width: 100%;">
                                <small class="text-muted mt-3 d-block" style="font-size: 0.75rem; line-height: 1.4;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <?= $order->status === 'shipped' ? 'Ganti nomor resi jika ada perubahan.' : 'Status pesanan akan berubah menjadi <strong>"Sedang Dikirim"</strong>.' ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 15px 20px;">
                        <button type="button" class="btn btn-outline btn-sm" data-bs-dismiss="modal"
                            style="border: none; color: var(--gray-500);">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm"
                            style="border-radius: 8px; font-weight: 700;">
                            <?php
                            if ($order->payment_method === 'pickup') {
                                echo 'Siap Diambil';
                            } else {
                                echo $order->status === 'shipped' ? 'Update Resi' : 'Konfirmasi Pengiriman';
                            }
                            ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Autofocus input when modal opens
    document.getElementById('shipModal').addEventListener('shown.bs.modal', function () {
        document.getElementById('trackingInput').focus();
    });

    function fetchEstimate(orderId) {
        const btn = document.getElementById('btnFetch');
        const results = document.getElementById('estimateResults');

        btn.disabled = true;
        results.style.display = 'block';
        results.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghubungkan ke API...';

        fetch('<?= base_url('admin/order/get-shipping-estimate/') ?>' + orderId)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    let html = '<div style="max-height: 150px; overflow-y: auto; background: var(--gray-800); border-radius: 8px; padding: 12px; border: 1px solid var(--gray-700); margin-top:10px; color:white;">';
                    let foundAny = false;

                    for (const courier in res.data) {
                        const data = res.data[courier];
                        let services = [];

                        if (Array.isArray(data)) {
                            services = data;
                        } else if (data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results[0]) {
                            services = data.rajaongkir.results[0].costs || [];
                        } else if (data.data) {
                            if (Array.isArray(data.data)) {
                                services = (data.data.length > 0 && data.data[0].costs) ? data.data[0].costs : data.data;
                            } else if (typeof data.data === 'object' && data.data.costs) {
                                services = data.data.costs;
                            }
                        } else if (Array.isArray(data.costs)) {
                            services = data.costs;
                        } else if (data.results && data.results[0]) {
                            services = data.results[0].costs || [];
                        }

                        if (services && services.length > 0) {
                            foundAny = true;
                            services.forEach((s, idx) => {
                                let price = 0;
                                if (typeof s.cost === 'number') {
                                    price = s.cost;
                                } else if (s.cost) {
                                    if (Array.isArray(s.cost)) {
                                        price = s.cost[0] ? (s.cost[0].value || s.cost[0].price || s.cost[0].cost || 0) : 0;
                                    } else {
                                        price = s.cost.value || s.cost.price || s.cost.cost || 0;
                                    }
                                } else {
                                    price = s.value || s.price || s.cost_value || s.shipping_cost || 0;
                                }

                                const serviceName = s.service || s.service_name || s.name || 'Layanan';
                                html += `<div style="display:flex; justify-content:space-between; margin-bottom:6px; padding-bottom:6px; border-bottom:1px solid rgba(255,255,255,0.05); font-size: 0.85rem;">
                <span><strong style="color:var(--yellow);">${courier.toUpperCase()}</strong> ${serviceName}</span>
                <span style="font-weight:700;">Rp ${price.toLocaleString('id-ID')}</span>
            </div>`;
                            });
                        }
                    }

                    if (!foundAny) {
                        html += '<div style="color:var(--gray-500); text-align:center; padding: 10px;">Layanan tidak tersedia.</div>';
                    }

                    html += '</div>';
                    results.innerHTML = html;
                } else {
                    results.innerHTML = '<div class="alert alert-danger" style="margin:0; padding:10px; font-size:0.8rem;">' + (res.message || 'Gagal memuat') + '</div>';
                }
            })
            .catch(err => {
                results.innerHTML = '<div class="alert alert-danger" style="margin:0; padding:10px; font-size:0.8rem;">Sistem Error</div>';
            })
            .finally(() => {
                btn.disabled = false;
            });
    }
</script>