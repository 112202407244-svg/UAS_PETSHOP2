<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($title ?? 'Data Kategori') ?></h1>
        <p>Kelola kategori produk yang digunakan pada katalog toko.</p>
    </div>
    <a class="btn btn-primary" href="<?= BASE_URL ?>index.php?url=category/create">+ Tambah Kategori</a>
</div>

<div class="content-card table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $i => $category): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= htmlspecialchars($category['slug']) ?></td>
                            <td>
                                <div class="page-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?= BASE_URL ?>index.php?url=category/edit/<?= (int) $category['id'] ?>">Edit</a>
                                    <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>index.php?url=category/delete/<?= (int) $category['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="muted-text">Belum ada kategori.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
