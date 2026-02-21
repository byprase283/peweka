<div class="form-card">
    <div class="card-header mb-4">
        <h3>Edit Detail Toko / Lokasi Pengiriman</h3>
        <p class="text-muted">Data lokasi di bawah digunakan sebagai titik asal (Origin) untuk kalkulasi ongkir
            RajaOngkir.</p>
    </div>

    <form action="<?= base_url('admin/store/update/' . $store->id) ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3" style="color: var(--yellow);">Informasi Dasar</h4>
                <div class="form-group">
                    <label>Nama Toko</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($store->name) ?>"
                        required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($store->phone) ?>">
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="address" class="form-control"
                        rows="4"><?= htmlspecialchars($store->address) ?></textarea>
                </div>
                <div class="form-group mt-4">
                    <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                        <input type="checkbox" name="is_default" value="1" <?= $store->is_default ? 'checked' : '' ?>
                        style="width: 18px; height: 18px;">
                        <span>Jadikan Toko Utama (Default Origin)</span>
                    </label>
                </div>
            </div>

            <div class="col-md-6 border-left pl-md-4">
                <h4 class="mb-3" style="color: var(--yellow);">Lokasi RajaOngkir</h4>

                <!-- Hidden inputs for names -->
                <input type="hidden" name="province_name" id="province_name" value="<?= $store->province_name ?>">
                <input type="hidden" name="city_name" id="city_name" value="<?= $store->city_name ?>">
                <input type="hidden" name="district_name" id="district_name" value="<?= $store->district_name ?>">
                <input type="hidden" name="subdistrict_name" id="subdistrict_name"
                    value="<?= $store->subdistrict_name ?>">

                <div class="form-group">
                    <label>Provinsi</label>
                    <select name="province_id" id="province_id" class="form-control" required>
                        <option value="">-- Pilih Provinsi --</option>
                        <?php foreach ($provinces as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $p['id'] == $store->province_id ? 'selected' : '' ?>>
                                <?= $p['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kota / Kabupaten</label>
                    <select name="city_id" id="city_id" class="form-control" required <?= !$store->city_id ? 'disabled' : '' ?>>
                        <option value="">-- Pilih Kota --</option>
                        <?php if ($store->city_id): ?>
                            <option value="<?= $store->city_id ?>" selected>
                                <?= $store->city_name ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <select name="district_id" id="district_id" class="form-control" required <?= !$store->district_id ? 'disabled' : '' ?>>
                        <option value="">-- Pilih Kecamatan --</option>
                        <?php if ($store->district_id): ?>
                            <option value="<?= $store->district_id ?>" selected>
                                <?= $store->district_name ?>
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelurahan / Desa (Subdistrict)</label>
                    <select name="subdistrict_id" id="subdistrict_id" class="form-control" required
                        <?= !$store->subdistrict_id ? 'disabled' : '' ?>>
                        <option value="">-- Pilih Desa --</option>
                        <?php if ($store->subdistrict_id): ?>
                            <option value="<?= $store->subdistrict_id ?>" selected>
                                <?= $store->subdistrict_name ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle"></i> Sangat disarankan mengisi
                        sampai tingkat Desa untuk akurasi ongkir.</small>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-top">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="<?= base_url('admin/stores') ?>" class="btn btn-outline ml-2">Batal</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const baseUrl = '<?= base_url() ?>';

        // When Province Changes
        $('#province_id').on('change', function () {
            const provinceId = $(this).val();
            const provinceName = $("#province_id option:selected").text().trim();
            $('#province_name').val(provinceName);

            $('#city_id').empty().append('<option value="">Loading...</option>').prop('disabled', true);
            $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
            $('#subdistrict_id').empty().append('<option value="">-- Pilih Desa --</option>').prop('disabled', true);

            if (provinceId) {
                $.get(baseUrl + 'order/get_cities/' + provinceId, function (res) {
                    $('#city_id').empty().append('<option value="">-- Pilih Kota --</option>').prop('disabled', false);
                    if (res.data) {
                        res.data.forEach(function (city) {
                            $('#city_id').append(`<option value="${city.id}">${city.name}</option>`);
                        });
                    }
                });
            }
        });

        // When City Changes
        $('#city_id').on('change', function () {
            const cityId = $(this).val();
            const cityName = $("#city_id option:selected").text().trim();
            $('#city_name').val(cityName);

            $('#district_id').empty().append('<option value="">Loading...</option>').prop('disabled', true);
            $('#subdistrict_id').empty().append('<option value="">-- Pilih Desa --</option>').prop('disabled', true);

            if (cityId) {
                $.get(baseUrl + 'order/get_districts/' + cityId, function (res) {
                    $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', false);
                    if (res.data) {
                        res.data.forEach(function (district) {
                            $('#district_id').append(`<option value="${district.id}">${district.name}</option>`);
                        });
                    }
                });
            }
        });

        // When District Changes
        $('#district_id').on('change', function () {
            const districtId = $(this).val();
            const districtName = $("#district_id option:selected").text().trim();
            $('#district_name').val(districtName);

            $('#subdistrict_id').empty().append('<option value="">Loading...</option>').prop('disabled', true);

            if (districtId) {
                $.get(baseUrl + 'order/get_subdistricts/' + districtId, function (res) {
                    $('#subdistrict_id').empty().append('<option value="">-- Pilih Desa --</option>').prop('disabled', false);
                    if (res.data) {
                        res.data.forEach(function (sub) {
                            $('#subdistrict_id').append(`<option value="${sub.id}">${sub.name}</option>`);
                        });
                    }
                });
            }
        });

        // When Subdistrict Changes
        $('#subdistrict_id').on('change', function () {
            const subName = $("#subdistrict_id option:selected").text().trim();
            $('#subdistrict_name').val(subName);
        });
    });
</script>

<style>
    .border-left {
        border-left: 1px solid rgba(255, 255, 255, 0.1);
    }

    @media (max-width: 768px) {
        .border-left {
            border-left: none;
            padding-left: 0 !important;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    }

    .ml-2 {
        margin-left: 0.5rem;
    }
</style>