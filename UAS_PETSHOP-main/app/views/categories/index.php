<h2><?= htmlspecialchars($title ?? 'Data Kategori') ?></h2>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<p>
    <a href="<?= BASE_URL ?>index.php?url=category/create">+ Tambah Kategori</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $i => $category): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?url=category/edit/<?= $category['id'] ?>">Edit</a> |
                        <a href="<?= BASE_URL ?>index.php?url=category/delete/<?= $category['id'] ?>"
                           onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center;">Belum ada kategori.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>