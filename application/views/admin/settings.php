<div class="form-card">
    <form action="<?= base_url('admin/settings_update') ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-4" style="font-family: 'Outfit', sans-serif; color: var(--yellow);">Identitas Situs</h4>
                <div class="form-group">
                    <label>Nama Produk / Toko</label>
                    <input type="text" name="site_name" class="form-control"
                        value="<?= htmlspecialchars($settings->site_name) ?>" required>
                </div>
                <div class="form-group">
                    <label>Logo Situs</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= base_url('assets/img/' . $settings->site_logo) ?>" alt="Logo"
                            style="height: 50px; background: #fff; padding: 5px; border-radius: 5px;">
                        <input type="file" name="site_logo" class="form-control">
                    </div>
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo. Format: JPG, PNG, SVG, WebP
                        (Maks 2MB)</small>
                </div>
                <div class="form-group">
                    <label>Tentang Toko</label>
                    <textarea name="site_about" class="form-control"
                        rows="5"><?= htmlspecialchars($settings->site_about) ?></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <h4 class="mb-4" style="font-family: 'Outfit', sans-serif; color: var(--yellow);">Kontak & Media Sosial
                </h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Instagram URL</label>
                        <input type="text" name="instagram_url" class="form-control"
                            value="<?= htmlspecialchars($settings->instagram_url) ?>"
                            placeholder="https://instagram.com/username">
                    </div>
                    <div class="form-group">
                        <label>Facebook URL</label>
                        <input type="text" name="facebook_url" class="form-control"
                            value="<?= htmlspecialchars($settings->facebook_url) ?>"
                            placeholder="https://facebook.com/page">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control"
                            value="<?= htmlspecialchars($settings->whatsapp_number) ?>" placeholder="628123456789">
                    </div>
                    <div class="form-group">
                        <label>Email Toko</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($settings->email) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="address" class="form-control"
                        rows="3"><?= htmlspecialchars($settings->address) ?></textarea>
                </div>
            </div>
        </div>

        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-md-12">
                <h4 class="mb-4" style="font-family: 'Outfit', sans-serif; color: var(--yellow);">Tampilan & Tema</h4>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Warna Utama (Primary)</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" name="theme_color" class="form-control"
                            style="width: 60px; height: 45px; padding: 5px;"
                            value="<?= $settings->theme_color ?: '#FFD700' ?>">
                        <input type="text" class="form-control" value="<?= $settings->theme_color ?: '#FFD700' ?>"
                            readonly>
                    </div>
                    <small class="text-muted">Akan mengubah aksen warna di seluruh situs (Tombol, Link, Header,
                        dll)</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Font Judul (Heading)</label>
                    <select name="theme_font_heading" class="form-control">
                        <option value="Outfit" <?= $settings->theme_font_heading == 'Outfit' ? 'selected' : '' ?>>Outfit
                            (Default)</option>
                        <option value="Inter" <?= $settings->theme_font_heading == 'Inter' ? 'selected' : '' ?>>Inter
                        </option>
                        <option value="'Roboto', sans-serif" <?= $settings->theme_font_heading == "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto</option>
                        <option value="'Montserrat', sans-serif" <?= $settings->theme_font_heading == "'Montserrat', sans-serif" ? 'selected' : '' ?>>Montserrat</option>
                        <option value="'Playfair Display', serif" <?= $settings->theme_font_heading == "'Playfair Display', serif" ? 'selected' : '' ?>>Playfair Display</option>
                        <option value="'Poppins', sans-serif" <?= $settings->theme_font_heading == "'Poppins', sans-serif" ? 'selected' : '' ?>>Poppins</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Font Isi (Body)</label>
                    <select name="theme_font_body" class="form-control">
                        <option value="Inter" <?= $settings->theme_font_body == 'Inter' ? 'selected' : '' ?>>Inter
                            (Default)</option>
                        <option value="Outfit" <?= $settings->theme_font_body == 'Outfit' ? 'selected' : '' ?>>Outfit
                        </option>
                        <option value="'Roboto', sans-serif" <?= $settings->theme_font_body == "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto</option>
                        <option value="'Open Sans', sans-serif" <?= $settings->theme_font_body == "'Open Sans', sans-serif" ? 'selected' : '' ?>>Open Sans</option>
                        <option value="'Lato', sans-serif" <?= $settings->theme_font_body == "'Lato', sans-serif" ? 'selected' : '' ?>>Lato</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>