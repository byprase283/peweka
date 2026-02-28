<style>
    .variant-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        background: rgba(255, 255, 255, 0.03);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        align-items: flex-end;
    }

    .variant-row .form-group {
        margin-bottom: 0;
        flex: 1;
        min-width: 120px;
    }

    .variant-row .form-group:nth-child(3) {
        flex: 0 0 60px;
        min-width: 60px;
    }

    .remove-variant {
        background: #ff4d4d;
        color: white;
        border: none;
        border-radius: 6px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 0;
    }

    .remove-variant:hover {
        background: #ff3333;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .variant-row {
            flex-direction: column;
            align-items: stretch;
        }

        .variant-row .form-group {
            flex: none;
            width: 100%;
        }

        .remove-variant {
            width: 100%;
            margin-top: 10px;
        }
    }
</style>
<!-- Product Form (Create/Edit) -->
<?php
$is_edit = !empty($product);
$action_url = $is_edit ? base_url('admin/product/update/' . $product->id) : base_url('admin/product/store');
?>

<div style="margin-bottom: 20px;">
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i>
        Kembali</a>
</div>

<form action="<?= $action_url ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
        value="<?= $this->security->get_csrf_hash() ?>">
    <div class="form-card" style="max-width: 800px;">
        <h3 style="font-family:'Outfit',sans-serif; color:var(--yellow); margin-bottom:20px;">
            <i class="fas fa-<?= $is_edit ? 'edit' : 'plus' ?>"></i>
            <?= $is_edit ? 'Edit' : 'Tambah' ?> Produk
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" class="form-control"
                    value="<?= $is_edit ? htmlspecialchars($product->name) : '' ?>" required>
            </div>
            <div class="form-group" style="display: none;">
                <label>Harga (Rp)</label>
                <input type="text" name="price" class="form-control"
                    value="<?= $is_edit ? (int) $product->price : '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= ($is_edit && isset($product->category_id) && $product->category_id == $cat->id) ? 'selected' : '' ?>>
                                <?= $cat->name ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" <?= ($is_edit && $product->is_active) ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($is_edit && !$product->is_active) ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control"
                required><?= $is_edit ? htmlspecialchars($product->description) : '' ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Gambar Utama</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if ($is_edit && $product->image): ?>
                    <img src="<?= base_url('assets/img/products/' . $product->image) ?>"
                        style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin-top:8px; border:1px solid rgba(255,255,255,0.1);"
                        onerror="this.style.display='none'">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Galeri Tambahan (Bisa pilih banyak)</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                <?php if ($is_edit && !empty($gallery_images)): ?>
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;">
                        <?php foreach ($gallery_images as $img): ?>
                            <div style="position:relative; width:80px; height:80px;">
                                <img src="<?= base_url('assets/img/products/' . $img->image) ?>"
                                    style="width:100%; height:100%; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
                                <a href="<?= base_url('admin/product/delete_image/' . $img->id . '/' . $product->id) ?>"
                                    onclick="return confirm('Hapus gambar ini?')"
                                    style="position:absolute; top:-5px; right:-5px; background:red; color:white; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:10px; text-decoration:none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Variants -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h4 style="font-family:'Outfit',sans-serif; color:var(--yellow);">Varian (Ukuran & Warna)</h4>
                <button type="button" class="btn btn-outline btn-sm" onclick="addVariantRow()">
                    <i class="fas fa-plus"></i> Tambah Varian
                </button>
            </div>

            <div id="variantContainer">
                <?php if ($is_edit && !empty($variants)): ?>
                    <?php foreach ($variants as $v): ?>
                        <div class="variant-row">
                            <div class="form-group">
                                <label
                                    style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Ukuran</label>
                                <select name="variant_size[]" class="form-control">
                                    <option value="XS" <?= $v->size == 'XS' ? 'selected' : '' ?>>XS</option>
                                    <option value="S" <?= $v->size == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= $v->size == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= $v->size == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= $v->size == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= $v->size == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                    <option value="ALL" <?= $v->size == 'ALL' ? 'selected' : '' ?>>ALL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label
                                    style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Warna</label>
                                <input type="text" name="variant_color[]" class="form-control"
                                    value="<?= htmlspecialchars($v->color) ?>" placeholder="Warna">
                            </div>
                            <div class="form-group">
                                <label
                                    style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Hex</label>
                                <input type="color" name="variant_hex[]" class="form-control" value="<?= $v->color_hex ?>"
                                    style="padding:5px; height:40px;">
                            </div>
                            <div class="form-group">
                                <label
                                    style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Stok</label>
                                <input type="number" name="variant_stock[]" class="form-control" value="<?= $v->stock ?>"
                                    placeholder="Stok" min="0">
                            </div>
                            <div class="form-group">
                                <label
                                    style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Harga</label>
                                <input type="text" name="variant_price[]" class="form-control" value="<?= (int) $v->price ?>"
                                    placeholder="Harga">
                            </div>
                            <button type="button" class="remove-variant" onclick="this.parentElement.remove()"
                                title="Hapus Varian">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="variant-row">
                        <div class="form-group">
                            <label
                                style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Ukuran</label>
                            <select name="variant_size[]" class="form-control">
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M" selected>M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="ALL">ALL</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label
                                style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Warna</label>
                            <input type="text" name="variant_color[]" class="form-control" placeholder="Warna"
                                value="Hitam">
                        </div>
                        <div class="form-group">
                            <label
                                style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Hex</label>
                            <input type="color" name="variant_hex[]" class="form-control" value="#1a1a1a"
                                style="padding:5px; height:40px;">
                        </div>
                        <div class="form-group">
                            <label
                                style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Stok</label>
                            <input type="number" name="variant_stock[]" class="form-control" placeholder="Stok" value="10"
                                min="0">
                        </div>
                        <div class="form-group">
                            <label
                                style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Harga</label>
                            <input type="text" name="variant_price[]" class="form-control" placeholder="Harga">
                        </div>
                        <button type="button" class="remove-variant" onclick="this.parentElement.remove()"
                            title="Hapus Varian">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 25px;">
            <i class="fas fa-save"></i>
            <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Produk' ?>
        </button>
    </div>
</form>

<script>
    function addVariantRow() {
        var html = '<div class="variant-row">' +
            '<div class="form-group"><label style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Ukuran</label><select name="variant_size[]" class="form-control">' +
            '<option value="XS">XS</option><option value="S">S</option><option value="M" selected>M</option><option value="L">L</option>' +
            '<option value="XL">XL</option><option value="XXL">XXL</option><option value="ALL">ALL</option></select></div>' +
            '<div class="form-group"><label style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Warna</label><input type="text" name="variant_color[]" class="form-control" placeholder="Warna"></div>' +
            '<div class="form-group"><label style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Hex</label><input type="color" name="variant_hex[]" class="form-control" value="#1a1a1a" style="padding:5px;height:40px;"></div>' +
            '<div class="form-group"><label style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Stok</label><input type="number" name="variant_stock[]" class="form-control" placeholder="Stok" min="0" value="10"></div>' +
            '<div class="form-group"><label style="font-size: 0.75rem; color: var(--gray-400); margin-bottom: 5px; display: block;">Harga</label><input type="text" name="variant_price[]" class="form-control" placeholder="Harga"></div>' +
            '<button type="button" class="remove-variant" onclick="this.parentElement.remove()" title="Hapus Varian"><i class="fas fa-times"></i></button></div>';
        document.getElementById('variantContainer').insertAdjacentHTML('beforeend', html);
    }
</script>