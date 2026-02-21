<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Daftar Kategori</h3>
        <a href="<?= base_url('admin/category/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (empty($categories)): ?>
            <div class="text-center py-5">
                <p class="text-muted">Belum ada kategori.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>#
                                    <?= $category->id ?>
                                </td>
                                <td><strong>
                                        <?= htmlspecialchars($category->name) ?>
                                    </strong></td>
                                <td>
                                    <?= htmlspecialchars($category->slug) ?>
                                </td>
                                <td>
                                    <?= date('d M Y H:i', strtotime($category->created_at)) ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/category/edit/' . $category->id) ?>"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/category/delete/' . $category->id) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>