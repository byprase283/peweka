<!-- Order Success Page -->
<div class="success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Pesanan Berhasil!</h2>
            <p>Terima kasih, <?= htmlspecialchars($order->customer_name) ?>.
                <?php if ($order->payment_method === 'komerce'): ?>
                    Pesananmu sudah kami terima. Silakan selesaikan pembayaran online di bawah ini menggunakan Komerce Pay.
                <?php elseif ($order->payment_method === 'midtrans'): ?>
                    Pesananmu sudah kami terima. Silakan selesaikan pembayaran online di bawah ini menggunakan Midtrans.
                <?php elseif ($order->payment_method === 'cod'): ?>
                    Pesananmu sedang diproses menggunakan **ELD Logistics**. Admin akan segera menghubungi untuk rincian
                    ongkos kirim.
                <?php elseif ($order->payment_method === 'pickup'): ?>
                    Pesananmu sudah masuk! Silakan ambil di toko setelah status berubah menjadi **Siap Diambil**.
                <?php else: ?>
                    Pesananmu sudah kami terima dan sedang menunggu konfirmasi pembayaran.
                <?php endif; ?>
            </p>

            <div class="order-code">
                <?= $order->order_code ?>
            </div>

            <p style="font-size: 0.9rem;">
                Simpan kode pesanan di atas untuk tracking status pesananmu.
            </p>

            <div
                style="background: rgba(255,215,0,0.08); border-radius: 12px; padding: 20px; margin-bottom: 25px; text-align: left;">
                <h4 style="color: var(--yellow); margin-bottom: 10px; font-family: var(--font-primary);">
                    <i class="fas fa-receipt"></i> Detail Pesanan
                </h4>
                <?php foreach ($order->items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>
                            <?= htmlspecialchars($item->product_name) ?> (<?= $item->size ?>/<?= $item->color ?>) x
                            <?= $item->quantity ?>
                        </span>
                        <span class="fw-bold">Rp. <?= number_format($item->subtotal, 0, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>

                <?php if ($order->discount > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px; color: var(--green);">
                        <span>Diskon (<?= $order->voucher_code ?>)</span>
                        <span>- Rp. <?= number_format($order->discount, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div
                    style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); color: var(--yellow); font-weight: 800; font-size: 1.1rem;">
                    <span>Total</span>
                    <span>
                        <?php if ($order->payment_method === 'cod' && $order->shipping_cost == 0): ?>
                            Rp. <?= number_format($order->total, 0, ',', '.') ?> <small
                                style="font-size: 0.7rem; color: #fff;">+ Ongkir Admin</small>
                        <?php else: ?>
                            Rp. <?= number_format($order->total, 0, ',', '.') ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div class="success-actions">
                <?php if ($order->payment_method === 'komerce' && !empty($snap_token)): ?>
                    <a href="<?= $snap_token ?>" class="btn btn-primary btn-lg btn-block"
                        style="background-color: #ffcc00; border-color: #ffcc00; color: #000; margin-bottom: 15px;">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang (Komerce Pay)
                    </a>
                <?php elseif ($order->payment_method === 'midtrans' && !empty($snap_token)): ?>
                    <button id="pay-button" class="btn btn-primary btn-lg btn-block"
                        style="background-color: #0070BA; border-color: #0070BA; margin-bottom: 15px;">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang (Midtrans)
                    </button>

                    <script
                        src="<?= $this->config->item('midtrans_is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>"
                        data-client-key="<?= $midtrans_client_key ?>"></script>
                    <script type="text/javascript">
                        var payButton = document.getElementById('pay-button');
                        payButton.addEventListener('click', function () {
                            window.snap.pay('<?= $snap_token ?>', {
                                onSuccess: function (result) { window.location.href = '<?= base_url('order/track/' . $order->order_code) ?>'; },
                                onPending: function (result) { window.location.href = '<?= base_url('order/track/' . $order->order_code) ?>'; },
                                onError: function (result) { alert("Pembayaran gagal!"); },
                                onClose: function () { alert('Kamu belum menyelesaikan pembayaran.'); }
                            });
                        });
                    </script>
                <?php elseif ($order->payment_method === 'transfer'): ?>
                    <?php
                    $wa_message = "Halo Admin " . get_setting('site_name', 'Peweka') . ", saya sudah melakukan pembayaran untuk order: " . $order->order_code;
                    $wa_url = "https://wa.me/6281234567890?text=" . urlencode($wa_message);
                    ?>
                    <a href="<?= $wa_url ?>" class="btn btn-success" target="_blank"
                        style="background-color: #25D366; border-color: #25D366; color: white;">
                        <i class="fab fa-whatsapp"></i> Konfirmasi via WA
                    </a>
                <?php endif; ?>

                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 5px;">
                    <a href="<?= base_url('order/track/' . $order->order_code) ?>" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cek Status
                    </a>
                    <a href="<?= base_url() ?>" class="btn btn-outline">
                        <i class="fas fa-shopping-bag"></i> Belanja Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Clear cart from local storage on successful order
    localStorage.removeItem('peweka_cart');
    if (typeof updateCartBadge === 'function') {
        updateCartBadge();
    }
</script>