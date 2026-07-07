<?php $isAdmin = (($_SESSION['user_role'] ?? '') === 'admin'); ?>

<div class="page-header">
    <div>
        <h1>Detail Produk</h1>
        <p>Informasi lengkap produk dan status ketersediaannya.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Kembali</a>
        <?php if ($isAdmin): ?>
            <a class="btn btn-primary" href="<?= BASE_URL ?>index.php?url=product/edit/<?= (int) $product['id'] ?>">Edit Produk</a>
        <?php endif; ?>
    </div>
</div>

<div class="content-card">
    <div style="display:grid; grid-template-columns:minmax(220px, 320px) minmax(0, 1fr); gap:24px; align-items:start;">
        <div>
            <?php if (!empty($product['image'])): ?>
                <img src="<?= UPLOAD_URL . htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%; border-radius:16px; border:1px solid #e2e8f0;">
            <?php else: ?>
                <div class="product-thumb-placeholder" style="min-height:280px; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0; border-radius:16px;">
                    Tidak ada gambar
                </div>
            <?php endif; ?>
        </div>

        <div class="summary-list">
            <div>
                <span>ID Produk</span>
                <strong><?= (int) $product['id'] ?></strong>
            </div>
            <div>
                <span>Nama Produk</span>
                <strong><?= htmlspecialchars($product['name']) ?></strong>
            </div>
            <div>
                <span>Slug</span>
                <strong><?= htmlspecialchars($product['slug']) ?></strong>
            </div>
            <div>
                <span>Kategori</span>
                <strong><?= htmlspecialchars($product['category_name'] ?? '-') ?></strong>
            </div>
            <div>
                <span>Harga</span>
                <strong>Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></strong>
            </div>
            <div>
                <span>Stok</span>
                <strong><?= (int) $product['stock'] ?></strong>
            </div>
            <div>
                <span>Berat</span>
                <strong><?= number_format((int) $product['weight'], 0, ',', '.') ?> gram</strong>
            </div>
            <div>
                <span>Status</span>
                <strong><?= (int) $product['is_active'] === 1 ? 'Aktif' : 'Tidak Aktif' ?></strong>
            </div>
            <div>
                <span>Dibuat</span>
                <strong><?= date('d-m-Y H:i', strtotime($product['created_at'])) ?></strong>
            </div>
            <div>
                <span>Terakhir Diubah</span>
                <strong><?= date('d-m-Y H:i', strtotime($product['updated_at'])) ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <h2 class="section-title">Deskripsi</h2>
    <div><?= nl2br(htmlspecialchars($product['description'])) ?></div>
</div>
