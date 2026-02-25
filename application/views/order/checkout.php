<style>
    .btn-sm {
        padding: 8px 10px;
    }

    .page-title {
        padding-top: 0px;
    }
</style>


<!-- Checkout Page -->
<div class="checkout-page">
    <div class="container">
        <div class="page-title">
            <h1>Checkout</h1>
            <p>Lengkapi data pemesanan Anda</p>
        </div>

        <style>
            .payment-methods {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 10px;
                margin-bottom: 20px;
                margin-top: 10px;
            }

            @media (max-width: 480px) {
                .payment-methods {
                    grid-template-columns: 1fr;
                }
            }

            .payment-method-card {
                border: 2px solid var(--gray-700);
                border-radius: 16px;
                padding: 16px;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                background: var(--black-mid);
                color: var(--gray-400);
            }

            .payment-method-card:hover {
                border-color: rgba(255, 215, 0, 0.3);
                background: #222;
                transform: translateY(-2px);
            }

            .payment-method-card.active {
                border-color: var(--yellow) !important;
                background: rgba(255, 215, 0, 0.05) !important;
                color: var(--white) !important;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4), 0 0 15px rgba(255, 215, 0, 0.1);
            }

            .payment-method-card input {
                position: absolute;
                opacity: 0;
            }

            .method-icon {
                font-size: 1.8rem;
                margin-bottom: 10px;
                color: var(--gray-500);
                transition: all 0.3s ease;
            }

            .payment-method-card.active .method-icon {
                color: var(--yellow);
                transform: scale(1.1);
            }

            .method-name {
                font-weight: 800;
                font-size: 0.9rem;
                display: block;
                font-family: var(--font-primary);
                letter-spacing: 0.5px;
            }

            .method-desc {
                font-size: 0.75rem;
                opacity: 0.7;
            }

            .selected-check {
                position: absolute;
                top: 10px;
                right: 10px;
                color: var(--yellow);
                display: none;
                font-size: 1rem;
            }

            .payment-method-card.active .selected-check {
                display: block;
            }

            .payment-section h3 {
                font-family: var(--font-primary);
                font-size: 1.4rem;
                font-weight: 800;
                margin-top: 40px;
                margin-bottom: 20px;
                color: var(--white);
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .payment-section h3 i {
                color: var(--yellow);
            }
        </style>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <style>
            /* Checkout Desktop Redesign - High End Aesthetics */
            .checkout-form-card {
                padding: 40px 35px !important;
            }

            .checkout-form-card .form-control {
                background-color: var(--black-mid) !important;
                border: 2px solid var(--gray-700) !important;
                color: var(--white) !important;
                border-radius: 12px !important;
                padding: 12px 18px !important;
                font-size: 0.95rem !important;
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            }

            .checkout-form-card .form-control:focus {
                border-color: var(--yellow) !important;
                box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.1) !important;
                background-color: #222 !important;
            }

            .shipping-grid-custom {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .shipping-full-row {
                grid-column: span 2;
            }

            .form-group label {
                display: flex;
                align-items: center;
                gap: 8px;
                color: var(--gray-400);
                margin-bottom: 10px;
                font-weight: 600;
                font-size: 0.85rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .form-group label i {
                color: var(--yellow);
                font-size: 0.9rem;
            }

            .origin-highlight-box {
                background: rgba(255, 215, 0, 0.05);
                border: 1px solid rgba(255, 215, 0, 0.2);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 25px;
            }

            .origin-highlight-box label {
                color: var(--yellow) !important;
                font-weight: 800 !important;
            }

            .origin-highlight-box .form-control {
                border-color: rgba(255, 215, 0, 0.4) !important;
                font-weight: 700 !important;
            }

            @media (max-width: 768px) {
                .shipping-grid-custom {
                    grid-template-columns: 1fr;
                }

                .shipping-full-row {
                    grid-column: span 1;
                }
            }

            /* Section Divider Styling */
            .section-header-modern {
                border-bottom: 2px solid rgba(255, 255, 255, 0.05);
                padding-bottom: 15px;
                margin-bottom: 30px;
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .section-header-modern i {
                background: var(--yellow);
                color: var(--black);
                width: 38px;
                height: 38px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                transform: rotate(-10deg);
                box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
            }

            .section-header-modern h3 {
                margin: 0 !important;
                font-family: var(--font-primary);
                font-size: 1.4rem;
                font-weight: 800;
                color: var(--white);
            }

            .checkout-form-card select option {
                background-color: var(--black-mid);
                color: var(--white);
            }

            /* Target the placeholder option */
            .checkout-form-card select option[value=""],
            .checkout-form-card select option:first-child {
                color: var(--gray-600);
            }
        </style>

        <form action="<?= base_url('order/store') ?>" method="post" enctype="multipart/form-data" id="checkoutForm">
            <div class="checkout-grid">
                <!-- Left: Form -->
                <div class="checkout-form-card">
                    <div class="section-header-modern">
                        <i class="fas fa-user"></i>
                        <h3>Data Pemesan</h3>
                    </div>

                    <div class="shipping-grid-custom">
                        <div class="form-group shipping-full-row">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control"
                                placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group shipping-full-row">
                            <label><i class="fab fa-whatsapp"></i> Nomor WhatsApp</label>
                            <input type="text" name="customer_phone" class="form-control"
                                placeholder="contoh: 08123456789" required>
                        </div>

                        <div class="form-group shipping-full-row">
                            <label><i class="fas fa-map-marker-alt"></i> Alamat Lengkap</label>
                            <textarea name="customer_address" class="form-control"
                                placeholder="Nama jalan, RT/RW, kelurahan, kecamatan, kode pos" required></textarea>
                        </div>
                    </div>

                    <div class="section-header-modern" style="margin-top: 40px;">
                        <i class="fas fa-truck"></i>
                        <h3>Informasi Pengiriman</h3>
                    </div>

                    <div id="shippingInfoSection">
                        <div class="origin-highlight-box">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-store"></i> Kirim Dari (Lokasi Toko)</label>
                                <select name="origin_id" id="originSelect" class="form-control"
                                    onchange="calculateShipping()">
                                    <?php foreach ($stores as $s): ?>
                                        <option value="<?= $s->id ?>" <?= $s->is_default ? 'selected' : '' ?>><?= $s->name ?>
                                            -
                                            <?= $s->address ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="shipping-grid-custom">
                            <div class="form-group">
                                <label><i class="fas fa-map-marked-alt"></i> Provinsi</label>
                                <select name="province_id" id="provinceSelect" class="form-control" required
                                    onchange="loadCities(this.value)">
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach ($provinces as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="province" id="provinceName">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-city"></i> Kota/Kabupaten</label>
                                <select name="city_id" id="citySelect" class="form-control" required
                                    onchange="loadDistricts(this.value)">
                                    <option value="">Pilih Kota</option>
                                </select>
                                <input type="hidden" name="city" id="cityName">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-map-pins"></i> Kecamatan</label>
                                <select name="district_id" id="districtSelect" class="form-control" required
                                    onchange="loadSubdistricts(this.value)">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="district" id="districtName">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-home"></i> Desa/Kelurahan</label>
                                <select name="subdistrict_id" id="subdistrictSelect" class="form-control" required
                                    onchange="updateSubdistrictName(this)">
                                    <option value="">Pilih Desa</option>
                                </select>
                                <input type="hidden" name="subdistrict" id="subdistrictName">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-truck-loading"></i> Kurir</label>
                                <select name="courier" id="courierSelect" class="form-control" required
                                    onchange="calculateShipping()">
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="jnt">J&T</option>
                                    <option value="anteraja">Anteraja</option>
                                    <option value="lion">Lion Parcel</option>
                                    <option value="ninja">Ninja Xpress</option>
                                    <option value="wahana">Wahana</option>
                                    <option value="idexpress">ID Express</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-concierge-bell"></i> Layanan</label>
                                <select name="service" id="serviceSelect" class="form-control" required
                                    onchange="updateTotalWithShipping()">
                                    <option value="">Pilih Layanan</option>
                                </select>
                                <input type="hidden" name="service_name" id="serviceName">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="payment-section">
                        <h3><i class="fas fa-credit-card"></i> Metode Pembayaran</h3>
                        <div class="payment-methods">

                            <label class="payment-method-card active">
                                <input type="radio" name="payment_method" value="midtrans" checked
                                    onclick="togglePaymentMethod(this, 'midtrans')">
                                <div class="method-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="method-info">
                                    <span class="method-name">Bayar Instan</span>
                                    <span class="method-desc">QRIS/VA/Gopay</span>
                                </div>
                                <div class="selected-check"><i class="fas fa-check-circle"></i></div>
                            </label>

                            <label class="payment-method-card">
                                <input type="radio" name="payment_method" value="transfer"
                                    onclick="togglePaymentMethod(this, 'transfer')">
                                <div class="method-icon"><i class="fas fa-university"></i></div>
                                <div class="method-info">
                                    <span class="method-name">Transfer Manual</span>
                                    <span class="method-desc">Admin Cek Manual</span>
                                </div>
                                <div class="selected-check"><i class="fas fa-check-circle"></i></div>
                            </label>

                            <label class="payment-method-card">
                                <input type="radio" name="payment_method" value="cod"
                                    onclick="togglePaymentMethod(this, 'cod')">
                                <div class="method-icon"><i class="fas fa-truck-pickup"></i></div>
                                <div class="method-info">
                                    <span class="method-name">COD (Kurir Lokal)</span>
                                    <span class="method-desc">Bayar di Tempat</span>
                                </div>
                                <div class="selected-check"><i class="fas fa-check-circle"></i></div>
                            </label>

                            <label class="payment-method-card">
                                <input type="radio" name="payment_method" value="pickup"
                                    onclick="togglePaymentMethod(this, 'pickup')">
                                <div class="method-icon"><i class="fas fa-store-alt"></i></div>
                                <div class="method-info">
                                    <span class="method-name">Ambil ke Toko</span>
                                    <span class="method-desc">Ambil di Lokasi</span>
                                </div>
                                <div class="selected-check"><i class="fas fa-check-circle"></i></div>
                            </label>

                            <label class="payment-method-card" style="display: none;">
                                <input type="radio" name="payment_method" value="komerce"
                                    onclick="togglePaymentMethod(this, 'komerce')">
                                <div class="method-icon"><i class="fas fa-qrcode"></i></div>
                                <div class="method-info">
                                    <span class="method-name">Komerce Pay</span>
                                    <span class="method-desc">QRIS/VA Otomatis</span>
                                </div>
                                <div class="selected-check"><i class="fas fa-check-circle"></i></div>
                            </label>


                        </div>

                        <!-- Bank Transfer Info -->
                        <div id="transferInfo" class="payment-info-box" style="display: none;">
                            <h4><i class="fas fa-university"></i> Rekening Pembayaran</h4>
                            <div class="bank-info">
                                <div class="bank-row">
                                    <span class="bank-label">Bank</span>
                                    <span class="bank-value">BCA</span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">No. Rekening</span>
                                    <span class="bank-value">1234567890</span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Atas Nama</span>
                                    <span class="bank-value"><?= get_setting('site_name', 'Peweka') ?> Official</span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Jumlah</span>
                                    <span class="bank-value text-yellow" id="transferAmount">Rp.
                                        <?= number_format($subtotal, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- COD Lokal Note -->
                        <div id="codNote" class="payment-info-box"
                            style="display: none; background: rgba(255,193,7,0.1); border-color: #ffc107;">
                            <h4 style="color: #ffc107;"><i class="fas fa-truck-pickup"></i> Info COD Kurir Lokal</h4>
                            <p style="font-size: 0.85rem; margin-top: 10px;">
                                Pesanan akan dikirim menggunakan kurir lokal kami. Biaya ongkir akan dihitung manual
                                oleh
                                Admin. Silakan lakukan pembayaran tunai saat barang sampai.
                            </p>
                        </div>

                        <!-- Pickup Note -->
                        <div id="pickupNote" class="payment-info-box"
                            style="display: none; background: rgba(40,167,69,0.1); border-color: #28a745;">
                            <h4 style="color: #28a745;"><i class="fas fa-store-alt"></i> Info Ambil ke Toko</h4>
                            <p style="font-size: 0.85rem; margin-top: 10px;">
                                Silakan ambil pesanan Anda langsung di lokasi toko yang dipilih setelah status menjadi
                                "Siap Diambil".
                            </p>
                        </div>

                        <!-- Upload Proof Section -->
                        <div id="proofUploadSection" class="form-group" style="margin-top: 20px; display: none;">
                            <label><i class="fas fa-camera"></i> Upload Bukti Transfer</label>
                            <div class="upload-area" onclick="document.getElementById('paymentFile').click()">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <p>Klik untuk upload bukti transfer</p>
                                <div class="file-name" id="fileName"></div>
                                <div id="previewContainer"></div>
                            </div>
                            <input type="file" name="payment_proof" id="paymentFile" accept="image/*"
                                style="display:none" onchange="previewUpload(this)">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitOrder"
                        style="margin-top: 30px;">
                        <i class="fas fa-paper-plane"></i> Kirim Pesanan
                    </button>
                </div>

                <!-- Right: Order Summary -->
                <div class="order-summary-card">
                    <h3><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>

                    <?php foreach ($items as $item): ?>
                        <div class="summary-item mb-3 p-2 rounded" style="background: rgba(255,255,255,0.05);">
                            <img src="<?= base_url('assets/img/products/' . $item->product_image) ?>"
                                alt="<?= htmlspecialchars($item->product_name) ?>"
                                onerror="this.src='<?= base_url('assets/img/products/default.svg') ?>'"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div class="summary-item-info ms-3">
                                <h4 style="font-size: 0.95rem; margin-bottom: 4px;">
                                    <?= htmlspecialchars($item->product_name) ?>
                                </h4>
                                <p style="font-size: 0.8rem; margin-bottom: 2px; color: var(--gray-400);">
                                    <?= $item->size ?> | <?= $item->color ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <span style="font-size: 0.85rem; color: var(--yellow);"><?= $item->quantity ?> x Rp.
                                        <?= number_format($item->product_price, 0, ',', '.') ?></span>
                                    <span style="font-weight: 600; font-size: 0.9rem;">Rp.
                                        <?= number_format($item->product_price * $item->quantity, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp. <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>

                    <!-- Voucher Section -->
                    <div class="voucher-section">
                        <label style="font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; display: block;">
                            <i class="fas fa-ticket-alt"></i> Kode Voucher
                        </label>
                        <div class="voucher-input-group">
                            <input type="text" name="voucher_code" id="voucherInput" placeholder="Masukkan kode">
                            <button type="button" class="btn btn-outline btn-sm"
                                onclick="applyVoucher()">Terapkan</button>
                        </div>
                        <div class="voucher-msg" id="voucherMsg"></div>
                    </div>

                    <div class="summary-row" id="discountRow" style="display: none;">
                        <span>Diskon</span>
                        <span class="discount" id="discountAmount">- Rp. 0</span>
                    </div>

                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span id="shippingCost">Dihitung otomatis</span>
                    </div>

                    <div class="summary-total">
                        <span>Total Bayar</span>
                        <span id="totalBayar">Rp. <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
        </form>
    </div>
</div>

<script>
    var baseSubtotal = <?= $subtotal ?>;
    var currentDiscount = 0;
    var currentShipping = 0;

    function loadCities(provinceId) {
        if (!provinceId) return;
        var select = document.getElementById('provinceSelect');
        document.getElementById('provinceName').value = select.options[select.selectedIndex].text;

        fetch('<?= base_url('order/get_cities/') ?>' + provinceId)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">Pilih Kota</option>';
                if (data.data) {
                    data.data.forEach(city => {
                        html += `<option value="${city.id}">${city.name}</option>`;
                    });
                }
                document.getElementById('citySelect').innerHTML = html;
                document.getElementById('districtSelect').innerHTML = '<option value="">Pilih Kecamatan</option>';
                document.getElementById('subdistrictSelect').innerHTML = '<option value="">Pilih Desa</option>';
            });
    }

    function loadDistricts(cityId) {
        if (!cityId) return;
        var select = document.getElementById('citySelect');
        document.getElementById('cityName').value = select.options[select.selectedIndex].text;

        fetch('<?= base_url('order/get_districts/') ?>' + cityId)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">Pilih Kecamatan</option>';
                if (data.data) {
                    data.data.forEach(d => {
                        html += `<option value="${d.id}">${d.name}</option>`;
                    });
                }
                document.getElementById('districtSelect').innerHTML = html;
                document.getElementById('subdistrictSelect').innerHTML = '<option value="">Pilih Desa</option>';
            });
    }

    function loadSubdistricts(districtId) {
        if (!districtId) return;
        var select = document.getElementById('districtSelect');
        document.getElementById('districtName').value = select.options[select.selectedIndex].text;

        fetch('<?= base_url('order/get_subdistricts/') ?>' + districtId)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">Pilih Desa</option>';
                if (data.data) {
                    data.data.forEach(s => {
                        html += `<option value="${s.id}">${s.name}</option>`;
                    });
                }
                document.getElementById('subdistrictSelect').innerHTML = html;
            });
    }

    function updateSubdistrictName(el) {
        document.getElementById('subdistrictName').value = el.options[el.selectedIndex].text;
        calculateShipping();
    }

    function calculateShipping() {
        const subdistrictId = document.getElementById('subdistrictSelect').value;
        const courier = document.getElementById('courierSelect').value;
        const originId = document.getElementById('originSelect').value;

        if (!subdistrictId || !courier) return;

        const shippingCostEl = document.getElementById('shippingCost');
        const serviceSelect = document.getElementById('serviceSelect');

        shippingCostEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        serviceSelect.innerHTML = '<option value="">Memuat layanan...</option>';

        const formData = new FormData();
        formData.append('subdistrict_id', subdistrictId);
        formData.append('courier', courier);
        formData.append('origin_id', originId);

        console.log("--- START SHIPPING V3 ---");
        fetch('<?= base_url('order/get_shipping_cost') ?>', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(text => {
                console.log("Raw Response:", text);
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error("JSON Error:", e);
                    shippingCostEl.innerHTML = '<span class="text-danger small">Error JSON</span>';
                    return;
                }

                console.log("Response Object:", data);

                let html = '<option value="">Pilih Layanan</option>';
                let services = [];

                // ATTEMPT 1: rajaongkir.results[0].costs (Standard RajaOngkir)
                if (data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results[0]) {
                    services = data.rajaongkir.results[0].costs || [];
                    console.log("Found in: rajaongkir.results[0].costs");
                }
                // ATTEMPT 2: data.data (Can be Array or Object with .costs)
                else if (data.data) {
                    if (Array.isArray(data.data)) {
                        if (data.data.length > 0 && data.data[0].costs) {
                            services = data.data[0].costs;
                            console.log("Found in: data.data[0].costs");
                        } else {
                            services = data.data;
                            console.log("Found in: data.data (flat array)");
                        }
                    } else if (typeof data.data === 'object' && data.data.costs) {
                        services = data.data.costs;
                        console.log("Found in: data.data.costs (object)");
                    } else if (typeof data.data === 'object') {
                        // Maybe it's directly the array but indexed? Unlikely but safe to check
                        services = Object.values(data.data).filter(item => typeof item === 'object');
                        console.log("Found in: data.data (object values)");
                    }
                }
                // ATTEMPT 3: costs (Directly at root)
                else if (Array.isArray(data.costs)) {
                    services = data.costs;
                    console.log("Found in: costs (root)");
                }
                // ATTEMPT 4: results[0].costs
                else if (data.results && data.results[0]) {
                    services = data.results[0].costs || [];
                    console.log("Found in: results[0].costs");
                }

                console.log("Final Services Array:", services);

                if (services && services.length > 0) {
                    services.forEach((s, idx) => {
                        let price = 0;
                        let etd = '';

                        console.log(`Service ${idx}:`, s);

                        // Price extraction (Aggressive fallbacks)
                        if (typeof s.cost === 'number') {
                            price = s.cost;
                            etd = s.etd || '';
                        } else if (s.cost) {
                            if (Array.isArray(s.cost)) {
                                price = s.cost[0] ? (s.cost[0].value || s.cost[0].price || s.cost[0].cost || 0) : 0;
                                etd = s.cost[0] ? (s.cost[0].etd || '') : '';
                            } else {
                                price = s.cost.value || s.cost.price || s.cost.cost || 0;
                                etd = s.cost.etd || '';
                            }
                        } else {
                            price = s.value || s.price || s.cost_value || s.shipping_cost || 0;
                            etd = s.etd || '';
                        }

                        const name = s.service || s.service_name || s.name || 'Layanan ' + (idx + 1);
                        const desc = s.description ? ` (${s.description})` : '';
                        const etdText = etd ? ` [${etd}]` : '';

                        html += `<option value="${price}" data-name="${name}">${name}${desc} - Rp. ${numberFormat(price)}${etdText}</option>`;
                    });
                    shippingCostEl.innerHTML = '<span class="text-success small">Layanan ditemukan</span>';
                } else {
                    let msg = data.message || (data.meta ? data.meta.message : 'Tidak ada layanan tersedia');
                    shippingCostEl.innerHTML = `<span class="text-danger small">${msg}</span>`;
                }

                serviceSelect.innerHTML = html;
                console.log("--- END SHIPPING V3 ---");
            })
            .catch(error => {
                console.error("Fatal Error:", error);
                shippingCostEl.innerHTML = '<span class="text-danger small">System Error</span>';
            });
    }

    function updateTotalWithShipping(forceShipping = null) {
        var serviceSelect = document.getElementById('serviceSelect');
        var shipping = 0;

        if (forceShipping !== null) {
            shipping = forceShipping;
            document.getElementById('shippingCost').innerText = (shipping === 0 && document.querySelector('input[name="payment_method"]:checked').value === 'cod') ? 'Dihitung Admin' : 'Rp. ' + numberFormat(shipping);
            document.getElementById('shippingCostInput').value = shipping;
        } else if (serviceSelect.value) {
            shipping = parseInt(serviceSelect.value);
            document.getElementById('serviceName').value = serviceSelect.options[serviceSelect.selectedIndex].getAttribute('data-name');
            document.getElementById('shippingCost').innerText = 'Rp. ' + numberFormat(shipping);
            document.getElementById('shippingCostInput').value = shipping;
        }

        currentShipping = shipping;
        var total = baseSubtotal - currentDiscount + currentShipping;
        document.getElementById('totalBayar').innerText = 'Rp. ' + numberFormat(total);
        document.getElementById('transferAmount').innerText = 'Rp. ' + numberFormat(total);
    }

    function applyVoucher() {
        const code = document.getElementById('voucherInput').value;
        if (!code) return;

        const formData = new FormData();
        formData.append('code', code);
        formData.append('subtotal', baseSubtotal);

        fetch('<?= base_url('order/apply_voucher') ?>', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const msgEl = document.getElementById('voucherMsg');
                if (data.valid) {
                    currentDiscount = data.discount;
                    document.getElementById('discountRow').style.display = 'flex';
                    document.getElementById('discountAmount').innerText = '- Rp. ' + numberFormat(currentDiscount);
                    msgEl.innerText = 'Voucher berhasil digunakan!';
                    msgEl.style.color = 'var(--green)';
                    updateTotalWithShipping();
                } else {
                    msgEl.innerText = data.message;
                    msgEl.style.color = 'red';
                }
            });
    }

    function togglePaymentMethod(el, method) {
        document.querySelectorAll('.payment-method-card').forEach(card => card.classList.remove('active'));
        el.closest('.payment-method-card').classList.add('active');

        var transferInfo = document.getElementById('transferInfo');
        var codNote = document.getElementById('codNote');
        var pickupNote = document.getElementById('pickupNote');
        var proofSection = document.getElementById('proofUploadSection');
        var proofInput = document.getElementById('paymentFile');
        var shippingSection = document.getElementById('shippingInfoSection');
        var courierSelect = document.getElementById('courierSelect');
        var serviceSelect = document.getElementById('serviceSelect');

        // Reset display
        transferInfo.style.display = 'none';
        codNote.style.display = 'none';
        pickupNote.style.display = 'none';
        proofSection.style.display = 'none';
        proofInput.removeAttribute('required');

        if (shippingSection) shippingSection.style.display = 'block';
        courierSelect.removeAttribute('disabled');
        serviceSelect.removeAttribute('disabled');
        courierSelect.setAttribute('required', 'required');
        serviceSelect.setAttribute('required', 'required');

        // Reset if previously from COD (which had fixed service)
        if (serviceSelect.innerHTML.includes('COD (Bayar di Tempat)')) {
            courierSelect.value = '';
            serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            document.getElementById('shippingCost').innerText = 'Dihitung otomatis';
        }

        if (method === 'transfer') {
            transferInfo.style.display = 'block';
            proofSection.style.display = 'block';
            proofInput.setAttribute('required', 'required');
        } else if (method === 'cod') {
            codNote.style.display = 'block';
            courierSelect.value = '';
            serviceSelect.innerHTML = '<option value="0" data-name="COD (Bayar di Tempat)">Kurir Lokal (Dihitung Admin)</option>';
            courierSelect.removeAttribute('required');
            serviceSelect.removeAttribute('required');
            courierSelect.setAttribute('disabled', 'disabled');
            serviceSelect.setAttribute('disabled', 'disabled');
            updateTotalWithShipping(0);
        } else if (method === 'pickup') {
            pickupNote.style.display = 'block';
            if (shippingSection) shippingSection.style.display = 'none';
            courierSelect.removeAttribute('required');
            serviceSelect.removeAttribute('required');
            updateTotalWithShipping(0);
        } else if (method === 'midtrans') {
            // Nothing special, standard shipping
        }
    }

    function previewUpload(input) {
        const file = input.files[0];
        if (file) {
            document.getElementById('fileName').innerText = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewContainer').innerHTML = `<img src="${e.target.result}" style="max-width: 100%; border-radius: 8px; margin-top: 10px;">`;
            }
            reader.readAsDataURL(file);
        }
    }

    function numberFormat(num) {
        return parseInt(num).toLocaleString('id-ID');
    }

    // Handle form submission via AJAX to clear local storage on success
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        // If it's transfer or online pay, we might want standard POST or stay on page for Snap
        // But for simplicity and ensuring localStorage clearing, we'll use a mix or just clear on success page.
        // Let's add a hook to the Success page to clear cart.
    });

    // Initial sync
    document.addEventListener('DOMContentLoaded', function () {
        const activeMethod = document.querySelector('input[name="payment_method"]:checked');
        if (activeMethod) {
            togglePaymentMethod(activeMethod, activeMethod.value);
        }
    });
</script>