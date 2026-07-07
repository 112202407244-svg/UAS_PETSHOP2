<?php $isAdmin = (($_SESSION['user_role'] ?? '') === 'admin'); ?>

<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($title ?? 'Data Produk') ?></h1>
        <p>Kelola produk pet shop dan tambahkan item ke keranjang belanja.</p>
    </div>

    <?php if ($isAdmin): ?>
        <a class="btn btn-primary" href="<?= BASE_URL ?>index.php?url=product/create">+ Tambah Produk</a>
    <?php endif; ?>
</div>

<div class="content-card table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $i => $product): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="product-inline">
                                    <div class="product-thumb">
                                        <?php if (!empty($product['image'])): ?>
                                            <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                        <?php else: ?>
                                            <span class="product-thumb-placeholder">No Image</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($product['name']) ?></strong>
                                        <div class="muted-text">Berat: <?= number_format((int) ($product['weight'] ?? 0), 0, ',', '.') ?> gram</div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                            <td>Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></td>
                            <td><?= (int) $product['stock'] ?></td>
                            <td><?= (int) $product['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
                            <td>
                                <div class="page-actions">
                                    <?php if (!$isAdmin): ?>
                                        <form method="POST" action="<?= BASE_URL ?>index.php?url=cart/add/<?= (int) $product['id'] ?>">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
                                                + Keranjang
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($isAdmin): ?>
                                        <a class="btn btn-secondary btn-sm" href="<?= BASE_URL ?>index.php?url=product/edit/<?= (int) $product['id'] ?>">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>index.php?url=product/delete/<?= (int) $product['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="muted-text">Belum ada produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
