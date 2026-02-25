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
                        <img src="<?= base_url('assets/img/' . ($settings->site_logo ?: 'logo.png')) ?>" alt="Logo"
                            style="height: 50px; background: #fff; padding: 5px; border-radius: 5px;">
                        <input type="file" name="site_logo" class="form-control">
                    </div>
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo. Format: JPG, PNG, SVG, WebP
                        (Maks 2MB)</small>
                </div>
                <div class="form-group">
                    <label>Favicon Situs</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= base_url('assets/img/' . ($settings->site_favicon ?: 'favicon.png')) ?>"
                            alt="Favicon"
                            style="height: 32px; width: 32px; background: #fff; padding: 2px; border-radius: 3px;">
                        <input type="file" name="site_favicon" class="form-control">
                    </div>
                    <small class="text-muted">Ikon kecil di tab browser. Format: ICO, PNG, JPG (Maks 1MB). Sistem akan
                        otomatis memperkecil ukuran.</small>
                </div>
                <div class="form-group">
                    <label>Tentang Toko (Halaman Utama)</label>
                    <textarea name="site_about" class="form-control"
                        rows="4"><?= htmlspecialchars($settings->site_about) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Deskripsi SEO Website</label>
                    <textarea name="site_description" class="form-control" rows="3"
                        placeholder="Deskripsi singkat untuk pencarian Google..."><?= htmlspecialchars($settings->site_description) ?></textarea>
                    <small class="text-muted">Deskripsi ini akan muncul di mesin pencari (Meta Description).</small>
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

            <!-- First Row: Preset & Accent -->
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="d-block mb-2">Preset Tema</label>
                    <select name="theme_preset" id="themePreset"
                        class="form-control bg-dark text-white border-secondary" onchange="toggleCustomColors()">
                        <option value="peweka-gold" <?= $settings->theme_preset == 'peweka-gold' ? 'selected' : '' ?>>
                            Peweka Gold (Default)</option>
                        <option value="midnight-ocean" <?= $settings->theme_preset == 'midnight-ocean' ? 'selected' : '' ?>>Midnight Ocean</option>
                        <option value="forest-emerald" <?= $settings->theme_preset == 'forest-emerald' ? 'selected' : '' ?>>Forest Emerald</option>
                        <option value="rose-velvet" <?= $settings->theme_preset == 'rose-velvet' ? 'selected' : '' ?>>Rose Velvet</option>
                        <option value="modern-light" <?= $settings->theme_preset == 'modern-light' ? 'selected' : '' ?>>Modern Light</option>
                        <option value="custom" <?= $settings->theme_preset == 'custom' ? 'selected' : '' ?>>Custom Theme</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="d-block mb-2">Warna Utama (Accent)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-dark border-secondary p-1">
                                <input type="color" name="theme_color"
                                    style="width: 40px; height: 35px; border: none; background: none; cursor: pointer;"
                                    value="<?= $settings->theme_color ?: '#FFD700' ?>"
                                    oninput="this.parentElement.parentElement.nextElementSibling.value = this.value">
                            </div>
                        </div>
                        <input type="text" class="form-control bg-dark text-white border-secondary border-left-0"
                            value="<?= $settings->theme_color ?: '#FFD700' ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="d-block mb-2">Font Judul (Heading)</label>
                    <select name="theme_font_heading" class="form-control bg-dark text-white border-secondary">
                        <option value="Outfit" <?= $settings->theme_font_heading == 'Outfit' ? 'selected' : '' ?>>Outfit (Default)</option>
                        <option value="Inter" <?= $settings->theme_font_heading == 'Inter' ? 'selected' : '' ?>>Inter</option>
                        <option value="'Roboto', sans-serif" <?= $settings->theme_font_heading == "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto</option>
                        <option value="'Montserrat', sans-serif" <?= $settings->theme_font_heading == "'Montserrat', sans-serif" ? 'selected' : '' ?>>Montserrat</option>
                        <option value="'Poppins', sans-serif" <?= $settings->theme_font_heading == "'Poppins', sans-serif" ? 'selected' : '' ?>>Poppins</option>
                    </select>
                </div>
            </div>

            <!-- Second Row: Custom Colors & Font Body -->
            <div id="customColorFields" class="col-md-8"
                style="<?= $settings->theme_preset == 'custom' ? 'display: block;' : 'display: none !important;' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="d-block mb-2">Warna Background</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-dark border-secondary p-1">
                                        <input type="color" name="theme_bg_color"
                                            style="width: 40px; height: 35px; border: none; background: none; cursor: pointer;"
                                            value="<?= $settings->theme_bg_color ?: '#0a0a0a' ?>"
                                            oninput="this.parentElement.parentElement.nextElementSibling.value = this.value">
                                    </div>
                                </div>
                                <input type="text"
                                    class="form-control bg-dark text-white border-secondary border-left-0"
                                    value="<?= $settings->theme_bg_color ?: '#0a0a0a' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="d-block mb-2">Warna Teks Utama</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-dark border-secondary p-1">
                                        <input type="color" name="theme_text_color"
                                            style="width: 40px; height: 35px; border: none; background: none; cursor: pointer;"
                                            value="<?= $settings->theme_text_color ?: '#ffffff' ?>"
                                            oninput="this.parentElement.parentElement.nextElementSibling.value = this.value">
                                    </div>
                                </div>
                                <input type="text"
                                    class="form-control bg-dark text-white border-secondary border-left-0"
                                    value="<?= $settings->theme_text_color ?: '#ffffff' ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label class="d-block mb-2">Font Isi (Body)</label>
                    <select name="theme_font_body" class="form-control bg-dark text-white border-secondary">
                        <option value="Inter" <?= $settings->theme_font_body == 'Inter' ? 'selected' : '' ?>>Inter (Default)</option>
                        <option value="Outfit" <?= $settings->theme_font_body == 'Outfit' ? 'selected' : '' ?>>Outfit</option>
                        <option value="'Roboto', sans-serif" <?= $settings->theme_font_body == "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto</option>
                        <option value="'Open Sans', sans-serif" <?= $settings->theme_font_body == "'Open Sans', sans-serif" ? 'selected' : '' ?>>Open Sans</option>
                        <option value="'Lato', sans-serif" <?= $settings->theme_font_body == "'Lato', sans-serif" ? 'selected' : '' ?>>Lato</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pengaturan Diskon Global -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-md-12">
                <h4 class="mb-4" style="font-family: 'Outfit', sans-serif; color: var(--yellow);">Pengaturan Diskon Global</h4>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Diskon (Muncul di Halaman Produk)</label>
                    <input type="text" name="global_discount_name" class="form-control" 
                           value="<?= htmlspecialchars($settings->global_discount_name ?? '') ?>" 
                           placeholder="Contoh: Ramadhan Sale, End of Year Sale">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Persentase Diskon (%)</label>
                    <input type="number" name="global_discount_percent" class="form-control" 
                           value="<?= (int)($settings->global_discount_percent ?? 0) ?>" 
                           min="0" max="100">
                    <small class="text-muted">Set ke 0 untuk menonaktifkan diskon global.</small>
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

<script>
    function toggleCustomColors() {
        var preset = document.getElementById('themePreset').value;
        var customFields = document.getElementById('customColorFields');
        if (preset === 'custom') {
            customFields.style.setProperty('display', 'block', 'important');
        } else {
            customFields.style.setProperty('display', 'none', 'important');
        }
    }
</script>