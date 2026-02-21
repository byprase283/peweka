<div class="card">
    <div class="card-header">
        <h3>
            <?= $title ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php
        $url = $category ? 'admin/category/update/' . $category->id : 'admin/category/store';
        ?>

        <?= form_open($url) ?>
        <div class="form-group mb-3">
            <label>Nama Kategori</label>
            <input type="text" name="name" class="form-control" value="<?= $category ? $category->name : '' ?>" required
                onkeyup="createSlug(this.value)">
        </div>

        <div class="form-group mb-3">
            <label>Slug (URL Friendly)</label>
            <input type="text" name="slug" id="slug" class="form-control"
                value="<?= $category ? $category->slug : '' ?>" required readonly>
            <small class="text-muted">Slug dibuat otomatis dari nama kategori.</small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary">Batal</a>
        </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    function createSlug(text) {
        const slug = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    }
</script>