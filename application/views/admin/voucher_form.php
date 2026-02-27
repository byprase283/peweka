<!-- Voucher Form (Create/Edit) -->
<?php
$is_edit = !empty($voucher);
$action_url = $is_edit ? base_url('admin/voucher/update/' . $voucher->id) : base_url('admin/voucher/store');
?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i>
        Kembali</a>
</div>

<form action="<?= $action_url ?>" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
        value="<?= $this->security->get_csrf_hash() ?>">
    <div class="form-card" style="max-width: 600px;">
        <h3 style="font-family:'Outfit',sans-serif; color:var(--yellow); margin-bottom:20px;">
            <i class="fas fa-<?= $is_edit ? 'edit' : 'plus' ?>"></i>
            <?= $is_edit ? 'Edit' : 'Tambah' ?> Voucher
        </h3>

        <div class="form-group">
            <label>Kode Voucher</label>
            <input type="text" name="code" class="form-control"
                value="<?= $is_edit ? htmlspecialchars($voucher->code) : '' ?>"
                placeholder="misal: <?= strtoupper(get_setting('site_name', 'PEWEKA')) ?>10" required
                style="text-transform:uppercase;">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tipe Diskon</label>
                <select name="type" class="form-control" id="discountType" onchange="toggleMaxDiscount()">
                    <option value="percentage" <?= ($is_edit && $voucher->type == 'percentage') ? 'selected' : '' ?>>
                        Persentase (%)</option>
                    <option value="fixed" <?= ($is_edit && $voucher->type == 'fixed') ? 'selected' : '' ?>>Nominal Tetap
                        (Rp)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nilai Diskon</label>
                <input type="number" name="value" class="form-control" value="<?= $is_edit ? $voucher->value : '' ?>"
                    placeholder="10 atau 50000" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Minimum Order (Rp)</label>
                <input type="number" name="min_order" class="form-control"
                    value="<?= $is_edit ? $voucher->min_order : '0' ?>" min="0">
            </div>
            <div class="form-group" id="maxDiscountGroup">
                <label>Maks. Diskon (Rp)</label>
                <input type="number" name="max_discount" class="form-control"
                    value="<?= $is_edit ? $voucher->max_discount : '' ?>" placeholder="Opsional">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Kuota Penggunaan</label>
                <input type="number" name="quota" class="form-control" value="<?= $is_edit ? $voucher->quota : '100' ?>"
                    min="0" required>
            </div>
            <div class="form-group">
                <label>Tanggal Expired</label>
                <input type="date" name="expired_at" class="form-control"
                    value="<?= $is_edit ? $voucher->expired_at : '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($is_edit && $voucher->is_active) ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= ($is_edit && !$voucher->is_active) ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 15px;">
            <i class="fas fa-save"></i>
            <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Voucher' ?>
        </button>
    </div>
</form>

<script>
    function toggleMaxDiscount() {
        var type = document.getElementById('discountType').value;
        document.getElementById('maxDiscountGroup').style.display = type === 'percentage' ? 'block' : 'none';
    }
    toggleMaxDiscount();
</script>