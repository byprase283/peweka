<div class="form-card" style="max-width: 500px; margin: 0 auto;">
    <h4 class="mb-4" style="font-family: 'Outfit', sans-serif; color: var(--yellow);">
        <i class="fas fa-key"></i> Ganti Password Admin
    </h4>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger"
            style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= validation_errors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/update_password') ?>" method="post">
        <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password" class="form-control" required
                placeholder="Masukkan password saat ini">
        </div>

        <div class="form-group mt-3">
            <label>Password Baru</label>
            <input type="password" name="new_password" class="form-control" required placeholder="Minimal 5 karakter">
        </div>

        <div class="form-group mt-3">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" class="form-control" required
                placeholder="Ulangi password baru">
        </div>

        <div class="mt-4 pt-3 border-top border-secondary">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save"></i> Perbarui Password
            </button>
        </div>
    </form>
</div>