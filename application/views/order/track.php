<!-- Order Track Page -->
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger mt-2">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>Pesanan Ditolak</strong><br>
                        <?= $order->notes ?: 'Pembayaran tidak dapat diverifikasi.' ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Order Info -->
            <div class="track-info">
                <h4 style="font-family: var(--font-primary); margin-bottom: 15px; color: var(--yellow);">
                    <i class="fas fa-info-circle"></i> Detail Pesanan
                </h4>

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