<!-- Order Detail -->
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i>
        Kembali</a>
</div>

<div class="detail-grid">
    <!-- Customer & Order Info -->
    <div>
        <div class="detail-section" style="margin-bottom: 20px;">
            <h4><i class="fas fa-user"></i> Data Pelanggan</h4>
            <div class="detail-row"><span class="dl">Nama</span><span class="dv">
                    <?= htmlspecialchars($order->customer_name) ?>
                </span></div>
            <div class="detail-row"><span class="dl">WhatsApp</span><span class="dv">
                    <?= htmlspecialchars($order->customer_phone) ?>
                </span></div>
            <div class="detail-row"><span class="dl">Alamat</span><span class="dv">
                    <?= htmlspecialchars($order->customer_address) ?>
                </span></div>
        </div>

        <div class="detail-section" style="margin-bottom: 20px;">
            <h4><i class="fas fa-receipt"></i> Detail Pesanan</h4>
            <div class="detail-row"><span class="dl">Kode Order</span><span class="dv"
                    style="color:var(--yellow);font-weight:800;">
                    <?= $order->order_code ?>
                </span></div>
            <div class="detail-row"><span class="dl">Status</span><span class="dv"><span
                        class="badge badge-<?= $order->status ?>">
                        <?= ucfirst($order->status) ?>
                    </span></span></div>
            <div class="detail-row"><span class="dl">Pembayaran</span><span class="dv">
                    <?php
                    $pm = $order->payment_method;
                    if ($pm === 'midtrans')
                        echo 'Midtrans';
                    elseif ($pm === 'transfer')
                        echo 'Transfer Bank';
                    elseif ($pm === 'cod')
                        echo 'COD (ELD Logistics)';
                    elseif ($pm === 'komerce')
                        echo 'Komerce Pay';
                    elseif ($pm === 'pickup')
                        echo 'Ambil di Toko';
                    else
                        echo ucfirst($pm);
                    ?>
                </span></div>
            <div class="detail-row"><span class="dl">Tanggal</span><span class="dv">
                    <?= date('d M Y, H:i', strtotime($order->created_at)) ?>
                </span></div>

            <?php foreach ($order->items as $item): ?>
                <div class="detail-row">
                    <span class="dl">
                        <?= htmlspecialchars($item->product_name) ?>
                    </span>
                    <span class="dv">
                        <?= $item->size ?>/
                        <?= $item->color ?> x
                        <?= $item->quantity ?> = Rp
                        <?= number_format($item->subtotal, 0, ',', '.') ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <div class="detail-row"><span class="dl">Subtotal</span><span class="dv">Rp
                    <?= number_format($order->subtotal, 0, ',', '.') ?>
                </span></div>
            <?php if ($order->discount > 0): ?>
                <div class="detail-row"><span class="dl">Diskon (
                        <?= $order->voucher_code ?>)
                    </span><span class="dv" style="color:var(--green);">- Rp
                        <?= number_format($order->discount, 0, ',', '.') ?>
                    </span></div>
            <?php endif; ?>
            <div class="detail-row" style="border:none; font-size:1.1rem;"><span class="dl"
                    style="font-weight:700;">Total</span><span class="dv"
                    style="color:var(--yellow);font-weight:800;">Rp
                    <?= number_format($order->total, 0, ',', '.') ?>
                </span></div>

            <?php if ($order->notes): ?>
                <div
                    style="margin-top:10px; padding:10px; background:rgba(239,68,68,0.08); border-radius:8px; font-size:0.85rem;">
                    <strong style="color:var(--red);">Catatan:</strong>
                    <?= htmlspecialchars($order->notes) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Proof & Actions -->
    <div>
        <div class="detail-section" style="margin-bottom: 20px;">
            <h4><i class="fas fa-image"></i> Bukti Pembayaran</h4>
            <?php if ($order->payment_proof): ?>
                <img src="<?= base_url('uploads/payments/' . $order->payment_proof) ?>" alt="Bukti Pembayaran"
                    class="payment-img">
            <?php else: ?>
                <p style="color:var(--gray-500);">Tidak ada bukti pembayaran.</p>
            <?php endif; ?>
        </div>

        <div class="detail-section">
            <h4><i class="fas fa-cogs"></i> Aksi</h4>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <?php if ($order->status === 'pending'): ?>
                    <a href="<?= base_url('admin/order/confirm/' . $order->id) ?>" class="btn btn-success btn-block"
                        onclick="return confirm('Konfirmasi pembayaran order ini?')">
                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                    </a>
                    <a href="<?= base_url('admin/order/reject/' . $order->id) ?>" class="btn btn-danger btn-block"
                        onclick="return confirm('Tolak order ini?')">
                        <i class="fas fa-times"></i> Tolak
                    </a>
                <?php endif; ?>

                <?php if ($order->status === 'confirmed'): ?>
                    <a href="<?= base_url('admin/order/ship/' . $order->id) ?>" class="btn btn-purple btn-block"
                        onclick="return confirm('Tandai sebagai dikirim?')">
                        <i class="fas fa-truck"></i> Tandai Dikirim
                    </a>
                <?php endif; ?>

                <?php if ($order->status === 'shipped'): ?>
                    <a href="<?= base_url('admin/order/deliver/' . $order->id) ?>" class="btn btn-success btn-block"
                        onclick="return confirm('Tandai sebagai diterima?')">
                        <i class="fas fa-box-open"></i> Tandai Diterima
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('admin/order/send-wa/' . $order->id) ?>" class="btn btn-primary btn-block"
                    target="_blank">
                    <i class="fab fa-whatsapp"></i> Kirim Status via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>