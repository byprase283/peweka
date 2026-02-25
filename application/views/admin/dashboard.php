<!-- Dashboard Professional Enhancements -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        <div class="stat-value" style="color: var(--yellow); font-size: 1.4rem;">Rp
            <?= number_format($revenue, 0, ',', '.') ?></div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<div class="row" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <!-- Revenue Chart -->
    <div style="flex: 2; min-width: 300px;">
        <div class="table-card" style="padding: 20px; height: 100%;">
            <div class="card-header"
                style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                <h3 style="font-size: 1.1rem;"><i class="fas fa-chart-line"
                        style="color: var(--yellow); margin-right: 10px;"></i> Pendapatan (7 Hari Terakhir)</h3>
            </div>
            <canvas id="revenueChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div style="flex: 1; min-width: 300px;">
        <div class="table-card" style="padding: 20px; height: 100%;">
            <div class="card-header"
                style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                <h3 style="font-size: 1.1rem; color: var(--red);"><i class="fas fa-exclamation-triangle"
                        style="margin-right: 10px;"></i> Stok Menipis</h3>
            </div>
            <div style="max-height: 280px; overflow-y: auto;">
                <?php if (!empty($low_stock)): ?>
                    <?php foreach ($low_stock as $ls): ?>
                        <div
                            style="display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <img src="<?= base_url('assets/img/products/' . ($ls->image ?: 'default.jpg')) ?>"
                                style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($ls->product_name) ?>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--gray-500);"><?= $ls->size ?> / <?= $ls->color ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span class="badge"
                                    style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);"><?= $ls->stock ?></span>
                                <div style="font-size: 0.65rem; color: var(--gray-600); margin-top: 4px;">Tersisa</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px 20px; color: var(--gray-600);">
                        <i class="fas fa-check-circle fa-2x mb-3" style="color: var(--green); opacity: 0.3;"></i>
                        <p style="font-size: 0.85rem;">Semua stok aman.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div style="margin-top: 15px; text-align: center;">
                <a href="<?= base_url('admin/products') ?>" class="btn btn-outline btn-sm"
                    style="width: 100%; font-size: 0.75rem;">Update Stok</a>
            </div>
        </div>
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
                        <td><a href="<?= base_url('admin/order/' . $order->id) ?>" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px; color:var(--gray-500);">Belum ada pesanan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');

        <?php
        $labels = [];
        $data_points = [];

        // Fill dates for last 7 days to ensure we have gaps filled with 0
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('d M', strtotime($date));

            $val = 0;
            foreach ($revenue_stats as $stat) {
                if ($stat->date == $date) {
                    $val = (float) $stat->revenue;
                    break;
                }
            }
            $data_points[] = $val;
        }
        ?>

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Pendapatan',
                    data: <?= json_encode($data_points) ?>,
                    borderColor: '#FFD700',
                    backgroundColor: 'rgba(255, 215, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#FFD700',
                    pointBorderColor: '#1a1a1a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: {
                            color: '#888',
                            callback: function (value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'k';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#888' }
                    }
                }
            }
        });
    });
</script>