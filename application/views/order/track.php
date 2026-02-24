<!-- Order Track Page -->
<style>
    .tracking-timeline {
        padding: 5px 0;
        position: relative;
    }

    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: rgba(255, 255, 255, 0.1);
    }

    .tracking-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 25px;
    }

    .tracking-item:last-child {
        margin-bottom: 0;
    }

    .tracking-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 16px;
        height: 16px;
        background: var(--gray-700);
        border: 3px solid var(--black-card);
        border-radius: 50%;
        z-index: 1;
    }

    .tracking-item.latest .tracking-dot {
        background: var(--yellow);
        box-shadow: 0 0 10px var(--yellow);
    }

    .tracking-date {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .tracking-desc {
        font-size: 0.9rem;
        color: var(--white);
        line-height: 1.4;
    }

    /* Toggle Details Styles */
    .track-info {
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
        max-height: 1000px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .track-info.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .details-toggle-btn {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 12px 20px;
        color: var(--yellow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        margin-bottom: 15px;
        transition: all 0.2s;
        font-weight: 600;
        font-family: var(--font-primary);
        margin-top: 5px;
    }

    .details-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .details-toggle-btn i.fa-chevron-down {
        transition: transform 0.3s;
        font-size: 0.8rem;
    }

    .details-toggle-btn.active i.fa-chevron-down {
        transform: rotate(180deg);
    }

    /* Timeline Resi Styles */
    .timeline-resi-box {
        background: rgba(255, 215, 0, 0.05);
        border: 1px dashed rgba(255, 215, 0, 0.3);
        border-radius: 10px;
        padding: 12px 15px;
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .resi-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .resi-label {
        color: var(--gray-400);
        font-size: 0.8rem;
    }

    .resi-value {
        color: var(--yellow);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .resi-actions {
        display: flex;
        gap: 8px;
    }

    .btn-lacak-timeline {
        background: var(--yellow);
        color: black;
        border: none;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }
</style>

<div class="track-page">
    <div class="container">
        <div class="track-card">
            <div class="track-header">
                <h2><i class="fas fa-truck"></i> Tracking Pesanan</h2>
                <div class="order-code">
                    <?= $order->order_code ?>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="text-center mb-3">
                <?php
                $status_labels = [
                    'pending' => 'Menunggu Konfirmasi',
                    'confirmed' => 'Pembayaran Dikonfirmasi',
                    'shipped' => 'Sedang Dikirim',
                    'delivered' => 'Pesanan Diterima',
                    'rejected' => 'Ditolak'
                ];
                ?>
                <span class="status-badge status-<?= $order->status ?>">
                    <?= $status_labels[$order->status] ?>
                </span>
            </div>

            <!-- Timeline -->
            <?php if ($order->status !== 'rejected'): ?>
                <div class="timeline">
                    <?php
                    $steps = [
                        'pending' => ['icon' => 'clock', 'title' => 'Menunggu Konfirmasi', 'desc' => 'Pesanan diterima, menunggu verifikasi pembayaran'],
                        'confirmed' => ['icon' => 'check-circle', 'title' => 'Pembayaran Dikonfirmasi', 'desc' => 'Pembayaran telah diverifikasi oleh admin'],
                        'shipped' => ['icon' => 'truck', 'title' => 'Sedang Dikirim', 'desc' => 'Pesanan sedang dalam proses pengiriman'],
                        'delivered' => ['icon' => 'box-open', 'title' => 'Pesanan Diterima', 'desc' => 'Pesanan telah sampai di tujuan']
                    ];
                    $status_order = ['pending', 'confirmed', 'shipped', 'delivered'];
                    $current_idx = array_search($order->status, $status_order);

                    foreach ($steps as $key => $step):
                        $idx = array_search($key, $status_order);
                        $is_completed = $idx < $current_idx;
                        $is_active = $idx == $current_idx;
                        ?>
                        <div class="timeline-item <?= $is_completed ? 'completed' : '' ?> <?= $is_active ? 'active' : '' ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h4><i class="fas fa-<?= $step['icon'] ?>"></i>
                                    <?= $step['title'] ?>
                                </h4>
                                <p>
                                    <?= $step['desc'] ?>
                                </p>

                                <?php if ($key === 'shipped' && $order->tracking_number && in_array($order->status, ['shipped', 'delivered'])): ?>
                                    <div class="timeline-resi-box">
                                        <div class="resi-row">
                                            <span class="resi-label">No. Resi:</span>
                                            <span class="resi-value"><?= htmlspecialchars($order->tracking_number) ?></span>
                                        </div>
                                        <div class="resi-actions">
                                            <button class="btn btn-outline btn-sm"
                                                onclick="navigator.clipboard.writeText('<?= $order->tracking_number ?>'); alert('Resi disalin!')"
                                                style="border-radius: 20px; font-size: 0.7rem; padding: 4px 12px;">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                            <button class="btn-lacak-timeline" onclick="openTrackingModal()">
                                                <i class="fas fa-map-marker-alt"></i> LACAK
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($order->payment_method === 'midtrans' && $order->status === 'pending' && !empty($snap_token)): ?>
                    <div
                        style="background: rgba(255, 68, 68, 0.1); border: 1px solid rgba(255, 68, 68, 0.2); border-radius: 12px; padding: 20px; margin: 20px 0; text-align: center;">
                        <h4 style="color: #ff8888; margin-bottom: 12px;"><i class="fas fa-exclamation-triangle"></i> Pembayaran
                            Belum Selesai</h4>
                        <p style="font-size: 0.9rem; color: var(--gray-300); margin-bottom: 15px;">Selesaikan pembayaran Anda
                            menggunakan Midtrans agar pesanan dapat segera diproses.</p>

                        <button id="pay-button" class="btn btn-primary btn-lg btn-block"
                            style="background-color: #0070BA; border-color: #0070BA; width: 100%;">
                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                        </button>

                        <script
                            src="<?= $this->config->item('midtrans_is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>"
                            data-client-key="<?= $midtrans_client_key ?>"></script>
                        <script type="text/javascript">
                            var payButton = document.getElementById('pay-button');
                            payButton.addEventListener('click', function () {
                                window.snap.pay('<?= $snap_token ?>', {
                                    onSuccess: function (result) { location.reload(); },
                                    onPending: function (result) { alert("Silakan selesaikan pembayaran sesuai instruksi."); },
                                    onError: function (result) { alert("Pembayaran gagal!"); },
                                    onClose: function () { alert('Anda belum menyelesaikan pembayaran.'); }
                                });
                            });
                        </script>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-danger mt-2">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>Pesanan Ditolak</strong><br>
                        <?= $order->notes ?: 'Pembayaran tidak dapat diverifikasi.' ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Order Info Toggle -->
            <button class="details-toggle-btn" onclick="toggleOrderDetails()" id="detailsToggle">
                <span><i class="fas fa-info-circle me-2"></i> Detail Pesanan</span>
                <i class="fas fa-chevron-down"></i>
            </button>

            <!-- Order Info -->
            <div class="track-info collapsed" id="orderDetails">
                <?php foreach ($order->items as $item): ?>
                    <div class="track-info-row">
                        <span class="label">
                            <?= htmlspecialchars($item->product_name) ?>
                        </span>
                        <span class="value">
                            <?= $item->size ?> /
                            <?= $item->color ?> x
                            <?= $item->quantity ?>
                        </span>
                    </div>
                <?php endforeach; ?>

                <div class="track-info-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp
                        <?= number_format($order->subtotal, 0, ',', '.') ?>
                    </span>
                </div>

                <?php if ($order->discount > 0): ?>
                    <div class="track-info-row">
                        <span class="label">Diskon (
                            <?= $order->voucher_code ?>)
                        </span>
                        <span class="value text-green">- Rp
                            <?= number_format($order->discount, 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="track-info-row" style="border-bottom: none; font-size: 1.1rem;">
                    <span class="label fw-bold">Total</span>
                    <span class="value text-yellow fw-bold">Rp
                        <?= number_format($order->total, 0, ',', '.') ?>
                    </span>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06);">
                    <div class="track-info-row">
                        <span class="label">Nama</span>
                        <span class="value">
                            <?= htmlspecialchars($order->customer_name) ?>
                        </span>
                    </div>
                    <div class="track-info-row">
                        <span class="label">WhatsApp</span>
                        <span class="value">
                            <?= htmlspecialchars($order->customer_phone) ?>
                        </span>
                    </div>
                    <div class="track-info-row">
                        <span class="label">Alamat</span>
                        <span class="value">
                            <?= htmlspecialchars($order->customer_address) ?>
                        </span>
                    </div>
                    <div class="track-info-row">
                        <span class="label">Tanggal Pesan</span>
                        <span class="value">
                            <?= date('d M Y, H:i', strtotime($order->created_at)) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="<?= base_url() ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Custom Tracking Modal Styles -->
<style>
    .pwk-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pwk-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .pwk-modal-container {
        background: #111111;
        width: 90%;
        max-width: 500px;
        border-radius: 20px;
        border: 1px solid rgba(255, 215, 0, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 20px rgba(255, 215, 0, 0.1);
        overflow: hidden;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }

    .pwk-modal-overlay.active .pwk-modal-container {
        transform: translateY(0);
    }

    .pwk-modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pwk-modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: var(--yellow);
        font-family: var(--font-primary);
        font-weight: 700;
    }

    .pwk-modal-close {
        background: none;
        border: none;
        color: var(--gray-400);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.2s;
        padding: 5px;
        line-height: 1;
    }

    .pwk-modal-close:hover {
        color: white;
    }

    .pwk-modal-body {
        padding: 25px;
        max-height: 60vh;
        overflow-y: auto;
    }

    /* Scrollbar Styling */
    .pwk-modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .pwk-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .pwk-modal-body::-webkit-scrollbar-thumb {
        background: rgba(255, 215, 0, 0.2);
        border-radius: 10px;
    }

    .pwk-modal-footer {
        padding: 15px 25px;
        background: rgba(255, 255, 255, 0.02);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
    }

    .pwk-btn-close-modal {
        background: var(--yellow);
        color: black;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .pwk-btn-close-modal:hover {
        transform: scale(1.05);
    }
</style>

<!-- Tracking History Modal -->
<div class="pwk-modal-overlay" id="trackingModal">
    <div class="pwk-modal-container">
        <div class="pwk-modal-header">
            <h3><i class="fas fa-route me-2"></i> Detail Pengiriman</h3>
            <button class="pwk-modal-close" onclick="closeTrackingModal()">&times;</button>
        </div>
        <div class="pwk-modal-body" id="tracking-history-content">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--yellow);"></i>
                <p class="mt-2 text-muted">Memuat riwayat pengiriman...</p>
            </div>
        </div>
        <div class="pwk-modal-footer">
            <span style="color: var(--gray-500);">Kurir: <strong
                    class="text-white"><?= strtoupper($order->courier ?: '') ?></strong></span>
            <button class="pwk-btn-close-modal" onclick="closeTrackingModal()">Tutup</button>
        </div>
    </div>
</div>

<script>
    function toggleOrderDetails() {
        const info = document.getElementById('orderDetails');
        const btn = document.getElementById('detailsToggle');
        info.classList.toggle('collapsed');
        btn.classList.toggle('active');
    }

    function openTrackingModal() {
        const modal = document.getElementById('trackingModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
        loadTrackingHistory();
    }

    function closeTrackingModal() {
        const modal = document.getElementById('trackingModal');
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    // Close on overlay click
    document.getElementById('trackingModal').addEventListener('click', function (e) {
        if (e.target === this) closeTrackingModal();
    });

    function loadTrackingHistory() {
        const container = document.getElementById('tracking-history-content');

        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--yellow);"></i>
                <p class="mt-2 text-muted">Memuat riwayat pengiriman...</p>
            </div>
        `;

        fetch('<?= base_url('order/track-history/' . $order->order_code) ?>')
            .then(response => response.json())
            .then(res => {
                if (res.success && res.data.manifest && res.data.manifest.length > 0) {
                    let html = '<div class="tracking-timeline">';
                    res.data.manifest.forEach((item, index) => {
                        const isLatest = index === 0;
                        html += `
                            <div class="tracking-item ${isLatest ? 'latest' : ''}">
                                <div class="tracking-dot"></div>
                                <div class="tracking-date">${item.manifest_date} ${item.manifest_time}</div>
                                <div class="tracking-desc">${item.manifest_description}</div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-white mb-1">Belum ada rincian perjalanan.</p>
                            <small class="text-muted">${res.message || 'Silakan cek kembali secara berkala.'}</small>
                        </div>
                    `;
                }
            })
            .catch(err => {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        Gagal memuat data pelacakan. Silakan coba lagi nanti.
                    </div>
                `;
            });
    }
</script>